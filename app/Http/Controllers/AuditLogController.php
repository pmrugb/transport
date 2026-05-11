<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    public function index(Request $request): View
    {
        $this->ensureCanViewAuditLogs();

        $perPage = $this->resolvePerPage($request);
        $filters = $this->filterValues($request);
        $logsQuery = $this->filteredLogsQuery($request);

        return view('logs.audit-logs', [
            'perPage' => $perPage,
            'filters' => $filters,
            'users' => User::query()->orderBy('name')->get(),
            'actions' => AuditLog::ACTION_LABELS,
            'subjectTypes' => AuditLog::query()
                ->whereNotNull('auditable_type')
                ->select('auditable_type')
                ->distinct()
                ->orderBy('auditable_type')
                ->pluck('auditable_type')
                ->mapWithKeys(fn (string $type): array => [$type => class_basename($type)])
                ->all(),
            'stats' => [
                'total' => AuditLog::count(),
                'today' => AuditLog::query()->whereDate('created_at', today())->count(),
                'payments' => AuditLog::query()->where('action', 'like', 'payment.%')->count(),
                'roles_trips' => AuditLog::query()
                    ->where(function ($query): void {
                        $query->where('action', 'like', 'role.%')
                            ->orWhere('action', 'like', 'trip.%');
                    })->count(),
            ],
            'logs' => $logsQuery
                ->paginate($this->paginationSize($perPage, (clone $logsQuery)->toBase()->getCountForPagination()))
                ->withQueryString(),
        ]);
    }

    public function deleteMonthsLogs(Request $request): RedirectResponse
    {
        $this->ensureCanViewAuditLogs();

        $deleted = AuditLog::query()
            ->where('created_at', '<', now()->startOfMonth())
            ->delete();

        return redirect()->route('logs.audit.index', $request->query())
            ->with('success', $deleted > 0 ? 'Older months audit logs deleted successfully.' : 'No older months audit logs found to delete.');
    }

    private function filteredLogsQuery(Request $request)
    {
        $filters = $this->filterValues($request);

        return AuditLog::query()
            ->with('user')
            ->when($filters['user_id'], fn ($query, $userId) => $query->where('user_id', $userId))
            ->when($filters['action'], fn ($query, $action) => $query->where('action', $action))
            ->when($filters['subject_type'], fn ($query, $subjectType) => $query->where('auditable_type', $subjectType))
            ->when($filters['from_date'], fn ($query, $fromDate) => $query->whereDate('created_at', '>=', $fromDate))
            ->when($filters['to_date'], fn ($query, $toDate) => $query->whereDate('created_at', '<=', $toDate))
            ->latest();
    }

    private function filterValues(Request $request): array
    {
        return [
            'user_id' => $request->integer('user_id') ?: null,
            'action' => $request->input('action'),
            'subject_type' => $request->input('subject_type'),
            'from_date' => $request->input('from_date'),
            'to_date' => $request->input('to_date'),
        ];
    }

    private function ensureCanViewAuditLogs(): void
    {
        abort_unless(auth()->user()?->canViewAuditLogs(), 403);
    }
}
