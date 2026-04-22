<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\BuildsExcelExports;
use App\Http\Requests\StoreVehicleRequest;
use App\Models\Operator;
use App\Models\TransportRoute;
use App\Models\Vehicle;
use App\Models\VehicleType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\View\View;

class VehicleController extends Controller
{
    use BuildsExcelExports;

    public function index(Request $request): View
    {
        $perPage = $this->resolvePerPage($request);
        $filters = $this->filterValues($request);
        $vehicleQuery = $this->filteredVehiclesQuery($request);

        return view('vehicles.index', [
            ...$this->sharedData(),
            'perPage' => $perPage,
            'search' => $filters['search'],
            'filters' => $filters,
            'exportColumns' => $this->vehicleExportColumns(),
            'selectedExportColumns' => $this->selectedVehicleExportColumns($request),
            'vehicles' => $vehicleQuery
                ->paginate($this->paginationSize($perPage, (clone $vehicleQuery)->toBase()->getCountForPagination()))
                ->withQueryString(),
        ]);
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $columns = $this->selectedVehicleExportColumns($request);
        $filename = 'vehicles-'.now()->format('Ymd-His').'.csv';

        return response()->streamDownload(function () use ($request, $columns): void {
            $handle = fopen('php://output', 'w');
            $serialNumber = 1;

            fputcsv($handle, array_values($columns));

            foreach ($this->filteredVehiclesQuery($request)->cursor() as $vehicle) {
                fputcsv($handle, array_values($this->vehicleExportRow($vehicle, $columns, $serialNumber)));
                $serialNumber++;
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function exportExcel(Request $request): BinaryFileResponse
    {
        $columns = $this->selectedVehicleExportColumns($request);
        $rows = $this->filteredVehiclesQuery($request)
            ->get()
            ->values()
            ->map(fn (Vehicle $vehicle, int $index): array => $this->vehicleExportRow($vehicle, $columns, $index + 1))
            ->all();
        $filename = 'vehicles-'.now()->format('Ymd-His').'.xlsx';
        $tempPath = tempnam(sys_get_temp_dir(), 'vehicles-xlsx-');

        $this->buildExcelExport($tempPath, $rows, 'Vehicles', 'Vehicles Export');

        return response()->download(
            $tempPath,
            $filename,
            ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
        )->deleteFileAfterSend(true);
    }

    public function pdfView(Request $request): View
    {
        $columns = $this->selectedVehicleExportColumns($request);
        $filters = $this->filterValues($request);
        $rows = $this->filteredVehiclesQuery($request)
            ->get()
            ->values()
            ->map(fn (Vehicle $vehicle, int $index): array => $this->vehicleExportRow($vehicle, $columns, $index + 1))
            ->all();

        return view('exports.table-pdf', [
            'title' => 'Vehicles Export',
            'subtitle' => 'Filtered vehicle records with the selected export columns.',
            'columns' => $columns,
            'rows' => $rows,
            'filters' => [
                'Search' => $filters['search'],
                'Transporter' => $filters['transporter_id']
                    ? Operator::query()->whereKey($filters['transporter_id'])->value('name')
                    : null,
                'Vehicle Type' => $filters['vehicle_type']
                    ? VehicleType::query()->whereKey($filters['vehicle_type'])->value('name')
                    : null,
                'Route' => $filters['route_id']
                    ? TransportRoute::query()->whereKey($filters['route_id'])->value('route_name')
                    : null,
                'Status' => $filters['status']
                    ? (Vehicle::STATUSES[$filters['status']] ?? ucfirst((string) $filters['status']))
                    : null,
            ],
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

    public function store(StoreVehicleRequest $request): RedirectResponse|JsonResponse
    {
        $vehicle = Vehicle::create($request->validated())->load(['transporter:id,name', 'route:id,route_name,starting_point,ending_point']);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Vehicle saved successfully.',
                'vehicle' => [
                    'id' => $vehicle->id,
                    'registration_no' => $vehicle->registration_no,
                    'transporter_id' => $vehicle->transporter_id,
                    'route_id' => $vehicle->route_id,
                    'transporter_name' => $vehicle->transporter?->name,
                    'route_name' => $vehicle->route?->route_name,
                    'route_from' => $vehicle->route?->starting_point,
                    'route_to' => $vehicle->route?->ending_point,
                ],
            ]);
        }

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

    private function filteredVehiclesQuery(Request $request)
    {
        $filters = $this->filterValues($request);

        return Vehicle::query()
            ->select(['id', 'transporter_id', 'vehicle_type', 'registration_no', 'chassis_no', 'route_id', 'status', 'remarks', 'created_at'])
            ->with([
                'transporter:id,name',
                'vehicleType:id,name',
                'route:id,route_name,starting_point,ending_point',
            ])
            ->when($filters['search'] !== '', function ($query) use ($filters) {
                $search = $filters['search'];

                $query->where(function ($nestedQuery) use ($search) {
                    $nestedQuery
                        ->where('registration_no', 'like', "%{$search}%")
                        ->orWhere('chassis_no', 'like', "%{$search}%")
                        ->orWhere('remarks', 'like', "%{$search}%")
                        ->orWhereHas('transporter', fn ($transporterQuery) => $transporterQuery->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('vehicleType', fn ($vehicleTypeQuery) => $vehicleTypeQuery->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('route', fn ($routeQuery) => $routeQuery->where('route_name', 'like', "%{$search}%"));
                });
            })
            ->when($filters['transporter_id'], fn ($query, $transporterId) => $query->where('transporter_id', $transporterId))
            ->when($filters['vehicle_type'], fn ($query, $vehicleType) => $query->where('vehicle_type', $vehicleType))
            ->when($filters['route_id'], fn ($query, $routeId) => $query->where('route_id', $routeId))
            ->when($filters['status'], fn ($query, $status) => $query->where('status', $status))
            ->latest();
    }

    private function filterValues(Request $request): array
    {
        return [
            'search' => trim((string) $request->input('search', '')),
            'transporter_id' => $request->integer('transporter_id') ?: null,
            'vehicle_type' => $request->integer('vehicle_type') ?: null,
            'route_id' => $request->integer('route_id') ?: null,
            'status' => $request->input('status'),
        ];
    }

    private function vehicleExportColumns(): array
    {
        return [
            'sr_no' => 'Sr #',
            'transporter' => 'Transporter',
            'vehicle_type' => 'Vehicle Type',
            'registration_no' => 'Registration No',
            'chassis_no' => 'Chassis No',
            'route' => 'Route',
            'route_from' => 'Route From',
            'route_to' => 'Route To',
            'status' => 'Status',
            'remarks' => 'Remarks',
        ];
    }

    private function selectedVehicleExportColumns(Request $request): array
    {
        $available = $this->vehicleExportColumns();
        $requested = array_values(array_filter((array) $request->input('columns', array_keys($available)), 'is_string'));
        $selected = array_intersect_key($available, array_flip($requested));

        return $selected !== [] ? $selected : $available;
    }

    private function vehicleExportRow(Vehicle $vehicle, array $columns, int $serialNumber = 1): array
    {
        $row = [
            'sr_no' => $serialNumber,
            'transporter' => $vehicle->transporter?->name ?: '',
            'vehicle_type' => $vehicle->vehicleType?->name ?: '',
            'registration_no' => $vehicle->registration_no ?: '',
            'chassis_no' => $vehicle->chassis_no ?: '',
            'route' => $vehicle->route?->route_name ?: '',
            'route_from' => $vehicle->route?->starting_point ?: '',
            'route_to' => $vehicle->route?->ending_point ?: '',
            'status' => Vehicle::STATUSES[$vehicle->status] ?? ucfirst((string) $vehicle->status),
            'remarks' => $vehicle->remarks ?: '',
        ];

        $exportRow = [];

        foreach ($columns as $key => $label) {
            $exportRow[$label] = $row[$key] ?? '';
        }

        return $exportRow;
    }
}
