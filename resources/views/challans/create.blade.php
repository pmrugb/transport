@extends('layouts.app', ['title' => 'Add Challan | Free Public Transport System', 'pageBadge' => 'Challan Management'])

@section('content')
    <div class="page-hero d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
        <div>
            <p class="page-eyebrow">Challan Management</p>
            <h1 class="page-title">Add Challan</h1>
            <p class="page-subtitle">Create a challan entry with route, district, image, and remarks details.</p>
        </div>
    </div>

    <section class="row g-4 stats-overlap">
        <div class="col-12">
            <div class="card section-card">
                <div class="card-header">
                    <h3 class="section-title">Challan Registration</h3>
                    <p class="section-copy">Add challan details below and upload the related image if available.</p>
                </div>
                <div class="card-body">
                    @include('challans.partials.form')
                </div>
            </div>
        </div>
    </section>
@endsection
