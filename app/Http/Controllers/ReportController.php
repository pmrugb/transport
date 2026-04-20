<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\BuildsExcelExports;
use App\Models\Department;
use App\Models\District;
use App\Models\Operator;
use App\Models\TransportRoute;
use App\Models\TripCost;
use App\Models\TripDetail;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\View\View;

class ReportController extends Controller
{
    use BuildsExcelExports;

    public function index(Request $request): View
    {
        $this->ensureSuperadmin();

        $perPage = $this->resolvePerPage($request);
        $filters = $this->filterValues($request);
        $reportsQuery = $this->filteredReportsQuery($request);
        $statsQuery = $this->filteredReportsQuery($request);

        return view('reports.index', [
            'perPage' => $perPage,
            'filters' => $filters,
            'statuses' => TripCost::STATUSES,
            'districts' => District::query()->select(['id', 'name'])->orderBy('name')->get(),
            'departments' => Department::query()->select(['id', 'name'])->where('status', 'active')->orderBy('name')->get(),
            'transporters' => Operator::query()->select(['id', 'name'])->orderBy('name')->get(),
            'routes' => TransportRoute::query()->select(['id', 'route_name'])->orderBy('route_name')->get(),
            'exportColumns' => $this->reportExportColumns(),
            'selectedExportColumns' => $this->selectedReportExportColumns($request),
            'reports' => $reportsQuery
                ->paginate($this->paginationSize($perPage, (clone $reportsQuery)->toBase()->getCountForPagination()))
                ->withQueryString(),
            'stats' => [
                'records' => (clone $statsQuery)->count(),
                'due' => (clone $statsQuery)->where('status', 'due')->count(),
                'paid' => (clone $statsQuery)->where('status', 'paid')->count(),
                'amount' => (float) ((clone $statsQuery)->sum('total_amount') ?: 0),
            ],
        ]);
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $this->ensureSuperadmin();

        $columns = $this->selectedReportExportColumns($request);
        $filename = 'reports-'.now()->format('Ymd-His').'.csv';

        return response()->streamDownload(function () use ($request, $columns): void {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, array_values($columns));

            foreach ($this->filteredReportsQuery($request)->cursor() as $report) {
                fputcsv($handle, array_values($this->reportExportRow($report, $columns)));
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function exportExcel(Request $request): BinaryFileResponse
    {
        $this->ensureSuperadmin();

        $columns = $this->selectedReportExportColumns($request);
        $rows = $this->filteredReportsQuery($request)
            ->get()
            ->map(fn (TripCost $report): array => $this->reportExportRow($report, $columns))
            ->all();
        $filename = 'reports-'.now()->format('Ymd-His').'.xlsx';
        $tempPath = tempnam(sys_get_temp_dir(), 'reports-xlsx-');

        $this->buildExcelExport($tempPath, $rows, 'Reports', 'Reports Export');

        return response()->download(
            $tempPath,
            $filename,
            ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
        )->deleteFileAfterSend(true);
    }

    public function pdfView(Request $request): View
    {
        $this->ensureSuperadmin();

        $columns = $this->selectedReportExportColumns($request);
        $rows = $this->filteredReportsQuery($request)
            ->get()
            ->map(fn (TripCost $report): array => $this->reportExportRow($report, $columns))
            ->all();
        $filters = $this->filterValues($request);

        return view('exports.table-pdf', [
            'title' => 'Reports Export',
            'subtitle' => '',
            'columns' => $columns,
            'rows' => $rows,
            'filters' => $this->reportFilterLabels($filters),
        ]);
    }

    private function filteredReportsQuery(Request $request)
    {
        $filters = $this->filterValues($request);
        $user = $request->user();
        $natcoDepartmentId = $user?->isNatcoDepartmentUser() ? Department::natcoId() : null;

        return TripCost::query()
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
                'trip:id,district_id,department_id,created_by,trip_date,driver_name,driver_mobile,status,remarks',
                'trip.district:id,name',
                'trip.department:id,name',
                'route:id,route_name,starting_point,ending_point,district_id',
                'route.district:id,name',
                'vehicle:id,registration_no,chassis_no,vehicle_type',
                'vehicle.type:id,name',
                'transporter:id,name,owner_type,cnic,phone,address,district_id',
                'transporter.district:id,name',
            ])
            ->when($user?->isNatcoDepartmentUser(), fn ($query) => $query->forNatco($natcoDepartmentId))
            ->when($filters['search'] !== '', function ($query) use ($filters) {
                $search = $filters['search'];

                $query->where(function ($nestedQuery) use ($search) {
                    $nestedQuery
                        ->whereHas('trip', fn ($tripQuery) => $tripQuery
                            ->where('driver_name', 'like', "%{$search}%")
                            ->orWhere('driver_mobile', 'like', "%{$search}%"))
                        ->orWhereHas('route', fn ($routeQuery) => $routeQuery->where('route_name', 'like', "%{$search}%"))
                        ->orWhereHas('vehicle', fn ($vehicleQuery) => $vehicleQuery->where('registration_no', 'like', "%{$search}%"))
                        ->orWhereHas('transporter', fn ($transporterQuery) => $transporterQuery->where('name', 'like', "%{$search}%"));
                });
            })
            ->when($filters['status'], fn ($query, $status) => $query->where('status', $status))
            ->when($filters['district_id'], function ($query, $districtId) {
                $query->where(function ($nestedQuery) use ($districtId) {
                    $nestedQuery
                        ->whereHas('trip', fn ($tripQuery) => $tripQuery->where('district_id', $districtId))
                        ->orWhereHas('route', fn ($routeQuery) => $routeQuery->where('district_id', $districtId))
                        ->orWhereHas('transporter', fn ($transporterQuery) => $transporterQuery->where('district_id', $districtId));
                });
            })
            ->when($filters['department_id'], fn ($query, $departmentId) => $query->whereHas('trip', fn ($tripQuery) => $tripQuery->where('department_id', $departmentId)))
            ->when($filters['transporter_id'], fn ($query, $transporterId) => $query->where('transporter_id', $transporterId))
            ->when($filters['route_id'], fn ($query, $routeId) => $query->where('route_id', $routeId))
            ->when($filters['from_date'], fn ($query, $fromDate) => $query->whereHas('trip', fn ($tripQuery) => $tripQuery->whereDate('trip_date', '>=', $fromDate)))
            ->when($filters['to_date'], fn ($query, $toDate) => $query->whereHas('trip', fn ($tripQuery) => $tripQuery->whereDate('trip_date', '<=', $toDate)))
            ->orderByDesc('calculation_date')
            ->orderByDesc('created_at');
    }

    private function filterValues(Request $request): array
    {
        return [
            'search' => trim((string) $request->input('search', '')),
            'status' => $request->input('status'),
            'district_id' => $request->integer('district_id') ?: null,
            'department_id' => $request->integer('department_id') ?: null,
            'transporter_id' => $request->integer('transporter_id') ?: null,
            'route_id' => $request->integer('route_id') ?: null,
            'from_date' => $request->input('from_date'),
            'to_date' => $request->input('to_date'),
        ];
    }

    private function reportExportColumns(): array
    {
        return [
            'trip_date' => 'Trip Date',
            'payment_date' => 'Payment Date',
            'status' => 'Payment Status',
            'department' => 'Department',
            'district' => 'District',
            'route' => 'Route',
            'transporter' => 'Transporter',
            'vehicle_registration' => 'Vehicle Registration',
            'vehicle_type' => 'Vehicle Type',
            'driver_name' => 'Driver Name',
            'driver_mobile' => 'Driver Mobile',
            'no_of_trips' => 'No. of Trips',
            'fare_amount' => 'Fare Amount',
            'total_amount' => 'Total Amount',
            'remarks' => 'Remarks',
        ];
    }

    private function selectedReportExportColumns(Request $request): array
    {
        $available = $this->reportExportColumns();
        $requested = array_values(array_filter((array) $request->input('columns', array_keys($available)), 'is_string'));
        $selected = array_intersect_key($available, array_flip($requested));

        return $selected !== [] ? $selected : $available;
    }

    private function reportExportRow(TripCost $report, array $columns): array
    {
        $trip = $report->trip;
        $route = $report->route;
        $vehicle = $report->vehicle;
        $transporter = $report->transporter;

        $row = [
            'trip_date' => $trip?->trip_date?->format('Y-m-d') ?: '',
            'payment_date' => $report->calculation_date?->format('Y-m-d') ?: '',
            'status' => TripCost::STATUSES[$report->status] ?? ucfirst((string) $report->status),
            'department' => $trip?->department?->name ?: '',
            'district' => $trip?->district?->name ?: ($route?->district?->name ?: ($transporter?->district?->name ?: '')),
            'route' => $route?->route_name ?: '',
            'transporter' => $transporter?->name ?: '',
            'vehicle_registration' => $vehicle?->registration_no ?: '',
            'vehicle_type' => $vehicle?->type?->name ?: '',
            'driver_name' => $trip?->driver_name ?: '',
            'driver_mobile' => $trip?->driver_mobile ?: '',
            'no_of_trips' => $report->no_of_trips,
            'fare_amount' => (float) $report->fare_amount,
            'total_amount' => (float) $report->total_amount,
            'remarks' => $report->remarks ?: ($trip?->remarks ?: ''),
        ];

        $exportRow = [];

        foreach ($columns as $key => $label) {
            $exportRow[$label] = $row[$key] ?? '';
        }

        return $exportRow;
    }

    private function reportFilterLabels(array $filters): array
    {
        return [
            'Search' => $filters['search'],
            'Status' => $filters['status'] ? (TripCost::STATUSES[$filters['status']] ?? ucfirst((string) $filters['status'])) : null,
            'Trip Date From' => $filters['from_date'],
            'Trip Date To' => $filters['to_date'],
            'District' => $filters['district_id'] ? District::query()->whereKey($filters['district_id'])->value('name') : null,
            'Department' => $filters['department_id'] ? Department::query()->whereKey($filters['department_id'])->value('name') : null,
            'Route' => $filters['route_id'] ? TransportRoute::query()->whereKey($filters['route_id'])->value('route_name') : null,
            'Transporter' => $filters['transporter_id'] ? Operator::query()->whereKey($filters['transporter_id'])->value('name') : null,
        ];
    }

    private function ensureSuperadmin(): void
    {
        abort_unless(auth()->user()?->isSuperadmin(), 403);
    }
}
