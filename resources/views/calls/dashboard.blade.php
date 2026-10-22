<x-app-layout>
    <style>
        .call-dashboard{color:#c5d2f3}.call-dashboard-breadcrumb{margin-bottom:8px;color:#7185bd;font-size:11px;font-weight:800;letter-spacing:.22em;text-transform:uppercase}.call-dashboard-breadcrumb span{margin-left:8px;color:#35ccef}.call-dashboard-heading{display:flex;align-items:center;justify-content:space-between;gap:16px;margin-bottom:18px}.call-dashboard-heading h1{margin:0;color:#f7f9ff;font-size:25px;font-weight:800}.call-dashboard-scope{padding:7px 13px;border:1px solid rgba(34,211,238,.38);border-radius:999px;background:rgba(34,211,238,.08);color:#2dd4ee;font-size:12px;font-weight:800}.call-dashboard-kpis{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:12px;margin-bottom:18px}.call-dashboard-kpi,.call-dashboard-panel{border:1px solid rgba(85,126,218,.3);border-radius:14px;background:rgba(7,25,61,.76);box-shadow:0 10px 28px rgba(0,0,0,.08)}.call-dashboard-kpi{position:relative;min-height:116px;padding:19px 17px}.call-dashboard-kpi i{position:absolute;top:17px;right:16px;color:#2dd4ee;font-size:20px}.call-dashboard-kpi small{display:block;color:#8295c5;font-size:11px;font-weight:800;letter-spacing:.08em;text-transform:uppercase}.call-dashboard-kpi strong{display:block;margin-top:9px;color:#f7f9ff;font-size:27px;line-height:1}.call-dashboard-kpi em{display:block;margin-top:9px;color:#21cda9;font-size:11px;font-style:normal;font-weight:800}.call-dashboard-grid{display:grid;grid-template-columns:minmax(0,1fr) minmax(0,1fr);gap:14px;margin-bottom:14px}.call-dashboard-panel{overflow:hidden;border-top:2px solid #28cae9}.call-dashboard-panel h2{margin:0;padding:15px 17px;border-bottom:1px solid rgba(85,126,218,.2);color:#e8efff;font-size:16px}.call-dashboard-panel-body{min-height:185px;padding:20px}.call-dashboard-breakdown{display:flex;align-items:center;gap:28px}.call-dashboard-donut{display:flex;align-items:center;justify-content:center;width:128px;height:128px;flex:0 0 128px;border-radius:50%;background:conic-gradient(#2dd4ee 0 var(--connected),#ef4d7b var(--connected) 100%)}.call-dashboard-donut::after{content:'';width:82px;height:82px;border-radius:50%;background:#081b42}.call-dashboard-donut-label{position:absolute;text-align:center}.call-dashboard-donut-label strong{display:block;color:#f7f9ff;font-size:23px}.call-dashboard-donut-label small{color:#7f92c1;font-size:9px}.call-dashboard-legend{display:grid;gap:12px}.call-dashboard-legend span{display:flex;align-items:center;gap:9px;color:#aebee4;font-size:13px}.call-dashboard-dot{width:9px;height:9px;border-radius:50%;background:#2dd4ee}.call-dashboard-dot.red{background:#ef4d7b}.call-dashboard-agents{display:grid;gap:12px;max-height:170px;overflow:auto}.call-dashboard-agent-row{display:grid;grid-template-columns:110px 1fr 35px;align-items:center;gap:10px;color:#aebee4;font-size:12px}.call-dashboard-agent-track{height:8px;overflow:hidden;border-radius:8px;background:#102959}.call-dashboard-agent-bar{height:100%;min-width:2px;border-radius:8px;background:linear-gradient(90deg,#438ff0,#2dd4ee)}.call-dashboard-agent-row b{text-align:right;color:#edf3ff}.call-dashboard-trend .call-dashboard-panel-body{height:280px;padding:12px 16px 16px}.call-dashboard-chart{width:100%;height:100%}.call-dashboard-empty{display:flex;align-items:center;justify-content:center;height:100%;color:#7184b4;font-size:13px}@media(max-width:1000px){.call-dashboard-kpis{grid-template-columns:repeat(2,minmax(0,1fr))}}@media(max-width:720px){.call-dashboard-kpis,.call-dashboard-grid{grid-template-columns:1fr}.call-dashboard-heading{align-items:flex-start;flex-direction:column}.call-dashboard-breakdown{justify-content:center}.call-dashboard-agent-row{grid-template-columns:85px 1fr 30px}}
    </style>
    <style>
        .call-dashboard { max-width:1560px; }
        .call-dashboard-hero { position:relative;display:flex;align-items:flex-end;justify-content:space-between;gap:24px;margin-bottom:18px;padding:22px 24px;overflow:hidden;border:1px solid rgba(64,116,213,.28);border-radius:16px;background:linear-gradient(120deg,rgba(15,48,106,.68),rgba(6,22,52,.75));box-shadow:0 14px 38px rgba(0,0,0,.12); }
        .call-dashboard-hero::after { content:'';position:absolute;right:-55px;top:-90px;width:260px;height:260px;border-radius:50%;background:radial-gradient(circle,rgba(45,212,238,.14),transparent 68%);pointer-events:none; }
        .call-dashboard-heading { display:block;margin:0; }
        .call-dashboard-heading h1 { margin-top:6px;font-size:27px; }
        .call-dashboard-subtitle { margin:7px 0 0;color:#8498c6;font-size:12px; }
        .call-dashboard-hero-meta { position:relative;z-index:1;display:flex;align-items:center;gap:12px; }
        .call-dashboard-plivo { display:flex;align-items:center;gap:9px;padding:8px 12px;border:1px solid rgba(38,212,174,.3);border-radius:11px;background:rgba(38,212,174,.08); }
        .call-dashboard-plivo > i { color:#26d4ae;font-size:20px; }
        .call-dashboard-plivo span { display:grid;line-height:1.05; }
        .call-dashboard-plivo small { color:#7185b6;font-size:8px;font-weight:800;letter-spacing:.1em;text-transform:uppercase; }
        .call-dashboard-plivo strong { margin-top:4px;color:#f7f9ff;font-size:13px;white-space:nowrap; }
        .call-dashboard-updated { color:#7185b6;font-size:10px;font-weight:700;letter-spacing:.06em;text-transform:uppercase; }
        .call-dashboard-section-title { display:flex;align-items:center;gap:9px;margin:0 0 9px 2px;color:#7187bd;font-size:10px;font-weight:800;letter-spacing:.17em;text-transform:uppercase; }
        .call-dashboard-section-title::before { content:'';width:18px;height:2px;border-radius:2px;background:#2dd4ee;box-shadow:0 0 8px rgba(45,212,238,.45); }
        .call-dashboard-kpis { margin-bottom:16px; }
        .call-dashboard-kpi { isolation:isolate;min-height:104px;padding:16px 17px;overflow:hidden; }
        .call-dashboard-kpi::before { content:'';position:absolute;z-index:-1;right:-35px;bottom:-50px;width:125px;height:125px;border-radius:50%;background:radial-gradient(circle,rgba(var(--accent-rgb),.13),transparent 68%); }
        .call-dashboard-kpi::after { right:14px;left:14px;background:rgb(var(--accent-rgb));opacity:.9; }
        .call-dashboard-kpi:nth-child(1){--accent-rgb:45,212,238}.call-dashboard-kpi:nth-child(2){--accent-rgb:38,212,174}.call-dashboard-kpi:nth-child(3){--accent-rgb:239,77,123}.call-dashboard-kpi:nth-child(4){--accent-rgb:247,185,85}.call-dashboard-kpi:nth-child(5){--accent-rgb:84,148,244}.call-dashboard-kpi:nth-child(6){--accent-rgb:38,212,174}.call-dashboard-kpi:nth-child(7){--accent-rgb:168,125,244}
        .call-dashboard-kpis.is-operations .call-dashboard-kpi:nth-child(1){--accent-rgb:45,212,238}.call-dashboard-kpis.is-operations .call-dashboard-kpi:nth-child(2){--accent-rgb:84,148,244}.call-dashboard-kpis.is-operations .call-dashboard-kpi:nth-child(3){--accent-rgb:38,212,174}.call-dashboard-kpis.is-operations .call-dashboard-kpi:nth-child(4){--accent-rgb:168,125,244}
        .call-dashboard-kpi i { color:rgb(var(--accent-rgb))!important;border-color:rgba(var(--accent-rgb),.22)!important;background:rgba(var(--accent-rgb),.09)!important; }
        .call-dashboard-kpi strong { font-size:25px; }
        .call-dashboard-kpi-meta { display:flex;align-items:center;gap:6px;margin-top:8px;color:#7287ba;font-size:10px;font-weight:700; }
        .call-dashboard-kpi-meta::before { content:'';width:5px;height:5px;border-radius:50%;background:rgb(var(--accent-rgb)); }
        .call-dashboard-kpi-meta.is-live::before { animation:callDashboardPulse 1.6s infinite;box-shadow:0 0 0 0 rgba(38,212,174,.55); }
        @keyframes callDashboardPulse { 70%{box-shadow:0 0 0 6px rgba(38,212,174,0)}100%{box-shadow:0 0 0 0 rgba(38,212,174,0)} }
        .call-dashboard-grid { grid-template-columns:minmax(320px,.85fr) minmax(460px,1.15fr); }
        .call-dashboard-panel { border-top:0; }
        .call-dashboard-panel h2 { position:relative;padding-left:18px; }
        .call-dashboard-panel h2::before { content:'';position:absolute;left:0;width:3px;height:18px;border-radius:0 3px 3px 0;background:#2dd4ee; }
        .call-dashboard-breakdown { justify-content:center;gap:40px; }
        .call-dashboard-donut-label { inset:0;display:flex;align-items:center;justify-content:center;flex-direction:column; }
        .call-dashboard-legend span { min-width:190px;justify-content:space-between;padding:8px 10px;border:1px solid rgba(85,126,218,.16);border-radius:9px;background:rgba(7,22,53,.48); }
        .call-dashboard-legend span b { margin-left:auto;color:#eef3ff; }
        .call-dashboard-agent-row { grid-template-columns:135px 1fr 38px; }
        .call-dashboard-agent-track { height:7px;background:rgba(26,62,123,.62); }
        .call-dashboard-agent-bar { box-shadow:0 0 10px rgba(45,212,238,.2); }
        .call-dashboard-trend { margin-top:0; }
        .call-dashboard-trend-head { display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;padding:13px 17px;border-bottom:1px solid rgba(85,126,218,.2); }
        .call-dashboard-trend-head h2 { padding:0;border:0; }
        .call-dashboard-trend-legend { display:flex;align-items:center;gap:14px;color:#8295c5;font-size:11px;font-weight:700; }
        .call-dashboard-trend-legend span { display:inline-flex;align-items:center;gap:6px; }
        .call-dashboard-trend-legend i { width:8px;height:8px;border-radius:50%;background:#2dd4ee; }
        .call-dashboard-trend-legend i.talk { background:#a78bfa; }
        .call-dashboard-trend-legend em { color:#5f73a6;font-style:normal;font-weight:600; }
        .call-dashboard-trend .call-dashboard-panel-body { position:relative;height:300px;padding:10px 12px 8px; }
        .call-dashboard-trend-scroll { height:100%;overflow-x:auto;overflow-y:hidden;scrollbar-width:thin;scrollbar-color:rgba(45,212,238,.35) transparent; }
        .call-dashboard-trend-scroll::-webkit-scrollbar { height:6px; }
        .call-dashboard-trend-scroll::-webkit-scrollbar-thumb { background:rgba(45,212,238,.35);border-radius:6px; }
        .call-dashboard-trend-scroll .call-dashboard-chart { display:block;height:calc(100% - 8px);cursor:pointer; }
        .call-dashboard-trend-tip { position:absolute;z-index:5;width:240px;padding:12px 13px;border:1px solid rgba(45,212,238,.35);border-radius:12px;background:rgba(6,20,52,.97);box-shadow:0 14px 34px rgba(0,0,0,.4);pointer-events:none;opacity:0;transition:opacity .12s; }
        .call-dashboard-trend-tip.is-visible { opacity:1; }
        .call-dashboard-trend-tip h4 { margin:0 0 8px;color:#f7f9ff;font-size:13px;font-weight:800; }
        .call-dashboard-tip-stats { display:grid;grid-template-columns:repeat(3,1fr);gap:6px;margin-bottom:9px; }
        .call-dashboard-tip-stats div { padding:6px 7px;border-radius:8px;background:rgba(26,62,123,.45); }
        .call-dashboard-tip-stats small { display:block;color:#7f92c1;font-size:9px;font-weight:800;letter-spacing:.06em;text-transform:uppercase; }
        .call-dashboard-tip-stats b { color:#eef3ff;font-size:13px; }
        .call-dashboard-tip-stats b.talk { color:#c4b5fd; }
        .call-dashboard-tip-agent { display:flex;justify-content:space-between;gap:8px;padding:4px 0;border-top:1px solid rgba(85,126,218,.15);color:#aebee4;font-size:12px; }
        .call-dashboard-tip-agent span { overflow:hidden;text-overflow:ellipsis;white-space:nowrap; }
        .call-dashboard-tip-agent b { flex:none;color:#eef3ff; }
        .call-dashboard-tip-agent b small { color:#a78bfa;font-weight:600; }
        .call-dashboard-tip-more { margin-top:6px;color:#2dd4ee;font-size:11px;font-weight:700; }
        .call-dashboard-day { border-top:1px solid rgba(85,126,218,.2);padding:16px 17px 18px; }
        .call-dashboard-day-head { display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-bottom:14px; }
        .call-dashboard-day-head h3 { margin:0;color:#e8efff;font-size:15px; }
        .call-dashboard-day-head h3 small { display:block;margin-bottom:3px;color:#7f92c1;font-size:10px;font-weight:800;letter-spacing:.14em;text-transform:uppercase; }
        .call-dashboard-day-nav { display:flex;gap:6px; }
        .call-dashboard-day-nav button { width:30px;height:30px;border:1px solid rgba(85,126,218,.35);border-radius:8px;background:rgba(26,62,123,.35);color:#c5d2f3;cursor:pointer; }
        .call-dashboard-day-nav button:disabled { opacity:.35;cursor:default; }
        .call-dashboard-day-stats { display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:10px;margin-bottom:14px; }
        .call-dashboard-day-stats div { padding:10px 12px;border:1px solid rgba(85,126,218,.22);border-radius:10px;background:rgba(10,32,74,.55); }
        .call-dashboard-day-stats small { display:block;color:#7f92c1;font-size:10px;font-weight:800;letter-spacing:.08em;text-transform:uppercase; }
        .call-dashboard-day-stats b { display:block;margin-top:4px;color:#f7f9ff;font-size:19px; }
        .call-dashboard-day-table { width:100%;border-collapse:collapse;font-size:13px; }
        .call-dashboard-day-table th { padding:8px 10px;color:#7f92c1;font-size:10px;font-weight:800;letter-spacing:.08em;text-align:right;text-transform:uppercase;border-bottom:1px solid rgba(85,126,218,.22); }
        .call-dashboard-day-table td { padding:9px 10px;color:#dfe8ff;text-align:right;border-bottom:1px solid rgba(85,126,218,.12); }
        .call-dashboard-day-table th:first-child,.call-dashboard-day-table td:first-child { text-align:left; }
        .call-dashboard-day-table td.talk { color:#c4b5fd;font-weight:700; }
        .call-dashboard-day-table td.miss { color:#f58aa8; }
        .call-dashboard-day-name { display:flex;align-items:center;gap:10px;min-width:160px; }
        .call-dashboard-day-name .track { flex:1;max-width:160px;height:6px;overflow:hidden;border-radius:6px;background:rgba(26,62,123,.62); }
        .call-dashboard-day-name .track i { display:block;height:100%;border-radius:6px;background:linear-gradient(90deg,#438ff0,#2dd4ee); }
        .call-dashboard-day-name span { flex:0 0 140px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap; }
        .call-dashboard-day-wrap { overflow-x:auto; }
        .call-dashboard-day-empty { padding:22px;color:#7184b4;font-size:13px;text-align:center; }
        @media(max-width:720px){.call-dashboard-day-stats{grid-template-columns:repeat(2,minmax(0,1fr))}.call-dashboard-trend-legend em{display:none}}
        @media(max-width:900px){.call-dashboard-hero{align-items:flex-start;flex-direction:column}.call-dashboard-grid{grid-template-columns:1fr}.call-dashboard-hero-meta{width:100%;justify-content:space-between}}
        @media(max-width:560px){.call-dashboard-hero{padding:18px}.call-dashboard-heading h1{font-size:23px}.call-dashboard-updated{display:none}.call-dashboard-breakdown{gap:18px}.call-dashboard-legend span{min-width:0}.call-dashboard-agent-row{grid-template-columns:95px 1fr 30px}}
    </style>
    <style>
        .call-dashboard { max-width:1600px;margin:0 auto;padding-bottom:24px; }
        .call-dashboard-heading { margin-bottom:14px;padding:2px 2px 0; }
        .call-dashboard-heading h1 { font-size:24px;letter-spacing:-.02em; }
        .call-dashboard-scope { display:inline-flex;align-items:center;gap:7px;padding:6px 12px;box-shadow:inset 0 0 16px rgba(45,212,238,.04); }
        .call-dashboard-scope::before { content:'';width:6px;height:6px;border-radius:50%;background:#2dd4ee;box-shadow:0 0 10px #2dd4ee; }
        .call-dashboard-kpis { grid-template-columns:repeat(12,minmax(0,1fr));gap:10px;margin-bottom:14px; }
        .call-dashboard-kpi { grid-column:span 3;min-height:92px;padding:14px 15px;border-color:rgba(85,126,218,.28);border-radius:12px;background:linear-gradient(145deg,rgba(10,32,76,.9),rgba(6,22,54,.82));box-shadow:0 8px 22px rgba(0,0,0,.08);transition:border-color .18s ease,transform .18s ease,box-shadow .18s ease; }
        .call-dashboard-kpi:nth-child(n+5) { grid-column:span 4; }
        .call-dashboard-kpis.is-operations .call-dashboard-kpi { grid-column:span 3; }
        .call-dashboard-kpi:hover { border-color:rgba(45,212,238,.42);box-shadow:0 12px 30px rgba(0,0,0,.14);transform:translateY(-2px); }
        .call-dashboard-kpi::after { content:'';position:absolute;right:0;bottom:0;left:0;height:2px;border-radius:0 0 12px 12px;background:linear-gradient(90deg,transparent,rgba(45,212,238,.65),transparent);opacity:.55; }
        .call-dashboard-kpi i { top:13px;right:14px;display:flex;align-items:center;justify-content:center;width:30px;height:30px;border:1px solid rgba(45,212,238,.18);border-radius:9px;background:rgba(45,212,238,.07);font-size:17px; }
        .call-dashboard-kpi small { padding-right:34px;font-size:10px;letter-spacing:.1em; }
        .call-dashboard-kpi strong { margin-top:7px;font-size:23px;letter-spacing:-.025em; }
        .call-dashboard-kpi em { margin-top:6px;font-size:10px; }
        .call-dashboard-kpi:nth-child(2) i,.call-dashboard-kpi:nth-child(5) i { color:#26d4ae; }
        .call-dashboard-kpi:nth-child(3) i { color:#ef6a91;border-color:rgba(239,106,145,.2);background:rgba(239,106,145,.07); }
        .call-dashboard-kpi:nth-child(4) i { color:#f7b955;border-color:rgba(247,185,85,.2);background:rgba(247,185,85,.07); }
        .call-dashboard-grid { gap:12px;margin-bottom:12px; }
        .call-dashboard-panel { border-radius:12px;background:linear-gradient(145deg,rgba(9,29,69,.88),rgba(6,21,51,.78));box-shadow:0 10px 30px rgba(0,0,0,.1); }
        .call-dashboard-panel h2 { display:flex;align-items:center;min-height:48px;padding:12px 16px;font-size:14px; }
        .call-dashboard-panel-body { min-height:164px;padding:16px 20px; }
        .call-dashboard-donut { width:112px;height:112px;flex-basis:112px;box-shadow:0 0 28px rgba(45,212,238,.08); }
        .call-dashboard-donut::after { width:74px;height:74px; }
        .call-dashboard-donut-label strong { font-size:20px; }
        .call-dashboard-agents { gap:10px;max-height:148px; }
        .call-dashboard-agent-row { padding:2px 0; }
        .call-dashboard-trend .call-dashboard-panel-body { height:300px; }
        @media(max-width:1100px){.call-dashboard-kpis{grid-template-columns:repeat(12,minmax(0,1fr))}.call-dashboard-kpi,.call-dashboard-kpi:nth-child(n+5),.call-dashboard-kpis.is-operations .call-dashboard-kpi{grid-column:span 6}}
        @media(max-width:720px){.call-dashboard-kpis{grid-template-columns:1fr}.call-dashboard-kpi,.call-dashboard-kpi:nth-child(n+5),.call-dashboard-kpis.is-operations .call-dashboard-kpi{grid-column:auto}.call-dashboard-kpi{min-height:88px}}
    </style>
    <div class="call-dashboard">
        <header class="call-dashboard-hero">
            <div class="call-dashboard-heading">
                <div class="call-dashboard-breadcrumb">Call Management <span>› &nbsp; Dashboard</span></div>
                <h1>Calling Dashboard</h1>
                <p class="call-dashboard-subtitle">A clear view of calling activity, agent availability and overall performance.</p>
            </div>
            <div class="call-dashboard-hero-meta">
                @role('superadmin')
                    <div class="call-dashboard-plivo" title="INR value based on the configured Plivo billing-credit rate">
                        <i class="material-icons">account_balance_wallet</i>
                        <span><small>Plivo Balance</small><strong id="plivoBalanceValue">Loading...</strong></span>
                    </div>
                @endrole
                <span class="call-dashboard-updated">Live data · refreshes automatically</span>
                <span class="call-dashboard-scope">{{ $canViewAllAgents ? 'All agents' : 'My performance' }}</span>
            </div>
        </header>
        <div class="call-dashboard-section-title">Performance overview</div>
        <section class="call-dashboard-kpis">
            <article class="call-dashboard-kpi"><small>Total Dial</small><strong>{{ number_format($totalDial) }}</strong><div class="call-dashboard-kpi-meta">All call attempts</div><i class="material-icons">dialpad</i></article>
            <article class="call-dashboard-kpi"><small>Call Connected</small><strong>{{ number_format($connected) }}</strong><em>↗ {{ $connectRate }}% connect rate</em><i class="material-icons">phone_in_talk</i></article>
            <article class="call-dashboard-kpi"><small>Not Connected</small><strong>{{ number_format($notConnected) }}</strong><div class="call-dashboard-kpi-meta">Unanswered attempts</div><i class="material-icons">phone_missed</i></article>
            <article class="call-dashboard-kpi"><small>Pending Calls</small><strong>{{ number_format($pendingCalls) }}</strong><div class="call-dashboard-kpi-meta">Assigned · not called yet</div><i class="material-icons">pending_actions</i></article>
        </section>
        <div class="call-dashboard-section-title">Live operations</div>
        <section class="call-dashboard-kpis is-operations">
            <article class="call-dashboard-kpi"><small>Today's Calls</small><strong>{{ number_format($todayCalls) }}</strong><div class="call-dashboard-kpi-meta">Dialed today</div><i class="material-icons">today</i></article>
            <article class="call-dashboard-kpi"><small>Follow-up / No Response</small><strong>{{ number_format($followUpOrNoResponseCalls) }}</strong><div class="call-dashboard-kpi-meta">Latest call outcome</div><i class="material-icons">phone_callback</i></article>
            <article class="call-dashboard-kpi"><small>Agents On Call</small><strong id="agentsOnCallCount">{{ number_format($agentsOnCall) }}</strong><div class="call-dashboard-kpi-meta is-live">Currently connected</div><i class="material-icons">phone_in_talk</i></article>
            <article class="call-dashboard-kpi"><small>Total Talk Time</small><strong>{{ $totalTalkTime }}</strong><div class="call-dashboard-kpi-meta">Connected duration</div><i class="material-icons">schedule</i></article>
        </section>
        <div class="call-dashboard-section-title">Call analytics</div>
        <section class="call-dashboard-grid">
            <article class="call-dashboard-panel">
                <h2>Call Status Breakdown</h2>
                <div class="call-dashboard-panel-body call-dashboard-breakdown">
                    <div style="position:relative"><div class="call-dashboard-donut" style="--connected:{{ $totalDial ? ($connected / $totalDial) * 100 : 0 }}%"></div><div class="call-dashboard-donut-label"><strong>{{ number_format($totalDial) }}</strong><small>TOTAL CALLS</small></div></div>
                    <div class="call-dashboard-legend"><span><i class="call-dashboard-dot"></i>Connected <b>{{ number_format($connected) }}</b></span><span><i class="call-dashboard-dot red"></i>Not connected <b>{{ number_format($notConnected) }}</b></span></div>
                </div>
            </article>
            <article class="call-dashboard-panel">
                <h2>Calls per Caller</h2>
                <div class="call-dashboard-panel-body call-dashboard-agents">
                    @php($maxAgentCalls = max(1, (int) $agentCallCounts->max('dashboard_calls_count')))
                    @forelse($agentCallCounts as $agent)
                        <div class="call-dashboard-agent-row"><span title="{{ $agent->name }}">{{ \Illuminate\Support\Str::limit($agent->name, 16) }}</span><div class="call-dashboard-agent-track"><div class="call-dashboard-agent-bar" style="width:{{ ($agent->dashboard_calls_count / $maxAgentCalls) * 100 }}%"></div></div><b>{{ $agent->dashboard_calls_count }}</b></div>
                    @empty
                        <div class="call-dashboard-empty">No active calling agents found.</div>
                    @endforelse
                </div>
            </article>
        </section>
        <section class="call-dashboard-panel call-dashboard-trend">
            <div class="call-dashboard-trend-head">
                <h2>Calls Trend (last 3 months)</h2>
                <div class="call-dashboard-trend-legend"><span><i></i>Calls</span><span><i class="talk"></i>Talk time</span><em>Click a day to see agent-wise calls</em></div>
            </div>
            <div class="call-dashboard-panel-body">
                <div class="call-dashboard-trend-scroll" id="callTrendScroll"><canvas class="call-dashboard-chart" id="callTrendChart"></canvas></div>
                <div class="call-dashboard-trend-tip" id="callTrendTip"></div>
            </div>
            <div class="call-dashboard-day" id="callTrendDay"></div>
        </section>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const plivoBalanceValue = document.getElementById('plivoBalanceValue');
            if (plivoBalanceValue) {
                fetch(@json(route('call-management.dashboard.plivo-balance')), {
                    headers: {'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest'}
                }).then(response => response.ok ? response.json() : Promise.reject())
                  .then(data => { plivoBalanceValue.textContent = data.symbol + Number(data.balance).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2}); })
                  .catch(() => { plivoBalanceValue.textContent = 'Unavailable'; });
            }

            const trend = @json($trend);
            const canvas = document.getElementById('callTrendChart');
            const scroller = document.getElementById('callTrendScroll');
            const tip = document.getElementById('callTrendTip');
            const dayPanel = document.getElementById('callTrendDay');
            const esc = v => String(v).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
            const fmt = n => Number(n).toLocaleString('en-IN');
            const geo = {left: 46, right: 24, top: 46, bottom: 34, step: 64, points: []};
            let hovered = -1, selected = trend.length - 1;

            const niceMax = v => { if (v <= 5) return 5; const p = Math.pow(10, Math.floor(Math.log10(v))); return [1, 2, 2.5, 5, 10].map(m => m * p).find(m => m >= v); };
            const curve = (ctx, pts) => {
                const n = pts.length, d = [], m = [];
                for (let i = 0; i < n - 1; i++) d.push((pts[i + 1][1] - pts[i][1]) / (pts[i + 1][0] - pts[i][0]));
                for (let i = 0; i < n; i++) m.push(i === 0 ? d[0] || 0 : i === n - 1 ? d[n - 2] : (d[i - 1] * d[i] <= 0 ? 0 : (d[i - 1] + d[i]) / 2));
                for (let i = 0; i < n - 1; i++) {
                    if (d[i] === 0) { m[i] = m[i + 1] = 0; continue; }
                    const a = m[i] / d[i], b = m[i + 1] / d[i], s = a * a + b * b;
                    if (s > 9) { const t = 3 / Math.sqrt(s); m[i] = t * a * d[i]; m[i + 1] = t * b * d[i]; }
                }
                ctx.moveTo(...pts[0]);
                for (let i = 0; i < n - 1; i++) {
                    const dx = (pts[i + 1][0] - pts[i][0]) / 3;
                    ctx.bezierCurveTo(pts[i][0] + dx, pts[i][1] + m[i] * dx, pts[i + 1][0] - dx, pts[i + 1][1] - m[i + 1] * dx, ...pts[i + 1]);
                }
            };

            const draw = () => {
                canvas.style.width = Math.max(scroller.clientWidth, trend.length * 64) + 'px';
                const ratio = window.devicePixelRatio || 1, box = canvas.getBoundingClientRect();
                canvas.width = box.width * ratio; canvas.height = box.height * ratio;
                const ctx = canvas.getContext('2d'); ctx.scale(ratio, ratio);
                const w = box.width, h = box.height, {left, right, top, bottom} = geo, base = h - bottom;
                const max = niceMax(Math.max(1, ...trend.map(d => d.total)));
                const step = geo.step = (w - left - right) / Math.max(1, trend.length - 1);
                const pts = geo.points = trend.map((d, i) => [left + i * step, top + (base - top) * (1 - d.total / max)]);

                ctx.font = '10px sans-serif'; ctx.textAlign = 'right'; ctx.textBaseline = 'middle';
                for (let i = 0; i <= 4; i++) {
                    const y = top + (base - top) * i / 4;
                    ctx.strokeStyle = i === 4 ? 'rgba(85,126,218,.35)' : 'rgba(85,126,218,.13)'; ctx.lineWidth = 1;
                    ctx.setLineDash(i === 4 ? [] : [4, 5]); ctx.beginPath(); ctx.moveTo(left, y); ctx.lineTo(w - right, y); ctx.stroke();
                    ctx.fillStyle = '#5f73a6'; ctx.fillText(fmt(Math.round(max * (4 - i) / 4)), left - 10, y);
                }
                ctx.setLineDash([]);

                const band = (i, color) => { ctx.fillStyle = color; ctx.fillRect(pts[i][0] - step / 2, top - 30, step, base - top + 30); };
                trend.forEach((d, i) => { if (new Date(d.date + 'T00:00:00').getDay() === 0) band(i, 'rgba(85,126,218,.05)'); });
                if (selected >= 0) band(selected, 'rgba(45,212,238,.08)');
                if (hovered >= 0 && hovered !== selected) band(hovered, 'rgba(45,212,238,.05)');

                ctx.textAlign = 'left'; ctx.textBaseline = 'alphabetic';
                trend.forEach((d, i) => {
                    if (d.date.slice(8) !== '01') return;
                    const x = pts[i][0] - step / 2;
                    if (i > 0) { ctx.strokeStyle = 'rgba(45,212,238,.28)'; ctx.setLineDash([3, 4]); ctx.beginPath(); ctx.moveTo(x, top - 34); ctx.lineTo(x, base); ctx.stroke(); ctx.setLineDash([]); }
                    ctx.fillStyle = '#35ccef'; ctx.font = '800 10px sans-serif';
                    ctx.fillText(new Date(d.date + 'T00:00:00').toLocaleString('en-IN', {month: 'long', year: 'numeric'}).toUpperCase(), Math.max(left, x + 8), 12);
                });

                const area = ctx.createLinearGradient(0, top, 0, base);
                area.addColorStop(0, 'rgba(45,212,238,.28)'); area.addColorStop(1, 'rgba(45,212,238,0)');
                ctx.beginPath(); curve(ctx, pts); ctx.lineTo(pts[pts.length - 1][0], base); ctx.lineTo(pts[0][0], base); ctx.closePath(); ctx.fillStyle = area; ctx.fill();
                ctx.beginPath(); curve(ctx, pts); ctx.strokeStyle = '#2dd4ee'; ctx.lineWidth = 2.5; ctx.shadowColor = 'rgba(45,212,238,.45)'; ctx.shadowBlur = 8; ctx.stroke(); ctx.shadowBlur = 0;

                if (hovered >= 0) { ctx.strokeStyle = 'rgba(45,212,238,.5)'; ctx.setLineDash([3, 3]); ctx.beginPath(); ctx.moveTo(pts[hovered][0], top - 20); ctx.lineTo(pts[hovered][0], base); ctx.stroke(); ctx.setLineDash([]); }

                trend.forEach((d, i) => {
                    const [x, y] = pts[i], active = i === hovered || i === selected;
                    ctx.beginPath(); ctx.arc(x, y, d.total ? (active ? 7 : 4.5) : 2.5, 0, Math.PI * 2);
                    ctx.fillStyle = d.total ? (active ? '#2dd4ee' : '#071b41') : 'rgba(95,115,166,.6)'; ctx.fill();
                    if (d.total) { ctx.strokeStyle = active ? '#e0fbff' : '#2dd4ee'; ctx.lineWidth = active ? 3 : 2.5; ctx.stroke(); }
                });

                ctx.textAlign = 'center'; ctx.lineJoin = 'round';
                trend.forEach((d, i) => {
                    const today = i === trend.length - 1, x = pts[i][0];
                    ctx.font = (i === selected || today ? '700 ' : '') + '11px sans-serif';
                    ctx.fillStyle = i === selected ? '#2dd4ee' : today ? '#dfe8ff' : '#6a7eb0';
                    ctx.fillText(today ? 'Today' : d.label, x, h - 10);
                    if (!d.total) return;
                    const y = Math.max(40, pts[i][1] - 12);
                    ctx.lineWidth = 4; ctx.strokeStyle = '#081b42';
                    ctx.font = '700 12px sans-serif'; ctx.fillStyle = '#f2f6ff';
                    ctx.strokeText(fmt(d.total), x, d.duration_label ? y - 13 : y); ctx.fillText(fmt(d.total), x, d.duration_label ? y - 13 : y);
                    if (d.duration_label) { ctx.font = '600 10px sans-serif'; ctx.fillStyle = '#a78bfa'; ctx.strokeText(d.duration_label, x, y); ctx.fillText(d.duration_label, x, y); }
                });
            };

            const agentRows = (agents, limit) => agents.slice(0, limit).map(a =>
                `<div class="call-dashboard-tip-agent"><span>${esc(a.name)}</span><b>${fmt(a.total)} <small>· ${esc(a.duration_full)}</small></b></div>`).join('');

            const showTip = i => {
                const d = trend[i];
                if (i < 0) { tip.classList.remove('is-visible'); return; }
                tip.innerHTML = `<h4>${esc(d.day)}</h4>
                    <div class="call-dashboard-tip-stats"><div><small>Calls</small><b>${fmt(d.total)}</b></div><div><small>Connected</small><b>${fmt(d.connected)}</b></div><div><small>Talk</small><b class="talk">${esc(d.duration_label || '0m')}</b></div></div>
                    ${d.agents.length ? agentRows(d.agents, 5) : '<div class="call-dashboard-tip-agent"><span>No calls on this day</span></div>'}
                    ${d.agents.length > 5 ? `<div class="call-dashboard-tip-more">+${d.agents.length - 5} more · click for details</div>` : ''}`;
                const body = tip.parentElement, x = geo.points[i][0] - scroller.scrollLeft + 12;
                const left = x + 20 + tip.offsetWidth > body.clientWidth ? x - tip.offsetWidth - 20 : x + 20;
                tip.style.left = Math.max(8, left) + 'px';
                tip.style.top = Math.min(Math.max(8, geo.points[i][1] - 40), body.clientHeight - tip.offsetHeight - 8) + 'px';
                tip.classList.add('is-visible');
            };

            const renderDay = () => {
                const d = trend[selected], top = Math.max(1, ...d.agents.map(a => a.total));
                dayPanel.innerHTML = `<div class="call-dashboard-day-head">
                        <h3><small>Agent-wise calls</small>${esc(d.day)}</h3>
                        <div class="call-dashboard-day-nav"><button type="button" data-step="-1" ${selected === 0 ? 'disabled' : ''} title="Previous day">&#8249;</button><button type="button" data-step="1" ${selected === trend.length - 1 ? 'disabled' : ''} title="Next day">&#8250;</button></div>
                    </div>
                    <div class="call-dashboard-day-stats">
                        <div><small>Total calls</small><b>${fmt(d.total)}</b></div>
                        <div><small>Connected</small><b>${fmt(d.connected)}</b></div>
                        <div><small>Not connected</small><b>${fmt(d.total - d.connected)}</b></div>
                        <div><small>Talk time</small><b>${esc(d.duration_full)}</b></div>
                    </div>
                    ${d.agents.length ? `<div class="call-dashboard-day-wrap"><table class="call-dashboard-day-table">
                        <thead><tr><th>Agent</th><th>Calls</th><th>Connected</th><th>Not connected</th><th>Talk time</th><th>Avg / call</th></tr></thead>
                        <tbody>${d.agents.map(a => `<tr>
                            <td><div class="call-dashboard-day-name"><span title="${esc(a.name)}">${esc(a.name)}</span><div class="track"><i style="width:${a.total / top * 100}%"></i></div></div></td>
                            <td><b>${fmt(a.total)}</b></td><td>${fmt(a.connected)}</td><td class="miss">${fmt(a.total - a.connected)}</td>
                            <td class="talk">${esc(a.duration_full)}</td><td>${a.connected ? (s => (s >= 60 ? Math.floor(s / 60) + 'm ' : '') + (s % 60) + 's')(Math.round(a.duration / a.connected)) : '–'}</td></tr>`).join('')}</tbody>
                    </table></div>` : '<div class="call-dashboard-day-empty">No calls were made on this day.</div>'}`;
            };

            const indexAt = e => {
                const x = e.clientX - canvas.getBoundingClientRect().left;
                const i = Math.round((x - geo.left) / geo.step);
                return i >= 0 && i < trend.length ? i : -1;
            };
            const select = i => { selected = i; draw(); renderDay(); };

            canvas.addEventListener('mousemove', e => { const i = indexAt(e); if (i !== hovered) { hovered = i; draw(); } showTip(i); });
            canvas.addEventListener('mouseleave', () => { hovered = -1; draw(); showTip(-1); });
            canvas.addEventListener('click', e => { const i = indexAt(e); if (i >= 0) select(i); });
            dayPanel.addEventListener('click', e => {
                const btn = e.target.closest('[data-step]'); if (!btn) return;
                select(Math.min(trend.length - 1, Math.max(0, selected + Number(btn.dataset.step))));
                const x = geo.points[selected][0];
                if (x < scroller.scrollLeft + 40 || x > scroller.scrollLeft + scroller.clientWidth - 40) scroller.scrollLeft = x - scroller.clientWidth / 2;
            });
            scroller.addEventListener('scroll', () => { if (hovered >= 0) { hovered = -1; draw(); showTip(-1); } });
            window.addEventListener('resize', draw);
            draw(); renderDay(); scroller.scrollLeft = scroller.scrollWidth;

            const refreshOnCallCount = () => fetch(@json(route('call-management.dashboard.on-call-count')), {
                headers: {'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest'}
            }).then(response => response.ok ? response.json() : Promise.reject())
              .then(data => { document.getElementById('agentsOnCallCount').textContent = Number(data.count || 0).toLocaleString(); })
              .catch(() => {});
            window.setInterval(refreshOnCallCount, 15000);
        });
    </script>
</x-app-layout>
