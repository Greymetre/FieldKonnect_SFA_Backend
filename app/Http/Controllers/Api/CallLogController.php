<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CallLog;
use App\Models\LeadLog;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;
use Throwable;

class CallLogController extends Controller
{
    public function feedbackStatuses()
    {
        $statuses = Status::query()
            ->where('module', Status::MODULE_CALL_FEEDBACK_STATUS)
            ->where('active', 'Y')
            ->select('id', 'status_name', 'display_name', 'status_message')
            ->orderBy('id')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $statuses,
        ]);
    }

    public function mobileHistory(Request $request)
    {
        try {
            return $this->mobileHistoryResponse($request);
        } catch (Throwable $exception) {
            $reference = (string) \Illuminate\Support\Str::uuid();

            Log::error('Mobile call history failed.', [
                'reference' => $reference,
                'user_id' => $request->user('users')?->id,
                'exception' => $exception,
            ]);

            // A listing failure must never be interpreted by the app as an
            // expired login. Keep the authenticated session/token untouched.
            return response()->json([
                'success' => false,
                'status' => false,
                'message' => 'Call history is temporarily unavailable.',
                'data' => [],
                'summary' => [
                    'attempts' => 0,
                    'connected' => 0,
                    'not_connected' => 0,
                    'duration' => 0,
                ],
                'pagination' => [
                    'current_page' => 1,
                    'last_page' => 1,
                    'per_page' => min(100, max(1, (int) $request->input('page_size', 30))),
                    'total' => 0,
                ],
                'error_reference' => $reference,
            ], 200);
        }
    }

    private function mobileHistoryResponse(Request $request)
    {
        $user = $request->user('users');
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated. Please login again.',
            ], 401);
        }

        if (!$user->call_management) {
            return response()->json([
                'success' => false,
                'message' => 'Call history is not enabled for this user.',
            ], 403);
        }

        $period = $request->input('period', 'weekly');
        $query = CallLog::with(['lead:id,company_name', 'lead.contacts:id,lead_id,name,phone_number'])
            ->where('user_id', $user->id);

        if ($period === 'today') {
            $query->whereDate('started_at', today());
        } elseif ($period === 'monthly') {
            $query->where('started_at', '>=', now()->startOfMonth());
        } else {
            $query->where('started_at', '>=', now()->startOfWeek());
        }

        $search = trim((string) $request->input('q', $request->input('search', '')));
        if ($search !== '') {
            $query->where(function ($searchQuery) use ($search) {
                $searchQuery->where('number', 'like', "%{$search}%")
                    ->orWhereHas('lead', function ($leadQuery) use ($search) {
                        $leadQuery->where('company_name', 'like', "%{$search}%")
                            ->orWhereHas('contacts', function ($contactQuery) use ($search) {
                                $contactQuery->where('name', 'like', "%{$search}%");
                            });
                    });
            });
        }

        $summaryQuery = clone $query;
        $attempts = (clone $summaryQuery)->count();
        $connected = (clone $summaryQuery)->whereNotNull('recording_url')->where('recording_url', '!=', '')->count();
        $duration = (clone $summaryQuery)->whereNotNull('recording_url')->where('recording_url', '!=', '')->sum('duration');
        $pageSize = min(100, max(1, (int) $request->input('page_size', 30)));
        $logs = $query->latest('started_at')->paginate($pageSize);

        $items = $logs->getCollection()->map(function (CallLog $log) {
            // A call log can remain after its lead/contact has been deleted.
            // Do not let an orphaned log fail the complete mobile listing.
            $contact = $log->lead ? $log->lead->contacts->first() : null;
            $connected = !empty($log->recording_url);

            $recordingPlayUrl = null;
            if ($connected) {
                try {
                    $recordingPlayUrl = URL::temporarySignedRoute(
                        'api.call-recordings.play',
                        now()->addHour(),
                        ['callLog' => $log->id]
                    );
                } catch (Throwable $exception) {
                    Log::warning('Could not generate call recording URL.', [
                        'call_log_id' => $log->id,
                        'exception' => $exception->getMessage(),
                    ]);
                }
            }

            return [
                'id' => $log->id,
                'lead_id' => $log->lead_id,
                'customer_name' => $contact?->name ?: $log->lead?->company_name ?: 'Unknown customer',
                'company_name' => $log->lead?->company_name,
                'number' => $log->number,
                'started_at' => optional($log->started_at)->toIso8601String(),
                'duration' => (int) $log->duration,
                'recording_duration' => $log->recording_duration,
                'connected' => $connected,
                'remark' => $log->remark,
                'recording_play_url' => $recordingPlayUrl,
            ];
        })->values();

        return response()->json([
            'success' => true,
            'data' => $items,
            'summary' => [
                'attempts' => $attempts,
                'connected' => $connected,
                'not_connected' => max(0, $attempts - $connected),
                'duration' => (int) $duration,
            ],
            'pagination' => [
                'current_page' => $logs->currentPage(),
                'last_page' => $logs->lastPage(),
                'per_page' => $logs->perPage(),
                'total' => $logs->total(),
            ],
        ]);
    }

    public function playRecording(CallLog $callLog)
    {
        abort_if(empty($callLog->recording_url), 404, 'Recording not available.');

        $recording = Http::withBasicAuth(
            config('services.plivo.auth_id'),
            config('services.plivo.auth_token')
        )->timeout(30)->get($callLog->recording_url);

        abort_unless($recording->successful(), 502, 'Unable to load recording.');

        return response($recording->body(), 200, [
            'Content-Type' => $recording->header('Content-Type') ?: 'audio/mpeg',
            'Content-Disposition' => 'inline; filename="call-'.$callLog->id.'.mp3"',
            'Cache-Control' => 'private, max-age=3600',
        ]);
    }

    /**
     * Store a new call log.
     */
    public function store(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'lead_id'    => 'required|exists:leads,id',
                'started_at' => 'required|date',
                'duration'   => 'required|integer|min:0',
                'status'     => 'required|in:0,1', // 0 = No Response, 1 = Received
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors'  => $validator->errors(),
                ], 422); // 422 Unprocessable Entity
            }

            $callLog = CallLog::create([
                'user_id'    => $request->user()->id,
                'lead_id'    => $request->lead_id,
                'number'     => $request->number,
                'started_at' => date('Y-m-d H:i:s', strtotime($request->started_at)),
                'duration'   => $request->duration ?? 0,
                'status'     => $request->status,
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Call log created successfully',
                'data'    => $callLog,
            ], 200);
        } catch (\Exception $e) {
            Log::info($e);
            return response()->json([
                'success' => false,
                'message' => 'Call log created successfully',
                'data'    => [],
            ], 200);
        }
    }

    /**
     * Get call logs (optionally filter by user or lead).
     */
    public function index(Request $request)
    {
        $user = $request->user('users');
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated. Please login again.',
            ], 401);
        }

        $user_ids = getUsersReportingToAuth($user->id);
        $users = User::select('id', 'name')->where('active', 'Y');
        $pageSize = min(100, max(1, (int) $request->input('page_size', 20)));

        if (!$user->hasRole('superadmin') && !$user->hasRole('Admin')) {
            $users = $users->whereIn('id', $user_ids);
        }
        $users = $users->get();

        // Base query
        $query = CallLog::with(['user:id,name', 'lead:id,company_name', 'lead.contacts:id,lead_id,name']);

        if ($request->has('user_id') && !empty($request->user_id)) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('date') && !empty($request->date)) {
            $query->whereDate('started_at', $request->date);
        }

        if (!$user->hasRole('superadmin') && !$user->hasRole('Admin')) {
            $query->whereIn('user_id', $user_ids); // ✅ should be user_id not id
        }

        if ($request->has('lead_id')) {
            $query->where('lead_id', $request->lead_id);
        }

        $summaryQuery = clone $query;
        $totalDuration = (int) (clone $summaryQuery)->sum('duration');
        $callDialed = (clone $summaryQuery)->count();
        $connected = (clone $summaryQuery)->where('status', 1)->count();
        $noResponse = (clone $summaryQuery)->where('status', 0)->count();
        $logs = $query->latest('started_at')->paginate($pageSize);

        $items = $logs->getCollection()->map(function (CallLog $log) {
            $contact = $log->lead?->contacts->first();
            $duration = max(0, (int) $log->duration);

            return array_merge($log->toArray(), [
                'contact_name' => $contact?->name ?: '',
                'duration' => sprintf('%02d:%02d', intdiv($duration, 60), $duration % 60),
            ]);
        })->values();

        return response()->json([
            'success' => true,
            'data'    => $items,
            'users'   => $users,
            'call_dialted'   => $callDialed,
            'connected'      => $connected,
            'no_response'    => $noResponse,
            'total_duration' => gmdate('H:i:s', $totalDuration),
        ]);
    }

    public function last_call(Request $request)
    {
        $user = $request->user('users');
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated. Please login again.',
            ], 401);
        }

        $user_id = $user->id;
        $last_call = CallLog::where('user_id', $user_id)->latest()->first();
        $data['last_call_id'] = $last_call ? $last_call->id : '';
        $data['last_call_remark'] = $last_call ? ($last_call->remark ? true : false) : true;
        $data['lead_type'] = $last_call?->lead?->status_is?->status_name ?? ($last_call ? 'lead not found' : '');
        $data['lead_type_id'] = $last_call ? ($last_call->lead ? $last_call->lead->status : 'lead not found') : '';
        $data['all_types'] = Status::where('module', 'LeadStatus')->select('id', 'display_name')->get();
        return response()->json([
            'success' => true,
            'data'    => $data,
        ]);
    }

    public function update_remark(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'id' => 'required',
            'remark' => 'required',
            'lead_type_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data'    => $validator->errors(),
            ]);
        }

        $last_call = CallLog::with('lead')->findOrFail($request->id);

        if (!$last_call) {
            return response()->json([
                'success' => false,
                'data'    => 'Call log not found',
            ]);
        }

        $last_call->remark = $request->remark;
        $last_call->save();

        // Update the related lead status
        if ($last_call->lead) {
            $old_status = Status::where('id', $last_call->lead->status)->first();
            $last_call->lead->status = $request->lead_type_id;
            $last_call->lead->save();
            $new_status = Status::where('id', $request->lead_type_id)->first();
            $msg = 'Lead move from ' . $old_status->display_name . ' to ' . $new_status->display_name .
                ' by ' . Auth::user()->name;
            LeadLog::create([
                'lead_id' => $last_call->lead->id,
                'message' => $msg,
                'created_by' => Auth::id(),
            ]);
        }
        return response()->json([
            'success' => true,
            'data'    => $last_call,
        ]);
    }
}
