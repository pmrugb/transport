@extends('layouts.app', ['title' => 'Add Fare | Free Public Transport System', 'pageBadge' => 'Fare Registration'])

@section('content')
    <div class="page-hero d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
        <div>
            <p class="page-eyebrow">Fare Setup</p>
            <h1 class="page-title">Add Fare</h1>
            <p class="page-subtitle">Create a fare record and assign it to a transport route with effective dates.</p>
        </div>
    </div>

    <section class="row g-4 stats-overlap">
        <div class="col-12">
            <div class="card section-card">
                <div class="card-header">
                    <h3 class="section-title">Fare Registration</h3>
                    <p class="section-copy">Add a new fare record to the system.</p>
                </div>
                <div class="card-body">
                    @include('fares.partials.form')
                </div>
            </div>
        </div>
    </section>
@endsection
