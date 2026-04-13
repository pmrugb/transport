@extends('layouts.app', ['title' => 'Route Details | Free Public Transport System', 'pageBadge' => 'Route Directory'])

@section('content')
    <div class="page-hero d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
        <div>
            <p class="page-eyebrow">Route Directory</p>
            <h1 class="page-title">{{ $transportRoute->route_name }}</h1>
            <p class="page-subtitle">Detailed view of the selected route record.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a class="btn btn-outline-secondary" href="{{ route('routes.index') }}">Back to Routes</a>
            @if ($canManageRoutes)
                <a class="btn btn-warning" href="{{ route('routes.edit', $transportRoute) }}">Edit Route</a>
            @endif
        </div>
    </div>

    <section class="row g-4 stats-overlap">
        <div class="col-12">
            <div class="card section-card">
                <div class="card-header">
                    <h3 class="section-title">Route Overview</h3>
                    <p class="section-copy">Review corridor timing, district, and distance details.</p>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="info-tile h-100">
                                <p class="mini-note mb-2">Route Name</p>
                                <p class="fw-semibold mb-0">{{ $transportRoute->route_name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-tile h-100">
                                <p class="mini-note mb-2">Route Code</p>
                                <p class="fw-semibold mb-0">{{ $transportRoute->route_code }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-tile h-100">
                                <p class="mini-note mb-2">District</p>
                                <p class="fw-semibold mb-0">{{ $transportRoute->district?->name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-tile h-100">
                                <p class="mini-note mb-2">Starting Point</p>
                                <p class="fw-semibold mb-0">{{ $transportRoute->starting_point }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-tile h-100">
                                <p class="mini-note mb-2">Ending Point</p>
                                <p class="fw-semibold mb-0">{{ $transportRoute->ending_point }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-tile h-100">
                                <p class="mini-note mb-2">Timing</p>
                                <p class="fw-semibold mb-0">{{ $transportRoute->timing }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-tile h-100">
                                <p class="mini-note mb-2">Total Distance</p>
                                <p class="fw-semibold mb-0">{{ $transportRoute->total_distance }} km</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="info-tile h-100">
                                <p class="mini-note mb-2">Remarks</p>
                                <p class="fw-semibold mb-0">{{ $transportRoute->remarks ?: 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
