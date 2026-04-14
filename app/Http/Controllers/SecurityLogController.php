<?php

namespace App\Http\Controllers;

use App\Models\SecurityLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SecurityLogController extends Controller
{
    public function index(Request $request): View
    {
        $this->ensureSuperadmin();

        $perPage = $this->resolvePerPage($request);
        $filters = $this->filterValues($request);
        $logsQuery = $this->filteredLogsQuery($request);

        return view('logs.security-logs', [
            'perPage' => $perPage,
            'filters' => $filters,
            'users' => User::query()->orderBy('name')->get(),
            'eventTypes' => SecurityLog::EVENT_LABELS,
            'stats' => [
                'total' => SecurityLog::count(),
                'today' => SecurityLog::query()->whereDate('created_at', today())->count(),
                'failed_logins' => SecurityLog::query()->whereIn('event_type', ['failed_login', 'captcha_failed'])->count(),
                'high_risk' => SecurityLog::query()->where('risk_level', 'high')->count(),
            ],
            'logs' => $logsQuery->paginate($this->paginationSize($perPage, (clone $logsQuery)->toBase()->getCountForPagination()))->withQueryString(),
        ]);
    }

    public function deleteMonthsLogs(Request $request): RedirectResponse
    {
        $this->ensureSuperadmin();

        $deleted = SecurityLog::query()
            ->where('created_at', '<', now()->startOfMonth())
            ->delete();

        return redirect()->route('logs.security.index', $request->query())
            ->with('success', $deleted > 0 ? 'Older months logs deleted successfully.' : 'No older months logs found to delete.');
    }

    private function filteredLogsQuery(Request $request)
    {
        $filters = $this->filterValues($request);

        return SecurityLog::query()
            ->with('user')
            ->when($filters['user_id'], fn ($query, $userId) => $query->where('user_id', $userId))
            ->when($filters['event_type'], fn ($query, $eventType) => $query->where('event_type', $eventType))
            ->when($filters['ip'], fn ($query, $ip) => $query->where('ip_address', 'like', '%'.$ip.'%'))
            ->when($filters['from_date'], fn ($query, $fromDate) => $query->whereDate('created_at', '>=', $fromDate))
            ->when($filters['to_date'], fn ($query, $toDate) => $query->whereDate('created_at', '<=', $toDate))
            ->latest();
    }

    private function filterValues(Request $request): array
    {
        return [
            'user_id' => $request->integer('user_id') ?: null,
            'event_type' => $request->input('event_type'),
            'ip' => trim((string) $request->input('ip')),
            'from_date' => $request->input('from_date'),
            'to_date' => $request->input('to_date'),
        ];
    }

    private function ensureSuperadmin(): void
    {
        abort_unless(auth()->user()?->isSuperadmin(), 403);
    }
}
