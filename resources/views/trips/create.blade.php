@extends('layouts.app', ['title' => 'Add Trip | Free Public Transport System', 'pageBadge' => 'Trip Management'])

@section('content')
    <div class="page-hero d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
        <div>
            <p class="page-eyebrow">Trip Management</p>
            <h1 class="page-title">Add Trip</h1>
            <p class="page-subtitle">Capture trip activity with linked vehicle, route, fare, district, and grant details through one guided entry form.</p>
        </div>
    </div>

    <section class="row g-4 stats-overlap">
        <div class="col-12">
            <div class="card section-card">
                <div class="card-header">
                    <h3 class="section-title">Trip Entry Form</h3>
                    <p class="section-copy">Select a vehicle first to auto-fill route, transporter, district, fare, and total amount for faster entry.</p>
                </div>
                <div class="card-body">
                    @include('trips.partials.form')
                </div>
            </div>
        </div>
    </section>
@endsection
