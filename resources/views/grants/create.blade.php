@extends('layouts.app', ['title' => 'Add Grant | Free Public Transport System', 'pageBadge' => 'Grant Registration'])

@section('content')
    <div class="page-hero d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
        <div>
            <p class="page-eyebrow">Grant Setup</p>
            <h1 class="page-title">Add Grant</h1>
            <p class="page-subtitle">Create a main budget grant record with financial year and district details.</p>
        </div>
    </div>

    <section class="row g-4 stats-overlap">
        <div class="col-12">
            <div class="card section-card">
                <div class="card-header">
                    <h3 class="section-title">Grant Registration</h3>
                    <p class="section-copy">Add a new approved grant record to the system.</p>
                </div>
                <div class="card-body">
                    @include('grants.partials.form')
                </div>
            </div>
        </div>
    </section>
@endsection
