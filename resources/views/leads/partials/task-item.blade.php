{{-- One lead task: who it is for, its status, and when it was seen / opened / completed. --}}
@php
    $taskStatusClass = ['pending' => 'is-pending', 'open' => 'is-open', 'in_progress' => 'is-progress', 'completed' => 'is-done'][$lead_task->status] ?? 'is-pending';
    $taskDue = $lead_task->date ? \Carbon\Carbon::parse($lead_task->date)->format('d M Y') . ($lead_task->time ? ', ' . \Carbon\Carbon::parse($lead_task->time)->format('h:i A') : '') : null;
@endphp
<li class="task-item">
    <div class="task-item-main">
        <div class="task-item-top">
            <span class="task-title">{{ strip_tags((string) $lead_task->description) }}</span>
            <span class="task-status {{ $taskStatusClass }}">{{ $lead_task->statusLabel() }}</span>
        </div>
        <div class="task-meta">
            @if($lead_task->assignUser)
            <span><i class="material-icons">person</i>{{ $lead_task->assignUser->name }}</span>
            @endif
            @if($taskDue)
            <span><i class="material-icons">event</i>Due {{ $taskDue }}</span>
            @endif
            @if($lead_task->priority)
            <span><i class="material-icons">flag</i>{{ ucfirst($lead_task->priority) }}</span>
            @endif
        </div>
        <div class="task-timeline">
            <span class="{{ $lead_task->seen_at ? 'is-done' : '' }}" title="{{ $lead_task->seen_at ? 'Seen by the assignee' : 'Not seen yet' }}">
                <i class="material-icons">{{ $lead_task->seen_at ? 'visibility' : 'visibility_off' }}</i>{{ $lead_task->seen_at ? 'Seen ' . $lead_task->seen_at->format('d M, h:i A') : 'Not seen yet' }}
            </span>
            @if($lead_task->opened_at || $lead_task->open_date)
            <span class="is-done"><i class="material-icons">play_circle</i>Opened {{ $lead_task->opened_at ? $lead_task->opened_at->format('d M, h:i A') : \Carbon\Carbon::parse($lead_task->open_date)->format('d M') }}</span>
            @endif
            @if($lead_task->status === 'completed')
            <span class="is-done"><i class="material-icons">task_alt</i>Completed {{ $lead_task->completed_at ? $lead_task->completed_at->format('d M, h:i A') : ($lead_task->close_date ? \Carbon\Carbon::parse($lead_task->close_date)->format('d M') : '') }}</span>
            @endif
        </div>
        @if($lead_task->remark)
        <div class="task-remark"><i class="material-icons">chat_bubble_outline</i>{{ $lead_task->remark }}</div>
        @endif
    </div>
    <div class="task-actions">
        <button type="button" class="contact-icon-btn contact-icon-muted" onclick="editTask('{{ $lead_task }}')" title="Edit task" data-toggle="tooltip"><i class="material-icons">edit</i></button>
        <form action="{{ route('lead-tasks.destroy', $lead_task->id) }}" method="POST" class="d-inline-flex m-0" onsubmit="return confirm('Do you really want to delete?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="contact-icon-btn contact-icon-muted contact-icon-danger" title="Delete task" data-toggle="tooltip"><i class="material-icons">delete</i></button>
        </form>
    </div>
</li>
