@extends('layouts.app', ['title' => 'Export Reports | Free Public Transport System', 'pageBadge' => 'Reporting'])

@section('content')
    <style>
        .report-export-card .form-check-input,
        .report-filter-card .form-check-input {
            accent-color: #198754;
        }

        .report-column-grid label {
            width: 100%;
            padding: 0.5rem 0.7rem;
            border: 1px solid #d7e7dd;
            border-radius: 0.7rem;
            background: #f8fbf9;
            font-size: 0.88rem;
            line-height: 1.2;
        }

        .report-column-grid .form-check-input:checked + span {
            color: #146c43;
            font-weight: 600;
        }

        .report-toolbar {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 1rem;
            align-items: center;
        }

        .report-filter-grid .col-lg-2,
        .report-filter-grid .col-lg-3 {
            display: flex;
            flex-direction: column;
        }
    </style>

    <div class="page-hero d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
        <div>
            <p class="page-eyebrow">Reporting</p>
            <h1 class="page-title">Export Reports</h1>
            <p class="page-subtitle">View, filter, analyze, and export professional transport payment reports from one place.</p>
        </div>
    </div>

    <section class="row g-4 stats-overlap">
        <div class="col-sm-6 col-xl-3"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">Filtered Records</p><h2 class="stat-value">{{ $stats['records'] }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-chart-line app-icon"></i></span></div><p class="stat-note">Records matching the current report filters.</p></div></div></div>
        <div class="col-sm-6 col-xl-3"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">Due Payments</p><h2 class="stat-value">{{ $stats['due'] }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-hourglass-half app-icon"></i></span></div><p class="stat-note">Outstanding due payments in the report set.</p></div></div></div>
        <div class="col-sm-6 col-xl-3"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">Paid Payments</p><h2 class="stat-value">{{ $stats['paid'] }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-circle-check app-icon"></i></span></div><p class="stat-note">Paid records visible in the current report.</p></div></div></div>
        <div class="col-sm-6 col-xl-3"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">Total Amount</p><h2 class="stat-value stat-value-compact">{{ number_format((float) $stats['amount'], 0) }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-wallet app-icon"></i></span></div><p class="stat-note">Combined amount across the filtered report data.</p></div></div></div>
    </section>

    <section class="row g-4 mt-2">
        <div class="col-12">
            <div class="card section-card report-export-card">
                <div class="card-body">
                    <form method="GET" class="d-flex flex-column gap-3">
                        <input type="hidden" name="search" value="{{ $filters['search'] }}">
                        <input type="hidden" name="status" value="{{ $filters['status'] }}">
                        <input type="hidden" name="district_id" value="{{ $filters['district_id'] }}">
                        <input type="hidden" name="department_id" value="{{ $filters['department_id'] }}">
                        <input type="hidden" name="route_id" value="{{ $filters['route_id'] }}">
                        <input type="hidden" name="transporter_id" value="{{ $filters['transporter_id'] }}">
                        <input type="hidden" name="from_date" value="{{ $filters['from_date'] }}">
                        <input type="hidden" name="to_date" value="{{ $filters['to_date'] }}">
                        <div class="report-toolbar">
                            <div>
                                <h3 class="section-title mb-1">Export Reports</h3>
                                <p class="section-copy mb-0">Download the currently filtered report data in CSV, Excel, or PDF format with your selected columns.</p>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <button class="btn btn-success" type="submit" formaction="{{ route('reports.export.csv') }}"><i class="fa-regular fa-file-lines me-2"></i>CSV</button>
                                <button class="btn btn-success" type="submit" formaction="{{ route('reports.export.excel') }}"><i class="fa-regular fa-file-excel me-2"></i>Excel</button>
                                <button class="btn btn-danger" type="submit" formaction="{{ route('reports.export.pdf-view') }}" formtarget="_blank"><i class="fa-regular fa-file-pdf me-2"></i>PDF</button>
                            </div>
                        </div>
                        <details>
                            <summary class="small fw-semibold" style="cursor: pointer; font-size: 0.82rem;">Choose Export Columns</summary>
                            <div class="row g-2 mt-2 report-column-grid">
                                @foreach ($exportColumns as $key => $label)
                                    <div class="col-sm-6 col-lg-4 col-xl-3">
                                        <label class="form-check-label d-flex align-items-center gap-2">
                                            <input class="form-check-input mt-0" type="checkbox" name="columns[]" value="{{ $key }}" @checked(array_key_exists($key, $selectedExportColumns))>
                                            <span>{{ $label }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </details>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card section-card report-filter-card">
                <div class="card-header">
                    <div class="report-toolbar">
                        <div>
                            <h3 class="section-title mb-1">Report Filters</h3>
                            <p class="section-copy mb-0">Narrow reports by status, date range, district, department, route, and transporter.</p>
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            <button class="btn btn-success" form="reportFilters" type="submit"><i class="fa-solid fa-filter me-2"></i>Apply Filters</button>
                            <a class="btn btn-outline-secondary" href="{{ route('reports.index') }}"><i class="fa-solid fa-rotate-right me-2"></i>Reset</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('reports.index') }}" id="reportFilters">
                        <div class="row g-3 report-filter-grid">
                            <div class="col-md-6 col-lg-3">
                                <label class="form-label fw-semibold" for="search">Search</label>
                                <input class="form-control" id="search" name="search" type="search" value="{{ $filters['search'] }}" placeholder="Driver, route, vehicle, transporter">
                            </div>
                            <div class="col-md-6 col-lg-2">
                                <label class="form-label fw-semibold" for="status">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="">All statuses</option>
                                    @foreach ($statuses as $value => $label)
                                        <option value="{{ $value }}" @selected($filters['status'] === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 col-lg-2">
                                <label class="form-label fw-semibold" for="from_date">Trip Date From</label>
                                <input class="form-control" id="from_date" name="from_date" type="date" value="{{ $filters['from_date'] }}">
                            </div>
                            <div class="col-md-6 col-lg-2">
                                <label class="form-label fw-semibold" for="to_date">Trip Date To</label>
                                <input class="form-control" id="to_date" name="to_date" type="date" value="{{ $filters['to_date'] }}">
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <label class="form-label fw-semibold" for="district_id">District</label>
                                <select class="form-select" id="district_id" name="district_id">
                                    <option value="">All districts</option>
                                    @foreach ($districts as $district)
                                        <option value="{{ $district->id }}" @selected((string) $filters['district_id'] === (string) $district->id)>{{ $district->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <label class="form-label fw-semibold" for="department_id">Department</label>
                                <select class="form-select" id="department_id" name="department_id">
                                    <option value="">All departments</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}" @selected((string) $filters['department_id'] === (string) $department->id)>{{ $department->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <label class="form-label fw-semibold" for="route_id">Route</label>
                                <select class="form-select" id="route_id" name="route_id">
                                    <option value="">All routes</option>
                                    @foreach ($routes as $route)
                                        <option value="{{ $route->id }}" @selected((string) $filters['route_id'] === (string) $route->id)>{{ $route->route_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <label class="form-label fw-semibold" for="transporter_id">Transporter</label>
                                <select class="form-select" id="transporter_id" name="transporter_id">
                                    <option value="">All transporters</option>
                                    @foreach ($transporters as $transporter)
                                        <option value="{{ $transporter->id }}" @selected((string) $filters['transporter_id'] === (string) $transporter->id)>{{ $transporter->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card section-card table-card mb-4">
                <div class="card-header">
                    <div class="report-toolbar">
                        <div>
                            <h3 class="section-title mb-1">Report Results</h3>
                            <p class="section-copy mb-0">Professional reporting view for transport operations, payment status, and route activity.</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-shell table-wrap">
                        <table class="table table-app align-middle">
                            <thead>
                                <tr>
                                    <th>Sr #</th>
                                    <th>Trip Date</th>
                                    <th>Payment Date</th>
                                    <th>Status</th>
                                    <th>Department</th>
                                    <th>District</th>
                                    <th>Route</th>
                                    <th>Transporter</th>
                                    <th>Vehicle</th>
                                    <th>Driver</th>
                                    <th>Trips</th>
                                    <th>Fare</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($reports as $report)
                                    @php
                                        $trip = $report->trip;
                                        $route = $report->route;
                                        $districtLabel = $trip?->district?->name ?: ($route?->district?->name ?: ($report->transporter?->district?->name ?: 'N/A'));
                                    @endphp
                                    <tr>
                                        <td>{{ $reports->firstItem() + $loop->index }}</td>
                                        <td class="text-nowrap">{{ $trip?->trip_date?->format('Y-m-d') ?: 'N/A' }}</td>
                                        <td class="text-nowrap">{{ $report->calculation_date?->format('Y-m-d') ?: 'N/A' }}</td>
                                        <td>{{ $statuses[$report->status] ?? ucfirst($report->status) }}</td>
                                        <td>{{ $trip?->department?->name ?: 'N/A' }}</td>
                                        <td>{{ $districtLabel }}</td>
                                        <td>{{ $route?->route_name ?: 'N/A' }}</td>
                                        <td>{{ $report->transporter?->name ?: 'N/A' }}</td>
                                        <td>{{ $report->vehicle?->registration_no ?: 'N/A' }}</td>
                                        <td>{{ $trip?->driver_name ?: 'N/A' }}<br><span class="text-muted small">{{ $trip?->driver_mobile ?: 'N/A' }}</span></td>
                                        <td>{{ $report->no_of_trips }}</td>
                                        <td>{{ number_format((float) $report->fare_amount, 2) }}</td>
                                        <td>{{ number_format((float) $report->total_amount, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="13" class="text-center text-muted py-4">No report records found for the selected filters.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @include('settings.partials.pagination', ['paginator' => $reports, 'perPage' => $perPage])
                </div>
            </div>
        </div>
    </section>
@endsection
