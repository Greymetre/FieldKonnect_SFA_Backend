<x-app-layout>
  <style>
    .lcx-panel {
      border: 1px solid var(--fk-list-border);
      border-radius: 16px;
      background: var(--fk-list-panel);
      color: var(--fk-list-text);
    }

    .lcx-actions { display: flex; flex-wrap: wrap; gap: 8px; align-items: center; }

    .lcx-btn {
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
      text-decoration: none !important;
    }

    .lcx-btn:hover { border-color: var(--fk-list-accent); }
    .lcx-btn .material-icons { font-size: 18px; }
    .lcx-btn.is-primary { border-color: transparent; background: linear-gradient(135deg, var(--fk-list-accent), var(--fk-list-accent-2)); color: var(--fk-list-primary-text) !important; }
    .lcx-btn.is-danger { border-color: rgba(239, 68, 68, .5); color: #ef4444 !important; background: rgba(239, 68, 68, .08); }

    /* Filters */
    .lcx-filters {
      display: grid;
      grid-template-columns: minmax(0, 1.6fr) repeat(5, minmax(0, 1fr)) auto;
      gap: 12px;
      align-items: end;
      padding: 16px 18px;
      margin-bottom: 18px;
    }

    .lcx-field label {
      display: block;
      margin-bottom: 6px;
      font-size: 11px;
      font-weight: 600;
      letter-spacing: .08em;
      text-transform: uppercase;
      color: var(--fk-list-muted);
    }

    .lcx-field .form-control {
      height: 40px !important;
      border: 1px solid var(--fk-list-border-strong) !important;
      border-radius: 10px !important;
      background: var(--fk-list-control) !important;
      color: var(--fk-list-text) !important;
      padding: 0 12px;
      font-size: 13px;
    }

    .lcx-search { position: relative; }
    .lcx-search .material-icons { position: absolute; left: 10px; bottom: 10px; font-size: 18px; color: var(--fk-list-muted); }
    .lcx-search .form-control { padding-left: 34px; }
    .lcx-field .select2-container { width: 100% !important; }
    .lcx-field .select2-container .select2-selection--single { height: 40px !important; border: 1px solid var(--fk-list-border-strong) !important; border-radius: 10px !important; background: var(--fk-list-control) !important; }
    .lcx-field .select2-container .select2-selection__rendered { line-height: 38px !important; color: var(--fk-list-text) !important; }
    .lcx-field .select2-container .select2-selection__arrow { height: 38px !important; }

    /* Bulk bar */
    .lcx-bulk {
      display: none;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
      padding: 10px 16px;
      margin-bottom: 14px;
      border-color: var(--fk-list-accent);
    }

    .lcx-bulk.is-visible { display: flex; }
    .lcx-bulk strong { color: var(--fk-list-heading); }
    .lcx-bulk form { margin: 0; }

    /* Table */
    .lcx-table-card .card-body { padding: 0 !important; }
    #getLeadContacts { width: 100% !important; }
    #getLeadContacts th { padding: 14px 16px; white-space: nowrap; vertical-align: middle; }
    #getLeadContacts td { padding: 12px 16px; vertical-align: middle; }
    #getLeadContacts th:first-child, #getLeadContacts td:first-child { width: 44px; padding-right: 0; }

    .lcx-contact { display: flex; align-items: center; gap: 12px; min-width: 200px; }
    .lcx-avatar {
      flex: 0 0 36px;
      width: 36px;
      height: 36px;
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

    .lcx-name { display: block; font-weight: 600; color: var(--fk-list-heading) !important; }
    .lcx-name:hover { color: var(--fk-list-accent) !important; }
    .lcx-sub { display: block; font-size: 12px; color: var(--fk-list-muted); }
    .lcx-muted { color: var(--fk-list-dim); }

    .lcx-link { display: inline-flex; align-items: center; gap: 6px; color: var(--fk-list-text) !important; white-space: nowrap; }
    .lcx-link .material-icons { font-size: 15px; color: var(--fk-list-muted); }
    .lcx-link:hover, .lcx-link:hover .material-icons { color: var(--fk-list-accent) !important; }

    .lcx-lead {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      max-width: 280px;
      padding: 4px 10px;
      border-radius: 20px;
      background: var(--fk-list-count-bg);
      border: 1px solid var(--fk-list-count-border);
      color: var(--fk-list-accent) !important;
      font-size: 12px;
      font-weight: 600;
    }

    .lcx-lead span:last-child { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .lcx-lead .material-icons { font-size: 14px; }

    .lcx-check { width: 16px; height: 16px; cursor: pointer; accent-color: var(--fk-list-accent); }

    @media (max-width: 1399px) {
      .lcx-filters { grid-template-columns: repeat(3, minmax(0, 1fr)); }
      .lcx-filters .lcx-search { grid-column: 1 / -1; }
    }

    @media (max-width: 575px) {
      .lcx-filters { grid-template-columns: minmax(0, 1fr) minmax(0, 1fr); }
    }
  </style>

  <section class="fk-manual-listing lcx">
    <div class="fk-list-page-head">
      <div class="fk-list-heading-block">
        <div class="fk-list-breadcrumb">
          <span>Lead Management</span><span>&rsaquo;</span><span class="fk-current">Contacts</span>
        </div>
        <div class="fk-list-title-row">
          <h1 class="fk-list-title">Lead Contacts</h1>
          <span class="fk-list-count is-visible" id="lcxRecordCount">0 records</span>
        </div>
      </div>
      <div class="fk-list-actions lcx-actions">
        @if(auth()->user()->can(['lead_contacts_template']))
        <a href="{{ URL::to('lead-contacts-template') }}" class="lcx-btn" title="Download the import template">
          <span class="material-icons">text_snippet</span><span>Template</span>
        </a>
        @endif
        @if(auth()->user()->can(['lead_contacts_upload']))
        <form action="{{ URL::to('lead-contacts-upload') }}" method="post" enctype="multipart/form-data" id="lcxUploadForm" class="m-0">
          {{ csrf_field() }}
          <input type="file" name="import_file" id="lcxImportFile" accept=".xls,.xlsx" hidden>
          <button type="button" class="lcx-btn" id="lcxImportBtn" title="Import contacts from Excel">
            <span class="material-icons">cloud_upload</span><span>Import</span>
          </button>
        </form>
        @endif
        <a href="{{ route('contacts-exportContacts') }}" class="lcx-btn" title="Export contacts to Excel">
          <span class="material-icons">cloud_download</span><span>Export</span>
        </a>
        <button type="button" class="lcx-btn is-primary" data-toggle="modal" data-target="#addLeadModel">
          <span class="material-icons">add_circle</span><span>Add Contact</span>
        </button>
      </div>
    </div>

    @if(count($errors) > 0)
    <div class="alert alert-danger">
      @foreach($errors->all() as $error)
      <div>{{ $error }}</div>
      @endforeach
    </div>
    @endif
    @if(session()->has('message_success'))
    <div class="alert alert-success">{{ session()->get('message_success') }}</div>
    @endif

    {{-- Filters stay on the page (no drawer) --}}
    <div class="lcx-panel lcx-filters">
      <div class="lcx-field lcx-search">
        <label for="search_text">Search</label>
        <span class="material-icons">search</span>
        <input type="search" class="form-control contact-filter" id="search_text" placeholder="Name, phone or email" autocomplete="off">
      </div>
      <div class="lcx-field">
        <label for="filter_user_id">Assigned to</label>
        <select class="select2 contact-filter" id="filter_user_id">
          <option value="">All users</option>
          @foreach($users as $user)
          <option value="{{ $user->id }}">{{ $user->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="lcx-field">
        <label for="filter_company_name">Company</label>
        <input type="text" class="form-control contact-filter" id="filter_company_name" placeholder="Company name" autocomplete="off">
      </div>
      <div class="lcx-field">
        <label for="filter_title">Designation</label>
        <input type="text" class="form-control contact-filter" id="filter_title" placeholder="e.g. Director" autocomplete="off">
      </div>
      <div class="lcx-field">
        <label for="start_date">Added from</label>
        <input type="date" class="form-control contact-filter" id="start_date">
      </div>
      <div class="lcx-field">
        <label for="end_date">Added to</label>
        <input type="date" class="form-control contact-filter" id="end_date">
      </div>
      <div>
        <button type="button" class="lcx-btn" id="lcxReset"><span class="material-icons">restart_alt</span>Reset</button>
      </div>
    </div>

    <div class="lcx-panel lcx-bulk" id="checkbox_option">
      <span><strong id="lcxSelectedCount">0</strong> contact(s) selected</span>
      {!! Form::open(['method' => 'POST', 'route' => ['lead-contacts.checkboxAction'], 'id' => 'frmAction']) !!}
        <input type="hidden" name="lead_ids" id="lead_ids">
        <button type="submit" class="lcx-btn is-danger" onclick="return confirm('Delete the selected contacts?')">
          <span class="material-icons">delete</span>Delete selected
        </button>
      {!! Form::close() !!}
    </div>

    <div class="card fk-listing-card lcx-table-card" data-fk-listing-ready="1">
      <div class="card-body">
        <div class="fk-table-meta">
          <div class="fk-table-meta-icon"><span class="material-icons">contacts</span></div>
          <div class="fk-table-meta-copy">
            <h2>Contact Directory</h2>
            <p class="fk-table-meta-subline">Click a contact to open its lead</p>
          </div>
        </div>
        <div class="table-responsive">
          <table id="getLeadContacts" class="table fk-glass-table">
            <thead>
              <tr>
                <th><input type="checkbox" class="lcx-check" id="lcxSelectAll" title="Select all on this page"></th>
                <th>Contact</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Lead</th>
                <th>Added</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
  <!--  -->
  <div class="modal fade" id="addLeadModel" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">New Lead</h4>
        </div>
        
          <form method="POST" 
          action="{{ route('leads.store') }}" class="form-horizontal" id="frmLeadsCreate" enctype="multipart/form-data">
            @csrf
          <div class="modal-body">
          <div class="row">
            <div class="col-md-6 pr-1 pl-1">
              <div class="col-md-12 form-group">
                  <label for="company_name">Company Name <span style="color:red">*</span></label>
                  <input type="text"  name="company_name" id="company_name" value="{{ old('company_name','') }}"  class="form-control" placeholder="Company Name">
                  @if($errors->has('company_name'))
                  <p class="help-block">
                      <strong>{{ $errors->first('company_name') }}</strong>
                  </p>
                  @endif
              </div>
            </div>
            <div class="col-md-6 pr-1 pl-1">
              <div class="col-md-12 form-group">
                  <label for="contact_name">Contact Name <span style="color:red">*</span></label>
                  <input type="text"  name="contact_name" id="contact_name" value="{{ old('contact_name','') }}"  class="form-control" placeholder="Contact Name">
                  @if($errors->has('contact_name'))
                  <p class="help-block">
                      <strong>{{ $errors->first('contact_name') }}</strong>
                  </p>
                  @endif
              </div>     
            </div>
            <div class="col-md-12" id="lead_exist_data">
            </div>
          </div>
        
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger mr-2" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-default">Create Lead</button>
        </div>
      </div>
      </form>
    </div>
  </div>
  <script src="{{ url('/').'/'.asset('assets/js/jquery.custom.js') }}"></script>

<script>
  function lcxEsc(value) {
    return $('<div>').text(value == null ? '' : String(value)).html();
  }

  function lcxInitials(name) {
    var parts = String(name || '').trim().split(/\s+/).filter(Boolean);
    return lcxEsc(((parts[0] || '?').charAt(0) + (parts[1] ? parts[1].charAt(0) : '')).toUpperCase());
  }

  function lcxBlank(value) {
    var text = String(value == null ? '' : value).trim();
    return text === '' || text.toUpperCase() === 'N/A';
  }

  var lcxSelected = {};

  jQuery(document).ready(function() {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    var table = jQuery('#getLeadContacts').DataTable({
      processing: true,
      serverSide: true,
      searching: false,
      order: [],
      dom: 't<"bottom"lip>',
      ajax: {
        url: "{{ route('lead-contacts.getLeadContacts') }}",
        method: 'POST',
        data: function(d) {
          d.search_text = jQuery('#search_text').val();
          d.user_id = jQuery('#filter_user_id').val();
          d.company_name = jQuery('#filter_company_name').val();
          d.title = jQuery('#filter_title').val();
          d.start_date = jQuery('#start_date').val();
          d.end_date = jQuery('#end_date').val();
        },
        dataSrc: function(json) {
          $('#lcxRecordCount').text((json.recordsFiltered || 0) + ' records');
          return json.data;
        }
      },
      columns: [{
          data: 'id',
          orderable: false,
          render: function(id) {
            return '<input type="checkbox" class="lcx-check lcx-row-check" value="' + lcxEsc(id) + '"' + (lcxSelected[id] ? ' checked' : '') + '>';
          }
        },
        {
          data: 'name',
          name: 'name',
          render: function(name, type, row) {
            var label = lcxEsc(name || 'Unnamed');
            var link = row.lead_url ? '<a class="lcx-name" href="' + lcxEsc(row.lead_url) + '">' + label + '</a>' : '<span class="lcx-name">' + label + '</span>';
            var title = lcxBlank(row.title) ? '' : '<span class="lcx-sub">' + lcxEsc(row.title) + '</span>';
            return '<div class="lcx-contact"><span class="lcx-avatar">' + lcxInitials(name) + '</span><div>' + link + title + '</div></div>';
          }
        },
        {
          data: 'phone_number',
          name: 'phone_number',
          render: function(phone) {
            return lcxBlank(phone) ? '<span class="lcx-muted">—</span>'
              : '<a class="lcx-link" href="tel:' + lcxEsc(phone) + '"><span class="material-icons">call</span>' + lcxEsc(phone) + '</a>';
          }
        },
        {
          data: 'email',
          name: 'email',
          render: function(email) {
            return lcxBlank(email) ? '<span class="lcx-muted">—</span>'
              : '<a class="lcx-link" href="mailto:' + lcxEsc(email) + '"><span class="material-icons">mail</span>' + lcxEsc(email) + '</a>';
          }
        },
        {
          data: 'lead.company_name',
          name: 'lead.company_name',
          defaultContent: '',
          render: function(company, type, row) {
            if (lcxBlank(company)) return '<span class="lcx-muted">—</span>';
            var chip = '<span class="material-icons">business</span><span>' + lcxEsc(company) + '</span>';
            var html = row.lead_url ? '<a class="lcx-lead" href="' + lcxEsc(row.lead_url) + '" title="' + lcxEsc(company) + '">' + chip + '</a>'
              : '<span class="lcx-lead">' + chip + '</span>';
            return html + (row.assigned_to ? '<span class="lcx-sub" style="margin-top:4px">Assigned to ' + lcxEsc(row.assigned_to) + '</span>' : '');
          }
        },
        {
          data: 'created',
          name: 'created_at',
          defaultContent: '',
          render: function(date) { return '<span class="lcx-sub">' + lcxEsc(date || '—') + '</span>'; }
        }
      ],
      drawCallback: function() {
        var rows = $('#getLeadContacts .lcx-row-check');
        $('#lcxSelectAll').prop('checked', rows.length > 0 && rows.filter(':checked').length === rows.length);
      }
    });

    var contactFilterTimer;
    jQuery(document).on('input change', '.contact-filter', function() {
      clearTimeout(contactFilterTimer);
      contactFilterTimer = setTimeout(function() { table.draw(); }, 400);
    });

    $('#lcxReset').on('click', function() {
      $('.contact-filter').val('');
      $('#filter_user_id').trigger('change.select2');
      table.draw();
    });

    // Bulk selection (kept across pages until deleted)
    $(document).on('change', '.lcx-row-check', function() {
      if (this.checked) lcxSelected[this.value] = true;
      else delete lcxSelected[this.value];
      syncSelection();
    });

    $('#lcxSelectAll').on('change', function() {
      var checked = this.checked;
      $('#getLeadContacts .lcx-row-check').each(function() {
        this.checked = checked;
        if (checked) lcxSelected[this.value] = true;
        else delete lcxSelected[this.value];
      });
      syncSelection();
    });

    function syncSelection() {
      var ids = Object.keys(lcxSelected);
      $('#lead_ids').val(ids.join(','));
      $('#lcxSelectedCount').text(ids.length);
      $('#checkbox_option').toggleClass('is-visible', ids.length > 0);
    }

    // Import: pick a file, then upload it straight away
    $('#lcxImportBtn').on('click', function() { $('#lcxImportFile').trigger('click'); });
    $('#lcxImportFile').on('change', function() {
      if (this.files.length && confirm('Import contacts from "' + this.files[0].name + '"?')) {
        $('#lcxUploadForm').trigger('submit');
      }
      this.value = this.files.length ? this.value : '';
    });

    jQuery('#frmLeadsCreate').validate({
      rules: {
        company_name: { required: true },
        contact_name: { required: true },
      }
    });
  });

  $('#company_name, #contact_name').on('keyup', function() {
    $.post("{{ route('leads.searchExistsLead') }}", {
      company_name: $('#company_name').val(),
      contact_name: $('#contact_name').val()
    }, function(response) {
      $('#lead_exist_data').html(response);
    });
  });
</script>

</x-app-layout>
