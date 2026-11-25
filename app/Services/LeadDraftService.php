<?php

namespace App\Services;

use App\Models\LeadContact;
use App\Models\LeadNote;
use App\Models\LeadNoteFeedback;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * Email / LinkedIn draft approval flow, shared by the CRM and the mobile app:
 *
 *   agent writes draft (for one contact) → approver
 *       ├─ Approve            → agent can send it
 *       ├─ Feedback (changes) → agent edits it → back to the approver
 *       └─ Reject (with reason) → final; the agent sees it as Rejected
 */
class LeadDraftService
{
    /** The contact must belong to the draft's lead. */
    public function validContactId($contactId, int $leadId): int
    {
        $contactId = (int) $contactId;
        if (! $contactId || ! LeadContact::whereKey($contactId)->where('lead_id', $leadId)->exists()) {
            throw ValidationException::withMessages(['lead_contact_id' => 'Please choose the contact this draft is for.']);
        }

        return $contactId;
    }

    /**
     * The agent saved an existing draft. If the approver had asked for changes,
     * the draft goes back to them for approval.
     */
    public function afterAgentEdit(LeadNote $draft, User $by): void
    {
        if (! $draft->needsChanges()) {
            return;
        }
        $draft->update(['changes_requested_at' => null]);
        if ($draft->approver_id && (int) $draft->approver_id !== (int) $by->id) {
            $this->notify($draft->approver_id, $draft, '✏️ '.$by->name.' updated the '.$this->label($draft).' for '.$this->company($draft).' as per your feedback. Please review it again.', 'Draft Approval');
        }
    }

    /** Approver asks for changes: store the feedback and send it to the agent. */
    public function requestChanges(LeadNote $draft, User $by, string $feedback): LeadNoteFeedback
    {
        $entry = LeadNoteFeedback::create(['lead_note_id' => $draft->id, 'user_id' => $by->id, 'feedback' => trim($feedback)]);
        $draft->update(['changes_requested_at' => now()]);

        if ($draft->created_by && (int) $draft->created_by !== (int) $by->id) {
            $this->notify($draft->created_by, $draft, '💬 '.$by->name.' sent feedback on your '.$this->label($draft).' for '.$this->company($draft).': "'.Str::limit(trim($feedback), 80).'"', 'Draft Feedback');
        }

        return $entry;
    }

    /** Approve, optionally with edited text in the same step. */
    public function approve(LeadNote $draft, User $by, ?string $note = null): void
    {
        $draft->update(array_filter([
            'note' => $note,
            'approved_by' => $by->id,
            'approved_at' => now(),
        ], fn ($value) => $value !== null && $value !== '') + ['changes_requested_at' => null, 'rejected_at' => null, 'rejected_by' => null, 'rejection_reason' => null]);

        if ($draft->created_by && (int) $draft->created_by !== (int) $by->id) {
            $this->notify($draft->created_by, $draft, '✅ '.$by->name.' approved your '.$this->label($draft).' for '.$this->company($draft).'. You can send it now.', 'Draft Approved');
        }
    }

    /** Reject the draft (final) and tell the agent why. */
    public function reject(LeadNote $draft, User $by, string $reason): void
    {
        $draft->update([
            'rejected_at' => now(),
            'rejected_by' => $by->id,
            'rejection_reason' => trim($reason),
            'changes_requested_at' => null,
        ]);

        if ($draft->created_by && (int) $draft->created_by !== (int) $by->id) {
            $this->notify($draft->created_by, $draft, '❌ '.$by->name.' rejected your '.$this->label($draft).' for '.$this->company($draft).': "'.Str::limit(trim($reason), 80).'"', 'Draft Rejected');
        }
    }

    /** Latest feedback text (for lists). */
    public function latestFeedback(LeadNote $draft): ?array
    {
        $last = $draft->relationLoaded('feedbacks') ? $draft->feedbacks->last() : $draft->feedbacks()->latest('id')->first();

        return $last ? [
            'text' => $last->feedback,
            'by' => $last->user?->name,
            'at' => $last->created_at?->format('d M Y, h:i A'),
        ] : null;
    }

    private function label(LeadNote $draft): string
    {
        return $draft->type === LeadNote::TYPE_LINKEDIN ? 'LinkedIn draft' : 'email draft';
    }

    private function company(LeadNote $draft): string
    {
        return Str::limit($draft->lead->company_name ?? 'the lead', 30);
    }

    private function notify(int $userId, LeadNote $draft, string $message, string $title): void
    {
        SendPushNotification($userId, $message, 'lead', $draft->lead_id, $title);
        StoreLeadNotification($draft->lead_id, $title, $message, $userId, 'lead');
    }
}
