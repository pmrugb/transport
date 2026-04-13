@extends('layouts.app', ['title' => 'Edit Grant Release | Free Public Transport System', 'pageBadge' => 'Grant Release Directory'])

@section('content')
    <div class="page-hero d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
        <div>
            <p class="page-eyebrow">Grant Release Directory</p>
            <h1 class="page-title">Edit Grant Release</h1>
            <p class="page-subtitle">Update the selected installment release record.</p>
        </div>
    </div>

    <section class="row g-4 stats-overlap">
        <div class="col-12">
            <div class="card section-card">
                <div class="card-header">
                    <h3 class="section-title">Grant Release Details</h3>
                    <p class="section-copy">Adjust release information below.</p>
                </div>
                <div class="card-body">
                    @include('grant-releases.partials.form')
                </div>
            </div>
        </div>
    </section>
@endsection
