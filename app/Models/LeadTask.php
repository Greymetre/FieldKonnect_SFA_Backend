<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
        'assigned_to',
        'created_by',
        'description',
        'date',
        'time',
        'priority',
        'status',
        'open_date',
        'due_date',
        'close_date',
        'seen_at',
        'opened_at',
        'completed_at',
        'remark'
    ];

    protected $casts = [
        'seen_at' => 'datetime',
        'opened_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public const STATUS_LABELS = [
        'pending' => 'Pending',
        'open' => 'Open',
        'in_progress' => 'In Progress',
        'completed' => 'Completed',
    ];

    public function statusLabel(): string
    {
        return self::STATUS_LABELS[$this->status] ?? ucwords(str_replace('_', ' ', (string) $this->status));
    }

    /** The assignee looked at the task (lead page or app) for the first time. */
    public function markSeenBy(?int $userId): void
    {
        if ($userId && ! $this->seen_at && (int) $this->assigned_to === (int) $userId) {
            $this->update(['seen_at' => now()]);
        }
    }

    /**
     * Change the status (web + app share this): records opened / completed
     * time, logs it on the lead's Activities and tells the creator on completion.
     */
    public function applyStatus(string $status, ?string $remark, ?User $by): void
    {
        $previous = $this->status;
        $updates = ['status' => $status, 'remark' => $remark];

        if (in_array($status, ['open', 'in_progress', 'completed'], true) && ! $this->opened_at) {
            $updates['opened_at'] = now();
            $updates['open_date'] = $this->open_date ?: now()->toDateString();
        }
        if ($status === 'completed') {
            $updates['completed_at'] = now();
            $updates['close_date'] = now()->toDateString();
        } elseif ($previous === 'completed') {
            // Re-opened after completion.
            $updates['completed_at'] = null;
            $updates['close_date'] = null;
        }
        if (! $this->seen_at && $by && (int) $this->assigned_to === (int) $by->id) {
            $updates['seen_at'] = now();
        }
        $this->update($updates);

        if ($previous !== $status && $this->lead_id) {
            LeadLog::create([
                'lead_id' => $this->lead_id,
                'message' => 'Task "'.\Illuminate\Support\Str::limit(strip_tags((string) $this->description), 60).'" marked '
                    .$this->statusLabel().' by '.($by->name ?? 'system')
                    .($status === 'completed' && $remark ? ' — '.$remark : ''),
                'created_by' => $by?->id,
            ]);
        }

        if ($status === 'completed' && $previous !== 'completed' && $this->created_by) {
            $msg = '📝 Your assigned task '.$this->description.
                ' related to lead: '.\Illuminate\Support\Str::limit($this->lead->company_name ?? '', 10, '...').
                ' has been completed.';
            SendPushNotification($this->created_by, $msg, 'task');
            StoreLeadNotification($this->id, 'Assigned Task', $msg, $this->created_by, 'task');
        }
    }

    
    public function lead(){
        return $this->belongsTo(Lead::class);
    }

    public function assignUser(){
        return $this->belongsTo(User::class,'assigned_to');
    }


    public function createdby(){
        return $this->belongsTo(User::class,'created_by');
    }
}
