@extends('layouts.app', ['title' => 'Fare Details | Free Public Transport System', 'pageBadge' => 'Fare Directory'])

@section('content')
    <div class="page-hero d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
        <div>
            <p class="page-eyebrow">Fare Directory</p>
            <h1 class="page-title">{{ $fare->route?->starting_point }} to {{ $fare->route?->ending_point }}</h1>
            <p class="page-subtitle">Detailed view of the selected fare record.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a class="btn btn-outline-secondary" href="{{ route('fares.index') }}">Back to Fares</a>
            @if ($canManageFares)
                <a class="btn btn-warning" href="{{ route('fares.edit', $fare) }}">Edit Fare</a>
            @endif
        </div>
    </div>

    <section class="row g-4 stats-overlap">
        <div class="col-12">
            <div class="card section-card">
                <div class="card-header">
                    <h3 class="section-title">Fare Overview</h3>
                    <p class="section-copy">Review route, amount, date range, and status details.</p>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="info-tile h-100">
                                <p class="mini-note mb-2">Route</p>
                                <p class="fw-semibold mb-0">{{ $fare->route?->starting_point }} to {{ $fare->route?->ending_point }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-tile h-100">
                                <p class="mini-note mb-2">Amount</p>
                                <p class="fw-semibold mb-0">{{ number_format((float) $fare->amount, 2) }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-tile h-100">
                                <p class="mini-note mb-2">Effective From</p>
                                <p class="fw-semibold mb-0">{{ $fare->effective_from?->format('Y-m-d') ?: 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-tile h-100">
                                <p class="mini-note mb-2">Effective To</p>
                                <p class="fw-semibold mb-0">{{ $fare->effective_to?->format('Y-m-d') ?: 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-tile h-100">
                                <p class="mini-note mb-2">Status</p>
                                <p class="fw-semibold mb-0">{{ $statuses[$fare->status] ?? ucfirst($fare->status) }}</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="info-tile h-100">
                                <p class="mini-note mb-2">Remarks</p>
                                <p class="fw-semibold mb-0">{{ $fare->remarks ?: 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
