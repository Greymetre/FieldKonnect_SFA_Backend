<x-app-layout>
    @php
        $entry = $callLog->callManagementEntry;
        $duration = max(0, (int) $callLog->duration);
        $transcriptEntries = data_get($callLog->diarized_transcript, 'entries', []);
    @endphp
    <style>
        .call-detail{max-width:1120px;margin:0 auto;color:#c5d2f3}.call-detail-back{display:inline-flex;align-items:center;gap:7px;margin-bottom:15px;color:#35ccef;text-decoration:none}.call-detail-head,.call-detail-card{border:1px solid rgba(85,126,218,.3);border-radius:15px;background:rgba(7,25,61,.76)}.call-detail-head{display:flex;align-items:center;justify-content:space-between;gap:18px;padding:21px 23px;margin-bottom:14px}.call-detail-head h1{margin:0;color:#f7f9ff;font-size:24px}.call-detail-head p{margin:5px 0 0;color:#7f92c1}.call-detail-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:14px}.call-detail-card{padding:18px}.call-detail-card small{display:block;margin-bottom:6px;color:#7890c4;font-size:10px;font-weight:800;letter-spacing:.1em;text-transform:uppercase}.call-detail-card strong{color:#f2f6ff;font-size:15px}.call-detail-wide{margin-bottom:14px}.call-detail-wide h2{margin:0 0 14px;color:#edf3ff;font-size:17px}.call-detail audio{width:100%;accent-color:#2dd4ee}.call-transcribe-btn{display:inline-flex;align-items:center;gap:8px;padding:11px 16px;border:0;border-radius:11px;background:linear-gradient(135deg,#26d4ae,#2dd4ee);color:#061329;font-weight:800}.call-transcribe-btn[disabled]{cursor:not-allowed;opacity:.55}.call-message{margin-bottom:14px;padding:11px 14px;border-radius:10px;background:rgba(38,212,174,.1);color:#5ee1bd}.call-message.error{background:rgba(239,77,123,.1);color:#ff8aaa}.transcript-line{margin-bottom:11px;padding:11px 13px;border-left:3px solid #2dd4ee;border-radius:0 9px 9px 0;background:rgba(34,211,238,.06);line-height:1.55}.transcript-line.alt{border-color:#26d4ae;background:rgba(38,212,174,.06)}.transcript-line b{display:block;margin-bottom:3px;color:#2dd4ee;font-size:10px;text-transform:uppercase}.transcript-line.alt b{color:#26d4ae}@media(max-width:760px){.call-detail-grid{grid-template-columns:1fr}.call-detail-head{align-items:flex-start;flex-direction:column}}
    </style>
    <div class="call-detail">
        <a class="call-detail-back" href="{{ route('customer-call-history.index') }}"><i class="material-icons">arrow_back</i> Call History</a>
        @if(session('success'))<div class="call-message">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="call-message error">{{ session('error') }}</div>@endif
        <header class="call-detail-head"><div><h1>{{ $entry->firm_name ?: 'Call details' }}</h1><p>{{ $entry->contact_person_name ?: '—' }} · {{ $entry->mobile_number ?: $callLog->number }}</p></div><span>{{ optional($callLog->started_at)->format('d M Y, h:i A') }}</span></header>
        <section class="call-detail-grid">
            <div class="call-detail-card"><small>Agent</small><strong>{{ optional($callLog->user)->name ?: '—' }}</strong></div>
            <div class="call-detail-card"><small>Duration</small><strong>{{ sprintf('%02d:%02d:%02d', intdiv($duration,3600), intdiv($duration%3600,60), $duration%60) }}</strong></div>
            <div class="call-detail-card"><small>Call Status</small><strong>{{ ucfirst($callLog->plivo_status ?: ((int)$callLog->status === 1 ? 'Completed' : 'Unknown')) }}</strong></div>
            <div class="call-detail-card"><small>Agent Status</small><strong>{{ optional($callLog->feedbackStatus)->display_name ?: optional($callLog->feedbackStatus)->status_name ?: '—' }}</strong></div>
            <div class="call-detail-card"><small>Notes</small><strong>{{ $callLog->remark ?: '—' }}</strong></div>
            <div class="call-detail-card"><small>Transcript Status</small><strong>{{ ucfirst(str_replace('_',' ',$callLog->transcription_status ?: 'Not requested')) }}</strong></div>
        </section>
        <section class="call-detail-card call-detail-wide"><h2>Audio Recording</h2>@if($callLog->recording_url)<audio controls preload="metadata" src="{{ route('call-management.recording',$callLog) }}"></audio>@else<p>Recording is unavailable.</p>@endif</section>
        <section class="call-detail-card call-detail-wide">
            <div style="display:flex;align-items:center;justify-content:space-between;gap:15px;margin-bottom:14px"><h2 style="margin:0">Transcript</h2>
                @can('call_management_transcribe')
                    @if(!in_array($callLog->transcription_status,['completed','queued','processing'],true) && $callLog->recording_url)
                        <form method="POST" action="{{ route('customer-call-history.transcribe',$callLog) }}">@csrf<button class="call-transcribe-btn" type="submit"><i class="material-icons">auto_awesome</i> Generate Transcript</button></form>
                    @elseif(in_array($callLog->transcription_status,['queued','processing'],true))<button class="call-transcribe-btn" disabled>Processing…</button>@endif
                @endcan
            </div>
            @if($callLog->transcription_status === 'completed')
                @forelse($transcriptEntries as $line)@php($speaker=(int)data_get($line,'speaker_id',0))<div class="transcript-line {{ $speaker % 2 ? 'alt' : '' }}"><b>Speaker {{ $speaker+1 }}</b>{{ data_get($line,'transcript') }}</div>@empty<div class="transcript-line">{{ $callLog->transcript ?: 'No transcript returned.' }}</div>@endforelse
            @elseif($callLog->transcription_status === 'failed')<p>Transcription failed. An authorized Super Admin can retry it.</p>@else<p>Transcript has not been generated yet.</p>@endif
        </section>
    </div>
</x-app-layout>
