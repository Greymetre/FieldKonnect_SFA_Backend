<x-app-layout>
    <style>
        .calls-page { color: #dbe7ff; }
        .calls-tabs { display: flex; gap: 12px; margin-bottom: 28px; }
        .calls-tab { padding: 11px 24px; border: 0; border-radius: 14px; background: transparent; color: #9eb0db; font-size: 15px; font-weight: 700; }
        .calls-tab.active { background: linear-gradient(135deg, #22d3ee, #69c9fa); color: #061329; box-shadow: 0 8px 22px rgba(34, 211, 238, .14); }
        .calls-workspace { padding: 2px 2px 24px; }
        .calls-title-row, .calls-filter-row { display: flex; align-items: center; justify-content: space-between; gap: 18px; }
        .calls-title { margin: 0; color: #f7f9ff; font-size: 26px; font-weight: 800; }
        .calls-actions { display: flex; align-items: center; gap: 12px; }
        .calls-btn { display: inline-flex; align-items: center; justify-content: center; gap: 9px; min-height: 46px; padding: 0 24px; border: 1px solid rgba(72, 119, 221, .38); border-radius: 13px; background: rgba(7, 21, 51, .68); color: #e3ecff; font-weight: 700; box-shadow: none; }
        .calls-btn-primary { border-color: transparent; background: linear-gradient(135deg, #22d3ee, #70c9fa); color: #061329; }
        .calls-btn[disabled], .calls-history[disabled] { cursor: default; opacity: 1; }
        .calls-filter-row { margin: 24px 0 18px; }
        .calls-filters { display: grid; grid-template-columns: minmax(250px, 1.25fr) minmax(180px, .8fr) minmax(180px, .8fr); gap: 14px; width: min(860px, 100%); }
        .calls-control-wrap label { display: block; margin: 0 0 8px; color: #7f91c2; font-size: 12px; font-weight: 800; letter-spacing: .12em; text-transform: uppercase; }
        .calls-control { width: 100%; height: 48px; padding: 0 16px; border: 1px solid rgba(72, 119, 221, .38) !important; border-radius: 12px; outline: 0; background: rgba(6, 19, 47, .72) !important; color: #dbe7ff !important; box-shadow: none !important; }
        .calls-search { position: relative; align-self: end; }
        .calls-search .material-icons { position: absolute; top: 13px; left: 15px; color: #8497c8; font-size: 21px; }
        .calls-search input { padding-left: 46px; }
        .calls-control::placeholder { color: #7081ae; }
        .calls-table-shell { overflow: hidden; border: 1px solid rgba(72, 119, 221, .3); border-radius: 16px; background: rgba(8, 24, 57, .66); }
        .calls-table-scroll { overflow-x: auto; }
        .calls-table { width: 100%; min-width: 1100px; margin: 0; border-collapse: collapse; }
        .calls-table th { padding: 20px 15px; border-bottom: 1px solid rgba(72, 119, 221, .3); color: #7f91c2; font-size: 12px; font-weight: 800; letter-spacing: .08em; text-align: left; text-transform: uppercase; white-space: nowrap; }
        .calls-table td { padding: 19px 15px; border-bottom: 1px solid rgba(72, 119, 221, .2); color: #aebfe8; font-size: 14px; vertical-align: middle; }
        .calls-table tbody tr:nth-child(even) { background: rgba(24, 53, 105, .3); }
        .calls-table tbody tr:last-child td { border-bottom: 0; }
        .calls-check { width: 17px; height: 17px; accent-color: #22d3ee; }
        .calls-status { display: inline-flex; padding: 7px 14px; border: 1px solid rgba(72, 119, 221, .42); border-radius: 999px; color: #aebfe8; font-size: 12px; font-weight: 800; letter-spacing: .04em; text-transform: uppercase; }
        .calls-history { display: inline-flex; align-items: center; gap: 7px; padding: 0; border: 0; background: transparent; color: #aebfe8; font-weight: 700; }
        .calls-history .material-icons { font-size: 19px; }
        .calls-empty { padding: 46px 20px !important; color: #7f91c2 !important; text-align: center; }
        @media (max-width: 991px) {
            .calls-title-row, .calls-filter-row { align-items: stretch; flex-direction: column; }
            .calls-filters { grid-template-columns: 1fr; }
            .calls-actions { flex-wrap: wrap; }
        }
    </style>

    <div class="calls-page">
        <div class="calls-tabs" role="tablist" aria-label="Call management sections">
            <button class="calls-tab active" type="button">Calls</button>
            <button class="calls-tab" type="button" disabled title="Report will be enabled later">Report</button>
        </div>

        <section class="calls-workspace" aria-labelledby="calls-page-title">
            <div class="calls-title-row">
                <h1 class="calls-title" id="calls-page-title">Call Management</h1>
                <div class="calls-actions">
                    <button class="calls-btn calls-btn-primary" type="button" disabled title="Excel import will be enabled later"><i class="material-icons">upload</i>Import Excel</button>
                    <button class="calls-btn" type="button" disabled title="Manual entry will be enabled later"><i class="material-icons">add</i>Add Manually</button>
                </div>
            </div>

            <div class="calls-filter-row">
                <div class="calls-filters">
                    <div class="calls-search"><i class="material-icons">search</i><input class="calls-control" id="callsSearch" type="search" placeholder="Search firm or mobile" autocomplete="off"></div>
                    <div class="calls-control-wrap"><label for="callsStatus">Status</label><select class="calls-control" id="callsStatus"><option value="">All</option><option value="pending">Pending</option></select></div>
                    <div class="calls-control-wrap"><label for="callsCaller">Caller</label><select class="calls-control" id="callsCaller"><option value="">All</option><option value="unassigned">Unassigned</option></select></div>
                </div>
                <button class="calls-btn" id="assignSelected" type="button" disabled>Assign Selected (&nbsp;<span id="selectedCount">0</span>&nbsp;)</button>
            </div>

            <div class="calls-table-shell">
                <div class="calls-table-scroll">
                    <table class="calls-table">
                        <thead><tr><th><input class="calls-check" id="selectAllCalls" type="checkbox" aria-label="Select all customers"></th><th>Firm Name</th><th>Contact Person</th><th>Mobile</th><th>Cust. Type</th><th>City</th><th>State</th><th>Status</th><th>Caller</th><th></th></tr></thead>
                        <tbody id="callsTableBody">
                            @php
                                $customers = [
                                    ['id' => 1, 'firm' => 'Shree Traders', 'contact' => 'Ramesh Patel', 'mobile' => '9812345001', 'type' => 'Retailer', 'city' => 'Surat', 'state' => 'GJ'],
                                    ['id' => 2, 'firm' => 'Om Enterprises', 'contact' => 'Suresh Nair', 'mobile' => '9812345002', 'type' => 'Dealer', 'city' => 'Vadodara', 'state' => 'GJ'],
                                    ['id' => 3, 'firm' => 'Balaji Agro', 'contact' => 'Vikas Rao', 'mobile' => '9812345003', 'type' => 'Distributor', 'city' => 'Nagpur', 'state' => 'MH'],
                                    ['id' => 4, 'firm' => 'Jai Hind Store', 'contact' => 'Anita Shah', 'mobile' => '9812345004', 'type' => 'Retailer', 'city' => 'Indore', 'state' => 'MP'],
                                    ['id' => 5, 'firm' => 'Krishna Mart', 'contact' => 'Deepak Joshi', 'mobile' => '9812345005', 'type' => 'Retailer', 'city' => 'Bhopal', 'state' => 'MP'],
                                ];
                            @endphp
                            @foreach($customers as $customer)
                                <tr data-search="{{ strtolower($customer['firm'] . ' ' . $customer['mobile'] . ' ' . $customer['contact']) }}" data-status="pending" data-caller="unassigned">
                                    <td><input class="calls-check calls-row-check" type="checkbox" value="{{ $customer['id'] }}" aria-label="Select {{ $customer['firm'] }}"></td>
                                    <td>{{ $customer['firm'] }}</td><td>{{ $customer['contact'] }}</td><td>{{ $customer['mobile'] }}</td><td>{{ $customer['type'] }}</td><td>{{ $customer['city'] }}</td><td>{{ $customer['state'] }}</td>
                                    <td><span class="calls-status">Pending</span></td><td>—</td>
                                    <td><button class="calls-history" type="button" disabled title="Call history will be enabled later"><i class="material-icons">history</i>History</button></td>
                                </tr>
                            @endforeach
                            <tr class="calls-empty-row" id="callsNoResults" hidden><td class="calls-empty" colspan="10">No matching customers found.</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const search = document.getElementById('callsSearch');
            const status = document.getElementById('callsStatus');
            const caller = document.getElementById('callsCaller');
            const selectAll = document.getElementById('selectAllCalls');
            const count = document.getElementById('selectedCount');
            const noResults = document.getElementById('callsNoResults');
            const rows = () => Array.from(document.querySelectorAll('#callsTableBody tr[data-search]'));
            const visibleRows = () => rows().filter(row => !row.hidden);

            function updateCount() { count.textContent = document.querySelectorAll('.calls-row-check:checked').length; }
            function filterRows() {
                const term = search.value.trim().toLowerCase();
                let visibleCount = 0;
                rows().forEach(function (row) {
                    row.hidden = !((!term || row.dataset.search.includes(term)) && (!status.value || row.dataset.status === status.value) && (!caller.value || row.dataset.caller === caller.value));
                    if (!row.hidden) visibleCount++;
                });
                noResults.hidden = visibleCount !== 0 || rows().length === 0;
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
