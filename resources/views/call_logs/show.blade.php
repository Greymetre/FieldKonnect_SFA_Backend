<x-app-layout>
  @php
    $contact = $callLog->lead?->contacts->first();
    $connected = (int) $callLog->status === 1 && !empty($callLog->recording_url);
    $duration = (int) $callLog->duration;
    $recordingDuration = (int) $callLog->recording_duration;
    $formatDuration = static fn ($seconds) => sprintf('%02d:%02d:%02d', intdiv($seconds, 3600), intdiv($seconds % 3600, 60), $seconds % 60);
  @endphp

  <style>
    .call-detail-page{max-width:1500px;margin:0 auto}.call-detail-header{display:flex;align-items:center;justify-content:space-between;gap:20px;margin-bottom:24px}.call-detail-title{margin:0;font-size:28px;font-weight:600;color:inherit}.call-detail-subtitle{margin:6px 0 0;color:#8e9abb;font-size:14px}.call-detail-back{display:inline-flex;align-items:center;gap:8px;flex-shrink:0;margin:0;border-radius:8px;text-transform:none}.call-detail-back .material-icons{font-size:18px}
    .call-summary{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:16px;margin-bottom:20px}.call-summary-item,.call-detail-card{border:1px solid rgba(94,129,205,.28);border-radius:12px;background:rgba(10,29,68,.45);box-shadow:0 8px 24px rgba(0,0,0,.08)}.call-summary-item{padding:18px 20px}.call-summary-label,.detail-label{color:#8e9abb;font-size:12px;font-weight:600;letter-spacing:.04em;text-transform:uppercase}.call-summary-value{display:block;margin-top:8px;font-size:17px;font-weight:600;overflow-wrap:anywhere}
    .call-status{display:inline-flex;align-items:center;gap:7px;padding:7px 12px;border:1px solid currentColor;border-radius:999px;font-size:12px;font-weight:700;letter-spacing:.06em;text-transform:uppercase}.call-status:before{width:7px;height:7px;border-radius:50%;background:currentColor;content:''}.call-status.connected{color:#20d9a1}.call-status.not-connected{color:#ff6584}
    .call-detail-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:20px}.call-detail-card{min-width:0;overflow:hidden}.call-detail-card.full-width{grid-column:1/-1}.detail-card-header{display:flex;align-items:center;gap:12px;padding:18px 22px;border-bottom:1px solid rgba(94,129,205,.22)}.detail-card-icon{display:grid;width:38px;height:38px;place-items:center;border-radius:10px;background:rgba(31,182,255,.12);color:#22b9ff}.detail-card-icon .material-icons{font-size:21px}.detail-card-title{margin:0;font-size:17px;font-weight:600}
    .detail-list{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));margin:0}.detail-item{min-width:0;padding:18px 22px;border-bottom:1px solid rgba(94,129,205,.14)}.detail-item:nth-child(odd){border-right:1px solid rgba(94,129,205,.14)}.detail-value{display:block;margin-top:7px;color:inherit;font-size:15px;font-weight:500;line-height:1.5;overflow-wrap:anywhere}.recording-body{padding:22px}.recording-body audio{display:block;width:100%;max-width:720px}.recording-unavailable{display:flex;align-items:center;gap:8px;margin:0;color:#8e9abb}
    @media(max-width:991px){.call-summary{grid-template-columns:repeat(2,minmax(0,1fr))}.call-detail-grid{grid-template-columns:1fr}.call-detail-card.full-width{grid-column:auto}}@media(max-width:575px){.call-detail-header{align-items:flex-start;flex-direction:column}.call-summary,.detail-list{grid-template-columns:1fr}.detail-item:nth-child(odd){border-right:0}}
  </style>

  <div class="call-detail-page">
    <div class="call-detail-header">
      <div><h2 class="call-detail-title">Call Details</h2><p class="call-detail-subtitle">Complete customer conversation information</p></div>
      <a href="{{ route('call-management.index') }}" class="btn btn-info call-detail-back"><i class="material-icons">arrow_back</i> Back to Call History</a>
    </div>

    <div class="call-summary">
      <div class="call-summary-item"><span class="call-summary-label">Status</span><span class="call-summary-value"><span class="call-status {{ $connected ? 'connected' : 'not-connected' }}">{{ $connected ? 'Connected' : 'Not Connected' }}</span></span></div>
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
    </div>
  </div>
</x-app-layout>
