<?php

namespace App\Http\Controllers;

use App\Exports\ExcelExport;
use App\Http\Controllers\Controller;
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
    /**
     * Get call logs (optionally filter by user or lead).
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('call_management_access'), 403, '403 Forbidden');
        $user_ids = getUsersReportingToAuth();
        $users = User::select('id', 'name')->where('active', 'Y');

        if (!Auth::user()->hasRole('superadmin') && !Auth::user()->hasRole('Admin')) {
            $users = $users->whereIn('id', $user_ids);
        }
        $users = $users->get();

        if ($request->ajax()) {
            // Base query
            $query = CallLog::with(['user:id,name', 'lead:id,company_name,status', 'lead.contacts:id,lead_id,name,phone_number'])
                ->whereNotNull('lead_id')
                ->whereNull('call_management_entry_id');

            if ($request->has('user_id') && !empty($request->user_id)) {
                $query->where('user_id', $request->user_id);
            }

            if ($request->has('start_date') && !empty($request->start_date)) {
                $query->whereDate('started_at', '>=', $request->start_date);
            }

            if ($request->has('end_date') && !empty($request->end_date)) {
                $query->whereDate('started_at', '<=', $request->end_date);
            }

            if (!Auth::user()->hasRole('superadmin') && !Auth::user()->hasRole('Admin')) {
                $query->whereIn('user_id', $user_ids); // ✅ should be user_id not id
            }

            if ($request->has('lead_id')) {
                $query->where('lead_id', $request->lead_id);
            }
            $statusSearch = trim((string) $request->input('columns.6.search.value', ''));
            if ($statusSearch !== '') {
            
                // Adjust this according to how your status is stored
                if (strtolower($statusSearch) === 'connected') {
                    $query->where('status', 1)
                        ->whereNotNull('recording_url')
                        ->where('recording_url', '!=', '');
                } elseif (in_array(strtolower($statusSearch), ['no response', 'not connected'], true)) {
                    $query->where(function ($statusQuery) {
                        $statusQuery->where('status', 0)
                            ->orWhereNull('recording_url')
                            ->orWhere('recording_url', '');
                    });
                } else {
                    // Optional: fuzzy match for text
                    $query->where('status', 'like', "%{$statusSearch}%");
                }
            }
            // Clone query for summary counts before DataTables' text search/pagination.
            $countsQuery = clone $query;

            $totalCalls = $countsQuery->count();
            $connectedCalls = (clone $countsQuery)->where('status', 1)
                ->whereNotNull('recording_url')
                ->where('recording_url', '!=', '')
                ->count();
            $noResponseCalls = (clone $countsQuery)->where(function ($statusQuery) {
                $statusQuery->where('status', 0)
                    ->orWhereNull('recording_url')
                    ->orWhere('recording_url', '');
            })->count();
            $totalDurationSeconds = (int) (clone $countsQuery)
                ->whereNotNull('recording_url')
                ->where('recording_url', '!=', '')
                ->sum('duration');

            // Convert seconds to HH:MM:SS
            $hours = floor($totalDurationSeconds / 3600);
            $minutes = floor(($totalDurationSeconds % 3600) / 60);
            $seconds = $totalDurationSeconds % 60;
            $formattedDuration = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

            $search = trim((string) $request->input('search.value', ''));
            if ($search !== '') {
                $query->where(function ($searchQuery) use ($search) {
                    $searchQuery->where('number', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('lead', function ($leadQuery) use ($search) {
                            $leadQuery->where('company_name', 'like', "%{$search}%")
                                ->orWhereHas('contacts', function ($contactQuery) use ($search) {
                                    $contactQuery->where('name', 'like', "%{$search}%");
                                });
                        });
                });
            }

            $filteredCalls = (clone $query)->count();
            $start = max(0, (int) $request->input('start', 0));
            $length = min(100, max(1, (int) $request->input('length', 10)));
            $callLogs = $query->orderByDesc('started_at')->skip($start)->take($length)->get();

            $data = $callLogs->map(function (CallLog $row) {
                $duration = (int) $row->duration;
                $connected = (int) $row->status === 1 && !empty($row->recording_url);
                $badge = $connected ? 'badge-success' : 'badge-danger';
                $label = $connected ? 'Connected' : 'Not Connected';
                $contact = $row->lead?->contacts->first();

                $recording = '<span class="text-muted">Processing / unavailable</span>';
                if (!empty($row->recording_url)) {
                    $recording = '<audio controls preload="metadata" style="width:220px;height:36px" src="'
                        .e(route('call-management.recording', $row)).'">'
                        .'Your browser does not support audio playback.</audio>';
                }

                return [
                    'detail_url' => route('call-management.show', $row),
                    'user' => ['name' => $row->user?->name ?: '-'],
                    'customer_name' => $contact?->name ?: '-',
                    'lead' => ['company_name' => $row->lead?->company_name ?: '-'],
                    'number' => $row->number ?: '-',
                    'started_at' => $row->started_at?->format('d/m/Y h:i A') ?: '-',
                    'duration' => sprintf(
                        '%02d:%02d:%02d',
                        intdiv($duration, 3600),
                        intdiv($duration % 3600, 60),
                        $duration % 60
                    ),
                    'status' => '<span class="badge '.$badge.'">'.$label.'</span>',
                    'recording' => $recording,
                ];
            })->values();

            return response()->json([
                'draw' => (int) $request->input('draw', 0),
                'recordsTotal' => $totalCalls,
                'recordsFiltered' => $filteredCalls,
                'data' => $data,
                'summary' => [
                    'total' => $totalCalls,
                    'connected' => $connectedCalls,
                    'no_response' => $noResponseCalls,
                    'total_duration' => $formattedDuration,
                ],
            ]);
        }

        return view('call_logs.index', compact('users'));
    }

    public function download(Request $request)
    {
        abort_if(Gate::denies('call_management_access'), 403, '403 Forbidden');
        $filename = 'Call Logs.xlsx';
        $user_ids = getUsersReportingToAuth();

        $query = CallLog::with(['user:id,name', 'lead:id,company_name,status', 'lead.contacts:id,lead_id,name,phone_number'])
            ->whereNotNull('lead_id')
            ->whereNull('call_management_entry_id');

        if ($request->has('user_id') && !empty($request->user_id)) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('start_date') && !empty($request->start_date)) {
            $query->whereDate('started_at', '>=', $request->start_date);
        }

        if ($request->has('end_date') && !empty($request->end_date)) {
            $query->whereDate('started_at', '<=', $request->end_date);
        }

        if (!Auth::user()->hasRole('superadmin') && !Auth::user()->hasRole('Admin')) {
            $query->whereIn('user_id', $user_ids); // ✅ should be user_id not id
        }

        if ($request->has('lead_id')) {
            $query->where('lead_id', $request->lead_id);
        }

        $call_logs = $query->orderBy('started_at', 'desc')->get();

        $rows = [];

        $headers = ['Agent', 'Customer', 'Lead', 'Contact No', 'Date & Time', 'Call Duration', 'Call Status', 'Plivo Status', 'Call UUID', 'Recording URL', 'Cost', 'Remark'];

        foreach ($call_logs as $call_log) {
            $seconds = (int) $call_log->duration;

            $hours = floor($seconds / 3600);
            $minutes = floor(($seconds % 3600) / 60);
            $seconds = $seconds % 60;

            $call_duration = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
            $rows[] = [
                $call_log->user?->name ?? 'Not Found',
                optional(optional($call_log->lead)->contacts->first())->name ?: 'Not Found',
                $call_log->lead ? $call_log->lead->company_name : 'Not Found',
                $call_log->number,
                date('d/m/Y h:i A', strtotime($call_log->started_at)),
                $call_duration,
                $call_log->status == 0 ? 'No Response' : 'Connected',
                $call_log->plivo_status,
                $call_log->plivo_call_uuid,
                $call_log->recording_url,
                $call_log->cost,
                '',
            ];
        }

        // ✅ Export
        $export = new ExcelExport($headers, $rows);
        return Excel::download($export, $filename);


    }

    public function recording(CallLog $callLog)
    {
        abort_if(Gate::denies('call_management_access'), 403, '403 Forbidden');
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
        abort_if(Gate::denies('call_management_access'), 403, '403 Forbidden');
        $this->authorizeCallLog($callLog);

        $callLog->load([
            'user:id,name,email',
            'lead:id,company_name,status',
            'lead.contacts:id,lead_id,name,phone_number',
            'lead.status_is:id,status_name,display_name',
            'feedbackStatus',
        ]);

        return view('call_logs.show', compact('callLog'));
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
