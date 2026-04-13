@extends('layouts.app', ['title' => 'Grant Release Details | Free Public Transport System', 'pageBadge' => 'Grant Release Directory'])

@section('content')
    <div class="page-hero d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
        <div>
            <p class="page-eyebrow">Grant Release Directory</p>
            <h1 class="page-title">Installment #{{ $grantRelease->installment_no }}</h1>
            <p class="page-subtitle">Detailed view of the selected grant installment release record.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a class="btn btn-outline-secondary" href="{{ route('grant-releases.index') }}">Back to Grant Releases</a>
            @if ($canManageGrantReleases)
                <a class="btn btn-warning" href="{{ route('grant-releases.edit', $grantRelease) }}">Edit Grant Release</a>
            @endif
        </div>
    </div>

    <section class="row g-4 stats-overlap">
        <div class="col-12">
            <div class="card section-card">
                <div class="card-header">
                    <h3 class="section-title">Grant Release Overview</h3>
                    <p class="section-copy">Review installment amount, grant, date, and release details.</p>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6"><div class="info-tile h-100"><p class="mini-note mb-2">Grant</p><p class="fw-semibold mb-0">{{ $grantRelease->grant?->title ?: 'N/A' }}</p></div></div>
                        <div class="col-md-6"><div class="info-tile h-100"><p class="mini-note mb-2">Release Amount</p><p class="fw-semibold mb-0">{{ number_format((float) $grantRelease->release_amount, 2) }}</p></div></div>
                        <div class="col-md-6"><div class="info-tile h-100"><p class="mini-note mb-2">Release Date</p><p class="fw-semibold mb-0">{{ $grantRelease->release_date?->format('Y-m-d') ?: 'N/A' }}</p></div></div>
                        <div class="col-md-6"><div class="info-tile h-100"><p class="mini-note mb-2">Installment No</p><p class="fw-semibold mb-0">{{ $grantRelease->installment_no }}</p></div></div>
                        <div class="col-md-6"><div class="info-tile h-100"><p class="mini-note mb-2">Released By</p><p class="fw-semibold mb-0">{{ $grantRelease->released_by ?: 'N/A' }}</p></div></div>
                        <div class="col-12"><div class="info-tile h-100"><p class="mini-note mb-2">Remarks</p><p class="fw-semibold mb-0">{{ $grantRelease->remarks ?: 'N/A' }}</p></div></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
