        @if($lead_call_history->isEmpty())
        <p class="lead-empty">No calls yet. Use the call icon on a contact to start one.</p>
        @else
        <ul class="call-history-list">
            @foreach($lead_call_history as $call)
            @php
                $callStatus = strtolower((string) $call->plivo_status);
                $isLive = ! $call->completed_at && ! in_array($callStatus, ['failed', 'completed', 'hangup', 'busy', 'no-answer', 'timeout', 'cancel', 'agent-unavailable'], true);
                if ($isLive) {
                    [$badgeClass, $badgeText] = ['live', 'In progress'];
                } elseif ($call->isConnected()) {
                    [$badgeClass, $badgeText] = ['ok', 'Connected'];
                } elseif (in_array($callStatus, ['failed', 'agent-unavailable'], true)) {
                    [$badgeClass, $badgeText] = ['bad', 'Failed'];
                } else {
                    [$badgeClass, $badgeText] = ['warn', $callStatus === 'busy' ? 'Busy' : 'Not answered'];
                }
                $durationSeconds = (int) $call->duration;
                $durationText = $durationSeconds > 0 ? sprintf('%d:%02d', intdiv($durationSeconds, 60), $durationSeconds % 60) : '-';
                $callContact = $call->matched_contact;
                $isInbound = $call->direction === 'inbound';
                $callTime = $call->started_at ?? $call->created_at;
            @endphp
            <li class="call-history-item">
                <div class="call-history-icon {{ $isInbound ? 'in' : 'out' }}" title="{{ $isInbound ? 'Incoming call' : 'Outgoing call' }}">
                    <i class="material-icons">{{ $isInbound ? 'call_received' : 'call_made' }}</i>
                </div>
                <div class="call-history-main">
                    <div class="call-history-top">
                        <strong>{{ $callContact->name ?? 'Unknown contact' }}</strong>
                        <span class="call-badge {{ $badgeClass }}">{{ $badgeText }}</span>
                    </div>
                    <div class="call-history-meta">
                        <span><i class="material-icons">phone</i>{{ $call->number }}</span>
                        @if($callContact && $callContact->title)<span>{{ $callContact->title }}</span>@endif
                    </div>
                    <div class="call-history-meta">
                        <span><i class="material-icons">support_agent</i>{{ $isInbound ? 'Received by' : 'Called by' }} {{ $call->user->name ?? '-' }}</span>
                        @if($call->initiatedBy && (int) $call->initiated_by !== (int) $call->user_id)
                        <span><i class="material-icons">touch_app</i>Started by {{ $call->initiatedBy->name }}</span>
                        @endif
                        <span><i class="material-icons">timer</i>{{ $durationText }}</span>
                    </div>
                    @if($call->follow_up_at)
                    <div class="call-history-meta" style="margin-top:4px">
                        @if(! empty($call->follow_up_channels))
                        <span style="color:#38bdf8"><i class="material-icons">event</i>Send details on {{ collect($call->follow_up_channels)->map(fn ($channel) => ['whatsapp' => 'WhatsApp', 'linkedin' => 'LinkedIn', 'email' => 'Email'][$channel] ?? $channel)->implode(' & ') }} by {{ $call->follow_up_at->format('d M Y, h:i A') }}</span>
                        @else
                        <span style="color:#38bdf8"><i class="material-icons">event</i>Call back on {{ $call->follow_up_at->format('d M Y, h:i A') }}</span>
                        @endif
                    </div>
                    @endif
                    @if($call->feedbackStatus || $call->remark)
                    <div class="call-history-remark">
                        @if($call->feedbackStatus)<span class="call-badge neutral">{{ $call->feedbackStatus->display_name ?: $call->feedbackStatus->status_name }}</span>@endif
                        {{ $call->remark }}
                    </div>
                    @endif
                    @if(! $isLive && trim((string) $call->remark) === '')
                    <button type="button" class="call-remark-btn" data-feedback-url="{{ route('leads.calls.feedback', [$lead->id, $call->id]) }}"
                        data-call-label="{{ ($callContact->name ?? $call->number) . ' · ' . ($callTime ? $callTime->format('d M, h:i A') : '') }}">
                        <i class="material-icons">rate_review</i> Add remark
                    </button>
                    @endif
                    @if($canViewCallRecordings && $call->recording_url)
                    <audio class="call-history-audio" controls preload="none" src="{{ route('call-management.recording', $call->id) }}"></audio>
                    @endif
                </div>
                <div class="call-history-time">
                    <span>{{ $callTime ? $callTime->format('d M Y') : '' }}</span>
                    <span>{{ $callTime ? $callTime->format('h:i A') : '' }}</span>
                </div>
            </li>
            @endforeach
        </ul>
        @endif
