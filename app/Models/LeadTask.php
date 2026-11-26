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
        'remark',
        'kind',
        'parent_task_id',
    ];

    protected $casts = [
        'seen_at' => 'datetime',
        'opened_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /** What the task is for (null = manual task). */
    public const KIND_SEND_DETAILS = 'send_details';
    public const KIND_CALL_BACK = 'call_back';
    public const KIND_FOLLOW_UP = 'follow_up';

    /** Completing one of these creates a follow-up task this many days later. */
    public const FOLLOW_UP_KINDS = [self::KIND_SEND_DETAILS];
    public const FOLLOW_UP_AFTER_DAYS = 3;

    protected static function booted(): void
    {
        // Any path that completes the task (web status change, edit form, app).
        static::updated(function (LeadTask $task) {
            if ($task->wasChanged('status') && $task->status === 'completed') {
                $task->createFollowUp();
            }
        });
    }

    public function isFollowUp(): bool
    {
        return $this->kind === self::KIND_FOLLOW_UP;
    }

    /**
     * Details were shared (WhatsApp / Email / LinkedIn): give the same agent a
     * task to follow up with the contact FOLLOW_UP_AFTER_DAYS days later.
     * Only once per task, even if it is re-opened and completed again.
     */
    public function createFollowUp(): ?self
    {
        if (! in_array($this->kind, self::FOLLOW_UP_KINDS, true) || ! $this->lead_id || ! $this->assigned_to
            || static::where('parent_task_id', $this->id)->exists()) {
            return null;
        }

        $due = now()->addDays(self::FOLLOW_UP_AFTER_DAYS);
        $description = preg_match('/^Send details on (.+?) to (.+)$/i', (string) $this->description, $match)
            ? 'Follow up with '.$match[2].' on the details sent on '.$match[1]
            : 'Follow up: '.\Illuminate\Support\Str::limit(strip_tags((string) $this->description), 200);

        $followUp = static::create([
            'lead_id' => $this->lead_id,
            'assigned_to' => $this->assigned_to,
            'created_by' => $this->created_by ?: $this->assigned_to,
            'description' => $description,
            'date' => $due->toDateString(),
            'time' => $this->time ?: '11:00:00',
            'priority' => $this->priority ?: 'high',
            'status' => 'pending',
            'kind' => self::KIND_FOLLOW_UP,
            'parent_task_id' => $this->id,
        ]);

        LeadLog::create([
            'lead_id' => $this->lead_id,
            'message' => 'Follow-up task created for '.$due->format('d M Y').': '.$description,
            'created_by' => $this->assigned_to,
        ]);

        $msg = '🔁 '.$description.' — follow-up due on '.$due->format('d M').'.';
        SendPushNotification($this->assigned_to, $msg, 'task');
        StoreLeadNotification($followUp->id, 'Follow-up Task', $msg, $this->assigned_to, 'task');

        return $followUp;
    }

    /** The task this follow-up was created from. */
    public function parentTask()
    {
        return $this->belongsTo(self::class, 'parent_task_id');
    }

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
