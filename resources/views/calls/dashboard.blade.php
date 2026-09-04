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
        .call-dashboard-trend .call-dashboard-panel-body { height:260px;padding:12px 18px 14px; }
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
        .call-dashboard-trend .call-dashboard-panel-body { height:245px; }
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
                    <div class="call-dashboard-plivo" title="Approximate INR value based on the configured USD to INR rate">
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
            <article class="call-dashboard-kpi"><small>Pending Calls</small><strong>{{ number_format($pendingCalls) }}</strong><div class="call-dashboard-kpi-meta">Awaiting action</div><i class="material-icons">pending_actions</i></article>
        </section>
        <div class="call-dashboard-section-title">Live operations</div>
        <section class="call-dashboard-kpis is-operations">
            <article class="call-dashboard-kpi"><small>Today's Calls</small><strong>{{ number_format($todayCalls) }}</strong><div class="call-dashboard-kpi-meta">Dialed today</div><i class="material-icons">today</i></article>
            <article class="call-dashboard-kpi"><small>Live Agents</small><strong>{{ number_format($liveAgents) }}</strong><div class="call-dashboard-kpi-meta">Calling enabled</div><i class="material-icons">support_agent</i></article>
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
            <h2>Calls Trend (last 7 days)</h2>
            <div class="call-dashboard-panel-body"><canvas class="call-dashboard-chart" id="callTrendChart"></canvas></div>
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

            const canvas = document.getElementById('callTrendChart');
            const values = @json($trend->pluck('total'));
            const labels = @json($trend->pluck('label'));
            const draw = () => {
                const ratio = window.devicePixelRatio || 1, box = canvas.getBoundingClientRect();
                canvas.width = box.width * ratio; canvas.height = box.height * ratio;
                const ctx = canvas.getContext('2d'); ctx.scale(ratio, ratio);
                const w = box.width, h = box.height, left = 34, right = 15, top = 22, bottom = 30;
                const max = Math.max(1, ...values), step = (w - left - right) / Math.max(1, values.length - 1);
                ctx.strokeStyle = 'rgba(85,126,218,.18)'; ctx.lineWidth = 1;
                for (let i=0;i<4;i++){const y=top+(h-top-bottom)*i/3;ctx.beginPath();ctx.moveTo(left,y);ctx.lineTo(w-right,y);ctx.stroke()}
                const points = values.map((v,i)=>[left+i*step,top+(h-top-bottom)*(1-v/max)]);
                const area = ctx.createLinearGradient(0,top,0,h-bottom); area.addColorStop(0,'rgba(45,212,238,.22)');area.addColorStop(1,'rgba(45,212,238,0)');
                ctx.beginPath();ctx.moveTo(points[0][0],h-bottom);points.forEach(p=>ctx.lineTo(...p));ctx.lineTo(points[points.length-1][0],h-bottom);ctx.closePath();ctx.fillStyle=area;ctx.fill();
                ctx.beginPath(); points.forEach((p,i)=>i?ctx.lineTo(...p):ctx.moveTo(...p)); ctx.strokeStyle='#2dd4ee';ctx.lineWidth=3;ctx.stroke();
                ctx.fillStyle='#071b41';points.forEach(p=>{ctx.beginPath();ctx.arc(...p,5,0,Math.PI*2);ctx.fill();ctx.strokeStyle='#2dd4ee';ctx.lineWidth=3;ctx.stroke()});
                ctx.fillStyle='#7184b4';ctx.font='11px sans-serif';ctx.textAlign='center';labels.forEach((label,i)=>ctx.fillText(label,left+i*step,h-8));
                ctx.fillStyle='#dfe8ff';values.forEach((value,i)=>ctx.fillText(value,left+i*step,Math.max(12,points[i][1]-10)));
            };
            draw(); window.addEventListener('resize', draw);

            const refreshOnCallCount = () => fetch(@json(route('call-management.dashboard.on-call-count')), {
                headers: {'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest'}
            }).then(response => response.ok ? response.json() : Promise.reject())
              .then(data => { document.getElementById('agentsOnCallCount').textContent = Number(data.count || 0).toLocaleString(); })
              .catch(() => {});
            window.setInterval(refreshOnCallCount, 15000);
        });
    </script>
</x-app-layout>
