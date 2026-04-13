@extends('layouts.app', ['title' => 'Edit Grant | Free Public Transport System', 'pageBadge' => 'Grant Directory'])

@section('content')
    <div class="page-hero d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
        <div>
            <p class="page-eyebrow">Grant Directory</p>
            <h1 class="page-title">Edit Grant</h1>
            <p class="page-subtitle">Update the selected grant record and save the latest budget details.</p>
        </div>
    </div>

    <section class="row g-4 stats-overlap">
        <div class="col-12">
            <div class="card section-card">
                <div class="card-header">
                    <h3 class="section-title">Grant Details</h3>
                    <p class="section-copy">Adjust grant information below.</p>
                </div>
                <div class="card-body">
                    @include('grants.partials.form')
                </div>
            </div>
        </div>
    </section>
@endsection
