@extends('layouts.app', ['title' => 'Edit Role | Free Public Transport System', 'pageBadge' => 'Settings'])

@section('content')
    <div class="page-hero d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
        <div>
            <p class="page-eyebrow">Settings</p>
            <h1 class="page-title">{{ $pageTitle }}</h1>
            <p class="page-subtitle">{{ $pageSubtitle }}</p>
        </div>
        <a class="btn btn-outline-secondary" href="{{ route('settings.roles.index') }}">Back to Roles</a>
    </div>

    <section class="row g-4 stats-overlap">
        <div class="col-12">
            <div class="card section-card">
                <div class="card-header">
                    <h3 class="section-title">Role Details</h3>
                    <p class="section-copy">Edit role identity, access scope, and permission settings below.</p>
                </div>
                <div class="card-body">
                    @include('settings.roles.partials.form')
                </div>
            </div>
        </div>
    </section>
@endsection
