<x-app-layout>
    @php
        $customers = [
            ['id' => 1, 'firm' => 'Shree Traders', 'contact' => 'Ramesh Patel', 'mobile' => '9812345001', 'type' => 'Retailer', 'city' => 'Surat', 'state' => 'GJ'],
            ['id' => 2, 'firm' => 'Om Enterprises', 'contact' => 'Suresh Nair', 'mobile' => '9812345002', 'type' => 'Dealer', 'city' => 'Vadodara', 'state' => 'GJ'],
            ['id' => 3, 'firm' => 'Balaji Agro', 'contact' => 'Vikas Rao', 'mobile' => '9812345003', 'type' => 'Distributor', 'city' => 'Nagpur', 'state' => 'MH'],
            ['id' => 4, 'firm' => 'Jai Hind Store', 'contact' => 'Anita Shah', 'mobile' => '9812345004', 'type' => 'Retailer', 'city' => 'Indore', 'state' => 'MP'],
            ['id' => 5, 'firm' => 'Krishna Mart', 'contact' => 'Deepak Joshi', 'mobile' => '9812345005', 'type' => 'Retailer', 'city' => 'Bhopal', 'state' => 'MP'],
        ];
    @endphp

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
        .calls-add-btn { height: 45px; gap: 8px; padding: 0 20px; border-color: transparent; background: linear-gradient(135deg, #2bd1e8, #4398f5); color: #061329; font-weight: 700; }
        .calls-toolbar button[disabled] { cursor: default; opacity: 1; }
        .calls-icon-btn .material-icons, .calls-filter-btn .material-icons, .calls-add-btn .material-icons { font-size: 20px; }
        .calls-filter-panel { display: none; grid-template-columns: minmax(230px, 1.2fr) repeat(2, minmax(170px, .65fr)); gap: 12px; margin: 0 0 14px; padding: 14px; border: 1px solid rgba(85, 126, 218, .2); border-radius: 12px; background: rgba(9, 24, 58, .45); }
        .calls-filter-panel.show { display: grid; }
        .calls-search { position: relative; }
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
        .calls-assign { display: inline-flex; align-items: center; min-height: 38px; padding: 0 15px; border: 1px solid rgba(85, 126, 218, .3); border-radius: 10px; background: transparent; color: #b7c6eb; font-size: 13px; font-weight: 700; }
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
        .calls-modal-dialog { width: min(720px, 100%); max-height: calc(100vh - 56px); overflow-y: auto; border: 1px solid rgba(77, 122, 221, .4); border-radius: 18px; background: #0c214c; box-shadow: 0 26px 80px rgba(0, 0, 0, .38); }
        .calls-modal-head { display: flex; align-items: center; justify-content: space-between; min-height: 76px; padding: 18px 28px; border-bottom: 1px solid rgba(85, 126, 218, .24); }
        .calls-modal-head h2 { margin: 0; color: #f3f7ff; font-size: 24px; font-weight: 800; }
        .calls-modal-close { display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; padding: 0; border: 0; background: transparent; color: #8fa1d0; }
        .calls-modal-close .material-icons { font-size: 27px; }
        .calls-modal-form { padding: 28px; }
        .calls-form-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 18px 20px; }
        .calls-form-field label { display: block; margin: 0 0 8px; color: #8fa1d0; font-size: 12px; font-weight: 800; letter-spacing: .08em; text-transform: uppercase; }
        .calls-form-field input, .calls-form-field select { width: 100%; height: 48px; padding: 0 14px; border: 1px solid rgba(85, 126, 218, .35); border-radius: 12px; outline: 0; background: rgba(5, 18, 47, .46); color: #d8e3ff; box-shadow: none; }
        .calls-form-field input:focus, .calls-form-field select:focus { border-color: rgba(34, 211, 238, .7); }
        .calls-modal-actions { display: flex; justify-content: flex-end; margin-top: 26px; }
        .calls-modal-submit { min-width: 145px; height: 46px; border: 0; border-radius: 12px; background: linear-gradient(135deg, #2bd1e8, #62baf7); color: #061329; font-size: 15px; font-weight: 800; }
        body.calls-modal-open { overflow: hidden; }
        @media (max-width: 991px) {
            .calls-page-head { align-items: flex-start; flex-direction: column; }
            .calls-toolbar { flex-wrap: wrap; }
            .calls-filter-panel { grid-template-columns: 1fr; }
        }
        @media (max-width: 575px) {
            .calls-form-grid { grid-template-columns: 1fr; }
            .calls-modal-head, .calls-modal-form { padding-left: 20px; padding-right: 20px; }
        }
    </style>

    <div class="calls-page">
        <header class="calls-page-head">
            <div>
                <div class="calls-breadcrumb">Call Management <span>› &nbsp; Calls</span></div>
                <div class="calls-heading">
                    <h1>Calls</h1>
                    <span class="calls-count" id="callsRecordCount">{{ count($customers) }} records</span>
                </div>
            </div>
            <div class="calls-toolbar">
                <button class="calls-icon-btn" type="button" disabled title="Import Excel will be enabled later"><i class="material-icons">cloud_upload</i></button>
                <button class="calls-icon-btn" type="button" disabled title="Report will be enabled later"><i class="material-icons">description</i></button>
                <button class="calls-filter-btn" id="callsFilterToggle" type="button"><i class="material-icons">tune</i>Filters</button>
                <button class="calls-add-btn" id="openAddCallModal" type="button"><i class="material-icons">add_circle_outline</i>Add Manually</button>
            </div>
        </header>

        <div class="calls-filter-panel" id="callsFilterPanel">
            <div class="calls-search"><i class="material-icons">search</i><input class="calls-control" id="callsSearch" type="search" placeholder="Search firm or mobile" autocomplete="off"></div>
            <select class="calls-control" id="callsStatus" aria-label="Filter by status"><option value="">All Statuses</option><option value="pending">Pending</option></select>
            <select class="calls-control" id="callsCaller" aria-label="Filter by caller"><option value="">All Callers</option><option value="unassigned">Unassigned</option></select>
        </div>

        <section class="calls-card" aria-labelledby="call-directory-title">
            <div class="calls-card-head">
                <div class="calls-directory">
                    <span class="calls-directory-icon"><i class="material-icons">phone_in_talk</i></span>
                    <div><strong id="call-directory-title">Call Directory</strong><small>Static calling list · Page 1 of 1</small></div>
                </div>
                <button class="calls-assign" type="button" disabled>Assign Selected (&nbsp;<span id="selectedCount">0</span>&nbsp;)</button>
            </div>

            <div class="calls-table-scroll">
                <table class="calls-table">
                    <thead><tr><th><input class="calls-check" id="selectAllCalls" type="checkbox" aria-label="Select all customers"></th><th>Firm Name</th><th>Contact Person</th><th>Mobile</th><th>Cust. Type</th><th>City</th><th>State</th><th>Status</th><th>Caller</th><th>Others</th></tr></thead>
                    <tbody id="callsTableBody">
                        @foreach($customers as $customer)
                            <tr data-search="{{ strtolower($customer['firm'] . ' ' . $customer['mobile'] . ' ' . $customer['contact']) }}" data-status="pending" data-caller="unassigned">
                                <td><input class="calls-check calls-row-check" type="checkbox" value="{{ $customer['id'] }}" aria-label="Select {{ $customer['firm'] }}"></td>
                                <td>{{ $customer['firm'] }}</td><td>{{ $customer['contact'] }}</td><td>{{ $customer['mobile'] }}</td><td>{{ $customer['type'] }}</td><td>{{ $customer['city'] }}</td><td>{{ $customer['state'] }}</td>
                                <td><span class="calls-status">Pending</span></td><td>—</td>
                                <td><button class="calls-history" type="button" disabled title="Call history will be enabled later"><i class="material-icons">history</i>History</button></td>
                            </tr>
                        @endforeach
                        <tr id="callsNoResults" hidden><td class="calls-empty" colspan="10">No matching records found.</td></tr>
                    </tbody>
                </table>
            </div>

            <footer class="calls-footer">
                <span id="callsShowing">Showing 1–{{ count($customers) }} of {{ count($customers) }} calls</span>
                <div class="calls-pagination" aria-label="Static pagination">
                    <button class="calls-page-btn" type="button" disabled><i class="material-icons">chevron_left</i></button>
                    <button class="calls-page-btn active" type="button" disabled>1</button>
                    <button class="calls-page-btn" type="button" disabled><i class="material-icons">chevron_right</i></button>
                </div>
            </footer>
        </section>
    </div>

    <div class="calls-modal" id="addCallModal" role="dialog" aria-modal="true" aria-labelledby="addCallModalTitle" aria-hidden="true">
        <div class="calls-modal-dialog">
            <div class="calls-modal-head">
                <h2 id="addCallModalTitle">Add Call Manually</h2>
                <button class="calls-modal-close" id="closeAddCallModal" type="button" aria-label="Close modal"><i class="material-icons">close</i></button>
            </div>
            <form class="calls-modal-form" id="addCallForm">
                <div class="calls-form-grid">
                    <div class="calls-form-field"><label for="manualFirmName">Firm Name</label><input id="manualFirmName" type="text"></div>
                    <div class="calls-form-field"><label for="manualContactName">Contact Person Name</label><input id="manualContactName" type="text"></div>
                    <div class="calls-form-field"><label for="manualMobile">Mobile Number</label><input id="manualMobile" type="tel"></div>
                    <div class="calls-form-field"><label for="manualCustomerType">Customer Type</label><input id="manualCustomerType" type="text"></div>
                    <div class="calls-form-field"><label for="manualAddress">Address</label><input id="manualAddress" type="text"></div>
                    <div class="calls-form-field"><label for="manualCity">City</label><input id="manualCity" type="text"></div>
                    <div class="calls-form-field"><label for="manualDistrict">District</label><input id="manualDistrict" type="text"></div>
                    <div class="calls-form-field"><label for="manualState">State</label><input id="manualState" type="text"></div>
                    <div class="calls-form-field"><label for="manualPin">Pin</label><input id="manualPin" type="text"></div>
                    <div class="calls-form-field">
                        <label for="manualCaller">Caller Assignment</label>
                        <select id="manualCaller"><option value="">Select caller</option><option>Sales Executive 1</option><option>Sales Executive 2</option></select>
                    </div>
                    <div class="calls-form-field"><label for="manualCustom1">Custom Column 1</label><input id="manualCustom1" type="text"></div>
                    <div class="calls-form-field"><label for="manualCustom2">Custom Column 2</label><input id="manualCustom2" type="text"></div>
                    <div class="calls-form-field"><label for="manualCustom3">Custom Column 3</label><input id="manualCustom3" type="text"></div>
                    <div class="calls-form-field"><label for="manualCustom4">Custom Column 4</label><input id="manualCustom4" type="text"></div>
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
            const rows = () => Array.from(document.querySelectorAll('#callsTableBody tr[data-search]'));
            const visibleRows = () => rows().filter(row => !row.hidden);

            document.getElementById('callsFilterToggle').addEventListener('click', function () {
                document.getElementById('callsFilterPanel').classList.toggle('show');
            });

            function setModal(open) {
                modal.classList.toggle('show', open);
                modal.setAttribute('aria-hidden', open ? 'false' : 'true');
                document.body.classList.toggle('calls-modal-open', open);
                if (open) document.getElementById('manualFirmName').focus();
            }

            openModal.addEventListener('click', () => setModal(true));
            closeModal.addEventListener('click', () => setModal(false));
            modal.addEventListener('click', event => { if (event.target === modal) setModal(false); });
            document.addEventListener('keydown', event => { if (event.key === 'Escape' && modal.classList.contains('show')) setModal(false); });
            document.getElementById('addCallForm').addEventListener('submit', function (event) {
                event.preventDefault();
                setModal(false);
            });

            function updateCount() { selectedCount.textContent = document.querySelectorAll('.calls-row-check:checked').length; }
            function filterRows() {
                const term = search.value.trim().toLowerCase();
                let visible = 0;
                rows().forEach(function (row) {
                    row.hidden = !((!term || row.dataset.search.includes(term)) && (!status.value || row.dataset.status === status.value) && (!caller.value || row.dataset.caller === caller.value));
                    if (!row.hidden) visible++;
                });
                noResults.hidden = visible !== 0;
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
        });
    </script>
</x-app-layout>
