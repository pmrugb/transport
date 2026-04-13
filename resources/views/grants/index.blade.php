@extends('layouts.app', ['title' => 'All Grants | Free Public Transport System', 'pageBadge' => 'Grant Directory'])

@section('content')
    <div class="page-hero d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
        <div>
            <p class="page-eyebrow">Grant Directory</p>
            <h1 class="page-title">All Grants</h1>
            <p class="page-subtitle">Review approved grants, financial years, districts, and current budget status.</p>
        </div>
    </div>

    <section class="row g-4 stats-overlap">
        <div class="col-sm-6 col-xl-4"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">Total Grants</p><h2 class="stat-value">{{ $stats['total'] }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-sack-dollar app-icon"></i></span></div><p class="stat-note">All approved grant records in the system.</p></div></div></div>
        <div class="col-sm-6 col-xl-4"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">Active Grants</p><h2 class="stat-value">{{ $stats['active'] }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-circle-check app-icon"></i></span></div><p class="stat-note">Grant records currently marked as active.</p></div></div></div>
        <div class="col-sm-6 col-xl-4"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">Covered Districts</p><h2 class="stat-value">{{ $stats['districts'] }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-map-location-dot app-icon"></i></span></div><p class="stat-note">Districts currently linked with grant records.</p></div></div></div>
    </section>

    <section class="card section-card table-card mb-4">
        <div class="card-header">
            <div class="table-toolbar">
                <div>
                    <h3 class="section-title">Grant Records</h3>
                    <p class="section-copy">Complete listing of main budget grant records.</p>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-shell table-wrap">
                <table class="table table-app table-routes align-middle">
                    <thead>
                        <tr>
                            <th>Sr #</th>
                            <th>Title</th>
                            <th>Total Amount</th>
                            <th>Financial Year</th>
                            <th>District</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            @if ($canManageGrants)
                                <th class="text-center">Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($grants as $grant)
                            <tr>
                                <td class="text-nowrap">{{ $grants->firstItem() + $loop->index }}</td>
                                <td class="fw-semibold">{{ $grant->title }}</td>
                                <td class="text-nowrap">{{ number_format((float) $grant->total_amount, 2) }}</td>
                                <td class="text-nowrap">{{ $grant->financial_year }}</td>
                                <td class="text-nowrap">{{ $grant->district?->name ?: 'N/A' }}</td>
                                <td class="text-nowrap">{{ $grant->start_date?->format('Y-m-d') ?: 'N/A' }}</td>
                                <td class="text-nowrap">{{ $grant->end_date?->format('Y-m-d') ?: 'N/A' }}</td>
                                <td class="text-nowrap">{{ $statuses[$grant->status] ?? ucfirst($grant->status) }}</td>
                                @if ($canManageGrants)
                                    <td class="text-center text-nowrap">
                                        <div class="action-stack justify-content-center">
                                            <a href="{{ route('grants.show', $grant) }}" class="action-btn btn-view" title="View Grant"><i class="fa-solid fa-eye"></i></a>
                                            <a href="{{ route('grants.edit', $grant) }}" class="action-btn btn-edit" title="Edit Grant"><i class="fa-solid fa-pen-to-square"></i></a>
                                            <form action="{{ route('grants.destroy', $grant) }}" method="POST" class="d-inline" data-confirm-delete data-delete-message="Are you sure you want to delete <strong>{{ e($grant->title) }}</strong>?">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="action-btn btn-vacate border-0" title="Delete Grant"><i class="fa-solid fa-trash-can"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr><td colspan="{{ $canManageGrants ? 9 : 8 }}" class="text-center text-muted py-4">No grants found yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @include('settings.partials.pagination', ['paginator' => $grants, 'perPage' => $perPage])
        </div>
    </section>
@endsection
