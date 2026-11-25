<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CallLog;
use App\Models\Lead;
use App\Models\LeadContact;
use App\Models\LeadNote;
use App\Models\LeadTask;
use App\Models\Status;
use App\Models\User;
use App\Services\LeadDraftService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

/**
 * Mobile app version of the CRM lead flow (Lead Management > Leads > lead page):
 * contacts with calling progress, call history, tasks, Email / LinkedIn drafts
 * with approval, and files. Every endpoint uses the same lead visibility as the
 * CRM: admins see all leads, others the leads created by / assigned to their team.
 */
class LeadFlowController extends Controller
{
    private const DRAFT_TYPES = [LeadNote::TYPE_EMAIL, LeadNote::TYPE_LINKEDIN];

    /** Everything the lead detail screen needs beyond api/leadDetails. */
    public function show(Request $request): JsonResponse
    {
        $request->validate(['lead_id' => ['required', 'integer']]);
        $user = $request->user();
        $lead = $this->accessibleLead($user, (int) $request->lead_id);
        $lead->load('contacts');

        // Per-contact calling summary (calls only count when placed to that contact).
        $callsByContact = CallLog::where('lead_id', $lead->id)->whereNotNull('lead_contact_id')
            ->with('feedbackStatus:id,display_name,status_name,module')
            ->orderByDesc('started_at')->get()
            ->groupBy('lead_contact_id');
        $contacts = $lead->contacts->map(function (LeadContact $contact) use ($callsByContact) {
            $calls = $callsByContact->get($contact->id, collect());
            $last = $calls->first();

            return [
                'id' => $contact->id,
                'external_contact_id' => $contact->external_contact_id,
                'name' => $contact->name,
                'title' => $contact->title,
                'phone_number' => $contact->phone_number,
                'email' => $contact->email,
                'linkedin_url' => $this->linkedinUrl($contact->url),
                'url' => $contact->url,
                'calls' => $calls->count(),
                'last_call_at' => $last?->started_at?->format('d M Y, h:i A'),
                'last_status' => $last ? $this->callStatusLabel($last) : null,
            ];
        })->values();
        $dialable = $contacts->filter(fn ($contact) => trim((string) $contact['phone_number']) !== '');
        $called = $dialable->filter(fn ($contact) => $contact['calls'] > 0);
        $nextContact = $dialable->first(fn ($contact) => $contact['calls'] === 0);

        $callHistory = CallLog::where('lead_id', $lead->id)
            ->with(['user:id,name', 'contact:id,name', 'feedbackStatus:id,display_name,status_name,module'])
            ->orderByDesc('started_at')->limit(30)->get()
            ->map(fn (CallLog $call) => [
                'id' => $call->id,
                'contact_name' => $call->contact?->name,
                'number' => $call->number,
                'agent' => $call->user?->name,
                'direction' => $call->direction,
                'connected' => $this->isConnected($call),
                'duration' => (int) $call->duration,
                'status' => $this->callStatusLabel($call),
                'status_key' => $call->feedbackStatus?->status_name,
                'remark' => $call->remark,
                'follow_up_at' => $call->follow_up_at?->format('d M Y, h:i A'),
                'started_at' => $call->started_at?->format('d M Y, h:i A'),
                'has_recording' => ! empty($call->recording_url),
            ]);

        $tasks = LeadTask::with(['assignUser:id,name', 'createdby:id,name'])->where('lead_id', $lead->id)
            ->orderByRaw("status = 'completed'")->orderBy('date')->orderBy('time')->get();
        $tasks->each(fn (LeadTask $task) => $task->markSeenBy($user->id));
        $tasks = $tasks->map(fn (LeadTask $task) => [
            'id' => $task->id,
            'description' => strip_tags((string) $task->description),
            'date' => $task->date ? date('d M Y', strtotime($task->date)) : null,
            'time' => $task->time ? date('h:i A', strtotime($task->time)) : null,
            'overdue' => $task->status !== 'completed' && $task->date && $task->date < now()->toDateString(),
            'priority' => $task->priority,
            'status' => $task->status,
            'status_label' => $task->statusLabel(),
            'assigned_to' => $task->assignUser?->name,
            'assigned_to_id' => $task->assigned_to,
            'created_by' => $task->createdby?->name,
            'seen_at' => $task->seen_at?->format('d M, h:i A'),
            'opened_at' => $task->opened_at?->format('d M, h:i A'),
            'completed_at' => $task->completed_at?->format('d M, h:i A'),
            'remark' => $task->remark,
            'can_change_status' => in_array((int) $user->id, [(int) $task->assigned_to, (int) $task->created_by], true) || $this->isAdmin($user),
        ]);

        $drafts = LeadNote::with(['createdby:id,name', 'approver:id,name', 'approvedBy:id,name', 'rejectedby:id,name', 'contact:id,name,title', 'feedbacks.user:id,name'])
            ->where('lead_id', $lead->id)->whereIn('type', self::DRAFT_TYPES)
            ->latest()->get()
            ->map(fn (LeadNote $draft) => $this->draftPayload($draft, $user))
            ->groupBy('type');

        $files = $lead->getMedia('lead_file')->sortByDesc('id')->map(fn (Media $media) => $this->filePayload($media))->values();

        return response()->json([
            'success' => true,
            'data' => [
                'lead_id' => $lead->id,
                'external_lead_id' => $lead->external_lead_id,
                'calling' => [
                    'dialable' => $dialable->count(),
                    'called' => $called->count(),
                    'next_contact' => $nextContact ? ['id' => $nextContact['id'], 'name' => $nextContact['name'], 'phone' => $nextContact['phone_number']] : null,
                ],
                'contacts' => $contacts,
                'call_history' => $callHistory,
                'tasks' => $tasks,
                'drafts' => [
                    'email' => $drafts->get(LeadNote::TYPE_EMAIL, collect())->values(),
                    'linkedin' => $drafts->get(LeadNote::TYPE_LINKEDIN, collect())->values(),
                ],
                'files' => $files,
            ],
        ]);
    }

