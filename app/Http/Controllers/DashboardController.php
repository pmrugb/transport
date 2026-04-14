<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Operator;
use App\Models\TransportRoute;
use App\Models\TripCost;
use App\Models\TripDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $perPage = $this->resolvePerPage($request);
        $user = $request->user();
        $isNatcoDashboard = $user?->isNatcoDepartmentUser() ?? false;

        if ($isNatcoDashboard) {
            $natcoDepartmentId = Cache::remember('dashboard:natco-department-id', now()->addMinutes(10), function () {
                return Department::query()
                    ->whereRaw('LOWER(name) = ?', ['natco'])
                    ->value('id');
            });

            $natcoTripBaseQuery = TripDetail::query()
                ->when(
                    $natcoDepartmentId,
                    fn ($query, $departmentId) => $query->where('department_id', $departmentId),
                    fn ($query) => $query->whereRaw('1 = 0')
                );

            $recentTripsQuery = (clone $natcoTripBaseQuery)
                ->select([
                    'id',
                    'department_id',
                    'district_id',
                    'route_id',
                    'vehicle_id',
                    'transporter_id',
                    'driver_name',
                    'driver_mobile',
                    'fare_amount',
                    'total_amount',
                    'status',
                    'trip_date',
                    'created_at',
                ])
                ->with([
                    'district:id,name',
                    'route:id,route_name',
                    'vehicle:id,registration_no',
                    'transporter:id,name',
                    'tripCost:id,trip_id',
                ])
                ->latest();

            $recentTrips = $recentTripsQuery
                ->paginate($this->paginationSize($perPage, (clone $recentTripsQuery)->toBase()->getCountForPagination()))
                ->withQueryString();

            $routeSnapshot = TransportRoute::query()
                ->select(['id', 'route_name', 'starting_point', 'ending_point', 'timing', 'total_distance', 'district_id', 'created_at'])
                ->with(['district:id,name'])
                ->whereIn(
                    'id',
                    (clone $natcoTripBaseQuery)->select('route_id')->distinct()
                )
                ->latest()
                ->take(3)
                ->get();

            $paymentQuery = TripCost::query()
                ->whereHas(
                    'trip',
                    fn ($query) => $query->when(
                        $natcoDepartmentId,
                        fn ($departmentScopedQuery, $departmentId) => $departmentScopedQuery->where('department_id', $departmentId),
                        fn ($departmentScopedQuery) => $departmentScopedQuery->whereRaw('1 = 0')
                    )
                );

            $tripStatusChart = (clone $natcoTripBaseQuery)
                ->select('status', DB::raw('count(*) as aggregate'))
                ->groupBy('status')
                ->pluck('aggregate', 'status');

            $paymentStatusChart = (clone $paymentQuery)
                ->select('status', DB::raw('count(*) as aggregate'))
                ->groupBy('status')
                ->pluck('aggregate', 'status');

            $monthlyTripCounts = (clone $natcoTripBaseQuery)
                ->selectRaw("DATE_FORMAT(trip_date, '%Y-%m') as month_key, count(*) as aggregate")
                ->whereDate('trip_date', '>=', now()->startOfMonth()->subMonths(5))
                ->groupBy('month_key')
                ->orderBy('month_key')
                ->pluck('aggregate', 'month_key');

            $monthlyLabels = collect(range(5, 0))
                ->map(fn ($offset) => now()->startOfMonth()->subMonths($offset))
                ->values();

            return view('dashboard', [
                'isNatcoDashboard' => true,
                'canManageTrips' => auth()->user()?->isSuperadmin() ?? false,
                'tripStatuses' => TripDetail::STATUSES,
                'stats' => [
                    'totalTrips' => (clone $natcoTripBaseQuery)->count(),
                    'duePayments' => (clone $paymentQuery)->where('status', 'due')->count(),
                    'paidAmount' => (clone $paymentQuery)->where('status', 'paid')->sum('total_amount'),
                ],
                'chartData' => [
                    'tripStatus' => [
                        'labels' => ['Active', 'Completed', 'Cancelled'],
                        'series' => [
                            (int) ($tripStatusChart['active'] ?? 0),
                            (int) ($tripStatusChart['completed'] ?? 0),
                            (int) ($tripStatusChart['cancelled'] ?? 0),
                        ],
                    ],
                    'paymentStatus' => [
                        'labels' => ['Due', 'Paid', 'Rejected'],
                        'series' => [
                            (int) ($paymentStatusChart['due'] ?? 0),
                            (int) ($paymentStatusChart['paid'] ?? 0),
                            (int) ($paymentStatusChart['rejected'] ?? 0),
                        ],
                    ],
                    'monthlyTrips' => [
                        'labels' => $monthlyLabels->map(fn ($date) => $date->format('M y'))->all(),
                        'series' => $monthlyLabels->map(fn ($date) => (int) ($monthlyTripCounts[$date->format('Y-m')] ?? 0))->all(),
                    ],
                ],
                'perPage' => $perPage,
                'recentTrips' => $recentTrips,
                'routeSnapshot' => $routeSnapshot,
            ]);
        }

        $recentTrips = TripDetail::query()
            ->select([
                'id',
                'route_id',
                'vehicle_id',
                'transporter_id',
                'driver_name',
                'driver_mobile',
                'district_id',
                'fare_amount',
                'total_amount',
                'status',
                'created_at',
            ])
            ->with([
                'route:id,route_name',
                'vehicle:id,registration_no',
                'transporter:id,name',
                'district:id,name',
                'tripCost:id,trip_id',
            ])
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        $routeSnapshot = TransportRoute::query()
            ->select(['id', 'route_name', 'starting_point', 'ending_point', 'timing', 'total_distance', 'district_id', 'created_at'])
            ->with(['district:id,name'])
            ->latest()
            ->take(3)
            ->get();

        $ownerTypeChart = Operator::query()
            ->select('owner_type', DB::raw('count(*) as aggregate'))
            ->groupBy('owner_type')
            ->pluck('aggregate', 'owner_type');

        $routesByDistrict = TransportRoute::query()
            ->leftJoin('districts', 'districts.id', '=', 'transport_routes.district_id')
            ->selectRaw('districts.name as district_name, count(transport_routes.id) as aggregate')
            ->groupBy('districts.name')
            ->orderByDesc('aggregate')
            ->limit(6)
            ->get();

        $monthlyOperators = Operator::query()
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month_key, count(*) as aggregate")
            ->whereDate('created_at', '>=', now()->startOfMonth()->subMonths(5))
            ->groupBy('month_key')
            ->orderBy('month_key')
            ->pluck('aggregate', 'month_key');

        $monthlyLabels = collect(range(5, 0))
            ->map(fn ($offset) => now()->startOfMonth()->subMonths($offset))
            ->values();
        $operatorStats = Operator::query()
            ->selectRaw('COUNT(*) as total')
            ->selectRaw("SUM(CASE WHEN owner_type = 'company' THEN 1 ELSE 0 END) as companies")
            ->selectRaw('COUNT(DISTINCT district_id) as districts')
            ->first();

        return view('dashboard', [
            'isNatcoDashboard' => false,
            'canManageTrips' => auth()->user()?->isSuperadmin() ?? false,
            'tripStatuses' => TripDetail::STATUSES,
            'stats' => [
                'totalOperators' => (int) ($operatorStats?->total ?? 0),
                'totalRoutes' => TransportRoute::count(),
                'activeCompanies' => (int) ($operatorStats?->companies ?? 0),
                'pendingChecks' => (int) ($operatorStats?->districts ?? 0),
            ],
            'chartData' => [
                'ownerMix' => [
                    'labels' => ['Companies', 'Private'],
                    'series' => [
                        (int) ($ownerTypeChart['company'] ?? 0),
                        (int) ($ownerTypeChart['private'] ?? 0),
                    ],
                ],
                'routesByDistrict' => [
                    'labels' => $routesByDistrict->pluck('district_name')->map(fn ($name) => $name ?: 'Unknown')->all(),
                    'series' => $routesByDistrict->pluck('aggregate')->map(fn ($count) => (int) $count)->all(),
                ],
                'monthlyOperators' => [
                    'labels' => $monthlyLabels->map(fn ($date) => $date->format('M y'))->all(),
                    'series' => $monthlyLabels->map(fn ($date) => (int) ($monthlyOperators[$date->format('Y-m')] ?? 0))->all(),
                ],
            ],
            'perPage' => $perPage,
            'recentTrips' => $recentTrips,
            'routeSnapshot' => $routeSnapshot,
        ]);
    }
}
