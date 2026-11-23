<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
        'lead_contact_id',
        'direction',
        'call_management_entry_id',
        'number',
        'started_at',
        'duration',
        'recording_duration',
        'user_id',
        'initiated_by',
        'status',
        'feedback_status_id',
        'remark',
        'follow_up_at',
        'plivo_status',
        'plivo_call_uuid',
        'plivo_b_leg_uuid',
        'recording_url',
        'recording_id',
        'transcription_status',
        'transcript',
        'diarized_transcript',
        'sarvam_job_id',
        'transcription_error',
        'cost',
        'answered_at',
        'completed_at',
        'webhook_token',
    ];

    protected $hidden = ['webhook_token'];

    protected $casts = [
        'started_at' => 'datetime',
        'duration' => 'integer',
        'recording_duration' => 'integer',
        'answered_at' => 'datetime',
        'completed_at' => 'datetime',
        'follow_up_at' => 'datetime',
        'diarized_transcript' => 'array',
    ];

    /**
     * A call log belongs to a lead.
     */
    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id', 'id');
    }

    public function contact()
    {
        return $this->belongsTo(LeadContact::class, 'lead_contact_id');
    }

    public function initiatedBy()
    {
        return $this->belongsTo(User::class, 'initiated_by');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function callManagementEntry()
    {
        return $this->belongsTo(CallManagementEntry::class, 'call_management_entry_id');
    }

    public function feedbackStatus()
    {
        return $this->belongsTo(Status::class, 'feedback_status_id', 'id');
    }
}
