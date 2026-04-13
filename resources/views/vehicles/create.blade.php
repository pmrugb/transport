@extends('layouts.app', ['title' => 'Add Vehicle | Free Public Transport System', 'pageBadge' => 'Vehicle Directory'])

@section('content')
    <div class="page-hero d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
        <div>
            <p class="page-eyebrow">Vehicle Directory</p>
            <h1 class="page-title">Add Vehicle</h1>
            <p class="page-subtitle">Register a vehicle and link it with transporter, vehicle type, and route details.</p>
        </div>
    </div>

    <section class="row g-4 stats-overlap">
        <div class="col-12">
            <div class="card section-card">
                <div class="card-header">
                    <h3 class="section-title">Vehicle Registration</h3>
                    <p class="section-copy">Add a new vehicle record to the system.</p>
                </div>
                <div class="card-body">
                    @include('vehicles.partials.form')
                </div>
            </div>
        </div>
    </section>
@endsection
