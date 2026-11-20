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
