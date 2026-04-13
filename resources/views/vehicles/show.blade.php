@extends('layouts.app', ['title' => 'Vehicle Details | Free Public Transport System', 'pageBadge' => 'Vehicle Directory'])

@section('content')
    <div class="page-hero d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
        <div>
            <p class="page-eyebrow">Vehicle Directory</p>
            <h1 class="page-title">{{ $vehicle->registration_no }}</h1>
            <p class="page-subtitle">Detailed view of the selected vehicle record.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a class="btn btn-outline-secondary" href="{{ route('vehicles.index') }}">Back to Vehicles</a>
            @if ($canManageVehicles)
                <a class="btn btn-warning" href="{{ route('vehicles.edit', $vehicle) }}">Edit Vehicle</a>
            @endif
        </div>
    </div>

    <section class="row g-4 stats-overlap">
        <div class="col-12">
            <div class="card section-card">
                <div class="card-header">
                    <h3 class="section-title">Vehicle Overview</h3>
                    <p class="section-copy">Review the selected vehicle information.</p>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="info-tile h-100">
                                <p class="mini-note mb-2">Transporter</p>
                                <p class="fw-semibold mb-0">{{ $vehicle->transporter?->name ?: 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-tile h-100">
                                <p class="mini-note mb-2">Vehicle Type</p>
                                <p class="fw-semibold mb-0">{{ $vehicle->vehicleType?->name ?: 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-tile h-100">
                                <p class="mini-note mb-2">Registration No</p>
                                <p class="fw-semibold mb-0">{{ $vehicle->registration_no }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-tile h-100">
                                <p class="mini-note mb-2">Chassis No</p>
                                <p class="fw-semibold mb-0">{{ $vehicle->chassis_no }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-tile h-100">
                                <p class="mini-note mb-2">Route</p>
                                <p class="fw-semibold mb-0">{{ $vehicle->route?->route_name ?: 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-tile h-100">
                                <p class="mini-note mb-2">From</p>
                                <p class="fw-semibold mb-0">{{ $vehicle->route?->starting_point ?: 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-tile h-100">
                                <p class="mini-note mb-2">To</p>
                                <p class="fw-semibold mb-0">{{ $vehicle->route?->ending_point ?: 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-tile h-100">
                                <p class="mini-note mb-2">Status</p>
                                <p class="fw-semibold mb-0">{{ $statuses[$vehicle->status] ?? ucfirst($vehicle->status) }}</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="info-tile h-100">
                                <p class="mini-note mb-2">Remarks</p>
                                <p class="fw-semibold mb-0">{{ $vehicle->remarks ?: 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