    /** Options for the post-call remark sheet: call outcome, lead type, follow-up channels. */
    public function callOptions(): JsonResponse
    {
        $order = array_flip(Status::LEAD_CALL_OUTCOMES);
        $callStatuses = Status::where('module', Status::MODULE_LEAD_CALL_FEEDBACK)->where('active', 'Y')
            ->get(['id', 'status_name', 'display_name'])
            ->sortBy(fn ($status) => $order[$status->status_name] ?? 99)
            ->map(fn ($status) => [
                'id' => $status->id,
                'key' => $status->status_name,
                'label' => $status->display_name ?: $status->status_name,
                // The app shows the date/time picker / channel choice for these.
                'needs_follow_up' => $status->status_name === Status::LEAD_CALL_BACK,
                'asks_channels' => $status->status_name === Status::LEAD_CALL_DONE,
                'not_reached' => in_array($status->status_name, ['lead_call_no_response', 'lead_call_switch_off', 'lead_call_wrong_number'], true),
            ])->values();
        $leadTypes = collect([['id' => 0, 'label' => 'Pending']])->merge(
            Status::where('module', 'LeadStatus')->where('active', 'Y')->orderBy('id')->get(['id', 'display_name'])
                ->map(fn ($status) => ['id' => $status->id, 'label' => $status->display_name])
        )->values();

        return response()->json([
            'success' => true,
            'data' => [
                'call_statuses' => $callStatuses,
                'lead_types' => $leadTypes,
                'channels' => [
                    ['key' => 'whatsapp', 'label' => 'WhatsApp'],
                    ['key' => 'linkedin', 'label' => 'LinkedIn'],
                    ['key' => 'email', 'label' => 'Email'],
                    ['key' => 'none', 'label' => 'No follow-up needed'],
                ],
            ],
        ]);
    }

