<x-app-layout>
  @php
    $contact = $callLog->lead?->contacts->first();
    $connected = (int) $callLog->status === 1 && !empty($callLog->recording_url);
    $duration = (int) $callLog->duration;
    $recordingDuration = (int) $callLog->recording_duration;
    $formatDuration = static function ($seconds) {
        return sprintf('%02d:%02d:%02d', intdiv($seconds, 3600), intdiv($seconds % 3600, 60), $seconds % 60);
    };
  @endphp

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header card-header-icon card-header-theme">
          <div class="card-icon"><i class="material-icons">phone</i></div>
          <h4 class="card-title">
            Call Details
            <a href="{{ route('call-management.index') }}" class="btn btn-sm btn-info float-right">
              <i class="material-icons">arrow_back</i> Back to Call History
            </a>
          </h4>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <h5 class="text-primary">Customer &amp; Lead</h5>
              <table class="table table-bordered">
                <tr><th width="40%">Agent</th><td>{{ $callLog->user?->name ?: '-' }}</td></tr>
                <tr><th>Agent Email</th><td>{{ $callLog->user?->email ?: '-' }}</td></tr>
                <tr><th>Customer Name</th><td>{{ $contact?->name ?: '-' }}</td></tr>
                <tr><th>Lead / Company</th><td>{{ $callLog->lead?->company_name ?: '-' }}</td></tr>
                <tr><th>Contact Number</th><td>{{ $callLog->number ?: ($contact?->phone_number ?: '-') }}</td></tr>
                <tr><th>Lead Status</th><td>{{ $callLog->lead?->status_is?->display_name ?: ($callLog->lead?->status_is?->status_name ?: '-') }}</td></tr>
              </table>
            </div>

            <div class="col-md-6">
              <h5 class="text-primary">Call Information</h5>
              <table class="table table-bordered">
                <tr><th width="40%">Call Status</th><td><span class="badge {{ $connected ? 'badge-success' : 'badge-danger' }}">{{ $connected ? 'Connected' : 'Not Connected' }}</span></td></tr>
                <tr><th>Provider Status</th><td>{{ $callLog->plivo_status ?: '-' }}</td></tr>
                <tr><th>Started At</th><td>{{ $callLog->started_at?->format('d/m/Y h:i:s A') ?: '-' }}</td></tr>
                <tr><th>Answered At</th><td>{{ $callLog->answered_at?->format('d/m/Y h:i:s A') ?: '-' }}</td></tr>
                <tr><th>Completed At</th><td>{{ $callLog->completed_at?->format('d/m/Y h:i:s A') ?: '-' }}</td></tr>
                <tr><th>Call Duration</th><td>{{ $formatDuration($duration) }}</td></tr>
                <tr><th>Recording Duration</th><td>{{ $recordingDuration ? $formatDuration($recordingDuration) : '-' }}</td></tr>
              </table>
            </div>
          </div>

          <div class="row mt-3">
            <div class="col-md-6">
              <h5 class="text-primary">Call Outcome</h5>
              <table class="table table-bordered">
                <tr><th width="40%">Feedback Status</th><td>{{ $callLog->feedbackStatus?->display_name ?: ($callLog->feedbackStatus?->status_name ?: '-') }}</td></tr>
                <tr><th>Remark</th><td>{{ $callLog->remark ?: '-' }}</td></tr>
                <tr><th>Cost</th><td>{{ $callLog->cost !== null ? number_format((float) $callLog->cost, 6) : '-' }}</td></tr>
              </table>
            </div>

            <div class="col-md-6">
              <h5 class="text-primary">Provider Details</h5>
              <table class="table table-bordered">
                <tr><th width="40%">Call UUID</th><td class="text-break">{{ $callLog->plivo_call_uuid ?: '-' }}</td></tr>
                <tr><th>B-Leg UUID</th><td class="text-break">{{ $callLog->plivo_b_leg_uuid ?: '-' }}</td></tr>
                <tr><th>Recording ID</th><td class="text-break">{{ $callLog->recording_id ?: '-' }}</td></tr>
              </table>
            </div>
          </div>

          <div class="mt-3">
            <h5 class="text-primary">Recording</h5>
            @if(!empty($callLog->recording_url))
              <audio controls preload="metadata" style="width:100%;max-width:600px">
                <source src="{{ route('call-management.recording', $callLog) }}" type="audio/mpeg">
                Your browser does not support audio playback.
              </audio>
            @else
              <p class="text-muted">Recording is processing or unavailable.</p>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
