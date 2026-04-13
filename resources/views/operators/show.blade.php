@extends('layouts.app', ['title' => 'Transporter Details | Free Public Transport System', 'pageBadge' => 'Transporter Directory'])

@section('content')
    <div class="page-hero d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
        <div>
            <p class="page-eyebrow">Transporter Directory</p>
            <h1 class="page-title">{{ $operator->name }}</h1>
            <p class="page-subtitle">Detailed view of the selected transporter record.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a class="btn btn-outline-secondary" href="{{ route('transporters.index') }}">Back to Transporters</a>
            @if ($canManageTransporters)
                <a class="btn btn-warning" href="{{ route('transporters.edit', $operator) }}">Edit Transporter</a>
            @endif
        </div>
    </div>

    <section class="row g-4 stats-overlap">
        <div class="col-12">
            <div class="card section-card">
                <div class="card-header">
                    <h3 class="section-title">Transporter Overview</h3>
                    <p class="section-copy">Review owner type, contact details, district information, and payment channels.</p>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="info-tile h-100">
                                <p class="mini-note mb-2">Owner Type</p>
                                <p class="fw-semibold mb-0">{{ $ownerTypes[$operator->owner_type] ?? ucfirst((string) $operator->owner_type) }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-tile h-100">
                                <p class="mini-note mb-2">Name</p>
                                <p class="fw-semibold mb-0">{{ $operator->name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-tile h-100">
                                <p class="mini-note mb-2">CNIC</p>
                                <p class="fw-semibold mb-0">{{ $operator->cnic }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-tile h-100">
                                <p class="mini-note mb-2">Phone Number</p>
                                <p class="fw-semibold mb-0">{{ $operator->phone }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-tile h-100">
                                <p class="mini-note mb-2">District</p>
                                <p class="fw-semibold mb-0">{{ $operator->district?->name ?: 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-tile h-100">
                                <p class="mini-note mb-2">Address</p>
                                <p class="fw-semibold mb-0">{{ $operator->address }}</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="info-tile h-100">
                                <p class="mini-note mb-2">Payment Methods</p>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <p class="mini-note mb-1">EasyPaisa</p>
                                        <p class="fw-semibold mb-0">{{ $operator->easypaisa_no ?: 'Not provided' }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="mini-note mb-1">JazzCash</p>
                                        <p class="fw-semibold mb-0">{{ $operator->jazzcash_no ?: 'Not provided' }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="mini-note mb-1">Bank</p>
                                        <p class="fw-semibold mb-0">{{ $operator->bank_name ?: 'Not provided' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mini-note mb-1">Account Title</p>
                                        <p class="fw-semibold mb-0">{{ $operator->bank_account_title ?: 'Not provided' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mini-note mb-1">Account Number</p>
                                        <p class="fw-semibold mb-0">{{ $operator->bank_account_no ?: 'Not provided' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="info-tile h-100">
                                <p class="mini-note mb-2">Remarks</p>
                                <p class="fw-semibold mb-0">{{ $operator->remarks ?: 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
