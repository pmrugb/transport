@extends('layouts.app', ['title' => 'Challan Details | Free Public Transport System', 'pageBadge' => 'Challan Management'])

@php
    $challanAttachmentUrl = $challan->challan_image ? route('challans.attachment', $challan) : null;
    $challanAttachmentIsPdf = $challan->challan_image
        && str_ends_with(strtolower($challan->challan_image), '.pdf');
@endphp

@section('content')
    <div class="page-hero d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
        <div>
            <p class="page-eyebrow">Challan Management</p>
            <h1 class="page-title">Challan Details</h1>
            <p class="page-subtitle">Detailed view of the selected challan record.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a class="btn btn-outline-secondary" href="{{ route('challans.index') }}">Back to Challans</a>
            @if ($canManageChallans)
                <a class="btn btn-warning" href="{{ route('challans.edit', $challan) }}">Edit Challan</a>
            @endif
        </div>
    </div>

    <section class="row g-4 stats-overlap">
        <div class="col-lg-8">
            <div class="card section-card h-100">
                <div class="card-header">
                    <h3 class="section-title">Challan Overview</h3>
                    <p class="section-copy">Review challan route, district, date, and notes.</p>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="info-tile h-100">
                                <p class="mini-note mb-2">Challan Date</p>
                                <p class="fw-semibold mb-0">{{ optional($challan->challan_date)->format('d-m-Y') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-tile h-100">
                                <p class="mini-note mb-2">Route</p>
                                <p class="fw-semibold mb-0">{{ $challan->route?->route_name ?: 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-tile h-100">
                                <p class="mini-note mb-2">Starting Point</p>
                                <p class="fw-semibold mb-0">{{ $challan->starting_point }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-tile h-100">
                                <p class="mini-note mb-2">Ending Point</p>
                                <p class="fw-semibold mb-0">{{ $challan->ending_point }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-tile h-100">
                                <p class="mini-note mb-2">District</p>
                                <p class="fw-semibold mb-0">{{ $challan->district?->name ?: 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="info-tile h-100">
                                <p class="mini-note mb-2">Remarks</p>
                                <p class="fw-semibold mb-0">{{ $challan->remarks ?: 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card section-card h-100">
                <div class="card-header">
                    <h3 class="section-title">Challan Image</h3>
                    <p class="section-copy">Preview of the uploaded challan attachment.</p>
                </div>
                <div class="card-body">
                    @if ($challan->challan_image)
                        @if ($challanAttachmentIsPdf)
                            <div class="d-grid gap-3">
                                <div class="ratio ratio-4x3 rounded-4 overflow-hidden border bg-light">
                                    <iframe src="{{ $challanAttachmentUrl }}" title="Challan PDF preview" class="w-100 h-100 border-0"></iframe>
                                </div>
                                <a href="{{ $challanAttachmentUrl }}" target="_blank" rel="noopener" class="btn btn-outline-secondary">
                                    Open PDF
                                </a>
                            </div>
                        @else
                            <a href="{{ $challanAttachmentUrl }}" target="_blank" rel="noopener">
                                <img src="{{ $challanAttachmentUrl }}" alt="Challan image" class="img-fluid rounded-4 border">
                            </a>
                        @endif
                    @else
                        <div class="info-tile h-100">
                            <p class="fw-semibold mb-0">No image uploaded.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
