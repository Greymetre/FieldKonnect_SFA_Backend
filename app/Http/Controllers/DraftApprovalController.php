<?php

namespace App\Http\Controllers;

use App\Models\LeadNote;
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

        $pending = LeadNote::whereIn('type', [LeadNote::TYPE_EMAIL, LeadNote::TYPE_LINKEDIN])
            ->whereNull('approved_at')
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
        $query = LeadNote::with(['lead:id,company_name,external_lead_id', 'createdby:id,name', 'approvedby:id,name'])
            ->where('type', $type);

        $status = $request->input('status', 'pending');
        if ($status === 'pending') {
            $query->whereNull('approved_at');
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
                'update_url' => route('draft-approvals.update', $draft),
                'approve_url' => route('draft-approvals.approve', $draft),
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
        $this->authorizeApproval();
        $this->ensureDraft($leadNote);

        $validated = $request->validate(['note' => ['required', 'string', 'max:10000']]);
        $leadNote->update(['note' => $validated['note']]);

        return response()->json(['success' => true, 'message' => 'Draft updated.']);
    }

    /** Approve (optionally with edited text in the same step). */
    public function approve(Request $request, LeadNote $leadNote)
    {
        $this->authorizeApproval();
        $this->ensureDraft($leadNote);

        $validated = $request->validate(['note' => ['nullable', 'string', 'max:10000']]);
        $leadNote->update(array_filter([
            'note' => $validated['note'] ?? null,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ], fn ($value) => $value !== null && $value !== ''));

        return response()->json(['success' => true, 'message' => 'Draft approved.']);
    }

    private function authorizeApproval(): void
    {
        abort_unless(Auth::user()->can('draft_approval'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    }

    private function ensureDraft(LeadNote $leadNote): void
    {
        abort_unless(in_array($leadNote->type, [LeadNote::TYPE_EMAIL, LeadNote::TYPE_LINKEDIN], true), Response::HTTP_NOT_FOUND);
    }
}
