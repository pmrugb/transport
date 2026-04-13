@extends('layouts.app', ['title' => 'Add Grant Release | Free Public Transport System', 'pageBadge' => 'Grant Release Registration'])

@section('content')
    <div class="page-hero d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
        <div>
            <p class="page-eyebrow">Grant Release Setup</p>
            <h1 class="page-title">Add Grant Release</h1>
            <p class="page-subtitle">Create a new installment release record for an approved grant.</p>
        </div>
    </div>

    <section class="row g-4 stats-overlap">
        <div class="col-12">
            <div class="card section-card">
                <div class="card-header">
                    <h3 class="section-title">Grant Release Registration</h3>
                    <p class="section-copy">Add a new installment release record to the system.</p>
                </div>
                <div class="card-body">
                    @include('grant-releases.partials.form')
                </div>
            </div>
        </div>
    </section>
@endsection
