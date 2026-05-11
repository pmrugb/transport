@extends('layouts.app', ['title' => 'All Users | Free Public Transport System', 'pageBadge' => 'User Management'])

@section('content')
    <style>
        .user-password-btn {
            width: 38px;
            height: 38px;
            border-radius: 0.85rem;
            border: 1.5px solid #f2c14d;
            background: #fffdf7;
            color: #f0b429;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.78rem;
            box-shadow: 0 4px 12px rgba(240, 180, 41, 0.08);
        }

        .user-password-btn:hover,
        .user-password-btn:focus {
            color: #d89f1f;
            border-color: #e3b23c;
            background: #fffaf0;
        }

        .user-action-stack {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            flex-wrap: wrap;
            justify-content: center;
        }

        .user-pill-btn {
            min-width: 68px;
            min-height: 36px;
            border: 0;
            border-radius: 0.85rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.45rem 0.75rem;
            font-size: 0.72rem;
            font-weight: 800;
            line-height: 1;
            text-decoration: none;
            box-shadow: 0 6px 14px rgba(25, 39, 52, 0.07);
        }

        .user-pill-btn-success {
            background: #4b9566;
            color: #fff;
        }

        .user-pill-btn-success:hover,
        .user-pill-btn-success:focus {
            background: #3f8157;
            color: #fff;
        }

        .user-pill-btn-danger {
            background: #d84c4c;
            color: #fff;
        }

        .user-pill-btn-danger:hover,
        .user-pill-btn-danger:focus {
            background: #bf3f3f;
            color: #fff;
        }

        .user-password-modal .modal-dialog {
            max-width: 520px;
        }

        .user-password-modal .modal-content {
            border: 0;
            border-radius: 1.4rem;
            overflow: hidden;
            box-shadow: 0 20px 48px rgba(25, 39, 52, 0.16);
        }

        .user-password-modal .modal-header,
        .user-password-modal .modal-footer {
            padding: 0.95rem 1.15rem;
        }

        .user-password-modal .modal-body {
            padding: 1rem 1.15rem 0.9rem;
        }

        .user-password-modal .modal-title {
            font-size: 0.98rem;
            font-weight: 800;
            color: #243245;
        }

        .user-password-modal .btn-close {
            font-size: 0.9rem;
        }

        .user-password-copy {
            color: #627089;
            font-size: 0.82rem;
            margin-bottom: 0.85rem;
        }

        .user-password-modal .form-label {
            font-size: 0.86rem;
            font-weight: 800;
            color: #3b465c;
            margin-bottom: 0.4rem;
        }

        .user-password-modal .form-control {
            min-height: 46px;
            border: 1px solid #d8e2ef;
            border-radius: 1rem;
            font-size: 0.92rem;
            padding: 0.7rem 0.95rem;
        }

        .user-password-modal .password-toggle-btn {
            width: 42px;
            min-width: 42px;
            border: 1px solid #d8e2ef;
            border-left: 0;
            border-radius: 0 1rem 1rem 0;
            background: #fff;
            color: #5a6479;
        }

        .user-password-modal .password-input-group .form-control {
            border-radius: 1rem 0 0 1rem;
        }

        .user-password-modal .modal-footer {
            border-top: 1px solid #e6edf3;
            gap: 0.6rem;
        }

        .user-password-modal .modal-footer .btn {
            min-width: 118px;
            min-height: 44px;
            border-radius: 1rem;
            font-size: 0.88rem;
            font-weight: 800;
        }

        .users-table-compact th,
        .users-table-compact td {
            padding-top: 0.65rem;
            padding-bottom: 0.65rem;
            white-space: nowrap;
            vertical-align: middle;
        }

        .users-table-compact thead th:nth-child(9),
        .users-table-compact tbody td:nth-child(9) {
            position: sticky;
            right: 0;
            z-index: 3;
            background: #fff;
        }

        .users-table-compact thead th:nth-child(9) {
            z-index: 4;
            background: #f8fafc;
        }

        .users-table-compact th {
            font-size: 0.84rem;
        }

        .users-table-compact td {
            font-size: 0.9rem;
        }

        .users-table-compact td:nth-child(2) {
            white-space: normal;
            min-width: 120px;
        }

        .users-table-compact td:nth-child(3) {
            min-width: 180px;
        }

        .users-table-compact .form-select {
            min-width: 115px;
            min-height: 38px;
            padding: 0.42rem 1.85rem 0.42rem 0.7rem;
            border-radius: 0.85rem;
            font-size: 0.82rem;
            white-space: nowrap;
        }

        .users-table-compact td:nth-child(5) .form-select,
        .users-table-compact td:nth-child(6) .form-select {
            min-width: 110px;
        }

        .users-table-compact td:nth-child(4) .form-select {
            min-width: 125px;
        }

        .users-table-compact td:nth-child(7) {
            min-width: 210px;
        }

        .users-route-summary {
            max-width: 190px;
            white-space: normal;
            line-height: 1.35;
        }

        .user-route-manage-btn {
            min-width: 128px;
            min-height: 34px;
            border: 1px solid #d8e2ef;
            border-radius: 0.85rem;
            background: #f8fafc;
            color: #243245;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .user-route-manage-btn:hover,
        .user-route-manage-btn:focus {
            background: #eef4fb;
            color: #1d2b3d;
        }

        .user-route-modal .modal-dialog {
            max-width: 760px;
        }

        .user-route-modal .modal-content {
            border: 0;
            border-radius: 1.35rem;
            overflow: hidden;
            box-shadow: 0 20px 48px rgba(25, 39, 52, 0.16);
        }

        .user-route-modal .modal-header,
        .user-route-modal .modal-footer {
            padding: 0.95rem 1.15rem;
        }

        .user-route-modal .modal-body {
            padding: 1rem 1.15rem;
        }

        .user-route-modal .modal-title {
            font-size: 0.98rem;
            font-weight: 800;
            color: #243245;
        }

        .user-route-picker-shell {
            display: grid;
            gap: 0.9rem;
        }

        .user-route-selected-box {
            min-height: 74px;
            padding: 0.85rem 0.95rem;
            border: 1px solid #d8e2ef;
            border-radius: 1rem;
            background: linear-gradient(180deg, #fcfdff 0%, #f5f8fc 100%);
        }

        .user-route-selected-label {
            display: block;
            margin-bottom: 0.45rem;
            color: #5f6c82;
            font-size: 0.76rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .user-route-selected-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 0.55rem;
        }

        .user-route-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.48rem 0.7rem;
            border-radius: 999px;
            background: #4b9566;
            color: #fff;
            font-size: 0.82rem;
            font-weight: 700;
            line-height: 1.2;
        }

        .user-route-chip-remove {
            width: 22px;
            height: 22px;
            border: 0;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.18);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
        }

        .user-route-chip-remove:hover,
        .user-route-chip-remove:focus {
            background: rgba(255, 255, 255, 0.28);
            color: #fff;
        }

        .user-route-empty {
            color: #6d7a90;
            font-size: 0.86rem;
        }

        .user-route-search {
            border-radius: 0.95rem;
        }

        .user-route-options {
            max-height: 320px;
            overflow-y: auto;
            display: grid;
            gap: 0.7rem;
            padding-right: 0.15rem;
        }

        .user-route-option {
            width: 100%;
            padding: 0.95rem 1rem;
            border: 1px solid #d8e2ef;
            border-radius: 1rem;
            background: #fff;
            text-align: left;
            transition: border-color 0.18s ease, box-shadow 0.18s ease, transform 0.18s ease, background 0.18s ease;
        }

        .user-route-option:hover,
        .user-route-option:focus {
            border-color: #adc4e6;
            background: #f8fbff;
            box-shadow: 0 10px 20px rgba(48, 88, 140, 0.08);
            transform: translateY(-1px);
        }

        .user-route-option.is-selected {
            border-color: #4b9566;
            background: linear-gradient(180deg, #eff8f2 0%, #e2f1e8 100%);
            box-shadow: 0 10px 22px rgba(75, 149, 102, 0.12);
        }

        .user-route-option-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 0.75rem;
            margin-bottom: 0.35rem;
        }

        .user-route-option-title {
            color: #243245;
            font-size: 0.96rem;
            font-weight: 800;
            line-height: 1.25;
        }

        .user-route-option-meta {
            color: #617089;
            font-size: 0.84rem;
            line-height: 1.35;
        }

        .user-route-option-badge {
            flex-shrink: 0;
            padding: 0.28rem 0.55rem;
            border-radius: 999px;
            background: #eef3f8;
            color: #516074;
            font-size: 0.72rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .user-route-option.is-selected .user-route-option-badge {
            background: #4b9566;
            color: #fff;
        }

        .user-route-picker-native {
            display: none;
        }

        .user-route-modal .form-text {
            font-size: 0.82rem;
        }

        .users-table-compact td:nth-child(8) {
            min-width: 78px;
        }

        .users-table-compact td:nth-child(9) {
            min-width: 130px;
            box-shadow: -10px 0 20px rgba(15, 23, 42, 0.08);
        }

        .user-password-btn {
            width: 34px;
            height: 34px;
            border-radius: 0.75rem;
            font-size: 0.72rem;
        }

        .user-pill-btn {
            min-width: 58px;
            min-height: 34px;
            padding: 0.4rem 0.65rem;
            font-size: 0.68rem;
            border-radius: 0.75rem;
        }
    </style>

    <div class="page-hero d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
        <div>
            <p class="page-eyebrow">User Management</p>
            <h1 class="page-title">All Users</h1>
            <p class="page-subtitle">Review and update users, roles, and scope settings in one place.</p>
        </div>
        <a class="btn btn-success" href="{{ route('users.create') }}">
            <i class="fa-solid fa-plus me-2"></i>Add User
        </a>
    </div>

    <section class="row g-4 stats-overlap">
        <div class="col-sm-6 col-xl-4"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">Total Users</p><h2 class="stat-value">{{ $stats['total'] }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-users app-icon"></i></span></div><p class="stat-note">All user accounts available in the system.</p></div></div></div>
        <div class="col-sm-6 col-xl-4"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">Admin Users</p><h2 class="stat-value">{{ $stats['admins'] }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-user-shield app-icon"></i></span></div><p class="stat-note">Users with full or administrative access roles.</p></div></div></div>
        <div class="col-sm-6 col-xl-4"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">Scoped Users</p><h2 class="stat-value">{{ $stats['scoped'] }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-layer-group app-icon"></i></span></div><p class="stat-note">Users limited to district, division, department, or route scope.</p></div></div></div>
    </section>

    <section class="row g-4 mt-2">
        <div class="col-12">
            <div class="card section-card table-card mb-4">
                <div class="card-header">
                    <div class="table-toolbar">
                        <div>
                            <h3 class="section-title">User Records</h3>
                            <p class="section-copy">Complete list of saved users with role and scope details.</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-shell table-wrap">
                        <table class="table table-app align-middle users-table-compact">
                            <thead>
                                <tr>
                                    <th>Sr #</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Division</th>
                                    <th>District</th>
                                    <th>Routes</th>
                                    <th>Change Password</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $listedUser)
                                    <tr>
                                        <td>{{ $users->firstItem() + $loop->index }}</td>
                                        <td class="fw-semibold">{{ $listedUser->name }}</td>
                                        <td>{{ $listedUser->email }}</td>
                                        <td class="p-2">
                                            <select class="form-select" name="role" form="user-row-form-{{ $listedUser->id }}">
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->slug }}" @selected($listedUser->role === $role->slug)>{{ $role->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="p-2">
                                            <select class="form-select" name="division_id" form="user-row-form-{{ $listedUser->id }}">
                                                <option value="">Select Division</option>
                                                @foreach ($divisions as $division)
                                                    <option value="{{ $division->id }}" @selected((int) $listedUser->division_id === (int) $division->id)>{{ $division->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="p-2">
                                            <select class="form-select" name="district_id" form="user-row-form-{{ $listedUser->id }}">
                                                <option value="">Select District</option>
                                                @foreach ($districts as $district)
                                                    <option value="{{ $district->id }}" @selected((int) $listedUser->district_id === (int) $district->id)>{{ $district->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="p-2">
                                            @php
                                                $selectedRoutes = $listedUser->routes->isNotEmpty()
                                                    ? $listedUser->routes
                                                    : ($listedUser->route ? collect([$listedUser->route]) : collect());
                                            @endphp
                                            <button
                                                type="button"
                                                class="user-route-manage-btn"
                                                data-bs-toggle="modal"
                                                data-bs-target="#userRoutesModal-{{ $listedUser->id }}"
                                            >
                                                Manage Routes
                                            </button>
                                            <div class="small text-muted mt-1">{{ $listedUser->all_routes_access ? 'All routes selected' : (($listedUser->routes->count() ?: ($listedUser->route_id ? 1 : 0)).' route(s) selected') }}</div>
                                            @if ($listedUser->all_routes_access)
                                                <div class="small text-muted mt-1 users-route-summary">All routes access enabled</div>
                                            @elseif ($selectedRoutes->isNotEmpty())
                                                <div class="small text-muted mt-1 users-route-summary">
                                                    @foreach ($selectedRoutes as $selectedRoute)
                                                        <div>{{ $selectedRoute->route_name }} ({{ $selectedRoute->starting_point ?: 'N/A' }} to {{ $selectedRoute->ending_point ?: 'N/A' }})</div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            <div class="modal fade user-route-modal" id="userRoutesModal-{{ $listedUser->id }}" tabindex="-1" aria-labelledby="userRoutesModalLabel-{{ $listedUser->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <div>
                                                                <h2 class="modal-title mb-1" id="userRoutesModalLabel-{{ $listedUser->id }}">Manage Routes</h2>
                                                                <p class="user-password-copy mb-0">{{ $listedUser->name }} - {{ $listedUser->email }}</p>
                                                            </div>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <label class="form-label fw-semibold" for="user_route_search_{{ $listedUser->id }}">Routes</label>
                                                            <div class="user-route-picker-shell" data-route-picker>
                                                                <select class="form-select route-picker user-route-picker-native" id="user_route_ids_{{ $listedUser->id }}" name="route_ids[]" form="user-row-form-{{ $listedUser->id }}" multiple size="10" data-route-select data-no-select2>
                                                                    <option value="all_routes" @selected($listedUser->all_routes_access)>All Routes</option>
                                                                    @foreach ($routes as $route)
                                                                        <option value="{{ $route->id }}" data-route-name="{{ $route->route_name }}" data-route-from="{{ $route->starting_point ?: 'N/A' }}" data-route-to="{{ $route->ending_point ?: 'N/A' }}" @selected($listedUser->routes->contains('id', $route->id) || ((int) $listedUser->route_id === (int) $route->id && $listedUser->routes->isEmpty()))>{{ $route->route_name }} ({{ $route->starting_point ?: 'N/A' }} to {{ $route->ending_point ?: 'N/A' }})</option>
                                                                    @endforeach
                                                                </select>
                                                                <div class="user-route-selected-box">
                                                                    <span class="user-route-selected-label">Selected Routes</span>
                                                                    <div class="user-route-selected-chips" data-route-selected></div>
                                                                </div>
                                                                <input class="form-control user-route-search" id="user_route_search_{{ $listedUser->id }}" type="search" placeholder="Search route name, start point, or end point" data-route-search>
                                                                <div class="user-route-options" data-route-options>
                                                                    <button type="button" class="user-route-option" data-route-card data-value="all_routes" data-search-text="all routes full access all routes">
                                                                        <div class="user-route-option-head">
                                                                            <div class="user-route-option-title">All Routes</div>
                                                                            <span class="user-route-option-badge">All</span>
                                                                        </div>
                                                                        <div class="user-route-option-meta">Give this user access to all routes, vehicles, and route-scoped records.</div>
                                                                    </button>
                                                                    @foreach ($routes as $route)
                                                                        <button
                                                                            type="button"
                                                                            class="user-route-option"
                                                                            data-route-card
                                                                            data-value="{{ $route->id }}"
                                                                            data-search-text="{{ strtolower($route->route_name.' '.$route->starting_point.' '.$route->ending_point) }}"
                                                                        >
                                                                            <div class="user-route-option-head">
                                                                                <div class="user-route-option-title">{{ $route->route_name }}</div>
                                                                                <span class="user-route-option-badge">Route</span>
                                                                            </div>
                                                                            <div class="user-route-option-meta">{{ $route->starting_point ?: 'N/A' }} to {{ $route->ending_point ?: 'N/A' }}</div>
                                                                        </button>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                            <div class="form-text mt-2">Select `All Routes` for full access, or hold `Command` on Mac / `Ctrl` on Windows to select multiple routes.</div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" form="user-row-form-{{ $listedUser->id }}" class="btn btn-success" data-bs-dismiss="modal">Save Routes</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <button
                                                type="button"
                                                class="user-password-btn border-0"
                                                title="Change Password"
                                                data-user-password-trigger
                                                data-user-id="{{ $listedUser->id }}"
                                                data-user-name="{{ $listedUser->name }}"
                                            >
                                                <i class="fa-solid fa-key"></i>
                                            </button>
                                        </td>
                                        <td class="text-center text-nowrap">
                                            <div class="user-action-stack">
                                                <form method="post" action="{{ route('users.update', $listedUser) }}" id="user-row-form-{{ $listedUser->id }}" class="d-inline">
                                                    @csrf
                                                    @method('put')
                                                    <input type="hidden" name="name" value="{{ $listedUser->name }}">
                                                    <input type="hidden" name="email" value="{{ $listedUser->email }}">
                                                    <input type="hidden" name="redirect_to" value="index">
                                                </form>
                                                <button type="submit" form="user-row-form-{{ $listedUser->id }}" class="user-pill-btn user-pill-btn-success">Update</button>
                                                @if ($listedUser->id !== auth()->id())
                                                    <form method="post" action="{{ route('users.destroy', $listedUser) }}" class="d-inline" data-confirm-delete data-delete-message="Are you sure you want to delete <strong>{{ e($listedUser->name) }}</strong>?">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" class="user-pill-btn user-pill-btn-danger">Delete</button>
                                                        
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="9" class="text-center text-muted py-4">No users found yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @include('settings.partials.pagination', ['paginator' => $users, 'perPage' => $perPage])
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade user-password-modal" id="userPasswordModal" tabindex="-1" aria-labelledby="userPasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="post" id="userPasswordForm">
                    @csrf
                    @method('put')
                    <input type="hidden" id="user_password_user_id" name="user_id" value="{{ old('user_id') }}">

                    <div class="modal-header">
                        <h2 class="modal-title mb-0" id="userPasswordModalLabel">Change Password</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <p class="user-password-copy mb-3">Updating password for: <strong id="userPasswordUserName">{{ old('user_name') }}</strong></p>

                        <div class="mb-3">
                            <label class="form-label" for="modal_password">New Password <span class="text-danger">*</span></label>
                            <div class="input-group password-input-group">
                                <input class="form-control @error('password') is-invalid @enderror" id="modal_password" name="password" type="password" required>
                                <button class="password-toggle-btn" type="button" data-modal-toggle-password="modal_password" aria-label="Toggle password visibility">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </div>
                            @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-0">
                            <label class="form-label" for="modal_password_confirmation">Confirm Password <span class="text-danger">*</span></label>
                            <div class="input-group password-input-group">
                                <input class="form-control @error('password_confirmation') is-invalid @enderror" id="modal_password_confirmation" name="password_confirmation" type="password" required>
                                <button class="password-toggle-btn" type="button" data-modal-toggle-password="modal_password_confirmation" aria-label="Toggle password visibility">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </div>
                            @error('password_confirmation')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-warning" type="submit">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('[data-route-picker]').forEach(function (picker) {
                const select = picker.querySelector('[data-route-select]');
                const selectedBox = picker.querySelector('[data-route-selected]');
                const searchField = picker.querySelector('[data-route-search]');
                const routeCards = Array.from(picker.querySelectorAll('[data-route-card]'));

                if (!select || !selectedBox) {
                    return;
                }

                const setSelectedValues = function (values) {
                    const normalizedValues = values.map(function (value) {
                        return String(value);
                    });

                    Array.from(select.options).forEach(function (option) {
                        option.selected = normalizedValues.includes(option.value);
                    });
                };

                const getSelectedValues = function () {
                    return Array.from(select.options)
                        .filter(function (option) {
                            return option.selected;
                        })
                        .map(function (option) {
                            return option.value;
                        });
                };

                const optionLabel = function (option) {
                    if (option.value === 'all_routes') {
                        return 'All Routes';
                    }

                    const routeName = option.dataset.routeName || option.textContent.trim();
                    const routeFrom = option.dataset.routeFrom || 'N/A';
                    const routeTo = option.dataset.routeTo || 'N/A';

                    return routeName + ' (' + routeFrom + ' to ' + routeTo + ')';
                };

                const syncCards = function () {
                    const selectedValues = getSelectedValues();

                    routeCards.forEach(function (card) {
                        card.classList.toggle('is-selected', selectedValues.includes(card.dataset.value));
                    });
                };

                const renderSelected = function () {
                    const selectedOptions = Array.from(select.options).filter(function (option) {
                        return option.selected;
                    });

                    selectedBox.innerHTML = '';

                    if (selectedOptions.length === 0) {
                        const emptyState = document.createElement('div');
                        emptyState.className = 'user-route-empty';
                        emptyState.textContent = 'No routes selected yet.';
                        selectedBox.appendChild(emptyState);
                        syncCards();
                        return;
                    }

                    selectedOptions.forEach(function (option) {
                        const chip = document.createElement('div');
                        chip.className = 'user-route-chip';

                        const chipText = document.createElement('span');
                        chipText.textContent = optionLabel(option);
                        chip.appendChild(chipText);

                        const removeButton = document.createElement('button');
                        removeButton.type = 'button';
                        removeButton.className = 'user-route-chip-remove';
                        removeButton.setAttribute('aria-label', 'Remove ' + optionLabel(option));
                        removeButton.innerHTML = '<i class="fa-solid fa-xmark"></i>';
                        removeButton.addEventListener('click', function () {
                            if (option.value === 'all_routes') {
                                option.selected = false;
                            } else {
                                option.selected = false;
                            }

                            renderSelected();
                        });
                        chip.appendChild(removeButton);

                        selectedBox.appendChild(chip);
                    });

                    syncCards();
                };

                const selectOnlyAllRoutes = function () {
                    setSelectedValues(['all_routes']);
                    renderSelected();
                };

                routeCards.forEach(function (card) {
                    card.addEventListener('click', function () {
                        const value = card.dataset.value;
                        const option = Array.from(select.options).find(function (item) {
                            return item.value === value;
                        });

                        if (!option) {
                            return;
                        }

                        if (value === 'all_routes') {
                            selectOnlyAllRoutes();
                            return;
                        }

                        const allRoutesOption = Array.from(select.options).find(function (item) {
                            return item.value === 'all_routes';
                        });

                        if (allRoutesOption) {
                            allRoutesOption.selected = false;
                        }

                        option.selected = !option.selected;
                        renderSelected();
                    });
                });

                if (searchField) {
                    searchField.addEventListener('input', function () {
                        const searchText = searchField.value.trim().toLowerCase();

                        routeCards.forEach(function (card) {
                            const haystack = (card.dataset.searchText || '').toLowerCase();
                            card.classList.toggle('d-none', searchText !== '' && !haystack.includes(searchText));
                        });
                    });
                }

                if (getSelectedValues().includes('all_routes')) {
                    selectOnlyAllRoutes();
                } else {
                    renderSelected();
                }
            });
        });
    </script>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modalElement = document.getElementById('userPasswordModal');
            const form = document.getElementById('userPasswordForm');
            const userIdField = document.getElementById('user_password_user_id');
            const userNameLabel = document.getElementById('userPasswordUserName');

            if (!modalElement || !form || !userIdField || !userNameLabel || !window.bootstrap) {
                return;
            }

            const modal = window.bootstrap.Modal.getOrCreateInstance(modalElement);
            const routeTemplate = @json(route('users.password.update', ['user' => '__USER__']));

            const populateModal = function (payload) {
                userIdField.value = payload.id || '';
                userNameLabel.textContent = payload.name || '';
                form.action = routeTemplate.replace('__USER__', payload.id);
            };

            document.querySelectorAll('[data-user-password-trigger]').forEach(function (trigger) {
                trigger.addEventListener('click', function () {
                    populateModal({
                        id: trigger.dataset.userId,
                        name: trigger.dataset.userName,
                    });

                    form.reset();
                    userIdField.value = trigger.dataset.userId;
                    userNameLabel.textContent = trigger.dataset.userName || '';
                    modal.show();
                });
            });

            document.querySelectorAll('[data-modal-toggle-password]').forEach(function (button) {
                button.addEventListener('click', function () {
                    const input = document.getElementById(button.dataset.modalTogglePassword);

                    if (!input) {
                        return;
                    }

                    const isPassword = input.type === 'password';
                    input.type = isPassword ? 'text' : 'password';

                    const icon = button.querySelector('i');
                    if (icon) {
                        icon.classList.toggle('fa-eye', !isPassword);
                        icon.classList.toggle('fa-eye-slash', isPassword);
                    }
                });
            });

            @if ($errors->has('password') || $errors->has('password_confirmation'))
                const previousTrigger = document.querySelector('[data-user-password-trigger][data-user-id="{{ old('user_id') }}"]');
                if (previousTrigger) {
                    populateModal({
                        id: previousTrigger.dataset.userId,
                        name: previousTrigger.dataset.userName,
                    });
                    modal.show();
                }
            @endif
        });
    </script>
@endpush
