@extends('layouts.app', ['title' => 'All Transporters | Free Public Transport System', 'pageBadge' => 'Transporter Directory'])

@php use App\Models\Operator; @endphp

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
            <p class="page-eyebrow">Transporter Directory</p>
            <h1 class="page-title">All Transporters</h1>
            <p class="page-subtitle">Review all registered transporters, their owner type, district, and contact details.</p>
        </div>
    </div>

    <section class="row g-4 stats-overlap">
        <div class="col-sm-6 col-xl-4"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">Total Transporters</p><h2 class="stat-value">{{ $stats['total'] }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-users app-icon"></i></span></div><p class="stat-note">All transporters currently registered in the system.</p></div></div></div>
        <div class="col-sm-6 col-xl-4"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">Private Owners</p><h2 class="stat-value">{{ $stats['private'] }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-user app-icon"></i></span></div><p class="stat-note">Private transporters currently available for route assignment.</p></div></div></div>
        <div class="col-sm-6 col-xl-4"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">Covered Districts</p><h2 class="stat-value">{{ $stats['districts'] }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-map-location-dot app-icon"></i></span></div><p class="stat-note">Districts currently linked with transporter records.</p></div></div></div>
    </section>

    <div id="transportersResultsRegion" data-live-region>
        <section class="card section-card mb-4">
            <div class="card-body">
                <form method="GET" class="d-flex flex-column gap-3">
                    <input type="hidden" name="search" value="{{ $filters['search'] }}">
                    <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
                        <div>
                            <h3 class="section-title mb-1">Export Transporters</h3>
                            <p class="section-copy mb-0">Download the current filtered transporter list and choose exactly which columns to include.</p>
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            <button class="btn btn-success" type="submit" formaction="{{ route('transporters.export.csv') }}">
                                <i class="fa-regular fa-file-lines me-2"></i>CSV
                            </button>
                            <button class="btn btn-success" type="submit" formaction="{{ route('transporters.export.excel') }}">
                                <i class="fa-regular fa-file-excel me-2"></i>Excel
                            </button>
                            <button class="btn btn-danger" type="submit" formaction="{{ route('transporters.export.pdf-view') }}" formtarget="_blank">
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
        </section>

        <section class="card section-card table-card mb-4">
            <div class="card-header">
                <div class="table-toolbar align-items-start align-items-md-center">
                    <div>
                        <h3 class="section-title">Transporter Records</h3>
                        <p class="section-copy">Complete listing of company and private transporter records.</p>
                    </div>
                    <form method="GET" action="{{ route('transporters.index') }}" class="ms-md-auto js-live-search-form" data-live-search-target="#transportersResultsRegion">
                        <div class="input-group input-group-sm" style="max-width: 220px;">
                            <input
                                type="search"
                                name="search"
                                class="form-control form-control-sm js-live-search-input"
                                value="{{ $search }}"
                                placeholder="Search"
                                autocomplete="off"
                                aria-label="Search transporter records"
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
                    <table class="table table-app table-routes align-middle">
                        <thead>
                            <tr>
                                <th>Sr #</th>
                                <th>Owner Type</th>
                                <th>Name</th>
                                <th>CNIC</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>District</th>
                                @if ($canManageTransporters)
                                    <th class="text-center">Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($operators as $operator)
                                <tr>
                                    <td class="text-nowrap">{{ $operators->firstItem() + $loop->index }}</td>
                                    <td class="text-nowrap">{{ $ownerTypes[$operator->owner_type] ?? ucfirst((string) $operator->owner_type) }}</td>
                                    <td class="fw-semibold text-nowrap">{{ $operator->name }}</td>
                                    <td class="text-nowrap">{{ $operator->cnic }}</td>
                                    <td class="text-nowrap">{{ $operator->phone }}</td>
                                    <td>{{ $operator->address }}</td>
                                    <td class="text-nowrap">{{ $operator->district?->name }}</td>
                                    @if ($canManageTransporters)
                                        <td class="text-center text-nowrap">
                                            <div class="action-stack justify-content-center">
                                                <a href="{{ route('transporters.show', $operator) }}" class="action-btn btn-view" title="View Transporter">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>
                                                <a href="{{ route('transporters.edit', $operator) }}" class="action-btn btn-edit" title="Edit Transporter">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                                <form action="{{ route('transporters.destroy', $operator) }}" method="POST" class="d-inline" data-confirm-delete data-delete-message="Are you sure you want to delete <strong>{{ e($operator->name) }}</strong>?">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="action-btn btn-vacate border-0" title="Delete Transporter">
                                                        <i class="fa-solid fa-trash-can"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr><td colspan="{{ $canManageTransporters ? 8 : 7 }}" class="text-center text-muted py-4">No transporters found yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @include('settings.partials.pagination', ['paginator' => $operators, 'perPage' => $perPage])
            </div>
        </section>
    </div>
@endsection
