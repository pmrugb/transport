@extends('layouts.app', ['title' => 'All Fares | Free Public Transport System', 'pageBadge' => 'Fare Directory'])

@section('content')
    <div class="page-hero d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
        <div>
            <p class="page-eyebrow">Fare Directory</p>
            <h1 class="page-title">All Fares</h1>
            <p class="page-subtitle">Review route fare records with amounts, date ranges, and current status.</p>
        </div>
    </div>

    <section class="row g-4 stats-overlap">
        <div class="col-sm-6 col-xl-4"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">Total Fares</p><h2 class="stat-value">{{ $stats['total'] }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-money-bill-wave app-icon"></i></span></div><p class="stat-note">All fare records currently available in the system.</p></div></div></div>
        <div class="col-sm-6 col-xl-4"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">Active Fares</p><h2 class="stat-value">{{ $stats['active'] }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-circle-check app-icon"></i></span></div><p class="stat-note">Fare records currently marked as active.</p></div></div></div>
        <div class="col-sm-6 col-xl-4"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">Covered Routes</p><h2 class="stat-value">{{ $stats['routes'] }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-route app-icon"></i></span></div><p class="stat-note">Routes currently linked with fare records.</p></div></div></div>
    </section>

    <section class="card section-card table-card mb-4">
        <div class="card-header">
            <div class="table-toolbar">
                <div>
                    <h3 class="section-title">Fare Records</h3>
                    <p class="section-copy">Complete listing of fare records for all transport routes.</p>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-shell table-wrap">
                <table class="table table-app table-routes align-middle">
                    <thead>
                        <tr>
                            <th>Sr #</th>
                            <th>Route</th>
                            <th>Amount</th>
                            <th>Effective From</th>
                            <th>Effective To</th>
                            <th>Status</th>
                            <th>Remarks</th>
                            @if ($canManageFares)
                                <th class="text-center">Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($fares as $fare)
                            <tr>
                                <td class="text-nowrap">{{ $fares->firstItem() + $loop->index }}</td>
                                <td class="fw-semibold text-nowrap">{{ $fare->route?->starting_point }} to {{ $fare->route?->ending_point }}</td>
                                <td class="text-nowrap">{{ number_format((float) $fare->amount, 2) }}</td>
                                <td class="text-nowrap">{{ $fare->effective_from?->format('Y-m-d') ?: 'N/A' }}</td>
                                <td class="text-nowrap">{{ $fare->effective_to?->format('Y-m-d') ?: 'N/A' }}</td>
                                <td class="text-nowrap">{{ $statuses[$fare->status] ?? ucfirst($fare->status) }}</td>
                                <td>{{ $fare->remarks ?: 'N/A' }}</td>
                                @if ($canManageFares)
                                    <td class="text-center text-nowrap">
                                        <div class="action-stack justify-content-center">
                                            <a href="{{ route('fares.show', $fare) }}" class="action-btn btn-view" title="View Fare">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                            <a href="{{ route('fares.edit', $fare) }}" class="action-btn btn-edit" title="Edit Fare">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <form action="{{ route('fares.destroy', $fare) }}" method="POST" class="d-inline" data-confirm-delete data-delete-message="Are you sure you want to delete this fare for <strong>{{ e(($fare->route?->starting_point ?: 'Route').' to '.($fare->route?->ending_point ?: '')) }}</strong>?">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="action-btn btn-vacate border-0" title="Delete Fare">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr><td colspan="{{ $canManageFares ? 8 : 7 }}" class="text-center text-muted py-4">No fares found yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @include('settings.partials.pagination', ['paginator' => $fares, 'perPage' => $perPage])
        </div>
    </section>
@endsection
