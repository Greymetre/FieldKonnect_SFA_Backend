<x-app-layout>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header card-header-icon card-header-theme">
          <div class="card-icon">
            <i class="material-icons">perm_identity</i>
          </div>
          <h4 class="card-title ">{{isset($lead) ? 'UPDATE' : 'ADD'}} NEW LEAD
            <span class="pull-right">
              <div class="btn-group">
                @if(auth()->user()->can(['customer_access']))
                <a href="{{ url('leads') }}" class="btn btn-just-icon btn-theme" title="Leads"><i class="material-icons">arrow_circle_left</i></a>
                @endif
              </div>
            </span>
          </h4>
        </div>
        <div class="card-body">
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
          <form method="POST" action="{{ isset($lead) ? route('leads.update', $lead->id) : route('leads.store') }}" id="frmLeadsCreate" enctype="multipart/form-data" class="w-100">
            @method(isset($lead) ? 'PUT' : 'POST')
            @csrf

            <div class="modal-content lead-modal">
              {{-- Header --}}
              <!-- <div class="modal-header border-0 pb-0">
            <h5 class="modal-title font-weight-bold text-uppercase mb-0">ADD NEW LEAD</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div> -->

              {{-- Body --}}
              <div class="modal-body pt-3">
                <div class="form-row">
                  {{-- Lead Type --}}
                  <div class="form-group col-6 mb-2">
                    <label for="status">Lead Type <span style="color:red">*</span></label>
                    <select name="status" id="status" class="custom-select" required>
                      <option value="" disabled selected>Lead Type</option>
                      @foreach($status as $opt)
                      <option value="{{ $opt->id }}" {{ old('status', isset($lead) ? $lead->status : 0)==$opt->id ? 'selected' : '' }}>{{ $opt->display_name }}</option>
                      @endforeach
                    </select>
                    @error('status') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                  </div>

                  {{-- Firm Name --}}
                  <div class="form-group col-6 mb-2">
                    <label for="company_name">Firm Name <span style="color:red">*</span></label>
                    <input type="text" name="company_name" id="company_name" value="{{ old('company_name', isset($lead) ? $lead->company_name : '') }}"
                      class="form-control form-control-lg" placeholder="Firm Name" required>
                    @error('company_name') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                  </div>
                </div>
                <div class="form-row">
                  {{-- Customer Name --}}
                  <div class="form-group col-6 mb-2">
                    <label for="contact_name">Customer Name <span style="color:red">*</span></label>
                    <input type="text" name="contact_name" id="contact_name" value="{{ old('contact_name', isset($lead) ? $lead->contacts?->first()->name : '') }}"
                      class="form-control form-control-lg" placeholder="Customer Name" required>
                    @error('contact_name') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                  </div>

                  {{-- Mobile --}}
                  <div class="form-group col-6 mb-2">
                    <label for="phone_number">Mobile Number</label>
                    <input type="tel" name="phone_number" id="phone_number" value="{{ old('phone_number', isset($lead) ? $lead->contacts?->first()->phone_number : '') }}"
                      class="form-control form-control-lg" placeholder="Mobile Number" maxlength="15">
                    @error('phone_number') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                  </div>
                </div>
                <div class="form-row">
                  {{-- Designation --}}
                  <div class="form-group col-6 mb-2">
                    <label for="designation">Designation</label>
                    <input type="text" name="designation" id="designation" value="{{ old('designation', isset($lead) ? $lead->contacts?->first()?->title : '') }}"
                      class="form-control form-control-lg" placeholder="Designation">
                    @error('designation') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                  </div>

                  {{-- Alternate Number --}}
                  <div class="form-group col-6 mb-2">
                    <label for="alternate_number">Alternate Number</label>
                    <input type="tel" name="alternate_number" id="alternate_number" value="{{ old('alternate_number', isset($lead) ? $lead->alternate_number : '') }}"
                      class="form-control form-control-lg" placeholder="Alternate Number" maxlength="15">
                    @error('alternate_number') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                  </div>
                </div>
                <div class="form-row">
                  {{-- Email --}}
                  <div class="form-group col-6 mb-2">
                    <label for="email">Email Id</label>
                    <input type="email" name="email" id="email" value="{{ old('email', isset($lead) ? $lead->contacts?->first()->email : '') }}"
                      class="form-control form-control-lg" placeholder="Email Id">
                    @error('email') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                  </div>

                  {{-- Address --}}
                  <div class="form-group col-6 mb-2">
                    <label for="address">Address</label>
                    <input type="text" name="address" id="address" value="{{ old('address', isset($lead) ? $lead->address?->address1 : '') }}"
                      class="form-control form-control-lg" placeholder="Address">
                    @error('address') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                  </div>
                </div>

                <div class="form-row">
                  {{-- Place --}}
                  <div class="form-group col-6 mb-2">
                    <label for="place">Place</label>
                    <input type="text" name="place" id="place" value="{{ old('place', isset($lead) ? $lead->address?->address2 : '') }}"
                      class="form-control form-control-lg" placeholder="Place">
                    @error('place') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                  </div>

                  {{-- Revenue --}}
                  <div class="form-group col-6 mb-2">
                    <label for="revenue_rs_cr">Revenue (Rs Cr)</label>
                    <input type="text" name="revenue_rs_cr" id="revenue_rs_cr" value="{{ old('revenue_rs_cr', isset($lead) ? $lead->revenue_rs_cr : '') }}"
                      class="form-control form-control-lg" placeholder="Revenue (Rs Cr)">
                    @error('revenue_rs_cr') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                  </div>
                </div>

                {{-- Pin / City --}}
                <div class="form-row">
                  <div class="form-group col-3 mb-2">
                    <label for="state_id">State</label>
                    <select class="form-control select2 " name="state_id" id="state_id" onchange="getDistrictList()" style="width: 100%;">
                      <option value="">Select {!! trans('panel.global.state') !!}</option>
                      @if($states && count($states) > 0)
                      @foreach($states as $state)
                      <option value="{!! $state->id !!}" {{ old('state_id', isset($lead) && $lead->address ? $lead->address->state_id : '') == $state->id ? 'selected' : '' }}>{!! $state->state_name !!}</option>
                      @endforeach
                      @endif
                    </select>
                    @error('state_alt') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                  </div>
                  <div class="form-group col-3 mb-2">
                    <label for="district_id">District</label>
                    <select class="form-control select2 district" name="district_id" id="district_id" onchange="getCityList()" style="width: 100%;">
                      @if(isset($lead) && $lead->address && $lead->address->district_id)
                      <option value="{!!  $lead->address->district_id !!}" selected>{!! $lead->address->districtname->district_name ?? '' !!}</option>
                      @else
                      <option value="">Select {!! trans('panel.global.district') !!}</option>
                      @endif
                    </select>
                    @error('state') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                  </div>
                  <div class="form-group col-3 mb-2">
                    <label for="city_id">City</label>
                    <select class="form-control select2 city" name="city_id" id="city_id" onchange="getPincodeList()" style="width: 100%;">
                      @if(isset($lead) && $lead->address && $lead->address->city_id)
                      <option value="{!!  $lead->address->city_id !!}" selected>{!! $lead->address->cityname->city_name??'' !!}</option>
                      @else
                      <option value="">Select {!! trans('panel.global.city') !!}</option>
                      @endif
                    </select>
                    @error('city') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                  </div>
                  <div class="form-group col-3 mb-2">
                  <label for="pincode_id">Pincode</label>
                  <select class="form-control pincode select2" name="pincode_id" id="pincode_id" onchange="getAddressData()" style="width: 100%;">
                    @if(isset($lead) && $lead->address && $lead->address->pincode_id)
                    <option value="{!!  $lead->address->pincode_id !!}" selected>{!! $lead->address->pincodename->pincode !!}</option>
                    @endif
                    <option value="">Select {!! trans('panel.global.pincode') !!}</option>
                  </select>
                  @error('pin_code') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                </div>
                </div>

                {{-- Other / Lead Source --}}
                <div class="form-row">
                  <div class="form-group col-6 mb-2">
                    <label for="other">Other</label>
                    <input type="text" name="other" id="other" value="{{ old('other', isset($lead) && isset($lead->others) ? (count(json_decode($lead->others, true)) > 0 ? array_values(json_decode($lead->others, true))[0] : '') : '') }}"
                      class="form-control form-control-lg" placeholder="Other">
                    @error('other') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                  </div>
                  <div class="form-group col-6 mb-2">
                    <label for="lead_source">Lead Source <span style="color:red">*</span></label>
                    <select name="lead_source" id="lead_source" class="custom-select" required>
                      <option value="" disabled selected>Lead Source</option>
                      @foreach($lead_sources as $src)
                      <option value="{{ $src }}" {{ old('lead_source', isset($lead) ? $lead->lead_source : '')==$src ? 'selected' : '' }}>{{ $src }}</option>
                      @endforeach
                    </select>
                    @error('lead_source') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                  </div>
                </div>

                {{-- Assigned To --}}
                <div class="form-row">
                  <div class="form-group col-6 mb-2">
                    <label for="company_url">Website</label>
                    <input type="text" name="company_url" id="company_url" value="{{ old('company_url', isset($lead) ? $lead->company_url : '') }}" class="form-control form-control-lg" placeholder="Website">
                    @error('company_url') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                  </div>
                  <div class="form-group col-6 mb-2">
                    <label for="assign_to">Assigned To <span style="color:red">*</span></label>
                    <select name="assign_to" id="assign_to" class="custom-select" required>
                      <option value="" disabled selected>Assigned To</option>
                      @foreach($users as $user)
                      <option value="{{ $user->id }}" {{ old('assign_to', isset($lead) ? $lead->assign_to : '')==$user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                      @endforeach
                    </select>
                    @error('assign_to') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                  </div>
                </div>

                {{-- Others 1-5 --}}
                <div class="form-row">
                  @for($i = 1; $i <= 5; $i++)
                  <div class="form-group {{ $i == 5 ? 'col-12' : 'col-6' }} mb-2">
                    <label for="others_{{ $i }}">Others {{ $i }}</label>
                    <input type="text" name="others_{{ $i }}" id="others_{{ $i }}" value="{{ old('others_'.$i, isset($lead) ? $lead->{'others_'.$i} : '') }}"
                      class="form-control form-control-lg" placeholder="Others {{ $i }}">
                    @error('others_'.$i) <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                  </div>
                  @endfor
                </div>

                {{-- Note --}}
                <div class="form-group mb-2">
                  <label for="note">Note</label>
                  <textarea name="note" id="note note_text" rows="3" class="form-control rounded border ckeditor-init" placeholder="Note">{{ old('note', isset($lead) ? $lead->notes->first()?->note : '') }}</textarea>
                  @error('note') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                </div>
              </div>

              {{-- Footer --}}
              <div class="modal-footer border-0">
                <button type="submit" class="btn btn-info btn-block lead-submit">{{ isset($lead) ? 'Update' : 'Create' }}</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  </div>
  <script src="{{ url('/').'/'.asset('assets/js/jquery.custom.js') }}"></script>
  <script type="text/javascript" src="{{ url('/').'/'.asset('vendor/ckeditor/js/ckeditor.js') }}"></script>
  <script>
    document.querySelectorAll('.ckeditor-init').forEach(function(item) {
      var editor = CKEDITOR.replace(item, {
        customConfig: 'config.js',
        toolbar: 'Basic',
        height: '15em'
      });

      editor.on('change', function() {
        this.updateElement();
      });
    });

    document.addEventListener('DOMContentLoaded', function() {
      var pincodeSelect = document.getElementById('pincode_id');
      if (pincodeSelect && pincodeSelect.value) {
        getAddressData();
      }
    });
  </script>

</x-app-layout>