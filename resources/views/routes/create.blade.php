@extends('layouts.app', ['title' => 'Add New Route | Free Public Transport System', 'pageBadge' => 'Route Registration'])

@section('content')
    <div class="page-hero d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
        <div>
            <p class="page-eyebrow">Route Setup</p>
            <h1 class="page-title">Add New Route</h1>
            <p class="page-subtitle">Create a route entry with district coverage and corridor details.</p>
        </div>
    </div>

    <section class="row g-4 stats-overlap">
        <div class="col-12">
            <div class="card section-card" id="route-form">
                <div class="card-header">
                    <h3 class="section-title">Route Registration</h3>
                    <p class="section-copy">Add a new route with starting point, ending point, timing, distance, and remarks.</p>
                </div>
                <div class="card-body">
                    @include('routes.partials.form')
                </div>
            </div>
        </div>
    </section>
@endsection
