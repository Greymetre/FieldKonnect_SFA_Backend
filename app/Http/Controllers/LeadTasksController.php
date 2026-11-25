<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

use DataTables;
use Auth;

use App\Exports\ExcelExport;
use Excel;

use App\Models\Lead;
use Illuminate\Support\Str;

use App\Models\LeadTask;

class LeadTasksController extends Controller
{



    public function index(Request $request)
    {
        abort_if(Gate::denies('lead_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // User filter: everyone whose tasks this user can see (team + tasks they created).
        $taskUsers = \App\Models\User::whereIn('id', $this->visibleTasks('all')->whereNotNull('lead_tasks.assigned_to')->select('lead_tasks.assigned_to')->distinct())
            ->orderBy('name')->get(['id', 'name']);

        return view('lead-tasks.index', compact('taskUsers'));
    }

    /**
     * Tasks the current user may see.
     *  - "all": everything for superadmin, else the team's tasks (users reporting
     *    to them) plus tasks they created for anyone else.
     *  - "mine": only the tasks assigned to the current user.
     */
    private function visibleTasks(?string $scope = null)
    {
        $query = LeadTask::query();
        if ($scope === 'mine') {
            return $query->where('lead_tasks.assigned_to', auth()->id());
        }
        if (!auth()->user()->hasRole('superadmin')) {
            // Team's tasks, plus tasks the user created for anyone else.
            $query->where(function ($scope) {
                $scope->whereIn('lead_tasks.assigned_to', getUsersReportingToAuth())
                    ->orWhere('lead_tasks.created_by', auth()->id());
            });
        }

        return $query;
    }

    /** Tasks assigned to one user (only within the "All tasks" view). */
    private function applyUserFilter($query, Request $request): void
    {
        if ($request->input('scope') !== 'mine' && $request->filled('user_id')) {
            $query->where('lead_tasks.assigned_to', (int) $request->user_id);
        }
    }

    private function applyStatusFilter($query, ?string $status): void
    {
        if ($status === 'overdue') {
            $query->where('lead_tasks.status', '!=', 'completed')->where('lead_tasks.date', '<', now()->toDateString());
        } elseif ($status) {
            $query->where('lead_tasks.status', $status);
        }
    }

    public function getLeadTasks(Request $request)
    {
        $lead_tasks = $this->visibleTasks($request->input('scope'))->with(['lead:id,company_name,external_lead_id', 'assignUser:id,name']);

        if ($request->filled('search')) {
            $search = $request->search;
            $lead_tasks->where(function ($query) use ($search) {
                $query->where('description', 'like', '%'.$search.'%')
                    ->orWhere('priority', 'like', '%'.$search.'%')
                    ->orWhereHas('lead', fn ($subQuery) => $subQuery->where('company_name', 'like', '%'.$search.'%'))
                    ->orWhereHas('assignUser', fn ($subQuery) => $subQuery->where('name', 'like', '%'.$search.'%'));
            });
        }
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $lead_tasks->whereBetween('lead_tasks.date', [$request->start_date, $request->end_date]);
        }
        $this->applyUserFilter($lead_tasks, $request);

        // Counts for the status tabs (same filters, without the status filter).
        $countsBase = (clone $lead_tasks)->setEagerLoads([]);
        $statusCounts = (clone $countsBase)->toBase()->reorder()
            ->selectRaw('lead_tasks.status, COUNT(*) as total')->groupBy('lead_tasks.status')->pluck('total', 'status');
        $overdueCount = (clone $countsBase)->where('lead_tasks.status', '!=', 'completed')->where('lead_tasks.date', '<', now()->toDateString())->count();

        $this->applyStatusFilter($lead_tasks, $request->status);

        $lead_tasks = $lead_tasks->orderByRaw("
                CASE
                    WHEN lead_tasks.status = 'pending' THEN 0
                    WHEN lead_tasks.status = 'open' THEN 1
                    WHEN lead_tasks.status = 'in_progress' THEN 2
                    ELSE 3
                END
            ")
            ->orderBy('lead_tasks.date', 'asc')->orderBy('lead_tasks.time', 'asc')
            ->select('lead_tasks.*');

        $today = now()->startOfDay();

        // Plain values only; the page builds (and escapes) the HTML.
        return DataTables::of($lead_tasks)
            ->addColumn('company', fn ($task) => $task->lead->company_name ?? '')
            ->addColumn('lead_code', fn ($task) => $task->lead->external_lead_id ?? '')
            ->addColumn('lead_url', fn ($task) => $task->lead_id ? route('leads.show', $task->lead_id) : null)
            ->addColumn('assignee', fn ($task) => $task->assignUser->name ?? '')
            ->addColumn('status_label', fn ($task) => $task->statusLabel())
            ->addColumn('due_date', fn ($task) => $task->date ? date('d M Y', strtotime($task->date)) : '')
            ->addColumn('due_time', fn ($task) => $task->time ? date('h:i A', strtotime($task->time)) : '')
            ->addColumn('overdue_days', function ($task) use ($today) {
                if ($task->status === 'completed' || ! $task->date) {
                    return 0;
                }
                $due = \Carbon\Carbon::parse($task->date)->startOfDay();
                return $due->lt($today) ? $due->diffInDays($today) : 0;
            })
            ->addColumn('seen', fn ($task) => $task->seen_at?->format('d M, h:i A'))
            ->addColumn('opened', fn ($task) => $task->opened_at?->format('d M, h:i A') ?? ($task->open_date ? date('d M', strtotime($task->open_date)) : null))
            ->addColumn('completed', fn ($task) => $task->completed_at?->format('d M, h:i A') ?? ($task->status === 'completed' && $task->close_date ? date('d M', strtotime($task->close_date)) : null))
            ->editColumn('description', fn ($task) => trim(html_entity_decode(strip_tags((string) $task->description))))
            ->with('status_counts', [
                'all' => (int) $statusCounts->sum(),
                'pending' => (int) ($statusCounts['pending'] ?? 0),
                'open' => (int) ($statusCounts['open'] ?? 0),
                'in_progress' => (int) ($statusCounts['in_progress'] ?? 0),
                'completed' => (int) ($statusCounts['completed'] ?? 0),
                'overdue' => $overdueCount,
            ])
            ->escapeColumns([])
            ->make(true);
    }

    function exportTasks(Request $request)
    {
        $filename = 'tasks.xlsx';

        $results_per_page = 8000;
        $page_number = intval($request->input('page_number'));
        $page_result = ($page_number - 1) * $results_per_page;

        $lead_tasks = $this->visibleTasks($request->input('scope'))->with(['lead', 'createdby', 'assignUser']);
        $this->applyStatusFilter($lead_tasks, $request->status);
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $lead_tasks->whereBetween('lead_tasks.date', [$request->start_date, $request->end_date]);
        }
        $this->applyUserFilter($lead_tasks, $request);
        $lead_tasks = $lead_tasks->orderByRaw("
                CASE 
                    WHEN lead_tasks.status = 'pending' THEN 0
                    WHEN lead_tasks.status = 'open' THEN 1
                    WHEN lead_tasks.status = 'in_progress' THEN 2
                    ELSE 3
                END
            ")
            ->orderBy('date', 'asc')
            ->orderBy('time', 'asc')->orderBy('lead_tasks.date', 'asc')->orderBy('lead_tasks.time', 'asc')->get();
        $data = $lead_tasks->map(function ($item, $key) {

            return [
                $item->id,
                $item->lead->company_name ?? '',
                $item->description,
                $item->priority,
                ucwords(str_replace('_', ' ', $item->status)),
                date("M d,Y", strtotime($item->created_at)),
                date("M d,Y", strtotime($item->date)),
                $item->close_date ? date("M d,Y", strtotime($item->close_date)) : '',
                $item->remark,
                $item->createdby->name ?? '',
                $item->assignUser->name ?? '',
            ];
        })->toArray();

        $export = new ExcelExport([
            'Id',
            'Name',
            'Description',
            'Priority',
            'Task Status',
            'Open Date',
            'Due Date',
            'Close Date',
            'Remarks',
            'Created By',
            'Assign to',
        ], $data);

        return Excel::download($export, $filename);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'lead_id' => 'required',
            'assigned_to' => 'required',
            'description' => 'required',
            'date' => 'required',
            'priority' => 'required',
            'status' => 'required',
        ];

        $request->validate($rules);
        $created_by = Auth::id();
        $task_id = $request->task_id;
        $lead_task = LeadTask::where(['id' => $task_id])->first();
        if (!$request->status && empty($request->status)) {
            $request->status = 'open';
        }
        if ($lead_task) {
            if ($request->status == 'completed' && $lead_task->status != 'completed') {
                $lead_task->update(['close_date' => date('Y-m-d')]);

                $msg = '📝 Your assigned task ' . $lead_task->description .
                    ' related to lead: ' . Str::limit($lead_task->lead->company_name, 10, '...') .
                    ' has been completed.';


                SendPushNotification($lead_task->created_by, $msg, 'task');
                StoreLeadNotification($lead_task->id, 'Assigned Task', $msg, $lead_task->created_by, 'task');
            }

            $lead_task->update(['assigned_to' => $request->assigned_to, 'lead_id' => $request->lead_id, 'created_by' => $created_by, 'description' => $request->description, 'date' => $request->date, 'time' => $request->time, 'priority' => $request->priority, 'status' => $request->status]);
            $request->session()->flash('message_success', __('Lead Task Update successfully.'));
        } else {
            $lead_task = LeadTask::create(['assigned_to' => $request->assigned_to, 'lead_id' => $request->lead_id, 'created_by' => $created_by, 'description' => $request->description, 'date' => $request->date, 'time' => $request->time, 'priority' => $request->priority, 'status' => $request->status]);
            $request->session()->flash('message_success', __('Lead Task Added successfully.'));

            SendPushNotification($request->assigned_to, '📝 A new task has been assigned to you.', 'task');
            StoreLeadNotification($lead_task->id, 'Assigned Task', '📝 A new task has been assigned to you.', $request->assigned_to, 'task');
        }
        if ($request->status == 'open') {
            $lead_task->update(['open_date' => date('Y-m-d')]);
        }
        if ($request->status == 'completed') {
            $lead_task->update(['close_date' => date('Y-m-d')]);
        }

        return redirect()->back();
    }




    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Lead  $lead
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, LeadTask $leadTask)
    // {
    //     $rules = [
    //         'lead_id'=>'required',
    //         'assigned_to'=>'required',
    //         'description'=>'required',
    //         'date'=>'required',
    //         'time'=>'required',

