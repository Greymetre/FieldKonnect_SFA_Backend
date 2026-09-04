<x-app-layout>
    @php
        $entry = $callLog->callManagementEntry;
        $duration = max(0, (int) $callLog->duration);
        $transcriptEntries = data_get($callLog->diarized_transcript, 'entries', []);
        $conversation = collect($transcriptEntries)->reduce(function ($groups, $line) {
            $speaker = (int) data_get($line, 'speaker_id', 0);
            $text = trim((string) data_get($line, 'transcript', ''));
            if ($text === '') return $groups;
            $last = count($groups) - 1;
            if ($last >= 0 && $groups[$last]['speaker'] === $speaker) {
                $groups[$last]['text'] .= ' '.$text;
                $groups[$last]['end'] = data_get($line, 'end_time_seconds', $groups[$last]['end']);
            } else {
                $groups[] = ['speaker' => $speaker, 'text' => $text, 'start' => data_get($line, 'start_time_seconds'), 'end' => data_get($line, 'end_time_seconds')];
            }
            return $groups;
        }, []);
    @endphp
    <style>
        .call-detail{max-width:1280px;margin:0 auto;padding:4px 2px 32px;color:#c9d6f4;font-size:14px}
        .call-detail-back{display:inline-flex;align-items:center;gap:8px;margin-bottom:16px;color:#43d5ee;font-size:14px;font-weight:700;text-decoration:none}.call-detail-back:hover{color:#84e8f7}.call-detail-back .material-icons{font-size:20px}
        .call-detail-head,.call-detail-card{border:1px solid rgba(85,126,218,.3);border-radius:16px;background:linear-gradient(145deg,rgba(10,32,76,.92),rgba(6,22,54,.86));box-shadow:0 12px 32px rgba(0,0,0,.1)}
        .call-detail-head{position:relative;display:flex;align-items:center;justify-content:space-between;gap:20px;padding:24px 26px;margin-bottom:16px;overflow:hidden}.call-detail-head::after{content:'';position:absolute;right:-55px;top:-90px;width:260px;height:260px;border-radius:50%;background:radial-gradient(circle,rgba(45,212,238,.13),transparent 68%);pointer-events:none}.call-detail-head h1{margin:0;color:#f8faff;font-size:27px;font-weight:800;letter-spacing:-.02em}.call-detail-head p{margin:7px 0 0;color:#8da0ca;font-size:14px}.call-detail-date{position:relative;z-index:1;padding:8px 12px;border:1px solid rgba(85,126,218,.28);border-radius:10px;background:rgba(5,18,47,.45);color:#aab9dc;font-size:13px;white-space:nowrap}
        .call-detail-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:13px;margin-bottom:16px}.call-detail-card{padding:19px 20px}.call-detail-card small{display:flex;align-items:center;gap:7px;margin-bottom:8px;color:#8297c6;font-size:11px;font-weight:800;letter-spacing:.11em;text-transform:uppercase}.call-detail-card small::before{content:'';width:5px;height:5px;border-radius:50%;background:#2dd4ee;box-shadow:0 0 8px rgba(45,212,238,.7)}.call-detail-card strong{display:block;color:#f1f5ff;font-size:15px;font-weight:600;line-height:1.5}.call-detail-wide{margin-bottom:16px}.call-detail-section-head{display:flex;align-items:center;justify-content:space-between;gap:16px;margin-bottom:16px}.call-detail-wide h2{margin:0;color:#f2f6ff;font-size:18px;font-weight:800}.call-detail-wide h2 span{display:block;margin-top:4px;color:#7489bb;font-size:12px;font-weight:500}.call-detail audio{display:block;width:100%;height:48px;accent-color:#2dd4ee}
        .call-transcribe-btn{display:inline-flex;align-items:center;gap:8px;padding:12px 17px;border:0;border-radius:11px;background:linear-gradient(135deg,#26d4ae,#2dd4ee);box-shadow:0 8px 22px rgba(38,212,174,.16);color:#061329;font-size:13px;font-weight:800;white-space:nowrap}.call-transcribe-btn[disabled]{cursor:not-allowed;box-shadow:none;opacity:.55}.call-message{margin-bottom:16px;padding:13px 16px;border:1px solid rgba(38,212,174,.2);border-radius:11px;background:rgba(38,212,174,.1);color:#65e7c2;font-size:14px}.call-message.error{border-color:rgba(239,77,123,.2);background:rgba(239,77,123,.1);color:#ff8aaa}
        .transcript-conversation{display:grid;gap:15px;padding:4px 0}.transcript-row{display:flex;align-items:flex-start;gap:11px}.transcript-row.alt{flex-direction:row-reverse}.transcript-avatar{display:grid;place-items:center;width:36px;height:36px;flex:0 0 36px;border:1px solid rgba(45,212,238,.38);border-radius:11px;background:rgba(45,212,238,.1);color:#4bddf4;font-size:12px;font-weight:800}.transcript-row.alt .transcript-avatar{border-color:rgba(38,212,174,.38);background:rgba(38,212,174,.1);color:#4de0ba}.transcript-bubble{width:min(82%,860px);padding:13px 15px;border:1px solid rgba(45,212,238,.16);border-radius:4px 13px 13px 13px;background:rgba(13,47,91,.67);color:#dce6fb;font-size:14px;line-height:1.65}.transcript-row.alt .transcript-bubble{border-color:rgba(38,212,174,.16);border-radius:13px 4px 13px 13px;background:rgba(10,55,75,.58)}.transcript-meta{display:flex;align-items:center;gap:9px;margin-bottom:5px;color:#45d8f0;font-size:10px;font-weight:800;letter-spacing:.08em;text-transform:uppercase}.transcript-row.alt .transcript-meta{justify-content:flex-end;color:#4bdcb7}.transcript-time{color:#7185b6;font-size:10px;font-weight:600;letter-spacing:0;text-transform:none}.transcript-empty{padding:22px;border:1px dashed rgba(85,126,218,.35);border-radius:12px;color:#8295c3;text-align:center}
        @media(max-width:900px){.call-detail-grid{grid-template-columns:repeat(2,1fr)}}@media(max-width:640px){.call-detail{font-size:13px}.call-detail-grid{grid-template-columns:1fr}.call-detail-head{align-items:flex-start;flex-direction:column;padding:20px}.call-detail-head h1{font-size:23px}.call-detail-date{white-space:normal}.call-detail-card{padding:16px}.call-detail-section-head{align-items:flex-start;flex-direction:column}.transcript-bubble{width:calc(100% - 47px);font-size:13px}.transcript-row.alt{flex-direction:row}.transcript-row.alt .transcript-meta{justify-content:flex-start}.transcript-row.alt .transcript-bubble{border-radius:4px 13px 13px 13px}}
    </style>
    <div class="call-detail">
        <a class="call-detail-back" href="{{ route('customer-call-history.index') }}"><i class="material-icons">arrow_back</i> Call History</a>
        @if(session('success'))<div class="call-message">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="call-message error">{{ session('error') }}</div>@endif
        <header class="call-detail-head"><div><h1>{{ $entry->firm_name ?: 'Call details' }}</h1><p>{{ $entry->contact_person_name ?: '—' }} · {{ $entry->mobile_number ?: $callLog->number }}</p></div><span class="call-detail-date">{{ optional($callLog->started_at)->format('d M Y, h:i A') }}</span></header>
        <section class="call-detail-grid">
            <div class="call-detail-card"><small>Agent</small><strong>{{ optional($callLog->user)->name ?: '—' }}</strong></div>
            <div class="call-detail-card"><small>Duration</small><strong>{{ sprintf('%02d:%02d:%02d', intdiv($duration,3600), intdiv($duration%3600,60), $duration%60) }}</strong></div>
            <div class="call-detail-card"><small>Call Status</small><strong>{{ ucfirst($callLog->plivo_status ?: ((int)$callLog->status === 1 ? 'Completed' : 'Unknown')) }}</strong></div>
            <div class="call-detail-card"><small>Agent Status</small><strong>{{ optional($callLog->feedbackStatus)->display_name ?: optional($callLog->feedbackStatus)->status_name ?: '—' }}</strong></div>
            <div class="call-detail-card"><small>Notes</small><strong>{{ $callLog->remark ?: '—' }}</strong></div>
            <div class="call-detail-card"><small>Transcript Status</small><strong>{{ ucfirst(str_replace('_',' ',$callLog->transcription_status ?: 'Not requested')) }}</strong></div>
        </section>
        <section class="call-detail-card call-detail-wide"><div class="call-detail-section-head"><h2>Audio Recording<span>Listen to the complete customer conversation</span></h2></div>@if($callLog->recording_url)<audio controls preload="metadata" src="{{ route('call-management.recording',$callLog) }}"></audio>@else<p>Recording is unavailable.</p>@endif</section>
        <section class="call-detail-card call-detail-wide">
            <div class="call-detail-section-head"><h2>Conversation Transcript<span>Speaker-separated transcript generated by Sarvam AI</span></h2>
                @can('call_management_transcribe')
                    @if(!in_array($callLog->transcription_status,['completed','queued','processing'],true) && $callLog->recording_url)
                        <form method="POST" action="{{ route('customer-call-history.transcribe',$callLog) }}">@csrf<button class="call-transcribe-btn" type="submit"><i class="material-icons">auto_awesome</i> Generate Transcript</button></form>
                    @elseif(in_array($callLog->transcription_status,['queued','processing'],true))<button class="call-transcribe-btn" disabled>Processing…</button>@endif
                @endcan
            </div>
            @if($callLog->transcription_status === 'completed')
                <div class="transcript-conversation">
                    @forelse($conversation as $line)
                        @php($speaker=(int)$line['speaker'])
                        <div class="transcript-row {{ $speaker % 2 ? 'alt' : '' }}"><span class="transcript-avatar">S{{ $speaker+1 }}</span><div class="transcript-bubble"><div class="transcript-meta"><span>Speaker {{ $speaker+1 }}</span>@if($line['start'] !== null)<span class="transcript-time">{{ gmdate('i:s',(int)$line['start']) }}</span>@endif</div>{{ $line['text'] }}</div></div>
                    @empty
                        <div class="transcript-empty">{{ $callLog->transcript ?: 'No transcript returned.' }}</div>
                    @endforelse
                </div>
            @elseif($callLog->transcription_status === 'failed')<p>Transcription failed. An authorized Super Admin can retry it.</p>@else<p>Transcript has not been generated yet.</p>@endif
        </section>
    </div>
</x-app-layout>
