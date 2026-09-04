<x-app-layout>
    <style>
        .call-dashboard{color:#c5d2f3}.call-dashboard-breadcrumb{margin-bottom:8px;color:#7185bd;font-size:11px;font-weight:800;letter-spacing:.22em;text-transform:uppercase}.call-dashboard-breadcrumb span{margin-left:8px;color:#35ccef}.call-dashboard-heading{display:flex;align-items:center;justify-content:space-between;gap:16px;margin-bottom:18px}.call-dashboard-heading h1{margin:0;color:#f7f9ff;font-size:25px;font-weight:800}.call-dashboard-scope{padding:7px 13px;border:1px solid rgba(34,211,238,.38);border-radius:999px;background:rgba(34,211,238,.08);color:#2dd4ee;font-size:12px;font-weight:800}.call-dashboard-kpis{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:12px;margin-bottom:18px}.call-dashboard-kpi,.call-dashboard-panel{border:1px solid rgba(85,126,218,.3);border-radius:14px;background:rgba(7,25,61,.76);box-shadow:0 10px 28px rgba(0,0,0,.08)}.call-dashboard-kpi{position:relative;min-height:116px;padding:19px 17px}.call-dashboard-kpi i{position:absolute;top:17px;right:16px;color:#2dd4ee;font-size:20px}.call-dashboard-kpi small{display:block;color:#8295c5;font-size:11px;font-weight:800;letter-spacing:.08em;text-transform:uppercase}.call-dashboard-kpi strong{display:block;margin-top:9px;color:#f7f9ff;font-size:27px;line-height:1}.call-dashboard-kpi em{display:block;margin-top:9px;color:#21cda9;font-size:11px;font-style:normal;font-weight:800}.call-dashboard-grid{display:grid;grid-template-columns:minmax(0,1fr) minmax(0,1fr);gap:14px;margin-bottom:14px}.call-dashboard-panel{overflow:hidden;border-top:2px solid #28cae9}.call-dashboard-panel h2{margin:0;padding:15px 17px;border-bottom:1px solid rgba(85,126,218,.2);color:#e8efff;font-size:16px}.call-dashboard-panel-body{min-height:185px;padding:20px}.call-dashboard-breakdown{display:flex;align-items:center;gap:28px}.call-dashboard-donut{display:flex;align-items:center;justify-content:center;width:128px;height:128px;flex:0 0 128px;border-radius:50%;background:conic-gradient(#2dd4ee 0 var(--connected),#ef4d7b var(--connected) 100%)}.call-dashboard-donut::after{content:'';width:82px;height:82px;border-radius:50%;background:#081b42}.call-dashboard-donut-label{position:absolute;text-align:center}.call-dashboard-donut-label strong{display:block;color:#f7f9ff;font-size:23px}.call-dashboard-donut-label small{color:#7f92c1;font-size:9px}.call-dashboard-legend{display:grid;gap:12px}.call-dashboard-legend span{display:flex;align-items:center;gap:9px;color:#aebee4;font-size:13px}.call-dashboard-dot{width:9px;height:9px;border-radius:50%;background:#2dd4ee}.call-dashboard-dot.red{background:#ef4d7b}.call-dashboard-agents{display:grid;gap:12px;max-height:170px;overflow:auto}.call-dashboard-agent-row{display:grid;grid-template-columns:110px 1fr 35px;align-items:center;gap:10px;color:#aebee4;font-size:12px}.call-dashboard-agent-track{height:8px;overflow:hidden;border-radius:8px;background:#102959}.call-dashboard-agent-bar{height:100%;min-width:2px;border-radius:8px;background:linear-gradient(90deg,#438ff0,#2dd4ee)}.call-dashboard-agent-row b{text-align:right;color:#edf3ff}.call-dashboard-trend .call-dashboard-panel-body{height:280px;padding:12px 16px 16px}.call-dashboard-chart{width:100%;height:100%}.call-dashboard-empty{display:flex;align-items:center;justify-content:center;height:100%;color:#7184b4;font-size:13px}@media(max-width:1000px){.call-dashboard-kpis{grid-template-columns:repeat(2,minmax(0,1fr))}}@media(max-width:720px){.call-dashboard-kpis,.call-dashboard-grid{grid-template-columns:1fr}.call-dashboard-heading{align-items:flex-start;flex-direction:column}.call-dashboard-breakdown{justify-content:center}.call-dashboard-agent-row{grid-template-columns:85px 1fr 30px}}
    </style>
    <div class="call-dashboard">
        <div class="call-dashboard-breadcrumb">Call Management <span>› &nbsp; Dashboard</span></div>
        <div class="call-dashboard-heading">
            <h1>Calling Dashboard</h1>
            <span class="call-dashboard-scope">{{ $canViewAllAgents ? 'All agents' : 'My performance' }}</span>
        </div>
        <section class="call-dashboard-kpis">
            <article class="call-dashboard-kpi"><small>Total Dial</small><strong>{{ number_format($totalDial) }}</strong><i class="material-icons">dialpad</i></article>
            <article class="call-dashboard-kpi"><small>Call Connected</small><strong>{{ number_format($connected) }}</strong><em>↗ {{ $connectRate }}% connect rate</em><i class="material-icons">phone_in_talk</i></article>
            <article class="call-dashboard-kpi"><small>Not Connected</small><strong>{{ number_format($notConnected) }}</strong><i class="material-icons">phone_missed</i></article>
            <article class="call-dashboard-kpi"><small>Live Agents</small><strong>{{ number_format($liveAgents) }}</strong><i class="material-icons">support_agent</i></article>
            <article class="call-dashboard-kpi"><small>Total Talk Time</small><strong>{{ $totalTalkTime }}</strong><i class="material-icons">schedule</i></article>
            <article class="call-dashboard-kpi"><small>Pending Calls</small><strong>{{ number_format($pendingCalls) }}</strong><i class="material-icons">pending_actions</i></article>
        </section>
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
                ctx.beginPath(); points.forEach((p,i)=>i?ctx.lineTo(...p):ctx.moveTo(...p)); ctx.strokeStyle='#2dd4ee';ctx.lineWidth=3;ctx.stroke();
                ctx.fillStyle='#2dd4ee';points.forEach(p=>{ctx.beginPath();ctx.arc(...p,5,0,Math.PI*2);ctx.fill()});
                ctx.fillStyle='#7184b4';ctx.font='11px sans-serif';ctx.textAlign='center';labels.forEach((label,i)=>ctx.fillText(label,left+i*step,h-8));
                ctx.fillStyle='#dfe8ff';values.forEach((value,i)=>ctx.fillText(value,left+i*step,Math.max(12,points[i][1]-10)));
            };
            draw(); window.addEventListener('resize', draw);
        });
    </script>
</x-app-layout>
