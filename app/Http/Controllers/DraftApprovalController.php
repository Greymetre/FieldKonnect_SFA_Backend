<?php

namespace App\Http\Controllers;

use App\Models\LeadNote;
use App\Services\LeadDraftService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

/**
 * Lead Management > Draft Approval: review, edit and approve the Email and
 * LinkedIn drafts agents write on leads. Approved drafts are locked for agents.
 */
class DraftApprovalController extends Controller
{
    public function index()
    {
        $this->authorizeApproval();

        $pending = $this->visibleDrafts()
            ->whereNull('approved_at')
            ->whereNull('changes_requested_at')
            ->selectRaw('type, COUNT(*) as total')
            ->groupBy('type')
            ->pluck('total', 'type');

        return view('draft-approvals.index', [
            'pendingEmail' => (int) ($pending[LeadNote::TYPE_EMAIL] ?? 0),
            'pendingLinkedin' => (int) ($pending[LeadNote::TYPE_LINKEDIN] ?? 0),
        ]);
    }

    /** DataTables JSON for one tab (email / linkedin). */
    public function data(Request $request)
    {
        $this->authorizeApproval();

        $type = $request->input('type') === LeadNote::TYPE_LINKEDIN ? LeadNote::TYPE_LINKEDIN : LeadNote::TYPE_EMAIL;
        $query = $this->visibleDrafts()
            ->with(['lead:id,company_name,external_lead_id', 'createdby:id,name', 'approvedby:id,name', 'approver:id,name', 'contact:id,name,title,email', 'feedbacks.user:id,name'])
            ->where('type', $type);

        $status = $request->input('status', 'pending');
        if ($status === 'pending') {
            $query->whereNull('approved_at')->whereNull('changes_requested_at');
        } elseif ($status === 'changes') {
            // Feedback sent, waiting for the agent to update the draft.
            $query->whereNull('approved_at')->whereNotNull('changes_requested_at');
        } elseif ($status === 'approved') {
            $query->whereNotNull('approved_at');
        }

        $recordsTotal = (clone $query)->count();

        $search = trim((string) $request->input('search_text', ''));
        if ($search !== '') {
            $query->where(function ($searchQuery) use ($search) {
                $searchQuery->where('note', 'like', "%{$search}%")
                    ->orWhereHas('lead', fn ($leadQuery) => $leadQuery->where('company_name', 'like', "%{$search}%")
                        ->orWhere('external_lead_id', 'like', "%{$search}%"))
                    ->orWhereHas('createdby', fn ($userQuery) => $userQuery->where('name', 'like', "%{$search}%"));
            });
        }

        $recordsFiltered = (clone $query)->count();
        $start = max(0, (int) $request->input('start', 0));
        $length = min(100, max(1, (int) $request->input('length', 10)));

        $data = $query->orderByRaw('approved_at IS NOT NULL')->orderByDesc('created_at')
            ->skip($start)->take($length)->get()
            ->map(fn (LeadNote $draft) => [
                'id' => $draft->id,
                'note' => $draft->note,
                'company' => $draft->lead?->company_name ?: '-',
                'lead_code' => $draft->lead?->external_lead_id,
                'lead_url' => $draft->lead_id ? route('leads.show', $draft->lead_id) : null,
                'created_by' => $draft->createdby?->name ?: '-',
                'created_at' => $draft->created_at?->format('d M Y, h:i A'),
                'approved' => $draft->isApproved(),
                'approved_by' => $draft->approvedby?->name,
                'approved_at' => $draft->approved_at?->format('d M Y, h:i A'),
                'approver' => $draft->approver?->name,
                'state' => $draft->draftState(),
                'contact' => $draft->contact?->name,
                'contact_title' => $draft->contact?->title,
                'feedbacks' => $draft->feedbacks->map(fn ($item) => [
                    'text' => $item->feedback,
                    'by' => $item->user?->name ?: '-',
                    'at' => $item->created_at?->format('d M Y, h:i A'),
                ])->values(),
                'update_url' => route('draft-approvals.update', $draft),
                'approve_url' => route('draft-approvals.approve', $draft),
                'feedback_url' => route('draft-approvals.feedback', $draft),
            ]);

        return response()->json([
            'draw' => (int) $request->input('draw', 0),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    /** Edit the draft text (approval state is kept). */
    public function update(Request $request, LeadNote $leadNote)
    {
        $this->ensureDraft($leadNote);
        abort_unless($leadNote->canBeApprovedBy(Auth::user()), Response::HTTP_FORBIDDEN, 'This draft was sent to another approver.');

        $validated = $request->validate(['note' => ['required', 'string', 'max:10000']]);
        $leadNote->update(['note' => $validated['note']]);

        return response()->json(['success' => true, 'message' => 'Draft updated.']);
    }

    /** Approve (optionally with edited text in the same step). */
    public function approve(Request $request, LeadNote $leadNote)
    {
        $this->ensureDraft($leadNote);
        abort_unless($leadNote->canBeApprovedBy(Auth::user()), Response::HTTP_FORBIDDEN, 'This draft was sent to another approver.');

        $validated = $request->validate(['note' => ['nullable', 'string', 'max:10000']]);
        app(LeadDraftService::class)->approve($leadNote, Auth::user(), $validated['note'] ?? null);

        return response()->json(['success' => true, 'message' => 'Draft approved.']);
    }

    /** Send feedback to the agent: the draft goes back to them for changes. */
    public function feedback(Request $request, LeadNote $leadNote)
    {
        $this->ensureDraft($leadNote);
        abort_unless($leadNote->canBeApprovedBy(Auth::user()), Response::HTTP_FORBIDDEN, 'This draft was sent to another approver.');
        abort_if($leadNote->isApproved(), Response::HTTP_UNPROCESSABLE_ENTITY, 'This draft is already approved.');

        $validated = $request->validate(
            ['feedback' => ['required', 'string', 'max:2000']],
            ['feedback.required' => 'Please write what should be changed.']
        );
        app(LeadDraftService::class)->requestChanges($leadNote, Auth::user(), $validated['feedback']);

        return response()->json(['success' => true, 'message' => 'Feedback sent to '.($leadNote->createdby->name ?? 'the agent').'.']);
    }

    /** Approvers see the page: full access with draft_approval, else only drafts sent to them. */
    private function authorizeApproval(): void
    {
        abort_unless(
            Auth::user()->can('draft_approval') || LeadNote::where('approver_id', Auth::id())->exists(),
            Response::HTTP_FORBIDDEN,
            '403 Forbidden'
        );
    }

    private function visibleDrafts()
    {
        return LeadNote::whereIn('type', [LeadNote::TYPE_EMAIL, LeadNote::TYPE_LINKEDIN])
            ->when(! Auth::user()->can('draft_approval'), fn ($query) => $query->where('approver_id', Auth::id()));
    }

    private function ensureDraft(LeadNote $leadNote): void
    {
        abort_unless(in_array($leadNote->type, [LeadNote::TYPE_EMAIL, LeadNote::TYPE_LINKEDIN], true), Response::HTTP_NOT_FOUND);
    }
}
