@extends('layouts.app', ['title' => 'All Vehicles | Free Public Transport System', 'pageBadge' => 'Vehicle Directory'])

@section('content')
    <style>
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
            <p class="page-eyebrow">Vehicle Directory</p>
            <h1 class="page-title">All Vehicles</h1>
            <p class="page-subtitle">Review registered vehicles, linked transporters, routes, and current operating status.</p>
        </div>
    </div>

    <section class="row g-4 stats-overlap">
        <div class="col-12">
            <div class="card section-card mb-4">
                <div class="card-body">
                    <form method="GET" class="d-flex flex-column gap-3">
                        <input type="hidden" name="search" value="{{ $filters['search'] }}">
                        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
                            <div>
                                <h3 class="section-title mb-1">Export Vehicles</h3>
                                <p class="section-copy mb-0">Download the current filtered vehicle list and choose the columns you want in the file.</p>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <button class="btn btn-success" type="submit" formaction="{{ route('vehicles.export.csv') }}">
                                    <i class="fa-regular fa-file-lines me-2"></i>CSV
                                </button>
                                <button class="btn btn-success" type="submit" formaction="{{ route('vehicles.export.excel') }}">
                                    <i class="fa-regular fa-file-excel me-2"></i>Excel
                                </button>
                                <button class="btn btn-danger" type="submit" formaction="{{ route('vehicles.export.pdf-view') }}" formtarget="_blank">
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
            <div class="card section-card table-card mb-4">
                <div class="card-header">
                    <div class="table-toolbar align-items-start align-items-md-center">
                        <div>
                            <h3 class="section-title">Vehicle Records</h3>
                            <p class="section-copy">Complete listing of vehicles available in the system.</p>
                        </div>
                        <form method="GET" action="{{ route('vehicles.index') }}" class="ms-md-auto js-live-search-form">
                            <div class="input-group input-group-sm" style="max-width: 220px;">
                                <input
                                    type="search"
                                    name="search"
                                    class="form-control form-control-sm js-live-search-input"
                                    value="{{ $search }}"
                                    placeholder="Search"
                                    autocomplete="off"
                                    aria-label="Search vehicle records"
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
                                    <th>Transporter</th>
                                    <th>Vehicle Type</th>
                                    <th>Registration No</th>
                                    <th>Chassis No</th>
                                    <th>Route</th>
                                    <th>Status</th>
                                    <th>Remarks</th>
                                    @if ($canManageVehicles)
                                        <th class="text-center">Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($vehicles as $vehicle)
                                    <tr>
                                        <td>{{ $vehicles->firstItem() + $loop->index }}</td>
                                        <td class="fw-semibold">{{ $vehicle->transporter?->name ?: 'N/A' }}</td>
                                        <td>{{ $vehicle->vehicleType?->name ?: 'N/A' }}</td>
                                        <td>{{ $vehicle->registration_no }}</td>
                                        <td>{{ $vehicle->chassis_no }}</td>
                                        <td>{{ $vehicle->route?->route_name ?: 'N/A' }}</td>
                                        <td class="text-capitalize">{{ $statuses[$vehicle->status] ?? ucfirst($vehicle->status) }}</td>
                                        <td>{{ $vehicle->remarks ?: 'N/A' }}</td>
                                        @if ($canManageVehicles)
                                            <td class="text-center text-nowrap">
                                                <div class="action-stack justify-content-center">
                                                    <a href="{{ route('vehicles.show', $vehicle) }}" class="action-btn btn-view" title="View Vehicle">
                                                        <i class="fa-solid fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('vehicles.edit', $vehicle) }}" class="action-btn btn-edit" title="Edit Vehicle">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                    </a>
                                                    <form action="{{ route('vehicles.destroy', $vehicle) }}" method="POST" class="d-inline" data-confirm-delete data-delete-message="Are you sure you want to delete <strong>{{ e($vehicle->registration_no) }}</strong>?">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="action-btn btn-vacate border-0" title="Delete Vehicle">
                                                            <i class="fa-solid fa-trash-can"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr><td colspan="{{ $canManageVehicles ? 9 : 8 }}" class="text-center text-muted py-4">No vehicles found yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @include('settings.partials.pagination', ['paginator' => $vehicles, 'perPage' => $perPage])
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.js-live-search-form').forEach(function (form) {
            var input = form.querySelector('.js-live-search-input');
            var timer;

            if (!input) {
                return;
            }

            input.addEventListener('input', function () {
                window.clearTimeout(timer);
                timer = window.setTimeout(function () {
                    form.requestSubmit();
                }, 250);
            });
        });
    </script>
@endpush
