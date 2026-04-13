<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVehicleTypeRequest;
use App\Models\VehicleType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VehicleTypeController extends Controller
{
    public function index(Request $request): View
    {
        $perPage = (int) $request->integer('per_page', 10);
        $perPage = in_array($perPage, [10, 25, 50, 100], true) ? $perPage : 10;

        return view('settings.vehicle-types.index', [
            ...$this->sharedData(),
            'perPage' => $perPage,
            'vehicleTypes' => VehicleType::query()
                ->latest()
                ->paginate($perPage)
                ->withQueryString(),
        ]);
    }

    public function create(): View
    {
        return view('settings.vehicle-types.index', [
            ...$this->sharedData(),
            'perPage' => 10,
            'vehicleTypes' => VehicleType::query()
                ->latest()
                ->paginate(10)
                ->withQueryString(),
        ]);
    }

    public function show(VehicleType $vehicleType): View
    {
        $this->ensureSuperadmin();

        return view('vehicles.show', [
            ...$this->sharedData(),
            'vehicleType' => $vehicleType,
        ]);
    }

    public function edit(VehicleType $vehicleType): View
    {
        $this->ensureSuperadmin();

        return view('settings.vehicle-types.edit', [
            ...$this->sharedData(),
            'vehicleType' => $vehicleType,
        ]);
    }

    public function store(StoreVehicleTypeRequest $request): RedirectResponse
    {
        VehicleType::create([
            ...$request->validated(),
            'status' => 'active',
        ]);

        return redirect()->route('vehicles.types.index')
            ->with('success', 'Vehicle type created successfully.');
    }

    public function update(StoreVehicleTypeRequest $request, VehicleType $vehicleType): RedirectResponse
    {
        $this->ensureSuperadmin();

        $vehicleType->update($request->validated());

        return redirect()->route('vehicles.types.edit', $vehicleType)
            ->with('success', 'Vehicle type updated successfully.');
    }

    public function destroy(VehicleType $vehicleType): RedirectResponse
    {
        $this->ensureSuperadmin();

        $vehicleType->delete();

        return redirect()->route('vehicles.types.index')
            ->with('success', 'Vehicle type deleted successfully.');
    }

    private function sharedData(): array
    {
        return [
            'statuses' => VehicleType::STATUSES,
            'canManageVehicles' => auth()->user()?->isSuperadmin() ?? false,
        ];
    }

    private function ensureSuperadmin(): void
    {
        abort_unless(auth()->user()?->isSuperadmin(), 403);
    }
}
