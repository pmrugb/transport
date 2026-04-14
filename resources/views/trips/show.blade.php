@extends('layouts.app', ['title' => 'Trip Details | Free Public Transport System', 'pageBadge' => 'Trip Management'])

@section('content')
    <div class="page-hero d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
        <div>
            <p class="page-eyebrow">Trip Management</p>
            <h1 class="page-title">Trip #{{ $trip->id }}</h1>
            <p class="page-subtitle">Detailed view of the selected trip record.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a class="btn btn-outline-secondary" href="{{ route('trips.index') }}">Back to Trips</a>
            @if ($trip->tripCost)
                <a class="btn btn-outline-success" href="{{ route('payments.show', $trip->tripCost) }}">View Payment</a>
            @endif
            @if ($canManageTrips)
                <a class="btn btn-warning" href="{{ route('trips.edit', $trip) }}">Edit Trip</a>
            @endif
        </div>
    </div>

    <section class="row g-4 stats-overlap">
        <div class="col-12">
            <div class="card section-card">
                <div class="card-header">
                    <h3 class="section-title">Trip Overview</h3>
                    <p class="section-copy">Review the selected trip information.</p>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="info-tile h-100">
                                <p class="mini-note mb-2">Trip Date</p>
                                <p class="fw-semibold mb-0">{{ optional($trip->trip_date)->format('d-m-Y') ?: 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-tile h-100">
                                <p class="mini-note mb-2">Status</p>
                                <p class="fw-semibold mb-0">{{ $statuses[$trip->status] ?? ucfirst((string) $trip->status) }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-tile h-100">
                                <p class="mini-note mb-2">Vehicle</p>
                                <p class="fw-semibold mb-0">{{ $trip->vehicle?->registration_no ?: 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-tile h-100">
                                <p class="mini-note mb-2">Transporter</p>
                                <p class="fw-semibold mb-0">{{ $trip->transporter?->name ?: 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-tile h-100">
                                <p class="mini-note mb-2">Driver Name</p>
                                <p class="fw-semibold mb-0">{{ $trip->driver_name ?: 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-tile h-100">
                                <p class="mini-note mb-2">Driver CNIC</p>
                                <p class="fw-semibold mb-0">{{ $trip->driver_cnic ?: 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-tile h-100">
                                <p class="mini-note mb-2">Driver Mobile</p>
                                <p class="fw-semibold mb-0">{{ $trip->driver_mobile ?: 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-tile h-100">
                                <p class="mini-note mb-2">District</p>
                                <p class="fw-semibold mb-0">{{ $trip->district?->name ?: 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-tile h-100">
                                <p class="mini-note mb-2">Route</p>
                                <p class="fw-semibold mb-0">{{ $trip->route?->route_name ?: 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-tile h-100">
                                <p class="mini-note mb-2">From</p>
                                <p class="fw-semibold mb-0">{{ $trip->route?->starting_point ?: 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-tile h-100">
                                <p class="mini-note mb-2">To</p>
                                <p class="fw-semibold mb-0">{{ $trip->route?->ending_point ?: 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-tile h-100">
                                <p class="mini-note mb-2">No. of Trips</p>
                                <p class="fw-semibold mb-0">{{ $trip->no_of_trips }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-tile h-100">
                                <p class="mini-note mb-2">Fare Amount</p>
                                <p class="fw-semibold mb-0">{{ number_format((float) $trip->fare_amount, 2) }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-tile h-100">
                                <p class="mini-note mb-2">Total Amount</p>
                                <p class="fw-semibold mb-0">{{ number_format((float) $trip->total_amount, 2) }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-tile h-100">
                                <p class="mini-note mb-2">Created By</p>
                                <p class="fw-semibold mb-0">{{ $trip->creator?->name ?: 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="info-tile h-100">
                                <p class="mini-note mb-2">Remarks</p>
                                <p class="fw-semibold mb-0">{{ $trip->remarks ?: 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
