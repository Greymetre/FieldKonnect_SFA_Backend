<x-app-layout>
  <style>
    .ltk-panel { border: 1px solid var(--fk-list-border); border-radius: 16px; background: var(--fk-list-panel); color: var(--fk-list-text); }
    .ltk *, .ltk-modal * { letter-spacing: normal; }

    .ltk-btn { display: inline-flex; align-items: center; gap: 6px; height: 40px; padding: 0 14px; border: 1px solid var(--fk-list-border-strong); border-radius: 10px; background: var(--fk-list-control); color: var(--fk-list-text) !important; font-size: 13px; font-weight: 500; white-space: nowrap; cursor: pointer; text-decoration: none !important; }
    .ltk-btn:hover { border-color: var(--fk-list-accent); }
    .ltk-btn .material-icons { font-size: 18px; }
    .ltk-btn.is-danger { border-color: rgba(239, 68, 68, .5); color: #ef4444 !important; background: rgba(239, 68, 68, .08); }

    /* Status tabs */
    .ltk-tabs { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 14px; }
    .ltk-tab { display: inline-flex; align-items: center; gap: 8px; height: 38px; padding: 0 14px; border: 1px solid var(--fk-list-border); border-radius: 20px; background: var(--fk-list-panel); color: var(--fk-list-muted); font-size: 13px; font-weight: 600; cursor: pointer; }
    .ltk-tab .ltk-dot { width: 8px; height: 8px; border-radius: 50%; background: currentColor; }
    .ltk-tab b { min-width: 22px; padding: 0 7px; border-radius: 10px; background: var(--fk-list-control); color: var(--fk-list-heading); font-size: 12px; line-height: 20px; text-align: center; }
    .ltk-tab.is-active { border-color: var(--fk-list-accent); color: var(--fk-list-heading); box-shadow: 0 0 0 1px var(--fk-list-accent) inset; }
    .ltk-tab[data-status="pending"] .ltk-dot { color: #f59e0b; }
    .ltk-tab[data-status="open"] .ltk-dot { color: #38bdf8; }
    .ltk-tab[data-status="in_progress"] .ltk-dot { color: #a78bfa; }
    .ltk-tab[data-status="completed"] .ltk-dot { color: #10b981; }
    .ltk-tab[data-status="overdue"] .ltk-dot { color: #ef4444; }

    /* Filters */
    .ltk-filters { display: grid; grid-template-columns: minmax(0, 2fr) repeat(2, minmax(0, 1fr)) auto; gap: 12px; align-items: end; padding: 14px 18px; margin-bottom: 16px; }
    .ltk-field label { display: block; margin-bottom: 6px; font-size: 11px; font-weight: 600; letter-spacing: .08em; text-transform: uppercase; color: var(--fk-list-muted); }
    .ltk-field .form-control { height: 40px !important; border: 1px solid var(--fk-list-border-strong) !important; border-radius: 10px !important; background: var(--fk-list-control) !important; color: var(--fk-list-text) !important; padding: 0 12px; font-size: 13px; }
    .ltk-search { position: relative; }
    .ltk-search .material-icons { position: absolute; left: 10px; bottom: 10px; font-size: 18px; color: var(--fk-list-muted); }
    .ltk-search .form-control { padding-left: 34px; }

    .ltk-bulk { display: none; align-items: center; justify-content: space-between; gap: 12px; padding: 10px 16px; margin-bottom: 14px; border-color: var(--fk-list-accent); }
    .ltk-bulk.is-visible { display: flex; }
    .ltk-bulk strong { color: var(--fk-list-heading); }
    .ltk-bulk form { margin: 0; }

    /* Table */
    .ltk-table-card .card-body { padding: 0 !important; }
    #getLeadTasks { width: 100% !important; }
    #getLeadTasks th { padding: 14px 16px; white-space: nowrap; }
    #getLeadTasks td { padding: 14px 16px; vertical-align: middle; }
    #getLeadTasks th:first-child, #getLeadTasks td:first-child { width: 44px; padding-right: 0; }
    .ltk-check { width: 16px; height: 16px; cursor: pointer; accent-color: var(--fk-list-accent); }

    .ltk-task-title { display: block; font-size: 13.5px; font-weight: 600; color: var(--fk-list-heading); overflow-wrap: anywhere; }
    .ltk-task-lead { display: inline-flex; align-items: center; gap: 4px; margin-top: 3px; font-size: 12px; color: var(--fk-list-accent) !important; }
    .ltk-task-lead .material-icons { font-size: 14px; }
    .ltk-task-lead small { color: var(--fk-list-muted); }
    .ltk-remark { display: block; margin-top: 4px; font-size: 12px; color: var(--fk-list-muted); }

    .ltk-chip { display: inline-flex; align-items: center; gap: 5px; padding: 4px 11px; border-radius: 20px; font-size: 12px; font-weight: 600; white-space: nowrap; border: 1px solid transparent; }
    .ltk-chip .material-icons { font-size: 14px; }
    .ltk-chip.is-high { color: #ef4444; background: rgba(239, 68, 68, .13); }
    .ltk-chip.is-medium { color: #f59e0b; background: rgba(245, 158, 11, .14); }
    .ltk-chip.is-low { color: #38bdf8; background: rgba(56, 189, 248, .13); }
    .ltk-chip.is-pending { color: #f59e0b; background: rgba(245, 158, 11, .14); }
    .ltk-chip.is-open { color: #38bdf8; background: rgba(56, 189, 248, .13); }
    .ltk-chip.is-in_progress { color: #a78bfa; background: rgba(167, 139, 250, .15); }
    .ltk-chip.is-completed { color: #10b981; background: rgba(16, 185, 129, .13); }
    button.ltk-chip { cursor: pointer; }
    button.ltk-chip:hover { border-color: currentColor; }

    .ltk-due { display: block; font-size: 13px; font-weight: 600; color: var(--fk-list-heading); white-space: nowrap; }
    .ltk-due-sub { display: block; font-size: 12px; color: var(--fk-list-muted); white-space: nowrap; }
    .ltk-due-sub.is-late { color: #ef4444; font-weight: 600; }

    .ltk-user { display: flex; align-items: center; gap: 8px; white-space: nowrap; }
    .ltk-avatar { flex: 0 0 30px; width: 30px; height: 30px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700; color: var(--fk-list-accent); background: var(--fk-list-control); border: 1px solid var(--fk-list-border-strong); }
    .ltk-user span:last-child { font-size: 13px; color: var(--fk-list-heading); }

    .ltk-steps { display: flex; gap: 6px; }
    .ltk-step { display: inline-flex; align-items: center; justify-content: center; width: 26px; height: 26px; border-radius: 50%; background: var(--fk-list-control); color: var(--fk-list-dim); border: 1px solid var(--fk-list-border); }
    .ltk-step .material-icons { font-size: 14px; }
    .ltk-step.is-done { color: #10b981; border-color: rgba(16, 185, 129, .5); background: rgba(16, 185, 129, .1); }

    /* Status popup */
    .ltk-modal .modal-dialog { max-width: 460px; }
    .ltk-modal .ltk-card { opacity: 1 !important; overflow: hidden; border-radius: 18px !important; }
    .ltk-modal .ltk-card, .ltk-modal .ltk-card * { letter-spacing: normal !important; text-transform: none !important; }
    .ltk-modal-head { display: flex; align-items: center; gap: 12px; padding: 16px 20px; border-bottom: 1px solid var(--fk-list-border); }
    .ltk-modal-head h5 { flex: 1 1 auto; margin: 0; font-size: 16px; font-weight: 700; color: var(--fk-list-heading); text-align: left; }
    .ltk-modal-head p { margin: 2px 0 0; font-size: 12px; color: var(--fk-list-muted); }
    .ltk-modal-close { width: 34px; height: 34px; border: 0; border-radius: 10px; background: var(--fk-list-control); color: var(--fk-list-muted); cursor: pointer; }
    .ltk-modal-body { padding: 16px 20px 6px; }
    .ltk-modal-label { display: block; margin-bottom: 8px; font-size: 12.5px; font-weight: 700; color: var(--fk-list-heading); }
    .ltk-options { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 16px; }
    .ltk-option { margin: 0; cursor: pointer; }
    .ltk-option input { position: absolute; opacity: 0; pointer-events: none; }
    .ltk-option span { display: flex; align-items: center; gap: 8px; padding: 10px 12px; border: 1px solid var(--fk-list-border); border-radius: 12px; background: var(--fk-list-control); font-size: 13px; font-weight: 600; color: var(--fk-list-heading); }
    .ltk-option span i { width: 10px; height: 10px; border-radius: 50%; }
    .ltk-option input:checked + span { border-color: var(--fk-list-accent); box-shadow: 0 0 0 1px var(--fk-list-accent) inset; }
    .ltk-modal textarea { min-height: 90px; border: 1px solid var(--fk-list-border-strong) !important; border-radius: 12px !important; background: var(--fk-list-control) !important; color: var(--fk-list-text) !important; padding: 10px 12px; }
    .ltk-modal-error { margin: 6px 0 0; min-height: 18px; font-size: 12px; color: #ef4444; }
    .ltk-modal-foot { display: flex; justify-content: flex-end; gap: 10px; padding: 14px 20px; border-top: 1px solid var(--fk-list-border); }
    .ltk-modal-foot .ltk-btn.is-primary { border-color: transparent; background: linear-gradient(135deg, var(--fk-list-accent), var(--fk-list-accent-2)); color: var(--fk-list-primary-text) !important; font-weight: 600; }

    @media (max-width: 991px) {
      .ltk-filters { grid-template-columns: minmax(0, 1fr) minmax(0, 1fr); }
      .ltk-filters .ltk-search { grid-column: 1 / -1; }
    }
  </style>

  <section class="fk-manual-listing ltk">
    <div class="fk-list-page-head">
      <div class="fk-list-heading-block">
        <div class="fk-list-breadcrumb">
          <span>Lead Management</span><span>&rsaquo;</span><span class="fk-current">Tasks</span>
        </div>
        <div class="fk-list-title-row">
          <h1 class="fk-list-title">Lead Tasks</h1>
          <span class="fk-list-count is-visible" id="ltkRecordCount">0 records</span>
        </div>
      </div>
      <div class="fk-list-actions">
        <a href="{{ route('tasks-exportTasks') }}" class="ltk-btn" id="ltkExport" title="Export tasks to Excel">
          <span class="material-icons">cloud_download</span><span>Export</span>
        </a>
      </div>
    </div>

    @if(count($errors) > 0)
    <div class="alert alert-danger">@foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div>
    @endif
    @if(session()->has('message_success'))
    <div class="alert alert-success">{{ session()->get('message_success') }}</div>
    @endif

    <div class="ltk-tabs" id="ltkTabs">
      <button type="button" class="ltk-tab is-active" data-status="">All <b data-count="all">0</b></button>
      <button type="button" class="ltk-tab" data-status="pending"><span class="ltk-dot"></span>Pending <b data-count="pending">0</b></button>
      <button type="button" class="ltk-tab" data-status="open"><span class="ltk-dot"></span>Open <b data-count="open">0</b></button>
      <button type="button" class="ltk-tab" data-status="in_progress"><span class="ltk-dot"></span>In Progress <b data-count="in_progress">0</b></button>
      <button type="button" class="ltk-tab" data-status="completed"><span class="ltk-dot"></span>Completed <b data-count="completed">0</b></button>
      <button type="button" class="ltk-tab" data-status="overdue"><span class="ltk-dot"></span>Overdue <b data-count="overdue">0</b></button>
    </div>

    <div class="ltk-panel ltk-filters">
      <div class="ltk-field ltk-search">
        <label for="ltkSearch">Search</label>
        <span class="material-icons">search</span>
        <input type="search" class="form-control" id="ltkSearch" placeholder="Task, company, priority or assignee" autocomplete="off">
      </div>
      <div class="ltk-field">
        <label for="start_date">Due from</label>
        <input type="date" class="form-control" id="start_date">
      </div>
      <div class="ltk-field">
        <label for="end_date">Due to</label>
        <input type="date" class="form-control" id="end_date">
      </div>
      <div>
        <button type="button" class="ltk-btn" id="ltkReset"><span class="material-icons">restart_alt</span>Reset</button>
      </div>
    </div>

    <div class="ltk-panel ltk-bulk" id="checkbox_option">
      <span><strong id="ltkSelectedCount">0</strong> task(s) selected</span>
      {!! Form::open(['method' => 'POST', 'route' => ['lead-tasks.checkboxAction'], 'id' => 'frmAction']) !!}
        <input type="hidden" name="lead_ids" id="lead_ids">
        <button type="submit" class="ltk-btn is-danger" onclick="return confirm('Delete the selected tasks?')"><span class="material-icons">delete</span>Delete selected</button>
      {!! Form::close() !!}
    </div>

    <div class="card fk-listing-card ltk-table-card" data-fk-listing-ready="1">
      <div class="card-body">
        <div class="fk-table-meta">
          <div class="fk-table-meta-icon"><span class="material-icons">task_alt</span></div>
          <div class="fk-table-meta-copy">
            <h2>Task Directory</h2>
            <p class="fk-table-meta-subline">Click a status to update it</p>
          </div>
        </div>
        <div class="table-responsive">
          <table id="getLeadTasks" class="table fk-glass-table">
            <thead>
              <tr>
                <th><input type="checkbox" class="ltk-check" id="ltkSelectAll" title="Select all on this page"></th>
                <th>Task</th>
                <th>Priority</th>
                <th>Status</th>
                <th>Due</th>
                <th>Assigned to</th>
                <th>Progress</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </section>

  <div class="modal fade ltk-modal" id="ltkStatusModal" tabindex="-1" role="dialog" aria-labelledby="ltkStatusTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content ltk-card">
        <div class="ltk-modal-head">
          <div style="flex:1 1 auto;min-width:0">
            <h5 id="ltkStatusTitle">Update task status</h5>
            <p id="ltkStatusTask"></p>
          </div>
          <button type="button" class="ltk-modal-close" data-dismiss="modal" aria-label="Close"><i class="material-icons">close</i></button>
        </div>
        <div class="ltk-modal-body">
          <span class="ltk-modal-label">New status</span>
          <div class="ltk-options">
            <label class="ltk-option"><input type="radio" name="ltk_status" value="pending"><span><i style="background:#f59e0b"></i>Pending</span></label>
            <label class="ltk-option"><input type="radio" name="ltk_status" value="open"><span><i style="background:#38bdf8"></i>Open</span></label>
            <label class="ltk-option"><input type="radio" name="ltk_status" value="in_progress"><span><i style="background:#a78bfa"></i>In Progress</span></label>
            <label class="ltk-option"><input type="radio" name="ltk_status" value="completed"><span><i style="background:#10b981"></i>Completed</span></label>
          </div>
          <label class="ltk-modal-label" for="ltkRemark">Remark <span style="color:#ef4444">*</span></label>
          <textarea class="form-control" id="ltkRemark" maxlength="255" placeholder="What was done / why the status changed"></textarea>
          <p class="ltk-modal-error" id="ltkStatusError"></p>
        </div>
        <div class="ltk-modal-foot">
          <button type="button" class="ltk-btn" data-dismiss="modal">Cancel</button>
          <button type="button" class="ltk-btn is-primary" id="ltkStatusSave"><span class="material-icons">check</span>Update status</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    function ltkEsc(value) {
      return $('<div>').text(value == null ? '' : String(value)).html();
    }

    function ltkInitials(name) {
      var parts = String(name || '').trim().split(/\s+/).filter(Boolean);
      return ltkEsc(((parts[0] || '?').charAt(0) + (parts[1] ? parts[1].charAt(0) : '')).toUpperCase());
    }

    var ltkStatus = '';
    var ltkSelected = {};
    var ltkRows = {};
    var ltkEditing = null;

    $(function() {
      $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

      var table = $('#getLeadTasks').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        ordering: false,
        dom: 't<"bottom"lip>',
        ajax: {
          url: "{{ route('lead-tasks.getLeadTasks') }}",
          method: 'POST',
          data: function(d) {
            d.search = $('#ltkSearch').val();
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
            d.status = ltkStatus;
          },
          dataSrc: function(json) {
            $('#ltkRecordCount').text((json.recordsFiltered || 0) + ' records');
            var counts = json.status_counts || {};
            $('#ltkTabs [data-count]').each(function() { $(this).text(counts[$(this).data('count')] || 0); });
            ltkRows = {};
            (json.data || []).forEach(function(row) { ltkRows[row.id] = row; });
            return json.data;
          }
        },
        columns: [{
            data: 'id',
            render: function(id) {
              return '<input type="checkbox" class="ltk-check ltk-row-check" value="' + ltkEsc(id) + '"' + (ltkSelected[id] ? ' checked' : '') + '>';
            }
          },
          {
            data: 'description',
            render: function(text, type, row) {
              var lead = row.company
                ? '<a class="ltk-task-lead" href="' + ltkEsc(row.lead_url) + '"><span class="material-icons">business</span>' + ltkEsc(row.company) + (row.lead_code ? ' <small>· ' + ltkEsc(row.lead_code) + '</small>' : '') + '</a>'
                : '';
              var remark = row.remark ? '<span class="ltk-remark">"' + ltkEsc(row.remark) + '"</span>' : '';
              return '<span class="ltk-task-title">' + ltkEsc(text || 'Untitled task') + '</span>' + lead + remark;
            }
          },
          {
            data: 'priority',
            render: function(priority) {
              if (!priority) return '<span class="ltk-due-sub">—</span>';
              var p = String(priority).toLowerCase();
              return '<span class="ltk-chip is-' + ltkEsc(p) + '"><span class="material-icons">flag</span>' + ltkEsc(p.charAt(0).toUpperCase() + p.slice(1)) + '</span>';
            }
          },
          {
            data: 'status',
            render: function(status, type, row) {
              return '<button type="button" class="ltk-chip is-' + ltkEsc(status) + ' ltk-change" data-id="' + ltkEsc(row.id) + '" title="Change status">'
                + ltkEsc(row.status_label) + '<span class="material-icons">expand_more</span></button>';
            }
          },
          {
            data: 'due_date',
            render: function(date, type, row) {
              if (!date) return '<span class="ltk-due-sub">No due date</span>';
              var sub = row.overdue_days > 0
                ? '<span class="ltk-due-sub is-late">Overdue by ' + row.overdue_days + ' day' + (row.overdue_days === 1 ? '' : 's') + '</span>'
                : '<span class="ltk-due-sub">' + ltkEsc(row.due_time) + '</span>';
              return '<span class="ltk-due">' + ltkEsc(date) + '</span>' + sub;
            }
          },
          {
            data: 'assignee',
            render: function(name) {
              return name ? '<span class="ltk-user"><span class="ltk-avatar">' + ltkInitials(name) + '</span><span>' + ltkEsc(name) + '</span></span>' : '<span class="ltk-due-sub">Unassigned</span>';
            }
          },
          {
            data: 'seen',
            render: function(seen, type, row) {
              var step = function(done, icon, label) {
                return '<span class="ltk-step' + (done ? ' is-done' : '') + '" title="' + ltkEsc(label) + '"><span class="material-icons">' + icon + '</span></span>';
              };
              return '<span class="ltk-steps">'
                + step(row.seen, 'visibility', row.seen ? 'Seen ' + row.seen : 'Not seen yet')
                + step(row.opened, 'play_arrow', row.opened ? 'Opened ' + row.opened : 'Not opened yet')
                + step(row.completed, 'check', row.completed ? 'Completed ' + row.completed : 'Not completed')
                + '</span>';
            }
          }
        ],
        drawCallback: function() {
          var rows = $('#getLeadTasks .ltk-row-check');
          $('#ltkSelectAll').prop('checked', rows.length > 0 && rows.filter(':checked').length === rows.length);
        }
      });

      // Status tabs
      $('#ltkTabs').on('click', '.ltk-tab', function() {
        $('.ltk-tab').removeClass('is-active');
        $(this).addClass('is-active');
        ltkStatus = String($(this).data('status') || '');
        syncExport();
        table.draw();
      });

      var searchTimer;
      $('#ltkSearch').on('input', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(function() { table.draw(); }, 350);
      });
      $('#start_date, #end_date').on('change', function() { syncExport(); table.draw(); });
      $('#ltkReset').on('click', function() {
        $('#ltkSearch, #start_date, #end_date').val('');
        $('.ltk-tab').removeClass('is-active').first().addClass('is-active');
        ltkStatus = '';
        syncExport();
        table.draw();
      });

      // Export uses the current status and dates.
      function syncExport() {
        var params = $.param({ status: ltkStatus, start_date: $('#start_date').val(), end_date: $('#end_date').val() });
        $('#ltkExport').attr('href', "{{ route('tasks-exportTasks') }}?" + params);
      }

      // Bulk selection
      $(document).on('change', '.ltk-row-check', function() {
        if (this.checked) ltkSelected[this.value] = true;
        else delete ltkSelected[this.value];
        syncSelection();
      });
      $('#ltkSelectAll').on('change', function() {
        var checked = this.checked;
        $('#getLeadTasks .ltk-row-check').each(function() {
          this.checked = checked;
          if (checked) ltkSelected[this.value] = true;
          else delete ltkSelected[this.value];
        });
        syncSelection();
      });
      function syncSelection() {
        var ids = Object.keys(ltkSelected);
        $('#lead_ids').val(ids.join(','));
        $('#ltkSelectedCount').text(ids.length);
        $('#checkbox_option').toggleClass('is-visible', ids.length > 0);
      }

      // Status popup (moved to <body> so the layout cannot hide it)
      $(document).on('click', '.ltk-change', function() {
        ltkEditing = ltkRows[$(this).data('id')];
        if (!ltkEditing) return;
        $('#ltkStatusTask').text(ltkEditing.description + (ltkEditing.company ? ' · ' + ltkEditing.company : ''));
        $('input[name="ltk_status"][value="' + ltkEditing.status + '"]').prop('checked', true);
        $('#ltkRemark').val('');
        $('#ltkStatusError').text('');
        var $modal = $('#ltkStatusModal');
        if (!$modal.parent().is('body')) $modal.appendTo('body');
        $modal.modal('show');
      });

      $('#ltkStatusSave').on('click', function() {
        var status = $('input[name="ltk_status"]:checked').val();
        var remark = $.trim($('#ltkRemark').val());
        if (!status) { $('#ltkStatusError').text('Choose a status.'); return; }
        if (!remark) { $('#ltkStatusError').text('Please add a remark.'); return; }
        var $btn = $(this).prop('disabled', true);
        $.post("{{ route('lead-tasks.change_status') }}", { task_id: ltkEditing.id, status: status, remark: remark })
          .done(function(response) {
            if (response.status === 'success') {
              $('#ltkStatusModal').modal('hide');
              table.draw(false);
            } else {
              $('#ltkStatusError').text('Could not update the task.');
            }
          })
          .fail(function(xhr) {
            var res = xhr.responseJSON || {};
            $('#ltkStatusError').text(res.message || 'Could not update the task.');
          })
          .always(function() { $btn.prop('disabled', false); });
      });

      syncExport();
    });
  </script>
</x-app-layout>
