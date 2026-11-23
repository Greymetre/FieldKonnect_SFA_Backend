<x-app-layout>
  <style>
    .lch { --lch-ok: #10b981; --lch-bad: #ef4444; --lch-warn: #f59e0b; --lch-in: #38bdf8; --lch-out: #a78bfa; }

    .lch-panel {
      border: 1px solid var(--fk-list-border);
      border-radius: 16px;
      background: var(--fk-list-panel);
      color: var(--fk-list-text);
    }

    /* Filters */
    .lch-filters {
      display: grid;
      grid-template-columns: repeat(6, minmax(0, 1fr)) auto;
      gap: 12px;
      align-items: end;
      padding: 16px 18px;
      margin-bottom: 18px;
    }

    .lch-field label {
      display: block;
      margin-bottom: 6px;
      font-size: 11px;
      font-weight: 600;
      letter-spacing: .08em;
      text-transform: uppercase;
      color: var(--fk-list-muted);
    }

    .lch-field .form-control,
    .lch-field .select2-container .select2-selection--single {
      height: 40px !important;
      border: 1px solid var(--fk-list-border-strong) !important;
      border-radius: 10px !important;
      background: var(--fk-list-control) !important;
      color: var(--fk-list-text) !important;
      padding: 0 12px;
      font-size: 13px;
    }

    .lch-field .select2-container { width: 100% !important; }
    .lch-field .select2-container .select2-selection__rendered { line-height: 38px !important; color: var(--fk-list-text) !important; padding-left: 0 !important; }
    .lch-field .select2-container .select2-selection__arrow { height: 38px !important; }

    .lch-search { position: relative; }
    .lch-search .material-icons { position: absolute; left: 10px; bottom: 10px; font-size: 18px; color: var(--fk-list-muted); }
    .lch-search .form-control { padding-left: 34px; }

    .lch-filter-actions { display: flex; gap: 8px; }

    .lch-btn {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      height: 40px;
      padding: 0 14px;
      border: 1px solid var(--fk-list-border-strong);
      border-radius: 10px;
      background: var(--fk-list-control);
      color: var(--fk-list-text) !important;
      font-size: 13px;
      font-weight: 500;
      white-space: nowrap;
      cursor: pointer;
    }

    .lch-btn:hover { border-color: var(--fk-list-accent); }
    .lch-btn .material-icons { font-size: 18px; }
    .lch-btn.is-primary { border-color: transparent; background: linear-gradient(135deg, var(--fk-list-accent), var(--fk-list-accent-2)); color: var(--fk-list-primary-text) !important; }

    /* KPI tiles */
    .lch-kpis {
      display: grid;
      grid-template-columns: repeat(3, minmax(0, 1fr)) minmax(0, 3fr);
      gap: 14px;
      margin-bottom: 18px;
    }

    .lch-kpi {
      position: relative;
      padding: 16px 18px;
      text-align: left;
      transition: border-color .15s ease, transform .15s ease;
    }

    .lch-kpi[data-status] { cursor: pointer; }
    .lch-kpi[data-status]:hover { border-color: var(--fk-list-accent); }
    .lch-kpi.is-active { border-color: var(--fk-list-accent); box-shadow: 0 0 0 1px var(--fk-list-accent) inset; }

    .lch-kpi-label {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 12px;
      font-weight: 500;
      color: var(--fk-list-muted);
    }

    .lch-kpi-icon {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 28px;
      height: 28px;
      border-radius: 8px;
      background: var(--fk-list-control);
      border: 1px solid var(--fk-list-border);
    }

    .lch-kpi-icon .material-icons { font-size: 16px; }
    .lch-kpi-value { margin-top: 10px; font-size: 26px; font-weight: 700; line-height: 1.1; color: var(--fk-list-heading); font-variant-numeric: tabular-nums; }
    .lch-kpi-hint { margin-top: 4px; font-size: 11px; color: var(--fk-list-dim); }

    .lch-tone-accent { color: var(--fk-list-accent); }
    .lch-tone-ok { color: var(--lch-ok); }
    .lch-tone-bad { color: var(--lch-bad); }
    .lch-tone-warn { color: var(--lch-warn); }

    /* Calls by agent */
    .lch-agents { display: flex; flex-direction: column; padding: 14px 18px 10px; min-height: 0; }
    .lch-agents-head { display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-bottom: 8px; }
    .lch-agents-head .lch-kpi-label { margin: 0; }
    .lch-legend { display: flex; gap: 12px; font-size: 11px; color: var(--fk-list-muted); }
    .lch-legend span { display: inline-flex; align-items: center; gap: 5px; }
    .lch-legend i { width: 8px; height: 8px; border-radius: 2px; display: inline-block; }
    .lch-agent-list { flex: 1 1 auto; max-height: 112px; overflow-y: auto; padding-right: 4px; }

    .lch-agent-row {
      display: grid;
      grid-template-columns: 26px minmax(110px, 180px) minmax(0, 1fr) auto;
      align-items: center;
      gap: 10px;
      width: 100%;
      padding: 5px 6px;
      border: 0;
      border-radius: 8px;
      background: transparent;
      color: inherit;
      text-align: left;
      cursor: pointer;
    }

    .lch-agent-row:hover, .lch-agent-row.is-active { background: var(--fk-list-control); }
    .lch-agent-row.is-active .lch-agent-name { color: var(--fk-list-accent); }
    .lch-agent-row .lch-avatar { flex-basis: 26px; width: 26px; height: 26px; font-size: 10px; }
    .lch-agent-name { font-size: 13px; font-weight: 600; color: var(--fk-list-heading); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .lch-agent-bar { display: flex; height: 8px; border-radius: 4px; background: var(--fk-list-border); overflow: hidden; }
    .lch-agent-bar span { display: block; height: 100%; }
    .lch-agent-bar .is-ok { background: var(--lch-ok); }
    .lch-agent-bar .is-bad { background: var(--lch-bad); opacity: .75; }
    .lch-agent-count { font-size: 12px; color: var(--fk-list-muted); white-space: nowrap; font-variant-numeric: tabular-nums; }
    .lch-agent-count b { font-size: 14px; color: var(--fk-list-heading); }
    .lch-agents-empty { padding: 18px 0; text-align: center; font-size: 13px; color: var(--fk-list-muted); }

    .lch-avatar {
      flex: 0 0 34px;
      width: 34px;
      height: 34px;
      border-radius: 50%;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 12px;
      font-weight: 600;
      color: var(--fk-list-accent);
      background: var(--fk-list-control);
      border: 1px solid var(--fk-list-border-strong);
    }

    /* Calls table */
    .lch-table-card .card-body { padding: 0 !important; }
    #getleadcalllogs { width: 100% !important; }
    #getleadcalllogs th { padding: 14px 16px; white-space: nowrap; vertical-align: middle; }
    #getleadcalllogs td { padding: 12px 16px; vertical-align: middle; }
    #getleadcalllogs tbody tr { cursor: pointer; }
    .lch-cell-main { font-weight: 600; color: var(--fk-list-heading); }
    .lch-cell-sub { display: block; font-size: 12px; color: var(--fk-list-muted); }
    .lch-cell-sub a { color: var(--fk-list-accent); }
    .lch-agent-cell { display: flex; align-items: center; gap: 10px; }

    .lch-chip {
      display: inline-flex;
      align-items: center;
      gap: 4px;
      padding: 3px 10px;
      border-radius: 20px;
      font-size: 11px;
      font-weight: 600;
      letter-spacing: .03em;
      white-space: nowrap;
    }

    .lch-chip .material-icons { font-size: 14px; }
    .lch-chip.is-in { color: var(--lch-in); background: rgba(56, 189, 248, .12); }
    .lch-chip.is-out { color: var(--lch-out); background: rgba(167, 139, 250, .14); }
    .lch-chip.is-ok { color: var(--lch-ok); background: rgba(16, 185, 129, .13); }
    .lch-chip.is-bad { color: var(--lch-bad); background: rgba(239, 68, 68, .13); }
    .lch-chip.is-live { color: var(--fk-list-accent); background: var(--fk-list-count-bg); }
    .lch-audio { width: 220px; height: 34px; }

    @media (max-width: 1399px) {
      .lch-filters { grid-template-columns: repeat(3, minmax(0, 1fr)); }
      .lch-filter-actions { grid-column: 1 / -1; justify-content: flex-end; }
      .lch-kpis { grid-template-columns: repeat(3, minmax(0, 1fr)); }
      .lch-agents { grid-column: 1 / -1; }
    }

    @media (max-width: 575px) {
      .lch-filters, .lch-kpis { grid-template-columns: minmax(0, 1fr) minmax(0, 1fr); }
      .lch-agent-row { grid-template-columns: 26px minmax(0, 1fr) auto; }
      .lch-agent-bar { display: none; }
    }
  </style>

  <section class="fk-manual-listing lch">
    <div class="fk-list-page-head">
      <div class="fk-list-heading-block">
        <div class="fk-list-breadcrumb">
          <span>Lead Management</span><span>&rsaquo;</span><span class="fk-current">Lead Call History</span>
        </div>
        <div class="fk-list-title-row">
          <h1 class="fk-list-title">Lead Call History</h1>
          <span class="fk-list-count is-visible" id="lchRecordCount">0 records</span>
        </div>
      </div>
      <div class="fk-list-actions">
        @if(auth()->user()->can(['call_log_download']))
        <button type="submit" form="lchFilterForm" class="lch-btn is-primary" title="Download call logs">
          <span class="material-icons">cloud_download</span><span>Download</span>
        </button>
        @endif
      </div>
    </div>

    {{-- Filters stay on the page (no drawer) --}}
    <form id="lchFilterForm" method="GET" action="{{ route('call-management.download') }}" class="lch-panel lch-filters">
      <div class="lch-field">
        <label for="executive_id">Agent</label>
        <select class="select2" name="user_id" id="executive_id">
          <option value="">All agents</option>
          @foreach($users as $user)
          <option value="{{ $user->id }}">{{ $user->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="lch-field">
        <label for="start_date">From</label>
        <input type="text" class="form-control datepicker" id="start_date" name="start_date" placeholder="Start date" autocomplete="off" readonly>
      </div>
      <div class="lch-field">
        <label for="end_date">To</label>
        <input type="text" class="form-control datepicker" id="end_date" name="end_date" placeholder="End date" autocomplete="off" readonly>
      </div>
      <div class="lch-field">
        <label for="direction">Direction</label>
        <select class="form-control" name="direction" id="direction">
          <option value="">All calls</option>
          <option value="outbound">Outbound</option>
          <option value="inbound">Inbound</option>
        </select>
      </div>
      <div class="lch-field">
        <label for="call_status">Status</label>
        <select class="form-control" name="call_status" id="call_status">
          <option value="">Any status</option>
          <option value="connected">Connected</option>
          <option value="not_connected">Not connected</option>
          <option value="no_remark">No remark saved</option>
        </select>
      </div>
      <div class="lch-field lch-search">
        <label for="lchSearch">Search</label>
        <span class="material-icons">search</span>
        <input type="search" class="form-control" id="lchSearch" placeholder="Agent, contact, company, number" autocomplete="off">
      </div>
      <div class="lch-filter-actions">
        <button type="button" class="lch-btn" id="lchReset"><span class="material-icons">restart_alt</span>Reset</button>
      </div>
    </form>

    <div class="lch-kpis">
      <div class="lch-panel lch-kpi" data-status="">
        <div class="lch-kpi-label"><span class="lch-kpi-icon lch-tone-accent"><span class="material-icons">call</span></span>Dialed calls</div>
        <div class="lch-kpi-value" id="dialed_calls">0</div>
        <div class="lch-kpi-hint">All calls in range</div>
      </div>
      <div class="lch-panel lch-kpi" data-status="connected">
        <div class="lch-kpi-label"><span class="lch-kpi-icon lch-tone-ok"><span class="material-icons">phone_in_talk</span></span>Connected</div>
        <div class="lch-kpi-value" id="connected_calls">0</div>
        <div class="lch-kpi-hint">Click to filter</div>
      </div>
      <div class="lch-panel lch-kpi" data-status="not_connected">
        <div class="lch-kpi-label"><span class="lch-kpi-icon lch-tone-bad"><span class="material-icons">phone_missed</span></span>Not connected</div>
        <div class="lch-kpi-value" id="no_response">0</div>
        <div class="lch-kpi-hint">Click to filter</div>
      </div>
      <div class="lch-panel lch-agents">
        <div class="lch-agents-head">
          <div class="lch-kpi-label"><span class="lch-kpi-icon lch-tone-accent"><span class="material-icons">support_agent</span></span>Calls by agent</div>
          <div class="lch-legend"><span><i style="background:var(--lch-ok)"></i>Connected</span><span><i style="background:var(--lch-bad)"></i>Not connected</span></div>
        </div>
        <div class="lch-agent-list" id="lchAgentList">
          <div class="lch-agents-empty">No calls in this range.</div>
        </div>
      </div>
    </div>

    <div class="card fk-listing-card lch-table-card" data-fk-listing-ready="1">
      <div class="card-body">
        <div class="fk-table-meta">
          <div class="fk-table-meta-icon"><span class="material-icons">history</span></div>
          <div class="fk-table-meta-copy">
            <h2>Call Log</h2>
            <p class="fk-table-meta-subline" id="lchTableMeta">Click a call to see details and transcript</p>
          </div>
        </div>
        <div class="alert lch-alert" style="display: none;">
          <span class="message"></span>
        </div>
        <div class="table-responsive">
          <table id="getleadcalllogs" class="table fk-glass-table">
            <thead>
              <tr>
                <th>Agent</th>
                <th>Contact / Lead</th>
                <th>Number</th>
                <th>Direction</th>
                <th>Date &amp; Time</th>
                <th>Duration</th>
                <th>Status</th>
                <th>Recording</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </section>

  <script type="text/javascript">
    var table;

    function lchEsc(value) {
      return $('<div>').text(value == null ? '' : String(value)).html();
    }

    function lchInitials(name) {
      var parts = String(name || '').trim().split(/\s+/).filter(Boolean);
      return lchEsc(((parts[0] || '?').charAt(0) + (parts[1] ? parts[1].charAt(0) : '')).toUpperCase());
    }

    function lchDirectionChip(direction) {
      return direction === 'inbound'
        ? '<span class="lch-chip is-in"><span class="material-icons">call_received</span>Inbound</span>'
        : '<span class="lch-chip is-out"><span class="material-icons">call_made</span>Outbound</span>';
    }

    $(function() {
      $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

      // Links from the Lead Dashboard: ?call_status=no_remark&start_date=…&end_date=…&user_id=…
      (function() {
        var params = new URLSearchParams(window.location.search);
        ['start_date', 'end_date'].forEach(function(key) {
          var value = params.get(key);
          if (value && /^\d{4}-\d{2}-\d{2}$/.test(value)) $('#' + key).val(value);
        });
        var status = params.get('call_status');
        if (status && $('#call_status option').filter(function() { return this.value === status; }).length) $('#call_status').val(status);
        var user = (params.get('user_id') || '').replace(/[^0-9]/g, '');
        if (user && $('#executive_id option[value="' + user + '"]').length) $('#executive_id').val(user).trigger('change.select2');
      })();

      table = $('#getleadcalllogs').DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        dom: 'rtip',
        ordering: false,
        retrieve: true,
        ajax: {
          url: "{{ route('call-management.index') }}",
          data: function(d) {
            d.user_id = $('#executive_id').val();
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
            d.direction = $('#direction').val();
            d.call_status = $('#call_status').val();
          },
          dataSrc: function(json) {
            if (json.summary) renderSummary(json.summary);
            $('#lchRecordCount').text((json.recordsFiltered || 0) + ' records');
            $('.lch-alert').hide();
            return json.data;
          },
          error: function(xhr) {
            console.error('Call history request failed', xhr.status, xhr.responseText);
            $('.lch-alert .message').text('Call history could not be loaded (HTTP ' + xhr.status + '). Please refresh the page.');
            $('.lch-alert').addClass('alert-danger').show();
          }
        },
        columns: [{
            data: 'agent',
            render: function(data) {
              return '<div class="lch-agent-cell"><span class="lch-avatar">' + lchInitials(data) + '</span><span class="lch-cell-main">' + lchEsc(data) + '</span></div>';
            }
          },
          {
            data: 'customer_name',
            render: function(data, type, row) {
              var company = row.lead_url
                ? '<a href="' + lchEsc(row.lead_url) + '">' + lchEsc(row.company_name) + '</a>'
                : lchEsc(row.company_name);
              return '<span class="lch-cell-main">' + lchEsc(data) + '</span><span class="lch-cell-sub">' + company + '</span>';
            }
          },
          { data: 'number', render: function(data) { return lchEsc(data); } },
          { data: 'direction', render: function(data) { return lchDirectionChip(data); } },
          {
            data: 'date',
            render: function(data, type, row) {
              return '<span class="lch-cell-main">' + lchEsc(data) + '</span><span class="lch-cell-sub">' + lchEsc(row.time) + '</span>';
            }
          },
          { data: 'duration', render: function(data) { return lchEsc(data); } },
          {
            data: 'connected',
            render: function(data, type, row) {
              if (row.live) return '<span class="lch-chip is-live"><span class="material-icons">graphic_eq</span>Live</span>';
              return data
                ? '<span class="lch-chip is-ok"><span class="material-icons">check_circle</span>Connected</span>'
                : '<span class="lch-chip is-bad"><span class="material-icons">cancel</span>Not connected</span>';
            }
          },
          {
            data: 'recording_url',
            render: function(data) {
              return data
                ? '<audio class="lch-audio" controls preload="none" src="' + lchEsc(data) + '"></audio>'
                : '<span class="lch-cell-sub">Not available</span>';
            }
          }
        ]
      });

      $('#getleadcalllogs tbody').on('click', 'tr', function(event) {
        if ($(event.target).closest('audio, a, button, input, select, label').length) return;
        var row = table.row(this).data();
        if (row && row.detail_url) window.location.href = row.detail_url;
      });

      $('#executive_id, #direction, #end_date').on('change', function() { table.draw(); });
      $('#start_date').on('change', function() {
        $('#end_date').datepicker('option', 'minDate', $('#start_date').datepicker('getDate'));
        table.draw();
      });
      $('#call_status').on('change', function() {
        syncKpiState();
        table.draw();
      });

      var searchTimer;
      $('#lchSearch').on('input', function() {
        var value = this.value;
        clearTimeout(searchTimer);
        searchTimer = setTimeout(function() { table.search(value).draw(); }, 350);
      });

      $('.lch-kpi[data-status]').on('click', function() {
        $('#call_status').val($(this).data('status'));
        syncKpiState();
        table.draw();
      });

      $('#lchReset').on('click', function() {
        $('#executive_id').val('').trigger('change.select2');
        $('#start_date, #end_date, #lchSearch').val('');
        $('#direction, #call_status').val('');
        syncKpiState();
        table.search('').draw();
      });

      syncKpiState();
    });

    function syncKpiState() {
      var status = $('#call_status').val() || '';
      $('.lch-kpi[data-status]').each(function() {
        $(this).toggleClass('is-active', String($(this).data('status')) === status);
      });
    }

    function renderSummary(summary) {
      $('#dialed_calls').text(summary.total);
      $('#connected_calls').text(summary.connected);
      $('#no_response').text(summary.no_response);
      renderAgents(summary.agents || []);
    }

    // Agent-wise calls: bar length is relative to the busiest agent.
    function renderAgents(agents) {
      if (!agents.length) {
        $('#lchAgentList').html('<div class="lch-agents-empty">No calls in this range.</div>');
        return;
      }
      var max = Math.max.apply(null, agents.map(function(agent) { return agent.total; })) || 1;
      var selected = String($('#executive_id').val() || '');
      $('#lchAgentList').html(agents.map(function(agent) {
        var notConnected = agent.total - agent.connected;
        return '<button type="button" class="lch-agent-row' + (String(agent.user_id) === selected ? ' is-active' : '') + '" data-user-id="' + lchEsc(agent.user_id) + '" title="Show only ' + lchEsc(agent.name) + '\'s calls">'
          + '<span class="lch-avatar">' + lchInitials(agent.name) + '</span>'
          + '<span class="lch-agent-name">' + lchEsc(agent.name) + '</span>'
          + '<span class="lch-agent-bar">'
          + '<span class="is-ok" style="width:' + (agent.connected * 100 / max) + '%"></span>'
          + '<span class="is-bad" style="width:' + (notConnected * 100 / max) + '%"></span>'
          + '</span>'
          + '<span class="lch-agent-count"><b>' + agent.total + '</b> calls · ' + agent.connected + ' conn.</span>'
          + '</button>';
      }).join(''));
    }

    // Click an agent to filter the table by that agent; click again to clear.
    $(document).on('click', '.lch-agent-row', function() {
      var userId = String($(this).data('user-id') || '');
      var current = String($('#executive_id').val() || '');
      $('#executive_id').val(userId === current ? '' : userId).trigger('change');
    });
  </script>
</x-app-layout>
