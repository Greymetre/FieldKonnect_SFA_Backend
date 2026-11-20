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
        'type',
        'note',
        'created_by',
        'approver_id',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
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

    public function createdby(){
        return $this->belongsTo(User::class, 'created_by');
    }
}
