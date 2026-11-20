<x-app-layout>
  <style>
    .dap-panel {
      border: 1px solid var(--fk-list-border);
      border-radius: 16px;
      background: var(--fk-list-panel);
      color: var(--fk-list-text);
    }

    .dap-toolbar {
      display: flex;
      flex-wrap: wrap;
      align-items: end;
      justify-content: space-between;
      gap: 14px;
      padding: 14px 18px;
      margin-bottom: 18px;
    }

    .dap-tabs { display: flex; gap: 6px; padding: 4px; border-radius: 12px; background: var(--fk-list-control); border: 1px solid var(--fk-list-border); }

    .dap-tab {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      height: 38px;
      padding: 0 16px;
      border: 0;
      border-radius: 9px;
      background: transparent;
      color: var(--fk-list-muted);
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
    }

    .dap-tab .material-icons { font-size: 18px; }
    .dap-tab.is-active { background: linear-gradient(135deg, var(--fk-list-accent), var(--fk-list-accent-2)); color: var(--fk-list-primary-text); }
    .dap-count { min-width: 20px; padding: 0 6px; border-radius: 10px; font-size: 11px; line-height: 18px; text-align: center; background: rgba(239, 68, 68, .9); color: #fff; }
    .dap-count.is-zero { display: none; }

    .dap-filters { display: flex; flex-wrap: wrap; gap: 12px; align-items: end; }

    .dap-field label {
      display: block;
      margin-bottom: 6px;
      font-size: 11px;
      font-weight: 600;
      letter-spacing: .08em;
      text-transform: uppercase;
      color: var(--fk-list-muted);
    }

    .dap-field .form-control {
      height: 40px !important;
      min-width: 170px;
      border: 1px solid var(--fk-list-border-strong) !important;
      border-radius: 10px !important;
      background: var(--fk-list-control) !important;
      color: var(--fk-list-text) !important;
      padding: 0 12px;
      font-size: 13px;
    }

    .dap-search { position: relative; }
    .dap-search .material-icons { position: absolute; left: 10px; bottom: 10px; font-size: 18px; color: var(--fk-list-muted); }
    .dap-search .form-control { min-width: 260px; padding-left: 34px; }

    .dap-table-card .card-body { padding: 0 !important; }
    #draftTable { width: 100% !important; }
    #draftTable th { padding: 14px 16px; white-space: nowrap; }
    #draftTable td { padding: 14px 16px; vertical-align: top; }

    .dap-lead { font-weight: 600; color: var(--fk-list-heading) !important; }
    .dap-lead:hover { color: var(--fk-list-accent) !important; }
    .dap-sub { display: block; margin-top: 2px; font-size: 12px; color: var(--fk-list-muted); }

    .dap-draft {
      max-width: 560px;
      max-height: 96px;
      overflow: hidden;
      white-space: pre-wrap;
      overflow-wrap: anywhere;
      font-size: 13px;
      line-height: 1.5;
      color: var(--fk-list-text);
    }

    .dap-draft.is-open { max-height: none; }
    .dap-more { margin-top: 4px; padding: 0; border: 0; background: none; color: var(--fk-list-accent); font-size: 12px; font-weight: 600; cursor: pointer; }

    .dap-chip { display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; white-space: nowrap; }
    .dap-chip .material-icons { font-size: 14px; }
    .dap-chip.is-ok { color: #10b981; background: rgba(16, 185, 129, .13); }
    .dap-chip.is-wait { color: #f59e0b; background: rgba(245, 158, 11, .14); }

    .dap-actions { display: flex; gap: 8px; white-space: nowrap; }

    .dap-btn {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      height: 34px;
      padding: 0 12px;
      border: 1px solid var(--fk-list-border-strong);
      border-radius: 9px;
      background: var(--fk-list-control);
      color: var(--fk-list-text) !important;
      font-size: 12px;
      font-weight: 600;
      cursor: pointer;
    }

    .dap-btn .material-icons { font-size: 16px; }
    .dap-btn:hover { border-color: var(--fk-list-accent); }
    .dap-btn.is-approve { border-color: transparent; background: #10b981; color: #fff !important; }

    .dap-modal { border: 1px solid var(--fk-list-border-strong) !important; border-radius: 16px !important; background: var(--fk-list-panel, #0b1736) !important; color: var(--fk-list-text) !important; }
    .dap-modal .modal-header, .dap-modal .modal-footer { border-color: var(--fk-list-border) !important; }
    .dap-modal .modal-title { font-weight: 600; color: var(--fk-list-heading); }
    .dap-modal .modal-header small { color: var(--fk-list-muted); }
    .dap-modal .close { color: var(--fk-list-text); text-shadow: none; }
    .dap-modal textarea { min-height: 260px; border: 1px solid var(--fk-list-border-strong) !important; border-radius: 10px !important; background: var(--fk-list-control) !important; color: var(--fk-list-text) !important; padding: 10px 12px; }
    .dap-error { margin: 8px 0 0; min-height: 18px; font-size: 12px; color: #ef4444; }

    @media (max-width: 767px) {
      .dap-search .form-control, .dap-field .form-control { min-width: 0; width: 100%; }
      .dap-filters, .dap-field { width: 100%; }
    }
  </style>

  <section class="fk-manual-listing dap">
    <div class="fk-list-page-head">
      <div class="fk-list-heading-block">
        <div class="fk-list-breadcrumb">
          <span>Lead Management</span><span>&rsaquo;</span><span class="fk-current">Draft Approval</span>
        </div>
        <div class="fk-list-title-row">
          <h1 class="fk-list-title">Draft Approval</h1>
          <span class="fk-list-count is-visible" id="dapRecordCount">0 records</span>
        </div>
      </div>
    </div>

    <div class="dap-panel dap-toolbar">
      <div class="dap-tabs" role="tablist">
        <button type="button" class="dap-tab is-active" data-type="email">
          <span class="material-icons">mail</span>Email Drafts
          <span class="dap-count {{ $pendingEmail ? '' : 'is-zero' }}" title="Pending approval">{{ $pendingEmail }}</span>
        </button>
        <button type="button" class="dap-tab" data-type="linkedin">
          <span class="material-icons">work</span>LinkedIn Drafts
          <span class="dap-count {{ $pendingLinkedin ? '' : 'is-zero' }}" title="Pending approval">{{ $pendingLinkedin }}</span>
        </button>
      </div>
      <div class="dap-filters">
        <div class="dap-field">
          <label for="dapStatus">Status</label>
          <select class="form-control" id="dapStatus">
            <option value="pending" selected>Pending approval</option>
            <option value="approved">Approved</option>
            <option value="all">All</option>
          </select>
        </div>
        <div class="dap-field dap-search">
          <label for="dapSearch">Search</label>
          <span class="material-icons">search</span>
          <input type="search" class="form-control" id="dapSearch" placeholder="Lead, Lead ID, agent or text" autocomplete="off">
        </div>
      </div>
    </div>

    <div class="card fk-listing-card dap-table-card" data-fk-listing-ready="1">
      <div class="card-body">
        <div class="fk-table-meta">
          <div class="fk-table-meta-icon"><span class="material-icons">fact_check</span></div>
          <div class="fk-table-meta-copy">
            <h2 id="dapTableTitle">Email Drafts</h2>
            <p class="fk-table-meta-subline">Review, edit and approve. Approved drafts are locked for agents.</p>
          </div>
        </div>
        <div class="table-responsive">
          <table id="draftTable" class="table fk-glass-table">
            <thead>
              <tr>
                <th>Lead</th>
                <th>Draft</th>
                <th>Written by</th>
                <th>Sent to</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </section>

  <div class="modal fade" id="dapModal" tabindex="-1" role="dialog" aria-labelledby="dapModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content dap-modal">
        <div class="modal-header">
          <div>
            <h5 class="modal-title" id="dapModalTitle">Review draft</h5>
            <small id="dapModalSub"></small>
          </div>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
          <textarea class="form-control" id="dapText" maxlength="10000"></textarea>
          <p class="dap-error" id="dapError"></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-outline-primary" id="dapSave">Save changes</button>
          <button type="button" class="btn btn-primary" id="dapSaveApprove">Save &amp; approve</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    function dapEsc(value) {
      return $('<div>').text(value == null ? '' : String(value)).html();
    }

    var dapType = 'email';
    var dapRows = {};
    var dapCurrent = null;

    $(function() {
      $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

      var table = $('#draftTable').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        ordering: false,
        dom: 't<"bottom"lip>',
        ajax: {
          url: "{{ route('draft-approvals.data') }}",
          method: 'POST',
          data: function(d) {
            d.type = dapType;
            d.status = $('#dapStatus').val();
            d.search_text = $('#dapSearch').val();
          },
          dataSrc: function(json) {
            $('#dapRecordCount').text((json.recordsFiltered || 0) + ' records');
            dapRows = {};
            (json.data || []).forEach(function(row) { dapRows[row.id] = row; });
            return json.data;
          }
        },
        columns: [{
            data: 'company',
            render: function(company, type, row) {
              var name = row.lead_url ? '<a class="dap-lead" href="' + dapEsc(row.lead_url) + '">' + dapEsc(company) + '</a>' : '<span class="dap-lead">' + dapEsc(company) + '</span>';
              return name + (row.lead_code ? '<span class="dap-sub">' + dapEsc(row.lead_code) + '</span>' : '');
            }
          },
          {
            data: 'note',
            render: function(note, type, row) {
              var long = String(note || '').length > 220 || String(note || '').split('\n').length > 4;
              return '<div class="dap-draft" id="dap-draft-' + row.id + '">' + dapEsc(note) + '</div>'
                + (long ? '<button type="button" class="dap-more" data-id="' + row.id + '">Show more</button>' : '');
            }
          },
          {
            data: 'created_by',
            render: function(name, type, row) {
              return '<span class="dap-lead">' + dapEsc(name) + '</span><span class="dap-sub">' + dapEsc(row.created_at) + '</span>';
            }
          },
          {
            data: 'approver',
            render: function(name) {
              return name ? '<span class="dap-lead">' + dapEsc(name) + '</span>' : '<span class="dap-sub">Not assigned</span>';
            }
          },
          {
            data: 'approved',
            render: function(approved, type, row) {
              return approved
                ? '<span class="dap-chip is-ok"><span class="material-icons">verified</span>Approved</span><span class="dap-sub">by ' + dapEsc(row.approved_by || '-') + '<br>' + dapEsc(row.approved_at || '') + '</span>'
                : '<span class="dap-chip is-wait"><span class="material-icons">schedule</span>Pending</span>';
            }
          },
          {
            data: 'id',
            render: function(id, type, row) {
              return '<div class="dap-actions">'
                + '<button type="button" class="dap-btn dap-edit" data-id="' + id + '"><span class="material-icons">edit</span>Edit</button>'
                + (row.approved ? '' : '<button type="button" class="dap-btn is-approve dap-approve" data-id="' + id + '"><span class="material-icons">check</span>Approve</button>')
                + '</div>';
            }
          }
        ]
      });

      $('.dap-tab').on('click', function() {
        $('.dap-tab').removeClass('is-active');
        $(this).addClass('is-active');
        dapType = $(this).data('type');
        $('#dapTableTitle').text(dapType === 'email' ? 'Email Drafts' : 'LinkedIn Drafts');
        table.draw();
      });

      $('#dapStatus').on('change', function() { table.draw(); });
      var searchTimer;
      $('#dapSearch').on('input', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(function() { table.draw(); }, 350);
      });

      $(document).on('click', '.dap-more', function() {
        var $draft = $('#dap-draft-' + $(this).data('id')).toggleClass('is-open');
        $(this).text($draft.hasClass('is-open') ? 'Show less' : 'Show more');
      });

      $(document).on('click', '.dap-edit', function() {
        dapCurrent = dapRows[$(this).data('id')];
        if (!dapCurrent) return;
        $('#dapModalTitle').text(dapCurrent.approved ? 'Edit approved draft' : 'Review draft');
        $('#dapModalSub').text(dapCurrent.company + ' · by ' + dapCurrent.created_by);
        $('#dapText').val(dapCurrent.note);
        $('#dapError').text('');
        $('#dapSaveApprove').toggle(!dapCurrent.approved);
        $('#dapModal').modal('show');
      });

      $(document).on('click', '.dap-approve', function() {
        var row = dapRows[$(this).data('id')];
        if (!row || !confirm('Approve this draft? The agent will not be able to edit it after approval.')) return;
        send(row.approve_url, 'POST', {});
      });

      $('#dapSave').on('click', function() {
        if (!dapCurrent) return;
        send(dapCurrent.update_url, 'PUT', { note: $('#dapText').val() }, true);
      });

      $('#dapSaveApprove').on('click', function() {
        if (!dapCurrent) return;
        send(dapCurrent.approve_url, 'POST', { note: $('#dapText').val() }, true);
      });

      function send(url, method, data, fromModal) {
        if (fromModal && !$.trim($('#dapText').val())) {
          $('#dapError').text('The draft cannot be empty.');
          return;
        }
        $.ajax({
          url: url,
          method: 'POST',
          data: $.extend({ _method: method }, data),
          success: function(res) {
            $('#dapModal').modal('hide');
            table.draw(false);
            refreshCounts();
          },
          error: function(xhr) {
            var res = xhr.responseJSON || {};
            var message = res.message || 'Could not save the draft.';
            if (res.errors) message = Object.values(res.errors)[0][0];
            if (fromModal) $('#dapError').text(message);
            else alert(message);
          }
        });
      }

      // Pending counts on the tabs, after an approve.
      function refreshCounts() {
        ['email', 'linkedin'].forEach(function(type) {
          $.post("{{ route('draft-approvals.data') }}", { type: type, status: 'pending', length: 1 }, function(res) {
            var count = res.recordsTotal || 0;
            $('.dap-tab[data-type="' + type + '"] .dap-count').text(count).toggleClass('is-zero', count === 0);
          });
        });
      }
    });
  </script>
</x-app-layout>
