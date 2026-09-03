<x-app-layout>
    @php($totalRecords = $entries->total())
    <style>
        .customer-calling-page { color: #c5d2f3; }
        .customer-calling-breadcrumb { margin-bottom: 8px; color: #7185bd; font-size: 11px; font-weight: 800; letter-spacing: .22em; text-transform: uppercase; }
        .customer-calling-breadcrumb span { margin-left: 8px; color: #35ccef; }
        .customer-calling-heading { display:flex;align-items:center;justify-content:space-between;gap:20px;margin-bottom:18px; }
        .customer-calling-heading-main { display:flex;align-items:center;gap:12px; }
        .customer-calling-title { margin: 0; color: #f7f9ff; font-size: 26px; font-weight: 800;line-height:1.15; }
        .customer-calling-count { display: inline-flex; align-items: center; min-height: 31px; padding: 0 16px; border: 1px solid rgba(34, 211, 238, .48); border-radius: 999px; background: rgba(34, 211, 238, .08); color: #28d7f4; font-size: 13px; font-weight: 800; }
        .customer-calling-filter-trigger { display:inline-flex;align-items:center;justify-content:center;gap:9px;min-width:148px;height:44px;padding:0 20px;border:1px solid rgba(85,126,218,.38);border-radius:12px;background:rgba(7,20,49,.62);color:#c7d5f5;font-size:14px;font-weight:700; }
        .customer-calling-filter-trigger .material-icons { font-size:20px; }
        .customer-calling-filter-trigger.is-active::after { content:'';width:7px;height:7px;border-radius:50%;background:#2dd4ee;box-shadow:0 0 10px rgba(45,212,238,.8); }
        .customer-calling-create { display:inline-flex;align-items:center;justify-content:center;gap:8px;height:44px;padding:0 18px;border:0;border-radius:12px;background:linear-gradient(135deg,#2bd1e8,#438ff0);color:#061329;font-size:14px;font-weight:800; }
        .customer-calling-create .material-icons { font-size:20px; }
        .customer-calling-heading-actions { display:flex;align-items:center;gap:10px; }
        .customer-calling-tool { display:inline-flex;align-items:center;justify-content:center;width:44px;height:44px;border:1px solid rgba(85,126,218,.38);border-radius:12px;background:rgba(7,20,49,.62);color:#c7d5f5;text-decoration:none; }
        .customer-calling-tool:hover { border-color:rgba(34,211,238,.52);color:#2dd4ee; }
        .customer-calling-tool .material-icons { font-size:20px; }
        .customer-calling-filter-overlay { position:fixed;inset:0;z-index:4500;visibility:hidden;background:rgba(1,8,24,.68);opacity:0;transition:opacity .22s ease,visibility .22s ease;backdrop-filter:blur(3px); }
        .customer-calling-filter-overlay.show { visibility:visible;opacity:1; }
        .customer-calling-filter-drawer { position:absolute;top:0;right:0;display:flex;flex-direction:column;width:min(560px,100%);height:100%;border-left:1px solid rgba(85,126,218,.36);background:#081b42;box-shadow:-24px 0 70px rgba(0,0,0,.36);transform:translateX(100%);transition:transform .25s ease; }
        .customer-calling-filter-overlay.show .customer-calling-filter-drawer { transform:translateX(0); }
        .customer-calling-filter-head { display:flex;align-items:center;justify-content:space-between;gap:20px;min-height:104px;padding:22px 28px;border-bottom:1px solid rgba(85,126,218,.28); }
        .customer-calling-filter-heading { display:flex;align-items:center;gap:15px; }
        .customer-calling-filter-icon { display:inline-flex;align-items:center;justify-content:center;width:48px;height:48px;border:1px solid rgba(34,211,238,.45);border-radius:13px;background:rgba(34,211,238,.08);color:#2dd4ee; }
        .customer-calling-filter-heading strong { display:block;color:#f5f8ff;font-size:20px;font-weight:800; }
        .customer-calling-filter-heading small { display:block;margin-top:3px;color:#8395c4;font-size:13px; }
        .customer-calling-filter-close { display:inline-flex;align-items:center;justify-content:center;width:42px;height:42px;border:1px solid rgba(85,126,218,.36);border-radius:11px;background:transparent;color:#aebfe7; }
        .customer-calling-filters { display:flex;flex:1;flex-direction:column;min-height:0; }
        .customer-calling-filter-body { flex:1;overflow-y:auto;padding:28px; }
        .customer-calling-filter-grid { display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:20px 18px; }
        .customer-calling-filter-field.is-wide { grid-column:1/-1; }
        .customer-calling-filter-field label { display:block;margin-bottom:8px;color:#91a3ce;font-size:11px;font-weight:800;letter-spacing:.14em;text-transform:uppercase; }
        .customer-calling-filter-field input,.customer-calling-filter-field select { width:100%;height:46px;padding:0 14px;border:1px solid rgba(85,126,218,.38);border-radius:11px;outline:0;background:#071938;color:#d5e0fa;font-size:14px;box-shadow:none; }
        .customer-calling-filter-field input:focus,.customer-calling-filter-field select:focus { border-color:rgba(34,211,238,.62);box-shadow:0 0 0 3px rgba(34,211,238,.08); }
        .customer-calling-filter-field input::placeholder { color:#6f81ae; }
        .customer-calling-filter-drawer .select2-container { width:100% !important; }
        .customer-calling-filter-drawer .select2-container--default .select2-selection--single { height:46px;border:1px solid rgba(85,126,218,.38);border-radius:11px;background:#071938; }
        .customer-calling-filter-drawer .select2-container--default .select2-selection--single .select2-selection__rendered { padding-left:14px;color:#d5e0fa;font-size:14px;line-height:44px; }
        .customer-calling-filter-drawer .select2-container--default .select2-selection--single .select2-selection__arrow { height:44px;right:8px; }
        .customer-calling-filter-drawer .select2-container--open .select2-selection--single { border-color:rgba(34,211,238,.62);box-shadow:0 0 0 3px rgba(34,211,238,.08); }
        .customer-calling-status-dropdown { border:1px solid rgba(85,126,218,.42)!important;border-radius:11px!important;background:#071938!important;overflow:hidden; }
        .customer-calling-status-dropdown .select2-results__option { padding:10px 14px;color:#b9c8e9;font-size:14px; }
        .customer-calling-status-dropdown .select2-results__option--highlighted[aria-selected] { background:#123568!important;color:#fff!important; }
        .customer-calling-status-dropdown .select2-results__option[aria-selected=true] { background:rgba(34,211,238,.14);color:#39d5ed; }
        .customer-calling-filter-actions { display:grid;grid-template-columns:132px 1fr;gap:12px;padding:18px 28px 24px;border-top:1px solid rgba(85,126,218,.28);background:#071837; }
        .customer-calling-filter-submit,.customer-calling-filter-clear { display:inline-flex;align-items:center;justify-content:center;height:46px;padding:0 18px;border-radius:11px;font-size:14px;font-weight:800;text-decoration:none;white-space:nowrap; }
        .customer-calling-filter-submit { border:0;background:linear-gradient(135deg,#2bd1e8,#438ff0);color:#061329; }
        .customer-calling-filter-clear { border:1px solid rgba(85,126,218,.4);background:transparent;color:#b8c7e9; }
        .customer-calling-card { overflow: hidden; border: 1px solid rgba(85, 126, 218, .27); border-radius: 14px; background: rgba(7, 20, 49, .54); }
        .customer-calling-card-head { display: flex; align-items: center; gap: 12px; min-height: 67px; padding: 10px 18px; border-bottom: 1px solid rgba(85, 126, 218, .24); }
        .customer-calling-directory-icon { display: inline-flex; align-items: center; justify-content: center; width: 46px; height: 46px; border: 1px solid rgba(34, 211, 238, .5); border-radius: 12px; background: rgba(34, 211, 238, .08); color: #22d3ee; }
        .customer-calling-card-head strong { display: block; color: #f5f8ff; font-size: 16px; }
        .customer-calling-card-head small { display: block; margin-top: 3px; color: #7284b5; font-size: 13px; }
        .customer-calling-scroll { overflow-x:auto;overflow-y:hidden;scrollbar-width:thin;scrollbar-color:#17386f #04112d;overscroll-behavior-x:contain; }
        .customer-calling-scroll::-webkit-scrollbar { height:7px; }
        .customer-calling-scroll::-webkit-scrollbar-track { background:#04112d;border-radius:20px; }
        .customer-calling-scroll::-webkit-scrollbar-thumb { border:1px solid #04112d;border-radius:20px;background:#17386f; }
        .customer-calling-scroll::-webkit-scrollbar-thumb:hover { background:#24519a; }
        .customer-calling-scroll::-webkit-scrollbar-corner { background:#04112d; }
        .customer-calling-table { width: 100%; min-width: 1120px; border-collapse: collapse; }
        .customer-calling-table th { padding: 13px 14px; border-bottom: 1px solid rgba(85, 126, 218, .24); color: #8395c4; font-size: 11px; font-weight: 800; letter-spacing: .09em; text-align: left; text-transform: uppercase; white-space: nowrap; }
        .customer-calling-table td { height: 61px; padding: 12px 14px; border-bottom: 1px solid rgba(85, 126, 218, .18); color: #adbee6; font-size: 13px; vertical-align: middle; }
        .customer-calling-table tbody tr:last-child td { border-bottom: 0; }
        .customer-calling-table tbody tr:hover { background: rgba(30, 62, 119, .14); }
        .customer-call-btn { display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; padding: 0; border: 1px solid rgba(34, 211, 238, .45); border-radius: 10px; background: rgba(34, 211, 238, .08); color: #2dd4ee; }
        .customer-call-btn .material-icons { font-size: 19px; }
        .customer-call-btn:disabled { cursor: wait; opacity: .55; }
        .customer-call-btn.is-view-only:disabled { cursor:not-allowed;opacity:.5; }
        .customer-call-actions { display:flex;align-items:center;gap:6px;white-space:nowrap; }
        .customer-call-actions form { margin:0; }
        .customer-call-edit,.customer-call-delete { display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;padding:0;border:1px solid rgba(85,126,218,.34);border-radius:10px;background:rgba(8,25,59,.55);color:#9fb1dc; }
        .customer-call-edit:hover { border-color:rgba(34,211,238,.48);color:#35d2ed; }
        .customer-call-delete:hover { border-color:rgba(248,113,113,.52);color:#fb7185; }
        .customer-call-edit .material-icons,.customer-call-delete .material-icons { font-size:18px; }
        .customer-call-message { display: none; margin-bottom: 14px; padding: 11px 14px; border: 1px solid rgba(34, 211, 238, .35); border-radius: 10px; background: rgba(34, 211, 238, .08); color: #73def0; font-size: 13px; }
        .customer-call-message.show { display: block; }
        .customer-call-message.error { border-color: rgba(248, 113, 113, .4); background: rgba(248, 113, 113, .08); color: #fca5a5; }
        .customer-call-status { display:inline-flex;align-items:center;justify-content:center;min-width:90px;min-height:30px;padding:0 12px;border:1px solid rgba(34,211,238,.34);border-radius:999px;background:rgba(34,211,238,.06);color:#45d6ef;font-size:11px;font-weight:800;letter-spacing:.07em;line-height:1;text-transform:uppercase;white-space:nowrap;word-break:keep-all; }
        .customer-note-cell { min-width:190px;max-width:260px; }
        .customer-note-preview { display:block;overflow:hidden;color:#adbee6;line-height:1.45;text-overflow:ellipsis;white-space:nowrap; }
        .customer-note-view { margin-top:4px;padding:0;border:0;background:transparent;color:#35d2ed;font-size:11px;font-weight:800; }
        .customer-calling-empty { padding: 38px 20px !important; color: #7d8fbd !important; text-align: center; }
        .customer-calling-footer { display:flex;align-items:center;justify-content:space-between;min-height:58px;padding:12px 18px;border-top:1px solid rgba(85,126,218,.22);color:#8193c2;font-size:13px; }
        .customer-calling-pagination { display:flex;align-items:center;gap:6px; }
        .customer-calling-page-link { display:inline-flex;align-items:center;justify-content:center;min-width:34px;height:34px;padding:0 10px;border:1px solid rgba(85,126,218,.34);border-radius:9px;background:#081a3e;color:#91a3ce;font-size:12px;font-weight:800;text-decoration:none; }
        .customer-calling-page-link .material-icons { font-size:18px; }
        .customer-calling-page-link:hover { border-color:rgba(34,211,238,.5);color:#35d2ed;text-decoration:none; }
        .customer-calling-page-link.is-current { border-color:#2dd4ee;background:linear-gradient(135deg,#2bd1e8,#438ff0);color:#061329; }
        .customer-calling-page-link.is-disabled { cursor:not-allowed;opacity:.4; }
        .customer-create-modal { position:fixed;inset:0;z-index:4600;display:none;align-items:center;justify-content:center;padding:24px 16px;background:rgba(1,8,24,.76);backdrop-filter:blur(4px); }
        .customer-create-modal.show { display:flex; }
        .customer-create-dialog { width:min(760px,100%);max-height:calc(100vh - 40px);overflow-y:auto;border:1px solid rgba(77,122,221,.42);border-radius:17px;background:#0b1e47;box-shadow:0 28px 80px rgba(0,0,0,.45); }
        .customer-create-head { position:sticky;top:0;z-index:2;display:flex;align-items:center;justify-content:space-between;padding:18px 24px;border-bottom:1px solid rgba(85,126,218,.25);background:#0b1e47; }
        .customer-create-head h2 { margin:0;color:#f5f8ff;font-size:20px;font-weight:800; }
        .customer-create-close { display:inline-flex;align-items:center;justify-content:center;width:38px;height:38px;border:0;background:transparent;color:#91a3ce; }
        .customer-create-form { padding:22px 24px 24px; }
        .customer-create-grid { display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px 18px; }
        .customer-create-field label { display:block;margin-bottom:7px;color:#91a3ce;font-size:11px;font-weight:800;letter-spacing:.1em;text-transform:uppercase; }
        .customer-create-field input,.customer-create-field select { width:100%;height:44px;padding:0 13px;border:1px solid rgba(85,126,218,.38);border-radius:10px;outline:0;background:#071938;color:#d5e0fa;font-size:14px; }
        .customer-create-field input[readonly] { color:#8193c2;background:#0a2048; }
        .customer-create-modal .select2-container { width:100% !important; }
        .customer-create-modal .select2-container--default .select2-selection--single { height:44px;border:1px solid rgba(85,126,218,.38);border-radius:10px;background:#071938; }
        .customer-create-modal .select2-container--default .select2-selection--single .select2-selection__rendered { padding-left:13px;color:#d5e0fa;line-height:42px;font-size:14px; }
        .customer-create-modal .select2-container--default .select2-selection--single .select2-selection__arrow { height:42px;right:8px; }
        .customer-create-modal .select2-container--open .select2-selection--single { border-color:rgba(34,211,238,.62);box-shadow:0 0 0 3px rgba(34,211,238,.08); }
        .customer-create-modal .select2-container--open,.customer-create-modal .select2-dropdown { z-index:4701; }
        .customer-create-field-error { display:block;margin-top:5px;color:#fca5a5;font-size:11px; }
        .customer-create-actions { display:flex;justify-content:flex-end;gap:10px;margin-top:22px; }
        .customer-create-cancel,.customer-create-submit { height:44px;padding:0 20px;border-radius:10px;font-size:14px;font-weight:800; }
        .customer-create-cancel { border:1px solid rgba(85,126,218,.4);background:transparent;color:#b8c7e9; }
        .customer-create-submit { min-width:130px;border:0;background:linear-gradient(135deg,#2bd1e8,#438ff0);color:#061329; }
        .customer-import-body { padding:24px; }
        .customer-import-file { display:flex;align-items:center;gap:13px;padding:18px;border:1px dashed rgba(85,126,218,.5);border-radius:12px;background:#071938; }
        .customer-import-file .material-icons { color:#2dd4ee;font-size:28px; }
        .customer-import-file input { width:100%;color:#b8c7e9;font-size:13px; }
        .call-ended-modal { position:fixed;inset:0;z-index:4000;display:none;align-items:center;justify-content:center;padding:20px;background:rgba(1,8,24,.78);backdrop-filter:blur(4px); }
        .call-ended-modal.show { display:flex; }
        .call-ended-dialog { width:min(620px,100%);max-height:calc(100vh - 32px);overflow-y:auto;border:1px solid rgba(77,122,221,.42);border-radius:18px;background:#0b1e47;box-shadow:0 28px 80px rgba(0,0,0,.45); }
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
        .call-ended-field[hidden] { display:none; }
        .call-ended-save { width:100%;height:45px;border:0;border-radius:11px;background:linear-gradient(135deg,#2bd1e8,#62baf7);color:#061329;font-size:14px;font-weight:800; }
        .call-ended-error { display:none;margin-bottom:12px;color:#fca5a5;font-size:12px; }
        .call-customer-details { display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:10px;margin-bottom:18px;padding:14px;border:1px solid rgba(85,126,218,.25);border-radius:12px;background:rgba(7,25,56,.62); }
        .call-customer-detail { min-width:0; }
        .call-customer-detail span { display:block;margin-bottom:3px;color:#7184b4;font-size:10px;font-weight:800;letter-spacing:.08em;text-transform:uppercase; }
        .call-customer-detail strong { display:block;overflow:hidden;color:#dce7ff;font-size:13px;font-weight:600;text-overflow:ellipsis;white-space:nowrap; }
        .call-customer-detail input,.call-customer-detail select,.call-customer-detail textarea { width:100%;border:0;border-bottom:1px solid rgba(85,126,218,.35);outline:0;background:transparent;color:#dce7ff;font-size:13px;font-weight:600;box-shadow:none; }
        .call-customer-detail input,.call-customer-detail select { height:30px;padding:0 2px; }
        .call-customer-detail textarea { min-height:48px;padding:6px 2px;resize:vertical; }
        .call-customer-detail input:focus,.call-customer-detail select:focus,.call-customer-detail textarea:focus { border-bottom-color:#35d2ed; }
        .call-customer-detail input[readonly] { color:#8193c2;cursor:not-allowed; }
        .call-pincode-read { display:flex;align-items:center;justify-content:space-between;gap:8px;min-height:30px; }
        .call-pincode-read strong { flex:1; }
        .call-pincode-change { padding:3px 8px;border:1px solid rgba(34,211,238,.4);border-radius:7px;background:rgba(34,211,238,.08);color:#35d2ed;font-size:10px;font-weight:800;text-transform:uppercase; }
        .call-pincode-editor[hidden],.call-pincode-read[hidden] { display:none; }
        #callEndedModal .select2-container { width:100% !important; }
        #callEndedModal .select2-container--default .select2-selection--single { height:30px;border:0;border-bottom:1px solid rgba(85,126,218,.35);border-radius:0;background:transparent; }
        #callEndedModal .select2-container--default .select2-selection--single .select2-selection__rendered { padding:0 20px 0 2px;color:#dce7ff;font-size:13px;font-weight:600;line-height:29px; }
        #callEndedModal .select2-container--default .select2-selection--single .select2-selection__arrow { height:29px;right:0; }
        #callEndedModal .select2-container--open .select2-selection--single { border-bottom-color:#35d2ed; }
        #callEndedModal .select2-container--open,#callEndedModal .select2-dropdown { z-index:4100; }
        .call-customer-detail.is-wide { grid-column:1/-1; }
        .call-customer-detail.is-wide strong { overflow:visible;line-height:1.45;text-overflow:clip;white-space:normal;word-break:break-word; }
        .feedback-previous-notes { margin-bottom:18px; }
        .feedback-previous-notes-title { margin:0 0 9px;color:#91a3ce;font-size:12px;font-weight:800;letter-spacing:.08em;text-transform:uppercase; }
        .feedback-previous-notes-list { max-height:190px;overflow-y:auto;padding-right:3px;scrollbar-width:thin;scrollbar-color:#17386f #04112d; }
        .call-notes-dialog { width:min(600px,100%); }
        .call-notes-body { max-height:60vh;overflow-y:auto;padding:20px 24px 24px; }
        .call-note-item { margin-bottom:12px;padding:14px;border:1px solid rgba(85,126,218,.25);border-radius:11px;background:#081a3e; }
        .call-note-item:last-child { margin-bottom:0; }
        .call-note-meta { display:flex;justify-content:space-between;gap:12px;margin-bottom:7px;color:#7184b4;font-size:11px; }
        .call-note-meta strong { color:#35d2ed;text-transform:uppercase; }
        .call-note-text { margin:0;color:#d5e0fa;font-size:13px;line-height:1.55;white-space:pre-wrap;word-break:break-word; }
        .call-notes-empty { color:#8193c2;text-align:center; }
        #callEndedModal { padding:12px; }
        #callEndedModal .call-ended-dialog { display:flex;flex-direction:column;width:calc(100vw - 24px);height:calc(100vh - 24px);max-height:none;overflow:hidden;border-radius:16px;background:linear-gradient(145deg,#0b214e,#081a3e); }
        #callEndedModal .call-ended-head { flex:0 0 auto;padding:18px 24px;background:rgba(8,26,62,.92); }
        #callEndedModal .call-ended-head h2 { font-size:22px; }
        #callEndedModal .call-ended-form { display:flex;flex:1;flex-direction:column;min-height:0;padding:20px 24px 24px; }
        .call-workspace-grid { display:grid;flex:1;grid-template-columns:minmax(0,1.35fr) minmax(340px,.65fr);gap:20px;min-height:0; }
        .call-workspace-panel { min-height:0;overflow-y:auto;padding:18px;border:1px solid rgba(85,126,218,.28);border-radius:14px;background:rgba(5,18,44,.56);scrollbar-width:thin;scrollbar-color:#17386f #04112d; }
        .call-workspace-panel-title { display:flex;align-items:center;gap:9px;margin:0 0 15px;color:#f1f5ff;font-size:15px;font-weight:800; }
        .call-workspace-panel-title .material-icons { color:#35d2ed;font-size:20px; }
        .call-workspace-panel .call-customer-details { grid-template-columns:repeat(3,minmax(0,1fr));margin-bottom:20px;padding:0;border:0;background:transparent; }
        .call-workspace-panel .call-customer-detail { min-height:62px;padding:11px 12px;border:1px solid rgba(85,126,218,.2);border-radius:10px;background:rgba(8,26,62,.72); }
        .call-workspace-panel .feedback-previous-notes { margin-bottom:0; }
        .call-workspace-panel .feedback-previous-notes-list { max-height:none; }
        .call-feedback-panel { display:flex;flex-direction:column; }
        .call-feedback-panel .call-ended-save { margin-top:auto; }
        @media (max-width: 800px) { #callEndedModal { padding:0; } #callEndedModal .call-ended-dialog { width:100vw;height:100vh;border:0;border-radius:0; } #callEndedModal .call-ended-form { overflow-y:auto;padding:16px; } .call-workspace-grid { display:block; } .call-workspace-panel { margin-bottom:14px;overflow:visible;padding:14px; } .call-workspace-panel .call-customer-details { grid-template-columns:repeat(2,minmax(0,1fr)); } }
        @media (max-width: 640px) { .customer-calling-heading { align-items:flex-start; } .customer-calling-title { font-size:22px; } .customer-calling-filter-trigger { min-width:44px;width:44px;padding:0; } .customer-calling-filter-trigger span:not(.material-icons),.customer-calling-create span:not(.material-icons) { display:none; } .customer-calling-create { width:44px;padding:0; } .customer-calling-filter-head,.customer-calling-filter-body { padding-left:20px;padding-right:20px; } .customer-calling-filter-grid,.customer-create-grid { grid-template-columns:1fr; } .customer-calling-filter-field.is-wide { grid-column:auto; } .customer-calling-filter-actions { grid-template-columns:1fr 1.5fr;padding-left:20px;padding-right:20px; } .customer-calling-footer { align-items:flex-start;flex-direction:column;gap:10px; } .call-workspace-panel .call-customer-details { grid-template-columns:1fr; } .call-customer-detail.is-wide { grid-column:auto; } }
    </style>

    <div class="customer-calling-page">
        <div class="customer-calling-breadcrumb">Call Management <span>› &nbsp; Customer Calling</span></div>
        <div class="customer-calling-heading">
            <div class="customer-calling-heading-main">
                <h1 class="customer-calling-title">Customer Calling</h1>
                <span class="customer-calling-count">{{ $totalRecords }} {{ $totalRecords === 1 ? 'record' : 'records' }}</span>
            </div>
            <div class="customer-calling-heading-actions">
                <button class="customer-calling-filter-trigger {{ request()->hasAny(['search', 'status', 'from_date', 'to_date']) ? 'is-active' : '' }}" id="openCustomerCallingFilters" type="button">
                    <span class="material-icons">tune</span><span>Filters</span>
                </button>
                @if($canImportExport)
                    <button class="customer-calling-tool" id="openCustomerCallImport" type="button" title="Import Excel" aria-label="Import Excel"><span class="material-icons">cloud_upload</span></button>
                    <a class="customer-calling-tool" href="{{ route('calls.export') }}" title="Export Excel" aria-label="Export Excel"><span class="material-icons">cloud_download</span></a>
                @endif
                @if($canCreateCall)
                    <button class="customer-calling-create" id="openCustomerCreateCall" type="button"><span class="material-icons">add_circle_outline</span><span>Create Call</span></button>
                @endif
            </div>
        </div>
        <div class="customer-call-message" id="customerCallMessage" role="status"></div>

        <section class="customer-calling-card">
            <div class="customer-calling-card-head">
                <span class="customer-calling-directory-icon"><i class="material-icons">support_agent</i></span>
                <div><strong>My Calling Queue</strong><small>Calls assigned to you · Page {{ $entries->currentPage() }} of {{ $entries->lastPage() }}</small></div>
            </div>
            <div class="customer-calling-scroll">
                <table class="customer-calling-table">
                    <thead><tr><th>Call</th><th>Firm Name</th><th>Contact Person</th><th>Mobile</th><th>Customer Type</th><th>City</th><th>State</th><th>Status</th><th>Follow-up Date</th><th>Latest Note</th>@role('superadmin')<th>Assigned To</th>@endrole</tr></thead>
                    <tbody>
                        @forelse($entries as $entry)
                            <tr data-entry-id="{{ $entry->id }}" data-update-url="{{ route('calls.update', $entry) }}" data-firm="{{ $entry->firm_name }}" data-contact="{{ $entry->contact_person_name }}" data-mobile="{{ $entry->mobile_number }}" data-customer-type="{{ $entry->customer_type }}" data-address="{{ $entry->address }}" data-pincode-id="{{ $entry->pincode_id }}" data-pincode="{{ $entry->pincode }}" data-city="{{ $entry->city }}" data-district="{{ $entry->district }}" data-state="{{ $entry->state }}" data-caller-id="{{ $entry->assigned_user_id }}" data-custom-column-1="{{ $entry->custom_column_1 }}" data-custom-column-2="{{ $entry->custom_column_2 }}" data-custom-column-3="{{ $entry->custom_column_3 }}" data-custom-column-4="{{ $entry->custom_column_4 }}">
                                <td>
                                    <div class="customer-call-actions">
                                        @if((int) $entry->assigned_user_id === (int) auth()->id())
                                            <button class="customer-call-btn" type="button" data-call-url="{{ route('customer-calling.call', $entry) }}" title="Call {{ $entry->mobile_number }}" aria-label="Call {{ $entry->mobile_number }}"><i class="material-icons">call</i></button>
                                        @else
                                            <button class="customer-call-btn is-view-only" type="button" disabled title="Assigned to {{ optional($entry->assignedUser)->name }}"><i class="material-icons">visibility</i></button>
                                        @endif
                                        @if($canEditDelete)
                                            <button class="customer-call-edit edit-customer-call" type="button" title="Edit call" aria-label="Edit call"><i class="material-icons">edit</i></button>
                                            <form method="POST" action="{{ route('calls.destroy', $entry) }}" onsubmit="return confirm('Delete this call entry?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="customer-call-delete" type="submit" title="Delete call" aria-label="Delete call"><i class="material-icons">delete</i></button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                                <td>{{ $entry->firm_name }}</td><td>{{ $entry->contact_person_name }}</td><td>{{ $entry->mobile_number }}</td>
                                <td>{{ $entry->customer_type ?: '—' }}</td><td>{{ $entry->city ?: '—' }}</td><td>{{ $entry->state ?: '—' }}</td>
                                <td><span class="customer-call-status">{{ optional(optional($entry->latestCallLog)->feedbackStatus)->display_name ?: optional(optional($entry->latestCallLog)->feedbackStatus)->status_name ?: $entry->status }}</span></td>
                                <td>{{ $entry->follow_up_date ? $entry->follow_up_date->format('d M Y') : '—' }}</td>
                                <td class="customer-note-cell">
                                    @if(optional($entry->latestNotedCallLog)->remark)
                                        <span class="customer-note-preview">{{ $entry->latestNotedCallLog->remark }}</span>
                                        <button class="customer-note-view view-call-notes" type="button" data-notes-url="{{ route('customer-calling.notes', $entry) }}" data-customer-name="{{ $entry->contact_person_name ?: $entry->firm_name }}">View all notes</button>
                                    @else
                                        <span class="customer-note-preview">—</span>
                                    @endif
                                </td>
                                @role('superadmin')<td>{{ optional($entry->assignedUser)->name ?: '—' }}</td>@endrole
                            </tr>
                        @empty
                            <tr><td class="customer-calling-empty" colspan="{{ auth()->user()->hasRole('superadmin') ? 11 : 10 }}">No matching assigned calls found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <footer class="customer-calling-footer">
                <span>
                    @if($totalRecords)
                        Showing {{ $entries->firstItem() }}–{{ $entries->lastItem() }} of {{ $totalRecords }} calls
                    @else
                        Showing 0 calls
                    @endif
                </span>
                @if($entries->lastPage() > 1)
                    <nav class="customer-calling-pagination" aria-label="Customer calling pages">
                        @if($entries->onFirstPage())
                            <span class="customer-calling-page-link is-disabled"><i class="material-icons">chevron_left</i></span>
                        @else
                            <a class="customer-calling-page-link" href="{{ $entries->previousPageUrl() }}" rel="prev"><i class="material-icons">chevron_left</i></a>
                        @endif

                        @php($pageStart = max(1, $entries->currentPage() - 2))
                        @php($pageEnd = min($entries->lastPage(), $entries->currentPage() + 2))
                        @for($page = $pageStart; $page <= $pageEnd; $page++)
                            <a class="customer-calling-page-link {{ $page === $entries->currentPage() ? 'is-current' : '' }}" href="{{ $entries->url($page) }}" aria-current="{{ $page === $entries->currentPage() ? 'page' : 'false' }}">{{ $page }}</a>
                        @endfor

                        @if($entries->hasMorePages())
                            <a class="customer-calling-page-link" href="{{ $entries->nextPageUrl() }}" rel="next"><i class="material-icons">chevron_right</i></a>
                        @else
                            <span class="customer-calling-page-link is-disabled"><i class="material-icons">chevron_right</i></span>
                        @endif
                    </nav>
                @endif
            </footer>
        </section>
    </div>

    @if($canCreateCall || $canEditDelete)
        <div class="customer-create-modal" id="customerCreateCallModal" role="dialog" aria-modal="true" aria-labelledby="customerCreateCallTitle" aria-hidden="true">
            <div class="customer-create-dialog">
                <div class="customer-create-head">
                    <h2 id="customerCreateCallTitle">Create New Call</h2>
                    <button class="customer-create-close" id="closeCustomerCreateCall" type="button" aria-label="Close"><i class="material-icons">close</i></button>
                </div>
                <form class="customer-create-form" method="POST" action="{{ route('calls.store') }}">
                    @csrf
                    <input id="customerCallFormMethod" type="hidden" name="_method" value="PUT" disabled>
                    <input id="customerCallEntryId" type="hidden" name="entry_id" value="">
                    <input type="hidden" name="redirect_to" value="customer-calling">
                    <div class="customer-create-grid">
                        <div class="customer-create-field"><label for="createFirmName">Firm Name *</label><input id="createFirmName" name="firm_name" type="text" value="{{ old('firm_name') }}" maxlength="200" required>@error('firm_name', 'addCall')<span class="customer-create-field-error">{{ $message }}</span>@enderror</div>
                        <div class="customer-create-field"><label for="createContactName">Contact Person *</label><input id="createContactName" name="contact_person_name" type="text" value="{{ old('contact_person_name') }}" maxlength="200" required>@error('contact_person_name', 'addCall')<span class="customer-create-field-error">{{ $message }}</span>@enderror</div>
                        <div class="customer-create-field"><label for="createMobile">Mobile Number *</label><input id="createMobile" name="mobile_number" type="tel" inputmode="numeric" value="{{ old('mobile_number') }}" minlength="10" maxlength="10" pattern="[0-9]{10}" required>@error('mobile_number', 'addCall')<span class="customer-create-field-error">{{ $message }}</span>@enderror</div>
                        <div class="customer-create-field"><label for="createCustomerType">Customer Type</label><input id="createCustomerType" name="customer_type" type="text" value="{{ old('customer_type') }}" maxlength="100"></div>
                        <div class="customer-create-field"><label for="createAddress">Address</label><input id="createAddress" name="address" type="text" value="{{ old('address') }}" maxlength="500"></div>
                        <div class="customer-create-field">
                            <label for="createPincode">Pincode *</label>
                            <select class="select2" id="createPincode" name="pincode_id" required style="width:100%;">
                                <option value="">Select pincode</option>
                                @foreach($pincodes as $pincode)
                                    <option value="{{ $pincode->id }}" @selected((string) old('pincode_id') === (string) $pincode->id)>{{ $pincode->pincode }}</option>
                                @endforeach
                            </select>
                            @error('pincode_id', 'addCall')<span class="customer-create-field-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="customer-create-field"><label for="createCity">City</label><input id="createCity" type="text" readonly></div>
                        <div class="customer-create-field"><label for="createDistrict">District</label><input id="createDistrict" type="text" readonly></div>
                        <div class="customer-create-field"><label for="createState">State</label><input id="createState" type="text" readonly></div>
                        <div class="customer-create-field">
                            <label for="createCaller">Assign To *</label>
                            <select id="createCaller" name="assigned_user_id" required>
                                <option value="">Select caller</option>
                                @foreach($callers as $caller)<option value="{{ $caller->id }}" @selected((string) old('assigned_user_id') === (string) $caller->id)>{{ $caller->name }}</option>@endforeach
                            </select>
                            @error('assigned_user_id', 'addCall')<span class="customer-create-field-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="customer-create-field"><label for="createCustomColumn1">Point Column 1</label><input id="createCustomColumn1" name="custom_column_1" type="text" value="{{ old('custom_column_1') }}" maxlength="255">@error('custom_column_1', 'addCall')<span class="customer-create-field-error">{{ $message }}</span>@enderror</div>
                        <div class="customer-create-field"><label for="createCustomColumn2">Point Column 2</label><input id="createCustomColumn2" name="custom_column_2" type="text" value="{{ old('custom_column_2') }}" maxlength="255">@error('custom_column_2', 'addCall')<span class="customer-create-field-error">{{ $message }}</span>@enderror</div>
                        <div class="customer-create-field"><label for="createCustomColumn3">Point Column 3</label><input id="createCustomColumn3" name="custom_column_3" type="text" value="{{ old('custom_column_3') }}" maxlength="255">@error('custom_column_3', 'addCall')<span class="customer-create-field-error">{{ $message }}</span>@enderror</div>
                        <div class="customer-create-field"><label for="createCustomColumn4">Point Column 4</label><input id="createCustomColumn4" name="custom_column_4" type="text" value="{{ old('custom_column_4') }}" maxlength="255">@error('custom_column_4', 'addCall')<span class="customer-create-field-error">{{ $message }}</span>@enderror</div>
                    </div>
                    <div class="customer-create-actions">
                        <button class="customer-create-cancel" id="cancelCustomerCreateCall" type="button">Cancel</button>
                        <button class="customer-create-submit" id="customerCallFormSubmit" type="submit">Create Call</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if($canImportExport)
        <div class="customer-create-modal" id="customerCallImportModal" role="dialog" aria-modal="true" aria-labelledby="customerCallImportTitle" aria-hidden="true">
            <div class="customer-create-dialog" style="width:min(560px,100%);">
                <div class="customer-create-head">
                    <h2 id="customerCallImportTitle">Import Calls from Excel</h2>
                    <button class="customer-create-close" id="closeCustomerCallImport" type="button" aria-label="Close"><i class="material-icons">close</i></button>
                </div>
                <form class="customer-import-body" method="POST" action="{{ route('calls.import') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="redirect_to" value="customer-calling">
                    <div class="customer-import-file">
                        <i class="material-icons">description</i>
                        <input name="import_file" type="file" accept=".xlsx,.xls,.csv" required>
                    </div>
                    @error('import_file', 'importCall')<span class="customer-create-field-error">{{ $message }}</span>@enderror
                    <div class="customer-create-actions">
                        <button class="customer-create-cancel" id="cancelCustomerCallImport" type="button">Cancel</button>
                        <button class="customer-create-submit" type="submit">Import Calls</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <div class="customer-calling-filter-overlay" id="customerCallingFilterOverlay" aria-hidden="true">
        <aside class="customer-calling-filter-drawer" role="dialog" aria-modal="true" aria-labelledby="customerCallingFilterTitle">
            <div class="customer-calling-filter-head">
                <div class="customer-calling-filter-heading">
                    <span class="customer-calling-filter-icon"><i class="material-icons">tune</i></span>
                    <div><strong id="customerCallingFilterTitle">Advanced Filters</strong><small>Filter your calling queue</small></div>
                </div>
                <button class="customer-calling-filter-close" id="closeCustomerCallingFilters" type="button" aria-label="Close filters"><i class="material-icons">close</i></button>
            </div>
            <form class="customer-calling-filters" method="GET" action="{{ route('customer-calling.index') }}">
                <div class="customer-calling-filter-body">
                    <div class="customer-calling-filter-grid">
                        <div class="customer-calling-filter-field is-wide">
                            <label for="customerCallingSearch">Search Calls</label>
                            <input id="customerCallingSearch" type="search" name="search" value="{{ request('search') }}" placeholder="Search firm, contact person or mobile">
                        </div>
                        <div class="customer-calling-filter-field is-wide">
                            <label for="customerCallingStatus">Status</label>
                            <select class="select2" id="customerCallingStatus" name="status" style="width:100%;">
                                <option value="">All statuses</option>
                                <option value="assigned" @selected(request('status') === 'assigned')>Assigned</option>
                                @foreach($feedbackStatuses as $feedbackStatus)
                                    <option value="feedback:{{ $feedbackStatus->id }}" @selected(request('status') === 'feedback:'.$feedbackStatus->id)>{{ $feedbackStatus->display_name ?: $feedbackStatus->status_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="customer-calling-filter-field">
                            <label for="customerCallingFromDate">From Date</label>
                            <input id="customerCallingFromDate" type="date" name="from_date" value="{{ request('from_date') }}">
                        </div>
                        <div class="customer-calling-filter-field">
                            <label for="customerCallingToDate">To Date</label>
                            <input id="customerCallingToDate" type="date" name="to_date" value="{{ request('to_date') }}">
                        </div>
                    </div>
                </div>
                <div class="customer-calling-filter-actions">
                    <a class="customer-calling-filter-clear" href="{{ route('customer-calling.index') }}">Reset</a>
                    <button class="customer-calling-filter-submit" type="submit">Apply Filters</button>
                </div>
            </form>
        </aside>
    </div>

    <div class="call-ended-modal" id="callEndedModal" role="dialog" aria-modal="true" aria-labelledby="callEndedTitle" aria-hidden="true">
        <div class="call-ended-dialog">
            <div class="call-ended-head">
                <div><h2 id="callEndedTitle">Call in Progress</h2><p><span id="endedCustomerName"></span> · <span id="endedCallDuration">0:00</span></p></div>
                <button class="call-ended-close" id="closeCallEnded" type="button" aria-label="Close"><i class="material-icons">close</i></button>
            </div>
            <form class="call-ended-form" id="callFeedbackForm">
                <div class="call-ended-error" id="callFeedbackError"></div>
                <div class="call-workspace-grid">
                    <section class="call-workspace-panel">
                        <h3 class="call-workspace-panel-title"><i class="material-icons">business</i> Customer &amp; Project Details</h3>
                        <div class="call-customer-details">
                            <div class="call-customer-detail"><span>Project Name</span><strong id="feedbackProjectName">—</strong></div>
                            <div class="call-customer-detail"><span>Project ID</span><strong id="feedbackProjectId">—</strong></div>
                            <div class="call-customer-detail"><span>Parent Name</span><input id="feedbackParentName" name="parent_name" maxlength="255"></div>
                            <div class="call-customer-detail"><span>Firm</span><strong id="feedbackFirmName">—</strong></div>
                            <div class="call-customer-detail"><span>Contact Person</span><strong id="feedbackContactPerson">—</strong></div>
                            <div class="call-customer-detail"><span>Mobile</span><strong id="feedbackMobile">—</strong></div>
                            <div class="call-customer-detail"><span>Customer Type</span><strong id="feedbackCustomerType">—</strong></div>
                            <div class="call-customer-detail"><span>Assigned To</span><strong id="feedbackAssignedTo">—</strong></div>
                            <div class="call-customer-detail is-wide"><span>Address</span><textarea id="feedbackAddress" name="address" maxlength="1000"></textarea></div>
                            <div class="call-customer-detail">
                                <span>Pincode</span>
                                <div class="call-pincode-read" id="feedbackPincodeRead"><strong id="feedbackPincodeText">—</strong><button class="call-pincode-change" id="changeFeedbackPincode" type="button">Change</button></div>
                                <div class="call-pincode-editor" id="feedbackPincodeEditor" hidden><select id="feedbackPincode" name="pincode_id" required><option value="">Select pincode</option>@foreach($pincodes as $pincode)<option value="{{ $pincode->id }}">{{ $pincode->pincode }}</option>@endforeach</select></div>
                            </div>
                            <div class="call-customer-detail"><span>City</span><input id="feedbackCity" name="city" maxlength="150"></div>
                            <div class="call-customer-detail"><span>District</span><input id="feedbackDistrict" name="district" maxlength="150"></div>
                            <div class="call-customer-detail"><span>State</span><input id="feedbackState" name="state" maxlength="150"></div>
                            <div class="call-customer-detail"><span>Point Column 1</span><strong id="feedbackCustomColumn1">—</strong></div>
                            <div class="call-customer-detail"><span>Point Column 2</span><strong id="feedbackCustomColumn2">—</strong></div>
                            <div class="call-customer-detail"><span>Point Column 3</span><strong id="feedbackCustomColumn3">—</strong></div>
                            <div class="call-customer-detail"><span>Point Column 4</span><strong id="feedbackCustomColumn4">—</strong></div>
                        </div>
                        <section class="feedback-previous-notes">
                            <h3 class="feedback-previous-notes-title">Previous Call Notes</h3>
                            <div class="feedback-previous-notes-list" id="feedbackPreviousNotes"><p class="call-notes-empty">No previous notes.</p></div>
                        </section>
                    </section>
                    <section class="call-workspace-panel call-feedback-panel">
                        <h3 class="call-workspace-panel-title"><i class="material-icons">edit_note</i> Call Feedback</h3>
                        <div class="call-ended-field">
                            <label for="callFeedbackStatus">Call Status *</label>
                            <select id="callFeedbackStatus" name="feedback_status_id" required>
                                <option value="">Select call status</option>
                                @foreach($feedbackStatuses as $feedbackStatus)
                                    <option value="{{ $feedbackStatus->id }}" data-follow-up="{{ $feedbackStatus->is_follow_up ? '1' : '0' }}">{{ $feedbackStatus->display_name ?: $feedbackStatus->status_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="call-ended-field" id="callFollowUpDateField" hidden>
                            <label for="callFollowUpDate">Follow-up Date *</label>
                            <input id="callFollowUpDate" name="follow_up_date" type="date" min="{{ now()->format('Y-m-d') }}" style="width:100%;height:45px;padding:0 13px;border:1px solid rgba(85,126,218,.38);border-radius:11px;outline:0;background:#081a3e;color:#dce7ff;font-size:14px;">
                        </div>
                        <div class="call-ended-field"><label for="callFeedbackMessage">Notes *</label><textarea id="callFeedbackMessage" name="message" maxlength="1000" placeholder="Write the discussion and next action here..." required></textarea></div>
                        <button class="call-ended-save" id="saveCallFeedback" type="submit">Save Call Record</button>
                    </section>
                </div>
            </form>
        </div>
    </div>

    <div class="call-ended-modal" id="callNotesModal" role="dialog" aria-modal="true" aria-labelledby="callNotesTitle" aria-hidden="true">
        <div class="call-ended-dialog call-notes-dialog">
            <div class="call-ended-head">
                <div><h2 id="callNotesTitle">Call Notes</h2><p id="callNotesCustomer"></p></div>
                <button class="call-ended-close" id="closeCallNotes" type="button" aria-label="Close"><i class="material-icons">close</i></button>
            </div>
            <div class="call-notes-body" id="callNotesBody"><p class="call-notes-empty">Loading notes...</p></div>
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
            const feedbackStatus = document.getElementById('callFeedbackStatus');
            const feedbackPincode = document.getElementById('feedbackPincode');
            const feedbackPincodeRead = document.getElementById('feedbackPincodeRead');
            const feedbackPincodeEditor = document.getElementById('feedbackPincodeEditor');
            const feedbackPincodeText = document.getElementById('feedbackPincodeText');
            const changeFeedbackPincode = document.getElementById('changeFeedbackPincode');
            const followUpDateField = document.getElementById('callFollowUpDateField');
            const followUpDate = document.getElementById('callFollowUpDate');
            const notesModal = document.getElementById('callNotesModal');
            const notesBody = document.getElementById('callNotesBody');
            const filterOverlay = document.getElementById('customerCallingFilterOverlay');
            const openFilters = document.getElementById('openCustomerCallingFilters');
            const closeFilters = document.getElementById('closeCustomerCallingFilters');
            let feedbackUrl = '';
            let activeCallButton = null;

            function updateFollowUpDateVisibility() {
                const option = feedbackStatus.options[feedbackStatus.selectedIndex];
                const isFollowUp = option && option.dataset.followUp === '1';
                followUpDateField.hidden = !isFollowUp;
                followUpDate.required = isFollowUp;
                if (!isFollowUp) followUpDate.value = '';
            }

            feedbackStatus.addEventListener('change', updateFollowUpDateVisibility);

            changeFeedbackPincode.addEventListener('click', function () {
                feedbackPincodeRead.hidden = true;
                feedbackPincodeEditor.hidden = false;
                if (window.jQuery && jQuery.fn.select2) {
                    jQuery(feedbackPincode).select2('open');
                } else {
                    feedbackPincode.focus();
                }
            });

            feedbackPincode.addEventListener('change', async function () {
                if (!feedbackPincode.value) {
                    document.getElementById('feedbackCity').value = '';
                    document.getElementById('feedbackDistrict').value = '';
                    document.getElementById('feedbackState').value = '';
                    return;
                }

                try {
                    const response = await fetch(@json(url('getAddressData')), {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
                            'X-CSRF-TOKEN': token
                        },
                        body: new URLSearchParams({ pincode_id: feedbackPincode.value }).toString()
                    });
                    const location = await response.json();
                    if (!response.ok) throw new Error('Unable to load pincode location.');
                    document.getElementById('feedbackCity').value = location.city_name || '';
                    document.getElementById('feedbackDistrict').value = location.district_name || '';
                    document.getElementById('feedbackState').value = location.state_name || '';
                    const selectedOption = feedbackPincode.options[feedbackPincode.selectedIndex];
                    feedbackPincodeText.textContent = selectedOption ? selectedOption.text : '—';
                } catch (error) {
                    feedbackError.textContent = error.message || 'Unable to load pincode location.';
                    feedbackError.style.display = 'block';
                }
            });

            if (window.jQuery && jQuery.fn.select2) {
                jQuery(feedbackPincode).select2({
                    dropdownParent: jQuery(feedbackModal),
                    placeholder: 'Search pincode',
                    allowClear: true,
                    width: '100%',
                    language: { noResults: function () { return 'No matching pincode found'; } }
                });
            }

            const nextDay = new Date();
            nextDay.setHours(24, 0, 5, 0);
            window.setTimeout(function () { window.location.reload(); }, nextDay.getTime() - Date.now());

            if (window.jQuery && jQuery.fn.select2) {
                const statusSelect = jQuery('#customerCallingStatus');
                if (statusSelect.hasClass('select2-hidden-accessible')) statusSelect.select2('destroy');
                statusSelect.select2({
                    dropdownParent: jQuery('#customerCallingFilterOverlay'),
                    dropdownCssClass: 'customer-calling-status-dropdown',
                    minimumResultsForSearch: Infinity,
                    width: '100%'
                });
            }

            function setFiltersOpen(isOpen) {
                filterOverlay.classList.toggle('show', isOpen);
                filterOverlay.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
                document.body.style.overflow = isOpen ? 'hidden' : '';
                if (isOpen) window.setTimeout(function () { document.getElementById('customerCallingSearch').focus(); }, 250);
            }

            openFilters.addEventListener('click', function () { setFiltersOpen(true); });
            closeFilters.addEventListener('click', function () { setFiltersOpen(false); });
            filterOverlay.addEventListener('click', function (event) {
                if (event.target === filterOverlay) setFiltersOpen(false);
            });
            document.addEventListener('keydown', function (event) {
                if (event.key !== 'Escape') return;
                if (filterOverlay.classList.contains('show')) setFiltersOpen(false);
                else if (notesModal.classList.contains('show')) setNotesModalOpen(false);
                else if (feedbackModal.classList.contains('show')) setFeedbackModalOpen(false);
                @if($canCreateCall || $canEditDelete)
                    else if (createModal.classList.contains('show')) setCreateModalOpen(false);
                @endif
                @if($canImportExport)
                    else if (importModal.classList.contains('show')) setImportModalOpen(false);
                @endif
            });

            @if($canCreateCall || $canEditDelete)
                const createModal = document.getElementById('customerCreateCallModal');
                const createPincode = document.getElementById('createPincode');
                const customerCallForm = createModal.querySelector('form');
                const customerCallFormMethod = document.getElementById('customerCallFormMethod');
                const customerCallFormTitle = document.getElementById('customerCreateCallTitle');
                const customerCallFormSubmit = document.getElementById('customerCallFormSubmit');

                function setCreateModalOpen(isOpen) {
                    createModal.classList.toggle('show', isOpen);
                    createModal.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
                    document.body.style.overflow = isOpen ? 'hidden' : '';
                }

                async function fillCreateLocation() {
                    const pincodeId = createPincode.value;
                    const cityInput = document.getElementById('createCity');
                    const districtInput = document.getElementById('createDistrict');
                    const stateInput = document.getElementById('createState');
                    if (!pincodeId) {
                        cityInput.value = '';
                        districtInput.value = '';
                        stateInput.value = '';
                        return;
                    }
                    try {
                        const response = await fetch(@json(url('getAddressData')), {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
                                'X-CSRF-TOKEN': token
                            },
                            body: new URLSearchParams({ pincode_id: pincodeId }).toString()
                        });
                        const location = await response.json();
                        if (!response.ok) throw new Error('Unable to load pincode location.');
                        cityInput.value = location.city_name || '';
                        districtInput.value = location.district_name || '';
                        stateInput.value = location.state_name || '';
                    } catch (error) {
                        cityInput.value = '';
                        districtInput.value = '';
                        stateInput.value = '';
                    }
                }

                @if($canCreateCall)
                    document.getElementById('openCustomerCreateCall').addEventListener('click', function () {
                        customerCallForm.reset();
                        customerCallForm.action = @json(route('calls.store'));
                        customerCallFormMethod.disabled = true;
                        document.getElementById('customerCallEntryId').value = '';
                        customerCallFormTitle.textContent = 'Create New Call';
                        customerCallFormSubmit.textContent = 'Create Call';
                        createPincode.value = '';
                        if (window.jQuery && jQuery.fn.select2) jQuery(createPincode).trigger('change');
                        fillCreateLocation();
                        setCreateModalOpen(true);
                    });
                @endif
                document.getElementById('closeCustomerCreateCall').addEventListener('click', function () { setCreateModalOpen(false); });
                document.getElementById('cancelCustomerCreateCall').addEventListener('click', function () { setCreateModalOpen(false); });
                createModal.addEventListener('click', function (event) { if (event.target === createModal) setCreateModalOpen(false); });
                createPincode.addEventListener('change', fillCreateLocation);
                if (window.jQuery && jQuery.fn.select2) {
                    jQuery(createPincode).select2({
                        dropdownParent: jQuery('#customerCreateCallModal'),
                        placeholder: 'Search pincode',
                        allowClear: true,
                        width: '100%',
                        language: { noResults: function () { return 'No matching pincode found'; } }
                    }).on('select2:select select2:clear', fillCreateLocation);
                }
                document.getElementById('createMobile').addEventListener('input', function () {
                    this.value = this.value.replace(/\D/g, '').slice(0, 10);
                });
                fillCreateLocation();

                @if($canEditDelete)
                    document.querySelectorAll('.edit-customer-call').forEach(function (button) {
                        button.addEventListener('click', function () {
                            const row = button.closest('tr');
                            customerCallFormTitle.textContent = 'Edit Call';
                            customerCallFormSubmit.textContent = 'Update Call';
                            setCreateModalOpen(true);
                            customerCallForm.action = row.dataset.updateUrl;
                            customerCallFormMethod.disabled = false;
                            document.getElementById('customerCallEntryId').value = row.dataset.entryId || '';
                            document.getElementById('createFirmName').value = row.dataset.firm || '';
                            document.getElementById('createContactName').value = row.dataset.contact || '';
                            document.getElementById('createMobile').value = row.dataset.mobile || '';
                            document.getElementById('createCustomerType').value = row.dataset.customerType || '';
                            document.getElementById('createAddress').value = row.dataset.address || '';
                            document.getElementById('createCaller').value = row.dataset.callerId || '';
                            document.getElementById('createCustomColumn1').value = row.dataset.customColumn1 || '';
                            document.getElementById('createCustomColumn2').value = row.dataset.customColumn2 || '';
                            document.getElementById('createCustomColumn3').value = row.dataset.customColumn3 || '';
                            document.getElementById('createCustomColumn4').value = row.dataset.customColumn4 || '';
                            let selectedPincode = Array.from(createPincode.options).find(function (option) { return option.value === (row.dataset.pincodeId || ''); });
                            if (!selectedPincode && row.dataset.pincodeId) {
                                selectedPincode = new Option(row.dataset.pincode || '', row.dataset.pincodeId, true, true);
                                selectedPincode.dataset.city = row.dataset.city || '';
                                selectedPincode.dataset.district = row.dataset.district || '';
                                selectedPincode.dataset.state = row.dataset.state || '';
                                createPincode.add(selectedPincode);
                            }
                            createPincode.value = row.dataset.pincodeId || '';
                            if (window.jQuery && jQuery.fn.select2) jQuery(createPincode).trigger('change');
                            fillCreateLocation();
                        });
                    });
                @endif

                @if($errors->addCall->any())
                    setCreateModalOpen(true);
                @endif
                @if($errors->editCall->any())
                    const invalidEditRow = document.querySelector('tr[data-entry-id="{{ (int) old('entry_id') }}"]');
                    if (invalidEditRow) invalidEditRow.querySelector('.edit-customer-call')?.click();
                @endif
            @endif

            @if($canImportExport)
                const importModal = document.getElementById('customerCallImportModal');

                function setImportModalOpen(isOpen) {
                    importModal.classList.toggle('show', isOpen);
                    importModal.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
                    document.body.style.overflow = isOpen ? 'hidden' : '';
                }

                document.getElementById('openCustomerCallImport').addEventListener('click', function () { setImportModalOpen(true); });
                document.getElementById('closeCustomerCallImport').addEventListener('click', function () { setImportModalOpen(false); });
                document.getElementById('cancelCustomerCallImport').addEventListener('click', function () { setImportModalOpen(false); });
                importModal.addEventListener('click', function (event) { if (event.target === importModal) setImportModalOpen(false); });

                @if($errors->importCall->any())
                    setImportModalOpen(true);
                @endif
            @endif

            function showMessage(text, isError) {
                message.textContent = text;
                message.classList.toggle('error', isError);
                message.classList.add('show');
            }

            async function readJsonResponse(response, fallbackMessage) {
                const body = await response.text();
                try {
                    return JSON.parse(body);
                } catch (error) {
                    throw new Error(response.ok ? fallbackMessage : fallbackMessage + ' (HTTP ' + response.status + ')');
                }
            }

            function formatDuration(seconds) {
                const minutes = Math.floor(seconds / 60);
                return minutes + ':' + String(seconds % 60).padStart(2, '0');
            }

            function setFeedbackModalOpen(isOpen) {
                feedbackModal.classList.toggle('show', isOpen);
                feedbackModal.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
            }

            function renderPreviousNotes(notes) {
                const container = document.getElementById('feedbackPreviousNotes');
                if (!container) return;
                container.replaceChildren();
                if (!notes || !notes.length) {
                    const empty = document.createElement('p');
                    empty.className = 'call-notes-empty';
                    empty.textContent = 'No previous notes.';
                    container.appendChild(empty);
                    return;
                }
                notes.forEach(function (note) {
                    const item = document.createElement('article');
                    item.className = 'call-note-item';
                    const meta = document.createElement('div');
                    meta.className = 'call-note-meta';
                    const status = document.createElement('strong');
                    status.textContent = note.status || '—';
                    const date = document.createElement('span');
                    date.textContent = note.date || '—';
                    const text = document.createElement('p');
                    text.className = 'call-note-text';
                    text.textContent = note.note || '—';
                    meta.append(status, date);
                    item.append(meta, text);
                    container.appendChild(item);
                });
            }

            function setFeedbackText(id, value) {
                const element = document.getElementById(id);
                if (element) element.textContent = value || '—';
            }

            function setFeedbackValue(id, value) {
                const element = document.getElementById(id);
                if (element) element.value = value == null ? '' : value;
            }

            function showFeedback(call, duration, resetForm) {
                feedbackUrl = call.feedback_url;
                if (resetForm !== false) feedbackForm.reset();
                setFeedbackText('endedCustomerName', call.customer_name);
                setFeedbackText('endedCallDuration', formatDuration(duration));
                setFeedbackText('feedbackProjectName', call.project_name);
                setFeedbackText('feedbackProjectId', call.project_id);
                setFeedbackValue('feedbackParentName', call.parent_name);
                setFeedbackText('feedbackFirmName', call.firm_name);
                setFeedbackText('feedbackContactPerson', call.contact_person);
                setFeedbackText('feedbackMobile', call.mobile);
                setFeedbackText('feedbackCustomerType', call.customer_type);
                setFeedbackText('feedbackAssignedTo', call.assigned_to);
                setFeedbackValue('feedbackAddress', call.address);
                feedbackPincodeText.textContent = call.pincode || '—';
                feedbackPincodeRead.hidden = false;
                feedbackPincodeEditor.hidden = true;
                let popupPincodeId = call.pincode_id || '';
                let popupPincodeOption = Array.from(feedbackPincode.options).find(function (option) {
                    return option.value === String(popupPincodeId);
                });
                if (!popupPincodeOption && call.pincode) {
                    popupPincodeOption = Array.from(feedbackPincode.options).find(function (option) {
                        return option.text.trim() === String(call.pincode).trim();
                    });
                    if (popupPincodeOption) popupPincodeId = popupPincodeOption.value;
                }
                if (!popupPincodeId && call.pincode) popupPincodeId = String(call.pincode).trim();
                // Imported calls already contain a resolved system pincode. Keep
                // it visible even when the current user's Create Customer list
                // no longer includes that assignment.
                if (!popupPincodeOption && popupPincodeId) {
                    popupPincodeOption = new Option(call.pincode || popupPincodeId, popupPincodeId, true, true);
                    feedbackPincode.add(popupPincodeOption);
                }
                setFeedbackValue('feedbackPincode', popupPincodeId);
                if (window.jQuery && jQuery.fn.select2) {
                    jQuery(feedbackPincode).val(String(popupPincodeId)).trigger('change.select2');
                }
                setFeedbackValue('feedbackCity', call.city);
                setFeedbackValue('feedbackDistrict', call.district);
                setFeedbackValue('feedbackState', call.state);
                setFeedbackText('feedbackCustomColumn1', call.custom_column_1);
                setFeedbackText('feedbackCustomColumn2', call.custom_column_2);
                setFeedbackText('feedbackCustomColumn3', call.custom_column_3);
                setFeedbackText('feedbackCustomColumn4', call.custom_column_4);
                renderPreviousNotes(call.previous_notes || []);
                setFeedbackText('callEndedTitle', 'Call in Progress');
                if (resetForm !== false) {
                    updateFollowUpDateVisibility();
                }
                feedbackError.style.display = 'none';
                setFeedbackModalOpen(true);
            }

            async function pollCall(call) {
                try {
                    const response = await fetch(call.status_url, { headers: { 'Accept': 'application/json' } });
                    const result = await response.json();
                    if (!response.ok || !result.success) throw new Error(result.message || 'Unable to check call status.');
                    if (result.data.completed) {
                        if (activeCallButton) activeCallButton.querySelector('.material-icons').textContent = 'call';
                        showMessage(result.data.duration > 0 ? 'Call completed.' : 'Call ended.', false);
                        document.getElementById('endedCallDuration').textContent = formatDuration(result.data.duration);
                        document.getElementById('callEndedTitle').textContent = 'Call Ended';
                        if (result.data.requires_feedback && !feedbackModal.classList.contains('show')) showFeedback(call, result.data.duration, false);
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
                        const result = await readJsonResponse(response, 'Unable to initiate call. Please contact the administrator');
                        if (!response.ok || !result.success) throw new Error(result.message || 'Unable to initiate call.');
                        showMessage(result.message, false);
                        try {
                            showFeedback(result.data, 0, true);
                        } catch (popupError) {
                            console.error('Unable to open call workspace:', popupError);
                        }
                        pollCall(result.data);
                    } catch (error) {
                        showMessage(error.message || 'Unable to initiate call.', true);
                        button.disabled = false;
                        icon.textContent = 'call';
                    }
                });
            });

            document.getElementById('closeCallEnded').addEventListener('click', function () {
                setFeedbackModalOpen(false);
                if (activeCallButton) activeCallButton.disabled = false;
            });
            feedbackModal.addEventListener('click', function (event) {
                if (event.target === feedbackModal) setFeedbackModalOpen(false);
            });

            function setNotesModalOpen(isOpen) {
                notesModal.classList.toggle('show', isOpen);
                notesModal.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
            }

            function renderCallNotes(notes) {
                notesBody.replaceChildren();
                if (!notes.length) {
                    const empty = document.createElement('p');
                    empty.className = 'call-notes-empty';
                    empty.textContent = 'No notes have been saved yet.';
                    notesBody.appendChild(empty);
                    return;
                }
                notes.forEach(function (note) {
                    const item = document.createElement('article');
                    item.className = 'call-note-item';
                    const meta = document.createElement('div');
                    meta.className = 'call-note-meta';
                    const status = document.createElement('strong');
                    status.textContent = note.status || '—';
                    const date = document.createElement('span');
                    date.textContent = note.date || '—';
                    const text = document.createElement('p');
                    text.className = 'call-note-text';
                    text.textContent = note.note || '—';
                    meta.append(status, date);
                    item.append(meta, text);
                    notesBody.appendChild(item);
                });
            }

            document.querySelectorAll('.view-call-notes').forEach(function (button) {
                button.addEventListener('click', async function () {
                    document.getElementById('callNotesCustomer').textContent = button.dataset.customerName || '';
                    notesBody.innerHTML = '<p class="call-notes-empty">Loading notes...</p>';
                    setNotesModalOpen(true);
                    try {
                        const response = await fetch(button.dataset.notesUrl, { headers: { 'Accept': 'application/json' } });
                        const result = await response.json();
                        if (!response.ok || !result.success) throw new Error(result.message || 'Unable to load notes.');
                        renderCallNotes(result.data || []);
                    } catch (error) {
                        notesBody.replaceChildren();
                        const errorMessage = document.createElement('p');
                        errorMessage.className = 'call-notes-empty';
                        errorMessage.textContent = error.message || 'Unable to load notes.';
                        notesBody.appendChild(errorMessage);
                    }
                });
            });
            document.getElementById('closeCallNotes').addEventListener('click', function () { setNotesModalOpen(false); });
            notesModal.addEventListener('click', function (event) { if (event.target === notesModal) setNotesModalOpen(false); });
            feedbackForm.addEventListener('submit', async function (event) {
                event.preventDefault();
                feedbackSave.disabled = true;
                feedbackSave.textContent = 'Saving...';
                feedbackError.style.display = 'none';
                try {
                    const response = await fetch(feedbackUrl, {
                        method: 'POST',
                        headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
                        body: JSON.stringify(Object.fromEntries(new FormData(feedbackForm).entries()))
                    });
                    const result = await response.json();
                    if (!response.ok || !result.success) throw new Error(result.message || 'Unable to save call record.');
                    setFeedbackModalOpen(false);
                    showMessage(result.message, false);

                    // Give immediate feedback when a completed call leaves the queue.
                    // Reload afterwards so counts, pagination and the next queued row
                    // are all refreshed from the server.
                    if (result.data && result.data.queue_removed && activeCallButton) {
                        const completedRow = activeCallButton.closest('tr');
                        if (completedRow) completedRow.remove();
                    }
                    window.location.reload();
                    return;
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