    //     ];

    //     $request->validate($rules);
    //     $data = $request->all();
    //     $created_by = Auth::id(); 
    //     $lead_task = LeadTask::where(['id'=>$leadTask->id])->first();
    //     if($lead_task){
    //         $lead_task->update(['assigned_to'=>$request->assigned_to,'lead_id'=>$request->lead_id,'created_by'=>$created_by,'description'=>$request->description,'date'=>$request->date,'time'=>$request->time]);
    //          $request->session()->flash('message_success',__('Lead Task Added successfully.'));
    //     }else{
    //          $request->session()->flash('message_info',__('something went wrong.'));

    //     }

    //     return redirect()->back();
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Lead  $lead
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, LeadTask $leadTask)
    {
        $leadTask->delete();
        $request->session()->flash('message_success', __('Lead Task deleted successfully.'));
        return redirect()->back();
    }

    public function checkboxAction(Request $request)
    {
        $taskIds = array_filter(array_map('intval', explode(',', (string) $request->lead_ids)));
        if (empty($taskIds)) {
            return redirect()->back();
        }
        // Only tasks the user can see may be deleted.
        $deleted = $this->visibleTasks()->whereIn('lead_tasks.id', $taskIds)->delete();
        $request->session()->flash('message_success', $deleted.' task(s) deleted successfully.');
        return redirect()->back();
    }

    public function change_status(Request $request)
    {
        $request->validate(['status' => 'required|in:'.implode(',', array_keys(LeadTask::STATUS_LABELS))]);
        // Visible team tasks, or a task the user created themselves.
        $lead_task = $this->visibleTasks()->whereKey($request->task_id)->first()
            ?? LeadTask::whereKey($request->task_id)->where('created_by', auth()->id())->first();
        if (! $lead_task) {
            return response()->json(['status' => 'error']);
        }

        $lead_task->applyStatus($request->status, $request->remark, Auth::user());
        $request->session()->flash('message_success', __('Lead Task status changed successfully.'));

        return response()->json(['status' => 'success']);
    }
}
