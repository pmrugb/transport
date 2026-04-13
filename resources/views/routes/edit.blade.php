@extends('layouts.app', ['title' => 'Edit Route | Free Public Transport System', 'pageBadge' => 'Route Directory'])

@section('content')
    <div class="page-hero d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
        <div>
            <p class="page-eyebrow">Route Directory</p>
            <h1 class="page-title">Edit Route</h1>
            <p class="page-subtitle">Update the selected route record and save the latest corridor details.</p>
        </div>
    </div>

    <section class="row g-4 stats-overlap">
        <div class="col-12">
            <div class="card section-card">
                <div class="card-header">
                    <h3 class="section-title">Route Details</h3>
                    <p class="section-copy">Adjust route information below.</p>
                </div>
                <div class="card-body">
                    @include('routes.partials.form')
                </div>
            </div>
        </div>
    </section>
@endsection
