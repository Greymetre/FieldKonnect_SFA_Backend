<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadNote extends Model
{
    use HasFactory;

    public const TYPE_NOTE = 'note';
    public const TYPE_EMAIL = 'email';
    public const TYPE_LINKEDIN = 'linkedin';
    public const TYPES = [self::TYPE_NOTE, self::TYPE_EMAIL, self::TYPE_LINKEDIN];

    protected $fillable = [
        'lead_id',
        'lead_contact_id',
        'type',
        'note',
        'created_by',
        'approver_id',
        'approved_by',
        'approved_at',
        'changes_requested_at',
        'rejected_at',
        'rejected_by',
        'rejection_reason',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'changes_requested_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    protected $attributes = [
        'type' => self::TYPE_NOTE,
    ];

    
    public function lead(){
        return $this->belongsTo(Lead::class);
    }

    /** The user this draft was sent to for approval. */
    public function approver(){
        return $this->belongsTo(User::class, 'approver_id');
    }

    /** Approvers: anyone with draft_approval, or the user the draft was sent to. */
    public function canBeApprovedBy(?User $user): bool
    {
        return $user && ($user->can('draft_approval') || (int) $this->approver_id === (int) $user->id);
    }

    public function approvedby(){
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function isApproved(): bool
    {
        return $this->approved_at !== null;
    }

    /** The approver rejected the draft (final; locked for the agent). */
    public function isRejected(): bool
    {
        return ! $this->isApproved() && $this->rejected_at !== null;
    }

    /** Approved or rejected: no further approval action is possible. */
    public function isDecided(): bool
    {
        return $this->isApproved() || $this->isRejected();
    }

    /** Agents may not edit/delete approved drafts; rejected drafts can only be deleted. */
    public function isLockedFor(?User $user): bool
    {
        return $this->isDecided() && ! $this->canBeApprovedBy($user);
    }

    public function rejectedby(){
        return $this->belongsTo(User::class, 'rejected_by');
    }

    /** The approver sent feedback and the agent has not updated the draft yet. */
    public function needsChanges(): bool
    {
        return ! $this->isDecided() && $this->changes_requested_at !== null;
    }

    /** pending | changes_requested | approved | rejected */
    public function draftState(): string
    {
        if ($this->isApproved()) {
            return 'approved';
        }
        if ($this->isRejected()) {
            return 'rejected';
        }

        return $this->needsChanges() ? 'changes_requested' : 'pending';
    }

    /** Waiting for the approver: not decided and no open feedback. */
    public function scopeAwaitingApproval($query)
    {
        return $query->whereNull('approved_at')->whereNull('rejected_at')->whereNull('changes_requested_at');
    }

    /** Draft list status filter shared by the CRM and the mobile app. */
    public function scopeDraftStatus($query, ?string $status)
    {
        return match ($status) {
            'all' => $query,
            'approved' => $query->whereNotNull('approved_at'),
            'rejected' => $query->whereNull('approved_at')->whereNotNull('rejected_at'),
            'changes' => $query->whereNull('approved_at')->whereNull('rejected_at')->whereNotNull('changes_requested_at'),
            default => $query->awaitingApproval(),
        };
    }

    /** The lead contact this draft is written for. */
    public function contact()
    {
        return $this->belongsTo(LeadContact::class, 'lead_contact_id');
    }

    /** Approver feedback on this draft, oldest first. */
    public function feedbacks()
    {
        return $this->hasMany(LeadNoteFeedback::class, 'lead_note_id')->orderBy('id');
    }

    public function createdby(){
        return $this->belongsTo(User::class, 'created_by');
    }
}
