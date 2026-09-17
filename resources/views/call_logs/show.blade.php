<x-app-layout>
  @php
    $contact = $callLog->lead?->contacts->first();
    $connected = (int) $callLog->status === 1 && !empty($callLog->recording_url);
    $duration = (int) $callLog->duration;
    $recordingDuration = (int) $callLog->recording_duration;
    $formatDuration = static fn ($seconds) => sprintf('%02d:%02d:%02d', intdiv($seconds, 3600), intdiv($seconds % 3600, 60), $seconds % 60);
    $transcriptInProgress = $callLog->transcription_status === 'processing' && $callLog->sarvam_job_id
      && $callLog->updated_at?->gt(now()->subMinutes(15));
    // Speaker IDs may arrive as 0/1, "1", or "SPEAKER_01". They are numbered
    // 1, 2, ... in order of first appearance, and consecutive lines from the
    // same speaker are merged into one bubble.
    $speakerNumbers = [];
    $conversation = collect(data_get($callLog->diarized_transcript, 'entries', []))->reduce(function ($groups, $line) use (&$speakerNumbers) {
      $text = trim((string) data_get($line, 'transcript', ''));
      if ($text === '') return $groups;
      $rawSpeaker = (string) data_get($line, 'speaker_id', '0');
      $key = preg_match('/\d+/', $rawSpeaker, $digits) ? (int) $digits[0] : $rawSpeaker;
      $speakerNumbers[$key] ??= count($speakerNumbers) + 1;
      $speaker = $speakerNumbers[$key];
      $last = count($groups) - 1;
      if ($last >= 0 && $groups[$last]['speaker'] === $speaker) {
        $groups[$last]['text'] .= ' '.$text;
      } else {
        $groups[] = ['speaker' => $speaker, 'text' => $text, 'start' => data_get($line, 'start_time_seconds')];
      }
      return $groups;
    }, []);
  @endphp

  <style>
    .call-detail-page{max-width:1500px;margin:0 auto}.call-detail-header{display:flex;align-items:center;justify-content:space-between;gap:20px;margin-bottom:24px}.call-detail-title{margin:0;font-size:28px;font-weight:600;color:inherit}.call-detail-subtitle{margin:6px 0 0;color:#8e9abb;font-size:14px}.call-detail-back{display:inline-flex;align-items:center;gap:8px;flex-shrink:0;margin:0;border-radius:8px;text-transform:none}.call-detail-back .material-icons{font-size:18px}
    .call-summary{display:grid;grid-template-columns:repeat(5,minmax(0,1fr));gap:16px;margin-bottom:20px}.call-summary-item,.call-detail-card{border:1px solid rgba(94,129,205,.28);border-radius:12px;background:rgba(10,29,68,.45);box-shadow:0 8px 24px rgba(0,0,0,.08)}.call-summary-item{padding:18px 20px}.call-summary-label,.detail-label{color:#8e9abb;font-size:12px;font-weight:600;letter-spacing:.04em;text-transform:uppercase}.call-summary-value{display:block;margin-top:8px;font-size:17px;font-weight:600;overflow-wrap:anywhere}
    .call-status{display:inline-flex;align-items:center;gap:7px;padding:7px 12px;border:1px solid currentColor;border-radius:999px;font-size:12px;font-weight:700;letter-spacing:.06em;text-transform:uppercase}.call-status:before{width:7px;height:7px;border-radius:50%;background:currentColor;content:''}.call-status.connected{color:#20d9a1}.call-status.not-connected{color:#ff6584}
    .call-detail-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:20px}.call-detail-card{min-width:0;overflow:hidden}.call-detail-card.full-width{grid-column:1/-1}.detail-card-header{display:flex;align-items:center;gap:12px;padding:18px 22px;border-bottom:1px solid rgba(94,129,205,.22)}.detail-card-icon{display:grid;width:38px;height:38px;place-items:center;border-radius:10px;background:rgba(31,182,255,.12);color:#22b9ff}.detail-card-icon .material-icons{font-size:21px}.detail-card-title{margin:0;font-size:17px;font-weight:600}
    .detail-list{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));margin:0}.detail-item{min-width:0;padding:18px 22px;border-bottom:1px solid rgba(94,129,205,.14)}.detail-item:nth-child(odd){border-right:1px solid rgba(94,129,205,.14)}.detail-value{display:block;margin-top:7px;color:inherit;font-size:15px;font-weight:500;line-height:1.5;overflow-wrap:anywhere}.recording-body{padding:22px}.recording-body audio{display:block;width:100%;max-width:720px}.recording-unavailable{display:flex;align-items:center;gap:8px;margin:0;color:#8e9abb}
    @media(max-width:991px){.call-summary{grid-template-columns:repeat(2,minmax(0,1fr))}.call-detail-grid{grid-template-columns:1fr}.call-detail-card.full-width{grid-column:auto}}@media(max-width:575px){.call-detail-header{align-items:flex-start;flex-direction:column}.call-summary,.detail-list{grid-template-columns:1fr}.detail-item:nth-child(odd){border-right:0}}
    .call-message{margin-bottom:16px;padding:13px 16px;border:1px solid rgba(38,212,174,.2);border-radius:11px;background:rgba(38,212,174,.1);color:#65e7c2;font-size:14px}.call-message.error{border-color:rgba(239,77,123,.2);background:rgba(239,77,123,.1);color:#ff8aaa}
    .transcript-header{justify-content:space-between}.transcript-header-title{display:flex;align-items:center;gap:12px}.call-transcribe-btn{display:inline-flex;align-items:center;gap:8px;padding:10px 16px;border:0;border-radius:10px;background:linear-gradient(135deg,#26d4ae,#2dd4ee);color:#061329;font-size:13px;font-weight:800;white-space:nowrap;cursor:pointer}.call-transcribe-btn[disabled]{cursor:not-allowed;opacity:.55}.call-transcribe-btn .material-icons{font-size:18px}
    .transcript-body{padding:22px}.transcript-conversation{display:grid;gap:15px}.transcript-row{display:flex;align-items:flex-start;gap:11px}.transcript-row.alt{flex-direction:row-reverse}.transcript-avatar{display:grid;place-items:center;width:36px;height:36px;flex:0 0 36px;border:1px solid rgba(45,212,238,.38);border-radius:11px;background:rgba(45,212,238,.1);color:#4bddf4;font-size:12px;font-weight:800}.transcript-row.alt .transcript-avatar{border-color:rgba(38,212,174,.38);background:rgba(38,212,174,.1);color:#4de0ba}.transcript-bubble{width:min(82%,860px);padding:13px 15px;border:1px solid rgba(45,212,238,.16);border-radius:4px 13px 13px 13px;background:rgba(13,47,91,.67);color:#dce6fb;font-size:14px;line-height:1.65}.transcript-row.alt .transcript-bubble{border-color:rgba(38,212,174,.16);border-radius:13px 4px 13px 13px;background:rgba(10,55,75,.58)}.transcript-meta{display:flex;align-items:center;gap:9px;margin-bottom:5px;color:#45d8f0;font-size:10px;font-weight:800;letter-spacing:.08em;text-transform:uppercase}.transcript-row.alt .transcript-meta{justify-content:flex-end;color:#4bdcb7}.transcript-time{color:#7185b6;font-size:10px;font-weight:600;letter-spacing:0;text-transform:none}.transcript-empty{padding:22px;border:1px dashed rgba(85,126,218,.35);border-radius:12px;color:#8295c3;text-align:center}
    @media(max-width:640px){.transcript-header{align-items:flex-start;flex-direction:column}.transcript-bubble{width:calc(100% - 47px);font-size:13px}.transcript-row.alt{flex-direction:row}.transcript-row.alt .transcript-meta{justify-content:flex-start}}
  </style>

  <div class="call-detail-page">
    <div class="call-detail-header">
      <div><h2 class="call-detail-title">Call Details</h2><p class="call-detail-subtitle">Complete customer conversation information</p></div>
      <a href="{{ route('call-management.index') }}" class="btn btn-info call-detail-back"><i class="material-icons">arrow_back</i> Back to Call History</a>
    </div>

    @if(session('success'))<div class="call-message">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="call-message error">{{ session('error') }}</div>@endif

    <div class="call-summary">
      <div class="call-summary-item"><span class="call-summary-label">Status</span><span class="call-summary-value"><span class="call-status {{ $connected ? 'connected' : 'not-connected' }}">{{ $connected ? 'Connected' : 'Not Connected' }}</span></span></div>
      <div class="call-summary-item"><span class="call-summary-label">Direction</span><span class="call-summary-value">{{ $callLog->direction === 'inbound' ? 'Inbound' : 'Outbound' }}</span></div>
      <div class="call-summary-item"><span class="call-summary-label">Contact Number</span><span class="call-summary-value">{{ $callLog->number ?: ($contact?->phone_number ?: '-') }}</span></div>
      <div class="call-summary-item"><span class="call-summary-label">Call Date</span><span class="call-summary-value">{{ $callLog->started_at?->format('d M Y, h:i A') ?: '-' }}</span></div>
      <div class="call-summary-item"><span class="call-summary-label">Duration</span><span class="call-summary-value">{{ $formatDuration($duration) }}</span></div>
    </div>

    <div class="call-detail-grid">
      <section class="call-detail-card">
        <div class="detail-card-header"><span class="detail-card-icon"><i class="material-icons">person_outline</i></span><h3 class="detail-card-title">Customer &amp; Lead</h3></div>
        <div class="detail-list">
          <div class="detail-item"><span class="detail-label">Customer Name</span><span class="detail-value">{{ $contact?->name ?: '-' }}</span></div>
          <div class="detail-item"><span class="detail-label">Lead / Company</span><span class="detail-value">{{ $callLog->lead?->company_name ?: '-' }}</span></div>
          <div class="detail-item"><span class="detail-label">Agent</span><span class="detail-value">{{ $callLog->user?->name ?: '-' }}</span></div>
          <div class="detail-item"><span class="detail-label">Agent Email</span><span class="detail-value">{{ $callLog->user?->email ?: '-' }}</span></div>
          <div class="detail-item"><span class="detail-label">Contact Number</span><span class="detail-value">{{ $callLog->number ?: ($contact?->phone_number ?: '-') }}</span></div>
          <div class="detail-item"><span class="detail-label">Lead Status</span><span class="detail-value">{{ $callLog->lead?->status_is?->display_name ?: ($callLog->lead?->status_is?->status_name ?: '-') }}</span></div>
        </div>
      </section>

      <section class="call-detail-card">
        <div class="detail-card-header"><span class="detail-card-icon"><i class="material-icons">schedule</i></span><h3 class="detail-card-title">Call Information</h3></div>
        <div class="detail-list">
          <div class="detail-item"><span class="detail-label">Started At</span><span class="detail-value">{{ $callLog->started_at?->format('d M Y, h:i:s A') ?: '-' }}</span></div>
          <div class="detail-item"><span class="detail-label">Answered At</span><span class="detail-value">{{ $callLog->answered_at?->format('d M Y, h:i:s A') ?: '-' }}</span></div>
          <div class="detail-item"><span class="detail-label">Completed At</span><span class="detail-value">{{ $callLog->completed_at?->format('d M Y, h:i:s A') ?: '-' }}</span></div>
          <div class="detail-item"><span class="detail-label">Call Duration</span><span class="detail-value">{{ $formatDuration($duration) }}</span></div>
          <div class="detail-item"><span class="detail-label">Recording Duration</span><span class="detail-value">{{ $recordingDuration ? $formatDuration($recordingDuration) : '-' }}</span></div>
        </div>
      </section>

      <section class="call-detail-card full-width">
        <div class="detail-card-header"><span class="detail-card-icon"><i class="material-icons">assignment_turned_in</i></span><h3 class="detail-card-title">Call Outcome</h3></div>
        <div class="detail-list">
          <div class="detail-item"><span class="detail-label">Feedback Status</span><span class="detail-value">{{ $callLog->feedbackStatus?->display_name ?: ($callLog->feedbackStatus?->status_name ?: '-') }}</span></div>
          <div class="detail-item"><span class="detail-label">Remark</span><span class="detail-value">{{ $callLog->remark ?: '-' }}</span></div>
          <div class="detail-item"><span class="detail-label">Call Cost</span><span class="detail-value">{{ $callLog->cost !== null ? number_format((float) $callLog->cost, 2) : '-' }}</span></div>
        </div>
      </section>

      <section class="call-detail-card full-width">
        <div class="detail-card-header"><span class="detail-card-icon"><i class="material-icons">play_circle_outline</i></span><h3 class="detail-card-title">Call Recording</h3></div>
        <div class="recording-body">
          @if(!empty($callLog->recording_url))
            <audio controls preload="metadata"><source src="{{ route('call-management.recording', $callLog) }}" type="audio/mpeg">Your browser does not support audio playback.</audio>
          @else
            <p class="recording-unavailable"><i class="material-icons">info_outline</i> Recording is processing or unavailable.</p>
          @endif
        </div>
      </section>

      <section class="call-detail-card full-width">
        <div class="detail-card-header transcript-header">
          <div class="transcript-header-title"><span class="detail-card-icon"><i class="material-icons">subject</i></span><h3 class="detail-card-title">Call Transcript</h3></div>
          @can('call_management_transcribe')
            @if($transcriptInProgress)
              <button class="call-transcribe-btn" type="button" disabled><i class="material-icons">hourglass_top</i> Processing…</button>
            @elseif($callLog->recording_url && $callLog->transcription_status === 'completed')
              <form method="POST" action="{{ route('call-management.transcribe', $callLog) }}" onsubmit="return confirm('Generate the transcript again? The current transcript will be replaced.')">@csrf<input type="hidden" name="regenerate" value="1"><button class="call-transcribe-btn" type="submit"><i class="material-icons">refresh</i> Regenerate Transcript</button></form>
            @elseif($callLog->recording_url)
              <form method="POST" action="{{ route('call-management.transcribe', $callLog) }}">@csrf<button class="call-transcribe-btn" type="submit"><i class="material-icons">auto_awesome</i> {{ $callLog->transcription_status === 'failed' ? 'Retry Transcript' : 'Generate Transcript' }}</button></form>
            @endif
          @endcan
        </div>
        <div class="transcript-body">
          @if($callLog->transcription_status === 'completed')
            <div class="transcript-conversation">
              @forelse($conversation as $line)
                @php($speaker = (int) $line['speaker'])
                <div class="transcript-row {{ $speaker % 2 === 0 ? 'alt' : '' }}"><span class="transcript-avatar">S{{ $speaker }}</span><div class="transcript-bubble"><div class="transcript-meta"><span>Speaker {{ $speaker }}</span>@if($line['start'] !== null)<span class="transcript-time">{{ gmdate('i:s', (int) $line['start']) }}</span>@endif</div>{{ $line['text'] }}</div></div>
              @empty
                <div class="transcript-empty">{{ $callLog->transcript ?: 'No transcript returned.' }}</div>
              @endforelse
            </div>
          @elseif($transcriptInProgress)
            <p class="recording-unavailable"><i class="material-icons">hourglass_top</i> Generating transcript. It will appear here automatically.</p>
          @elseif($callLog->transcription_status === 'failed')
            <p class="recording-unavailable"><i class="material-icons">error_outline</i> Transcript could not be generated. Please try again.</p>
          @elseif(empty($callLog->recording_url))
            <p class="recording-unavailable"><i class="material-icons">info_outline</i> A transcript can be generated once the recording is available.</p>
          @else
            <p class="recording-unavailable"><i class="material-icons">info_outline</i> Transcript has not been generated yet.</p>
          @endif
        </div>
      </section>
    </div>
  </div>
  @if($transcriptInProgress)
    <script>setTimeout(function () { window.location.reload(); }, 5000);</script>
  @endif
</x-app-layout>
