@extends('layouts.app', ['title' => 'Deleted Users | Free Public Transport System', 'pageBadge' => 'Logs'])

@section('content')
    <div class="page-hero d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
        <div>
            <p class="page-eyebrow">Logs</p>
            <h1 class="page-title">Deleted Users</h1>
            <p class="page-subtitle">Restore soft-deleted user accounts without editing the database manually.</p>
            @include('logs.partials.subnav')
        </div>
    </div>

    <section class="row g-4 stats-overlap">
        <div class="col-sm-6 col-xl-3"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">Deleted Users</p><h2 class="stat-value">{{ $stats['total'] }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-user-slash app-icon"></i></span></div><p class="stat-note">All soft-deleted user accounts.</p></div></div></div>
        <div class="col-sm-6 col-xl-3"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">Deleted Today</p><h2 class="stat-value">{{ $stats['today'] }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-calendar-day app-icon"></i></span></div><p class="stat-note">Accounts deleted today.</p></div></div></div>
        <div class="col-sm-6 col-xl-3"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">Route Scoped</p><h2 class="stat-value">{{ $stats['route_scoped'] }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-route app-icon"></i></span></div><p class="stat-note">Deleted users with route-scoped roles.</p></div></div></div>
        <div class="col-sm-6 col-xl-3"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">Ready To Restore</p><h2 class="stat-value">{{ $stats['restorable'] }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-rotate-left app-icon"></i></span></div><p class="stat-note">Users whose original email is free.</p></div></div></div>
    </section>

    <section class="card section-card mt-2 mb-4">
        <div class="card-header d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
            <div>
                <h3 class="section-title mb-1">Restore Deleted Users</h3>
                <p class="section-copy mb-0">The restore action clears the deleted flag and puts the original email back if no active account already uses it.</p>
            </div>
            <a class="btn btn-outline-secondary" href="{{ route('logs.deleted-users.index') }}">Reset</a>
        </div>
        <div class="card-body">
            <form method="get" action="{{ route('logs.deleted-users.index') }}" class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label class="form-label fw-semibold" for="search">Search</label>
                    <input class="form-control" id="search" name="search" type="text" placeholder="Search by name or deleted email" value="{{ $search }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold" for="per_page">Rows</label>
                    <select class="form-select" id="per_page" name="per_page">
                        @foreach ([10, 25, 50, 100, 'all'] as $option)
                            <option value="{{ $option }}" @selected((string) $perPage === (string) $option)>{{ $option === 'all' ? 'All' : $option }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button class="btn btn-success flex-fill" type="submit">Filter</button>
                </div>
            </form>
        </div>
    </section>

    <section class="card section-card table-card mb-4">
        <div class="card-body">
            <div class="table-shell table-wrap">
                <table class="table table-app align-middle">
                    <thead>
                        <tr>
                            <th>Sr#</th>
                            <th>Name</th>
                            <th>Deleted Email</th>
                            <th>Original Email</th>
                            <th>Role</th>
                            <th>Deleted At</th>
                            <th>Status</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($deletedUsers as $deletedUser)
                            @php
                                $originalEmail = $deletedUser->softDeletedOriginalEmail();
                                $canRestore = $deletedUser->canRestoreOriginalEmail();
                            @endphp
                            <tr>
                                <td>{{ $deletedUsers->firstItem() + $loop->index }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $deletedUser->name }}</div>
                                    <div class="text-muted small">ID #{{ $deletedUser->id }}</div>
                                </td>
                                <td class="text-break">{{ $deletedUser->email }}</td>
                                <td class="text-break">{{ $originalEmail ?: '-' }}</td>
                                <td>{{ $deletedUser->accessRole?->name ?: $deletedUser->role ?: '-' }}</td>
                                <td class="text-nowrap">{{ $deletedUser->deleted_at?->format('d-m-Y H:i:s') ?: '-' }}</td>
                                <td>
                                    @if ($canRestore)
                                        <span class="badge text-bg-success">Ready</span>
                                    @else
                                        <span class="badge text-bg-warning">Email In Use</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    @if ($canRestore)
                                        <form method="post" action="{{ route('logs.deleted-users.restore', ['user' => $deletedUser->id] + request()->query()) }}" class="d-inline" data-confirm-delete data-delete-message="Restore this deleted user account?">
                                            @csrf
                                            @method('PUT')
                                            <button class="btn btn-sm btn-success" type="submit">
                                                <i class="fa-solid fa-rotate-left me-2"></i>Restore
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted small">Free the original email first.</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">No deleted users found for the current filters.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($deletedUsers->hasPages())
            <div class="card-footer">
                {{ $deletedUsers->links() }}
            </div>
        @endif
    </section>
@endsection
