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
        .call-ended-modal { position:fixed;inset:0;z-index:4000;display:none;align-items:center;justify-content:center;padding:20px;background:rgba(1,8,24,.78);backdrop-filter:blur(4px); }
        .call-ended-modal.show { display:flex; }
        .call-ended-dialog { width:min(520px,100%);overflow:hidden;border:1px solid rgba(77,122,221,.42);border-radius:18px;background:#0b1e47;box-shadow:0 28px 80px rgba(0,0,0,.45); }
        .call-ended-head { display:flex;align-items:flex-start;justify-content:space-between;padding:22px 24px 18px;border-bottom:1px solid rgba(85,126,218,.24); }
        .call-ended-head h2 { margin:0;color:#f5f8ff;font-size:24px;font-weight:800; }
        .call-ended-head p { margin:5px 0 0;color:#91a3ce;font-size:14px; }
        .call-ended-close { border:0;background:transparent;color:#91a3ce; }
        .call-ended-form { padding:22px 24px 24px; }
        .call-ended-field { margin-bottom:17px; }
        .call-ended-field label { display:block;margin-bottom:8px;color:#91a3ce;font-size:12px;font-weight:800;letter-spacing:.08em;text-transform:uppercase; }
        .call-ended-field select,.call-ended-field textarea { width:100%;border:1px solid rgba(85,126,218,.38);border-radius:11px;outline:0;background:#081a3e;color:#dce7ff;font-size:14px; }
        .call-ended-field select { height:45px;padding:0 13px; }
        .call-ended-field textarea { min-height:120px;padding:13px;resize:vertical; }
        .call-ended-save { width:100%;height:45px;border:0;border-radius:11px;background:linear-gradient(135deg,#2bd1e8,#62baf7);color:#061329;font-size:14px;font-weight:800; }
        .call-ended-error { display:none;margin-bottom:12px;color:#fca5a5;font-size:12px; }
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

    <div class="call-ended-modal" id="callEndedModal" role="dialog" aria-modal="true" aria-labelledby="callEndedTitle" aria-hidden="true">
        <div class="call-ended-dialog">
            <div class="call-ended-head">
                <div><h2 id="callEndedTitle">Call Ended</h2><p><span id="endedCustomerName"></span> · <span id="endedCallDuration">0:00</span></p></div>
                <button class="call-ended-close" id="closeCallEnded" type="button" aria-label="Close"><i class="material-icons">close</i></button>
            </div>
            <form class="call-ended-form" id="callFeedbackForm">
                <div class="call-ended-error" id="callFeedbackError"></div>
                <div class="call-ended-field">
                    <label for="callFeedbackStatus">Call Status *</label>
                    <select id="callFeedbackStatus" name="feedback_status_id" required>
                        <option value="">Select call status</option>
                        @foreach($feedbackStatuses as $feedbackStatus)
                            <option value="{{ $feedbackStatus->id }}">{{ $feedbackStatus->display_name ?: $feedbackStatus->status_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="call-ended-field"><label for="callFeedbackMessage">Notes *</label><textarea id="callFeedbackMessage" name="message" maxlength="1000" placeholder="What happened on this call?" required></textarea></div>
                <button class="call-ended-save" id="saveCallFeedback" type="submit">Save Call Record</button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const message = document.getElementById('customerCallMessage');
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const feedbackModal = document.getElementById('callEndedModal');
            const feedbackForm = document.getElementById('callFeedbackForm');
            const feedbackError = document.getElementById('callFeedbackError');
            const feedbackSave = document.getElementById('saveCallFeedback');
            let feedbackUrl = '';
            let activeCallButton = null;

            function showMessage(text, isError) {
                message.textContent = text;
                message.classList.toggle('error', isError);
                message.classList.add('show');
            }

            function formatDuration(seconds) {
                const minutes = Math.floor(seconds / 60);
                return minutes + ':' + String(seconds % 60).padStart(2, '0');
            }

            function showFeedback(call, duration) {
                feedbackUrl = call.feedback_url;
                document.getElementById('endedCustomerName').textContent = call.customer_name;
                document.getElementById('endedCallDuration').textContent = formatDuration(duration);
                feedbackForm.reset();
                feedbackError.style.display = 'none';
                feedbackModal.classList.add('show');
                feedbackModal.setAttribute('aria-hidden', 'false');
            }

            async function pollCall(call) {
                try {
                    const response = await fetch(call.status_url, { headers: { 'Accept': 'application/json' } });
                    const result = await response.json();
                    if (!response.ok || !result.success) throw new Error(result.message || 'Unable to check call status.');
                    if (result.data.completed) {
                        if (activeCallButton) activeCallButton.querySelector('.material-icons').textContent = 'call';
                        showMessage(result.data.duration > 0 ? 'Call completed.' : 'Call ended.', false);
                        if (result.data.requires_feedback) showFeedback(call, result.data.duration);
                        else if (activeCallButton) activeCallButton.disabled = false;
                        return;
                    }
                    window.setTimeout(function () { pollCall(call); }, 2000);
                } catch (error) {
                    showMessage(error.message || 'Unable to check call status.', true);
                    if (activeCallButton) activeCallButton.disabled = false;
                }
            }

            document.querySelectorAll('.customer-call-btn[data-call-url]').forEach(function (button) {
                button.addEventListener('click', async function () {
                    const icon = button.querySelector('.material-icons');
                    button.disabled = true;
                    activeCallButton = button;
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
                        pollCall(result.data);
                    } catch (error) {
                        showMessage(error.message || 'Unable to initiate call.', true);
                        button.disabled = false;
                        icon.textContent = 'call';
                    }
                });
            });

            document.getElementById('closeCallEnded').addEventListener('click', function () {
                feedbackModal.classList.remove('show');
                feedbackModal.setAttribute('aria-hidden', 'true');
                if (activeCallButton) activeCallButton.disabled = false;
            });
            feedbackForm.addEventListener('submit', async function (event) {
                event.preventDefault();
                feedbackSave.disabled = true;
                feedbackSave.textContent = 'Saving...';
                feedbackError.style.display = 'none';
                try {
                    const response = await fetch(feedbackUrl, {
                        method: 'POST',
                        headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
                        body: JSON.stringify({ feedback_status_id: document.getElementById('callFeedbackStatus').value, message: document.getElementById('callFeedbackMessage').value })
                    });
                    const result = await response.json();
                    if (!response.ok || !result.success) throw new Error(result.message || 'Unable to save call record.');
                    feedbackModal.classList.remove('show');
                    showMessage(result.message, false);
                    if (activeCallButton) activeCallButton.disabled = false;
                } catch (error) {
                    feedbackError.textContent = error.message || 'Unable to save call record.';
                    feedbackError.style.display = 'block';
                } finally {
                    feedbackSave.disabled = false;
                    feedbackSave.textContent = 'Save Call Record';
                }
            });
        });
    </script>
</x-app-layout>
