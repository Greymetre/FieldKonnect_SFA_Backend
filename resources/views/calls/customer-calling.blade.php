<x-app-layout>
    <style>
        .customer-calling-page { color: #c5d2f3; }
        .customer-calling-breadcrumb { margin-bottom: 8px; color: #7185bd; font-size: 11px; font-weight: 800; letter-spacing: .22em; text-transform: uppercase; }
        .customer-calling-breadcrumb span { margin-left: 8px; color: #35ccef; }
        .customer-calling-title { margin: 0 0 18px; color: #f7f9ff; font-size: 25px; font-weight: 800; }
        .customer-calling-card { min-height: 320px; border: 1px solid rgba(85, 126, 218, .27); border-radius: 14px; background: rgba(7, 20, 49, .54); }
        .customer-calling-card-head { display: flex; align-items: center; gap: 12px; min-height: 67px; padding: 10px 18px; border-bottom: 1px solid rgba(85, 126, 218, .24); }
        .customer-calling-icon { display: inline-flex; align-items: center; justify-content: center; width: 46px; height: 46px; border: 1px solid rgba(34, 211, 238, .5); border-radius: 12px; background: rgba(34, 211, 238, .08); color: #22d3ee; }
        .customer-calling-icon .material-icons { font-size: 22px; }
        .customer-calling-card-head strong { display: block; color: #f5f8ff; font-size: 16px; }
        .customer-calling-card-head small { display: block; margin-top: 3px; color: #7284b5; font-size: 13px; }
    </style>

    <div class="customer-calling-page">
        <div class="customer-calling-breadcrumb">Call Management <span>› &nbsp; Customer Calling</span></div>
        <h1 class="customer-calling-title">Customer Calling</h1>

        <section class="customer-calling-card">
            <div class="customer-calling-card-head">
                <span class="customer-calling-icon"><i class="material-icons">support_agent</i></span>
                <div>
                    <strong>Customer Calling</strong>
                    <small>Customer calling workspace</small>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
