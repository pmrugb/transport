@extends('layouts.app', ['title' => 'Add Roles | Free Public Transport System', 'pageBadge' => 'Settings'])

@section('content')
    <div class="page-hero d-flex flex-column flex-lg-row align-items-lg-start justify-content-between gap-4">
        <div>
            <p class="page-eyebrow">Settings</p>
            <h1 class="page-title">{{ $pageTitle }}</h1>
            <p class="page-subtitle">{{ $pageSubtitle }}</p>
        </div>
        <div class="row g-3 w-auto">
            <div class="col-auto"><div class="card stat-card"><div class="card-body py-3 px-4"><p class="stat-label mb-1">Total Roles</p><h2 class="stat-value mb-0" style="font-size: 1.9rem;">{{ $stats['total'] }}</h2></div></div></div>
            <div class="col-auto"><div class="card stat-card"><div class="card-body py-3 px-4"><p class="stat-label mb-1">System Roles</p><h2 class="stat-value mb-0" style="font-size: 1.9rem;">{{ $stats['system'] }}</h2></div></div></div>
            <div class="col-auto"><div class="card stat-card"><div class="card-body py-3 px-4"><p class="stat-label mb-1">Custom Roles</p><h2 class="stat-value mb-0" style="font-size: 1.9rem;">{{ $stats['custom'] }}</h2></div></div></div>
        </div>
    </div>

    <section class="row g-4 stats-overlap">
        <div class="col-12">
            <div class="card section-card">
                <div class="card-header d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                    <div>
                        <h3 class="section-title">Add New Role</h3>
                        <p class="section-copy mb-0">Set role identity, scope, and permissions in one place.</p>
                    </div>
                    <a class="btn btn-success" href="{{ route('settings.roles.index') }}">View All Roles</a>
                </div>
                <div class="card-body">
                    @include('settings.roles.partials.form')
                </div>
            </div>
        </div>
    </section>
@endsection
