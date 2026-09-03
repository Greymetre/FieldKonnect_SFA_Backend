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
        .calls-icon-btn { width: 45px; height: 45px; padding: 0; text-decoration: none; cursor: pointer; }
        .calls-upload-btn { width: auto; height: 42px; gap: 7px; padding: 0 16px; font-size: 13px; font-weight: 700; }
        .calls-filter-btn { height: 45px; gap: 8px; padding: 0 18px; }
        .calls-add-btn { width: 168px; height: 42px; gap: 7px; padding: 0 16px; border-color: transparent; border-radius: 10px; background: linear-gradient(135deg, #31cfe5, #438ff0); color: #061329; font-size: 14px; font-weight: 700; }
        .calls-toolbar button[disabled] { cursor: default; opacity: 1; }
        .calls-icon-btn .material-icons, .calls-filter-btn .material-icons, .calls-add-btn .material-icons { font-size: 20px; }
        .calls-filter-panel { display: grid; grid-template-columns: minmax(230px, 1.2fr) repeat(2, minmax(170px, .65fr)) auto; align-items: end; gap: 12px; margin: 0 0 14px; }
        .calls-filter-field label { display: block; margin: 0 0 7px; color: #7f91c2; font-size: 11px; font-weight: 800; letter-spacing: .1em; text-transform: uppercase; }
        .calls-filter-field .select2-container { width: 100% !important; }
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
        .calls-row-actions { display: flex; align-items: center; gap: 6px; white-space: nowrap; }
        .calls-row-actions form { margin: 0; }
        .calls-action-btn { display: inline-flex; align-items: center; justify-content: center; width: 31px; height: 31px; padding: 0; border: 1px solid rgba(85, 126, 218, .28); border-radius: 8px; background: rgba(8, 25, 59, .55); color: #9fb1dc; }
        .calls-action-btn:hover { border-color: rgba(34, 211, 238, .45); color: #35d2ed; }
        .calls-action-btn.delete:hover { border-color: rgba(248, 113, 113, .48); color: #fb8c9b; }
        .calls-action-btn .material-icons { font-size: 17px; }
        .calls-footer { display: flex; align-items: center; justify-content: space-between; gap: 15px; min-height: 64px; padding: 12px 18px; border-top: 1px solid rgba(85, 126, 218, .22); color: #8193c2; font-size: 13px; }
        .calls-pagination { display: flex; align-items: center; gap: 5px; }
        .calls-page-btn { display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 32px; border: 1px solid rgba(85, 126, 218, .27); border-radius: 10px; background: rgba(6, 18, 45, .56); color: #687bac; }
        .calls-page-btn.active { border-color: transparent; background: linear-gradient(135deg, #29d0e8, #439af5); color: #07142b; font-weight: 800; }
        .calls-page-btn .material-icons { font-size: 18px; }
        .calls-empty { padding: 34px 20px !important; color: #7d8fbd !important; text-align: center; }
        .calls-modal { position: fixed; inset: 0; z-index: 3000; display: none; align-items: center; justify-content: center; padding: 28px 16px; background: rgba(1, 8, 24, .76); backdrop-filter: blur(3px); }
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
        .calls-modal .select2-container { width: 100% !important; z-index: 10010 !important; }
        .calls-modal .select2-container--default .select2-selection--single { min-height: 42px !important; }
        .calls-modal-actions { display: flex; justify-content: flex-end; margin-top: 20px; }
        .calls-modal-submit { min-width: 130px; height: 42px; border: 0; border-radius: 10px; background: linear-gradient(135deg, #2bd1e8, #62baf7); color: #061329; font-size: 14px; font-weight: 800; }
        .calls-confirm-submit { min-width: 220px; padding: 0 22px; white-space: nowrap; }
        .calls-alert { margin-bottom: 14px; padding: 11px 14px; border: 1px solid rgba(45, 212, 191, .35); border-radius: 10px; background: rgba(45, 212, 191, .08); color: #76e4cf; font-size: 13px; }
        .calls-alert-error { border-color: rgba(248, 113, 113, .4); background: rgba(248, 113, 113, .08); color: #fca5a5; }
        .calls-assign:disabled { opacity: .5; cursor: not-allowed; }
        .calls-assign-dialog { width: min(680px, 100%); }
        .calls-import-dialog { width: min(520px, 100%); }
        .calls-import-file-card { display: flex; align-items: center; gap: 13px; min-height: 70px; padding: 14px 16px; border: 1px solid rgba(85, 126, 218, .34); border-radius: 12px; background: rgba(5, 18, 47, .46); }
        .calls-import-file-card > .material-icons { color: #2ed2ec; font-size: 30px; }
        .calls-import-file-info { min-width: 0; flex: 1; }
        .calls-import-file-info strong { display: block; overflow: hidden; color: #dce7ff; font-size: 14px; text-overflow: ellipsis; white-space: nowrap; }
        .calls-import-file-info span { color: #8193c2; font-size: 11px; }
        .calls-import-change { border: 0; background: transparent; color: #36d2ed; font-size: 12px; font-weight: 700; }
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
        .calls-assignment-row .select2-container { width: 100% !important; }
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
        @if(session('message_error'))
            <div class="calls-alert calls-alert-error">{{ session('message_error') }}</div>
        @endif
        @if($errors->importCall->any())
            <div class="calls-alert calls-alert-error">
                {{ $errors->importCall->first() }}
            </div>
        @endif
        <header class="calls-page-head">
            <div>
                <div class="calls-breadcrumb">Call Management <span>› &nbsp; Call Assignment</span></div>
                <div class="calls-heading">
                    <h1>Call Assignment</h1>
                    <span class="calls-count" id="callsRecordCount">{{ $totalRecords }} records</span>
                </div>
            </div>
            <div class="calls-toolbar">
                <button class="calls-icon-btn calls-upload-btn" id="openCallsImport" type="button" hidden><i class="material-icons">cloud_upload</i>Import Excel</button>
                <a class="calls-icon-btn" href="{{ route('calls.export') }}" title="Export Excel" aria-label="Export Excel" hidden>
                    <i class="material-icons">cloud_download</i>
                </a>
                <button class="calls-add-btn" id="openAddCallModal" type="button" hidden><i class="material-icons">add_circle_outline</i>Add Manually</button>
            </div>
        </header>

        <div class="calls-filter-panel" id="callsFilterPanel">
            <div class="calls-search"><i class="material-icons">search</i><input class="calls-control" id="callsSearch" type="search" placeholder="Search firm or mobile" autocomplete="off"></div>
            <div class="calls-filter-field">
                <label for="callsStatus">Status</label>
                <select class="form-control select2" id="callsStatus" style="width: 100%;">
                    <option value="">All</option>
                    <option value="pending">Pending</option>
                </select>
            </div>
            <div class="calls-filter-field">
                <label for="callsCaller">Caller</label>
                <select class="form-control select2" id="callsCaller" style="width: 100%;">
                    <option value="">All</option>
                    <option value="unassigned">Unassigned</option>
                    @foreach($callers as $filterCaller)
                        <option value="{{ $filterCaller->id }}">{{ $filterCaller->name }}</option>
                    @endforeach
                </select>
            </div>
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
                            <tr data-entry-id="{{ $entry->id }}" data-update-url="{{ route('calls.update', $entry) }}" data-firm="{{ $entry->firm_name }}" data-contact="{{ $entry->contact_person_name }}" data-mobile="{{ $entry->mobile_number }}" data-customer-type="{{ $entry->customer_type }}" data-address="{{ $entry->address }}" data-pincode-id="{{ $entry->pincode_id }}" data-custom-1="{{ $entry->custom_column_1 }}" data-custom-2="{{ $entry->custom_column_2 }}" data-custom-3="{{ $entry->custom_column_3 }}" data-custom-4="{{ $entry->custom_column_4 }}" data-current-caller="{{ $entry->assigned_user_id }}" data-current-caller-name="{{ optional($entry->assignedUser)->name }}" data-search="{{ strtolower($entry->firm_name . ' ' . $entry->mobile_number . ' ' . $entry->contact_person_name) }}" data-status="{{ $entry->status }}" data-caller="{{ $entry->assigned_user_id }}">
                                <td><input class="calls-check calls-row-check" type="checkbox" value="{{ $entry->id }}" aria-label="Select {{ $entry->firm_name }}"></td>
                                <td>{{ $entry->firm_name }}</td><td>{{ $entry->contact_person_name }}</td><td>{{ $entry->mobile_number }}</td><td>{{ $entry->customer_type ?: '—' }}</td><td>{{ $entry->city ?: '—' }}</td><td>{{ $entry->state ?: '—' }}</td>
                                <td><span class="calls-status">{{ $entry->status }}</span></td><td>{{ optional($entry->assignedUser)->name ?: '—' }}</td>
                                <td>
                                    <div class="calls-row-actions">
                                        @can('call_management_edit_delete')
                                            <button class="calls-action-btn edit-call-entry" type="button" title="Edit"><i class="material-icons">edit</i></button>
                                            <form method="POST" action="{{ route('calls.destroy', $entry) }}" onsubmit="return confirm('Delete this call entry?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="calls-action-btn delete" type="submit" title="Delete"><i class="material-icons">delete</i></button>
                                            </form>
                                        @endcan
                                        <button class="calls-action-btn" type="button" disabled title="Call history will be enabled later"><i class="material-icons">history</i></button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        @if($entries->isEmpty())
                            <tr id="callsEmptyState"><td class="calls-empty" colspan="10">No calls are waiting for assignment.</td></tr>
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

    <div class="calls-modal" id="importCallsModal" role="dialog" aria-modal="true" aria-labelledby="importCallsModalTitle" aria-hidden="true">
        <div class="calls-modal-dialog calls-import-dialog">
            <div class="calls-modal-head">
                <h2 id="importCallsModalTitle">Import Calls</h2>
                <button class="calls-modal-close" id="closeCallsImport" type="button" aria-label="Close modal"><i class="material-icons">close</i></button>
            </div>
            <form class="calls-modal-form" id="callsImportForm" action="{{ route('calls.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input id="callsImportFile" name="import_file" type="file" accept=".xlsx,.xls,.csv" required hidden>
                <div class="calls-import-file-card">
                    <i class="material-icons">description</i>
                    <div class="calls-import-file-info">
                        <strong id="callsImportFileName">No file selected</strong>
                        <span id="callsImportFileSize">XLSX, XLS or CSV</span>
                    </div>
                    <button class="calls-import-change" id="changeCallsImportFile" type="button">Change</button>
                </div>
                <div class="calls-modal-actions">
                    <button class="calls-modal-submit" id="submitCallsImport" type="submit">Import</button>
                </div>
            </form>
        </div>
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
                    <select class="calls-bulk-select select2" id="bulkAssignedCaller" name="bulk_assigned_user_id" required style="width: 100%;">
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
                <div class="calls-modal-actions"><button class="calls-modal-submit calls-confirm-submit" type="submit">Confirm Assignment</button></div>
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
                <input id="callFormMethod" type="hidden" name="_method" value="PUT" disabled>
                <input id="editEntryId" type="hidden" name="entry_id" value="">
                <div class="calls-form-grid">
                    <div class="calls-form-field"><label for="manualFirmName">Firm Name</label><input id="manualFirmName" name="firm_name" type="text" value="{{ old('firm_name') }}" required>@error('firm_name', 'addCall')<span class="calls-field-error">{{ $message }}</span>@enderror</div>
                    <div class="calls-form-field"><label for="manualContactName">Contact Person Name</label><input id="manualContactName" name="contact_person_name" type="text" value="{{ old('contact_person_name') }}" required>@error('contact_person_name', 'addCall')<span class="calls-field-error">{{ $message }}</span>@enderror</div>
                    <div class="calls-form-field"><label for="manualMobile">Mobile Number</label><input id="manualMobile" name="mobile_number" type="tel" inputmode="numeric" minlength="10" maxlength="10" pattern="[0-9]{10}" value="{{ old('mobile_number') }}" required>@error('mobile_number', 'addCall')<span class="calls-field-error">{{ $message }}</span>@enderror</div>
                    <div class="calls-form-field"><label for="manualCustomerType">Customer Type</label><input id="manualCustomerType" name="customer_type" type="text" value="{{ old('customer_type') }}"></div>
                    <div class="calls-form-field"><label for="manualAddress">Address</label><input id="manualAddress" name="address" type="text" value="{{ old('address') }}"></div>
                    <div class="calls-form-field">
                        <label for="manualPin">Pincode</label>
                        <select class="form-control pincode select2" id="manualPin" name="pincode_id" required style="width: 100%;">
                            <option value="">Select Pincode</option>
                            @foreach($pincodeOptions as $pin)
                                <option value="{!! $pin['id'] !!}" data-city="{!! e($pin['city']) !!}" data-district="{!! e($pin['district']) !!}" data-state="{!! e($pin['state']) !!}">{!! e($pin['pincode']) !!}</option>
                            @endforeach
                        </select>
                        @error('pincode_id', 'addCall')<span class="calls-field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="calls-form-field"><label for="manualCity">City</label><input id="manualCity" type="text" readonly></div>
                    <div class="calls-form-field"><label for="manualDistrict">District</label><input id="manualDistrict" type="text" readonly></div>
                    <div class="calls-form-field"><label for="manualState">State</label><input id="manualState" type="text" readonly></div>
                    <div class="calls-form-field">
                        <label for="manualCaller">Caller Assignment</label>
                        <select class="form-control select2" id="manualCaller" name="assigned_user_id" required style="width: 100%;">
                            <option value="">Select caller</option>
                            @foreach($callers as $caller)
                                <option value="{{ $caller->id }}">{{ $caller->name }}</option>
                            @endforeach
                        </select>
                        @error('assigned_user_id', 'addCall')<span class="calls-field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="calls-form-field"><label for="manualCustom1">Custom Column 1</label><input id="manualCustom1" name="custom_column_1" type="text" value="{{ old('custom_column_1') }}"></div>
                    <div class="calls-form-field"><label for="manualCustom2">Custom Column 2</label><input id="manualCustom2" name="custom_column_2" type="text" value="{{ old('custom_column_2') }}"></div>
                    <div class="calls-form-field"><label for="manualCustom3">Custom Column 3</label><input id="manualCustom3" name="custom_column_3" type="text" value="{{ old('custom_column_3') }}"></div>
                    <div class="calls-form-field"><label for="manualCustom4">Custom Column 4</label><input id="manualCustom4" name="custom_column_4" type="text" value="{{ old('custom_column_4') }}"></div>
                </div>
                <div class="calls-modal-actions"><button class="calls-modal-submit" id="callFormSubmit" type="submit">Add Call</button></div>
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
            const mobileInput = document.getElementById('manualMobile');
            const callForm = document.getElementById('addCallForm');
            const callFormMethod = document.getElementById('callFormMethod');
            const callFormSubmit = document.getElementById('callFormSubmit');
            const callFormTitle = document.getElementById('addCallModalTitle');
            const importModal = document.getElementById('importCallsModal');
            const openImport = document.getElementById('openCallsImport');
            const closeImport = document.getElementById('closeCallsImport');
            const changeImportFile = document.getElementById('changeCallsImportFile');
            const importFile = document.getElementById('callsImportFile');
            const importForm = document.getElementById('callsImportForm');
            const importSubmit = document.getElementById('submitCallsImport');
            const createCallUrl = @json(route('calls.store'));
            const callerOptions = @json($callers->map(fn ($caller) => ['id' => $caller->id, 'name' => $caller->name])->values());
            const rows = () => Array.from(document.querySelectorAll('#callsTableBody tr[data-search]'));
            const visibleRows = () => rows().filter(row => !row.hidden);

            document.addEventListener('click', function (event) {
                const editButton = event.target.closest('.edit-call-entry');
                if (!editButton) return;
                event.preventDefault();
                openEditForm(editButton.closest('tr'));
            });

            function setImportModal(open) {
                importModal.classList.toggle('show', open);
                importModal.setAttribute('aria-hidden', open ? 'false' : 'true');
                document.body.classList.toggle('calls-modal-open', open);
            }

            function chooseImportFile() {
                importFile.value = '';
                importFile.click();
            }

            if (openImport && changeImportFile && importFile && closeImport && importModal && importForm && importSubmit) {
                openImport.addEventListener('click', chooseImportFile);
                changeImportFile.addEventListener('click', chooseImportFile);
                importFile.addEventListener('change', function () {
                    if (!this.files.length) return;
                    const file = this.files[0];
                    document.getElementById('callsImportFileName').textContent = file.name;
                    document.getElementById('callsImportFileSize').textContent = (file.size / 1024).toFixed(1) + ' KB';
                    setImportModal(true);
                });
                closeImport.addEventListener('click', function () { setImportModal(false); });
                importModal.addEventListener('click', function (event) {
                    if (event.target === importModal) setImportModal(false);
                });
                importForm.addEventListener('submit', function () {
                    importSubmit.disabled = true;
                    importSubmit.textContent = 'Importing...';
                });
            }

            function setModal(open) {
                modal.classList.toggle('show', open);
                modal.setAttribute('aria-hidden', open ? 'false' : 'true');
                document.body.classList.toggle('calls-modal-open', open);
                if (open) document.getElementById('manualFirmName').focus();
            }

            function setSelectValue(element, value) {
                element.value = value || '';
                if (window.jQuery) jQuery(element).trigger('change');
            }

            function openCreateForm() {
                callForm.reset();
                callForm.action = createCallUrl;
                callFormMethod.disabled = true;
                document.getElementById('editEntryId').value = '';
                callFormTitle.textContent = 'Add Call Manually';
                callFormSubmit.textContent = 'Add Call';
                setSelectValue(pincode, '');
                setSelectValue(document.getElementById('manualCaller'), '');
                fillLocation();
                setModal(true);
            }

            function openEditForm(row) {
                if (!row) return;
                setModal(true);
                callForm.action = row.dataset.updateUrl;
                callFormMethod.disabled = false;
                document.getElementById('editEntryId').value = row.dataset.entryId;
                callFormTitle.textContent = 'Edit Call';
                callFormSubmit.textContent = 'Update Call';
                document.getElementById('manualFirmName').value = row.dataset.firm || '';
                document.getElementById('manualContactName').value = row.dataset.contact || '';
                mobileInput.value = row.dataset.mobile || '';
                document.getElementById('manualCustomerType').value = row.dataset.customerType || '';
                document.getElementById('manualAddress').value = row.dataset.address || '';
                document.getElementById('manualCustom1').value = row.dataset.custom1 || '';
                document.getElementById('manualCustom2').value = row.dataset.custom2 || '';
                document.getElementById('manualCustom3').value = row.dataset.custom3 || '';
                document.getElementById('manualCustom4').value = row.dataset.custom4 || '';
                setSelectValue(pincode, row.dataset.pincodeId);
                setSelectValue(document.getElementById('manualCaller'), row.dataset.currentCaller);
                fillLocation();
            }

            openModal.addEventListener('click', openCreateForm);
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
                    defaultOption.textContent = row.dataset.currentCallerName
                        ? 'Use bulk assignment (Current: ' + row.dataset.currentCallerName + ')'
                        : 'Use bulk assignment';
                    override.appendChild(defaultOption);
                    callerOptions.forEach(function (caller) {
                        const option = document.createElement('option');
                        option.value = caller.id;
                        option.textContent = caller.name;
                        override.appendChild(option);
                    });
                    override.value = '';

                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'entry_ids[]';
                    input.value = entryId;
                    inputsContainer.appendChild(input);
                    item.append(firm, mobile, override);
                    rowsContainer.appendChild(item);

                    if (window.jQuery && jQuery.fn.select2) {
                        jQuery(override).select2({
                            dropdownParent: jQuery('#assignCallsModal'),
                            placeholder: 'Search caller',
                            width: '100%'
                        });
                    }
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
                if (importModal.classList.contains('show')) setImportModal(false);
                else if (assignModal.classList.contains('show')) setAssignModal(false);
                else if (modal.classList.contains('show')) setModal(false);
            });

            function fillLocation() {
                const option = pincode.options[pincode.selectedIndex];
                document.getElementById('manualCity').value = option ? option.dataset.city || '' : '';
                document.getElementById('manualDistrict').value = option ? option.dataset.district || '' : '';
                document.getElementById('manualState').value = option ? option.dataset.state || '' : '';
            }

            pincode.addEventListener('change', fillLocation);
            pincode.value = @json((string) old('pincode_id'));
            document.getElementById('manualCaller').value = @json((string) old('assigned_user_id'));
            if (window.jQuery) jQuery(pincode).on('change', fillLocation);
            fillLocation();

            mobileInput.addEventListener('input', function () {
                this.value = this.value.replace(/\D/g, '').slice(0, 10);
            });

            @if($errors->addCall->any())
                setModal(true);
            @endif
            @if($errors->editCall->any())
                const invalidEditRow = document.querySelector('tr[data-entry-id="{{ (int) old('entry_id') }}"]');
                if (invalidEditRow) openEditForm(invalidEditRow);
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
            if (window.jQuery) {
                jQuery(status).on('change', filterRows);
                jQuery(caller).on('change', filterRows);
            }
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
                if (window.jQuery) jQuery('#bulkAssignedCaller').trigger('change');
                setAssignModal(true);
            @endif
        });
    </script>
</x-app-layout>
