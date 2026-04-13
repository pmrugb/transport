@extends('layouts.app', ['title' => 'Edit Trip | Free Public Transport System', 'pageBadge' => 'Trip Management'])

@section('content')
    <div class="page-hero d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
        <div>
            <p class="page-eyebrow">Trip Management</p>
            <h1 class="page-title">Edit Trip</h1>
            <p class="page-subtitle">Review and update the selected trip record.</p>
        </div>
    </div>

    <section class="row g-4 stats-overlap">
        <div class="col-12">
            <div class="card section-card">
                <div class="card-header">
                    <h3 class="section-title">Trip Details</h3>
                    <p class="section-copy">Adjust the trip record and keep linked data aligned.</p>
                </div>
                <div class="card-body">
                    @include('trips.partials.form')
                </div>
            </div>
        </div>
    </section>
@endsection
