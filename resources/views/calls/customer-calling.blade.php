<x-app-layout>
    @php($totalRecords = $entries->count())
    <style>
        .customer-calling-page { color: #c5d2f3; }
        .customer-calling-breadcrumb { margin-bottom: 8px; color: #7185bd; font-size: 11px; font-weight: 800; letter-spacing: .22em; text-transform: uppercase; }
        .customer-calling-breadcrumb span { margin-left: 8px; color: #35ccef; }
        .customer-calling-heading { display: flex; align-items: center; gap: 12px; margin-bottom: 18px; }
        .customer-calling-title { margin: 0; color: #f7f9ff; font-size: 25px; font-weight: 800; }
        .customer-calling-count { display: inline-flex; align-items: center; min-height: 31px; padding: 0 16px; border: 1px solid rgba(34, 211, 238, .48); border-radius: 999px; background: rgba(34, 211, 238, .08); color: #28d7f4; font-size: 13px; font-weight: 800; }
        .customer-calling-card { overflow: hidden; border: 1px solid rgba(85, 126, 218, .27); border-radius: 14px; background: rgba(7, 20, 49, .54); }
        .customer-calling-card-head { display: flex; align-items: center; gap: 12px; min-height: 67px; padding: 10px 18px; border-bottom: 1px solid rgba(85, 126, 218, .24); }
        .customer-calling-directory-icon { display: inline-flex; align-items: center; justify-content: center; width: 46px; height: 46px; border: 1px solid rgba(34, 211, 238, .5); border-radius: 12px; background: rgba(34, 211, 238, .08); color: #22d3ee; }
        .customer-calling-card-head strong { display: block; color: #f5f8ff; font-size: 16px; }
        .customer-calling-card-head small { display: block; margin-top: 3px; color: #7284b5; font-size: 13px; }
        .customer-calling-scroll { overflow-x: auto; }
        .customer-calling-table { width: 100%; min-width: 900px; border-collapse: collapse; }
        .customer-calling-table th { padding: 13px 14px; border-bottom: 1px solid rgba(85, 126, 218, .24); color: #8395c4; font-size: 11px; font-weight: 800; letter-spacing: .09em; text-align: left; text-transform: uppercase; white-space: nowrap; }
        .customer-calling-table td { height: 61px; padding: 12px 14px; border-bottom: 1px solid rgba(85, 126, 218, .18); color: #adbee6; font-size: 13px; vertical-align: middle; }
        .customer-calling-table tbody tr:last-child td { border-bottom: 0; }
        .customer-calling-table tbody tr:hover { background: rgba(30, 62, 119, .14); }
        .customer-call-btn { display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; padding: 0; border: 1px solid rgba(34, 211, 238, .45); border-radius: 10px; background: rgba(34, 211, 238, .08); color: #2dd4ee; }
        .customer-call-btn .material-icons { font-size: 19px; }
        .customer-call-btn:disabled { cursor: wait; opacity: .55; }
        .customer-call-message { display: none; margin-bottom: 14px; padding: 11px 14px; border: 1px solid rgba(34, 211, 238, .35); border-radius: 10px; background: rgba(34, 211, 238, .08); color: #73def0; font-size: 13px; }
        .customer-call-message.show { display: block; }
        .customer-call-message.error { border-color: rgba(248, 113, 113, .4); background: rgba(248, 113, 113, .08); color: #fca5a5; }
        .customer-call-status { display: inline-flex; align-items: center; justify-content: center; min-width: 90px; min-height: 30px; padding: 0 12px; border: 1px solid rgba(34, 211, 238, .34); border-radius: 999px; background: rgba(34, 211, 238, .06); color: #45d6ef; font-size: 11px; font-weight: 800; letter-spacing: .07em; text-transform: uppercase; }
        .customer-calling-empty { padding: 38px 20px !important; color: #7d8fbd !important; text-align: center; }
    </style>

    <div class="customer-calling-page">
        <div class="customer-calling-breadcrumb">Call Management <span>› &nbsp; Customer Calling</span></div>
        <div class="customer-calling-heading">
            <h1 class="customer-calling-title">Customer Calling</h1>
            <span class="customer-calling-count">{{ $totalRecords }} {{ $totalRecords === 1 ? 'record' : 'records' }}</span>
        </div>
        <div class="customer-call-message" id="customerCallMessage" role="status"></div>

        <section class="customer-calling-card">
            <div class="customer-calling-card-head">
                <span class="customer-calling-directory-icon"><i class="material-icons">support_agent</i></span>
                <div><strong>My Calling Queue</strong><small>Calls assigned to you</small></div>
            </div>
            <div class="customer-calling-scroll">
                <table class="customer-calling-table">
                    <thead><tr><th>Call</th><th>Firm Name</th><th>Contact Person</th><th>Mobile</th><th>Customer Type</th><th>City</th><th>State</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse($entries as $entry)
                            <tr>
                                <td><button class="customer-call-btn" type="button" data-call-url="{{ route('customer-calling.call', $entry) }}" title="Call {{ $entry->mobile_number }}" aria-label="Call {{ $entry->mobile_number }}"><i class="material-icons">call</i></button></td>
                                <td>{{ $entry->firm_name }}</td><td>{{ $entry->contact_person_name }}</td><td>{{ $entry->mobile_number }}</td>
                                <td>{{ $entry->customer_type ?: '—' }}</td><td>{{ $entry->city ?: '—' }}</td><td>{{ $entry->state ?: '—' }}</td>
                                <td><span class="customer-call-status">{{ $entry->status }}</span></td>
                            </tr>
                        @empty
                            <tr><td class="customer-calling-empty" colspan="8">No calls are assigned to you.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const message = document.getElementById('customerCallMessage');
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            function showMessage(text, isError) {
                message.textContent = text;
                message.classList.toggle('error', isError);
                message.classList.add('show');
            }

            document.querySelectorAll('.customer-call-btn[data-call-url]').forEach(function (button) {
                button.addEventListener('click', async function () {
                    const icon = button.querySelector('.material-icons');
                    button.disabled = true;
                    icon.textContent = 'hourglass_top';
                    showMessage('Connecting with Plivo...', false);

                    try {
                        const response = await fetch(button.dataset.callUrl, {
                            method: 'POST',
                            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': token }
                        });
                        const result = await response.json();
                        if (!response.ok || !result.success) throw new Error(result.message || 'Unable to initiate call.');
                        showMessage(result.message, false);
                    } catch (error) {
                        showMessage(error.message || 'Unable to initiate call.', true);
                    } finally {
                        button.disabled = false;
                        icon.textContent = 'call';
                    }
                });
            });
        });
    </script>
</x-app-layout>
