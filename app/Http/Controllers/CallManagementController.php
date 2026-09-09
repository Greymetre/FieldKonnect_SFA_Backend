<?php

namespace App\Http\Controllers;

use App\Exports\CallManagementEntryExport;
use App\Exports\CustomerCallHistoryExport;
use App\Imports\CallManagementEntryImport;
use App\Jobs\TranscribeCallRecording;
use App\Models\CallManagementEntry;
use App\Models\CallLog;
use App\Models\ClientCallLog;
use App\Models\Pincode;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class CallManagementController extends Controller
{
    public function dashboard()
    {
        abort_if(Gate::denies('call_management_dashboard_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user = auth()->user();
        $canViewAllAgents = $user->hasRole('superadmin') || $user->hasRole('Admin');
        $calls = CallLog::query()->whereNotNull('call_management_entry_id');
        $pendingCalls = CallManagementEntry::query()->where('status', 'assigned');

        if (! $canViewAllAgents) {
            $calls->where('user_id', $user->id);
            $pendingCalls->where('assigned_user_id', $user->id);
        }

        $totalDial = (clone $calls)->count();
        $connectedQuery = (clone $calls)->where(function ($query) {
            $query->where('duration', '>', 0)
                ->orWhere('status', 1)
                ->orWhereNotNull('recording_url');
        });
        $connected = $connectedQuery->count();
        $totalTalkTime = (int) (clone $connectedQuery)->sum('duration');
        $todayCalls = (clone $calls)->whereDate('started_at', today())->count();

        $agentQuery = User::permission('call_management_access')
            ->where('active', 'Y')
            ->where('call_management', 1);
        if (! $canViewAllAgents) {
            $agentQuery->whereKey($user->id);
        }

        $agents = $agentQuery->orderBy('name')->get(['id', 'name']);
        $agentsOnCall = (clone $calls)
            ->whereNotNull('answered_at')
            ->whereNull('completed_at')
            ->where('updated_at', '>=', now()->subHours(2))
            ->distinct()
            ->count('user_id');
        $agentCounts = (clone $calls)
            ->selectRaw('user_id, COUNT(*) as total')
            ->groupBy('user_id')
            ->pluck('total', 'user_id');
        $agentCallCounts = $agents->map(function (User $agent) use ($agentCounts) {
            $agent->setAttribute('dashboard_calls_count', (int) ($agentCounts[$agent->id] ?? 0));

            return $agent;
        })->sortByDesc('dashboard_calls_count')->values();

        $trendStart = today()->subDays(6);
        $trendCounts = (clone $calls)
            ->whereDate('started_at', '>=', $trendStart)
            ->selectRaw('DATE(started_at) as call_date, COUNT(*) as total')
            ->groupBy(DB::raw('DATE(started_at)'))
            ->pluck('total', 'call_date');
        $trend = collect(range(0, 6))->map(function ($offset) use ($trendStart, $trendCounts) {
            $date = $trendStart->copy()->addDays($offset);

            return ['label' => $date->format('d M'), 'total' => (int) ($trendCounts[$date->toDateString()] ?? 0)];
        });

        return view('calls.dashboard', [
            'totalDial' => $totalDial,
            'connected' => $connected,
            'notConnected' => max(0, $totalDial - $connected),
            'connectRate' => $totalDial ? round(($connected / $totalDial) * 100, 1) : 0,
            'liveAgents' => $agents->count(),
            'agentsOnCall' => $agentsOnCall,
            'totalTalkTime' => $this->formatDashboardDuration($totalTalkTime),
            'todayCalls' => $todayCalls,
            'pendingCalls' => $pendingCalls->count(),
            'agentCallCounts' => $agentCallCounts,
            'trend' => $trend,
            'canViewAllAgents' => $canViewAllAgents,
        ]);
    }

    public function onCallCount()
    {
        abort_if(Gate::denies('call_management_dashboard_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $calls = CallLog::query()
            ->whereNotNull('call_management_entry_id')
            ->whereNotNull('answered_at')
            ->whereNull('completed_at')
            ->where('updated_at', '>=', now()->subHours(2));

        if (! auth()->user()->hasRole('superadmin') && ! auth()->user()->hasRole('Admin')) {
            $calls->where('user_id', auth()->id());
        }

        return response()->json(['count' => $calls->distinct()->count('user_id')]);
    }

    public function plivoBalance(Request $request)
    {
        abort_unless($request->user()->hasRole('superadmin'), Response::HTTP_FORBIDDEN);

        $authId = config('services.plivo.auth_id');
        $authToken = config('services.plivo.auth_token');
        $usdToInrRate = (float) config('services.plivo.usd_to_inr_rate', 80.00);

        if (empty($authId) || empty($authToken) || $usdToInrRate <= 0) {
            return response()->json(['message' => 'Plivo balance configuration is incomplete.'], Response::HTTP_SERVICE_UNAVAILABLE);
        }

        try {
            $usdBalance = Cache::remember('plivo.account.balance', now()->addMinute(), function () use ($authId, $authToken) {
                $response = Http::withBasicAuth($authId, $authToken)
                    ->acceptJson()
                    ->connectTimeout(5)
                    ->timeout(10)
                    ->get("https://api.plivo.com/v1/Account/{$authId}/");

                $response->throw();

                $cashCredits = $response->json('cash_credits');
                if (! is_numeric($cashCredits)) {
                    throw new \RuntimeException('Plivo returned an invalid cash_credits value.');
                }

                return (float) $cashCredits;
            });

            return response()->json([
                'balance' => number_format($usdBalance * $usdToInrRate, 2, '.', ''),
                'currency' => 'INR',
                'symbol' => '₹',
                'exchange_rate' => $usdToInrRate,
            ]);
        } catch (Throwable $exception) {
            Log::warning('Unable to fetch the Plivo account balance.', ['message' => $exception->getMessage()]);

            return response()->json(['message' => 'Plivo balance is temporarily unavailable.'], Response::HTTP_BAD_GATEWAY);
        }
    }

    public function customerCalling(Request $request)
    {
        abort_if(Gate::denies('call_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $canCreateCall = auth()->user()->can('call_management_create');
        $canImportExport = auth()->user()->can('call_management_import_export');
        $canEditDelete = auth()->user()->can('call_management_edit_delete');
        $canViewAllAgents = auth()->user()->hasRole('superadmin') || auth()->user()->hasRole('Admin');
        $filterAgents = $canViewAllAgents
            ? User::permission('call_management_access')
                ->where('active', 'Y')
                ->where('call_management', 1)
                ->orderBy('name')
                ->get(['id', 'name'])
            : collect();
        $feedbackStatuses = Status::query()
            ->where('module', Status::MODULE_CALL_MANAGEMENT_FEEDBACK)
            ->where('active', 'Y')
            ->orderBy('id')
            ->get(['id', 'status_name', 'display_name']);

        $selectedStatus = (string) $request->input('status');
        $selectedCallingType = in_array($request->input('calling_type'), [
            CallManagementEntry::TYPE_CUSTOMER_CALLING,
            CallManagementEntry::TYPE_CLIENT_CALLING,
        ], true) ? $request->input('calling_type') : CallManagementEntry::TYPE_CUSTOMER_CALLING;
        $selectedFeedbackStatus = str_starts_with($selectedStatus, 'feedback:')
            ? $feedbackStatuses->firstWhere('id', (int) substr($selectedStatus, 9))
            : null;
        $showCompleted = $selectedFeedbackStatus
            && $this->callManagementFeedbackOutcome($selectedFeedbackStatus) === 'completed';

        $latestCallLogs = CallLog::query()
            ->selectRaw('call_management_entry_id, MAX(id) as latest_call_log_id')
            ->whereNotNull('call_management_entry_id')
            ->groupBy('call_management_entry_id');

        $query = CallManagementEntry::query()
            ->select('call_management_entries.*')
            ->leftJoinSub($latestCallLogs, 'latest_call_logs', function ($join) {
                $join->on('latest_call_logs.call_management_entry_id', '=', 'call_management_entries.id');
            })
            ->leftJoin('call_logs as latest_call_log', 'latest_call_log.id', '=', 'latest_call_logs.latest_call_log_id')
            ->with([
                'assignedUser:id,name',
                'latestCallLog.feedbackStatus:id,status_name,display_name',
                'latestNotedCallLog',
            ])
            ->where('call_management_entries.status', $showCompleted ? 'completed' : 'assigned')
            ->where('call_management_entries.calling_type', $selectedCallingType);

        if (! $canViewAllAgents) {
            $query->where('call_management_entries.assigned_user_id', auth()->id());
        } elseif ($request->filled('agent_id') && $filterAgents->contains('id', $request->integer('agent_id'))) {
            $query->where('call_management_entries.assigned_user_id', $request->integer('agent_id'));
        }

        if ($search = trim((string) $request->input('search'))) {
            $query->where(function ($searchQuery) use ($search) {
                $searchQuery->where('call_management_entries.firm_name', 'like', '%'.$search.'%')
                    ->orWhere('call_management_entries.contact_person_name', 'like', '%'.$search.'%')
                    ->orWhere('call_management_entries.mobile_number', 'like', '%'.$search.'%');
            });
        }

        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        if ($fromDate && preg_match('/^\d{4}-\d{2}-\d{2}$/', $fromDate)) {
            $query->where('call_management_entries.updated_at', '>=', $fromDate.' 00:00:00');
        }

        if ($toDate && preg_match('/^\d{4}-\d{2}-\d{2}$/', $toDate)) {
            $query->where('call_management_entries.updated_at', '<=', $toDate.' 23:59:59');
        }

        if ($selectedStatus === 'assigned') {
            $query->whereNull('latest_call_log.feedback_status_id');
        } elseif ($selectedFeedbackStatus) {
            $query->where('latest_call_log.feedback_status_id', $selectedFeedbackStatus->id);
        } elseif ($selectedStatus !== '') {
            $query->whereRaw('1 = 0');
        }

        $followUpStatusIds = $feedbackStatuses
            ->filter(fn (Status $status) => $this->isFollowUpFeedback($status))
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->values();

        if ($followUpStatusIds->isNotEmpty()) {
            $placeholders = $followUpStatusIds->map(fn () => '?')->implode(',');
            $query->orderByRaw(
                "CASE WHEN latest_call_log.feedback_status_id IN ({$placeholders}) AND call_management_entries.follow_up_date <= ? THEN 0 WHEN latest_call_log.feedback_status_id IS NULL THEN 1 ELSE 2 END",
                [...$followUpStatusIds->all(), today()->toDateString()]
            );
        } else {
            $query->orderByRaw('CASE WHEN latest_call_log.feedback_status_id IS NULL THEN 1 ELSE 2 END');
        }

        $entries = $query
            ->orderByDesc('call_management_entries.listing_order')
            ->orderByDesc('call_management_entries.id')
            ->paginate(10)
            ->withQueryString();
        $feedbackStatuses->each(function (Status $status) {
            $status->setAttribute('is_follow_up', $this->isFollowUpFeedback($status));
        });
        $callers = collect();

        if ($canCreateCall || $canEditDelete) {
            $callers = User::permission('call_management_access')
                ->where('active', 'Y')
                ->orderBy('name')
                ->get(['id', 'name']);
        }

        // Keep Create Call consistent with Customer Create: preload every active
        // pincode available to the current user's reporting-area assignment.
        // The feedback popup continues to use the paginated AJAX search.
        $pincodeUserIds = getUsersReportingToAuth();
        $pincodes = Pincode::query()
            ->where('active', 'Y')
            ->whereHas('assigncitiesusers', function ($query) use ($pincodeUserIds) {
                if (! auth()->user()->hasRole('superadmin') && ! auth()->user()->hasRole('Admin')) {
                    $query->whereIn('userid', $pincodeUserIds);
                }
            })
            ->select('id', 'pincode')
            ->orderByDesc('id')
            ->get();

        return view('calls.customer-calling', compact(
            'entries',
            'feedbackStatuses',
            'canCreateCall',
            'canImportExport',
            'canEditDelete',
            'canViewAllAgents',
            'filterAgents',
            'callers',
            'pincodes',
            'selectedCallingType'
        ));
    }

    public function searchPincodes(Request $request)
    {
        abort_if(Gate::denies('call_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pincodes = Pincode::with([
                'cityname:id,city_name,district_id,state_id',
                'cityname.districtname:id,district_name,state_id',
                'cityname.districtname.statename:id,state_name',
                'cityname.statename:id,state_name',
            ])
            ->where('active', 'Y')
            ->when(trim((string) $request->input('q')), function ($query, $search) {
                $query->where('pincode', 'like', $search.'%');
            })
            ->orderBy('pincode')
            ->paginate(20);

        return response()->json([
            'results' => $pincodes->map(function ($pincode) {
                $city = $pincode->cityname;
                $district = optional($city)->districtname;
                $state = optional($district)->statename ?: optional($city)->statename;

                return [
                    'id' => $pincode->id,
                    'text' => $pincode->pincode,
                    'city' => optional($city)->city_name ?: '',
                    'district' => optional($district)->district_name ?: '',
                    'state' => optional($state)->state_name ?: '',
                ];
            })->values(),
            'pagination' => ['more' => $pincodes->hasMorePages()],
        ]);
    }

    public function customerCallHistory(Request $request)
    {
        abort_if(Gate::denies('call_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $selectedHistoryType = $request->input('history_type') === CallManagementEntry::TYPE_CLIENT_CALLING
            ? CallManagementEntry::TYPE_CLIENT_CALLING
            : CallManagementEntry::TYPE_CUSTOMER_CALLING;
        $historyQuery = $selectedHistoryType === CallManagementEntry::TYPE_CLIENT_CALLING
            ? $this->clientCallHistoryQuery($request)
            : $this->customerCallHistoryQuery($request);
        $callLogs = $historyQuery
            ->latest('started_at')
            ->latest('id')
            ->paginate(10)
            ->withQueryString();
        $agentIds = $selectedHistoryType === CallManagementEntry::TYPE_CLIENT_CALLING
            ? ClientCallLog::query()->select('assigned_user_id')
            : CallLog::whereNotNull('call_management_entry_id')->select('user_id');
        $agents = auth()->user()->hasRole('superadmin')
            ? User::whereIn('id', $agentIds)
                ->orderBy('name')
                ->get(['id', 'name'])
            : collect();
        $feedbackStatuses = Status::query()
            ->where('module', Status::MODULE_CALL_MANAGEMENT_FEEDBACK)
            ->where('active', 'Y')
            ->orderBy('id')
            ->get(['id', 'status_name', 'display_name']);

        return view('calls.history', compact('callLogs', 'agents', 'feedbackStatuses', 'selectedHistoryType'));
    }

    public function exportCustomerCallHistory(Request $request)
    {
        abort_if(Gate::denies('call_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $selectedHistoryType = $request->input('history_type') === CallManagementEntry::TYPE_CLIENT_CALLING
            ? CallManagementEntry::TYPE_CLIENT_CALLING
            : CallManagementEntry::TYPE_CUSTOMER_CALLING;
        $callLogs = ($selectedHistoryType === CallManagementEntry::TYPE_CLIENT_CALLING
            ? $this->clientCallHistoryQuery($request)
            : $this->customerCallHistoryQuery($request))
            ->latest('started_at')->get();

        $filename = $selectedHistoryType === CallManagementEntry::TYPE_CLIENT_CALLING
            ? 'client-calling-history.xlsx'
            : 'customer-calling-history.xlsx';

        return Excel::download(new CustomerCallHistoryExport($callLogs, $selectedHistoryType), $filename);
    }

    public function customerCallHistoryDetail(CallLog $callLog)
    {
        abort_if(Gate::denies('call_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_unless($callLog->call_management_entry_id, Response::HTTP_NOT_FOUND);
        $this->authorizeCustomerCallLog($callLog);

        $callLog->load(['user:id,name,email,mobile', 'feedbackStatus:id,status_name,display_name', 'callManagementEntry']);

        return view('calls.history-detail', compact('callLog'));
    }

    public function transcribeCustomerCall(CallLog $callLog)
    {
        abort_if(Gate::denies('call_management_transcribe'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_unless($callLog->call_management_entry_id, Response::HTTP_NOT_FOUND);
        abort_if(empty($callLog->recording_url), Response::HTTP_UNPROCESSABLE_ENTITY, 'Recording is not available.');

        if (config('queue.default') === 'sync') {
            return back()->with('error', 'Queue is not configured. Set QUEUE_CONNECTION=database and start the transcription worker.');
        }

        if ($callLog->transcription_status === 'completed') {
            return back()->with('success', 'Transcript is already available.');
        }

        if (in_array($callLog->transcription_status, ['queued', 'processing'], true)) {
            return back()->with('success', 'Transcription is already in progress.');
        }

        $callLog->update(['transcription_status' => 'queued', 'transcription_error' => null]);
        TranscribeCallRecording::dispatch($callLog->id)->onQueue('transcriptions');

        return back()->with('success', 'Recording queued for transcription. Refresh this page after a few minutes.');
    }

    private function customerCallHistoryQuery(Request $request)
    {
        $query = CallLog::with([
                'user:id,name',
                'feedbackStatus:id,status_name,display_name',
                'callManagementEntry:id,firm_name,contact_person_name,mobile_number',
            ])
            ->whereNotNull('call_management_entry_id')
            ->whereHas('callManagementEntry', function ($entryQuery) {
                $entryQuery->where('calling_type', CallManagementEntry::TYPE_CUSTOMER_CALLING);
            });

        if (! auth()->user()->hasRole('superadmin')) {
            $query->where('user_id', auth()->id());
        } elseif ($request->filled('agent_id')) {
            $query->where('user_id', $request->input('agent_id'));
        }

        if ($search = trim((string) $request->input('search'))) {
            $query->where(function ($searchQuery) use ($search) {
                $searchQuery->where('number', 'like', '%'.$search.'%')
                    ->orWhere('remark', 'like', '%'.$search.'%')
                    ->orWhereHas('callManagementEntry', function ($entryQuery) use ($search) {
                        $entryQuery->where('firm_name', 'like', '%'.$search.'%')
                            ->orWhere('contact_person_name', 'like', '%'.$search.'%')
                            ->orWhere('mobile_number', 'like', '%'.$search.'%');
                    });
            });
        }

        if ($request->filled('feedback_status_id')) {
            $query->where('feedback_status_id', $request->input('feedback_status_id'));
        }

        if ($request->filled('from_date') && preg_match('/^\d{4}-\d{2}-\d{2}$/', $request->input('from_date'))) {
            $query->whereDate('started_at', '>=', $request->input('from_date'));
        }

        if ($request->filled('to_date') && preg_match('/^\d{4}-\d{2}-\d{2}$/', $request->input('to_date'))) {
            $query->whereDate('started_at', '<=', $request->input('to_date'));
        }

        if ($request->input('call_status') === 'completed') {
            $query->where(function ($statusQuery) {
                $statusQuery->where('duration', '>', 0)
                    ->orWhereNotNull('recording_url')
                    ->orWhere('status', 1);
            });
        } elseif ($request->filled('call_status')) {
            $query->where('plivo_status', $request->input('call_status'))
                ->where('duration', '<=', 0)
                ->whereNull('recording_url')
                ->where('status', '!=', 1);
        }

        return $query;
    }

    private function clientCallHistoryQuery(Request $request)
    {
        $query = ClientCallLog::with([
            'assignedAgent:id,name',
            'feedbackStatus:id,status_name,display_name',
            'entry:id,firm_name,contact_person_name,mobile_number',
        ]);

        if (! auth()->user()->hasRole('superadmin')) {
            $query->where('assigned_user_id', auth()->id());
        } elseif ($request->filled('agent_id')) {
            $query->where('assigned_user_id', $request->input('agent_id'));
        }

        if ($search = trim((string) $request->input('search'))) {
            $query->where(function ($searchQuery) use ($search) {
                $searchQuery->where('customer_number', 'like', '%'.$search.'%')
                    ->orWhere('remark', 'like', '%'.$search.'%')
                    ->orWhere('direction', 'like', '%'.$search.'%')
                    ->orWhereHas('entry', function ($entryQuery) use ($search) {
                        $entryQuery->where('firm_name', 'like', '%'.$search.'%')
                            ->orWhere('contact_person_name', 'like', '%'.$search.'%')
                            ->orWhere('mobile_number', 'like', '%'.$search.'%');
                    });
            });
        }

        if ($request->filled('feedback_status_id')) {
            $query->where('feedback_status_id', $request->input('feedback_status_id'));
        }
        if ($request->filled('from_date') && preg_match('/^\d{4}-\d{2}-\d{2}$/', $request->input('from_date'))) {
            $query->whereDate('started_at', '>=', $request->input('from_date'));
        }
        if ($request->filled('to_date') && preg_match('/^\d{4}-\d{2}-\d{2}$/', $request->input('to_date'))) {
            $query->whereDate('started_at', '<=', $request->input('to_date'));
        }
        if ($request->input('call_status') === 'completed') {
            $query->whereNotNull('completed_at');
        } elseif ($request->filled('call_status')) {
            $status = str_replace('-', '_', strtolower((string) $request->input('call_status')));
            $query->whereRaw("REPLACE(LOWER(status), '-', '_') = ?", [$status]);
        }

        return $query;
    }

    private function authorizeCustomerCallLog(CallLog $callLog): void
    {
        if (auth()->user()->hasRole('superadmin') || auth()->user()->hasRole('Admin')) {
            return;
        }

        abort_unless((int) $callLog->user_id === (int) auth()->id(), Response::HTTP_FORBIDDEN, '403 Forbidden');
    }

    private function formatDashboardDuration(int $seconds): string
    {
        $hours = intdiv($seconds, 3600);
        $minutes = intdiv($seconds % 3600, 60);
        $remainingSeconds = $seconds % 60;

        return $hours > 0
            ? sprintf('%dh %02dm %02ds', $hours, $minutes, $remainingSeconds)
            : sprintf('%dm %02ds', $minutes, $remainingSeconds);
    }

    public function initiateCustomerCall(CallManagementEntry $callManagementEntry)
    {
        abort_if(Gate::denies('call_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user = auth()->user();
        abort_unless($user->call_management, Response::HTTP_FORBIDDEN, 'Plivo calling is not enabled for this user.');
        abort_unless((int) $callManagementEntry->assigned_user_id === (int) $user->id, Response::HTTP_FORBIDDEN, 'This call is not assigned to you.');
        abort_unless(
            $callManagementEntry->calling_type === CallManagementEntry::TYPE_CUSTOMER_CALLING,
            Response::HTTP_UNPROCESSABLE_ENTITY,
            'Use Client Calling through Phone for this lead.'
        );

        $agentNumber = $this->e164($user->mobile);
        $customerNumber = $this->e164($callManagementEntry->mobile_number);
        $resolvedPincode = null;
        if ($callManagementEntry->pincode_id || $callManagementEntry->pincode) {
            $resolvedPincode = Pincode::query()
                ->where(function ($query) use ($callManagementEntry) {
                    if ($callManagementEntry->pincode_id) {
                        $query->whereKey($callManagementEntry->pincode_id);
                        $query->orWhere('pincode', (string) $callManagementEntry->pincode_id);
                    }

                    if ($callManagementEntry->pincode) {
                        $method = $callManagementEntry->pincode_id ? 'orWhere' : 'where';
                        $query->{$method}('pincode', trim((string) $callManagementEntry->pincode));
                    }
                })
                ->first(['id', 'pincode']);
        }
        if (! $agentNumber || ! $customerNumber) {
            return response()->json(['success' => false, 'message' => 'Agent or customer mobile number is invalid.'], 422);
        }

        $callLog = null;
        try {
            $this->ensurePlivoConfigured();
            $callLog = CallLog::create([
                'call_management_entry_id' => $callManagementEntry->id,
                'user_id' => $user->id,
                'number' => $customerNumber,
                'started_at' => now(),
                'duration' => 0,
                'status' => 0,
                'plivo_status' => 'initiating',
                'webhook_token' => Str::random(64),
            ]);
            $query = http_build_query(['call_log_id' => $callLog->id, 'token' => $callLog->webhook_token]);

            $response = Http::withBasicAuth(config('services.plivo.auth_id'), config('services.plivo.auth_token'))
                ->asJson()
                ->post('https://api.plivo.com/v1/Account/'.config('services.plivo.auth_id').'/Call/', [
                    'from' => $this->plivoFromNumber(),
                    'to' => $agentNumber,
                    'answer_url' => $this->plivoWebhookUrl('answer_url', 'api/plivo/answer').'?'.$query,
                    'answer_method' => 'POST',
                    'ring_url' => $this->plivoWebhookUrl('status_url', 'api/plivo/status').'?'.$query,
                    'ring_method' => 'POST',
                    'hangup_url' => $this->plivoWebhookUrl('status_url', 'api/plivo/status').'?'.$query,
                    'hangup_method' => 'POST',
                ]);

            if (! $response->successful()) {
                $callLog->update(['plivo_status' => 'failed']);
                return response()->json(['success' => false, 'message' => 'Plivo rejected the call request.'], 502);
            }

            $callUuid = $response->json('request_uuid.0') ?: $response->json('request_uuid');
            $callLog->update(['plivo_call_uuid' => $callUuid, 'plivo_status' => 'queued']);
            $previousNotes = collect();
            try {
                $previousNotes = CallLog::with('feedbackStatus:id,status_name,display_name')
                    ->where('call_management_entry_id', $callManagementEntry->id)
                    ->where('id', '!=', $callLog->id)
                    ->whereNotNull('remark')
                    ->where('remark', '!=', '')
                    ->latest('started_at')
                    ->get()
                    ->map(function (CallLog $previousCall) {
                        return [
                            'note' => $previousCall->remark,
                            'status' => optional($previousCall->feedbackStatus)->display_name
                                ?: optional($previousCall->feedbackStatus)->status_name
                                ?: '—',
                            'date' => optional($previousCall->started_at)->format('d M Y, h:i A') ?: '—',
                        ];
                    })
                    ->values();
            } catch (Throwable $exception) {
                report($exception);
            }

            return response()->json([
                'success' => true,
                'message' => 'Call initiated. Your phone will ring first.',
                'data' => [
                    'call_log_id' => $callLog->id,
                    'status_url' => route('customer-calling.call-status', $callLog),
                    'feedback_url' => route('customer-calling.call-feedback', $callLog),
                    'customer_name' => $callManagementEntry->contact_person_name ?: $callManagementEntry->firm_name,
                    'project_name' => $callManagementEntry->project_name,
                    'project_id' => $callManagementEntry->project_id,
                    'parent_name' => $callManagementEntry->parent_name,
                    'firm_name' => $callManagementEntry->firm_name,
                    'contact_person' => $callManagementEntry->contact_person_name,
                    'mobile' => $callManagementEntry->mobile_number,
                    'customer_type' => $callManagementEntry->customer_type,
                    'address' => $callManagementEntry->address,
                    'pincode_id' => optional($resolvedPincode)->id ?: $callManagementEntry->pincode_id,
                    'pincode' => optional($resolvedPincode)->pincode ?: $callManagementEntry->pincode,
                    'city' => $callManagementEntry->city,
                    'district' => $callManagementEntry->district,
                    'state' => $callManagementEntry->state,
                    'assigned_to' => $user->name,
                    'custom_column_1' => $callManagementEntry->custom_column_1,
                    'custom_column_2' => $callManagementEntry->custom_column_2,
                    'custom_column_3' => $callManagementEntry->custom_column_3,
                    'custom_column_4' => $callManagementEntry->custom_column_4,
                    'previous_notes' => $previousNotes,
                ],
            ]);
        } catch (Throwable $exception) {
            report($exception);
            if ($callLog) $callLog->update(['plivo_status' => 'failed']);

            return response()->json([
                'success' => false,
                'message' => 'Calling service is currently unavailable. Please contact the administrator.',
            ], 502);
        }
    }

    public function createCrmCallSession(CallManagementEntry $callManagementEntry)
    {
        abort_if(Gate::denies('call_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user = auth()->user();
        abort_unless($user->call_management, Response::HTTP_FORBIDDEN, 'Plivo calling is not enabled for this user.');
        abort_unless((int) $callManagementEntry->assigned_user_id === (int) $user->id, Response::HTTP_FORBIDDEN, 'This call is not assigned to you.');
        abort_unless(
            $callManagementEntry->calling_type === CallManagementEntry::TYPE_CUSTOMER_CALLING,
            Response::HTTP_UNPROCESSABLE_ENTITY,
            'Use Client Calling through Phone for this lead.'
        );

        $customerNumber = $this->e164($callManagementEntry->mobile_number);
        if (! $customerNumber) {
            return response()->json(['success' => false, 'message' => 'Customer mobile number is invalid.'], 422);
        }

        $this->ensurePlivoBrowserConfigured();

        try {
            $endpointUsername = $this->ensureAgentPlivoEndpoint($user);
            $now = now()->timestamp;
            $tokenResponse = Http::withBasicAuth(config('services.plivo.auth_id'), config('services.plivo.auth_token'))
                ->acceptJson()
                ->asJson()
                ->connectTimeout(5)
                ->timeout(15)
                ->post('https://api.plivo.com/v1/Account/'.config('services.plivo.auth_id').'/JWT/Token/', [
                    'iss' => config('services.plivo.auth_id'),
                    'sub' => $endpointUsername,
                    'nbf' => $now - 10,
                    'exp' => $now + 300,
                    'per' => ['voice' => ['incoming_allow' => false, 'outgoing_allow' => true]],
                    'app' => (string) config('services.plivo.browser_app_id'),
                ]);

            if (! $tokenResponse->successful() || ! $tokenResponse->json('token')) {
                Log::warning('Plivo browser JWT generation failed.', ['status' => $tokenResponse->status(), 'body' => $tokenResponse->json()]);
                return response()->json(['success' => false, 'message' => 'Unable to authenticate CRM calling.'], 502);
            }

            $callLog = CallLog::create([
                'call_management_entry_id' => $callManagementEntry->id,
                'user_id' => $user->id,
                'number' => $customerNumber,
                'started_at' => now(),
                'duration' => 0,
                'status' => 0,
                'plivo_status' => 'browser-initiating',
                'webhook_token' => Str::random(64),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'CRM calling is ready.',
                'data' => array_merge($this->customerCallData($callManagementEntry, $callLog, $user), [
                    'access_token' => $tokenResponse->json('token'),
                    'destination' => $customerNumber,
                    'call_event_url' => route('customer-calling.crm-event', $callLog),
                    'sip_headers' => [
                        'X-PH-CallLogId' => (string) $callLog->id,
                        'X-PH-CallToken' => $callLog->webhook_token,
                    ],
                ]),
            ], 201);
        } catch (Throwable $exception) {
            report($exception);

            return response()->json(['success' => false, 'message' => 'CRM calling service is currently unavailable.'], 502);
        }
    }

    public function updateCrmCallEvent(Request $request, CallLog $callLog)
    {
        abort_if(Gate::denies('call_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_unless((int) $callLog->user_id === (int) auth()->id() && $callLog->call_management_entry_id, Response::HTTP_FORBIDDEN, 'You cannot update this call.');

        $validated = $request->validate([
            'event' => ['required', Rule::in(['calling', 'ringing', 'answered', 'media-connected', 'terminated', 'failed'])],
            'call_uuid' => ['nullable', 'string', 'max:255'],
            'duration' => ['nullable', 'integer', 'min:0', 'max:86400'],
        ]);

        $updates = ['plivo_status' => $validated['event']];
        if (! empty($validated['call_uuid'])) $updates['plivo_call_uuid'] = $validated['call_uuid'];
        if (in_array($validated['event'], ['answered', 'media-connected'], true) && ! $callLog->answered_at) {
            $updates['answered_at'] = now();
            $updates['status'] = 1;
        }
        if (in_array($validated['event'], ['terminated', 'failed'], true)) {
            $updates['completed_at'] = $callLog->completed_at ?: now();
            if (isset($validated['duration'])) $updates['duration'] = $validated['duration'];
        }
        $callLog->update($updates);

        return response()->json(['success' => true]);
    }

    public function customerCallNotes(CallManagementEntry $callManagementEntry)
    {
        abort_if(Gate::denies('call_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_unless(
            auth()->user()->hasRole('superadmin') || (int) $callManagementEntry->assigned_user_id === (int) auth()->id(),
            Response::HTTP_FORBIDDEN,
            'You cannot view these notes.'
        );

        $notes = CallLog::with('feedbackStatus:id,status_name,display_name')
            ->where('call_management_entry_id', $callManagementEntry->id)
            ->whereNotNull('remark')
            ->where('remark', '!=', '')
            ->latest('started_at')
            ->get()
            ->map(function (CallLog $callLog) {
                return [
                    'note' => $callLog->remark,
                    'status' => optional($callLog->feedbackStatus)->display_name
                        ?: optional($callLog->feedbackStatus)->status_name
                        ?: '—',
                    'date' => optional($callLog->started_at)->format('d M Y, h:i A') ?: '—',
                ];
            });

        return response()->json(['success' => true, 'data' => $notes]);
    }

    public function customerCallStatus(CallLog $callLog)
    {
        abort_if(Gate::denies('call_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_unless((int) $callLog->user_id === (int) auth()->id() && $callLog->call_management_entry_id, Response::HTTP_FORBIDDEN, 'You cannot view this call.');

        return response()->json([
            'success' => true,
            'data' => [
                'completed' => (bool) $callLog->completed_at,
                'duration' => (int) $callLog->duration,
                'status' => $callLog->plivo_status,
                'requires_feedback' => (bool) $callLog->completed_at && ! $callLog->feedback_status_id,
            ],
        ]);
    }

    public function saveCustomerCallFeedback(Request $request, CallLog $callLog)
    {
        abort_if(Gate::denies('call_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_unless((int) $callLog->user_id === (int) auth()->id() && $callLog->call_management_entry_id, Response::HTTP_FORBIDDEN, 'You cannot update this call.');

        $validated = $request->validate([
            'feedback_status_id' => ['required', 'integer'],
            'message' => ['required', 'string', 'max:1000'],
            'follow_up_date' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:today'],
            'parent_name' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:1000'],
            'pincode_id' => ['required', 'string', 'max:20'],
            'city' => ['nullable', 'string', 'max:150'],
            'district' => ['nullable', 'string', 'max:150'],
            'state' => ['nullable', 'string', 'max:150'],
        ]);
        $status = Status::query()
            ->whereKey($validated['feedback_status_id'])
            ->where('module', Status::MODULE_CALL_MANAGEMENT_FEEDBACK)
            ->where('active', 'Y')
            ->firstOrFail();
        $feedbackOutcome = $this->callManagementFeedbackOutcome($status);
        $isFollowUp = $this->isFollowUpFeedback($status);

        if ($isFollowUp && empty($validated['follow_up_date'])) {
            throw ValidationException::withMessages([
                'follow_up_date' => 'Please select a follow-up date.',
            ]);
        }

        $selectedPincode = Pincode::query()
            ->where('active', 'Y')
            ->where('pincode', trim((string) $validated['pincode_id']))
            ->first();
        if (! $selectedPincode && ctype_digit((string) $validated['pincode_id'])) {
            $selectedPincode = Pincode::where('active', 'Y')->find((int) $validated['pincode_id']);
        }
        if (! $selectedPincode) {
            throw ValidationException::withMessages([
                'pincode_id' => 'Please select a valid pincode.',
            ]);
        }

        DB::transaction(function () use ($callLog, $status, $validated, $feedbackOutcome, $isFollowUp, $selectedPincode) {
            $callLog->update([
                'feedback_status_id' => $status->id,
                'remark' => trim($validated['message']),
            ]);

            $entryUpdates = [
                'follow_up_date' => $isFollowUp ? $validated['follow_up_date'] : null,
                'parent_name' => $validated['parent_name'] ?? null,
                'address' => $validated['address'] ?? null,
                'pincode_id' => $selectedPincode->id,
                'pincode' => $selectedPincode->pincode,
                'city' => $validated['city'] ?? null,
                'district' => $validated['district'] ?? null,
                'state' => $validated['state'] ?? null,
            ];
            if ($feedbackOutcome) $entryUpdates['status'] = $feedbackOutcome;

            CallManagementEntry::whereKey($callLog->call_management_entry_id)->update($entryUpdates);
        });

        return response()->json([
            'success' => true,
            'message' => 'Call record saved successfully.',
            'data' => [
                'queue_removed' => $feedbackOutcome !== null,
                'entry_status' => $feedbackOutcome ?: 'assigned',
                'follow_up_date' => $isFollowUp ? $validated['follow_up_date'] : null,
            ],
        ]);
    }

    private function callManagementFeedbackOutcome(Status $status): ?string
    {
        $labels = [$status->status_name, $status->display_name];

        foreach ($labels as $label) {
            $normalized = preg_replace('/[^a-z0-9]+/', '', strtolower((string) $label));

            // Feedback names are configurable, so accept labels such as
            // "Call Done" and "Completed Successfully" as completed outcomes.
            // Negative labels must be checked first because "Not Completed"
            // also contains the word "completed".
            if (
                str_contains($normalized, 'notcomplete')
                || str_contains($normalized, 'incomplete')
                || str_contains($normalized, 'uncomplete')
            ) {
                continue;
            }

            if (
                str_contains($normalized, 'complete')
                || in_array($normalized, ['done', 'calldone'], true)
            ) {
                return 'completed';
            }
        }

        return null;
    }

    private function isFollowUpFeedback(?Status $status): bool
    {
        if (! $status) {
            return false;
        }

        foreach ([$status->status_name, $status->display_name] as $label) {
            $normalized = preg_replace('/[^a-z0-9]+/', '', strtolower((string) $label));

            if (str_contains($normalized, 'followup')) {
                return true;
            }
        }

        return false;
    }

    private function e164(?string $number): ?string
    {
        $digits = preg_replace('/\D+/', '', (string) $number);
        if (strlen($digits) === 10) $digits = '91'.$digits;

        return strlen($digits) >= 11 && strlen($digits) <= 15 ? '+'.$digits : null;
    }

    private function plivoWebhookUrl(string $configKey, string $path): string
    {
        return rtrim(config('services.plivo.'.$configKey) ?: url($path), '/');
    }

    private function ensurePlivoBrowserConfigured(): void
    {
        $this->ensurePlivoConfigured();
        abort_unless(
            config('services.plivo.browser_app_id'),
            500,
            'PLIVO_BROWSER_APP_ID is not configured on the server.'
        );
    }

    private function ensureAgentPlivoEndpoint(User $user): string
    {
        if ($user->plivo_endpoint_username) return $user->plivo_endpoint_username;

        $username = 'fk'.$user->id.Str::lower(Str::random(8));
        $response = Http::withBasicAuth(config('services.plivo.auth_id'), config('services.plivo.auth_token'))
            ->acceptJson()
            ->asJson()
            ->connectTimeout(5)
            ->timeout(15)
            ->post('https://api.plivo.com/v1/Account/'.config('services.plivo.auth_id').'/Endpoint/', [
                'username' => $username,
                'password' => Str::random(32),
                'alias' => 'fieldkonnect_agent_'.$user->id,
                'app_id' => (string) config('services.plivo.browser_app_id'),
            ]);

        if (! $response->successful()) {
            Log::warning('Unable to provision Plivo browser endpoint.', ['user_id' => $user->id, 'status' => $response->status(), 'body' => $response->json()]);
            throw new \RuntimeException('Unable to provision the Plivo browser endpoint.');
        }

        $user->forceFill([
            'plivo_endpoint_id' => $response->json('endpoint_id'),
            'plivo_endpoint_username' => $response->json('username') ?: $username,
        ])->save();

        return $user->plivo_endpoint_username;
    }

    private function customerCallData(CallManagementEntry $entry, CallLog $callLog, User $user): array
    {
        $previousNotes = CallLog::with('feedbackStatus:id,status_name,display_name')
            ->where('call_management_entry_id', $entry->id)
            ->where('id', '!=', $callLog->id)
            ->whereNotNull('remark')
            ->where('remark', '!=', '')
            ->latest('started_at')
            ->get()
            ->map(fn (CallLog $previousCall) => [
                'note' => $previousCall->remark,
                'status' => optional($previousCall->feedbackStatus)->display_name ?: optional($previousCall->feedbackStatus)->status_name ?: '—',
                'date' => optional($previousCall->started_at)->format('d M Y, h:i A') ?: '—',
            ])->values();

        return [
            'call_log_id' => $callLog->id,
            'status_url' => route('customer-calling.call-status', $callLog),
            'feedback_url' => route('customer-calling.call-feedback', $callLog),
            'customer_name' => $entry->contact_person_name ?: $entry->firm_name,
            'project_name' => $entry->project_name,
            'project_id' => $entry->project_id,
            'parent_name' => $entry->parent_name,
            'firm_name' => $entry->firm_name,
            'contact_person' => $entry->contact_person_name,
            'mobile' => $entry->mobile_number,
            'customer_type' => $entry->customer_type,
            'address' => $entry->address,
            'pincode_id' => $entry->pincode_id,
            'pincode' => $entry->pincode,
            'city' => $entry->city,
            'district' => $entry->district,
            'state' => $entry->state,
            'assigned_to' => $user->name,
            'custom_column_1' => $entry->custom_column_1,
            'custom_column_2' => $entry->custom_column_2,
            'custom_column_3' => $entry->custom_column_3,
            'custom_column_4' => $entry->custom_column_4,
            'previous_notes' => $previousNotes,
        ];
    }

    private function ensurePlivoConfigured(): void
    {
        abort_unless(
            config('services.plivo.auth_id')
                && config('services.plivo.auth_token')
                && $this->plivoFromNumber(),
            500,
            'Plivo credentials or caller number are not configured correctly on the server.'
        );
    }

    private function plivoFromNumber(): ?string
    {
        return $this->e164(config('services.plivo.from_number'));
    }

    public function store(Request $request)
    {
        abort_unless(
            Gate::allows('call_management_create'),
            Response::HTTP_FORBIDDEN,
            '403 Forbidden'
        );

        $callerIds = User::permission('call_management_access')
            ->where('active', 'Y')
            ->pluck('id');

        $validated = $request->validateWithBag('addCall', [
            'firm_name' => ['required', 'string', 'max:200'],
            'contact_person_name' => ['required', 'string', 'max:200'],
            'mobile_number' => ['required', 'digits:10'],
            'customer_type' => ['nullable', 'string', 'max:100'],
            'calling_type' => ['required', Rule::in([
                CallManagementEntry::TYPE_CUSTOMER_CALLING,
                CallManagementEntry::TYPE_CLIENT_CALLING,
            ])],
            'address' => ['nullable', 'string', 'max:500'],
            'pincode_id' => ['required', 'integer', 'exists:pincodes,id'],
            'assigned_user_id' => ['required', 'integer', Rule::in($callerIds->all())],
            'custom_column_1' => ['nullable', 'string', 'max:255'],
            'custom_column_2' => ['nullable', 'string', 'max:255'],
            'custom_column_3' => ['nullable', 'string', 'max:255'],
            'custom_column_4' => ['nullable', 'string', 'max:255'],
        ]);

        $pincode = Pincode::with(['cityname.districtname.statename', 'cityname.statename'])
            ->findOrFail($validated['pincode_id']);

        $city = $pincode->cityname;
        $district = optional($city)->districtname;
        $state = optional($district)->statename ?: optional($city)->statename;

        CallManagementEntry::create(array_merge($validated, [
            'pincode' => $pincode->pincode,
            'city' => optional($city)->city_name,
            'district' => optional($district)->district_name,
            'state' => optional($state)->state_name,
            'status' => 'assigned',
            'created_by' => auth()->id(),
        ]));

        return redirect()->route('customer-calling.index', ['calling_type' => $validated['calling_type']])
            ->with('message_success', 'Call entry added successfully.');
    }

    public function update(Request $request, CallManagementEntry $callManagementEntry)
    {
        abort_if(Gate::denies('call_management_edit_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $callerIds = User::permission('call_management_access')
            ->where('active', 'Y')
            ->pluck('id');

        $validated = $request->validateWithBag('editCall', [
            'firm_name' => ['required', 'string', 'max:200'],
            'contact_person_name' => ['required', 'string', 'max:200'],
            'mobile_number' => ['required', 'digits:10'],
            'customer_type' => ['nullable', 'string', 'max:100'],
            'calling_type' => ['required', Rule::in([
                CallManagementEntry::TYPE_CUSTOMER_CALLING,
                CallManagementEntry::TYPE_CLIENT_CALLING,
            ])],
            'address' => ['nullable', 'string', 'max:500'],
            'pincode_id' => ['required', 'integer', 'exists:pincodes,id'],
            'assigned_user_id' => ['required', 'integer', Rule::in($callerIds->all())],
            'custom_column_1' => ['nullable', 'string', 'max:255'],
            'custom_column_2' => ['nullable', 'string', 'max:255'],
            'custom_column_3' => ['nullable', 'string', 'max:255'],
            'custom_column_4' => ['nullable', 'string', 'max:255'],
        ]);

        $pincode = Pincode::with(['cityname.districtname.statename', 'cityname.statename'])
            ->findOrFail($validated['pincode_id']);
        $city = $pincode->cityname;
        $district = optional($city)->districtname;
        $state = optional($district)->statename ?: optional($city)->statename;

        $callManagementEntry->update(array_merge($validated, [
            'pincode' => $pincode->pincode,
            'city' => optional($city)->city_name,
            'district' => optional($district)->district_name,
            'state' => optional($state)->state_name,
        ]));

        return redirect()->route('customer-calling.index', ['calling_type' => $validated['calling_type']])
            ->with('message_success', 'Call entry updated successfully.');
    }

    public function destroy(CallManagementEntry $callManagementEntry)
    {
        abort_if(Gate::denies('call_management_edit_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $callManagementEntry->delete();

        return redirect()->back()->with('message_success', 'Call entry deleted successfully.');
    }

    public function import(Request $request)
    {
        abort_if(Gate::denies('call_management_import_export'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $redirectRoute = 'customer-calling.index';

        $request->validateWithBag('importCall', [
            'import_file' => [
                'bail',
                'required',
                'file',
                'max:10240',
                function ($attribute, $file, $fail) {
                    if (! in_array(strtolower($file->getClientOriginalExtension()), ['xlsx', 'xls', 'csv'], true)) {
                        $fail('Please select a valid XLSX, XLS or CSV file.');
                    }
                },
            ],
        ]);

        try {
            if (ob_get_contents()) {
                ob_end_clean();
            }
            ob_start();

            $import = new CallManagementEntryImport(auth()->id());
            Excel::import($import, $request->file('import_file'));

            if (($import->createdCount() + $import->updatedCount() + $import->skippedCount()) === 0) {
                throw ValidationException::withMessages([
                    'import_file' => 'No data rows found. Please use the exported Excel column headings.',
                ]);
            }

            $redirect = redirect()->route($redirectRoute)->with(
                'message_success',
                $import->createdCount().' call entries created and '
                .$import->updatedCount().' call entries updated successfully.'
            );

            if ($import->skippedCount()) {
                $redirect->with(
                    'message_error',
                    $import->skippedCount().' rows skipped. '.implode(' | ', array_slice($import->errors(), 0, 3))
                );
            }

            return $redirect;
        } catch (ValidationException $exception) {
            return redirect()->route($redirectRoute)->withErrors(
                $exception->errors(),
                'importCall'
            );
        } catch (Throwable $exception) {
            report($exception);

            return redirect()->route($redirectRoute)->withErrors([
                'import_file' => $exception->getMessage(),
            ], 'importCall');
        }
    }

    public function export()
    {
        abort_if(Gate::denies('call_management_import_export'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return Excel::download(new CallManagementEntryExport, 'call-management-entries.xlsx');
    }
}
