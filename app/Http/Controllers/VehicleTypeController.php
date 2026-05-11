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
        $this->ensureCanManageVehicleTypes();

        $perPage = $this->resolvePerPage($request);
        $vehicleTypeQuery = VehicleType::query()->latest();

        return view('settings.vehicle-types.index', [
            ...$this->sharedData(),
            'perPage' => $perPage,
            'vehicleTypes' => $vehicleTypeQuery
                ->paginate($this->paginationSize($perPage, (clone $vehicleTypeQuery)->toBase()->getCountForPagination()))
                ->withQueryString(),
        ]);
    }

    public function create(): View
    {
        $this->ensureCanManageVehicleTypes();

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
        $this->ensureCanManageVehicleTypes();

        return view('vehicles.show', [
            ...$this->sharedData(),
            'vehicleType' => $vehicleType,
        ]);
    }

    public function edit(VehicleType $vehicleType): View
    {
        $this->ensureCanManageVehicleTypes();

        return view('settings.vehicle-types.edit', [
            ...$this->sharedData(),
            'vehicleType' => $vehicleType,
        ]);
    }

    public function store(StoreVehicleTypeRequest $request): RedirectResponse
    {
        $this->ensureCanManageVehicleTypes();

        VehicleType::create([
            ...$request->validated(),
            'status' => 'active',
        ]);

        return redirect()->route('vehicles.types.index')
            ->with('success', 'Vehicle type created successfully.');
    }

    public function update(StoreVehicleTypeRequest $request, VehicleType $vehicleType): RedirectResponse
    {
        $this->ensureCanManageVehicleTypes();

        $vehicleType->update($request->validated());

        return redirect()->route('vehicles.types.edit', $vehicleType)
            ->with('success', 'Vehicle type updated successfully.');
    }

    public function destroy(VehicleType $vehicleType): RedirectResponse
    {
        $this->ensureCanManageVehicleTypes();

        $vehicleType->delete();

        return redirect()->route('vehicles.types.index')
            ->with('success', 'Vehicle type deleted successfully.');
    }

    private function sharedData(): array
    {
        return [
            'statuses' => VehicleType::STATUSES,
            'canManageVehicles' => auth()->user()?->canManageVehicleTypes() ?? false,
        ];
    }

    private function ensureCanManageVehicleTypes(): void
    {
        abort_unless(auth()->user()?->canManageVehicleTypes(), 403);
    }
}
