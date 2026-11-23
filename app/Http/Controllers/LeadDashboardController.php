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

        // ---- New leads per day (in range) -------------------------------------
        $perDay = (clone $leads)->whereBetween('created_at', [$from, $to])
            ->selectRaw('DATE(created_at) as day, COUNT(*) as total')->groupBy('day')->pluck('total', 'day');
        $trend = [];
        for ($day = $from->copy(); $day->lte($to); $day->addDay()) {
            $trend[] = ['date' => $day->format('d M'), 'full' => $day->format('D, d M Y'), 'total' => (int) ($perDay[$day->toDateString()] ?? 0)];
        }

        // ---- Pipeline split (all leads in scope) ------------------------------
        $typeNames = Status::where('module', 'LeadStatus')->pluck('display_name', 'id');
        $byType = (clone $leads)->selectRaw('status, COUNT(*) as total')->groupBy('status')->orderByDesc('total')->get()
            ->map(fn ($row) => ['label' => (int) $row->status === 0 ? 'Pending' : ($typeNames[$row->status] ?? 'Other'), 'total' => (int) $row->total])
            ->groupBy('label')->map(fn ($rows, $label) => ['label' => $label, 'total' => $rows->sum('total')])->values()->sortByDesc('total')->values();

        $bySource = (clone $leads)->selectRaw("COALESCE(NULLIF(lead_source, ''), 'Not set') as source, COUNT(*) as total")
            ->groupBy('source')->orderByDesc('total')->get()
            ->map(fn ($row) => ['label' => $row->source, 'total' => (int) $row->total]);

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
        $outcomeNames = Status::whereIn('id', (clone $callsInRange)->whereNotNull('feedback_status_id')->distinct()->pluck('feedback_status_id'))
            ->get()->mapWithKeys(fn ($status) => [$status->id => $status->display_name ?: $status->status_name]);
        $outcomes = (clone $callsInRange)->selectRaw('feedback_status_id, COUNT(*) as total')->groupBy('feedback_status_id')->orderByDesc('total')->get()
            ->map(fn ($row) => ['label' => $row->feedback_status_id ? ($outcomeNames[$row->feedback_status_id] ?? 'Other') : 'No remark yet', 'total' => (int) $row->total]);

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

        return view('lead-dashboard.index', compact(
            'users', 'userId', 'from', 'to',
            'totalLeads', 'newLeads', 'convertedLeads', 'callsMade', 'callsConnected', 'talkSeconds',
            'openTasks', 'overdueTasks', 'pendingDrafts',
            'trend', 'byType', 'bySource', 'progress', 'outcomes', 'agents', 'followUps', 'today'
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
