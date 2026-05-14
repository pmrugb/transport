<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Challan;
use App\Models\Department;
use App\Models\District;
use App\Models\Division;
use App\Models\Fare;
use App\Models\Grant;
use App\Models\GrantRelease;
use App\Models\Operator;
use App\Models\Role;
use App\Models\TransportRoute;
use App\Models\TripCost;
use App\Models\TripDetail;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class RecoveryController extends Controller
{
    private const RESOURCES = [
        'user' => ['model' => User::class, 'label' => 'User'],
        'role' => ['model' => Role::class, 'label' => 'Role'],
        'division' => ['model' => Division::class, 'label' => 'Division'],
        'district' => ['model' => District::class, 'label' => 'District'],
        'department' => ['model' => Department::class, 'label' => 'Department'],
        'transporter' => ['model' => Operator::class, 'label' => 'Transporter'],
        'vehicle_type' => ['model' => VehicleType::class, 'label' => 'Vehicle Type'],
        'vehicle' => ['model' => Vehicle::class, 'label' => 'Vehicle'],
        'route' => ['model' => TransportRoute::class, 'label' => 'Route'],
        'fare' => ['model' => Fare::class, 'label' => 'Fare'],
        'grant' => ['model' => Grant::class, 'label' => 'Grant'],
        'grant_release' => ['model' => GrantRelease::class, 'label' => 'Grant Release'],
        'challan' => ['model' => Challan::class, 'label' => 'Challan'],
        'trip' => ['model' => TripDetail::class, 'label' => 'Trip'],
    ];

    public function index(Request $request): View
    {
        $this->ensureCanManageUsers();

        $perPage = $this->resolvePerPage($request);
        $search = trim((string) $request->input('search'));
        $type = (string) $request->input('type', '');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        $items = $this->recoveryItems($search, $type, $fromDate, $toDate);
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $pageItems = $items->forPage($currentPage, $this->paginationSize($perPage, $items->count()))->values();

        return view('logs.recovery', [
            'perPage' => $perPage,
            'search' => $search,
            'type' => $type,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'types' => collect(self::RESOURCES)->mapWithKeys(fn (array $resource, string $resourceType): array => [$resourceType => $resource['label']])->all(),
            'stats' => [
                'total' => $items->count(),
                'today' => $items->filter(fn (array $item): bool => $item['deleted_at']?->isToday() ?? false)->count(),
                'modules' => $items->pluck('type')->unique()->count(),
                'restorable' => $items->where('can_restore', true)->count(),
            ],
            'recoveryItems' => new LengthAwarePaginator(
                $pageItems,
                $items->count(),
                $this->paginationSize($perPage, $items->count()),
                $currentPage,
                [
                    'path' => $request->url(),
                    'query' => $request->query(),
                ]
            ),
        ]);
    }

    public function restore(Request $request, string $type, int $id): RedirectResponse
    {
        $this->ensureCanManageUsers();

        $resource = self::RESOURCES[$type] ?? null;
        abort_if($resource === null, 404);

        /** @var \Illuminate\Database\Eloquent\Model $record */
        $record = $resource['model']::withTrashed()->findOrFail($id);
        abort_unless(method_exists($record, 'trashed') && $record->trashed(), 404);

        if (! $this->canRestoreRecord($record)) {
            return redirect()->route('logs.recovery.index', $request->query())
                ->with('error', 'This record cannot be restored until its original unique values become available.');
        }

        $oldValues = [
            'label' => $this->recordTitle($record, $type),
            'type' => $resource['label'],
            'deleted_at' => $record->deleted_at?->toDateTimeString(),
        ];

        if (method_exists($record, 'softDeletedOriginalValues')) {
            $oldValues['original_values'] = $record->softDeletedOriginalValues();
        }

        $record->restore();

        if (method_exists($record, 'restoreSoftDeletedUniqueFields')) {
            $record->restoreSoftDeletedUniqueFields();
        }

        if ($record instanceof TripDetail) {
            TripCost::withTrashed()->where('trip_id', $record->getKey())->restore();
        }

        AuditLog::recordEvent('recovery.restored', $request, $record->fresh(), [
            ...$oldValues,
        ], [
            'deleted_at' => null,
        ]);

        return redirect()->route('logs.recovery.index', $request->query())
            ->with('success', $resource['label'].' restored successfully.');
    }

    public function deleteMonthsLogs(Request $request): RedirectResponse
    {
        $this->ensureCanManageUsers();

        $months = max(1, min(12, $request->integer('months', 1)));
        $cutoff = now()->subMonths($months)->startOfMonth();
        $deleted = 0;

        foreach (self::RESOURCES as $resourceType => $resource) {
            $query = $resource['model']::onlyTrashed()->where('deleted_at', '<', $cutoff);

            if ($resourceType === 'trip') {
                $tripIds = $query->pluck('id');

                if ($tripIds->isNotEmpty()) {
                    TripCost::onlyTrashed()->whereIn('trip_id', $tripIds)->forceDelete();
                }
            }

            $deleted += (clone $query)->count();
            $query->forceDelete();
        }

        return redirect()->route('logs.recovery.index', $request->query())
            ->with('success', $deleted > 0 ? 'Recovery records older than the selected month window deleted successfully.' : 'No older recovery records found for the selected month window.');
    }

    private function recoveryItems(string $search, string $type, ?string $fromDate, ?string $toDate): Collection
    {
        $items = collect();

        foreach (self::RESOURCES as $resourceType => $resource) {
            if ($type !== '' && $type !== $resourceType) {
                continue;
            }

            $records = $resource['model']::onlyTrashed()->get();

            foreach ($records as $record) {
                $item = $this->mapRecord($resourceType, $resource['label'], $record);

                if ($search !== '' && ! $this->matchesSearch($item, $search)) {
                    continue;
                }

                if ($fromDate && (! $item['deleted_at'] || $item['deleted_at']->toDateString() < $fromDate)) {
                    continue;
                }

                if ($toDate && (! $item['deleted_at'] || $item['deleted_at']->toDateString() > $toDate)) {
                    continue;
                }

                $items->push($item);
            }
        }

        return $items->sortByDesc(fn (array $item) => optional($item['deleted_at'])->timestamp ?? 0)->values();
    }

    private function mapRecord(string $type, string $label, mixed $record): array
    {
        $originalValues = method_exists($record, 'softDeletedOriginalValues')
            ? $record->softDeletedOriginalValues()
            : [];

        return [
            'id' => $record->getKey(),
            'type' => $type,
            'type_label' => $label,
            'title' => $this->recordTitle($record, $type),
            'current_value' => $this->recordCurrentValue($record, $type),
            'original_values' => $originalValues,
            'deleted_at' => $record->deleted_at,
            'can_restore' => $this->canRestoreRecord($record),
            'reason' => $this->restoreBlockReason($record),
        ];
    }

    private function matchesSearch(array $item, string $search): bool
    {
        $haystacks = [
            $item['type_label'],
            $item['title'],
            $item['current_value'],
            implode(' ', array_values($item['original_values'])),
        ];

        foreach ($haystacks as $haystack) {
            if ($haystack !== null && str_contains(strtolower((string) $haystack), strtolower($search))) {
                return true;
            }
        }

        return false;
    }

    private function canRestoreRecord(mixed $record): bool
    {
        if (method_exists($record, 'canRestoreSoftDeletedUniqueFields')) {
            return $record->canRestoreSoftDeletedUniqueFields();
        }

        return true;
    }

    private function restoreBlockReason(mixed $record): ?string
    {
        return $this->canRestoreRecord($record)
            ? null
            : 'Original unique value is already in use.';
    }

    private function recordTitle(mixed $record, string $type): string
    {
        return match ($type) {
            'user' => (string) $record->name,
            'role' => (string) $record->name,
            'division' => (string) $record->name,
            'district' => (string) $record->name,
            'department' => (string) $record->name,
            'transporter' => (string) $record->name,
            'vehicle_type' => (string) $record->name,
            'vehicle' => (string) $record->registration_no,
            'route' => trim((string) $record->route_name),
            'fare' => 'Fare #'.$record->getKey(),
            'grant' => (string) $record->title,
            'grant_release' => 'Installment #'.((string) $record->installment_no ?: $record->getKey()),
            'challan' => 'Challan #'.$record->getKey(),
            'trip' => 'Trip #'.$record->getKey(),
            default => class_basename($record).' #'.$record->getKey(),
        };
    }

    private function recordCurrentValue(mixed $record, string $type): ?string
    {
        return match ($type) {
            'user' => (string) $record->email,
            'role' => (string) $record->slug,
            'district', 'division', 'department', 'vehicle_type' => (string) $record->name,
            'transporter' => (string) $record->cnic,
            'vehicle' => trim((string) $record->registration_no.' / '.(string) $record->chassis_no, ' /'),
            'route' => (string) $record->route_code,
            'fare' => 'Amount: '.(string) $record->amount,
            'grant' => (string) $record->financial_year,
            'grant_release' => 'Amount: '.(string) $record->release_amount,
            'challan' => optional($record->challan_date)?->format('d-m-Y'),
            'trip' => optional($record->trip_date)?->format('d-m-Y'),
            default => null,
        };
    }

    private function ensureCanManageUsers(): void
    {
        abort_unless(auth()->user()?->canManageUsers(), 403);
    }
}
