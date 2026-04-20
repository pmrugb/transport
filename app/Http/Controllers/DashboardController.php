<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Grant;
use App\Models\Operator;
use App\Models\TransportRoute;
use App\Models\TripCost;
use App\Models\TripDetail;
use Illuminate\Http\Request;
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
            $paymentQuery = TripCost::query();
            $routePaymentSummary = TransportRoute::query()
                ->select([
                    'transport_routes.id',
                    'transport_routes.route_name',
                    'transport_routes.starting_point',
                    'transport_routes.ending_point',
                    'transport_routes.timing',
                    'transport_routes.total_distance',
                    'transport_routes.district_id',
                ])
                ->selectRaw('COUNT(trip_costs.id) as payment_count')
                ->selectRaw("COALESCE(SUM(CASE WHEN trip_costs.status = 'paid' THEN trip_costs.total_amount ELSE 0 END), 0) as paid_amount")
                ->selectRaw("COALESCE(SUM(CASE WHEN trip_costs.status IN ('due', 'on_hold') THEN trip_costs.total_amount ELSE 0 END), 0) as unpaid_amount")
                ->leftJoin('trip_costs', 'trip_costs.route_id', '=', 'transport_routes.id')
                ->with('district:id,name')
                ->groupBy(
                    'transport_routes.id',
                    'transport_routes.route_name',
                    'transport_routes.starting_point',
                    'transport_routes.ending_point',
                    'transport_routes.timing',
                    'transport_routes.total_distance',
                    'transport_routes.district_id'
                )
                ->orderBy('transport_routes.route_name')
                ->get();

            $recentPaymentsQuery = (clone $paymentQuery)
                ->select([
                    'id',
                    'trip_id',
                    'route_id',
                    'vehicle_id',
                    'transporter_id',
                    'fare_amount',
                    'no_of_trips',
                    'total_amount',
                    'calculation_date',
                    'status',
                    'remarks',
                    'created_at',
                ])
                ->with([
                    'trip:id,trip_date,district_id',
                    'trip.district:id,name',
                    'route:id,route_name',
                    'vehicle:id,registration_no',
                    'transporter:id,name',
                ])
                ->orderByDesc('calculation_date')
                ->latest('id');

            $recentPayments = $recentPaymentsQuery
                ->paginate($this->paginationSize($perPage, (clone $recentPaymentsQuery)->toBase()->getCountForPagination()))
                ->withQueryString();

            $todayPaymentsQuery = (clone $paymentQuery)
                ->where(function ($query) {
                    $query->whereDate('calculation_date', today())
                        ->orWhereDate('created_at', today());
                });
            $totalGrantAmount = (float) Grant::query()->sum('total_amount');
            $paidAmount = (float) ((clone $paymentQuery)->where('status', 'paid')->sum('total_amount') ?: 0);

            return view('dashboard', [
                'isNatcoDashboard' => true,
                'canCreateTrips' => auth()->user()?->canCreateTrips() ?? false,
                'canEditTrips' => auth()->user()?->canEditTrips() ?? false,
                'canDeleteTrips' => auth()->user()?->canDeleteTrips() ?? false,
                'canManagePayments' => auth()->user()?->canManagePayments() ?? false,
                'tripStatuses' => TripDetail::STATUSES,
                'stats' => [
                    'totalPayments' => (clone $paymentQuery)->count(),
                    'duePayments' => (clone $paymentQuery)->where('status', 'due')->count(),
                    'paidPayments' => (clone $paymentQuery)->where('status', 'paid')->count(),
                    'todayPayments' => (clone $todayPaymentsQuery)->count(),
                    'dueAmount' => (float) ((clone $paymentQuery)->where('status', 'due')->sum('total_amount') ?: 0),
                    'paidAmount' => $paidAmount,
                    'amountLeft' => max($totalGrantAmount - $paidAmount, 0),
                    'todayAmount' => (float) ((clone $todayPaymentsQuery)->sum('total_amount') ?: 0),
                    'onHoldPayments' => (clone $paymentQuery)->where('status', 'on_hold')->count(),
                    'rejectedPayments' => (clone $paymentQuery)->where('status', 'rejected')->count(),
                ],
                'perPage' => $perPage,
                'recentTrips' => collect(),
                'recentPayments' => $recentPayments,
                'paymentStatuses' => TripCost::STATUSES,
                'routePaymentSummary' => $routePaymentSummary,
                'routeSnapshot' => collect(),
                'chartData' => [],
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
            'canCreateTrips' => auth()->user()?->canCreateTrips() ?? false,
            'canEditTrips' => auth()->user()?->canEditTrips() ?? false,
            'canDeleteTrips' => auth()->user()?->canDeleteTrips() ?? false,
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
