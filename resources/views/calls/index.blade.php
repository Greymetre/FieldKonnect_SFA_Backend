<x-app-layout>
    @php($totalRecords = $entries->count())

    <style>
        .calls-page { color: #c5d2f3; }
        .calls-page-head { display: flex; align-items: center; justify-content: space-between; gap: 20px; margin-bottom: 16px; }
        .calls-breadcrumb { margin-bottom: 8px; color: #7185bd; font-size: 11px; font-weight: 800; letter-spacing: .22em; text-transform: uppercase; }
        .calls-breadcrumb span { margin-left: 8px; color: #35ccef; }
        .calls-heading { display: flex; align-items: center; gap: 12px; }
        .calls-heading h1 { margin: 0; color: #f7f9ff; font-size: 25px; font-weight: 800; line-height: 1; }
        .calls-count { display: inline-flex; align-items: center; min-height: 31px; padding: 0 16px; border: 1px solid rgba(34, 211, 238, .48); border-radius: 999px; background: rgba(34, 211, 238, .08); color: #28d7f4; font-size: 13px; font-weight: 800; }
        .calls-toolbar { display: flex; align-items: center; gap: 10px; }
        .calls-icon-btn, .calls-filter-btn, .calls-add-btn { display: inline-flex; align-items: center; justify-content: center; border: 1px solid rgba(85, 126, 218, .32); border-radius: 12px; background: rgba(7, 20, 49, .62); color: #c7d5f5; box-shadow: none; }
        .calls-icon-btn { width: 45px; height: 45px; padding: 0; }
        .calls-filter-btn { height: 45px; gap: 8px; padding: 0 18px; }
        .calls-add-btn { width: 168px; height: 42px; gap: 7px; padding: 0 16px; border-color: transparent; border-radius: 10px; background: linear-gradient(135deg, #31cfe5, #438ff0); color: #061329; font-size: 14px; font-weight: 700; }
        .calls-toolbar button[disabled] { cursor: default; opacity: 1; }
        .calls-icon-btn .material-icons, .calls-filter-btn .material-icons, .calls-add-btn .material-icons { font-size: 20px; }
        .calls-filter-panel { display: grid; grid-template-columns: minmax(230px, 1.2fr) repeat(2, minmax(170px, .65fr)) auto; align-items: end; gap: 12px; margin: 0 0 14px; }
        .calls-filter-field label { display: block; margin: 0 0 7px; color: #7f91c2; font-size: 11px; font-weight: 800; letter-spacing: .1em; text-transform: uppercase; }
        .calls-search { position: relative; align-self: end; }
        .calls-search .material-icons { position: absolute; top: 11px; left: 13px; color: #7185b8; font-size: 19px; }
        .calls-control { width: 100%; height: 42px; padding: 0 13px; border: 1px solid rgba(85, 126, 218, .3) !important; border-radius: 10px; outline: 0; background: rgba(5, 17, 43, .68) !important; color: #c9d6f4 !important; box-shadow: none !important; }
        .calls-search .calls-control { padding-left: 41px; }
        .calls-control::placeholder { color: #6f81ae; }
        .calls-card { overflow: hidden; border: 1px solid rgba(85, 126, 218, .27); border-radius: 14px; background: rgba(7, 20, 49, .54); }
        .calls-card-head { display: flex; align-items: center; justify-content: space-between; gap: 20px; min-height: 67px; padding: 10px 18px; border-bottom: 1px solid rgba(85, 126, 218, .24); }
        .calls-directory { display: flex; align-items: center; gap: 12px; }
        .calls-directory-icon { display: inline-flex; align-items: center; justify-content: center; width: 46px; height: 46px; border: 1px solid rgba(34, 211, 238, .5); border-radius: 12px; background: rgba(34, 211, 238, .08); color: #22d3ee; }
        .calls-directory-icon .material-icons { font-size: 22px; }
        .calls-directory strong { display: block; color: #f5f8ff; font-size: 16px; }
        .calls-directory small { display: block; margin-top: 3px; color: #7284b5; font-size: 13px; }
        .calls-assign { display: inline-flex; align-items: center; justify-content: center; min-width: 190px; height: 42px; padding: 0 15px; border: 1px solid rgba(85, 126, 218, .3); border-radius: 10px; background: transparent; color: #b7c6eb; font-size: 13px; font-weight: 700; }
        .calls-table-scroll { overflow-x: auto; }
        .calls-table { width: 100%; min-width: 1050px; margin: 0; border-collapse: collapse; }
        .calls-table th { padding: 13px 14px; border-bottom: 1px solid rgba(85, 126, 218, .24); color: #8395c4; font-size: 11px; font-weight: 800; letter-spacing: .09em; text-align: left; text-transform: uppercase; white-space: nowrap; }
        .calls-table td { height: 61px; padding: 12px 14px; border-bottom: 1px solid rgba(85, 126, 218, .18); color: #adbee6; font-size: 13px; vertical-align: middle; }
        .calls-table tbody tr:last-child td { border-bottom: 0; }
        .calls-table tbody tr:hover { background: rgba(30, 62, 119, .14); }
        .calls-check { width: 15px; height: 15px; accent-color: #22d3ee; }
        .calls-status { display: inline-flex; align-items: center; justify-content: center; min-width: 92px; min-height: 31px; padding: 0 13px; border: 1px solid rgba(34, 211, 238, .34); border-radius: 999px; background: rgba(34, 211, 238, .06); color: #45d6ef; font-size: 11px; font-weight: 800; letter-spacing: .08em; text-transform: uppercase; }
        .calls-history { display: inline-flex; align-items: center; gap: 6px; padding: 0; border: 0; background: transparent; color: #aebfe7; font-size: 13px; font-weight: 700; }
        .calls-history .material-icons { font-size: 17px; }
        .calls-footer { display: flex; align-items: center; justify-content: space-between; gap: 15px; min-height: 64px; padding: 12px 18px; border-top: 1px solid rgba(85, 126, 218, .22); color: #8193c2; font-size: 13px; }
        .calls-pagination { display: flex; align-items: center; gap: 5px; }
        .calls-page-btn { display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 32px; border: 1px solid rgba(85, 126, 218, .27); border-radius: 10px; background: rgba(6, 18, 45, .56); color: #687bac; }
        .calls-page-btn.active { border-color: transparent; background: linear-gradient(135deg, #29d0e8, #439af5); color: #07142b; font-weight: 800; }
        .calls-page-btn .material-icons { font-size: 18px; }
        .calls-empty { padding: 34px 20px !important; color: #7d8fbd !important; text-align: center; }
        .calls-modal { position: fixed; inset: 0; z-index: 9999; display: none; align-items: center; justify-content: center; padding: 28px 16px; background: rgba(1, 8, 24, .76); backdrop-filter: blur(3px); }
        .calls-modal.show { display: flex; }
        .calls-modal-dialog { width: min(760px, 100%); max-height: calc(100vh - 40px); overflow-y: auto; border: 1px solid rgba(77, 122, 221, .4); border-radius: 16px; background: #0b1e47; box-shadow: 0 26px 80px rgba(0, 0, 0, .38); scrollbar-width: thin; scrollbar-color: #22d3ee transparent; }
        .calls-modal-head { position: sticky; top: 0; z-index: 2; display: flex; align-items: center; justify-content: space-between; min-height: 64px; padding: 14px 24px; border-bottom: 1px solid rgba(85, 126, 218, .24); background: #0b1e47; }
        .calls-modal-head h2 { margin: 0; color: #f3f7ff; font-size: 20px; font-weight: 800; }
        .calls-modal-close { display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; padding: 0; border: 0; background: transparent; color: #8fa1d0; }
        .calls-modal-close .material-icons { font-size: 27px; }
        .calls-modal-form { padding: 22px 24px 24px; }
        .calls-form-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px 18px; }
        .calls-form-field label { display: block; margin: 0 0 8px; color: #8fa1d0; font-size: 12px; font-weight: 800; letter-spacing: .08em; text-transform: uppercase; }
        .calls-form-field input, .calls-form-field select { width: 100%; height: 42px; padding: 0 13px; border: 1px solid rgba(85, 126, 218, .35); border-radius: 10px; outline: 0; background: rgba(5, 18, 47, .46); color: #d8e3ff; font-size: 14px; box-shadow: none; }
        .calls-form-field input[readonly] { background: rgba(20, 39, 78, .48); color: #91a3ce; cursor: default; }
        .calls-field-error { display: block; margin-top: 5px; color: #ff8c9b; font-size: 11px; }
        .calls-form-field input:focus, .calls-form-field select:focus { border-color: rgba(34, 211, 238, .7); }
        .calls-modal .select2-container { width: 100% !important; }
        .calls-modal .select2-container--default .select2-selection--single { height: 42px !important; border: 1px solid rgba(85, 126, 218, .35) !important; border-radius: 10px !important; background: rgba(5, 18, 47, .46) !important; box-shadow: none !important; }
        .calls-modal .select2-container--default .select2-selection--single .select2-selection__rendered { height: 40px; padding: 0 38px 0 13px; color: #d8e3ff !important; font-size: 14px; line-height: 40px !important; }
        .calls-modal .select2-container--default .select2-selection--single .select2-selection__placeholder { color: #7f91bd !important; }
        .calls-modal .select2-container--default .select2-selection--single .select2-selection__arrow { top: 7px !important; right: 8px !important; }
        .calls-pincode-dropdown { z-index: 10010 !important; overflow: hidden; border: 1px solid rgba(85, 126, 218, .45) !important; border-radius: 10px !important; background: #0a1c43 !important; box-shadow: 0 16px 40px rgba(0, 0, 0, .35); }
        .calls-pincode-dropdown .select2-search--dropdown { padding: 10px !important; background: #0a1c43; }
        .calls-pincode-dropdown .select2-search__field { height: 38px; padding: 0 12px !important; border: 1px solid rgba(34, 211, 238, .45) !important; border-radius: 8px; outline: 0; background: #071735 !important; color: #e2ebff !important; }
        .calls-pincode-dropdown .select2-results { background: #0a1c43; }
        .calls-pincode-dropdown .select2-results__options { max-height: 220px !important; }
        .calls-pincode-dropdown .select2-results__option { padding: 9px 12px !important; color: #b8c7e9 !important; font-size: 13px; }
        .calls-pincode-dropdown .select2-results__option--highlighted[aria-selected] { background: rgba(34, 211, 238, .16) !important; color: #ffffff !important; }
        .calls-pincode-dropdown .select2-results__option[aria-selected=true] { background: rgba(59, 130, 246, .2) !important; }
        .calls-modal-actions { display: flex; justify-content: flex-end; margin-top: 20px; }
        .calls-modal-submit { min-width: 130px; height: 42px; border: 0; border-radius: 10px; background: linear-gradient(135deg, #2bd1e8, #62baf7); color: #061329; font-size: 14px; font-weight: 800; }
        .calls-alert { margin-bottom: 14px; padding: 11px 14px; border: 1px solid rgba(45, 212, 191, .35); border-radius: 10px; background: rgba(45, 212, 191, .08); color: #76e4cf; font-size: 13px; }
        .calls-assign:disabled { opacity: .5; cursor: not-allowed; }
        .calls-assign-dialog { width: min(680px, 100%); }
        .calls-selected-chip { display: inline-flex; align-items: center; min-height: 34px; margin-bottom: 16px; padding: 0 15px; border: 1px solid rgba(34, 211, 238, .45); border-radius: 999px; background: rgba(34, 211, 238, .08); color: #27d5f2; font-size: 12px; font-weight: 800; letter-spacing: .08em; text-transform: uppercase; }
        .calls-bulk-box { margin-bottom: 18px; padding: 16px; border: 1px solid rgba(85, 126, 218, .3); border-radius: 12px; background: rgba(11, 31, 72, .55); }
        .calls-bulk-box label { display: block; margin: 0 0 9px; color: #8fa1d0; font-size: 12px; font-weight: 800; letter-spacing: .08em; text-transform: uppercase; }
        .calls-bulk-select { width: 100%; height: 42px; padding: 0 13px; border: 1px solid rgba(85, 126, 218, .4); border-radius: 10px; outline: 0; background: #091a3e; color: #d8e3ff; }
        .calls-assignment-list { overflow: hidden; border: 1px solid rgba(85, 126, 218, .3); border-radius: 12px; }
        .calls-assignment-head, .calls-assignment-row { display: grid; grid-template-columns: 1fr .85fr 1.2fr; align-items: center; gap: 14px; padding: 13px 16px; }
        .calls-assignment-head { border-bottom: 1px solid rgba(85, 126, 218, .3); color: #8fa1d0; font-size: 11px; font-weight: 800; letter-spacing: .07em; text-transform: uppercase; }
        .calls-assignment-row { border-bottom: 1px solid rgba(85, 126, 218, .2); color: #b9c8e9; font-size: 13px; }
        .calls-assignment-row:last-child { border-bottom: 0; }
        .calls-assignment-row select { width: 100%; height: 38px; padding: 0 10px; border: 1px solid rgba(85, 126, 218, .35); border-radius: 9px; background: #091a3e; color: #d8e3ff; }
        body.calls-modal-open { overflow: hidden; }
        @media (max-width: 991px) {
            .calls-page-head { align-items: flex-start; flex-direction: column; }
            .calls-toolbar { flex-wrap: wrap; }
            .calls-filter-panel { grid-template-columns: 1fr; }
        }
        @media (max-width: 575px) {
            .calls-form-grid { grid-template-columns: 1fr; }
            .calls-modal-head, .calls-modal-form { padding-left: 20px; padding-right: 20px; }
            .calls-assignment-head { display: none; }
            .calls-assignment-row { grid-template-columns: 1fr; gap: 7px; }
        }
    </style>

    <div class="calls-page">
        @if(session('message_success'))
            <div class="calls-alert">{{ session('message_success') }}</div>
        @endif
        <header class="calls-page-head">
            <div>
                <div class="calls-breadcrumb">Call Management <span>› &nbsp; Calls</span></div>
                <div class="calls-heading">
                    <h1>Calls</h1>
                    <span class="calls-count" id="callsRecordCount">{{ $totalRecords }} records</span>
                </div>
            </div>
            <div class="calls-toolbar">
                <button class="calls-icon-btn" type="button" disabled title="Import Excel will be enabled later"><i class="material-icons">cloud_upload</i></button>
                <button class="calls-icon-btn" type="button" disabled title="Report will be enabled later"><i class="material-icons">description</i></button>
                <button class="calls-add-btn" id="openAddCallModal" type="button"><i class="material-icons">add_circle_outline</i>Add Manually</button>
            </div>
        </header>

        <div class="calls-filter-panel" id="callsFilterPanel">
            <div class="calls-search"><i class="material-icons">search</i><input class="calls-control" id="callsSearch" type="search" placeholder="Search firm or mobile" autocomplete="off"></div>
            <div class="calls-filter-field"><label for="callsStatus">Status</label><select class="calls-control" id="callsStatus"><option value="">All</option><option value="pending">Pending</option></select></div>
            <div class="calls-filter-field"><label for="callsCaller">Caller</label><select class="calls-control" id="callsCaller"><option value="">All</option><option value="unassigned">Unassigned</option></select></div>
            <button class="calls-assign" id="openAssignModal" type="button" disabled>Assign Selected (&nbsp;<span id="selectedCount">0</span>&nbsp;)</button>
        </div>

        <section class="calls-card" aria-labelledby="call-directory-title">
            <div class="calls-card-head">
                <div class="calls-directory">
                    <span class="calls-directory-icon"><i class="material-icons">phone_in_talk</i></span>
                    <div><strong id="call-directory-title">Call Directory</strong><small>Calling list · Page 1 of 1</small></div>
                </div>
            </div>

            <div class="calls-table-scroll">
                <table class="calls-table">
                    <thead><tr><th><input class="calls-check" id="selectAllCalls" type="checkbox" aria-label="Select all customers"></th><th>Firm Name</th><th>Contact Person</th><th>Mobile</th><th>Cust. Type</th><th>City</th><th>State</th><th>Status</th><th>Caller</th><th>Others</th></tr></thead>
                    <tbody id="callsTableBody">
                        @foreach($entries as $entry)
                            <tr data-entry-id="{{ $entry->id }}" data-firm="{{ $entry->firm_name }}" data-mobile="{{ $entry->mobile_number }}" data-current-caller="{{ $entry->assigned_user_id }}" data-search="{{ strtolower($entry->firm_name . ' ' . $entry->mobile_number . ' ' . $entry->contact_person_name) }}" data-status="{{ $entry->status }}" data-caller="{{ $entry->assigned_user_id }}">
                                <td><input class="calls-check calls-row-check" type="checkbox" value="{{ $entry->id }}" aria-label="Select {{ $entry->firm_name }}"></td>
                                <td>{{ $entry->firm_name }}</td><td>{{ $entry->contact_person_name }}</td><td>{{ $entry->mobile_number }}</td><td>{{ $entry->customer_type ?: '—' }}</td><td>{{ $entry->city ?: '—' }}</td><td>{{ $entry->state ?: '—' }}</td>
                                <td><span class="calls-status">{{ $entry->status }}</span></td><td>{{ optional($entry->assignedUser)->name ?: '—' }}</td>
                                <td><button class="calls-history" type="button" disabled title="Call history will be enabled later"><i class="material-icons">history</i>History</button></td>
                            </tr>
                        @endforeach
                        @if($entries->isEmpty())
                            <tr id="callsEmptyState"><td class="calls-empty" colspan="10">No call entries available. Use Add Manually to create one.</td></tr>
                        @endif
                        <tr id="callsNoResults" hidden><td class="calls-empty" colspan="10">No matching records found.</td></tr>
                    </tbody>
                </table>
            </div>

            <footer class="calls-footer">
                <span id="callsShowing">{{ $totalRecords ? 'Showing 1–' . $totalRecords . ' of ' . $totalRecords . ' calls' : 'Showing 0 of 0 calls' }}</span>
                <div class="calls-pagination" aria-label="Static pagination">
                    <button class="calls-page-btn" type="button" disabled><i class="material-icons">chevron_left</i></button>
                    <button class="calls-page-btn active" type="button" disabled>1</button>
                    <button class="calls-page-btn" type="button" disabled><i class="material-icons">chevron_right</i></button>
                </div>
            </footer>
        </section>
    </div>

    <div class="calls-modal" id="assignCallsModal" role="dialog" aria-modal="true" aria-labelledby="assignCallsModalTitle" aria-hidden="true">
        <div class="calls-modal-dialog calls-assign-dialog">
            <div class="calls-modal-head">
                <h2 id="assignCallsModalTitle">Assign Calls</h2>
                <button class="calls-modal-close" id="closeAssignModal" type="button" aria-label="Close modal"><i class="material-icons">close</i></button>
            </div>
            <form class="calls-modal-form" method="POST" action="{{ route('calls.bulk-assign') }}" id="bulkAssignForm">
                @csrf
                <span class="calls-selected-chip"><span id="modalSelectedCount">0</span>&nbsp; selected</span>
                <div class="calls-bulk-box">
                    <label for="bulkAssignedCaller">Bulk assign all to</label>
                    <select class="calls-bulk-select" id="bulkAssignedCaller" name="bulk_assigned_user_id" required>
                        <option value="">Select caller</option>
                        @foreach($callers as $caller)<option value="{{ $caller->id }}">{{ $caller->name }}</option>@endforeach
                    </select>
                    @error('bulk_assigned_user_id', 'bulkAssign')<span class="calls-field-error">{{ $message }}</span>@enderror
                </div>
                <div class="calls-assignment-list">
                    <div class="calls-assignment-head"><span>Firm Name</span><span>Mobile</span><span>Caller (Override)</span></div>
                    <div id="assignmentRows"></div>
                </div>
                <div id="assignmentInputs"></div>
                @error('entry_ids', 'bulkAssign')<span class="calls-field-error">{{ $message }}</span>@enderror
                <div class="calls-modal-actions"><button class="calls-modal-submit" type="submit">Confirm Assignment</button></div>
            </form>
        </div>
    </div>

    <div class="calls-modal" id="addCallModal" role="dialog" aria-modal="true" aria-labelledby="addCallModalTitle" aria-hidden="true">
        <div class="calls-modal-dialog">
            <div class="calls-modal-head">
                <h2 id="addCallModalTitle">Add Call Manually</h2>
                <button class="calls-modal-close" id="closeAddCallModal" type="button" aria-label="Close modal"><i class="material-icons">close</i></button>
            </div>
            <form class="calls-modal-form" id="addCallForm" method="POST" action="{{ route('calls.store') }}">
                @csrf
                <div class="calls-form-grid">
                    <div class="calls-form-field"><label for="manualFirmName">Firm Name</label><input id="manualFirmName" name="firm_name" type="text" value="{{ old('firm_name') }}" required>@error('firm_name', 'addCall')<span class="calls-field-error">{{ $message }}</span>@enderror</div>
                    <div class="calls-form-field"><label for="manualContactName">Contact Person Name</label><input id="manualContactName" name="contact_person_name" type="text" value="{{ old('contact_person_name') }}" required>@error('contact_person_name', 'addCall')<span class="calls-field-error">{{ $message }}</span>@enderror</div>
                    <div class="calls-form-field"><label for="manualMobile">Mobile Number</label><input id="manualMobile" name="mobile_number" type="tel" value="{{ old('mobile_number') }}" required>@error('mobile_number', 'addCall')<span class="calls-field-error">{{ $message }}</span>@enderror</div>
                    <div class="calls-form-field"><label for="manualCustomerType">Customer Type</label><input id="manualCustomerType" name="customer_type" type="text" value="{{ old('customer_type') }}"></div>
                    <div class="calls-form-field"><label for="manualAddress">Address</label><input id="manualAddress" name="address" type="text" value="{{ old('address') }}"></div>
                    <div class="calls-form-field">
                        <label for="manualPin">Pincode</label>
                        <select id="manualPin" name="pincode_id" required>
                            <option value="">Select pincode</option>
                            @foreach($pincodes as $pincode)
                                @php
                                    $pinCity = $pincode->cityname;
                                    $pinDistrict = optional($pinCity)->districtname;
                                    $pinState = optional($pinDistrict)->statename ?: optional($pinCity)->statename;
                                @endphp
                                <option value="{{ $pincode->id }}" data-city="{{ optional($pinCity)->city_name }}" data-district="{{ optional($pinDistrict)->district_name }}" data-state="{{ optional($pinState)->state_name }}" {{ old('pincode_id') == $pincode->id ? 'selected' : '' }}>{{ $pincode->pincode }}{{ optional($pinCity)->city_name ? ' — ' . optional($pinCity)->city_name : '' }}{{ optional($pinDistrict)->district_name ? ', ' . optional($pinDistrict)->district_name : '' }}</option>
                            @endforeach
                        </select>
                        @error('pincode_id', 'addCall')<span class="calls-field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="calls-form-field"><label for="manualCity">City</label><input id="manualCity" type="text" readonly></div>
                    <div class="calls-form-field"><label for="manualDistrict">District</label><input id="manualDistrict" type="text" readonly></div>
                    <div class="calls-form-field"><label for="manualState">State</label><input id="manualState" type="text" readonly></div>
                    <div class="calls-form-field">
                        <label for="manualCaller">Caller Assignment</label>
                        <select id="manualCaller" name="assigned_user_id" required><option value="">Select caller</option>@foreach($callers as $caller)<option value="{{ $caller->id }}" {{ old('assigned_user_id') == $caller->id ? 'selected' : '' }}>{{ $caller->name }}</option>@endforeach</select>
                        @error('assigned_user_id', 'addCall')<span class="calls-field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="calls-form-field"><label for="manualCustom1">Custom Column 1</label><input id="manualCustom1" name="custom_column_1" type="text" value="{{ old('custom_column_1') }}"></div>
                    <div class="calls-form-field"><label for="manualCustom2">Custom Column 2</label><input id="manualCustom2" name="custom_column_2" type="text" value="{{ old('custom_column_2') }}"></div>
                    <div class="calls-form-field"><label for="manualCustom3">Custom Column 3</label><input id="manualCustom3" name="custom_column_3" type="text" value="{{ old('custom_column_3') }}"></div>
                    <div class="calls-form-field"><label for="manualCustom4">Custom Column 4</label><input id="manualCustom4" name="custom_column_4" type="text" value="{{ old('custom_column_4') }}"></div>
                </div>
                <div class="calls-modal-actions"><button class="calls-modal-submit" type="submit">Add Lead</button></div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const search = document.getElementById('callsSearch');
            const status = document.getElementById('callsStatus');
            const caller = document.getElementById('callsCaller');
            const selectAll = document.getElementById('selectAllCalls');
            const selectedCount = document.getElementById('selectedCount');
            const recordCount = document.getElementById('callsRecordCount');
            const showing = document.getElementById('callsShowing');
            const noResults = document.getElementById('callsNoResults');
            const modal = document.getElementById('addCallModal');
            const openModal = document.getElementById('openAddCallModal');
            const closeModal = document.getElementById('closeAddCallModal');
            const pincode = document.getElementById('manualPin');
            const assignModal = document.getElementById('assignCallsModal');
            const openAssignModal = document.getElementById('openAssignModal');
            const closeAssignModal = document.getElementById('closeAssignModal');
            const callerOptions = @json($callers->map(fn ($caller) => ['id' => $caller->id, 'name' => $caller->name])->values());
            const rows = () => Array.from(document.querySelectorAll('#callsTableBody tr[data-search]'));
            const visibleRows = () => rows().filter(row => !row.hidden);

            function setModal(open) {
                modal.classList.toggle('show', open);
                modal.setAttribute('aria-hidden', open ? 'false' : 'true');
                document.body.classList.toggle('calls-modal-open', open);
                if (open) document.getElementById('manualFirmName').focus();
            }

            openModal.addEventListener('click', () => setModal(true));
            closeModal.addEventListener('click', () => setModal(false));
            modal.addEventListener('click', event => { if (event.target === modal) setModal(false); });

            function setAssignModal(open) {
                assignModal.classList.toggle('show', open);
                assignModal.setAttribute('aria-hidden', open ? 'false' : 'true');
                document.body.classList.toggle('calls-modal-open', open);
            }

            function buildAssignmentRows() {
                const selected = Array.from(document.querySelectorAll('.calls-row-check:checked'));
                const rowsContainer = document.getElementById('assignmentRows');
                const inputsContainer = document.getElementById('assignmentInputs');
                rowsContainer.innerHTML = '';
                inputsContainer.innerHTML = '';
                document.getElementById('modalSelectedCount').textContent = selected.length;

                selected.forEach(function (checkbox) {
                    const row = checkbox.closest('tr');
                    const entryId = row.dataset.entryId;
                    const item = document.createElement('div');
                    item.className = 'calls-assignment-row';

                    const firm = document.createElement('span');
                    firm.textContent = row.dataset.firm;
                    const mobile = document.createElement('span');
                    mobile.textContent = row.dataset.mobile;
                    const override = document.createElement('select');
                    override.name = 'overrides[' + entryId + ']';
                    override.setAttribute('aria-label', 'Override caller for ' + row.dataset.firm);

                    const defaultOption = document.createElement('option');
                    defaultOption.value = '';
                    defaultOption.textContent = 'Use bulk assignment';
                    override.appendChild(defaultOption);
                    callerOptions.forEach(function (caller) {
                        const option = document.createElement('option');
                        option.value = caller.id;
                        option.textContent = caller.name;
                        override.appendChild(option);
                    });

                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'entry_ids[]';
                    input.value = entryId;
                    inputsContainer.appendChild(input);
                    item.append(firm, mobile, override);
                    rowsContainer.appendChild(item);
                });
            }

            openAssignModal.addEventListener('click', function () {
                buildAssignmentRows();
                setAssignModal(true);
            });
            closeAssignModal.addEventListener('click', () => setAssignModal(false));
            assignModal.addEventListener('click', event => { if (event.target === assignModal) setAssignModal(false); });
            document.addEventListener('keydown', function (event) {
                if (event.key !== 'Escape') return;
                if (assignModal.classList.contains('show')) setAssignModal(false);
                else if (modal.classList.contains('show')) setModal(false);
            });

            function fillLocation() {
                const option = pincode.options[pincode.selectedIndex];
                document.getElementById('manualCity').value = option ? option.dataset.city || '' : '';
                document.getElementById('manualDistrict').value = option ? option.dataset.district || '' : '';
                document.getElementById('manualState').value = option ? option.dataset.state || '' : '';
            }

            pincode.addEventListener('change', fillLocation);
            if (window.jQuery && jQuery.fn.select2) {
                jQuery(pincode).select2({
                    dropdownParent: jQuery('#addCallModal'),
                    dropdownCssClass: 'calls-pincode-dropdown',
                    placeholder: 'Search or select pincode',
                    width: '100%'
                }).on('change', fillLocation);
            }
            fillLocation();

            @if($errors->addCall->any())
                setModal(true);
            @endif

            function updateCount() {
                const total = document.querySelectorAll('.calls-row-check:checked').length;
                selectedCount.textContent = total;
                openAssignModal.disabled = total === 0;
            }
            function filterRows() {
                const term = search.value.trim().toLowerCase();
                let visible = 0;
                rows().forEach(function (row) {
                    row.hidden = !((!term || row.dataset.search.includes(term)) && (!status.value || row.dataset.status === status.value) && (!caller.value || row.dataset.caller === caller.value));
                    if (!row.hidden) visible++;
                });
                noResults.hidden = visible !== 0 || rows().length === 0;
                recordCount.textContent = visible + (visible === 1 ? ' record' : ' records');
                showing.textContent = visible ? 'Showing 1–' + visible + ' of ' + visible + ' calls' : 'Showing 0 of 0 calls';
                selectAll.checked = false;
                updateCount();
            }

            search.addEventListener('input', filterRows);
            status.addEventListener('change', filterRows);
            caller.addEventListener('change', filterRows);
            selectAll.addEventListener('change', function () {
                visibleRows().forEach(row => row.querySelector('.calls-row-check').checked = selectAll.checked);
                updateCount();
            });
            document.querySelectorAll('.calls-row-check').forEach(checkbox => checkbox.addEventListener('change', updateCount));

            @if($errors->bulkAssign->any())
                @foreach((array) old('entry_ids', []) as $oldEntryId)
                    document.querySelector('.calls-row-check[value="{{ (int) $oldEntryId }}"]')?.click();
                @endforeach
                buildAssignmentRows();
                document.getElementById('bulkAssignedCaller').value = @json((string) old('bulk_assigned_user_id'));
                setAssignModal(true);
            @endif
        });
    </script>
</x-app-layout>
