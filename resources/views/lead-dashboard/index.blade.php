<x-app-layout>
  @php
    $fmtDuration = fn ($seconds) => sprintf('%d:%02d:%02d', intdiv($seconds, 3600), intdiv($seconds % 3600, 60), $seconds % 60);
    $pct = fn ($part, $whole) => $whole > 0 ? round($part * 100 / $whole) : 0;
    $connectRate = $pct($callsConnected, $callsMade);
    // ↑/↓ vs the previous period of the same length.
    $delta = function ($now, $before, $suffix = '%') {
        if ($before === null) return null;
        if ($before == 0) return $now > 0 ? ['up', 'new'] : null;
        $change = round(($now - $before) * 100 / $before);
        return [$change >= 0 ? 'up' : 'down', ($change >= 0 ? '+' : '').$change.$suffix];
    };
    $deltas = [
        'newLeads' => $delta($newLeads, $previous['newLeads']),
        'callsMade' => $delta($callsMade, $previous['callsMade']),
        'callsConnected' => $delta($callsConnected, $previous['callsConnected']),
        'connectRate' => $previous['connectRate'] === null ? null : [$connectRate >= $previous['connectRate'] ? 'up' : 'down', ($connectRate - $previous['connectRate'] >= 0 ? '+' : '').($connectRate - $previous['connectRate']).' pts'],
    ];
    $leadMax = max(1, collect($trend)->max('total'));
    $callMax = max(1, collect($trend)->max('calls'));
    $typeMax = max(1, $byType->max('total') ?? 0);
    $outcomeTotal = max(1, $outcomes->sum('total'));
    $progressTotal = collect($progress)->sum('total');
    $funnelTop = max(1, $funnel[0]['total'] ?? 0);
    $rangeLabel = $from->format('d M Y').' – '.$to->format('d M Y');
    $periodDays = count($trend);
    $initials = fn ($name) => strtoupper(collect(preg_split('/\s+/', trim((string) $name)))->filter()->take(2)->map(fn ($part) => mb_substr($part, 0, 1))->implode('')) ?: '?';
  @endphp

  <style>
    .ldb {
      --ldb-blue: #3987e5; --ldb-orange: #d95926; --ldb-aqua: #199e70; --ldb-yellow: #c98500; --ldb-violet: #9085e9;
      --ldb-good: #10b981; --ldb-warn: #f59e0b; --ldb-bad: #ef4444; --ldb-info: #38bdf8;
      --ldb-muted-fill: rgba(148, 163, 184, .32);
    }
    .ldb * { letter-spacing: normal; }
    .ldb-panel { position: relative; border: 1px solid var(--fk-list-border); border-radius: 16px; background: var(--fk-list-panel); color: var(--fk-list-text); }

    /* Filters */
    .ldb-filters { display: flex; flex-wrap: wrap; align-items: end; gap: 10px; padding: 12px 16px; margin-bottom: 14px; }
    .ldb-field label { display: block; margin: 0 0 4px; font-size: 10.5px; font-weight: 600; letter-spacing: .05em; text-transform: uppercase; color: var(--fk-list-muted); }
    .ldb-field .form-control { height: 38px !important; min-width: 160px; padding: 0 12px; border: 1px solid var(--fk-list-border-strong) !important; border-radius: 10px !important; background: var(--fk-list-control) !important; color: var(--fk-list-text) !important; font-size: 13px; color-scheme: dark; }
    .ldb-quick { display: flex; gap: 6px; }
    .ldb-chip-btn { height: 38px; padding: 0 12px; border: 1px solid var(--fk-list-border-strong); border-radius: 10px; background: var(--fk-list-control); color: var(--fk-list-text); font-size: 12.5px; font-weight: 600; cursor: pointer; }
    .ldb-chip-btn:hover, .ldb-chip-btn.is-active { border-color: var(--fk-list-accent); color: var(--fk-list-accent); }
    .ldb-apply { height: 38px; padding: 0 18px; border: 0; border-radius: 10px; background: linear-gradient(135deg, var(--fk-list-accent), var(--fk-list-accent-2)); color: var(--fk-list-primary-text); font-weight: 600; cursor: pointer; }
    .ldb-range { margin-left: auto; font-size: 12.5px; color: var(--fk-list-muted); }

    /* Today strip */
    .ldb-today { display: flex; flex-wrap: wrap; align-items: center; gap: 10px; margin-bottom: 14px; padding: 10px 14px; border-radius: 14px; background: linear-gradient(90deg, rgba(56, 189, 248, .12), rgba(144, 133, 233, .1)); border: 1px solid rgba(56, 189, 248, .25); }
    .ldb-today-title { display: flex; align-items: center; gap: 6px; margin-right: 6px; font-size: 13px; font-weight: 700; color: var(--fk-list-heading); }
    .ldb-today-title .material-icons { font-size: 18px; color: var(--ldb-info); }
    .ldb-today-item { display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px; border-radius: 20px; background: rgba(8, 18, 44, .45); font-size: 12.5px; color: var(--fk-list-muted); }
    .ldb-today-item b { font-size: 14px; color: var(--fk-list-heading); font-variant-numeric: tabular-nums; }

    /* KPI tiles */
    .ldb-kpis { display: grid; grid-template-columns: repeat(6, minmax(0, 1fr)); gap: 12px; margin-bottom: 14px; }
    .ldb-kpi { overflow: hidden; padding: 14px 16px 14px; }
    .ldb-kpi::before { content: ''; position: absolute; inset: 0 0 auto 0; height: 3px; background: var(--kpi-color, var(--ldb-blue)); opacity: .9; }
    .ldb-kpi-top { display: flex; align-items: center; gap: 10px; }
    .ldb-kpi-icon { display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 10px; background: color-mix(in srgb, var(--kpi-color, var(--ldb-blue)) 18%, transparent); color: var(--kpi-color, var(--ldb-blue)); }
    .ldb-kpi-icon .material-icons { font-size: 19px; }
    .ldb-kpi-label { font-size: 12px; font-weight: 600; color: var(--fk-list-muted); line-height: 1.25; }
    .ldb-kpi-value { margin-top: 10px; font-size: 27px; font-weight: 700; line-height: 1.05; color: var(--fk-list-heading); font-variant-numeric: tabular-nums; }
    .ldb-kpi-sub { display: flex; flex-wrap: wrap; align-items: center; gap: 6px; margin-top: 6px; font-size: 11.5px; color: var(--fk-list-muted); }
    .ldb-kpi-sub b { color: var(--fk-list-text); font-weight: 600; }
    .ldb-delta { display: inline-flex; align-items: center; gap: 1px; padding: 1px 7px; border-radius: 10px; font-size: 11px; font-weight: 700; }
    .ldb-delta .material-icons { font-size: 14px; }
    .ldb-delta.is-up { color: var(--ldb-good); background: rgba(16, 185, 129, .13); }
    .ldb-delta.is-down { color: var(--ldb-bad); background: rgba(239, 68, 68, .13); }
    .ldb-kpi-sub .is-bad { color: var(--ldb-bad); }

    /* Cards */
    .ldb-grid { display: grid; grid-template-columns: repeat(12, minmax(0, 1fr)); gap: 14px; margin-bottom: 14px; }
    .ldb-span-8 { grid-column: span 8; } .ldb-span-6 { grid-column: span 6; } .ldb-span-4 { grid-column: span 4; }
    .ldb-card-head { display: flex; align-items: center; justify-content: space-between; gap: 10px; padding: 14px 16px 0; }
    .ldb-card-head h3 { display: flex; align-items: center; gap: 8px; margin: 0; font-size: 14.5px; font-weight: 700; color: var(--fk-list-heading); }
    .ldb-card-head h3 .material-icons { font-size: 18px; color: var(--fk-list-accent); }
    .ldb-card-head small { font-size: 12px; color: var(--fk-list-muted); }
    .ldb-card-body { padding: 12px 16px 16px; }
    .ldb-empty { padding: 26px 0; text-align: center; font-size: 13px; color: var(--fk-list-muted); }
    .ldb a { text-decoration: none !important; }
    .ldb a:hover { text-decoration: underline !important; }
    .ldb-link { font-size: 12px; font-weight: 600; color: var(--fk-list-accent) !important; }

    /* Trend tabs */
    .ldb-tabs { display: inline-flex; gap: 4px; padding: 3px; border-radius: 10px; background: var(--fk-list-control); }
    .ldb-tab { height: 28px; padding: 0 12px; border: 0; border-radius: 8px; background: transparent; color: var(--fk-list-muted); font-size: 12px; font-weight: 600; cursor: pointer; }
    .ldb-tab.is-active { background: var(--fk-list-panel); color: var(--fk-list-heading); box-shadow: 0 0 0 1px var(--fk-list-border-strong) inset; }
    .ldb-trend-meta { display: flex; flex-wrap: wrap; gap: 16px; margin-bottom: 8px; font-size: 12px; color: var(--fk-list-muted); }
    .ldb-trend-meta b { color: var(--fk-list-heading); font-size: 14px; }
    .ldb-legend-inline { display: inline-flex; align-items: center; gap: 12px; }
    .ldb-legend-inline span { display: inline-flex; align-items: center; gap: 5px; }
    .ldb-legend-inline i { width: 10px; height: 10px; border-radius: 3px; }
    .ldb-columns { display: flex; align-items: flex-end; gap: 2px; height: 180px; border-bottom: 1px solid var(--fk-list-border); background-image: linear-gradient(var(--fk-list-border) 1px, transparent 1px); background-size: 100% 45px; background-position: bottom; }
    .ldb-col { position: relative; flex: 1 1 0; height: 100%; display: flex; flex-direction: column; justify-content: flex-end; gap: 2px; }
    .ldb-col span { display: block; width: 100%; }
    .ldb-col span:first-child { border-radius: 4px 4px 0 0; }
    .ldb-col .is-lead { background: var(--ldb-blue); }
    .ldb-col .is-connected { background: var(--ldb-blue); }
    .ldb-col .is-missed { background: var(--ldb-orange); }
    .ldb-col .is-zero { height: 2px !important; background: var(--ldb-muted-fill); border-radius: 2px; }
    .ldb-col:hover span:not(.is-zero) { filter: brightness(1.2); }
    .ldb-col-axis { display: flex; justify-content: space-between; margin-top: 6px; font-size: 11px; color: var(--fk-list-muted); }
    .ldb-trend-pane[hidden] { display: none; }

    /* Funnel */
    .ldb-funnel { display: flex; flex-direction: column; gap: 8px; }
    .ldb-funnel-row { display: grid; grid-template-columns: 92px minmax(0, 1fr) 62px; align-items: center; gap: 10px; }
    .ldb-funnel-label { font-size: 12.5px; font-weight: 600; color: var(--fk-list-heading); }
    .ldb-funnel-label small { display: block; font-size: 10.5px; font-weight: 400; color: var(--fk-list-muted); }
    .ldb-funnel-bar { position: relative; height: 22px; border-radius: 8px; background: var(--fk-list-control); overflow: hidden; }
    .ldb-funnel-bar span { position: absolute; inset: 0 auto 0 0; border-radius: 8px; background: linear-gradient(90deg, var(--ldb-blue), var(--ldb-violet)); }
    .ldb-funnel-value { text-align: right; font-size: 13px; font-weight: 700; color: var(--fk-list-heading); font-variant-numeric: tabular-nums; }
    .ldb-funnel-value small { display: block; font-size: 10.5px; font-weight: 500; color: var(--fk-list-muted); }

    /* Horizontal bars */
    .ldb-bars { display: flex; flex-direction: column; gap: 10px; }
    .ldb-bar-row { display: grid; grid-template-columns: minmax(90px, 130px) minmax(0, 1fr) 54px; align-items: center; gap: 10px; }
    .ldb-bar-label { display: flex; align-items: center; gap: 6px; min-width: 0; font-size: 12.5px; color: var(--fk-list-text); }
    .ldb-bar-label span { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .ldb-bar-label i { flex: 0 0 8px; width: 8px; height: 8px; border-radius: 50%; }
    .ldb-bar-track { height: 10px; border-radius: 0 5px 5px 0; background: rgba(148, 163, 184, .08); }
    .ldb-bar-fill { display: block; height: 100%; min-width: 3px; border-radius: 0 5px 5px 0; background: var(--ldb-blue); }
    .ldb-bar-value { font-size: 12.5px; font-weight: 700; text-align: right; color: var(--fk-list-heading); font-variant-numeric: tabular-nums; }
    .ldb-bar-value small { display: block; font-size: 10.5px; font-weight: 500; color: var(--fk-list-muted); }
    .tone-good { background: var(--ldb-good) !important; } .tone-warn { background: var(--ldb-warn) !important; } .tone-bad { background: var(--ldb-bad) !important; }
    .tone-info { background: var(--ldb-info) !important; } .tone-muted { background: var(--ldb-muted-fill) !important; }

    /* Calling progress donut */
    .ldb-donut-wrap { display: flex; align-items: center; gap: 16px; }
    .ldb-donut { position: relative; flex: 0 0 130px; width: 130px; height: 130px; border-radius: 50%; }
    .ldb-donut-ring { position: absolute; inset: 0; border-radius: 50%; -webkit-mask: radial-gradient(circle, transparent 46px, #000 47px); mask: radial-gradient(circle, transparent 46px, #000 47px); }
    .ldb-donut-center { position: absolute; inset: 0; z-index: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; }
    .ldb-donut-center b { font-size: 22px; color: var(--fk-list-heading); }
    .ldb-donut-center small { font-size: 11px; color: var(--fk-list-muted); }
    .ldb-legend { flex: 1 1 auto; display: flex; flex-direction: column; gap: 6px; min-width: 0; }
    .ldb-legend-item { display: flex; align-items: center; gap: 8px; padding: 6px 8px; border-radius: 9px; text-decoration: none !important; }
    .ldb-legend-item:hover { background: var(--fk-list-control); }
    .ldb-legend-item i { flex: 0 0 10px; width: 10px; height: 10px; border-radius: 3px; }
    .ldb-legend-item span { flex: 1 1 auto; font-size: 12.5px; color: var(--fk-list-text); }
    .ldb-legend-item b { font-size: 13px; color: var(--fk-list-heading); font-variant-numeric: tabular-nums; }
    .ldb-legend-item small { width: 34px; text-align: right; font-size: 11px; color: var(--fk-list-muted); }

    /* Lists */
    .ldb-list { list-style: none; margin: 0; padding: 0; }
    .ldb-list li { display: flex; align-items: center; gap: 10px; padding: 9px 0; border-bottom: 1px solid var(--fk-list-border); }
    .ldb-list li:last-child { border-bottom: 0; }
    .ldb-list-main { flex: 1 1 auto; min-width: 0; }
    .ldb-list-main strong, .ldb-list-main a.ldb-title { display: block; font-size: 13px; font-weight: 600; color: var(--fk-list-heading) !important; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .ldb-list-main span { display: block; font-size: 11.5px; color: var(--fk-list-muted); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .ldb-avatar { flex: 0 0 30px; width: 30px; height: 30px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 10.5px; font-weight: 700; color: var(--fk-list-accent); background: var(--fk-list-control); border: 1px solid var(--fk-list-border-strong); }
    .ldb-tag { flex: 0 0 auto; padding: 2px 9px; border-radius: 12px; font-size: 11px; font-weight: 700; white-space: nowrap; }
    .ldb-tag.is-good { color: var(--ldb-good); background: rgba(16, 185, 129, .13); }
    .ldb-tag.is-warn { color: var(--ldb-warn); background: rgba(245, 158, 11, .14); }
    .ldb-tag.is-bad { color: var(--ldb-bad); background: rgba(239, 68, 68, .13); }
    .ldb-tag.is-info { color: var(--ldb-info); background: rgba(56, 189, 248, .13); }
    .ldb-tag.is-muted { color: #94a3b8; background: rgba(148, 163, 184, .14); }
    .ldb-time { flex: 0 0 auto; font-size: 11px; color: var(--fk-list-muted); text-align: right; white-space: nowrap; }
    .ldb-follow-date { flex: 0 0 52px; text-align: center; padding: 4px 0; border-radius: 9px; background: var(--fk-list-control); font-size: 10.5px; color: var(--fk-list-muted); }
    .ldb-follow-date b { display: block; font-size: 15px; color: var(--fk-list-heading); }
    .ldb-follow-date.is-late { background: rgba(239, 68, 68, .14); color: var(--ldb-bad); }
    .ldb-follow-date.is-late b { color: var(--ldb-bad); }

    /* Agents table */
    .ldb-table { width: 100%; border-collapse: collapse; }
    .ldb-table th { padding: 8px 10px; font-size: 10.5px; font-weight: 600; letter-spacing: .05em; text-transform: uppercase; color: var(--fk-list-muted); border-bottom: 1px solid var(--fk-list-border); text-align: left; white-space: nowrap; }
    .ldb-table td { padding: 10px; font-size: 13px; color: var(--fk-list-text); border-bottom: 1px solid var(--fk-list-border); white-space: nowrap; font-variant-numeric: tabular-nums; }
    .ldb-table tr:last-child td { border-bottom: 0; }
    .ldb-table tbody tr:hover td { background: rgba(56, 189, 248, .04); }
    .ldb-table .is-num { text-align: right; }
    .ldb-agent { display: flex; align-items: center; gap: 8px; font-weight: 600; color: var(--fk-list-heading); }
    .ldb-mini { display: inline-block; width: 64px; height: 6px; margin-right: 6px; border-radius: 3px; background: var(--ldb-muted-fill); vertical-align: middle; overflow: hidden; }
    .ldb-mini span { display: block; height: 100%; background: var(--ldb-good); }
    .ldb-overdue { color: var(--ldb-bad) !important; font-weight: 700; }
    .ldb-rank { display: inline-block; width: 18px; font-size: 11px; color: var(--fk-list-muted); }

    /* Action center */
    .ldb-actions { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 10px; }
    .ldb-action { --act: var(--ldb-blue); display: flex; flex-direction: column; gap: 2px; padding: 12px 14px; border: 1px solid var(--fk-list-border); border-left: 3px solid var(--act); border-radius: 12px; background: color-mix(in srgb, var(--act) 7%, transparent); text-decoration: none !important; transition: transform .12s ease, border-color .12s ease, background .12s ease; }
    .ldb-action:hover { transform: translateY(-2px); border-color: var(--act); background: color-mix(in srgb, var(--act) 14%, transparent); text-decoration: none !important; }
    .ldb-action-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 6px; }
    .ldb-action-icon { display: inline-flex; align-items: center; justify-content: center; width: 30px; height: 30px; border-radius: 9px; background: color-mix(in srgb, var(--act) 18%, transparent); color: var(--act); }
    .ldb-action-icon .material-icons { font-size: 18px; }
    .ldb-action-go { font-size: 16px; color: var(--fk-list-muted); opacity: 0; transition: opacity .12s ease; }
    .ldb-action:hover .ldb-action-go { opacity: 1; color: var(--act); }
    .ldb-action b { font-size: 24px; font-weight: 700; line-height: 1.1; color: var(--fk-list-heading); font-variant-numeric: tabular-nums; }
    .ldb-action strong { font-size: 12.5px; font-weight: 600; color: var(--fk-list-text); }
    .ldb-action small { font-size: 11px; color: var(--fk-list-muted); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .ldb-action.is-clear { opacity: .6; }
    .tone-blue-text { --act: var(--ldb-blue); } .tone-warn-text { --act: var(--ldb-warn); } .tone-bad-text { --act: var(--ldb-bad); }
    .tone-info-text { --act: var(--ldb-info); } .tone-violet-text { --act: var(--ldb-violet); } .tone-orange-text { --act: var(--ldb-orange); }
    .tone-muted-text { --act: #94a3b8; } .tone-good-text { --act: var(--ldb-good); }
    a.ldb-kpi { display: block; text-decoration: none !important; transition: transform .12s ease, border-color .12s ease; }
    a.ldb-kpi:hover { transform: translateY(-2px); border-color: var(--kpi-color); }

    .ldb-tip { position: fixed; z-index: 2000; pointer-events: none; padding: 6px 10px; border-radius: 8px; background: #0b1230; border: 1px solid var(--fk-list-border-strong); color: #e8f0ff; font-size: 12px; white-space: nowrap; box-shadow: 0 8px 20px rgba(0,0,0,.35); opacity: 0; transition: opacity .1s ease; }

    @media (max-width: 1399px) { .ldb-kpis { grid-template-columns: repeat(3, minmax(0, 1fr)); } }
    @media (max-width: 767px) { .ldb-actions { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
    @media (max-width: 1199px) { .ldb-span-8, .ldb-span-6, .ldb-span-4 { grid-column: span 12; } }
    @media (max-width: 575px) { .ldb-kpis { grid-template-columns: repeat(2, minmax(0, 1fr)); } .ldb-range { margin-left: 0; } .ldb-donut-wrap { flex-direction: column; } }
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
        @foreach([7, 30, 90] as $days)
        <button type="button" class="ldb-chip-btn {{ $periodDays === $days && $to->isToday() ? 'is-active' : '' }}" data-days="{{ $days }}">{{ $days }} days</button>
        @endforeach
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
      <span class="ldb-range">{{ $rangeLabel }} · compared with the previous {{ $periodDays }} days</span>
    </form>

    {{-- Today --}}
    <div class="ldb-today">
      <span class="ldb-today-title"><i class="material-icons">today</i>Today</span>
      <span class="ldb-today-item"><b>{{ number_format($todayStats['calls']) }}</b> calls</span>
      <span class="ldb-today-item"><b>{{ number_format($todayStats['connected']) }}</b> connected</span>
      <span class="ldb-today-item"><b>{{ number_format($todayStats['newLeads']) }}</b> new leads</span>
      <span class="ldb-today-item"><b>{{ number_format($todayStats['tasksDue']) }}</b> tasks due</span>
    </div>

    {{-- KPI tiles --}}
    @php
      $tiles = [
        ['icon' => 'diamond', 'color' => 'var(--ldb-blue)', 'label' => 'Total leads', 'value' => number_format($totalLeads), 'sub' => '<b>'.number_format($convertedLeads).'</b> converted', 'delta' => null, 'url' => url('leads').($userId ? '?user_id='.$userId : '')],
        ['icon' => 'fiber_new', 'color' => 'var(--ldb-violet)', 'label' => 'New leads', 'value' => number_format($newLeads), 'sub' => 'in this period', 'delta' => $deltas['newLeads'], 'url' => url('leads').($userId ? '?user_id='.$userId : '')],
        ['icon' => 'call', 'color' => 'var(--ldb-info)', 'label' => 'Calls made', 'value' => number_format($callsMade), 'sub' => '<b>'.number_format($callsConnected).'</b> connected', 'delta' => $deltas['callsMade'], 'url' => route('call-management.index').'?'.http_build_query(array_filter(['start_date' => $from->toDateString(), 'end_date' => $to->toDateString(), 'user_id' => $userId]))],
        ['icon' => 'percent', 'color' => 'var(--ldb-good)', 'label' => 'Connect rate', 'value' => $connectRate.'%', 'sub' => 'talk <b>'.$fmtDuration($talkSeconds).'</b>', 'delta' => $deltas['connectRate'], 'url' => route('call-management.index').'?'.http_build_query(array_filter(['call_status' => 'connected', 'start_date' => $from->toDateString(), 'end_date' => $to->toDateString(), 'user_id' => $userId]))],
        ['icon' => 'task_alt', 'color' => 'var(--ldb-warn)', 'label' => 'Open tasks', 'value' => number_format($openTasks), 'sub' => '<b class="'.($overdueTasks ? 'is-bad' : '').'">'.number_format($overdueTasks).'</b> overdue', 'delta' => null, 'url' => url('lead-tasks')],
        ['icon' => 'fact_check', 'color' => 'var(--ldb-orange)', 'label' => 'Drafts to approve', 'value' => number_format($pendingDrafts), 'sub' => 'Email &amp; LinkedIn', 'delta' => null, 'url' => auth()->user()?->can('draft_approval') ? route('draft-approvals.index') : null],
      ];
    @endphp
    <div class="ldb-kpis">
      @foreach($tiles as $tile)
      <{{ $tile['url'] ? 'a' : 'div' }} class="ldb-panel ldb-kpi" style="--kpi-color: {{ $tile['color'] }}" @if($tile['url']) href="{{ $tile['url'] }}" @endif>
        <div class="ldb-kpi-top">
          <span class="ldb-kpi-icon"><span class="material-icons">{{ $tile['icon'] }}</span></span>
          <span class="ldb-kpi-label">{{ $tile['label'] }}</span>
        </div>
        <div class="ldb-kpi-value">{{ $tile['value'] }}</div>
        <div class="ldb-kpi-sub">
          @if($tile['delta'])
          <span class="ldb-delta is-{{ $tile['delta'][0] }}" title="vs previous {{ $periodDays }} days"><span class="material-icons">{{ $tile['delta'][0] === 'up' ? 'arrow_upward' : 'arrow_downward' }}</span>{{ $tile['delta'][1] }}</span>
          @endif
          <span>{!! $tile['sub'] !!}</span>
        </div>
      </{{ $tile['url'] ? 'a' : 'div' }}>
      @endforeach
    </div>

    <div class="ldb-grid">
      {{-- Activity trend --}}
      <div class="ldb-panel ldb-span-8">
        <div class="ldb-card-head">
          <h3><span class="material-icons">insights</span>Activity trend</h3>
          <div class="ldb-tabs" role="tablist">
            <button type="button" class="ldb-tab is-active" data-pane="calls">Calls per day</button>
            <button type="button" class="ldb-tab" data-pane="leads">New leads per day</button>
          </div>
        </div>
        <div class="ldb-card-body">
          <div class="ldb-trend-pane" data-pane="calls">
            <div class="ldb-trend-meta">
              <span><b>{{ number_format($callsMade) }}</b> calls</span>
              <span><b>{{ round($callsMade / max(1, $periodDays), 1) }}</b> avg / day</span>
              <span class="ldb-legend-inline"><span><i style="background:var(--ldb-blue)"></i>Connected</span><span><i style="background:var(--ldb-orange)"></i>Not connected</span></span>
            </div>
            <div class="ldb-columns" role="img" aria-label="Calls per day">
              @foreach($trend as $point)
              <div class="ldb-col" data-tip="{{ $point['full'] }} · {{ $point['calls'] }} calls ({{ $point['connected'] }} connected, {{ $point['missed'] }} not connected)">
                @if($point['calls'] === 0)
                <span class="is-zero"></span>
                @else
                @if($point['missed'] > 0)<span class="is-missed" style="height: {{ $point['missed'] * 100 / $callMax }}%"></span>@endif
                @if($point['connected'] > 0)<span class="is-connected" style="height: {{ $point['connected'] * 100 / $callMax }}%"></span>@endif
                @endif
              </div>
              @endforeach
            </div>
          </div>
          <div class="ldb-trend-pane" data-pane="leads" hidden>
            <div class="ldb-trend-meta">
              <span><b>{{ number_format($newLeads) }}</b> new leads</span>
              <span><b>{{ round($newLeads / max(1, $periodDays), 1) }}</b> avg / day</span>
              <span>Peak <b>{{ number_format($leadMax) }}</b> in a day</span>
            </div>
            <div class="ldb-columns" role="img" aria-label="New leads per day">
              @foreach($trend as $point)
              <div class="ldb-col" data-tip="{{ $point['full'] }} · {{ $point['total'] }} new lead{{ $point['total'] === 1 ? '' : 's' }}">
                @if($point['total'] === 0)
                <span class="is-zero"></span>
                @else
                <span class="is-lead" style="height: {{ max(2, $point['total'] * 100 / $leadMax) }}%"></span>
                @endif
              </div>
              @endforeach
            </div>
          </div>
          <div class="ldb-col-axis"><span>{{ $trend[0]['date'] ?? '' }}</span><span>{{ $trend[count($trend) - 1]['date'] ?? '' }}</span></div>
        </div>
      </div>

      {{-- Funnel --}}
      <div class="ldb-panel ldb-span-4">
        <div class="ldb-card-head"><h3><span class="material-icons">filter_alt</span>Lead funnel</h3><small>All leads</small></div>
        <div class="ldb-card-body">
          <div class="ldb-funnel">
            @foreach($funnel as $step)
            <div class="ldb-funnel-row" data-tip="{{ $step['label'] }}: {{ number_format($step['total']) }} ({{ $pct($step['total'], $funnelTop) }}% of all leads) · {{ $step['hint'] }}">
              <span class="ldb-funnel-label">{{ $step['label'] }}<small>{{ $step['hint'] }}</small></span>
              <span class="ldb-funnel-bar">
                <span style="width: {{ $step['total'] > 0 ? max(4, $step['total'] * 100 / $funnelTop) : 0 }}%"></span>
              </span>
              <span class="ldb-funnel-value">{{ number_format($step['total']) }}<small>{{ $pct($step['total'], $funnelTop) }}%</small></span>
            </div>
            @endforeach
          </div>
        </div>
      </div>

      {{-- Calling progress --}}
      <div class="ldb-panel ldb-span-4">
        <div class="ldb-card-head"><h3><span class="material-icons">phone_forwarded</span>Calling progress</h3><small>{{ number_format($progressTotal) }} leads</small></div>
        <div class="ldb-card-body">
          @php
            $progressColors = ['all_called' => '#10b981', 'partial' => '#f59e0b', 'not_called' => '#3987e5', 'no_phone' => 'rgba(148,163,184,.4)'];
            $stops = []; $cursor = 0;
            foreach ($progress as $part) {
                $share = $progressTotal > 0 ? $part['total'] * 100 / $progressTotal : 0;
                if ($share > 0) { $stops[] = $progressColors[$part['key']].' '.$cursor.'% '.($cursor + $share).'%'; $cursor += $share; }
            }
            $donut = $stops ? 'conic-gradient('.implode(', ', $stops).')' : 'rgba(148,163,184,.2)';
            $calledLeads = collect($progress)->whereIn('key', ['all_called', 'partial'])->sum('total');
          @endphp
          @if($progressTotal === 0)
          <div class="ldb-empty">No leads yet.</div>
          @else
          <div class="ldb-donut-wrap">
            <div class="ldb-donut" role="img" aria-label="Calling progress">
              <span class="ldb-donut-ring" style="background: {{ $donut }}"></span>
              <div class="ldb-donut-center"><b>{{ $pct($calledLeads, $progressTotal) }}%</b><small>started</small></div>
            </div>
            <div class="ldb-legend">
              @foreach($progress as $part)
              <a class="ldb-legend-item" href="{{ $part['key'] !== 'no_phone' ? url('leads').'?call_progress='.$part['key'] : url('leads') }}" title="Open these leads">
                <i style="background: {{ $progressColors[$part['key']] }}"></i>
                <span>{{ $part['label'] }}</span>
                <b>{{ number_format($part['total']) }}</b>
                <small>{{ $pct($part['total'], $progressTotal) }}%</small>
              </a>
              @endforeach
            </div>
          </div>
          @endif
        </div>
      </div>

      {{-- Call outcomes --}}
      <div class="ldb-panel ldb-span-4">
        <div class="ldb-card-head"><h3><span class="material-icons">rule</span>Call outcomes</h3><small>{{ number_format($callsMade) }} calls</small></div>
        <div class="ldb-card-body">
          @if($outcomes->isEmpty())
          <div class="ldb-empty">No calls in this period.</div>
          @else
          <div class="ldb-bars">
            @foreach($outcomes as $row)
            <div class="ldb-bar-row" data-tip="{{ $row['label'] }}: {{ $row['total'] }} calls ({{ $pct($row['total'], $outcomeTotal) }}%)">
              <span class="ldb-bar-label"><i class="tone-{{ $row['tone'] }}"></i><span>{{ $row['label'] }}</span></span>
              <span class="ldb-bar-track"><span class="ldb-bar-fill tone-{{ $row['tone'] }}" style="width: {{ $row['total'] * 100 / max(1, $outcomes->max('total')) }}%"></span></span>
              <span class="ldb-bar-value">{{ number_format($row['total']) }}<small>{{ $pct($row['total'], $outcomeTotal) }}%</small></span>
            </div>
            @endforeach
          </div>
          @endif
        </div>
      </div>

      {{-- Lead type --}}
      <div class="ldb-panel ldb-span-4">
        <div class="ldb-card-head"><h3><span class="material-icons">local_fire_department</span>Leads by type</h3><small>All leads</small></div>
        <div class="ldb-card-body">
          @if($byType->isEmpty())
          <div class="ldb-empty">No leads yet.</div>
          @else
          <div class="ldb-bars">
            @foreach($byType->take(7) as $row)
            <div class="ldb-bar-row" data-tip="{{ $row['label'] }}: {{ $row['total'] }} leads ({{ $pct($row['total'], $totalLeads) }}%)">
              <span class="ldb-bar-label"><span>{{ $row['label'] }}</span></span>
              <span class="ldb-bar-track"><span class="ldb-bar-fill" style="width: {{ $row['total'] * 100 / $typeMax }}%"></span></span>
              <span class="ldb-bar-value">{{ number_format($row['total']) }}<small>{{ $pct($row['total'], $totalLeads) }}%</small></span>
            </div>
            @endforeach
          </div>
          @endif
        </div>
      </div>

      {{-- Action center: pending work, every tile opens the matching screen --}}
      <div class="ldb-panel ldb-span-8">
        <div class="ldb-card-head"><h3><span class="material-icons">pending_actions</span>Action center</h3><small>What is pending · click a tile to open the list</small></div>
        <div class="ldb-card-body">
          <div class="ldb-actions">
            @foreach($actions as $action)
            <a class="ldb-action tone-{{ $action['tone'] }}-text {{ $action['total'] > 0 ? '' : 'is-clear' }}" href="{{ $action['url'] }}">
              <span class="ldb-action-top">
                <span class="ldb-action-icon"><span class="material-icons">{{ $action['icon'] }}</span></span>
                <span class="material-icons ldb-action-go">arrow_forward</span>
              </span>
              <b>{{ number_format($action['total']) }}</b>
              <strong>{{ $action['label'] }}</strong>
              <small>{{ $action['total'] > 0 ? $action['hint'] : 'All clear' }}</small>
            </a>
            @endforeach
          </div>
        </div>
      </div>

      {{-- Recent calls --}}
      <div class="ldb-panel ldb-span-4">
        <div class="ldb-card-head"><h3><span class="material-icons">history</span>Recent calls</h3><a class="ldb-link" href="{{ route('call-management.index') }}">View all</a></div>
        <div class="ldb-card-body">
          @if($recentCalls->isEmpty())
          <div class="ldb-empty">No calls yet.</div>
          @else
          <ul class="ldb-list">
            @foreach($recentCalls as $call)
            @php
              $isConnected = (int) $call->status === 1 && ! empty($call->recording_url);
              $feedback = $call->feedbackStatus;
              if ($feedback && $feedback->module === \App\Models\Status::MODULE_LEAD_CALL_FEEDBACK) {
                  [$callTag, $callTone] = [$feedback->display_name ?: $feedback->status_name, ['good' => 'is-good', 'warn' => 'is-warn', 'bad' => 'is-bad', 'info' => 'is-info', 'muted' => 'is-muted'][$outcomeTones[$feedback->status_name] ?? 'info']];
              } else {
                  [$callTag, $callTone] = $isConnected ? ['Connected', 'is-good'] : ['Not connected', 'is-muted'];
              }
            @endphp
            <li>
              <span class="ldb-avatar">{{ $initials($call->user->name ?? '') }}</span>
              <div class="ldb-list-main">
                <strong>{{ $call->contact->name ?? $call->number }}</strong>
                <span>{{ $call->lead->company_name ?? '' }} · {{ $call->user->name ?? 'Unknown' }}</span>
              </div>
              <div class="ldb-time"><span class="ldb-tag {{ $callTone }}">{{ $callTag }}</span><br>{{ $call->started_at?->diffForHumans() }}</div>
            </li>
            @endforeach
          </ul>
          @endif
        </div>
      </div>

      {{-- Team performance --}}
      <div class="ldb-panel ldb-span-8">
        <div class="ldb-card-head"><h3><span class="material-icons">groups</span>Team performance</h3><small>Calls &amp; connects for {{ $rangeLabel }}</small></div>
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
              @foreach($agents as $index => $agent)
              <tr>
                <td><span class="ldb-agent"><span class="ldb-rank">{{ $index + 1 }}</span><span class="ldb-avatar">{{ $initials($agent['name']) }}</span>{{ $agent['name'] }}</span></td>
                <td class="is-num">{{ number_format($agent['leads']) }}</td>
                <td data-tip="{{ $agent['called'] }} of {{ $agent['leads'] }} leads have at least one contact called">
                  <span class="ldb-mini"><span style="width: {{ $pct($agent['called'], $agent['leads']) }}%"></span></span>{{ $pct($agent['called'], $agent['leads']) }}%
                </td>
                <td class="is-num">{{ number_format($agent['calls']) }}</td>
                <td class="is-num">{{ number_format($agent['connected']) }} <small style="color:var(--fk-list-muted)">{{ $agent['calls'] ? '('.$pct($agent['connected'], $agent['calls']).'%)' : '' }}</small></td>
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
        <div class="ldb-card-head"><h3><span class="material-icons">event</span>Follow-ups</h3><a class="ldb-link" href="{{ url('lead-tasks') }}">All tasks</a></div>
        <div class="ldb-card-body">
          @if($followUps->isEmpty())
          <div class="ldb-empty">Nothing due in the next 7 days. 🎉</div>
          @else
          <ul class="ldb-list">
            @foreach($followUps as $task)
            @php
              $late = $task->date && $task->date < $today;
            @endphp
            <li>
              <div class="ldb-follow-date {{ $late ? 'is-late' : '' }}">
                <b>{{ $task->date ? \Carbon\Carbon::parse($task->date)->format('d') : '–' }}</b>{{ $task->date ? \Carbon\Carbon::parse($task->date)->format('M') : '' }}
              </div>
              <div class="ldb-list-main">
                <strong title="{{ strip_tags((string) $task->description) }}">{{ strip_tags((string) $task->description) }}</strong>
                <span>
                  @if($task->lead)<a href="{{ route('leads.show', $task->lead_id) }}" style="color:var(--fk-list-accent)">{{ $task->lead->company_name }}</a> · @endif
                  {{ $task->assignUser->name ?? 'Unassigned' }}{{ $task->time ? ' · '.\Carbon\Carbon::parse($task->time)->format('h:i A') : '' }}
                </span>
              </div>
              @if($late)<span class="ldb-tag is-bad">Overdue</span>@endif
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
      var pad = function (n) { return (n < 10 ? '0' : '') + n; };
      var toDate = function (date) { return date.getFullYear() + '-' + pad(date.getMonth() + 1) + '-' + pad(date.getDate()); };
      document.querySelectorAll('.ldb-chip-btn').forEach(function (button) {
        button.addEventListener('click', function () {
          var days = parseInt(button.getAttribute('data-days'), 10);
          var to = new Date(), from = new Date();
          from.setDate(to.getDate() - (days - 1));
          document.getElementById('ldbFrom').value = toDate(from);
          document.getElementById('ldbTo').value = toDate(to);
          document.getElementById('ldbFilters').submit();
        });
      });

      // Trend tabs
      document.querySelectorAll('.ldb-tab').forEach(function (tab) {
        tab.addEventListener('click', function () {
          document.querySelectorAll('.ldb-tab').forEach(function (t) { t.classList.toggle('is-active', t === tab); });
          document.querySelectorAll('.ldb-trend-pane').forEach(function (pane) { pane.hidden = pane.getAttribute('data-pane') !== tab.getAttribute('data-pane'); });
        });
      });

      // Plain-text hover tooltip for every [data-tip] mark
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
