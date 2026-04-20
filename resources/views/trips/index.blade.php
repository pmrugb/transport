@extends('layouts.app', ['title' => 'All Trips | Free Public Transport System', 'pageBadge' => 'Trip Management'])

@section('content')
    <style>
        .trip-filter-card {
            border-radius: 1rem;
        }

        .trip-filter-card .card-header {
            padding: 0.8rem 0.95rem;
            border-bottom-width: 1px;
        }

        .trip-filter-card .card-body {
            padding: 0.85rem 0.95rem 0.95rem;
        }

        .trip-filter-card .form-label {
            font-size: 0.82rem;
            margin-bottom: 0.35rem;
        }

        .trip-filter-card .form-control,
        .trip-filter-card .form-select {
            min-height: 40px;
            border-radius: 0.8rem;
            font-size: 0.88rem;
            padding-top: 0.45rem;
            padding-bottom: 0.45rem;
        }

        .trip-filter-grid {
            row-gap: 0.7rem;
        }

        .trip-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .trip-filter-title {
            margin: 0;
            font-size: 0.98rem;
            font-weight: 800;
            color: #2c3a4d;
        }

        .trip-filter-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.45rem;
        }

        .trip-filter-actions .btn {
            min-width: 0;
            padding: 0.65rem 0.8rem;
            border-radius: 0.75rem;
            font-size: 0.88rem;
            font-weight: 700;
            line-height: 1.1;
        }

        .trip-filter-actions .btn-outline-secondary {
            min-width: 94px;
        }

        .export-columns-toggle {
            cursor: pointer;
            font-size: 0.82rem;
            font-weight: 600;
        }

        .export-columns-grid .form-check-input {
            accent-color: #198754;
        }

        .export-columns-grid label {
            width: 100%;
            padding: 0.5rem 0.7rem;
            border: 1px solid #d7e7dd;
            border-radius: 0.7rem;
            background: #f8fbf9;
            font-size: 0.88rem;
            line-height: 1.2;
        }

        .export-columns-grid .form-check-input:checked + span {
            color: #146c43;
            font-weight: 600;
        }
    </style>

    <div class="page-hero d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
        <div>
            <p class="page-eyebrow">Trip Management</p>
            <h1 class="page-title">All Trips</h1>
            <p class="page-subtitle">Track submitted trips, operational assignments, fare values, and funding linkage in one directory.</p>
        </div>
        @if ($canCreateTrips)
            <a class="btn btn-success" href="{{ route('trips.create') }}">
                <i class="fa-solid fa-plus me-2"></i>Add Trip
            </a>
        @endif
    </div>

    <section class="row g-4 stats-overlap">
        <div class="col-sm-6 col-xl-4"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">Total Trips</p><h2 class="stat-value stat-value-compact">{{ $stats['total'] }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-route app-icon"></i></span></div><p class="stat-note">All trip entries recorded in the system.</p></div></div></div>
        <div class="col-sm-6 col-xl-4"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">Today</p><h2 class="stat-value stat-value-compact">{{ $stats['today'] }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-calendar-day app-icon"></i></span></div><p class="stat-note">Trips logged for the current day.</p></div></div></div>
        <div class="col-sm-6 col-xl-4"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">Total Amount</p><h2 class="stat-value stat-value-compact">{{ number_format((float) $stats['amount']) }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-money-check-dollar app-icon"></i></span></div><p class="stat-note">Cumulative amount across recorded trips.</p></div></div></div>
    </section>

    <section class="row g-4 mt-2">
        <div class="col-12">
            <div id="tripsResultsRegion" data-live-region>
                <div class="card section-card mb-4">
                    <div class="card-body">
                        <form method="GET" class="d-flex flex-column gap-3">
                            <input type="hidden" name="search" value="{{ $filters['search'] }}">
                            <input type="hidden" name="status" value="{{ $filters['status'] }}">
                            <input type="hidden" name="district_id" value="{{ $filters['district_id'] }}">
                            <input type="hidden" name="transporter_id" value="{{ $filters['transporter_id'] }}">
                            <input type="hidden" name="route_id" value="{{ $filters['route_id'] }}">
                            <input type="hidden" name="from_date" value="{{ $filters['from_date'] }}">
                            <input type="hidden" name="to_date" value="{{ $filters['to_date'] }}">
                            <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
                                <div>
                                    <h3 class="section-title mb-1">Export Trips</h3>
                                    <p class="section-copy mb-0">Download the current filtered trip list and toggle the columns you want included.</p>
                                </div>
                                <div class="d-flex flex-wrap gap-2">
                                    <button class="btn btn-success" type="submit" formaction="{{ route('trips.export.csv') }}">
                                        <i class="fa-regular fa-file-lines me-2"></i>CSV
                                    </button>
                                    <button class="btn btn-success" type="submit" formaction="{{ route('trips.export.excel') }}">
                                        <i class="fa-regular fa-file-excel me-2"></i>Excel
                                    </button>
                                    <button class="btn btn-danger" type="submit" formaction="{{ route('trips.export.pdf-view') }}" formtarget="_blank">
                                        <i class="fa-regular fa-file-pdf me-2"></i>PDF
                                    </button>
                                </div>
                            </div>
                            <details>
                                <summary class="export-columns-toggle">Choose Export Columns</summary>
                                <div class="row g-2 mt-2 export-columns-grid">
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
                <div class="card section-card trip-filter-card mb-4">
                    <div class="card-header">
                        <div class="trip-toolbar">
                            <h3 class="trip-filter-title">Filters</h3>
                            <div class="trip-filter-actions">
                                <button class="btn btn-success" form="tripFilters" type="submit"><i class="fa-solid fa-filter me-2"></i>Apply Filters</button>
                                <a class="btn btn-outline-secondary" href="{{ route('trips.index') }}"><i class="fa-solid fa-rotate-right me-2"></i>Reset</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('trips.index') }}" id="tripFilters">
                            <input type="hidden" name="search" value="{{ $filters['search'] }}">
                            <div class="row trip-filter-grid">
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold" for="status">Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="">All statuses</option>
                                        @foreach ($statuses as $value => $label)
                                            <option value="{{ $value }}" @selected($filters['status'] === $value)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold" for="district_id">District</label>
                                    <select class="form-select" id="district_id" name="district_id">
                                        <option value="">All districts</option>
                                        @foreach ($districts as $district)
                                            <option value="{{ $district->id }}" @selected((string) $filters['district_id'] === (string) $district->id)>{{ $district->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold" for="transporter_id">Transporter</label>
                                    <select class="form-select" id="transporter_id" name="transporter_id">
                                        <option value="">All transporters</option>
                                        @foreach ($transporters as $transporter)
                                            <option value="{{ $transporter->id }}" @selected((string) $filters['transporter_id'] === (string) $transporter->id)>{{ $transporter->name }}{{ $transporter->cnic ? ' - '.$transporter->cnic : '' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold" for="route_id">Route</label>
                                    <select class="form-select" id="route_id" name="route_id">
                                        <option value="">All routes</option>
                                        @foreach ($routes as $route)
                                            <option value="{{ $route->id }}" @selected((string) $filters['route_id'] === (string) $route->id)>{{ $route->route_name }} ({{ $route->starting_point }} → {{ $route->ending_point }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold" for="from_date">From Date</label>
                                    <input class="form-control" id="from_date" name="from_date" type="date" value="{{ $filters['from_date'] }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold" for="to_date">To Date</label>
                                    <input class="form-control" id="to_date" name="to_date" type="date" value="{{ $filters['to_date'] }}">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card section-card table-card mb-4">
                    <div class="card-header">
                        <div class="table-toolbar align-items-start align-items-md-center">
                            <div>
                                <h3 class="section-title">Trip Records</h3>
                                <p class="section-copy">Complete listing of trip data entered through the trip management form.</p>
                            </div>
                            <form method="GET" action="{{ route('trips.index') }}" class="ms-md-auto js-live-search-form" data-live-search-target="#tripsResultsRegion">
                                <input type="hidden" name="status" value="{{ $filters['status'] }}">
                                <input type="hidden" name="district_id" value="{{ $filters['district_id'] }}">
                                <input type="hidden" name="transporter_id" value="{{ $filters['transporter_id'] }}">
                                <input type="hidden" name="route_id" value="{{ $filters['route_id'] }}">
                                <input type="hidden" name="from_date" value="{{ $filters['from_date'] }}">
                                <input type="hidden" name="to_date" value="{{ $filters['to_date'] }}">
                                <div class="input-group input-group-sm" style="max-width: 220px;">
                                    <input
                                        type="search"
                                        name="search"
                                        class="form-control form-control-sm js-live-search-input"
                                        value="{{ $search }}"
                                        placeholder="Search"
                                        autocomplete="off"
                                        aria-label="Search trip records"
                                    >
                                    <button class="btn btn-outline-secondary btn-sm" type="submit" title="Search">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-shell table-wrap">
                            <table class="table table-app align-middle">
                                <thead>
                                    <tr>
                                        <th>Sr #</th>
                                        <th>Vehicle</th>
                                        <th>Route</th>
                                        <th>Transporter</th>
                                        <th>Driver</th>
                                        <th>CNIC</th>
                                        <th>District</th>
                                        <th>Fare</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        @if ($canEditTrips || $canDeleteTrips)
                                            <th class="text-center">Action</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($trips as $trip)
                                        <tr>
                                            <td>{{ $trips->firstItem() + $loop->index }}</td>
                                            <td>{{ $trip->vehicle?->registration_no ?: 'N/A' }}</td>
                                            <td>{{ $trip->route?->route_name ?: 'N/A' }}</td>
                                            <td>{{ $trip->transporter?->name ?: 'N/A' }}</td>
                                            <td>{{ $trip->driver_name }}<br><span class="text-muted small">{{ $trip->driver_mobile }}</span></td>
                                            <td>{{ $trip->driver_cnic ?: 'N/A' }}</td>
                                            <td>{{ $trip->district?->name ?: 'N/A' }}</td>
                                            <td>{{ number_format((float) $trip->fare_amount, 2) }}</td>
                                            <td>{{ number_format((float) $trip->total_amount, 2) }}</td>
                                            <td>{{ $statuses[$trip->status] ?? ucfirst($trip->status) }}</td>
                                            @if ($canEditTrips || $canDeleteTrips)
                                                <td class="text-center text-nowrap">
                                                    <div class="action-stack justify-content-center">
                                                        <a href="{{ route('trips.show', $trip) }}" class="action-btn btn-view" title="View Trip">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </a>
                                                        @if ($canEditTrips)
                                                            <a href="{{ route('trips.edit', $trip) }}" class="action-btn btn-edit" title="Edit Trip">
                                                                <i class="fa-solid fa-pen-to-square"></i>
                                                            </a>
                                                        @endif
                                                        @if ($canDeleteTrips)
                                                            <form action="{{ route('trips.destroy', $trip) }}" method="POST" class="d-inline" data-confirm-delete data-delete-message="Are you sure you want to delete this trip record?">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="action-btn btn-vacate border-0" title="Delete Trip">
                                                                    <i class="fa-solid fa-trash-can"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            @endif
                                        </tr>
                                    @empty
                                        <tr><td colspan="{{ ($canEditTrips || $canDeleteTrips) ? 13 : 12 }}" class="text-center text-muted py-4">No trips found yet.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @include('settings.partials.pagination', ['paginator' => $trips, 'perPage' => $perPage])
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
