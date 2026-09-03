<x-app-layout>
    @php($totalRecords = $callLogs->count())
    <style>
        .customer-history { color:#c5d2f3; }
        .customer-history-breadcrumb { margin-bottom:8px;color:#7185bd;font-size:11px;font-weight:800;letter-spacing:.22em;text-transform:uppercase; }
        .customer-history-breadcrumb span { margin-left:8px;color:#35ccef; }
        .customer-history-heading { display:flex;align-items:center;justify-content:space-between;gap:20px;margin-bottom:18px; }
        .customer-history-heading-main { display:flex;align-items:center;gap:12px; }
        .customer-history-heading h1 { margin:0;color:#f7f9ff;font-size:25px;font-weight:800; }
        .customer-history-count { display:inline-flex;align-items:center;min-height:31px;padding:0 16px;border:1px solid rgba(34,211,238,.48);border-radius:999px;background:rgba(34,211,238,.08);color:#28d7f4;font-size:13px;font-weight:800; }
        .customer-history-actions { display:flex;align-items:center;gap:10px; }
        .customer-history-filter-trigger,.customer-history-export { display:inline-flex;align-items:center;justify-content:center;gap:9px;height:44px;padding:0 20px;border:1px solid rgba(85,126,218,.38);border-radius:12px;background:rgba(7,20,49,.62);color:#c7d5f5;font-size:14px;font-weight:700;text-decoration:none; }
        .customer-history-filter-trigger { min-width:148px; }
        .customer-history-filter-trigger.is-active::after { content:'';width:7px;height:7px;border-radius:50%;background:#2dd4ee;box-shadow:0 0 10px rgba(45,212,238,.8); }
        .customer-history-export:hover { border-color:rgba(34,211,238,.5);color:#2dd4ee; }
        .customer-history-filter-trigger .material-icons,.customer-history-export .material-icons { font-size:20px; }
        .customer-history-filter-overlay { position:fixed;inset:0;z-index:4500;visibility:hidden;background:rgba(1,8,24,.68);opacity:0;transition:opacity .22s ease,visibility .22s ease;backdrop-filter:blur(3px); }
        .customer-history-filter-overlay.show { visibility:visible;opacity:1; }
        .customer-history-filter-drawer { position:absolute;top:0;right:0;display:flex;flex-direction:column;width:min(560px,100%);height:100%;border-left:1px solid rgba(85,126,218,.36);background:#081b42;box-shadow:-24px 0 70px rgba(0,0,0,.36);transform:translateX(100%);transition:transform .25s ease; }
        .customer-history-filter-overlay.show .customer-history-filter-drawer { transform:translateX(0); }
        .customer-history-filter-head { display:flex;align-items:center;justify-content:space-between;gap:20px;min-height:104px;padding:22px 28px;border-bottom:1px solid rgba(85,126,218,.28); }
        .customer-history-filter-heading { display:flex;align-items:center;gap:15px; }
        .customer-history-filter-icon { display:inline-flex;align-items:center;justify-content:center;width:48px;height:48px;border:1px solid rgba(34,211,238,.45);border-radius:13px;background:rgba(34,211,238,.08);color:#2dd4ee; }
        .customer-history-filter-heading strong { display:block;color:#f5f8ff;font-size:20px;font-weight:800; }
        .customer-history-filter-heading small { display:block;margin-top:3px;color:#8395c4;font-size:13px; }
        .customer-history-filter-close { display:inline-flex;align-items:center;justify-content:center;width:42px;height:42px;border:1px solid rgba(85,126,218,.36);border-radius:11px;background:transparent;color:#aebfe7; }
        .customer-history-filters { display:flex;flex:1;flex-direction:column;min-height:0; }
        .customer-history-filter-body { flex:1;overflow-y:auto;padding:28px; }
        .customer-history-filter-grid { display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:20px 18px; }
        .customer-history-filter.is-wide { grid-column:1/-1; }
        .customer-history-filter label { display:block;margin-bottom:8px;color:#91a3ce;font-size:11px;font-weight:800;letter-spacing:.14em;text-transform:uppercase; }
        .customer-history-filter input,.customer-history-filter select { width:100%;height:46px;padding:0 14px;border:1px solid rgba(85,126,218,.38);border-radius:11px;outline:0;background:#071938;color:#d5e0fa;font-size:14px; }
        .customer-history-filter input:focus,.customer-history-filter select:focus { border-color:rgba(34,211,238,.58);box-shadow:0 0 0 3px rgba(34,211,238,.08); }
        .customer-history-filter input::placeholder { color:#6f81ae; }
        .customer-history-filter-actions { display:grid;grid-template-columns:132px 1fr;gap:12px;padding:18px 28px 24px;border-top:1px solid rgba(85,126,218,.28);background:#071837; }
        .customer-history-apply,.customer-history-reset { display:inline-flex;align-items:center;justify-content:center;height:46px;padding:0 18px;border-radius:11px;font-size:14px;font-weight:800;text-decoration:none;white-space:nowrap; }
        .customer-history-apply { border:0;background:linear-gradient(135deg,#2bd1e8,#438ff0);color:#061329; }
        .customer-history-reset { border:1px solid rgba(85,126,218,.38);background:transparent;color:#aebfe7; }
        .customer-history-card { overflow:hidden;border:1px solid rgba(85,126,218,.27);border-radius:14px;background:rgba(7,20,49,.54); }
        .customer-history-scroll { overflow-x:auto;overflow-y:hidden;scrollbar-width:thin;scrollbar-color:#17386f #04112d; }
        .customer-history-scroll::-webkit-scrollbar { height:7px; }
        .customer-history-scroll::-webkit-scrollbar-track { background:#04112d;border-radius:20px; }
        .customer-history-scroll::-webkit-scrollbar-thumb { border:1px solid #04112d;border-radius:20px;background:#17386f; }
        .customer-history-scroll::-webkit-scrollbar-thumb:hover { background:#24519a; }
        .customer-history-scroll::-webkit-scrollbar-corner { background:#04112d; }
        .customer-history-table { width:100%;min-width:1050px;border-collapse:collapse; }
        .customer-history-table th { padding:14px;border-bottom:1px solid rgba(85,126,218,.24);color:#8395c4;font-size:11px;font-weight:800;letter-spacing:.09em;text-align:left;text-transform:uppercase;white-space:nowrap; }
        .customer-history-table td { height:62px;padding:12px 14px;border-bottom:1px solid rgba(85,126,218,.18);color:#adbee6;font-size:13px;vertical-align:middle; }
        .customer-history-table tbody tr:last-child td { border-bottom:0; }
        .customer-history-status { display:inline-flex;padding:7px 12px;border:1px solid rgba(34,211,238,.34);border-radius:999px;color:#45d6ef;font-size:11px;font-weight:800;text-transform:uppercase; }
        .customer-history-recording { width:210px;height:34px; }
        .customer-history-empty { padding:38px 20px!important;color:#7d8fbd!important;text-align:center; }
        @media (max-width:640px) { .customer-history-heading { align-items:flex-start; } .customer-history-heading h1 { font-size:22px; } .customer-history-export span:not(.material-icons),.customer-history-filter-trigger span:not(.material-icons) { display:none; } .customer-history-export,.customer-history-filter-trigger { min-width:44px;width:44px;padding:0; } .customer-history-filter-head,.customer-history-filter-body { padding-left:20px;padding-right:20px; } .customer-history-filter-grid { grid-template-columns:1fr; } .customer-history-filter.is-wide { grid-column:auto; } .customer-history-filter-actions { grid-template-columns:1fr 1.5fr;padding-left:20px;padding-right:20px; } }
    </style>
    <div class="customer-history">
        <div class="customer-history-breadcrumb">Call Management <span>› &nbsp; Call History</span></div>
        <div class="customer-history-heading">
            <div class="customer-history-heading-main"><h1>Call History</h1><span class="customer-history-count">{{ $totalRecords }} {{ $totalRecords === 1 ? 'record' : 'records' }}</span></div>
            <div class="customer-history-actions">
                <button class="customer-history-filter-trigger {{ request()->hasAny(['search', 'agent_id', 'call_status', 'feedback_status_id', 'from_date', 'to_date']) ? 'is-active' : '' }}" id="openHistoryFilters" type="button"><span class="material-icons">tune</span><span>Filters</span></button>
                <a class="customer-history-export" href="{{ route('customer-call-history.export', request()->query()) }}"><span class="material-icons">download</span><span>Export Excel</span></a>
            </div>
        </div>
        <section class="customer-history-card">
            <div class="customer-history-scroll">
                <table class="customer-history-table">
                    <thead><tr><th>Agent</th><th>Firm Name</th><th>Contact Person</th><th>Mobile</th><th>Date &amp; Time</th><th>Duration</th><th>Status</th><th>Agent Status</th><th>Notes</th><th>Recording</th></tr></thead>
                    <tbody>
                        @forelse($callLogs as $callLog)
                            @php($duration = (int) $callLog->duration)
                            <tr>
                                <td>{{ optional($callLog->user)->name ?: '—' }}</td>
                                <td>{{ optional($callLog->callManagementEntry)->firm_name ?: '—' }}</td>
                                <td>{{ optional($callLog->callManagementEntry)->contact_person_name ?: '—' }}</td>
                                <td>{{ optional($callLog->callManagementEntry)->mobile_number ?: $callLog->number }}</td>
                                <td>{{ optional($callLog->started_at)->format('d/m/Y h:i A') ?: '—' }}</td>
                                <td>{{ sprintf('%02d:%02d:%02d', intdiv($duration, 3600), intdiv($duration % 3600, 60), $duration % 60) }}</td>
                                <td><span class="customer-history-status">{{ ((int) $callLog->duration > 0 || $callLog->recording_url || (int) $callLog->status === 1) ? 'Completed' : ($callLog->plivo_status ?: 'initiated') }}</span></td>
                                <td>{{ optional($callLog->feedbackStatus)->display_name ?: optional($callLog->feedbackStatus)->status_name ?: '—' }}</td>
                                <td>{{ $callLog->remark ?: '—' }}</td>
                                <td>
                                    @if($callLog->recording_url)
                                        <audio class="customer-history-recording" controls preload="none"><source src="{{ route('call-management.recording', $callLog) }}" type="audio/mpeg"></audio>
                                    @else
                                        <span>Processing / unavailable</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td class="customer-history-empty" colspan="10">No customer call history available.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <div class="customer-history-filter-overlay" id="historyFilterOverlay" aria-hidden="true">
        <aside class="customer-history-filter-drawer" role="dialog" aria-modal="true" aria-labelledby="historyFilterTitle">
            <div class="customer-history-filter-head">
                <div class="customer-history-filter-heading">
                    <span class="customer-history-filter-icon"><i class="material-icons">tune</i></span>
                    <div><strong id="historyFilterTitle">Advanced Filters</strong><small>Filter call history records</small></div>
                </div>
                <button class="customer-history-filter-close" id="closeHistoryFilters" type="button" aria-label="Close filters"><i class="material-icons">close</i></button>
            </div>
            <form class="customer-history-filters" method="GET" action="{{ route('customer-call-history.index') }}">
                <div class="customer-history-filter-body">
                    <div class="customer-history-filter-grid">
                        <div class="customer-history-filter is-wide">
                            <label for="historySearch">Search Calls</label>
                            <input id="historySearch" type="search" name="search" value="{{ request('search') }}" placeholder="Search firm, contact, mobile or notes">
                        </div>
                        @role('superadmin')
                            <div class="customer-history-filter is-wide">
                                <label for="historyAgent">Agent</label>
                                <select id="historyAgent" name="agent_id"><option value="">All agents</option>@foreach($agents as $agent)<option value="{{ $agent->id }}" @selected((string) request('agent_id') === (string) $agent->id)>{{ $agent->name }}</option>@endforeach</select>
                            </div>
                        @endrole
                        <div class="customer-history-filter">
                            <label for="historyCallStatus">Call Status</label>
                            <select id="historyCallStatus" name="call_status">
                                <option value="">All call statuses</option>
                                @foreach(['completed' => 'Completed', 'initiated' => 'Initiated', 'queued' => 'Queued', 'ringing' => 'Ringing', 'failed' => 'Failed', 'busy' => 'Busy', 'no-answer' => 'No Answer'] as $value => $label)<option value="{{ $value }}" @selected(request('call_status') === $value)>{{ $label }}</option>@endforeach
                            </select>
                        </div>
                        <div class="customer-history-filter">
                            <label for="historyAgentStatus">Agent Status</label>
                            <select id="historyAgentStatus" name="feedback_status_id">
                                <option value="">All agent statuses</option>
                                @foreach($feedbackStatuses as $feedbackStatus)<option value="{{ $feedbackStatus->id }}" @selected((string) request('feedback_status_id') === (string) $feedbackStatus->id)>{{ $feedbackStatus->display_name ?: $feedbackStatus->status_name }}</option>@endforeach
                            </select>
                        </div>
                        <div class="customer-history-filter">
                            <label for="historyFromDate">From Date</label>
                            <input id="historyFromDate" type="date" name="from_date" value="{{ request('from_date') }}">
                        </div>
                        <div class="customer-history-filter">
                            <label for="historyToDate">To Date</label>
                            <input id="historyToDate" type="date" name="to_date" value="{{ request('to_date') }}">
                        </div>
                    </div>
                </div>
                <div class="customer-history-filter-actions">
                    <a class="customer-history-reset" href="{{ route('customer-call-history.index') }}">Reset</a>
                    <button class="customer-history-apply" type="submit">Apply Filters</button>
                </div>
            </form>
        </aside>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const overlay = document.getElementById('historyFilterOverlay');

            function setHistoryFiltersOpen(isOpen) {
                overlay.classList.toggle('show', isOpen);
                overlay.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
                document.body.style.overflow = isOpen ? 'hidden' : '';
                if (isOpen) window.setTimeout(function () { document.getElementById('historySearch').focus(); }, 250);
            }

            document.getElementById('openHistoryFilters').addEventListener('click', function () { setHistoryFiltersOpen(true); });
            document.getElementById('closeHistoryFilters').addEventListener('click', function () { setHistoryFiltersOpen(false); });
            overlay.addEventListener('click', function (event) { if (event.target === overlay) setHistoryFiltersOpen(false); });
            document.addEventListener('keydown', function (event) { if (event.key === 'Escape' && overlay.classList.contains('show')) setHistoryFiltersOpen(false); });
        });
    </script>
</x-app-layout>
