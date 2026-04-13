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
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
            white-space: nowrap;
        }

        .users-table-compact td {
            font-size: 0.95rem;
        }

        .users-table-compact td:nth-child(2) {
            white-space: normal;
            min-width: 130px;
        }

        .users-table-compact td:nth-child(3) {
            min-width: 220px;
        }

        .users-table-compact .form-select {
            min-width: 150px;
            min-height: 40px;
            padding: 0.45rem 2rem 0.45rem 0.8rem;
            border-radius: 1rem;
            font-size: 0.88rem;
            white-space: nowrap;
        }

        .users-table-compact td:nth-child(5) .form-select,
        .users-table-compact td:nth-child(6) .form-select {
            min-width: 135px;
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
        <div class="col-sm-6 col-xl-4"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">Scoped Users</p><h2 class="stat-value">{{ $stats['scoped'] }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-layer-group app-icon"></i></span></div><p class="stat-note">Users limited to a district or division scope.</p></div></div></div>
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
                                    <tr><td colspan="8" class="text-center text-muted py-4">No users found yet.</td></tr>
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
