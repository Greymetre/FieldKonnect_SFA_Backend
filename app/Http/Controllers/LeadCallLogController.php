<?php

namespace App\Http\Controllers;

use App\Exports\ExcelExport;
use App\Http\Controllers\Controller;
use App\Services\CallTranscriptionService;
use App\Models\CallLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class LeadCallLogController extends Controller
{
    private const TERMINAL_STATUSES = ['failed', 'completed', 'hangup', 'busy', 'no-answer', 'no_answer', 'timeout', 'cancel', 'cancelled', 'rejected', 'agent-unavailable'];

    /**
     * Lead call history dashboard (page + DataTables JSON).
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('lead_call_history_access'), 403, '403 Forbidden');

        if (! $request->ajax()) {
            $users = User::select('id', 'name')->where('active', 'Y');
            if (! $this->isAdmin()) {
                $users->whereIn('id', getUsersReportingToAuth());
            }
            $users = $users->orderBy('name')->get();

            return view('call_logs.index', compact('users'));
        }

        // Summary cards ignore the status filter so they always show the
        // full split for the selected agent/date/direction.
        $baseQuery = $this->filteredQuery($request, false);
        $summary = $this->summary(clone $baseQuery);

        $query = $this->filteredQuery($request, true);
        $recordsTotal = (clone $query)->count();

        $search = trim((string) $request->input('search.value', $request->input('search_text', '')));
        if ($search !== '') {
            $query->where(function ($searchQuery) use ($search) {
                $searchQuery->where('number', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($userQuery) => $userQuery->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('lead', function ($leadQuery) use ($search) {
                        $leadQuery->where('company_name', 'like', "%{$search}%")
                            ->orWhereHas('contacts', fn ($contactQuery) => $contactQuery->where('name', 'like', "%{$search}%"));
                    });
            });
        }

        $recordsFiltered = (clone $query)->count();
        $start = max(0, (int) $request->input('start', 0));
        $length = min(100, max(1, (int) $request->input('length', 10)));
        $callLogs = $query->orderByDesc('started_at')->orderByDesc('id')->skip($start)->take($length)->get();

        $data = $callLogs->map(function (CallLog $row) {
            $duration = (int) $row->duration;
            $connected = $this->isConnected($row);
            $contact = $this->contactFor($row);

            return [
                'detail_url' => route('call-management.show', $row),
                'agent' => $row->user?->name ?: '-',
                'customer_name' => $contact?->name ?: '-',
                'company_name' => $row->lead?->company_name ?: '-',
                'lead_url' => $row->lead ? route('leads.show', $row->lead) : null,
                'number' => $row->number ?: '-',
                'direction' => $row->direction === 'inbound' ? 'inbound' : 'outbound',
                'date' => $row->started_at?->format('d M Y') ?: '-',
                'time' => $row->started_at?->format('h:i A') ?: '',
                'duration' => $this->formatDuration($duration),
                'connected' => $connected,
                'live' => $this->isLive($row),
                'recording_url' => ! empty($row->recording_url) ? route('call-management.recording', $row) : null,
            ];
        })->values();

        return response()->json([
            'draw' => (int) $request->input('draw', 0),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
            'summary' => $summary,
        ]);
    }

    public function download(Request $request)
    {
        abort_if(Gate::denies('lead_call_history_access'), 403, '403 Forbidden');
        $filename = 'Call Logs.xlsx';

        $call_logs = $this->filteredQuery($request, true)->orderBy('started_at', 'desc')->get();

        $headers = ['Agent', 'Customer', 'Lead', 'Contact No', 'Direction', 'Date & Time', 'Call Duration', 'Call Status', 'Plivo Status', 'Call UUID', 'Recording URL', 'Cost', 'Remark'];
        $rows = $call_logs->map(fn (CallLog $call_log) => [
            $call_log->user?->name ?? 'Not Found',
            $this->contactFor($call_log)?->name ?: 'Not Found',
            $call_log->lead ? $call_log->lead->company_name : 'Not Found',
            $call_log->number,
            $call_log->direction === 'inbound' ? 'Inbound' : 'Outbound',
            $call_log->started_at?->format('d/m/Y h:i A'),
            $this->formatDuration((int) $call_log->duration),
            $this->isConnected($call_log) ? 'Connected' : 'Not Connected',
            $call_log->plivo_status,
            $call_log->plivo_call_uuid,
            $call_log->recording_url,
            $call_log->cost,
            $call_log->remark ?? '',
        ])->all();

        return Excel::download(new ExcelExport($headers, $rows), $filename);
    }

    private function filteredQuery(Request $request, bool $withStatus)
    {
        $query = CallLog::with(['user:id,name', 'lead:id,company_name,status', 'lead.contacts:id,lead_id,name,phone_number', 'contact:id,name'])
            ->whereNotNull('lead_id')
            ->whereNull('call_management_entry_id');

        if (! $this->isAdmin()) {
            $query->whereIn('user_id', getUsersReportingToAuth());
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('start_date')) {
            $query->whereDate('started_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('started_at', '<=', $request->end_date);
        }
        if ($request->filled('lead_id')) {
            $query->where('lead_id', $request->lead_id);
        }
        if (in_array($request->input('direction'), ['inbound', 'outbound'], true)) {
            $query->where('direction', $request->input('direction'));
        }

        $status = $withStatus ? $request->input('call_status') : null;
        if ($status === 'connected') {
            $this->whereConnected($query);
        } elseif ($status === 'not_connected') {
            $query->where(function ($statusQuery) {
                $statusQuery->where('status', 0)
                    ->orWhereNull('recording_url')
                    ->orWhere('recording_url', '');
            });
        } elseif ($status === 'no_remark') {
            $query->whereNull('feedback_status_id');
        }

        return $query;
    }

    private function summary($query): array
    {
        $total = (clone $query)->count();
        $connected = $this->whereConnected(clone $query)->count();

        // Calls per agent for the "Calls by agent" panel (busiest first).
        $agents = (clone $query)->setEagerLoads([])
            ->selectRaw('user_id, COUNT(*) as total_calls')
            ->selectRaw("SUM(CASE WHEN status = 1 AND recording_url IS NOT NULL AND recording_url <> '' THEN 1 ELSE 0 END) as connected_calls")
            ->groupBy('user_id')
            ->orderByDesc('total_calls')
            ->limit(50)
            ->get();
        $agentNames = User::whereIn('id', $agents->pluck('user_id')->filter())->pluck('name', 'id');

        return [
            'total' => $total,
            'connected' => $connected,
            'no_response' => $total - $connected,
            'agents' => $agents->map(fn ($row) => [
                'user_id' => $row->user_id,
                'name' => $agentNames[$row->user_id] ?? 'Unassigned',
                'total' => (int) $row->total_calls,
                'connected' => (int) $row->connected_calls,
            ])->values(),
        ];
    }

    private function whereConnected($query)
    {
        return $query->where('status', 1)
            ->whereNotNull('recording_url')
            ->where('recording_url', '!=', '');
    }

    private function isConnected(CallLog $call): bool
    {
        return (int) $call->status === 1 && ! empty($call->recording_url);
    }

    private function isLive(CallLog $call): bool
    {
        return ! $call->completed_at
            && $call->started_at?->gt(now()->subHours(2))
            && ! in_array(strtolower((string) $call->plivo_status), self::TERMINAL_STATUSES, true);
    }

    /** The dialled contact, falling back to a number match, then the first contact. */
    private function contactFor(CallLog $call)
    {
        if ($call->contact) {
            return $call->contact;
        }
        $contacts = $call->lead?->contacts ?? collect();
        $target = substr(preg_replace('/\D+/', '', (string) $call->number), -10);

        return $contacts->first(fn ($contact) => $target !== '' && substr(preg_replace('/\D+/', '', (string) $contact->phone_number), -10) === $target)
            ?? $contacts->first();
    }

    private function formatDuration(int $seconds): string
    {
        return sprintf('%02d:%02d:%02d', intdiv($seconds, 3600), intdiv($seconds % 3600, 60), $seconds % 60);
    }

    private function isAdmin(): bool
    {
        return Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('Admin');
    }

    public function recording(CallLog $callLog)
    {
        abort_if(Gate::denies('lead_call_history_access'), 403, '403 Forbidden');
        $this->authorizeCallLog($callLog);
        abort_if(empty($callLog->recording_url), 404, 'Recording not available.');

        $requestHeaders = [];
        if (request()->hasHeader('Range')) {
            $requestHeaders['Range'] = request()->header('Range');
        }

        $recording = Http::withBasicAuth(
            config('services.plivo.auth_id'),
            config('services.plivo.auth_token')
        )->withHeaders($requestHeaders)->timeout(30)->get($callLog->recording_url);

        abort_unless($recording->successful(), 502, 'Unable to load recording from Plivo.');

        $headers = [
            'Content-Type' => $recording->header('Content-Type') ?: 'audio/mpeg',
            'Content-Disposition' => 'inline; filename="call-'.$callLog->id.'"',
            'Cache-Control' => 'private, max-age=3600',
            'Accept-Ranges' => $recording->header('Accept-Ranges') ?: 'bytes',
        ];

        foreach (['Content-Length', 'Content-Range', 'ETag', 'Last-Modified'] as $header) {
            if ($recording->header($header)) {
                $headers[$header] = $recording->header($header);
            }
        }

        return response($recording->body(), $recording->status(), $headers);
    }

    public function show(CallLog $callLog)
    {
        abort_if(Gate::denies('lead_call_history_access'), 403, '403 Forbidden');
        $this->authorizeCallLog($callLog);

        $callLog->load([
            'user:id,name,email',
            'lead:id,company_name,status',
            'lead.contacts:id,lead_id,name,phone_number',
            'lead.status_is:id,status_name,display_name',
            'feedbackStatus',
        ]);

        app(CallTranscriptionService::class)->sync($callLog);

        return view('call_logs.show', compact('callLog'));
    }

    public function transcribe(Request $request, CallLog $callLog, CallTranscriptionService $transcription)
    {
        abort_if(Gate::denies('call_management_transcribe'), 403, '403 Forbidden');
        $this->authorizeCallLog($callLog);
        abort_if(empty($callLog->recording_url), 422, 'Recording is not available.');

        if ($callLog->transcription_status === 'completed' && ! $request->boolean('regenerate')) {
            return back()->with('success', 'Transcript is already available.');
        }
        if ($callLog->transcription_status === 'processing' && $callLog->sarvam_job_id
            && $callLog->updated_at?->gt(now()->subMinutes(15))) {
            return back()->with('success', 'Transcript is already being generated.');
        }

        // Started inline and completed by the detail page's status checks, so no
        // queue worker or cron is needed on shared hosting.
        $transcription->start($callLog);

        return $callLog->transcription_status === 'failed'
            ? back()->with('error', 'Transcript could not be started. Please try again.')
            : back()->with('success', 'Generating transcript. It will appear here shortly.');
    }

    private function authorizeCallLog(CallLog $callLog): void
    {
        if (Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('Admin')) {
            return;
        }

        abort_unless(
            in_array((int) $callLog->user_id, array_map('intval', getUsersReportingToAuth()), true),
            403,
            '403 Forbidden'
        );
    }
}