    /** Users a draft can be sent to for approval (active internal users, not the sender). */
    public function draftApprovers(Request $request): JsonResponse
    {
        $users = User::where('active', 'Y')
            ->whereDoesntHave('roles', fn ($query) => $query->whereIn('id', config('constants.customer_roles')))
            ->where('id', '!=', $request->user()->id)
            ->orderBy('name')->get(['id', 'name']);

        return response()->json(['success' => true, 'data' => $users]);
    }

    /** Create or edit an Email / LinkedIn draft; it goes to the chosen approver. */
    public function saveDraft(Request $request): JsonResponse
    {
        $user = $request->user();
        $validated = $request->validate([
            'draft_id' => ['nullable', 'integer'],
            'lead_id' => ['required_without:draft_id', 'integer'],
            'type' => ['required_without:draft_id', 'in:'.implode(',', self::DRAFT_TYPES)],
            'note' => ['required', 'string', 'max:10000'],
            'approver_id' => ['required', 'integer', 'exists:users,id'],
            'lead_contact_id' => ['required', 'integer'],
        ], [
            'approver_id.required' => 'Please choose who should approve this draft.',
            'lead_contact_id.required' => 'Please choose the contact this draft is for.',
        ]);
        $service = app(LeadDraftService::class);

        $draft = ! empty($validated['draft_id']) ? LeadNote::whereIn('type', self::DRAFT_TYPES)->findOrFail($validated['draft_id']) : null;
        $lead = $this->accessibleLead($user, (int) ($draft->lead_id ?? $validated['lead_id']));
        $approverId = (int) $validated['approver_id'];
        $contactId = $service->validContactId($validated['lead_contact_id'], $lead->id);

        if ($draft) {
            abort_if($draft->isLockedFor($user), Response::HTTP_UNPROCESSABLE_ENTITY, $draft->isRejected() ? 'This draft was rejected and can no longer be edited. Please write a new draft.' : 'This draft is approved and can no longer be edited.');
            $approverChanged = (int) $draft->approver_id !== $approverId;
            $wasSentBack = $draft->needsChanges();
            $draft->update(['note' => $validated['note'], 'approver_id' => $approverId, 'lead_contact_id' => $contactId]);
            if ($wasSentBack) {
                // Edited after the approver's feedback: back to the approver.
                $service->afterAgentEdit($draft, $user);
                $message = 'Draft updated and sent back to '.($draft->approver->name ?? 'the approver').' for approval.';
            } else {
                if ($approverChanged && ! $draft->isDecided()) {
                    $this->notifyApprover($draft, $user);
                }
                $message = 'Draft updated.';
            }
        } else {
            $draft = LeadNote::create([
                'type' => $validated['type'],
                'note' => $validated['note'],
                'lead_id' => $lead->id,
                'lead_contact_id' => $contactId,
                'created_by' => $user->id,
                'approver_id' => $approverId,
            ]);
            $this->notifyApprover($draft, $user);
            $message = 'Draft sent to '.($draft->approver->name ?? 'the approver').' for approval.';
        }

        return response()->json(['success' => true, 'message' => $message, 'data' => $this->draftPayload($draft->fresh(['createdby', 'approver', 'approvedBy', 'contact', 'feedbacks.user']), $user)]);
    }

    public function deleteDraft(Request $request): JsonResponse
    {
        $request->validate(['draft_id' => ['required', 'integer']]);
        $user = $request->user();
        $draft = LeadNote::whereIn('type', self::DRAFT_TYPES)->findOrFail($request->draft_id);
        $this->accessibleLead($user, (int) $draft->lead_id);
        abort_if($draft->isApproved() && ! $draft->canBeApprovedBy($user), Response::HTTP_UNPROCESSABLE_ENTITY, 'This draft is approved and can no longer be deleted.');
        $draft->delete();

        return response()->json(['success' => true, 'message' => 'Draft deleted.']);
    }

