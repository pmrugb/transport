@extends('layouts.app', ['title' => 'Grant Details | Free Public Transport System', 'pageBadge' => 'Grant Directory'])

@section('content')
    <div class="page-hero d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
        <div>
            <p class="page-eyebrow">Grant Directory</p>
            <h1 class="page-title">{{ $grant->title }}</h1>
            <p class="page-subtitle">Detailed view of the selected grant record.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a class="btn btn-outline-secondary" href="{{ route('grants.index') }}">Back to Grants</a>
            @if ($canManageGrants)
                <a class="btn btn-warning" href="{{ route('grants.edit', $grant) }}">Edit Grant</a>
            @endif
        </div>
    </div>

    <section class="row g-4 stats-overlap">
        <div class="col-12">
            <div class="card section-card">
                <div class="card-header">
                    <h3 class="section-title">Grant Overview</h3>
                    <p class="section-copy">Review approved amount, financial year, district, and release coverage.</p>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6"><div class="info-tile h-100"><p class="mini-note mb-2">Title</p><p class="fw-semibold mb-0">{{ $grant->title }}</p></div></div>
                        <div class="col-md-6"><div class="info-tile h-100"><p class="mini-note mb-2">Total Amount</p><p class="fw-semibold mb-0">{{ number_format((float) $grant->total_amount, 2) }}</p></div></div>
                        <div class="col-md-6"><div class="info-tile h-100"><p class="mini-note mb-2">Financial Year</p><p class="fw-semibold mb-0">{{ $grant->financial_year }}</p></div></div>
                        <div class="col-md-6"><div class="info-tile h-100"><p class="mini-note mb-2">District</p><p class="fw-semibold mb-0">{{ $grant->district?->name ?: 'N/A' }}</p></div></div>
                        <div class="col-md-6"><div class="info-tile h-100"><p class="mini-note mb-2">Start Date</p><p class="fw-semibold mb-0">{{ $grant->start_date?->format('Y-m-d') ?: 'N/A' }}</p></div></div>
                        <div class="col-md-6"><div class="info-tile h-100"><p class="mini-note mb-2">End Date</p><p class="fw-semibold mb-0">{{ $grant->end_date?->format('Y-m-d') ?: 'N/A' }}</p></div></div>
                        <div class="col-md-6"><div class="info-tile h-100"><p class="mini-note mb-2">Status</p><p class="fw-semibold mb-0">{{ $statuses[$grant->status] ?? ucfirst($grant->status) }}</p></div></div>
                        <div class="col-md-6"><div class="info-tile h-100"><p class="mini-note mb-2">Installments</p><p class="fw-semibold mb-0">{{ $grant->releases->count() }}</p></div></div>
                        <div class="col-12"><div class="info-tile h-100"><p class="mini-note mb-2">Remarks</p><p class="fw-semibold mb-0">{{ $grant->remarks ?: 'N/A' }}</p></div></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
