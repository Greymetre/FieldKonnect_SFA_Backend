<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientCallLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'public_id', 'call_management_entry_id', 'assigned_user_id',
        'feedback_status_id', 'direction', 'customer_number', 'agent_number',
        'virtual_number', 'status', 'provider_call_uuid',
        'provider_agent_leg_uuid', 'provider_customer_leg_uuid', 'started_at',
        'ringing_at', 'answered_at', 'completed_at', 'duration',
        'recording_duration', 'recording_url', 'recording_id', 'cost',
        'hangup_cause', 'remark', 'metadata', 'webhook_token',
    ];

    protected $hidden = ['webhook_token'];

    protected $casts = [
        'started_at' => 'datetime', 'ringing_at' => 'datetime',
        'answered_at' => 'datetime', 'completed_at' => 'datetime',
        'duration' => 'integer', 'recording_duration' => 'integer',
        'metadata' => 'array',
    ];

    public function entry()
    {
        return $this->belongsTo(CallManagementEntry::class, 'call_management_entry_id');
    }

    public function assignedAgent()
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function feedbackStatus()
    {
        return $this->belongsTo(Status::class, 'feedback_status_id');
    }
}
