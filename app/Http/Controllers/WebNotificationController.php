<?php

namespace App\Http\Controllers;

use App\Models\LeadTask;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class WebNotificationController extends Controller
{
    public function page(Request $request): View
    {
        $notifications = Notification::query()
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(20);

        return view('web-notifications.index', compact('notifications'));
    }

    public function index(Request $request): JsonResponse
    {
        $userId = $request->user()->id;
        $notifications = Notification::query()
            ->where('user_id', $userId)
            ->latest()
            ->limit(20)
            ->get(['id', 'type', 'data', 'image', 'read', 'model', 'model_id', 'created_at'])
            ->map(fn (Notification $notification) => [
                'id' => $notification->id,
                'title' => $notification->type ?: 'FieldKonnect',
                'message' => $notification->data,
                'image' => $notification->image,
                'read' => (bool) $notification->read,
                'created_at' => $notification->created_at?->diffForHumans(),
                'url' => route('web-notifications.open', $notification),
            ]);

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => Notification::where('user_id', $userId)->where('read', false)->count(),
        ]);
    }

    public function open(Request $request, Notification $notification): RedirectResponse
    {
        abort_unless((int) $notification->user_id === (int) $request->user()->id, 404);

        if (!$notification->read) {
            $notification->update(['read' => true]);
        }

        return redirect()->to($this->destination($notification));
    }

    public function readAll(Request $request): JsonResponse
    {
        Notification::where('user_id', $request->user()->id)
            ->where('read', false)
            ->update(['read' => true]);

        return response()->json(['status' => 'success']);
    }

    public function download(Request $request, Notification $notification): BinaryFileResponse
    {
        abort_unless((int) $notification->user_id === (int) $request->user()->id, 404);
        abort_if(empty($notification->image), 404);

        $urlPath = rawurldecode((string) parse_url($notification->image, PHP_URL_PATH));
        $uploadPosition = strpos($urlPath, '/uploads/notifications/');
        abort_if($uploadPosition === false, 404);

        $relativePath = ltrim(substr($urlPath, $uploadPosition), '/');
        $filePath = realpath(public_path($relativePath));
        $allowedDirectory = realpath(public_path('uploads/notifications'));

        abort_unless(
            $filePath
            && $allowedDirectory
            && str_starts_with($filePath, $allowedDirectory . DIRECTORY_SEPARATOR)
            && is_file($filePath),
            404
        );

        return response()->download($filePath);
    }

    private function destination(Notification $notification): string
    {
        $model = strtolower(trim((string) $notification->model));
        $title = strtolower(trim((string) $notification->type));
        $message = strtolower((string) $notification->data);
        $id = $notification->model_id;

        if ($model === 'general_notification') {
            return route('web-notifications.page');
        }

        // Draft sent for approval: only the chosen approver gets this, and the
        // approval screen is open to every approver (DraftApprovalController::authorizeApproval).
        if ($title === 'draft approval') {
            return route('draft-approvals.index');
        }

        // Lead tasks / follow-ups ("task" = lead_tasks row): open the lead they belong to.
        if ($model === 'task') {
            $leadId = $id ? LeadTask::whereKey($id)->value('lead_id') : null;
            return $leadId ? route('leads.show', $leadId) : url('lead-tasks');
        }

        // No single record (bulk assign, import, older notifications): open the matching list.
        if (! $id) {
            if ($model === 'lead' || $title === 'assigned lead' || str_contains($message, 'new lead') || str_contains($message, 'leads have been assigned')) {
                return url('leads');
            }
            return match ($model) {
                'task_management' => url('tasks'),
                'opportunity' => url('lead-opportunities'),
                'leave' => url('leaves'),
                'tour', 'tour_plan' => url('tours'),
                'expense', 'expense_management' => url('expenses'),
                'order', 'order_history' => url('orders'),
                'attendance' => url('attendances'),
                default => route('web-notifications.page'),
            };
        }

        return match ($model) {
            'order', 'order_history' => route('orders.show', $id),
            'leave' => route('leaves.show', $id),
            'tour', 'tour_plan' => route('tours.show', $id),
            'expense', 'expense_management' => route('expenses.show', $id),
            'lead' => route('leads.show', $id),
            'task_management' => route('tasks.show', $id),
            'opportunity' => route('lead-opportunities.show', $id),
            'customer', 'distributor' => route('customers.show', $id),
            'attendance' => url('attendances?notification_id=' . $id),
            default => route('web-notifications.page'),
        };
    }
}
