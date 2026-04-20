@extends('layouts.app', ['title' => 'Dashboard | Free Public Transport System', 'pageBadge' => 'Dashboard Overview'])

@section('content')
    <style>
        .dashboard-route-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.95rem;
        }

        .dashboard-route-tile {
            border: 1px solid #dfe7e1;
            border-radius: 1.1rem;
            padding: 1rem 1.05rem;
            background: #fff;
        }

        .dashboard-route-title {
            margin: 0 0 0.25rem;
            font-size: 0.95rem;
            font-weight: 800;
            color: #213246;
        }

        .dashboard-route-copy {
            margin: 0 0 0.75rem;
            color: #6c7b91;
            font-size: 0.8rem;
        }

        .dashboard-route-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .dashboard-route-meta-item {
            display: inline-flex;
            align-items: center;
            gap: 0.38rem;
            padding: 0.34rem 0.6rem;
            border-radius: 999px;
            background: #f4f8f5;
            color: #567265;
            font-size: 0.76rem;
            font-weight: 700;
            line-height: 1;
        }

        .dashboard-badge-soft {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            border-radius: 999px;
            background: #eef4ff;
            color: #4d68d3;
            padding: 0.38rem 0.7rem;
            font-size: 0.76rem;
            font-weight: 700;
        }

        @media (max-width: 767.98px) {
            .dashboard-route-grid {
                grid-template-columns: 1fr;
            }
        }

    </style>

    <div class="page-hero d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
        <div>
            <p class="page-eyebrow">Dashboard</p>
            <h1 class="page-title">{{ $isNatcoDashboard ? 'NATCO Overview' : 'Overview' }}</h1>
            <p class="page-subtitle">
                {{ $isNatcoDashboard
                    ? 'Track all transporter payments, settlement totals, and payment activity from one focused workspace.'
                    : 'View a summary of key information and recent records.' }}
            </p>
        </div>
    </div>

    <section class="row g-4 stats-overlap">
        @if ($isNatcoDashboard)
            <div class="col-sm-6 col-xl-3"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">Total Payments</p><h2 class="stat-value">{{ $stats['totalPayments'] }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-file-invoice-dollar app-icon"></i></span></div><p class="stat-note">All trip cost payment records available in the system.</p></div></div></div>
            <div class="col-sm-6 col-xl-3"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">Due Payments</p><h2 class="stat-value">{{ $stats['duePayments'] }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-hourglass-half app-icon"></i></span></div><p class="stat-note">Payments waiting for approval or settlement.</p></div></div></div>
            <div class="col-sm-6 col-xl-3"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">Paid Amount</p><h2 class="stat-value stat-value-compact">{{ number_format((float) $stats['paidAmount'], 0) }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-money-check-dollar app-icon"></i></span></div><p class="stat-note">Amount already paid to transporters.</p></div></div></div>
            <div class="col-sm-6 col-xl-3"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">Amount Left</p><h2 class="stat-value stat-value-compact">{{ number_format((float) $stats['amountLeft'], 0) }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-wallet app-icon"></i></span></div><p class="stat-note">Amount still pending in due payments.</p></div></div></div>
        @else
            <div class="col-sm-6 col-xl-4"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">Total Operators</p><h2 class="stat-value">{{ $stats['totalOperators'] }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-users app-icon"></i></span></div><p class="stat-note">All transporters currently registered in the system.</p></div></div></div>
            <div class="col-sm-6 col-xl-4"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">Total Routes</p><h2 class="stat-value">{{ $stats['totalRoutes'] }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-route app-icon"></i></span></div><p class="stat-note">Active route records available for operations.</p></div></div></div>
            <div class="col-sm-6 col-xl-4"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">Covered Districts</p><h2 class="stat-value">{{ $stats['pendingChecks'] }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-map-location-dot app-icon"></i></span></div><p class="stat-note">Districts currently linked with operator records.</p></div></div></div>
        @endif
    </section>

    @if ($isNatcoDashboard)
        <section class="card section-card mt-2 mb-4">
            <div class="card-header">
                <h3 class="section-title">Payment Overview</h3>
                <p class="section-copy">Complete payment position across due, paid, hold, rejected, and today&apos;s activity.</p>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4 col-sm-6">
                        <div class="info-tile h-100">
                            <p class="mini-note mb-2">Due Amount</p>
                            <p class="fw-semibold mb-1">{{ number_format((float) $stats['dueAmount'], 2) }}</p>
                            <p class="text-muted small mb-0">Outstanding payment value still pending settlement.</p>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="info-tile h-100">
                            <p class="mini-note mb-2">Paid Payments</p>
                            <p class="fw-semibold mb-1">{{ $stats['paidPayments'] }}</p>
                            <p class="text-muted small mb-0">Payments already completed for transporters.</p>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="info-tile h-100">
                            <p class="mini-note mb-2">On Hold</p>
                            <p class="fw-semibold mb-1">{{ $stats['onHoldPayments'] }}</p>
                            <p class="text-muted small mb-0">Payments temporarily paused for review or clarification.</p>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="info-tile h-100">
                            <p class="mini-note mb-2">Rejected</p>
                            <p class="fw-semibold mb-1">{{ $stats['rejectedPayments'] }}</p>
                            <p class="text-muted small mb-0">Records reviewed and not approved for settlement.</p>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="info-tile h-100">
                            <p class="mini-note mb-2">Today's Payment Count</p>
                            <p class="fw-semibold mb-1">{{ $stats['todayPayments'] }}</p>
                            <p class="text-muted small mb-0">Payments created or calculated today.</p>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="info-tile h-100">
                            <p class="mini-note mb-2">Today's Payment Amount</p>
                            <p class="fw-semibold mb-1">{{ number_format((float) $stats['todayAmount'], 2) }}</p>
                            <p class="text-muted small mb-0">Total value from today&apos;s payment activity.</p>
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-wrap gap-2 pt-3">
                    <a class="btn btn-outline-secondary" href="{{ route('payments.due') }}">Due Payments</a>
                    <a class="btn btn-outline-secondary" href="{{ route('payments.paid') }}">Paid Payments</a>
                    <a class="btn btn-success" href="{{ route('payments.index') }}">Open Payments</a>
                </div>
            </div>
        </section>

        <section class="card section-card mb-4">
            <div class="card-header">
                <h3 class="section-title">Routes</h3>
                <p class="section-copy">Review route-wise paid and unpaid payment totals across the dashboard.</p>
            </div>
            <div class="card-body">
                <div class="dashboard-route-grid">
                    @forelse ($routePaymentSummary as $route)
                        <div class="dashboard-route-tile">
                            <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                <p class="dashboard-route-title">{{ $route->route_name }}</p>
                                <span class="dashboard-badge-soft">{{ $route->district?->name ?: 'District' }}</span>
                            </div>
                            <p class="dashboard-route-copy">{{ $route->starting_point }} to {{ $route->ending_point }}</p>
                            <div class="dashboard-route-meta mb-3">
                                <span class="dashboard-route-meta-item"><i class="fa-solid fa-clock app-icon"></i> {{ $route->timing ?: 'N/A' }}</span>
                                <span class="dashboard-route-meta-item"><i class="fa-solid fa-ruler-horizontal app-icon"></i> {{ $route->total_distance }} km</span>
                                <span class="dashboard-route-meta-item"><i class="fa-solid fa-file-invoice-dollar app-icon"></i> {{ $route->payment_count }} {{ (int) $route->payment_count === 1 ? 'payment' : 'payments' }}</span>
                            </div>
                            <div class="row g-2">
                                <div class="col-sm-6">
                                    <div class="info-tile h-100">
                                        <p class="mini-note mb-2">Paid</p>
                                        <p class="fw-semibold mb-0">{{ number_format((float) $route->paid_amount, 2) }}</p>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="info-tile h-100">
                                        <p class="mini-note mb-2">Unpaid</p>
                                        <p class="fw-semibold mb-0">{{ number_format((float) $route->unpaid_amount, 2) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No routes found yet.</p>
                    @endforelse
                </div>
            </div>
        </section>

        <section class="card section-card table-card mb-4">
            <div class="card-header">
                <div class="table-toolbar">
                    <div>
                        <h3 class="section-title">Recent Payment Records</h3>
                        <p class="section-copy">Latest payment entries with settlement status and amount details.</p>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-shell table-wrap">
                    <table class="table table-app align-middle">
                        <thead>
                            <tr>
                                <th>Sr #</th>
                                <th>Date</th>
                                <th>Transporter</th>
                                <th>Vehicle</th>
                                <th>Route</th>
                                <th>District</th>
                                <th>Trips</th>
                                <th>Total</th>
                                <th>Status</th>
                                @if ($canManagePayments)
                                    <th class="text-center">Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentPayments as $payment)
                                <tr>
                                    <td>{{ $recentPayments->firstItem() + $loop->index }}</td>
                                    <td>{{ $payment->calculation_date?->format('Y-m-d') ?: ($payment->created_at?->format('Y-m-d') ?: 'N/A') }}</td>
                                    <td>{{ $payment->transporter?->name ?: 'N/A' }}</td>
                                    <td>{{ $payment->vehicle?->registration_no ?: 'N/A' }}</td>
                                    <td>{{ $payment->route?->route_name ?: 'N/A' }}</td>
                                    <td>{{ $payment->trip?->district?->name ?: 'N/A' }}</td>
                                    <td>{{ $payment->no_of_trips }}</td>
                                    <td>{{ number_format((float) $payment->total_amount, 2) }}</td>
                                    <td>{{ $paymentStatuses[$payment->status] ?? ucfirst((string) $payment->status) }}</td>
                                    @if ($canManagePayments)
                                        <td class="text-center text-nowrap">
                                            <div class="action-stack justify-content-center">
                                                <a href="{{ route('payments.show', $payment) }}" class="action-btn btn-view" title="View Payment">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr><td colspan="{{ $canManagePayments ? 10 : 9 }}" class="text-center text-muted py-4">No payment records found yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @include('settings.partials.pagination', ['paginator' => $recentPayments, 'perPage' => $perPage])
            </div>
        </section>
    @else
        <section class="card section-card table-card mt-2 mb-4">
            <div class="card-header">
                <div class="table-toolbar">
                    <div>
                        <h3 class="section-title">Recent Trip Records</h3>
                        <p class="section-copy">Latest trip records entered through trip management.</p>
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
                                @if ($canEditTrips || $canDeleteTrips)
                                    <th class="text-center">Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentTrips as $trip)
                                <tr>
                                    <td>{{ $recentTrips->firstItem() + $loop->index }}</td>
                                    <td>{{ $trip->vehicle?->registration_no ?: 'N/A' }}</td>
                                    <td>{{ $trip->route?->route_name ?: 'N/A' }}</td>
                                    <td>{{ $trip->transporter?->name ?: 'N/A' }}</td>
                                    <td>{{ $trip->driver_name }}<br><span class="text-muted small">{{ $trip->driver_mobile }}</span></td>
                                    <td>{{ $trip->district?->name ?: 'N/A' }}</td>
                                    <td>{{ number_format((float) $trip->fare_amount, 2) }}</td>
                                    <td>{{ number_format((float) $trip->total_amount, 2) }}</td>
                                    <td>{{ $tripStatuses[$trip->status] ?? ucfirst($trip->status) }}</td>
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
                                <tr><td colspan="{{ ($canEditTrips || $canDeleteTrips) ? 10 : 9 }}" class="text-center text-muted py-4">No trips found yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @include('settings.partials.pagination', ['paginator' => $recentTrips, 'perPage' => $perPage])
            </div>
        </section>

        <section class="card section-card mb-4">
            <div class="card-header">
                <h3 class="section-title">Routes</h3>
                <p class="section-copy">Quick look at the latest route cards across the portal.</p>
            </div>
            <div class="card-body">
                <div class="dashboard-route-grid">
                    @forelse ($routeSnapshot as $route)
                        <div class="dashboard-route-tile">
                            <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                <p class="dashboard-route-title">{{ $route->route_name }}</p>
                                <span class="dashboard-badge-soft">{{ $route->district?->name ?: 'District' }}</span>
                            </div>
                            <p class="dashboard-route-copy">{{ $route->starting_point }} to {{ $route->ending_point }}</p>
                            <div class="dashboard-route-meta">
                                <span class="dashboard-route-meta-item"><i class="fa-solid fa-clock app-icon"></i> {{ $route->timing }}</span>
                                <span class="dashboard-route-meta-item"><i class="fa-solid fa-ruler-horizontal app-icon"></i> {{ $route->total_distance }} km</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No routes found yet.</p>
                    @endforelse
                </div>
            </div>
        </section>
    @endif
@endsection
