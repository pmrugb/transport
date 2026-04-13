@extends('layouts.app', ['title' => 'Create User | Free Public Transport System', 'pageBadge' => 'User Management'])

@section('content')
    <div class="page-hero d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
        <div>
            <p class="page-eyebrow">User Management</p>
            <h1 class="page-title">Create User</h1>
            <p class="page-subtitle">Add a new system user with the right role and access scope.</p>
        </div>
    </div>

    <section class="row g-4 stats-overlap">
        <div class="col-12">
            <div class="card section-card">
                <div class="card-header">
                    <h3 class="section-title">User Details</h3>
                    <p class="section-copy">Create a user account with role, optional district or division scope, and password.</p>
                </div>
                <div class="card-body">
                    @include('users.partials.form')
                </div>
            </div>
        </div>
    </section>
@endsection
