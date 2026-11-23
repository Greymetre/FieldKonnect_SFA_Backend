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
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'changes_requested_at' => 'datetime',
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

    /** The approver sent feedback and the agent has not updated the draft yet. */
    public function needsChanges(): bool
    {
        return ! $this->isApproved() && $this->changes_requested_at !== null;
    }

    /** pending | changes_requested | approved */
    public function draftState(): string
    {
        return $this->isApproved() ? 'approved' : ($this->needsChanges() ? 'changes_requested' : 'pending');
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