    /** Drafts waiting for (or approved by) the signed-in approver. */
    public function approvals(Request $request): JsonResponse
    {
        $user = $request->user();
        $type = $request->input('type') === LeadNote::TYPE_LINKEDIN ? LeadNote::TYPE_LINKEDIN : LeadNote::TYPE_EMAIL;
        $state = $request->input('state', 'pending');
        $canSeeAll = $user->can('draft_approval');

        $query = LeadNote::with(['createdby:id,name', 'approver:id,name', 'approvedBy:id,name', 'rejectedby:id,name', 'lead:id,company_name,external_lead_id', 'contact:id,name,title', 'feedbacks.user:id,name'])
            ->where('type', $type)
            ->when(! $canSeeAll, fn ($q) => $q->where('approver_id', $user->id))
            ->draftStatus($state)
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->where(fn ($inner) => $inner->where('note', 'like', '%'.$search.'%')
                    ->orWhereHas('lead', fn ($lead) => $lead->where('company_name', 'like', '%'.$search.'%')));
            })
            ->latest();
        $page = $query->paginate((int) $request->input('pageSize', 20));

        $counts = LeadNote::whereIn('type', self::DRAFT_TYPES)->awaitingApproval()
            ->when(! $canSeeAll, fn ($q) => $q->where('approver_id', $user->id))
            ->selectRaw('type, COUNT(*) as total')->groupBy('type')->pluck('total', 'type');

        return response()->json([
            'success' => true,
            'data' => collect($page->items())->map(fn (LeadNote $draft) => $this->draftPayload($draft, $user) + [
                'lead_id' => $draft->lead_id,
                'company_name' => $draft->lead?->company_name,
                'external_lead_id' => $draft->lead?->external_lead_id,
            ])->values(),
            'pending_counts' => ['email' => (int) ($counts['email'] ?? 0), 'linkedin' => (int) ($counts['linkedin'] ?? 0)],
            'last_page' => $page->lastPage(),
            'current_page' => $page->currentPage(),
        ]);
    }

    /** Approve a draft, optionally with edited text in the same step. */
    public function approveDraft(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'draft_id' => ['required', 'integer'],
            'note' => ['nullable', 'string', 'max:10000'],
        ]);
        $user = $request->user();
        $draft = LeadNote::whereIn('type', self::DRAFT_TYPES)->findOrFail($validated['draft_id']);
        abort_unless($draft->canBeApprovedBy($user), Response::HTTP_FORBIDDEN, 'This draft was sent to another approver.');

        app(LeadDraftService::class)->approve($draft, $user, $validated['note'] ?? null);

        return response()->json(['success' => true, 'message' => 'Draft approved.', 'data' => $this->draftPayload($draft->fresh(['createdby', 'approver', 'approvedBy', 'contact', 'feedbacks.user']), $user)]);
    }

    /** Approver sends feedback: the draft goes back to the agent for changes. */
    public function draftFeedback(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'draft_id' => ['required', 'integer'],
            'feedback' => ['required', 'string', 'max:2000'],
        ], ['feedback.required' => 'Please write what should be changed.']);
        $user = $request->user();
        $draft = LeadNote::whereIn('type', self::DRAFT_TYPES)->findOrFail($validated['draft_id']);
        abort_unless($draft->canBeApprovedBy($user), Response::HTTP_FORBIDDEN, 'This draft was sent to another approver.');
        abort_if($draft->isApproved(), Response::HTTP_UNPROCESSABLE_ENTITY, 'This draft is already approved.');
        abort_if($draft->isRejected(), Response::HTTP_UNPROCESSABLE_ENTITY, 'This draft is already rejected.');
        app(LeadDraftService::class)->requestChanges($draft, $user, $validated['feedback']);

        return response()->json(['success' => true, 'message' => 'Feedback sent to '.($draft->createdby->name ?? 'the agent').'.']);
    }

    /** Approver rejects a draft (final) with a reason the agent can see. */
    public function rejectDraft(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'draft_id' => ['required', 'integer'],
            'reason' => ['required', 'string', 'max:2000'],
        ], ['reason.required' => 'Please write why the draft is rejected.']);
        $user = $request->user();
        $draft = LeadNote::whereIn('type', self::DRAFT_TYPES)->findOrFail($validated['draft_id']);
        abort_unless($draft->canBeApprovedBy($user), Response::HTTP_FORBIDDEN, 'This draft was sent to another approver.');
        abort_if($draft->isApproved(), Response::HTTP_UNPROCESSABLE_ENTITY, 'This draft is already approved.');
        abort_if($draft->isRejected(), Response::HTTP_UNPROCESSABLE_ENTITY, 'This draft is already rejected.');
        app(LeadDraftService::class)->reject($draft, $user, $validated['reason']);

        return response()->json(['success' => true, 'message' => 'Draft rejected. '.($draft->createdby->name ?? 'The agent').' has been notified.', 'data' => $this->draftPayload($draft->fresh(['createdby', 'approver', 'approvedBy', 'rejectedby', 'contact', 'feedbacks.user']), $user)]);
    }

    /** Upload one or more files to a lead (local public disk, same as the CRM). */
    public function uploadFiles(Request $request): JsonResponse
    {
        $request->validate([
            'lead_id' => ['required', 'integer'],
            'files' => ['required_without:file', 'array', 'max:20'],
            'files.*' => ['file', 'max:25600'],
            'file' => ['nullable', 'file', 'max:25600'],
        ], [
            'files.required_without' => 'Please choose at least one file.',
            'files.*.max' => 'Each file must be 25 MB or smaller.',
        ]);
        $user = $request->user();
        $lead = $this->accessibleLead($user, (int) $request->lead_id);

        $uploaded = [];
        foreach (array_filter(array_merge((array) $request->file('files', []), [$request->file('file')])) as $file) {
            $original = $file->getClientOriginalName();
            $extension = strtolower($file->getClientOriginalExtension());
            $uploaded[] = $this->filePayload($lead->addMedia($file)
                ->usingName(pathinfo($original, PATHINFO_FILENAME))
                ->usingFileName(Str::random(8).'-'.time().($extension ? '.'.$extension : ''))
                ->withCustomProperties(['uploaded_by' => $user->id, 'original_name' => $original])
                ->toMediaCollection('lead_file', 'public'));
        }

        return response()->json([
            'success' => true,
            'message' => count($uploaded) === 1 ? 'File uploaded.' : count($uploaded).' files uploaded.',
            'data' => $uploaded,
        ]);
    }

    public function deleteFile(Request $request): JsonResponse
    {
        $request->validate(['media_id' => ['required', 'integer']]);
        $media = Media::where('model_type', Lead::class)->where('collection_name', 'lead_file')->findOrFail($request->media_id);
        $this->accessibleLead($request->user(), (int) $media->model_id);
        $media->delete();

        return response()->json(['success' => true, 'message' => 'File deleted.']);
    }

    /** Signed link (no token needed) so the app can open a file in the browser / viewer. */
    public function viewFile(Request $request, Media $media)
    {
        abort_unless($media->model_type === Lead::class && $media->collection_name === 'lead_file', Response::HTTP_NOT_FOUND);
        $path = $media->getPath();
        abort_unless(is_file($path), Response::HTTP_NOT_FOUND);
        $name = $media->getCustomProperty('original_name') ?: $media->file_name;

        return response()->file($path, [
            'Content-Type' => $media->mime_type ?: 'application/octet-stream',
            'Content-Disposition' => ($request->boolean('download') ? 'attachment' : 'inline').'; filename="'.addslashes($name).'"',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    // ---------------------------------------------------------------------

    private function accessibleLead(User $user, int $leadId): Lead
    {
        $lead = Lead::findOrFail($leadId);
        if ($this->isAdmin($user)) {
            return $lead;
        }
        $team = array_map('intval', (array) getUsersReportingToAuth($user->id));
        abort_unless(
            in_array((int) $lead->assign_to, $team, true) || in_array((int) $lead->created_by, $team, true),
            Response::HTTP_FORBIDDEN,
            'You do not have access to this lead.'
        );

        return $lead;
    }

    private function isAdmin(User $user): bool
    {
        return $user->hasRole('superadmin') || $user->hasRole('Admin');
    }

    private function isConnected(CallLog $call): bool
    {
        return $call->isConnected();
    }

    private function callStatusLabel(CallLog $call): string
    {
        $status = $call->feedbackStatus;
        if ($status && $status->module === Status::MODULE_LEAD_CALL_FEEDBACK) {
            return $status->display_name ?: $status->status_name;
        }

        return $this->isConnected($call) ? 'Connected' : 'Not connected';
    }

    private function linkedinUrl(?string $url): ?string
    {
        $url = trim((string) $url);
        if ($url === '' || ! str_contains(strtolower($url), 'linkedin.com')) {
            return null;
        }

        return preg_match('#^https?://#i', $url) ? $url : 'https://'.ltrim($url, '/');
    }

    private function draftPayload(LeadNote $draft, User $user): array
    {
        $approved = $draft->isApproved();
        $rejected = $draft->isRejected();

        return [
            'id' => $draft->id,
            'type' => $draft->type,
            'note' => strip_tags(str_replace(['<br>', '<br/>', '<br />', '</p>'], "\n", (string) $draft->note)),
            'created_by' => $draft->createdby?->name,
            'created_at' => $draft->created_at?->format('d M Y, h:i A'),
            'approver_id' => $draft->approver_id,
            'approver' => $draft->approver?->name,
            'approved' => $approved,
            'approved_by' => $draft->approvedBy?->name,
            'approved_at' => $draft->approved_at ? \Carbon\Carbon::parse($draft->approved_at)->format('d M Y, h:i A') : null,
            'rejected' => $rejected,
            'rejected_by' => $draft->rejectedby?->name,
            'rejected_at' => $draft->rejected_at?->format('d M Y, h:i A'),
            'rejection_reason' => $draft->rejection_reason,
            'can_edit' => ! $draft->isLockedFor($user) && ! $rejected,
            'can_delete' => ! $approved || $draft->canBeApprovedBy($user),
            'can_approve' => ! $approved && ! $rejected && $draft->canBeApprovedBy($user),
            'can_reject' => ! $approved && ! $rejected && $draft->canBeApprovedBy($user),
            'state' => $draft->draftState(),
            'contact_id' => $draft->lead_contact_id,
            'contact_name' => $draft->contact?->name,
            'contact_title' => $draft->contact?->title,
            'feedbacks' => $draft->feedbacks->map(fn ($item) => [
                'text' => $item->feedback,
                'by' => $item->user?->name,
                'at' => $item->created_at?->format('d M Y, h:i A'),
            ])->values(),
        ];
    }

    private function filePayload(Media $media): array
    {
        $signed = fn (bool $download) => URL::temporarySignedRoute('api.lead-files.view', now()->addHours(2), ['media' => $media->id] + ($download ? ['download' => 1] : []));

        return [
            'id' => $media->id,
            'file_name' => $media->getCustomProperty('original_name') ?: $media->file_name,
            'mime_type' => $media->mime_type,
            'size' => (int) $media->size,
            'is_image' => str_starts_with((string) $media->mime_type, 'image/'),
            'uploaded_at' => $media->created_at?->format('d M Y, h:i A'),
            'url' => $signed(false),
            'download_url' => $signed(true),
        ];
    }

    private function notifyApprover(LeadNote $draft, User $by): void
    {
        if (! $draft->approver_id || (int) $draft->approver_id === (int) $by->id) {
            return;
        }
        $label = $draft->type === LeadNote::TYPE_LINKEDIN ? 'linkedin draft' : 'email draft';
        $msg = '✉️ '.$by->name.' sent you a '.$label.' for '.Str::limit($draft->lead->company_name ?? '', 30).' to approve.';
        SendPushNotification($draft->approver_id, $msg, 'lead');
        StoreLeadNotification($draft->lead_id, 'Draft Approval', $msg, $draft->approver_id, 'lead');
    }
}
