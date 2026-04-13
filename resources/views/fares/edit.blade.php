@extends('layouts.app', ['title' => 'Edit Fare | Free Public Transport System', 'pageBadge' => 'Fare Directory'])

@section('content')
    <div class="page-hero d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
        <div>
            <p class="page-eyebrow">Fare Directory</p>
            <h1 class="page-title">Edit Fare</h1>
            <p class="page-subtitle">Update the selected fare record and save the latest pricing details.</p>
        </div>
    </div>

    <section class="row g-4 stats-overlap">
        <div class="col-12">
            <div class="card section-card">
                <div class="card-header">
                    <h3 class="section-title">Fare Details</h3>
                    <p class="section-copy">Adjust fare information below.</p>
                </div>
                <div class="card-body">
                    @include('fares.partials.form')
                </div>
            </div>
        </div>
    </section>
@endsection
