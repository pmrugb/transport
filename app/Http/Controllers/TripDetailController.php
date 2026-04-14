<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTripDetailRequest;
use App\Models\District;
use App\Models\Fare;
use App\Models\Operator;
use App\Models\TransportRoute;
use App\Models\TripCost;
use App\Models\TripDetail;
use App\Models\Vehicle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TripDetailController extends Controller
{
    public function vehicleDetails(Request $request): JsonResponse
    {
        $vehicle = Vehicle::query()
            ->select(['id', 'route_id', 'transporter_id'])
            ->with([
                'route:id,district_id',
                'transporter:id,name,owner_type,cnic,phone',
            ])
            ->findOrFail((int) $request->integer('vehicle_id'));

        $fare = Fare::query()
            ->where('status', 'active')
            ->where('route_id', $vehicle->route_id)
            ->orderByDesc('effective_from')
            ->first();

        return response()->json([
            'route_id' => $vehicle->route_id,
            'transporter_id' => $vehicle->transporter_id,
            'transporter_owner_type' => $vehicle->transporter?->owner_type,
            'district_id' => $vehicle->route?->district_id,
            'driver_name' => $vehicle->transporter?->name,
            'driver_cnic' => $vehicle->transporter?->cnic,
            'driver_mobile' => $vehicle->transporter?->phone,
            'fare_id' => $fare?->id,
            'fare_amount' => $fare?->amount,
        ]);
    }

    public function routeDetails(Request $request): JsonResponse
    {
        $route = TransportRoute::query()
            ->select(['id', 'district_id'])
            ->findOrFail((int) $request->integer('route_id'));

        $fare = Fare::query()
            ->where('status', 'active')
            ->where('route_id', $route->id)
            ->orderByDesc('effective_from')
            ->first();

        return response()->json([
            'district_id' => $route->district_id,
            'fare_id' => $fare?->id,
            'fare_amount' => $fare?->amount,
        ]);
    }

    public function index(Request $request): View
    {
        $perPage = $this->resolvePerPage($request);
        $search = trim((string) $request->input('search', ''));
        $tripQuery = TripDetail::query()
            ->select([
                'id',
                'vehicle_id',
                'route_id',
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
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($nestedQuery) use ($search) {
                    $nestedQuery
                        ->where('driver_name', 'like', "%{$search}%")
                        ->orWhere('driver_mobile', 'like', "%{$search}%")
                        ->orWhereHas('vehicle', fn ($vehicleQuery) => $vehicleQuery->where('registration_no', 'like', "%{$search}%"))
                        ->orWhereHas('route', fn ($routeQuery) => $routeQuery->where('route_name', 'like', "%{$search}%"))
                        ->orWhereHas('transporter', fn ($transporterQuery) => $transporterQuery->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('district', fn ($districtQuery) => $districtQuery->where('name', 'like', "%{$search}%"));
                });
            })
            ->latest();

        return view('trips.index', [
            ...$this->sharedData(),
            'perPage' => $perPage,
            'search' => $search,
            'trips' => $tripQuery
                ->paginate($this->paginationSize($perPage, (clone $tripQuery)->toBase()->getCountForPagination()))
                ->withQueryString(),
        ]);
    }

    public function create(): View
    {
        return view('trips.create', [
            ...$this->sharedData(),
            'trip' => new TripDetail([
                'trip_date' => today(),
                'status' => 'active',
            ]),
            'formAction' => route('trips.store'),
            'formMethod' => 'post',
            'submitLabel' => 'Save Trip',
        ]);
    }

    public function show(TripDetail $trip): View
    {
        $trip->load([
            'route:id,route_name,starting_point,ending_point',
            'vehicle:id,registration_no',
            'transporter:id,name,cnic,phone,owner_type',
            'district:id,name',
            'fare:id,amount,effective_from',
            'tripCost:id,trip_id,status',
            'creator:id,name',
        ]);

        return view('trips.show', [
            'trip' => $trip,
            'statuses' => TripDetail::STATUSES,
            'canManageTrips' => auth()->user()?->isSuperadmin() ?? false,
        ]);
    }

    public function store(StoreTripDetailRequest $request): RedirectResponse
    {
        $payload = $request->validated();
        $payload['total_amount'] = (float) $payload['fare_amount'] * (int) $payload['no_of_trips'];
        $payload['created_by'] = auth()->id();

        DB::transaction(function () use ($payload): void {
            $trip = TripDetail::create($payload);
            $this->syncTripCost($trip, $payload);
        });

        return redirect()->route('trips.create')
            ->with('success', 'Trip saved successfully.');
    }

    public function edit(TripDetail $trip): View
    {
        $this->ensureSuperadmin();

        return view('trips.edit', [
            ...$this->sharedData(),
            'trip' => $trip,
            'formAction' => route('trips.update', $trip),
            'formMethod' => 'put',
            'submitLabel' => 'Save Changes',
        ]);
    }

    public function update(StoreTripDetailRequest $request, TripDetail $trip): RedirectResponse
    {
        $this->ensureSuperadmin();

        $payload = $request->validated();
        $payload['total_amount'] = (float) $payload['fare_amount'] * (int) $payload['no_of_trips'];

        DB::transaction(function () use ($trip, $payload): void {
            $trip->update($payload);
            $this->syncTripCost($trip->fresh(), $payload);
        });

        return redirect()->route('trips.edit', $trip)
            ->with('success', 'Trip updated successfully.');
    }

    public function destroy(TripDetail $trip): RedirectResponse
    {
        $this->ensureSuperadmin();

        DB::transaction(function () use ($trip): void {
            $trip->tripCost()?->delete();
            $trip->delete();
        });

        return redirect()->route('trips.index')
            ->with('success', 'Trip deleted successfully.');
    }

    private function syncTripCost(TripDetail $trip, array $payload): void
    {
        $currentStatus = $trip->tripCost?->status;
        $paymentStatus = in_array($currentStatus, array_keys(TripCost::STATUSES), true)
            ? $currentStatus
            : 'due';

        $trip->tripCost()->updateOrCreate(
            ['trip_id' => $trip->id],
            [
                'route_id' => $payload['route_id'],
                'vehicle_id' => $payload['vehicle_id'],
                'transporter_id' => $payload['transporter_id'],
                'fare_amount' => $payload['fare_amount'],
                'no_of_trips' => $payload['no_of_trips'],
                'total_amount' => $payload['total_amount'],
                'calculation_date' => $payload['trip_date'],
                'status' => $paymentStatus,
                'remarks' => $payload['remarks'] ?? null,
            ]
        );
    }

    private function sharedData(): array
    {
        $routes = TransportRoute::query()
            ->select(['id', 'route_name', 'starting_point', 'ending_point', 'district_id'])
            ->with(['district:id,name'])
            ->orderBy('route_name')
            ->get();
        $vehicles = Vehicle::query()
            ->select(['id', 'transporter_id', 'vehicle_type', 'registration_no', 'route_id'])
            ->with([
                'transporter:id,name',
                'route:id,route_name,district_id',
                'route.district:id,name',
                'vehicleType:id,name',
            ])
            ->orderBy('registration_no')
            ->get();
        $fares = Fare::query()
            ->select(['id', 'route_id', 'amount', 'status', 'effective_from'])
            ->with(['route:id,route_name'])
            ->where('status', 'active')
            ->orderByDesc('effective_from')
            ->get();
        $stats = TripDetail::query()
            ->selectRaw('COUNT(*) as total')
            ->selectRaw("SUM(CASE WHEN DATE(created_at) = ? THEN 1 ELSE 0 END) as today", [today()->toDateString()])
            ->selectRaw("SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active")
            ->selectRaw('COALESCE(SUM(total_amount), 0) as amount')
            ->first();

        return [
            'routes' => $routes,
            'vehicles' => $vehicles,
            'transporters' => Operator::query()->select(['id', 'name', 'owner_type'])->orderBy('name')->get(),
            'fares' => $fares,
            'districts' => District::query()->select(['id', 'name'])->orderBy('name')->get(),
            'statuses' => TripDetail::STATUSES,
            'canManageTrips' => auth()->user()?->isSuperadmin() ?? false,
            'stats' => [
                'total' => (int) ($stats?->total ?? 0),
                'today' => (int) ($stats?->today ?? 0),
                'active' => (int) ($stats?->active ?? 0),
                'amount' => (float) ($stats?->amount ?? 0),
            ],
        ];
    }

    private function ensureSuperadmin(): void
    {
        abort_unless(auth()->user()?->isSuperadmin(), 403);
    }
}
