<?php

namespace App\Http\Controllers;

use App\Models\CallLog;
use App\Models\Lead;
use App\Models\LeadNote;
use App\Models\LeadTask;
use App\Models\Status;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Lead Management > Lead Dashboard: pipeline, calling and follow-up overview.
 * Admins see everything; other users see their team's leads, calls and tasks.
 */
class LeadDashboardController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(Auth::user()->can('lead_dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $isAdmin = Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('Admin');
        $teamIds = $isAdmin ? null : array_map('intval', getUsersReportingToAuth());

        // Filters: date range (default last 30 days) and one assignee.
        $to = $this->date($request->input('to')) ?? now()->endOfDay();
        $from = $this->date($request->input('from')) ?? now()->subDays(29)->startOfDay();
        if ($from->gt($to)) {
            [$from, $to] = [$to->copy()->startOfDay(), $from->copy()->endOfDay()];
        }
        $to = $to->copy()->endOfDay();
        $from = $from->copy()->startOfDay();
        $userId = $request->filled('user_id') ? (int) $request->user_id : null;
        if ($userId && $teamIds !== null && ! in_array($userId, $teamIds, true)) {
            $userId = null;
        }

        $users = User::where('active', 'Y')
            ->whereDoesntHave('roles', fn ($query) => $query->whereIn('id', config('constants.customer_roles')))
            ->when($teamIds !== null, fn ($query) => $query->whereIn('id', $teamIds))
            ->orderBy('name')->get(['id', 'name']);

        // ---- Scoped base queries -------------------------------------------------
        $leads = Lead::query()
            ->when($teamIds !== null, fn ($query) => $query->where(fn ($scope) => $scope->whereIn('assign_to', $teamIds)->orWhereIn('created_by', $teamIds)))
            ->when($userId, fn ($query) => $query->where('assign_to', $userId));

        $calls = CallLog::query()->whereNotNull('lead_id')->whereNull('call_management_entry_id')
            ->when($teamIds !== null, fn ($query) => $query->whereIn('user_id', $teamIds))
            ->when($userId, fn ($query) => $query->where('user_id', $userId));
        $callsInRange = (clone $calls)->whereBetween('started_at', [$from, $to]);

        $tasks = LeadTask::query()
            ->when($teamIds !== null, fn ($query) => $query->where(fn ($scope) => $scope->whereIn('assigned_to', $teamIds)->orWhere('created_by', Auth::id())))
            ->when($userId, fn ($query) => $query->where('assigned_to', $userId));

        $today = now()->toDateString();
        $connected = fn ($query) => $query->where('status', 1)->whereNotNull('recording_url')->where('recording_url', '!=', '');

        // ---- KPI tiles ---------------------------------------------------------
        $totalLeads = (clone $leads)->count();
        $newLeads = (clone $leads)->whereBetween('created_at', [$from, $to])->count();
        $convertedLeads = (clone $leads)->whereNotNull('customer_id')->count();
        $callsMade = (clone $callsInRange)->count();
        $callsConnected = $connected(clone $callsInRange)->count();
        $talkSeconds = (int) $connected(clone $callsInRange)->sum('duration');
        $openTasks = (clone $tasks)->where('status', '!=', 'completed')->count();
        $overdueTasks = (clone $tasks)->where('status', '!=', 'completed')->where('date', '<', $today)->count();
        $pendingDrafts = LeadNote::whereIn('type', [LeadNote::TYPE_EMAIL, LeadNote::TYPE_LINKEDIN])
            ->whereNull('approved_at')
            ->whereIn('lead_id', (clone $leads)->select('id'))
            ->count();

        // ---- Previous period of the same length, for the ↑/↓ comparison ------
        $periodDays = $from->diffInDays($to) + 1;
        $prevFrom = $from->copy()->subDays($periodDays);
        $prevTo = $from->copy()->subSecond();
        $prevCalls = (clone $calls)->whereBetween('started_at', [$prevFrom, $prevTo]);
        $previous = [
            'newLeads' => (clone $leads)->whereBetween('created_at', [$prevFrom, $prevTo])->count(),
            'callsMade' => (clone $prevCalls)->count(),
            'callsConnected' => $connected(clone $prevCalls)->count(),
        ];
        $previous['connectRate'] = $previous['callsMade'] > 0 ? round($previous['callsConnected'] * 100 / $previous['callsMade']) : null;

        // ---- Today -----------------------------------------------------------
        $todayCalls = (clone $calls)->whereDate('started_at', $today);
        $todayStats = [
            'calls' => (clone $todayCalls)->count(),
            'connected' => $connected(clone $todayCalls)->count(),
            'newLeads' => (clone $leads)->whereDate('created_at', $today)->count(),
            'tasksDue' => (clone $tasks)->where('status', '!=', 'completed')->where('date', $today)->count(),
        ];

        // ---- Funnel (all leads in scope) -------------------------------------
        $leadIdsSub = (clone $leads)->select('leads.id');
        $doneStatusId = Status::where('status_name', Status::LEAD_CALL_DONE)->value('id');
        $funnel = [
            ['label' => 'Leads', 'total' => $totalLeads, 'hint' => 'All leads'],
            ['label' => 'Called', 'total' => CallLog::whereIn('lead_id', $leadIdsSub)->distinct()->count('lead_id'), 'hint' => 'At least one call'],
            ['label' => 'Connected', 'total' => $connected(CallLog::whereIn('lead_id', $leadIdsSub))->distinct()->count('lead_id'), 'hint' => 'A call was answered'],
            ['label' => 'Call done', 'total' => $doneStatusId ? CallLog::whereIn('lead_id', $leadIdsSub)->where('feedback_status_id', $doneStatusId)->distinct()->count('lead_id') : 0, 'hint' => 'Marked "Call Done"'],
            ['label' => 'Converted', 'total' => $convertedLeads, 'hint' => 'Became a customer'],
        ];

        // ---- New leads per day (in range) -------------------------------------
        $perDay = (clone $leads)->whereBetween('created_at', [$from, $to])
            ->selectRaw('DATE(created_at) as day, COUNT(*) as total')->groupBy('day')->pluck('total', 'day');
        $callsPerDay = (clone $callsInRange)->selectRaw('DATE(started_at) as day, COUNT(*) as total')
            ->selectRaw("SUM(CASE WHEN status = 1 AND recording_url IS NOT NULL AND recording_url <> '' THEN 1 ELSE 0 END) as connected")
            ->groupBy('day')->get()->keyBy('day');
        $trend = [];
        for ($day = $from->copy(); $day->lte($to); $day->addDay()) {
            $key = $day->toDateString();
            $dayCalls = (int) ($callsPerDay[$key]->total ?? 0);
            $dayConnected = (int) ($callsPerDay[$key]->connected ?? 0);
            $trend[] = [
                'date' => $day->format('d M'),
                'full' => $day->format('D, d M Y'),
                'total' => (int) ($perDay[$key] ?? 0),
                'calls' => $dayCalls,
                'connected' => $dayConnected,
                'missed' => max(0, $dayCalls - $dayConnected),
            ];
        }

        // ---- Pipeline split (all leads in scope) ------------------------------
        $typeNames = Status::where('module', 'LeadStatus')->pluck('display_name', 'id');
        $byType = (clone $leads)->selectRaw('status, COUNT(*) as total')->groupBy('status')->orderByDesc('total')->get()
            ->map(fn ($row) => ['label' => (int) $row->status === 0 ? 'Pending' : ($typeNames[$row->status] ?? 'Other'), 'total' => (int) $row->total])
            ->groupBy('label')->map(fn ($rows, $label) => ['label' => $label, 'total' => $rows->sum('total')])->values()->sortByDesc('total')->values();

        // ---- Calling progress (callable contacts = contacts with a phone) -----
        $progressRows = DB::query()->fromSub(
            (clone $leads)->toBase()->select('leads.id')
                ->selectSub(fn ($q) => $q->from('lead_contacts')->whereColumn('lead_contacts.lead_id', 'leads.id')
                    ->whereNotNull('phone_number')->where('phone_number', '!=', '')->selectRaw('COUNT(*)'), 'dialable')
                ->selectSub(fn ($q) => $q->from('lead_contacts')->whereColumn('lead_contacts.lead_id', 'leads.id')
                    ->whereExists(fn ($e) => $e->from('call_logs')->whereColumn('call_logs.lead_contact_id', 'lead_contacts.id')->selectRaw('1'))
                    ->selectRaw('COUNT(*)'), 'called'),
            'progress'
        )->selectRaw('SUM(CASE WHEN dialable = 0 THEN 1 ELSE 0 END) as no_phone')
            ->selectRaw('SUM(CASE WHEN dialable > 0 AND called = 0 THEN 1 ELSE 0 END) as not_called')
            ->selectRaw('SUM(CASE WHEN dialable > 0 AND called > 0 AND called < dialable THEN 1 ELSE 0 END) as partial')
            ->selectRaw('SUM(CASE WHEN dialable > 0 AND called >= dialable THEN 1 ELSE 0 END) as all_called')
            ->first();
        $progress = [
            ['key' => 'all_called', 'label' => 'All contacts called', 'total' => (int) ($progressRows->all_called ?? 0)],
            ['key' => 'partial', 'label' => 'Partially called', 'total' => (int) ($progressRows->partial ?? 0)],
            ['key' => 'not_called', 'label' => 'Not called', 'total' => (int) ($progressRows->not_called ?? 0)],
            ['key' => 'no_phone', 'label' => 'No phone number', 'total' => (int) ($progressRows->no_phone ?? 0)],
        ];

        // ---- Call outcomes (in range) -----------------------------------------
        $outcomeStatuses = Status::whereIn('id', (clone $callsInRange)->whereNotNull('feedback_status_id')->distinct()->pluck('feedback_status_id'))->get()->keyBy('id');
        $outcomeTones = [
            'lead_call_done' => 'good', 'lead_call_no_response' => 'warn', 'lead_call_switch_off' => 'muted',
            'lead_call_wrong_number' => 'bad', 'lead_call_back' => 'info',
        ];
        $outcomes = (clone $callsInRange)->selectRaw('feedback_status_id, COUNT(*) as total')->groupBy('feedback_status_id')->get()
            ->map(function ($row) use ($outcomeStatuses, $outcomeTones) {
                $status = $row->feedback_status_id ? ($outcomeStatuses[$row->feedback_status_id] ?? null) : null;
                if (! $row->feedback_status_id) {
                    return ['label' => 'No remark yet', 'tone' => 'muted', 'total' => (int) $row->total];
                }
                if ($status && $status->module === Status::MODULE_LEAD_CALL_FEEDBACK) {
                    return ['label' => $status->display_name ?: $status->status_name, 'tone' => $outcomeTones[$status->status_name] ?? 'info', 'total' => (int) $row->total];
                }
                // Older app builds stored the lead type as the call result.
                return ['label' => 'Old app remark', 'tone' => 'muted', 'total' => (int) $row->total];
            })
            ->groupBy('label')
            ->map(fn ($rows, $label) => ['label' => $label, 'tone' => $rows->first()['tone'], 'total' => $rows->sum('total')])
            ->sortByDesc('total')->values();

        // ---- Per agent -----------------------------------------------------------
        $agentLeads = (clone $leads)->whereNotNull('assign_to')->selectRaw('assign_to as user_id, COUNT(*) as total')->groupBy('assign_to')->pluck('total', 'user_id');
        $agentCalled = DB::query()->fromSub(
            (clone $leads)->toBase()->whereNotNull('assign_to')->select('leads.assign_to')
                ->whereExists(fn ($e) => $e->from('lead_contacts')->whereColumn('lead_contacts.lead_id', 'leads.id')
                    ->whereExists(fn ($c) => $c->from('call_logs')->whereColumn('call_logs.lead_contact_id', 'lead_contacts.id')->selectRaw('1'))->selectRaw('1')),
            'called_leads'
        )->selectRaw('assign_to as user_id, COUNT(*) as total')->groupBy('assign_to')->pluck('total', 'user_id');
        $agentCalls = (clone $callsInRange)->selectRaw('user_id, COUNT(*) as total')
            ->selectRaw("SUM(CASE WHEN status = 1 AND recording_url IS NOT NULL AND recording_url <> '' THEN 1 ELSE 0 END) as connected")
            ->groupBy('user_id')->get()->keyBy('user_id');
        $agentTasks = (clone $tasks)->where('status', '!=', 'completed')->selectRaw('assigned_to as user_id, COUNT(*) as open_tasks')
            ->selectRaw('SUM(CASE WHEN date < ? THEN 1 ELSE 0 END) as overdue', [$today])
            ->groupBy('assigned_to')->get()->keyBy('user_id');

        $agentIds = collect($agentLeads->keys())->merge($agentCalls->keys())->merge($agentTasks->keys())->filter()->unique();
        $agentNames = User::whereIn('id', $agentIds)->pluck('name', 'id');
        $agents = $agentIds->map(function ($id) use ($agentNames, $agentLeads, $agentCalled, $agentCalls, $agentTasks) {
            $leadsCount = (int) ($agentLeads[$id] ?? 0);
            return [
                'name' => $agentNames[$id] ?? 'Unknown',
                'leads' => $leadsCount,
                'called' => (int) ($agentCalled[$id] ?? 0),
                'calls' => (int) ($agentCalls[$id]->total ?? 0),
                'connected' => (int) ($agentCalls[$id]->connected ?? 0),
                'open_tasks' => (int) ($agentTasks[$id]->open_tasks ?? 0),
                'overdue' => (int) ($agentTasks[$id]->overdue ?? 0),
            ];
        })->sortByDesc('leads')->values();

        // ---- Follow-ups: overdue + next 7 days --------------------------------
        $followUps = (clone $tasks)->with(['lead:id,company_name', 'assignUser:id,name'])
            ->where('status', '!=', 'completed')
            ->where('date', '<=', now()->addDays(7)->toDateString())
            ->orderBy('date')->orderBy('time')->limit(12)->get();

        // ---- Action center: pending work, each tile opens the screen that lists it
        $tomorrow = now()->addDay()->toDateString();
        $weekAhead = now()->addDays(7)->toDateString();
        $progressBy = collect($progress)->keyBy('key');
        $leadUrl = fn (array $params) => url('leads').'?'.http_build_query(array_filter($params + ['user_id' => $userId]));
        $callUrl = fn (array $params) => route('call-management.index').'?'.http_build_query(array_filter($params + ['user_id' => $userId]));
        $taskUrl = fn (array $params) => url('lead-tasks').'?'.http_build_query(array_filter($params));
        $actions = [
            ['icon' => 'phone_disabled', 'tone' => 'blue', 'label' => 'Leads not called', 'hint' => 'No contact called yet',
                'total' => $progressBy['not_called']['total'] ?? 0, 'url' => $leadUrl(['call_progress' => 'not_called'])],
            ['icon' => 'phone_in_talk', 'tone' => 'warn', 'label' => 'Partially called', 'hint' => 'Some contacts still left',
                'total' => $progressBy['partial']['total'] ?? 0, 'url' => $leadUrl(['call_progress' => 'partial'])],
            ['icon' => 'running_with_errors', 'tone' => 'bad', 'label' => 'Overdue tasks', 'hint' => 'Past due, not completed',
                'total' => $overdueTasks, 'url' => $taskUrl(['status' => 'overdue'])],
            ['icon' => 'today', 'tone' => 'info', 'label' => 'Tasks due today', 'hint' => 'Follow-ups planned today',
                'total' => $todayStats['tasksDue'], 'url' => $taskUrl(['start_date' => $today, 'end_date' => $today])],
            ['icon' => 'event_note', 'tone' => 'violet', 'label' => 'Upcoming tasks', 'hint' => 'Due in the next 7 days',
                'total' => (clone $tasks)->where('status', '!=', 'completed')->whereBetween('date', [$tomorrow, $weekAhead])->count(),
                'url' => $taskUrl(['start_date' => $tomorrow, 'end_date' => $weekAhead])],
            ['icon' => 'edit_note', 'tone' => 'orange', 'label' => 'Calls without remark', 'hint' => 'Outcome not saved · '.$from->format('d M').' – '.$to->format('d M'),
                'total' => (clone $callsInRange)->whereNull('feedback_status_id')->count(),
                'url' => $callUrl(['call_status' => 'no_remark', 'start_date' => $from->toDateString(), 'end_date' => $to->toDateString()])],
            ['icon' => 'call_missed', 'tone' => 'muted', 'label' => 'Not connected today', 'hint' => 'Calls to try again',
                'total' => max(0, $todayStats['calls'] - $todayStats['connected']),
                'url' => $callUrl(['call_status' => 'not_connected', 'start_date' => $today, 'end_date' => $today])],
        ];
        $actions[] = Auth::user()->can('draft_approval')
            ? ['icon' => 'fact_check', 'tone' => 'good', 'label' => 'Drafts to approve', 'hint' => 'Email & LinkedIn drafts',
                'total' => $pendingDrafts, 'url' => route('draft-approvals.index')]
            : ['icon' => 'call', 'tone' => 'good', 'label' => 'Connected today', 'hint' => 'Answered calls today',
                'total' => $todayStats['connected'],
                'url' => $callUrl(['call_status' => 'connected', 'start_date' => $today, 'end_date' => $today])];

        // ---- Recent calls ------------------------------------------------------
        $recentCalls = (clone $calls)->with(['user:id,name', 'lead:id,company_name', 'contact:id,name', 'feedbackStatus:id,display_name,status_name,module'])
            ->orderByDesc('started_at')->limit(8)->get();

        return view('lead-dashboard.index', compact(
            'previous', 'todayStats', 'funnel', 'actions', 'recentCalls', 'outcomeTones',
            'users', 'userId', 'from', 'to',
            'totalLeads', 'newLeads', 'convertedLeads', 'callsMade', 'callsConnected', 'talkSeconds',
            'openTasks', 'overdueTasks', 'pendingDrafts',
            'trend', 'byType', 'progress', 'outcomes', 'agents', 'followUps', 'today'
        ));
    }

    private function date($value): ?Carbon
    {
        if (! $value) {
            return null;
        }
        try {
            return Carbon::createFromFormat('Y-m-d', $value);
        } catch (\Throwable $e) {
            return null;
        }
    }
}
