<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVehicleRequest;
use App\Models\Operator;
use App\Models\TransportRoute;
use App\Models\Vehicle;
use App\Models\VehicleType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VehicleController extends Controller
{
    public function index(Request $request): View
    {
        $perPage = (int) $request->integer('per_page', 10);
        $perPage = in_array($perPage, [10, 25, 50, 100], true) ? $perPage : 10;

        return view('vehicles.index', [
            ...$this->sharedData(),
            'perPage' => $perPage,
            'vehicles' => Vehicle::query()
                ->select(['id', 'transporter_id', 'vehicle_type', 'registration_no', 'chassis_no', 'route_id', 'status', 'remarks', 'created_at'])
                ->with([
                    'transporter:id,name',
                    'vehicleType:id,name',
                    'route:id,route_name',
                ])
                ->latest()
                ->paginate($perPage)
                ->withQueryString(),
        ]);
    }

    public function create(): View
    {
        return view('vehicles.create', [
            ...$this->sharedData(),
            'vehicle' => new Vehicle(),
            'formAction' => route('vehicles.store'),
            'formMethod' => 'post',
            'submitLabel' => 'Save Vehicle',
        ]);
    }

    public function store(StoreVehicleRequest $request): RedirectResponse
    {
        Vehicle::create($request->validated());

        return redirect()->route('vehicles.create')
            ->with('success', 'Vehicle saved successfully.');
    }

    public function show(Vehicle $vehicle): View
    {
        return view('vehicles.show', [
            ...$this->sharedData(),
            'vehicle' => $vehicle->load(['transporter', 'vehicleType', 'route']),
        ]);
    }

    public function edit(Vehicle $vehicle): View
    {
        $this->ensureSuperadmin();

        return view('vehicles.edit', [
            ...$this->sharedData(),
            'vehicle' => $vehicle->load(['transporter', 'vehicleType', 'route']),
            'formAction' => route('vehicles.update', $vehicle),
            'formMethod' => 'put',
            'submitLabel' => 'Save Changes',
        ]);
    }

    public function update(StoreVehicleRequest $request, Vehicle $vehicle): RedirectResponse
    {
        $this->ensureSuperadmin();

        $vehicle->update($request->validated());

        return redirect()->route('vehicles.edit', $vehicle)
            ->with('success', 'Vehicle updated successfully.');
    }

    public function destroy(Vehicle $vehicle): RedirectResponse
    {
        $this->ensureSuperadmin();

        $vehicle->delete();

        return redirect()->route('vehicles.index')
            ->with('success', 'Vehicle deleted successfully.');
    }

    private function sharedData(): array
    {
        return [
            'transporters' => Operator::query()->select(['id', 'name', 'cnic'])->orderBy('name')->get(),
            'vehicleTypes' => VehicleType::query()->select(['id', 'name'])->orderBy('name')->get(),
            'routes' => TransportRoute::query()->select(['id', 'route_name', 'starting_point', 'ending_point'])->orderBy('route_name')->get(),
            'statuses' => Vehicle::STATUSES,
            'canManageVehicles' => auth()->user()?->isSuperadmin() ?? false,
        ];
    }

    private function ensureSuperadmin(): void
    {
        abort_unless(auth()->user()?->isSuperadmin(), 403);
    }
}
