<x-app-layout>
  <style>
    .lead-calling-chip { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; white-space: nowrap; }
    .lead-calling-chip.is-none { color: #94a3b8; background: rgba(148, 163, 184, .14); }
    .lead-calling-chip.is-partial { color: #f59e0b; background: rgba(245, 158, 11, .14); }
    .lead-calling-chip.is-all { color: #10b981; background: rgba(16, 185, 129, .13); }
    /* Inline lead filters: two compact rows
       row 1: search (2 cols) + lead type + calling + assigned to
       row 2: created between + state + district + city + reset */
    .lfx-filters { display: grid; grid-template-columns: repeat(5, minmax(0, 1fr)); gap: 10px 12px; align-items: end; margin: 0 0 14px; padding: 12px 14px; border: 1px solid var(--fk-list-border); border-radius: 14px; background: var(--fk-list-panel); }
    .lfx-field { min-width: 0; }
    .lfx-field label { display: block; margin: 0 0 4px; font-size: 10.5px; font-weight: 600; letter-spacing: .05em !important; text-transform: uppercase; color: var(--fk-list-muted); line-height: 1.2; }
    .lfx-search { position: relative; grid-column: span 2; }

    /* one look for every control */
    .lfx-field .form-control:not(.bootstrap-select),
    .lfx-field .bootstrap-select > .dropdown-toggle,
    .lfx-field .select2-container .select2-selection--single {
      display: flex !important; align-items: center; width: 100%; height: 38px !important; min-height: 38px !important;
      margin: 0 !important; padding: 0 32px 0 12px !important;
      border: 1px solid var(--fk-list-border-strong) !important; border-radius: 10px !important;
      background: var(--fk-list-control) !important; box-shadow: none !important;
      color: var(--fk-list-text) !important; font-size: 13px !important; line-height: 36px !important; text-transform: none !important;
    }
    .lfx-search .form-control:not(.bootstrap-select) { padding-left: 34px !important; }
    .lfx-search .material-icons { position: absolute; left: 10px; bottom: 10px; font-size: 18px; color: var(--fk-list-muted); z-index: 1; }

    /* bootstrap-select puts .form-control on its wrapper too: keep only the button styled */
    .lfx-field .bootstrap-select.form-control { display: block !important; width: 100% !important; height: auto !important; padding: 0 !important; margin: 0 !important; border: 0 !important; background: transparent !important; box-shadow: none !important; }
    .lfx-field .bootstrap-select > .dropdown-toggle .filter-option { position: static; display: flex; align-items: center; height: 100%; padding: 0 !important; }
    body.fk-shell .lfx-filters .lfx-field .filter-option-inner-inner,
    .lfx-field .bootstrap-select > .dropdown-toggle .filter-option-inner-inner { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; color: var(--fk-list-text) !important; font-size: 13px !important; line-height: 36px !important; text-transform: none !important; }
    .lfx-field .bootstrap-select > .dropdown-toggle::after { position: absolute; right: 12px; top: 50%; margin-top: -2px; }

    .lfx-field .select2-container { width: 100% !important; }
    .lfx-field .select2-container .select2-selection--single { position: relative; }
    body.fk-shell .lfx-filters .lfx-field .select2-container--default .select2-selection--single .select2-selection__rendered,
    .lfx-field .select2-selection__rendered { flex: 1 1 auto; min-width: 0; margin: 0 !important; padding: 0 !important; text-indent: 0 !important; line-height: 36px !important; color: var(--fk-list-text) !important; font-size: 13px !important; text-transform: none !important; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .lfx-field .select2-selection__arrow { position: absolute !important; top: 50% !important; right: 10px !important; height: 20px !important; margin: 0 !important; transform: translateY(-50%); }
    .lfx-field .bootstrap-select > .dropdown-toggle:focus, .lfx-field .bootstrap-select.show > .dropdown-toggle { border-color: var(--fk-list-accent) !important; outline: 0 !important; }
    .lfx-field form { display: block; margin: 0; }
    .lfx-field form .form-control { width: 100% !important; }

    .lfx-actions { display: flex; justify-content: flex-end; }
    .lfx-reset { display: inline-flex; align-items: center; justify-content: center; gap: 6px; width: 100%; height: 38px; padding: 0 14px; border: 1px solid var(--fk-list-border-strong); border-radius: 10px; background: var(--fk-list-control); color: var(--fk-list-text); font-size: 13px; font-weight: 600; cursor: pointer; }
    .lfx-reset:hover { border-color: var(--fk-list-accent); color: var(--fk-list-accent); }
    .lfx-reset .material-icons { font-size: 18px; }

    @media (max-width: 1199px) { .lfx-filters { grid-template-columns: repeat(3, minmax(0, 1fr)); } .lfx-search { grid-column: 1 / -1; } }
    @media (max-width: 575px) { .lfx-filters { grid-template-columns: minmax(0, 1fr) minmax(0, 1fr); } }
  </style>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header card-header-icon card-header-theme">
          <!-- <div class="card-icon">
             <i class="material-icons">perm_identity</i> 
          </div> -->
          <h4 class="card-title ">Leads<span class="brig ml-2"></span>
            <span class="">
              <div class="pream_entry">


                <div class="both_btn d-flex">
                <a href="{{route('leads.create')}}" class="btn btn-primary btn-sm btn-icon-split float-right" title="{!! trans('panel.global.add') !!} Lead">
                  <span class="icon text-white-50">
                    <i class="material-icons">add_circle</i>
                  </span>
                  <span class="text">Add Lead</span>
                </a>

                  <a href="{{route('leads-exportLeads')}}" class="btn exportbtn btn-primary btn-sm btn-icon-split float-right" id="export_button">
                    <span class="icon text-white-50">
                      <i class="material-icons">cloud_download</i>
                    </span>
                    <span class="text">Export</span>
                  </a>
                  @if(auth()->user()->can(['lead_template']))
                  <a href="{{ URL::to('lead-template') }}" class="btn btn-just-icon btn-theme" title="{!!  trans('panel.global.template') !!} Leads"><i class="material-icons">text_snippet</i></a>
                  @endif
                  @if(auth()->user()->can(['lead_upload']))
                  <form action="{{ URL::to('lead-upload') }}" class="form-horizontal" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="input-group">
                      <div class="fileinput fileinput-new text-center" data-provides="fileinput">
                        <span class="btn btn-just-icon btn-theme btn-file">
                          <span class="fileinput-new"><i class="material-icons">attach_file</i></span>
                          <span class="fileinput-exists">Change</span>
                          <input type="hidden">
                          <input type="file" title="Select File" name="import_file" required accept=".xls,.xlsx" />
                        </span>
                      </div>
                      <div class="input-group-append">
                        <button class="btn btn-just-icon btn-theme" title="{!!  trans('panel.global.upload') !!} Leads">
                          <i class="material-icons">cloud_upload</i>
                          <div class="ripple-container"></div>
                        </button>
                      </div>
                    </div>
                  </form>
                  @endif
                </div>
              </div>
            </span>
          </h4>

        </div>
        <div class="card-body">
          {{-- Filters shown on the page (not in a drawer) --}}
          <div class="lfx-filters">
            <div class="lfx-field lfx-search">
              <label for="search_lead">Search</label>
              <span class="material-icons">search</span>
              <input type="search" class="form-control" id="search_lead" placeholder="Company, contact, phone or Lead ID" autocomplete="off">
            </div>
            <div class="lfx-field">
              <label>Lead type</label>
              <select name="status" id="status" class="form-control selectpicker" title="Status">
                    <option value="">All</option>
                    @if($status->count() > 0)
                    @foreach($status as $stat)
                    <option value="{{$stat['id']}}"> {{$stat['display_name']}} </option>
                    @endforeach
                    @endif
                  </select>
            </div>
            <div class="lfx-field">
              <label>Calling</label>
              <select name="call_progress" id="call_progress" class="form-control selectpicker" title="Calling">
                    <option value="">All leads</option>
                    <option value="not_called">Not called</option>
                    <option value="partial">Partially called</option>
                    <option value="all_called">All contacts called</option>
                  </select>
            </div>
            <div class="lfx-field">
              <label>Assigned to</label>
              <select name="user_id_search" id="user_id_search" class="form-control select2">
                    <option value="">Assign To</option>
                    @if(@isset($users ))
                    @foreach($users as $user)
                    <option value="{!! $user['id'] !!}">{!! $user['name'] !!}</option>
                    @endforeach
                    @endif
                  </select>
            </div>
            <div class="lfx-field lfx-date">
              <label>Created between</label>
              {!! Form::open(['method' => 'POST', 'class' => 'form-inline', 'id' => 'frmFilter']) !!}
                  {!! Form::text('datetime', old('datetime'), ['class' => 'form-control','placeholder'=> __('MM/DD/YYYY - MM/DD/YYYY'), 'autocomplete' => 'off']) !!}
                  {!! Form::close() !!}
            </div>
            <div class="lfx-field">
              <label>State</label>
              <select class="form-control select2 " name="state_id" id="state_id" onchange="getDistrictList()">
                      <option value="">Select {!! trans('panel.global.state') !!}</option>
                      @if($states && count($states) > 0)
                      @foreach($states as $state)
                      <option value="{!! $state->id !!}" {{ old('state_id', isset($lead) && $lead->address ? $lead->address->state_id : '') == $state->id ? 'selected' : '' }}>{!! $state->state_name !!}</option>
                      @endforeach
                      @endif
                  </select>
            </div>
            <div class="lfx-field">
              <label>District</label>
              <select class="form-control select2 district" name="district_id" id="district_id" onchange="getCityList()">
                      @if(isset($lead) && $lead->address && $lead->address->district_id)
                      <option value="{!!  $lead->address->district_id !!}" selected>{!! $lead->address->districtname->district_name ?? '' !!}</option>
                      @else
                      <option value="">Select {!! trans('panel.global.district') !!}</option>
                      @endif
                    </select>
            </div>
            <div class="lfx-field">
              <label>City</label>
              <select class="form-control select2 city" name="city_id" id="city_id" onchange="getPincodeList()">
                      @if(isset($lead) && $lead->address && $lead->address->city_id)
                      <option value="{!!  $lead->address->city_id !!}" selected>{!! $lead->address->cityname->city_name??'' !!}</option>
                      @else
                      <option value="">Select {!! trans('panel.global.city') !!}</option>
                      @endif
                    </select>
            </div>
            <div class="lfx-actions">
              <button type="button" class="lfx-reset" id="lfxReset"><span class="material-icons">restart_alt</span>Reset</button>
            </div>
          </div>
          @if(count($errors) > 0)
          <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <i class="material-icons">close</i>
            </button>
            <span>
              @foreach($errors->all() as $error)
              <li>{{$error}}</li>
              @endforeach
            </span>
          </div>
          @endif
          @if(session()->has('message_success'))
          <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <i class="material-icons">close</i>
            </button>
            <span>
              {!!session()->get('message_success') !!}
            </span>
          </div>
          @endif
          <!--  -->
          <div class="well">
            <div class="dd">
            </div>

            <div class="sort_btn d-flex">
              <div class="ass_del d-none">
                <div class="btn-group">
                  <select name="user_id" id="user_id" class="form-control">
                    <option value="">Assign Lead</option>
                    @if(@isset($users ))
                    @foreach($users as $user)
                    <option value="{!! $user['id'] !!}">{!! $user['name'] !!}</option>
                    @endforeach
                    @endif
                  </select>
                </div>
                @can('lead_delete')
                <button type="button" class="btn mr-3 ml-3" id="del_btn"><i class="material-icons icon mr-1">delete</i>Delete</button>
                @endcan
                <button type="button" class="btn mr-3 ml-3" id="convert_btn"><i class="material-icons icon mr-1">transcribe</i>Convert to Customer</button>
              </div>
            </div>
          </div>
          <!--  -->
          <div class="alert fk-js-alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <i class="material-icons">close</i>
            </button>
            <span class="message"></span>
          </div>
          <div class="table-responsive">
            <table id="getLeads" class="table text-wrap">
              <thead class=" text-primary">
                <tr>
                  <th><input type="checkbox" id="checkAll"></th>
                  <th>Action</th>
                  <th>Date</th>
                  <th>Lead ID</th>
                  <th>Company Name</th>
                  <th>Contact</th>
                  <th>Phone</th>
                  <th>Email</th>
                  <th>City</th>
                  <th>Status</th>
                  <th>Calling</th>
                  <th>Assigned To</th>
                  <th>Note</th>
                  <th>Others</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <link href="{{ url('/').'/'.asset('vendor/bootstrap-daterange/daterangepicker.css') }}" rel="stylesheet">
  <script src="{{ url('/').'/'.asset('assets/js/jquery.custom.js') }}"></script>
  <!-- Load jQuery and moment.js -->
  <!-- <script src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script> -->

  <script src="{{ url('/').'/'.asset('vendor/bootstrap-daterange/daterangepicker.min.js') }}"></script>

  <script>
    jQuery(document).ready(function() {
      getLeads();

      jQuery('#frmFilter').submit(function() {
        getLeads();
        return false;
      });

      jQuery('#frmLeadsCreate').validate({
        rules: {
          company_name: {
            required: true
          },
          contact_name: {
            required: true
          },
        }

      });


      $(document).on('change', '#checkAll', function() {
        console.log('checkAll', this.checked);
        $('.lead-checkbox').prop('checked', this.checked);
        let selectedIds = [];

        $('.checkbox_cls:checked').each(function() {
          selectedIds.push($(this).val());
        });

        if (selectedIds.length > 0) {
          $('.ass_del').removeClass('d-none');
        } else {
          $('.ass_del').addClass('d-none');
        }
      });


      jQuery('#frmFilter [name="datetime"]').daterangepicker({
        autoUpdateInput: false,
        locale: {
          cancelLabel: 'Clear'
        }
      }).val("{{ old('datetime') }}");
      jQuery('#frmFilter [name="datetime"]').on('apply.daterangepicker', function(ev, picker) {
        jQuery(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        var this_val = jQuery(this).val();
        var assign_to = jQuery('#user_id_search').val();
        var export_button_url = "{{route('leads-exportLeads')}}?datetime=" + this_val + "&assign_to=" + assign_to;
        $('#export_button').attr('href', export_button_url);
        //console.log(jQuery(this).val());
        getLeads();
      }).on('cancel.daterangepicker', function(ev, picker) {
        jQuery(this).val('');
      });


    });



    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $('#company_name, #contact_name').on('keyup', function() {
      var company_name = $('#company_name').val();
      var contact_name = $('#contact_name').val();
      $.post("{{route('leads.searchExistsLead')}}", {
        company_name: company_name,
        contact_name: contact_name
      }, function(response) {
        $('#lead_exist_data').html(response);
      });
    });



    // Links from the Lead Dashboard can pre-select the "Calling" filter (?call_progress=partial).
    (function() {
      var params = new URLSearchParams(window.location.search);
      var preset = params.get('call_progress');
      if (preset && jQuery('#call_progress option[value="' + preset + '"]').length) {
        jQuery('#call_progress').val(preset);
        if (jQuery.fn.selectpicker) jQuery(function() { jQuery('#call_progress').selectpicker('refresh'); });
      }
      // ...and the "Assigned to" filter (?user_id=12).
      var presetUser = params.get('user_id');
      if (presetUser && jQuery('#user_id_search option[value="' + presetUser.replace(/[^0-9]/g, '') + '"]').length) {
        jQuery('#user_id_search').val(presetUser.replace(/[^0-9]/g, ''));
        jQuery(function() { jQuery('#user_id_search').trigger('change.select2'); });
      }
    })();

    function getLeads() {
      jQuery('#getLeads').dataTable().fnDestroy();
      jQuery('#getLeads tbody').empty();
      var datetime = jQuery('#frmFilter [name=datetime]').val();
      var search = jQuery('#search_lead').val();
      var assign_to = jQuery('#user_id_search').val();
      var state_id = jQuery('#state_id').val();
      var district_id = jQuery('#district_id').val();
      var city_id = jQuery('#city_id').val();
      var status = jQuery('#status').val();
      var table = jQuery('#getLeads').DataTable({
        processing: false,
        serverSide: true,
        searching: false,
        ajax: {
          url: '{{ route('leads.getLeads') }}',
          method: 'POST',
          data: {
            datetime: datetime,
            search: jQuery('#search_lead').val(),
            status: status,
            call_progress: jQuery('#call_progress').val(),
            assign_to: assign_to,
            state_id: state_id,
            district_id: district_id,
            city_id: city_id
          }
        },
        columns: [{
            data: 'checkbox',
            name: 'checkbox',
            orderable: false,
            searchable: false
          },
          {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false
          },
          {
            data: 'created_at',
            name: 'created_at'
          },
          {
            data: 'external_lead_id',
            name: 'external_lead_id',
            defaultContent: '-'
          },
          {
            data: 'company_name',
            name: 'company_name'
          },
          {
            data: 'contacts',
            name: 'contacts',
            orderable: false,
            searchable: false
          },
          {
            data: 'phone',
            name: 'phone',
            orderable: false,
            searchable: false
          },
          {
            data: 'email',
            name: 'email',
            orderable: false,
            searchable: false
          },
          {
            data: 'city',
            name: 'city',
            orderable: false,
            searchable: false
          },
          {
            data: 'status',
            name: 'status',
            searchable: false
          },
          {
            data: 'calling',
            name: 'calling',
            orderable: false,
            searchable: false
          },
          {
            data: 'assign_to',
            name: 'assign_to',
            searchable: false
          },
          {
            data: 'note',
            name: 'note',
            orderable: false,
            searchable: false
          },
          {
            data: 'others',
            name: 'others',
            orderable: false,
            searchable: false
          },
        ],
        order: [
          [2, 'desc']
        ],
        dom: 't<"bottom"lip>',

      });
      table.on('xhr', function(e, settings, json) {
          if (json && json.records_filtered_count !== undefined) {
              jQuery('.brig.ml-2').text(json.records_filtered_count + ' Leads');
          }
      });
    }

    $('#search_lead').on('keyup', function() {
      getLeads();
    });
    $('#user_id_search').on('change', function() {
      var this_val = jQuery('#frmFilter [name="datetime"]').val();
      var assign_to = jQuery(this).val();
      var export_button_url = "{{route('leads-exportLeads')}}?datetime=" + this_val + "&assign_to=" + assign_to;
      $('#export_button').attr('href', export_button_url);
      getLeads();
    });
    $('#state_id').on('change', function() {
      getLeads();
    })
    $('#district_id').on('change', function() {
      getLeads();
    })
    $('#city_id').on('change', function() {
      getLeads();
    })
    $('#status').on('change', function() {
      getLeads();
    });
    $('#call_progress').on('change', function() {
      getLeads();
    });

    $(document).on('click', '#lfxReset', function() {
      jQuery('#search_lead').val('');
      jQuery('#status, #call_progress').val('');
      if (jQuery.fn.selectpicker) jQuery('#status, #call_progress').selectpicker('refresh');
      jQuery('#user_id_search, #state_id, #district_id, #city_id').val('').trigger('change.select2');
      resetFilter();
    });

    function resetFilter() {
      jQuery('#frmFilter :input:not(:button, [type="hidden"])').val('');
      jQuery('#call_progress').val('').trigger('change.select2');
      if (jQuery.fn.selectpicker) jQuery('#call_progress').selectpicker('refresh');
      getLeads();
    }

    $(document).on('change', '.checkbox_cls', function() {
      let selectedIds = [];

      $('.checkbox_cls:checked').each(function() {
        selectedIds.push($(this).val());
      });

      if (selectedIds.length > 0) {
        $('.ass_del').removeClass('d-none');
      } else {
        $('.ass_del').addClass('d-none');
      }
    });

    $('#user_id').on('change', function() {
      var user_id = $(this).val();
      let selectedIds = [];
      $('.checkbox_cls:checked').each(function() {
        selectedIds.push($(this).val()); // or $(this).data('id')
      });
      if (selectedIds.length > 0) {
        Swal.fire({
          title: 'Are you sure?',
          text: "You won't assign this leads!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes'
        }).then((result) => {
          if (result.value) {
            $.ajax({
              url: "{{ route('leads.assignLead') }}",
              method: 'POST',
              data: {
                lead_id: selectedIds,
                user_id: user_id,
                _token: '{{ csrf_token() }}'
              },
              success: function(res) {
                if (res.status == 'success') {
                  getLeads();
                  $('.ass_del').addClass('d-none');
                  Swal.fire({
                    icon: 'success',
                    title: res.message
                  });
                } else {
                  Swal.fire({
                    icon: 'error',
                    title: res.message
                  });
                }
              }
            })
          }
        })
      } else {
        // None selected
        $('.ass_del').addClass('d-none');
        // $('#deleteButton').prop('disabled', true);
      }
    });

    $(document).on('click', '.lead-delete-btn', function() {
      var leadId = $(this).data('id');
      Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this lead!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.value) {
          $.ajax({
            url: "{{ route('leads.deleteLead') }}",
            method: 'POST',
            data: {
              lead_id: [leadId],
              _token: '{{ csrf_token() }}'
            },
            success: function(res) {
              Swal.fire({
                icon: res.status == 'success' ? 'success' : 'error',
                title: res.message
              });
              if (res.status == 'success') {
                getLeads();
              }
            },
            error: function() {
              Swal.fire({
                icon: 'error',
                title: 'Something went wrong.'
              });
            }
          });
        }
      });
    });

    $('#del_btn').on('click', function() {
      let selectedIds = [];
      $('.checkbox_cls:checked').each(function() {
        selectedIds.push($(this).val()); // or $(this).data('id')
      });
      if (selectedIds.length > 0) {
        Swal.fire({
          title: 'Are you sure?',
          text: "You won't delete this leads!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.value) {
            $.ajax({
              url: "{{ route('leads.deleteLead') }}",
              method: 'POST',
              data: {
                lead_id: selectedIds,
                _token: '{{ csrf_token() }}'
              },
              success: function(res) {
                if (res.status == 'success') {
                  getLeads();
                  $('.ass_del').addClass('d-none');
                  Swal.fire({
                    icon: 'success',
                    title: res.message
                  });
                } else {
                  Swal.fire({
                    icon: 'error',
                    title: res.message
                  });
                }
              }
            })
          }
        })
      } else {
        // None selected
        $('.ass_del').addClass('d-none');
        // $('#deleteButton').prop('disabled', true);
      }
    });
    $('#convert_btn').on('click', function() {
      let selectedIds = [];
      $('.checkbox_cls:checked').each(function() {
        selectedIds.push($(this).val()); // or $(this).data('id')
      });
      if (selectedIds.length > 0) {
        Swal.fire({
          title: 'Are you sure?',
          text: "You won't convert leads into Customer!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, convert it!'
        }).then((result) => {
          if (result.value) {
            $.ajax({
              url: "{{ route('leads.convert') }}",
              method: 'POST',
              data: {
                lead_id: selectedIds,
                _token: '{{ csrf_token() }}'
              },
              success: function(res) {
                if (res.status == 'success') {
                  getLeads();
                  $('.ass_del').addClass('d-none');
                  Swal.fire({
                    icon: 'success',
                    title: res.message
                  });
                } else {
                  Swal.fire({
                    icon: 'error',
                    title: res.message
                  });
                }
              }
            })
          }
        })
      } else {
        // None selected
        $('.ass_del').addClass('d-none');
        // $('#deleteButton').prop('disabled', true);
      }
    });
  </script>

</x-app-layout>
