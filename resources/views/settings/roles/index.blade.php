@extends('layouts.app', ['title' => 'All Roles | Free Public Transport System', 'pageBadge' => 'Settings'])

@section('content')
    <style>
        .role-directory-card {
            border-radius: 1.55rem;
            border: 1px solid #dfe7e1;
            overflow: hidden;
            background: linear-gradient(180deg, #ffffff 0%, #fbfdfb 100%);
        }

        .role-directory-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1.35rem 1.5rem;
            border-bottom: 1px solid #e8efea;
        }

        .role-directory-title {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            flex-wrap: wrap;
        }

        .role-system-badge,
        .role-user-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.45rem 0.85rem;
            border-radius: 999px;
            font-size: 0.82rem;
            font-weight: 700;
        }

        .role-system-badge {
            background: #f5f0ff;
            color: #6d45e2;
            border: 1px solid #e3d7ff;
        }

        .role-user-badge {
            background: #eef4ff;
            color: #3f63d1;
            border: 1px solid #d5e1ff;
        }

        .role-directory-menu {
            width: 52px;
            height: 52px;
            border-radius: 1rem;
            border: 1px solid #d4dde8;
            background: #fff;
            color: #445065;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 24px rgba(25, 39, 52, 0.08);
        }

        .role-directory-body {
            padding: 1.35rem 1.5rem 1.45rem;
        }

        .role-meta-grid,
        .role-permission-grid {
            display: grid;
            gap: 0.85rem;
        }

        .role-meta-grid {
            grid-template-columns: 1.2fr 1fr 1.35fr;
            margin-bottom: 1rem;
        }

        .role-permission-grid {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }

        .role-meta-item,
        .role-permission-item {
            border: 1px solid #dfe7e1;
            border-radius: 1.1rem;
            padding: 0.9rem 1rem;
            background: #fff;
            min-height: 84px;
        }

        .role-meta-label,
        .role-permission-label {
            color: #7a8699;
            font-size: 0.78rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 0.35rem;
        }

        .role-meta-value,
        .role-permission-value {
            color: #243245;
            font-size: 0.92rem;
            font-weight: 800;
            line-height: 1.35;
        }

        .role-role-id {
            color: #7a8699;
            font-size: 0.85rem;
            margin-top: 0.9rem;
        }

        @media (max-width: 1199.98px) {
            .role-meta-grid,
            .role-permission-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 575.98px) {
            .role-directory-head,
            .role-directory-body {
                padding: 1.1rem;
            }

            .role-meta-grid,
            .role-permission-grid {
                grid-template-columns: 1fr;
            }
        }

        .role-details-modal .modal-dialog {
            max-width: 720px;
        }

        .role-details-modal .modal-content {
            border: 0;
            border-radius: 1.5rem;
            overflow: hidden;
            box-shadow: 0 22px 55px rgba(25, 39, 52, 0.18);
        }

        .role-details-modal .modal-header,
        .role-details-modal .modal-footer {
            padding: 1.1rem 1.4rem;
        }

        .role-details-modal .modal-header {
            border-bottom: 1px solid #e6edf3;
        }

        .role-details-modal .modal-body {
            padding: 1.25rem 1.4rem 1rem;
        }

        .role-details-modal .modal-title {
            color: #243245;
            font-size: 1.05rem;
            font-weight: 800;
        }

        .role-details-modal .btn-close {
            font-size: 0.95rem;
        }

        .role-details-modal .form-label {
            font-size: 0.92rem;
            font-weight: 800;
            color: #3b465c;
            margin-bottom: 0.45rem;
        }

        .role-details-modal .form-control {
            min-height: 48px;
            border: 1px solid #d8e2ef;
            border-radius: 1rem;
            font-size: 0.98rem;
            padding: 0.75rem 1rem;
            color: #445065;
        }

        .role-details-modal .form-text {
            color: #708198;
            font-size: 0.82rem;
            margin-top: 0.4rem;
        }

        .role-details-modal .modal-footer {
            border-top: 1px solid #e6edf3;
            gap: 0.75rem;
        }

        .role-details-modal .modal-footer .btn {
            min-width: 140px;
            min-height: 52px;
            border-radius: 1rem;
            font-size: 0.95rem;
            font-weight: 800;
        }

        .role-access-modal .modal-dialog {
            max-width: 760px;
        }

        .role-access-modal .modal-content {
            border: 0;
            border-radius: 1.5rem;
            overflow: hidden;
            box-shadow: 0 22px 55px rgba(25, 39, 52, 0.18);
        }

        .role-access-modal .modal-header,
        .role-access-modal .modal-footer {
            padding: 0.95rem 1.15rem;
        }

        .role-access-modal .modal-body {
            padding: 1rem 1.15rem 0.9rem;
        }

        .role-access-modal .modal-title {
            color: #243245;
            font-size: 0.98rem;
            font-weight: 800;
        }

        .role-access-section-title {
            color: #3b465c;
            font-size: 0.84rem;
            font-weight: 800;
            margin-bottom: 0.65rem;
        }

        .role-access-scope-grid,
        .role-access-permission-grid {
            display: grid;
            gap: 0.75rem;
        }

        .role-access-scope-grid {
            grid-template-columns: repeat(4, minmax(0, 1fr));
            margin-bottom: 0.8rem;
        }

        .role-access-permission-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .role-access-scope-input,
        .role-access-permission-input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .role-access-scope-label,
        .role-access-permission-label {
            display: block;
            cursor: pointer;
            margin: 0;
        }

        .role-access-scope-card,
        .role-access-permission-card {
            position: relative;
            border: 1px solid #d9e4dc;
            border-radius: 1.3rem;
            background: #fff;
            transition: border-color 0.18s ease, box-shadow 0.18s ease, background 0.18s ease;
        }

        .role-access-scope-card {
            min-height: 126px;
            padding: 0.8rem;
        }

        .role-access-permission-card {
            min-height: 92px;
            padding: 0.85rem 4.8rem 0.85rem 0.9rem;
        }

        .role-access-scope-input:checked + .role-access-scope-label .role-access-scope-card,
        .role-access-permission-input:checked + .role-access-permission-label .role-access-permission-card {
            border-color: #5ea47b;
            background: linear-gradient(180deg, #fbfefc 0%, #f3faf6 100%);
            box-shadow: inset 0 0 0 1px rgba(94, 164, 123, 0.18);
        }

        .role-access-scope-icon {
            width: 42px;
            height: 42px;
            border-radius: 0.85rem;
            background: #edf3ef;
            color: #687186;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            margin-bottom: 0.7rem;
        }

        .role-access-scope-title,
        .role-access-permission-title {
            color: #2d473c;
            font-size: 0.82rem;
            font-weight: 800;
            margin-bottom: 0.22rem;
        }

        .role-access-scope-copy,
        .role-access-permission-copy {
            color: #6d7b91;
            font-size: 0.72rem;
            line-height: 1.45;
            margin-bottom: 0;
        }

        .role-access-scope-check {
            position: absolute;
            right: 0.7rem;
            bottom: 0.7rem;
            width: 18px;
            height: 18px;
            border-radius: 999px;
            border: 2px solid #c9d4e0;
            background: #fff;
            transition: border-color 0.18s ease, background 0.18s ease;
        }

        .role-access-scope-check::after {
            content: '';
            position: absolute;
            inset: 4px;
            border-radius: 999px;
            background: transparent;
        }

        .role-access-scope-input:checked + .role-access-scope-label .role-access-scope-check {
            border-color: #3f9263;
            background: #3f9263;
        }

        .role-access-scope-input:checked + .role-access-scope-label .role-access-scope-check::after {
            background: #fff;
        }

        .role-access-switch {
            position: absolute;
            right: 0.85rem;
            top: 50%;
            transform: translateY(-50%);
            width: 3.1rem;
            height: 1.6rem;
            border-radius: 999px;
            background: #d8e1da;
            transition: background 0.18s ease;
        }

        .role-access-switch::after {
            content: '';
            position: absolute;
            top: 0.18rem;
            left: 0.18rem;
            width: 1.24rem;
            height: 1.24rem;
            border-radius: 999px;
            background: #fff;
            box-shadow: 0 2px 6px rgba(21, 34, 23, 0.16);
            transition: transform 0.18s ease;
        }

        .role-access-permission-input:checked + .role-access-permission-label .role-access-switch {
            background: #4b9566;
        }

        .role-access-permission-input:checked + .role-access-permission-label .role-access-switch::after {
            transform: translateX(1.48rem);
        }

        .role-access-modal .modal-footer {
            border-top: 1px solid #e6edf3;
            gap: 0.6rem;
        }

        .role-access-modal .modal-footer .btn {
            min-width: 118px;
            min-height: 44px;
            border-radius: 1rem;
            font-size: 0.88rem;
            font-weight: 800;
        }

        @media (max-width: 991.98px) {
            .role-access-scope-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 767.98px) {
            .role-access-modal .modal-dialog {
                max-width: calc(100% - 1rem);
                margin: 0.5rem auto;
            }

            .role-access-scope-grid,
            .role-access-permission-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="page-hero d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
        <div>
            <p class="page-eyebrow">Settings</p>
            <h1 class="page-title">All Roles</h1>
            <p class="page-subtitle">Review role scope and permission settings across the full system.</p>
        </div>
        <a class="btn btn-success" href="{{ route('settings.roles.create') }}">
            <i class="fa-solid fa-plus me-2"></i>Add Role
        </a>
    </div>

    <section class="row g-4 stats-overlap">
        <div class="col-sm-6 col-xl-4"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">Total Roles</p><h2 class="stat-value">{{ $stats['total'] }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-user-shield app-icon"></i></span></div><p class="stat-note">All role records currently available in the system.</p></div></div></div>
        <div class="col-sm-6 col-xl-4"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">System Roles</p><h2 class="stat-value">{{ $stats['system'] }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-lock app-icon"></i></span></div><p class="stat-note">Protected roles created as system defaults.</p></div></div></div>
        <div class="col-sm-6 col-xl-4"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">Custom Roles</p><h2 class="stat-value">{{ $stats['custom'] }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-pen-ruler app-icon"></i></span></div><p class="stat-note">Roles created manually for project-specific use.</p></div></div></div>
    </section>

    <section class="row g-4 mt-2">
        <div class="col-12">
            <div class="card section-card">
                <div class="card-header">
                    <div class="table-toolbar">
                        <div>
                            <h3 class="section-title">All Roles</h3>
                            <p class="section-copy">Manage each role from the actions menu.</p>
                        </div>
                        <a class="btn btn-success" href="{{ route('settings.roles.create') }}">Add Roles</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        @forelse ($roles as $role)
                            <div class="col-12 col-xl-6">
                                <div class="role-directory-card h-100">
                                    <div class="role-directory-head">
                                        <div class="role-directory-title">
                                            <h3 class="section-title mb-0">{{ $role->name }}</h3>
                                            @if ($role->is_system)
                                                <span class="role-system-badge">System</span>
                                            @endif
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="role-user-badge"><i class="fa-solid fa-users"></i> {{ $role->users_count }} {{ \Illuminate\Support\Str::plural('User', $role->users_count) }}</span>
                                            <div class="dropdown">
                                                <button class="role-directory-menu border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fa-solid fa-ellipsis"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end topbar-dropdown-menu">
                                                    <li>
                                                        <button
                                                            class="dropdown-item"
                                                            type="button"
                                                            data-role-details-trigger
                                                            data-role-id="{{ $role->id }}"
                                                            data-role-name="{{ $role->name }}"
                                                            data-role-slug="{{ $role->slug }}"
                                                            data-role-description="{{ $role->description }}"
                                                            data-role-is-system="{{ $role->is_system ? '1' : '0' }}"
                                                        >
                                                            <i class="fa-solid fa-pen me-2"></i>Edit Details
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <button
                                                            class="dropdown-item"
                                                            type="button"
                                                            data-role-access-trigger
                                                            data-role-id="{{ $role->id }}"
                                                            data-role-access-scope="{{ $role->access_scope }}"
                                                            data-role-can-view="{{ $role->can_view ? '1' : '0' }}"
                                                            data-role-can-create="{{ $role->can_create ? '1' : '0' }}"
                                                            data-role-can-edit="{{ $role->can_edit ? '1' : '0' }}"
                                                            data-role-can-delete="{{ $role->can_delete ? '1' : '0' }}"
                                                            data-role-can-manage-users="{{ $role->can_manage_users ? '1' : '0' }}"
                                                            data-role-can-manage-system-settings="{{ $role->can_manage_system_settings ? '1' : '0' }}"
                                                        >
                                                            <i class="fa-solid fa-shield-halved me-2"></i>Edit Access
                                                        </button>
                                                    </li>
                                                    @if ($role->is_system)
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <span class="dropdown-item text-muted">
                                                                <i class="fa-solid fa-lock me-2"></i>Protected
                                                            </span>
                                                        </li>
                                                    @else
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <form method="post" action="{{ route('settings.roles.destroy', $role) }}" data-confirm-delete data-delete-message="Are you sure you want to delete <strong>{{ e($role->name) }}</strong>?">
                                                                @csrf
                                                                @method('delete')
                                                                <button type="submit" class="dropdown-item text-danger">
                                                                    <i class="fa-solid fa-trash-can me-2"></i>Delete Role
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="role-directory-body">
                                        <div class="role-meta-grid">
                                            <div class="role-meta-item">
                                                <div class="role-meta-label">Name</div>
                                                <div class="role-meta-value">{{ $role->name }}</div>
                                            </div>
                                            <div class="role-meta-item">
                                                <div class="role-meta-label">Slug</div>
                                                <div class="role-meta-value">{{ $role->slug }}</div>
                                            </div>
                                            <div class="role-meta-item">
                                                <div class="role-meta-label">Description</div>
                                                <div class="role-meta-value">{{ $role->description ?: 'No description added.' }}</div>
                                            </div>
                                        </div>

                                        <div class="role-permission-grid">
                                            <div class="role-permission-item">
                                                <div class="role-permission-label">Scope</div>
                                                <div class="role-permission-value">{{ $accessScopes[$role->access_scope] ?? ucfirst($role->access_scope) }}</div>
                                            </div>
                                            <div class="role-permission-item">
                                                <div class="role-permission-label">View</div>
                                                <div class="role-permission-value">{{ $role->can_view ? 'Yes' : 'No' }}</div>
                                            </div>
                                            <div class="role-permission-item">
                                                <div class="role-permission-label">Create</div>
                                                <div class="role-permission-value">{{ $role->can_create ? 'Yes' : 'No' }}</div>
                                            </div>
                                            <div class="role-permission-item">
                                                <div class="role-permission-label">Edit</div>
                                                <div class="role-permission-value">{{ $role->can_edit ? 'Yes' : 'No' }}</div>
                                            </div>
                                            <div class="role-permission-item">
                                                <div class="role-permission-label">Delete</div>
                                                <div class="role-permission-value">{{ $role->can_delete ? 'Yes' : 'No' }}</div>
                                            </div>
                                            <div class="role-permission-item">
                                                <div class="role-permission-label">Manage Users</div>
                                                <div class="role-permission-value">{{ $role->can_manage_users ? 'Yes' : 'No' }}</div>
                                            </div>
                                            <div class="role-permission-item">
                                                <div class="role-permission-label">System Settings</div>
                                                <div class="role-permission-value">{{ $role->can_manage_system_settings ? 'Yes' : 'No' }}</div>
                                            </div>
                                        </div>

                                        <div class="role-role-id">ID: {{ $role->id }}</div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center text-muted py-4">No roles found yet.</div>
                        @endforelse
                    </div>

                    @include('settings.partials.pagination', ['paginator' => $roles, 'perPage' => $perPage])
                </div>
            </div>
    </section>

    <div class="modal fade role-details-modal" id="roleDetailsModal" tabindex="-1" aria-labelledby="roleDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="post" id="roleDetailsForm">
                    @csrf
                    @method('put')
                    <input type="hidden" id="role_details_role_id" name="role_id" value="{{ old('role_id') }}">

                    <div class="modal-header">
                        <h2 class="modal-title mb-0" id="roleDetailsModalLabel">Edit Role Details</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row g-4">
                            <div class="col-12">
                                <label class="form-label fw-semibold" for="role_details_name">Role Name <span class="text-danger">*</span></label>
                                <input class="form-control @error('name') is-invalid @enderror" id="role_details_name" name="name" type="text" value="{{ old('name') }}" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold" for="role_details_slug">Slug</label>
                                <input class="form-control @error('slug') is-invalid @enderror" id="role_details_slug" name="slug" type="text" value="{{ old('slug') }}">
                                <div class="form-text" id="roleDetailsSlugHelp">Used internally by the system.</div>
                                @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold" for="role_details_description">Description</label>
                                <input class="form-control @error('description') is-invalid @enderror" id="role_details_description" name="description" type="text" value="{{ old('description') }}">
                                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-success" type="submit">Save Details</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade role-access-modal" id="roleAccessModal" tabindex="-1" aria-labelledby="roleAccessModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="post" id="roleAccessForm">
                    @csrf
                    @method('put')
                    <input type="hidden" id="role_access_role_id" name="role_id" value="{{ old('role_id') }}">

                    <div class="modal-header">
                        <h2 class="modal-title mb-0" id="roleAccessModalLabel">Edit Access & Permissions</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        @php
                            $scopeMeta = [
                                'global' => ['icon' => 'fa-globe', 'title' => 'Global', 'copy' => 'See records across the whole system.'],
                                'department' => ['icon' => 'fa-building-user', 'title' => 'Department', 'copy' => 'Limit access to one controlling department.'],
                                'district' => ['icon' => 'fa-location-dot', 'title' => 'District', 'copy' => 'Limit access to a single district.'],
                                'division' => ['icon' => 'fa-sitemap', 'title' => 'Division', 'copy' => 'Limit access to every district in one division.'],
                            ];

                            $permissionMeta = [
                                ['field' => 'can_view', 'title' => 'View', 'copy' => 'Allow reading records.'],
                                ['field' => 'can_create', 'title' => 'Create', 'copy' => 'Allow new entries.'],
                                ['field' => 'can_edit', 'title' => 'Edit', 'copy' => 'Allow record updates.'],
                                ['field' => 'can_delete', 'title' => 'Delete', 'copy' => 'Allow removing records.'],
                                ['field' => 'can_manage_users', 'title' => 'Manage Users', 'copy' => 'Create and update user accounts.'],
                                ['field' => 'can_manage_system_settings', 'title' => 'System Settings', 'copy' => 'Open admin settings and role config.'],
                            ];
                        @endphp

                        <div class="role-access-section-title">Access Scope</div>
                        <div class="role-access-scope-grid">
                            @foreach ($accessScopes as $value => $label)
                                <div>
                                    <input class="role-access-scope-input" type="radio" name="access_scope" id="modal_access_scope_{{ $value }}" value="{{ $value }}" @checked(old('access_scope') === $value)>
                                    <label class="role-access-scope-label" for="modal_access_scope_{{ $value }}">
                                        <div class="role-access-scope-card">
                                            <span class="role-access-scope-icon"><i class="fa-solid {{ $scopeMeta[$value]['icon'] }}"></i></span>
                                            <div class="role-access-scope-title">{{ $scopeMeta[$value]['title'] }}</div>
                                            <p class="role-access-scope-copy">{{ $scopeMeta[$value]['copy'] }}</p>
                                            <span class="role-access-scope-check"></span>
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('access_scope')<div class="text-danger small mb-3">{{ $message }}</div>@enderror

                        <div class="role-access-permission-grid">
                            @foreach ($permissionMeta as $permission)
                                <div>
                                    <input class="role-access-permission-input" type="checkbox" name="{{ $permission['field'] }}" id="modal_{{ $permission['field'] }}" value="1" @checked(old($permission['field']))>
                                    <label class="role-access-permission-label" for="modal_{{ $permission['field'] }}">
                                        <div class="role-access-permission-card">
                                            <div class="role-access-permission-title">{{ $permission['title'] }}</div>
                                            <p class="role-access-permission-copy">{{ $permission['copy'] }}</p>
                                            <span class="role-access-switch"></span>
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-success" type="submit">Save Access</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modalElement = document.getElementById('roleDetailsModal');
            const accessModalElement = document.getElementById('roleAccessModal');
            const form = document.getElementById('roleDetailsForm');
            const accessForm = document.getElementById('roleAccessForm');
            const roleIdField = document.getElementById('role_details_role_id');
            const nameField = document.getElementById('role_details_name');
            const slugField = document.getElementById('role_details_slug');
            const descriptionField = document.getElementById('role_details_description');
            const slugHelp = document.getElementById('roleDetailsSlugHelp');

            if (!modalElement || !accessModalElement || !form || !accessForm || !roleIdField || !nameField || !slugField || !descriptionField || !window.bootstrap) {
                return;
            }

            const modal = window.bootstrap.Modal.getOrCreateInstance(modalElement);
            const accessModal = window.bootstrap.Modal.getOrCreateInstance(accessModalElement);
            const detailsRouteTemplate = @json(route('settings.roles.details.update', ['role' => '__ROLE__']));
            const accessRouteTemplate = @json(route('settings.roles.access.update', ['role' => '__ROLE__']));

            const populateModal = function (payload) {
                roleIdField.value = payload.id || '';
                form.action = detailsRouteTemplate.replace('__ROLE__', payload.id);
                nameField.value = payload.name || '';
                slugField.value = payload.slug || '';
                descriptionField.value = payload.description || '';

                const isSystemRole = payload.isSystem === '1';
                slugField.readOnly = isSystemRole;
                slugField.classList.toggle('bg-light', isSystemRole);
                slugHelp.textContent = isSystemRole
                    ? 'Used internally by the system.'
                    : 'Leave blank to auto-generate from the role name.';
            };

            document.querySelectorAll('[data-role-details-trigger]').forEach(function (trigger) {
                trigger.addEventListener('click', function () {
                    populateModal({
                        id: trigger.dataset.roleId,
                        name: trigger.dataset.roleName,
                        slug: trigger.dataset.roleSlug,
                        description: trigger.dataset.roleDescription,
                        isSystem: trigger.dataset.roleIsSystem,
                    });

                    modal.show();
                });
            });

            const accessRoleIdField = document.getElementById('role_access_role_id');

            const populateAccessModal = function (payload) {
                accessRoleIdField.value = payload.id || '';
                accessForm.action = accessRouteTemplate.replace('__ROLE__', payload.id);

                const selectedScope = payload.accessScope || 'global';
                const selectedScopeField = document.getElementById('modal_access_scope_' + selectedScope);

                if (selectedScopeField) {
                    selectedScopeField.checked = true;
                }

                [
                    ['modal_can_view', payload.canView],
                    ['modal_can_create', payload.canCreate],
                    ['modal_can_edit', payload.canEdit],
                    ['modal_can_delete', payload.canDelete],
                    ['modal_can_manage_users', payload.canManageUsers],
                    ['modal_can_manage_system_settings', payload.canManageSystemSettings],
                ].forEach(function (item) {
                    const field = document.getElementById(item[0]);

                    if (field) {
                        field.checked = item[1] === '1';
                    }
                });
            };

            document.querySelectorAll('[data-role-access-trigger]').forEach(function (trigger) {
                trigger.addEventListener('click', function () {
                    populateAccessModal({
                        id: trigger.dataset.roleId,
                        accessScope: trigger.dataset.roleAccessScope,
                        canView: trigger.dataset.roleCanView,
                        canCreate: trigger.dataset.roleCanCreate,
                        canEdit: trigger.dataset.roleCanEdit,
                        canDelete: trigger.dataset.roleCanDelete,
                        canManageUsers: trigger.dataset.roleCanManageUsers,
                        canManageSystemSettings: trigger.dataset.roleCanManageSystemSettings,
                    });

                    accessModal.show();
                });
            });

            @if ($errors->any() && old('name') !== null)
                const previousRoleTrigger = document.querySelector('[data-role-details-trigger][data-role-id="{{ old('role_id') }}"]');

                populateModal({
                    id: @json((string) old('role_id')),
                    name: @json(old('name')),
                    slug: @json(old('slug')),
                    description: @json(old('description')),
                    isSystem: previousRoleTrigger ? previousRoleTrigger.dataset.roleIsSystem : '0',
                });

                modal.show();
            @endif

            @if ($errors->any() && old('access_scope') !== null)
                const previousAccessTrigger = document.querySelector('[data-role-access-trigger][data-role-id="{{ old('role_id') }}"]');

                populateAccessModal({
                    id: @json((string) old('role_id')),
                    accessScope: @json(old('access_scope')),
                    canView: @json(old('can_view') ? '1' : '0'),
                    canCreate: @json(old('can_create') ? '1' : '0'),
                    canEdit: @json(old('can_edit') ? '1' : '0'),
                    canDelete: @json(old('can_delete') ? '1' : '0'),
                    canManageUsers: @json(old('can_manage_users') ? '1' : '0'),
                    canManageSystemSettings: @json(old('can_manage_system_settings') ? '1' : '0'),
                });

                if (previousAccessTrigger) {
                    accessModal.show();
                }
            @endif
        });
    </script>
@endpush
