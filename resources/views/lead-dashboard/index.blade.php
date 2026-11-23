<x-app-layout>
  @php
    $fmtDuration = fn ($seconds) => sprintf('%d:%02d:%02d', intdiv($seconds, 3600), intdiv($seconds % 3600, 60), $seconds % 60);
    $pct = fn ($part, $whole) => $whole > 0 ? round($part * 100 / $whole) : 0;
    $trendMax = max(1, collect($trend)->max('total'));
    $typeMax = max(1, $byType->max('total') ?? 0);
    $sourceMax = max(1, $bySource->max('total') ?? 0);
    $outcomeMax = max(1, $outcomes->max('total') ?? 0);
    $progressTotal = collect($progress)->sum('total');
    $rangeLabel = $from->format('d M Y').' – '.$to->format('d M Y');
  @endphp

  <style>
    .ldb {
      --ldb-series: #3987e5;
      --ldb-good: #199e70;
      --ldb-warn: #c98500;
      --ldb-bad: #e66767;
      --ldb-muted-fill: rgba(148, 163, 184, .35);
    }
    :root[data-theme="light"] .ldb, body:not(.fk-dark-shell) .ldb {
      --ldb-series: #2a78d6;
      --ldb-good: #1baf7a;
      --ldb-warn: #eda100;
      --ldb-bad: #e34948;
      --ldb-muted-fill: rgba(100, 116, 139, .3);
    }
    .ldb * { letter-spacing: normal; }
    .ldb-panel { border: 1px solid var(--fk-list-border); border-radius: 16px; background: var(--fk-list-panel); color: var(--fk-list-text); }

    /* Filters */
    .ldb-filters { display: flex; flex-wrap: wrap; align-items: end; gap: 12px; padding: 12px 16px; margin-bottom: 16px; }
    .ldb-field label { display: block; margin: 0 0 4px; font-size: 10.5px; font-weight: 600; letter-spacing: .05em; text-transform: uppercase; color: var(--fk-list-muted); }
    .ldb-field .form-control { height: 38px !important; min-width: 170px; padding: 0 12px; border: 1px solid var(--fk-list-border-strong) !important; border-radius: 10px !important; background: var(--fk-list-control) !important; color: var(--fk-list-text) !important; font-size: 13px; color-scheme: dark; }
    .ldb-quick { display: flex; gap: 6px; }
    .ldb-chip-btn { height: 38px; padding: 0 12px; border: 1px solid var(--fk-list-border-strong); border-radius: 10px; background: var(--fk-list-control); color: var(--fk-list-text); font-size: 12.5px; font-weight: 600; cursor: pointer; }
    .ldb-chip-btn:hover, .ldb-chip-btn.is-active { border-color: var(--fk-list-accent); color: var(--fk-list-accent); }
    .ldb-apply { height: 38px; padding: 0 18px; border: 0; border-radius: 10px; background: linear-gradient(135deg, var(--fk-list-accent), var(--fk-list-accent-2)); color: var(--fk-list-primary-text); font-weight: 600; cursor: pointer; }
    .ldb-range { margin-left: auto; font-size: 12.5px; color: var(--fk-list-muted); }

    /* KPI tiles */
    .ldb-kpis { display: grid; grid-template-columns: repeat(6, minmax(0, 1fr)); gap: 12px; margin-bottom: 16px; }
    .ldb-kpi { padding: 14px 16px; }
    .ldb-kpi-label { display: flex; align-items: center; gap: 8px; font-size: 12px; font-weight: 500; color: var(--fk-list-muted); }
    .ldb-kpi-label .material-icons { font-size: 17px; color: var(--fk-list-accent); }
    .ldb-kpi-value { margin-top: 8px; font-size: 26px; font-weight: 700; line-height: 1.1; color: var(--fk-list-heading); font-variant-numeric: tabular-nums; }
    .ldb-kpi-sub { margin-top: 4px; font-size: 11.5px; color: var(--fk-list-muted); }
    .ldb-kpi-sub b { color: var(--fk-list-text); font-weight: 600; }
    .ldb-kpi-sub .is-bad { color: var(--ldb-bad); }

    /* Grid of cards */
    .ldb-grid { display: grid; grid-template-columns: repeat(12, minmax(0, 1fr)); gap: 16px; margin-bottom: 16px; }
    .ldb-span-12 { grid-column: span 12; }
    .ldb-span-8 { grid-column: span 8; }
    .ldb-span-6 { grid-column: span 6; }
    .ldb-span-4 { grid-column: span 4; }
    .ldb-card-head { display: flex; align-items: baseline; justify-content: space-between; gap: 10px; padding: 14px 16px 0; }
    .ldb-card-head h3 { margin: 0; font-size: 14.5px; font-weight: 700; color: var(--fk-list-heading); }
    .ldb-card-head small { font-size: 12px; color: var(--fk-list-muted); }
    .ldb-card-body { padding: 12px 16px 16px; }
    .ldb-empty { padding: 26px 0; text-align: center; font-size: 13px; color: var(--fk-list-muted); }

    /* Column chart (new leads per day) */
    .ldb-columns { display: flex; align-items: flex-end; gap: 2px; height: 170px; padding-top: 8px; border-bottom: 1px solid var(--fk-list-border); }
    .ldb-col { position: relative; flex: 1 1 0; height: 100%; display: flex; align-items: flex-end; cursor: default; }
    .ldb-col span { display: block; width: 100%; min-height: 2px; border-radius: 4px 4px 0 0; background: var(--ldb-series); }
    .ldb-col.is-zero span { background: var(--ldb-muted-fill); }
    .ldb-col:hover span { filter: brightness(1.2); }
    .ldb-col-axis { display: flex; justify-content: space-between; margin-top: 6px; font-size: 11px; color: var(--fk-list-muted); }

    /* Horizontal bars */
    .ldb-bars { display: flex; flex-direction: column; gap: 10px; }
    .ldb-bar-row { display: grid; grid-template-columns: minmax(90px, 140px) minmax(0, 1fr) 44px; align-items: center; gap: 10px; }
    .ldb-bar-label { font-size: 12.5px; color: var(--fk-list-text); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .ldb-bar-track { height: 10px; border-radius: 0 4px 4px 0; background: transparent; }
    .ldb-bar-fill { display: block; height: 100%; min-width: 2px; border-radius: 0 4px 4px 0; background: var(--ldb-series); }
    .ldb-bar-value { font-size: 12.5px; font-weight: 600; text-align: right; color: var(--fk-list-heading); font-variant-numeric: tabular-nums; }

    /* Stacked calling progress */
    .ldb-stack { display: flex; gap: 2px; height: 14px; margin: 6px 0 14px; }
    .ldb-stack span { display: block; height: 100%; }
    .ldb-stack span:first-child { border-radius: 4px 0 0 4px; }
    .ldb-stack span:last-child { border-radius: 0 4px 4px 0; }
    .ldb-stack span:only-child { border-radius: 4px; }
    .ldb-legend { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
    .ldb-legend-item { display: flex; align-items: center; gap: 8px; padding: 8px 10px; border-radius: 10px; background: var(--fk-list-control); text-decoration: none !important; }
    .ldb-legend-item i { flex: 0 0 10px; width: 10px; height: 10px; border-radius: 3px; }
    .ldb-legend-item span { flex: 1 1 auto; font-size: 12.5px; color: var(--fk-list-text); }
    .ldb-legend-item b { font-size: 13px; color: var(--fk-list-heading); font-variant-numeric: tabular-nums; }
    .ldb-legend-item small { font-size: 11px; color: var(--fk-list-muted); }
    .ldb-legend-item:hover { outline: 1px solid var(--fk-list-accent); }

    /* Agents table */
    .ldb-table { width: 100%; border-collapse: collapse; }
    .ldb-table th { padding: 8px 10px; font-size: 11px; font-weight: 600; letter-spacing: .05em; text-transform: uppercase; color: var(--fk-list-muted); border-bottom: 1px solid var(--fk-list-border); text-align: left; white-space: nowrap; }
    .ldb-table td { padding: 10px; font-size: 13px; color: var(--fk-list-text); border-bottom: 1px solid var(--fk-list-border); white-space: nowrap; font-variant-numeric: tabular-nums; }
    .ldb-table tr:last-child td { border-bottom: 0; }
    .ldb-table .is-num { text-align: right; }
    .ldb-agent { display: flex; align-items: center; gap: 8px; font-weight: 600; color: var(--fk-list-heading); }
    .ldb-avatar { flex: 0 0 28px; width: 28px; height: 28px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 10.5px; font-weight: 700; color: var(--fk-list-accent); background: var(--fk-list-control); border: 1px solid var(--fk-list-border-strong); }
    .ldb-mini { display: inline-block; width: 60px; height: 6px; margin-right: 6px; border-radius: 3px; background: var(--ldb-muted-fill); vertical-align: middle; overflow: hidden; }
    .ldb-mini span { display: block; height: 100%; background: var(--ldb-good); }
    .ldb-overdue { color: var(--ldb-bad); font-weight: 600; }

    /* Follow-ups */
    .ldb-follow { list-style: none; margin: 0; padding: 0; }
    .ldb-follow li { display: flex; gap: 10px; padding: 9px 0; border-bottom: 1px solid var(--fk-list-border); }
    .ldb-follow li:last-child { border-bottom: 0; }
    .ldb-follow-date { flex: 0 0 64px; text-align: center; padding: 4px 0; border-radius: 8px; background: var(--fk-list-control); font-size: 11px; color: var(--fk-list-muted); }
    .ldb-follow-date b { display: block; font-size: 15px; color: var(--fk-list-heading); }
    .ldb-follow-date.is-late { background: rgba(230, 103, 103, .15); color: var(--ldb-bad); }
    .ldb-follow-date.is-late b { color: var(--ldb-bad); }
    .ldb-follow-main { min-width: 0; }
    .ldb-follow-main strong { display: block; font-size: 13px; color: var(--fk-list-heading); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .ldb-follow-main a, .ldb-follow-main span { font-size: 12px; color: var(--fk-list-muted); }
    .ldb-follow-main a { color: var(--fk-list-accent) !important; }

    /* Tooltip */
    .ldb-tip { position: fixed; z-index: 2000; pointer-events: none; padding: 6px 10px; border-radius: 8px; background: #0b1230; border: 1px solid var(--fk-list-border-strong); color: #e8f0ff; font-size: 12px; white-space: nowrap; box-shadow: 0 8px 20px rgba(0,0,0,.35); opacity: 0; transition: opacity .1s ease; }

    @media (max-width: 1399px) { .ldb-kpis { grid-template-columns: repeat(3, minmax(0, 1fr)); } }
    @media (max-width: 1199px) { .ldb-span-8, .ldb-span-6, .ldb-span-4 { grid-column: span 12; } }
    @media (max-width: 575px) { .ldb-kpis { grid-template-columns: repeat(2, minmax(0, 1fr)); } .ldb-legend { grid-template-columns: 1fr; } .ldb-range { margin-left: 0; } }
  </style>

  <section class="fk-manual-listing ldb">
    <div class="fk-list-page-head">
      <div class="fk-list-heading-block">
        <div class="fk-list-breadcrumb">
          <span>Lead Management</span><span>&rsaquo;</span><span class="fk-current">Lead Dashboard</span>
        </div>
        <div class="fk-list-title-row">
          <h1 class="fk-list-title">Lead Dashboard</h1>
        </div>
      </div>
    </div>

    <form method="GET" action="{{ route('lead-dashboard.index') }}" class="ldb-panel ldb-filters" id="ldbFilters">
      <div class="ldb-field">
        <label for="ldbFrom">From</label>
        <input type="date" class="form-control" id="ldbFrom" name="from" value="{{ $from->toDateString() }}">
      </div>
      <div class="ldb-field">
        <label for="ldbTo">To</label>
        <input type="date" class="form-control" id="ldbTo" name="to" value="{{ $to->toDateString() }}">
      </div>
      <div class="ldb-quick">
        <button type="button" class="ldb-chip-btn" data-days="7">7 days</button>
        <button type="button" class="ldb-chip-btn" data-days="30">30 days</button>
        <button type="button" class="ldb-chip-btn" data-days="90">90 days</button>
      </div>
      <div class="ldb-field">
        <label for="ldbUser">Assigned to</label>
        <select class="form-control" id="ldbUser" name="user_id">
          <option value="">All users</option>
          @foreach($users as $user)
          <option value="{{ $user->id }}" {{ (int) $userId === (int) $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
          @endforeach
        </select>
      </div>
      <button type="submit" class="ldb-apply">Apply</button>
      <span class="ldb-range">Activity for {{ $rangeLabel }}</span>
    </form>

    {{-- KPI tiles --}}
    <div class="ldb-kpis">
      <div class="ldb-panel ldb-kpi">
        <div class="ldb-kpi-label"><span class="material-icons">diamond</span>Total leads</div>
        <div class="ldb-kpi-value">{{ number_format($totalLeads) }}</div>
        <div class="ldb-kpi-sub"><b>{{ number_format($convertedLeads) }}</b> converted to customers</div>
      </div>
      <div class="ldb-panel ldb-kpi">
        <div class="ldb-kpi-label"><span class="material-icons">fiber_new</span>New leads</div>
        <div class="ldb-kpi-value">{{ number_format($newLeads) }}</div>
        <div class="ldb-kpi-sub">Added in this period</div>
      </div>
      <div class="ldb-panel ldb-kpi">
        <div class="ldb-kpi-label"><span class="material-icons">call</span>Calls made</div>
        <div class="ldb-kpi-value">{{ number_format($callsMade) }}</div>
        <div class="ldb-kpi-sub"><b>{{ number_format($callsConnected) }}</b> connected</div>
      </div>
      <div class="ldb-panel ldb-kpi">
        <div class="ldb-kpi-label"><span class="material-icons">percent</span>Connect rate</div>
        <div class="ldb-kpi-value">{{ $pct($callsConnected, $callsMade) }}%</div>
        <div class="ldb-kpi-sub">Talk time <b>{{ $fmtDuration($talkSeconds) }}</b></div>
      </div>
      <div class="ldb-panel ldb-kpi">
        <div class="ldb-kpi-label"><span class="material-icons">task_alt</span>Open tasks</div>
        <div class="ldb-kpi-value">{{ number_format($openTasks) }}</div>
        <div class="ldb-kpi-sub"><span class="{{ $overdueTasks ? 'is-bad' : '' }}"><b class="{{ $overdueTasks ? 'is-bad' : '' }}">{{ number_format($overdueTasks) }}</b> overdue</span></div>
      </div>
      <div class="ldb-panel ldb-kpi">
        <div class="ldb-kpi-label"><span class="material-icons">fact_check</span>Drafts to approve</div>
        <div class="ldb-kpi-value">{{ number_format($pendingDrafts) }}</div>
        <div class="ldb-kpi-sub">Email &amp; LinkedIn drafts</div>
      </div>
    </div>

    <div class="ldb-grid">
      {{-- New leads per day --}}
      <div class="ldb-panel ldb-span-8">
        <div class="ldb-card-head"><h3>New leads per day</h3><small>{{ number_format($newLeads) }} in {{ count($trend) }} days</small></div>
        <div class="ldb-card-body">
          <div class="ldb-columns" role="img" aria-label="New leads per day">
            @foreach($trend as $point)
            <div class="ldb-col {{ $point['total'] === 0 ? 'is-zero' : '' }}" data-tip="{{ $point['total'] }} new lead{{ $point['total'] === 1 ? '' : 's' }} · {{ $point['full'] }}">
              <span style="height: {{ $point['total'] === 0 ? 2 : max(3, $point['total'] * 100 / $trendMax) }}%"></span>
            </div>
            @endforeach
          </div>
          <div class="ldb-col-axis"><span>{{ $trend[0]['date'] ?? '' }}</span><span>Peak {{ $trendMax }} / day</span><span>{{ $trend[count($trend) - 1]['date'] ?? '' }}</span></div>
        </div>
      </div>

      {{-- Calling progress --}}
      <div class="ldb-panel ldb-span-4">
        <div class="ldb-card-head"><h3>Calling progress</h3><small>{{ number_format($progressTotal) }} leads</small></div>
        <div class="ldb-card-body">
          @php
            $progressColors = ['all_called' => 'var(--ldb-good)', 'partial' => 'var(--ldb-warn)', 'not_called' => 'var(--ldb-series)', 'no_phone' => 'var(--ldb-muted-fill)'];
            $progressLinks = ['all_called' => 'all_called', 'partial' => 'partial', 'not_called' => 'not_called'];
          @endphp
          @if($progressTotal === 0)
          <div class="ldb-empty">No leads yet.</div>
          @else
          <div class="ldb-stack" role="img" aria-label="Calling progress">
            @foreach($progress as $part)
            @if($part['total'] > 0)
            <span style="flex: {{ $part['total'] }}; background: {{ $progressColors[$part['key']] }}" data-tip="{{ $part['total'] }} · {{ $part['label'] }} ({{ $pct($part['total'], $progressTotal) }}%)"></span>
            @endif
            @endforeach
          </div>
          <div class="ldb-legend">
            @foreach($progress as $part)
            <a class="ldb-legend-item" href="{{ isset($progressLinks[$part['key']]) ? url('leads').'?call_progress='.$progressLinks[$part['key']] : '#' }}">
              <i style="background: {{ $progressColors[$part['key']] }}"></i>
              <span>{{ $part['label'] }}</span>
              <b>{{ number_format($part['total']) }}</b>
              <small>{{ $pct($part['total'], $progressTotal) }}%</small>
            </a>
            @endforeach
          </div>
          @endif
        </div>
      </div>

      {{-- Lead type --}}
      <div class="ldb-panel ldb-span-4">
        <div class="ldb-card-head"><h3>Leads by type</h3><small>All leads</small></div>
        <div class="ldb-card-body">
          @if($byType->isEmpty())
          <div class="ldb-empty">No leads yet.</div>
          @else
          <div class="ldb-bars">
            @foreach($byType as $row)
            <div class="ldb-bar-row" data-tip="{{ $row['total'] }} · {{ $row['label'] }}">
              <span class="ldb-bar-label" title="{{ $row['label'] }}">{{ $row['label'] }}</span>
              <span class="ldb-bar-track"><span class="ldb-bar-fill" style="width: {{ $row['total'] * 100 / $typeMax }}%"></span></span>
              <span class="ldb-bar-value">{{ number_format($row['total']) }}</span>
            </div>
            @endforeach
          </div>
          @endif
        </div>
      </div>

      {{-- Lead source --}}
      <div class="ldb-panel ldb-span-4">
        <div class="ldb-card-head"><h3>Leads by source</h3><small>All leads</small></div>
        <div class="ldb-card-body">
          @if($bySource->isEmpty())
          <div class="ldb-empty">No leads yet.</div>
          @else
          <div class="ldb-bars">
            @foreach($bySource->take(8) as $row)
            <div class="ldb-bar-row" data-tip="{{ $row['total'] }} · {{ $row['label'] }}">
              <span class="ldb-bar-label" title="{{ $row['label'] }}">{{ $row['label'] }}</span>
              <span class="ldb-bar-track"><span class="ldb-bar-fill" style="width: {{ $row['total'] * 100 / $sourceMax }}%"></span></span>
              <span class="ldb-bar-value">{{ number_format($row['total']) }}</span>
            </div>
            @endforeach
          </div>
          @endif
        </div>
      </div>

      {{-- Call outcomes --}}
      <div class="ldb-panel ldb-span-4">
        <div class="ldb-card-head"><h3>Call outcomes</h3><small>{{ number_format($callsMade) }} calls in period</small></div>
        <div class="ldb-card-body">
          @if($outcomes->isEmpty())
          <div class="ldb-empty">No calls in this period.</div>
          @else
          <div class="ldb-bars">
            @foreach($outcomes as $row)
            <div class="ldb-bar-row" data-tip="{{ $row['total'] }} · {{ $row['label'] }} ({{ $pct($row['total'], $callsMade) }}%)">
              <span class="ldb-bar-label" title="{{ $row['label'] }}">{{ $row['label'] }}</span>
              <span class="ldb-bar-track"><span class="ldb-bar-fill" style="width: {{ $row['total'] * 100 / $outcomeMax }}%"></span></span>
              <span class="ldb-bar-value">{{ number_format($row['total']) }}</span>
            </div>
            @endforeach
          </div>
          @endif
        </div>
      </div>

      {{-- Agents --}}
      <div class="ldb-panel ldb-span-8">
        <div class="ldb-card-head"><h3>Team performance</h3><small>Calls &amp; connects for {{ $rangeLabel }}</small></div>
        <div class="ldb-card-body" style="overflow-x:auto">
          @if($agents->isEmpty())
          <div class="ldb-empty">No assigned leads yet.</div>
          @else
          <table class="ldb-table">
            <thead>
              <tr>
                <th>Agent</th>
                <th class="is-num">Leads</th>
                <th>Leads called</th>
                <th class="is-num">Calls</th>
                <th class="is-num">Connected</th>
                <th class="is-num">Open tasks</th>
                <th class="is-num">Overdue</th>
              </tr>
            </thead>
            <tbody>
              @foreach($agents as $agent)
              @php
                $initials = collect(preg_split('/\s+/', trim($agent['name'])))->filter()->take(2)->map(fn ($part) => mb_substr($part, 0, 1))->implode('');
              @endphp
              <tr>
                <td><span class="ldb-agent"><span class="ldb-avatar">{{ strtoupper($initials) ?: '?' }}</span>{{ $agent['name'] }}</span></td>
                <td class="is-num">{{ number_format($agent['leads']) }}</td>
                <td data-tip="{{ $agent['called'] }} of {{ $agent['leads'] }} leads have at least one contact called">
                  <span class="ldb-mini"><span style="width: {{ $pct($agent['called'], $agent['leads']) }}%"></span></span>{{ $pct($agent['called'], $agent['leads']) }}%
                </td>
                <td class="is-num">{{ number_format($agent['calls']) }}</td>
                <td class="is-num">{{ number_format($agent['connected']) }} <small style="color:var(--fk-list-muted)">({{ $pct($agent['connected'], $agent['calls']) }}%)</small></td>
                <td class="is-num">{{ number_format($agent['open_tasks']) }}</td>
                <td class="is-num {{ $agent['overdue'] ? 'ldb-overdue' : '' }}">{{ number_format($agent['overdue']) }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
          @endif
        </div>
      </div>

      {{-- Follow-ups --}}
      <div class="ldb-panel ldb-span-4">
        <div class="ldb-card-head"><h3>Follow-ups</h3><small>Overdue &amp; next 7 days</small></div>
        <div class="ldb-card-body">
          @if($followUps->isEmpty())
          <div class="ldb-empty">Nothing due. 🎉</div>
          @else
          <ul class="ldb-follow">
            @foreach($followUps as $task)
            @php
              $late = $task->date && $task->date < $today;
            @endphp
            <li>
              <div class="ldb-follow-date {{ $late ? 'is-late' : '' }}">
                <b>{{ $task->date ? \Carbon\Carbon::parse($task->date)->format('d') : '–' }}</b>{{ $task->date ? \Carbon\Carbon::parse($task->date)->format('M') : '' }}
              </div>
              <div class="ldb-follow-main">
                <strong title="{{ strip_tags((string) $task->description) }}">{{ strip_tags((string) $task->description) }}</strong>
                @if($task->lead)
                <a href="{{ route('leads.show', $task->lead_id) }}">{{ $task->lead->company_name }}</a>
                @endif
                <span> · {{ $task->assignUser->name ?? 'Unassigned' }}{{ $task->time ? ' · '.\Carbon\Carbon::parse($task->time)->format('h:i A') : '' }}{{ $late ? ' · overdue' : '' }}</span>
              </div>
            </li>
            @endforeach
          </ul>
          @endif
        </div>
      </div>
    </div>
  </section>

  <div class="ldb-tip" id="ldbTip"></div>

  <script>
    (function () {
      // Quick ranges
      var toDate = function (date) {
        var pad = function (n) { return (n < 10 ? '0' : '') + n; };
        return date.getFullYear() + '-' + pad(date.getMonth() + 1) + '-' + pad(date.getDate());
      };
      document.querySelectorAll('.ldb-chip-btn').forEach(function (button) {
        button.addEventListener('click', function () {
          var days = parseInt(button.getAttribute('data-days'), 10);
          var to = new Date();
          var from = new Date();
          from.setDate(to.getDate() - (days - 1));
          document.getElementById('ldbFrom').value = toDate(from);
          document.getElementById('ldbTo').value = toDate(to);
          document.getElementById('ldbFilters').submit();
        });
      });

      // Hover tooltip for every [data-tip] mark (bars, columns, segments)
      var tip = document.getElementById('ldbTip');
      document.addEventListener('mousemove', function (event) {
        var target = event.target.closest('[data-tip]');
        if (!target) { tip.style.opacity = 0; return; }
        tip.textContent = target.getAttribute('data-tip');
        tip.style.left = Math.min(event.clientX + 14, window.innerWidth - tip.offsetWidth - 8) + 'px';
        tip.style.top = (event.clientY + 14) + 'px';
        tip.style.opacity = 1;
      });
    })();
  </script>
</x-app-layout>
