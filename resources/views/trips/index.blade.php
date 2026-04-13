@extends('layouts.app', ['title' => 'All Trips | Free Public Transport System', 'pageBadge' => 'Trip Management'])

@section('content')
    <div class="page-hero d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
        <div>
            <p class="page-eyebrow">Trip Management</p>
            <h1 class="page-title">All Trips</h1>
            <p class="page-subtitle">Track submitted trips, operational assignments, fare values, and funding linkage in one directory.</p>
        </div>
        <a class="btn btn-success" href="{{ route('trips.create') }}">
            <i class="fa-solid fa-plus me-2"></i>Add Trip
        </a>
    </div>

    <section class="row g-4 stats-overlap">
        <div class="col-sm-6 col-xl-4"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">Total Trips</p><h2 class="stat-value stat-value-compact">{{ $stats['total'] }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-route app-icon"></i></span></div><p class="stat-note">All trip entries recorded in the system.</p></div></div></div>
        <div class="col-sm-6 col-xl-4"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">Today</p><h2 class="stat-value stat-value-compact">{{ $stats['today'] }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-calendar-day app-icon"></i></span></div><p class="stat-note">Trips logged for the current day.</p></div></div></div>
        <div class="col-sm-6 col-xl-4"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">Total Amount</p><h2 class="stat-value stat-value-compact">{{ number_format((float) $stats['amount']) }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-money-check-dollar app-icon"></i></span></div><p class="stat-note">Cumulative amount across recorded trips.</p></div></div></div>
    </section>

    <section class="row g-4 mt-2">
        <div class="col-12">
            <div class="card section-card table-card mb-4">
                <div class="card-header">
                    <div class="table-toolbar">
                        <div>
                            <h3 class="section-title">Trip Records</h3>
                            <p class="section-copy">Complete listing of trip data entered through the trip management form.</p>
                        </div>
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
                                    <th>District</th>
                                    <th>Fare</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    @if ($canManageTrips)
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
                                        <td>{{ $trip->district?->name ?: 'N/A' }}</td>
                                        <td>{{ number_format((float) $trip->fare_amount, 2) }}</td>
                                        <td>{{ number_format((float) $trip->total_amount, 2) }}</td>
                                        <td>{{ $statuses[$trip->status] ?? ucfirst($trip->status) }}</td>
                                        @if ($canManageTrips)
                                            <td class="text-center text-nowrap">
                                                <div class="action-stack justify-content-center">
                                                    @if ($trip->tripCost)
                                                        <a href="{{ route('payments.show', $trip->tripCost) }}" class="action-btn btn-view" title="View Trip Payment">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </a>
                                                    @endif
                                                    <a href="{{ route('trips.edit', $trip) }}" class="action-btn btn-edit" title="Edit Trip">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                    </a>
                                                    <form action="{{ route('trips.destroy', $trip) }}" method="POST" class="d-inline" data-confirm-delete data-delete-message="Are you sure you want to delete this trip record?">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="action-btn btn-vacate border-0" title="Delete Trip">
                                                            <i class="fa-solid fa-trash-can"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr><td colspan="{{ $canManageTrips ? 12 : 11 }}" class="text-center text-muted py-4">No trips found yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @include('settings.partials.pagination', ['paginator' => $trips, 'perPage' => $perPage])
                </div>
            </div>
        </div>
    </section>
@endsection
