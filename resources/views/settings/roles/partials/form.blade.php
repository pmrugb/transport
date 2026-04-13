<form method="post" action="{{ $formAction }}">
    @csrf
    @if ($formMethod !== 'post')
        @method($formMethod)
    @endif

    <style>
        .role-scope-card,
        .role-permission-card {
            position: relative;
            border: 1px solid #dfe7e1;
            border-radius: 1.1rem;
            background: linear-gradient(180deg, #ffffff 0%, #fbfdfb 100%);
            padding: 0.95rem 1rem;
            min-height: 118px;
            transition: border-color 0.18s ease, box-shadow 0.18s ease, transform 0.18s ease;
        }

        .role-scope-card:hover,
        .role-permission-card:hover {
            transform: translateY(-1px);
            box-shadow: 0 14px 30px rgba(32, 52, 84, 0.07);
        }

        .role-scope-input,
        .role-permission-input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .role-scope-label,
        .role-permission-label {
            display: block;
            cursor: pointer;
        }

        .role-scope-input:checked + .role-scope-label .role-scope-card {
            border-color: #5ea47b;
            box-shadow: inset 0 0 0 1px rgba(94, 164, 123, 0.32);
            background: linear-gradient(180deg, #fbfefc 0%, #f4faf6 100%);
        }

        .role-scope-check {
            position: absolute;
            right: 0.8rem;
            bottom: 0.8rem;
            width: 20px;
            height: 20px;
            border-radius: 999px;
            border: 1.5px solid #c9d4e0;
            background: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: transparent;
            font-size: 0.58rem;
        }

        .role-scope-input:checked + .role-scope-label .role-scope-check {
            border-color: #3f9263;
            background: #3f9263;
            color: #fff;
        }

        .role-scope-icon,
        .role-permission-icon {
            width: 38px;
            height: 38px;
            border-radius: 0.8rem;
            background: #edf5ef;
            color: #4e8063;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            margin-bottom: 0.75rem;
        }

        .role-scope-title,
        .role-permission-title {
            color: #2b443a;
            font-size: 0.92rem;
            font-weight: 800;
            margin-bottom: 0.3rem;
        }

        .role-scope-copy,
        .role-permission-copy {
            color: #6b7a8f;
            font-size: 0.74rem;
            margin-bottom: 0;
            max-width: 12rem;
        }

        .role-permission-card {
            min-height: 100px;
            padding-right: 4rem;
        }

        .role-switch {
            position: absolute;
            right: 0.95rem;
            top: 50%;
            transform: translateY(-50%);
            width: 2.8rem;
            height: 1.55rem;
            border-radius: 999px;
            background: #d8e1da;
            transition: background 0.18s ease;
        }

        .role-switch::after {
            content: '';
            position: absolute;
            top: 0.18rem;
            left: 0.22rem;
            width: 1.15rem;
            height: 1.15rem;
            border-radius: 999px;
            background: #fff;
            box-shadow: 0 2px 6px rgba(21, 34, 23, 0.16);
            transition: transform 0.18s ease;
        }

        .role-permission-input:checked + .role-permission-label .role-permission-card {
            border-color: #cfe2d6;
            background: linear-gradient(180deg, #fcfffd 0%, #f3faf6 100%);
        }

        .role-permission-input:checked + .role-permission-label .role-switch {
            background: #4b9566;
        }

        .role-permission-input:checked + .role-permission-label .role-switch::after {
            transform: translateX(1.2rem);
        }
    </style>

    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label fw-semibold" for="name">Role Name <span class="text-danger">*</span></label>
            <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" type="text" value="{{ old('name', $role->name) }}" placeholder="e.g. Procurement Admin">
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold" for="slug">Slug (optional)</label>
            <input class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" type="text" value="{{ old('slug', $role->slug) }}" placeholder="auto from name">
            @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-5">
            <label class="form-label fw-semibold" for="description">Description (optional)</label>
            <input class="form-control @error('description') is-invalid @enderror" id="description" name="description" type="text" value="{{ old('description', $role->description) }}" placeholder="Role description">
            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-12 pt-2">
            <h4 class="section-title mb-2">Access Scope</h4>
            <div class="row g-3">
                @php
                    $scopeMeta = [
                        'global' => ['icon' => 'fa-globe', 'title' => 'Global', 'copy' => 'See records across the whole system.'],
                        'department' => ['icon' => 'fa-building-user', 'title' => 'Department', 'copy' => 'Limit access to any department.'],
                        'district' => ['icon' => 'fa-location-dot', 'title' => 'District', 'copy' => 'Limit access to a single district.'],
                        'division' => ['icon' => 'fa-sitemap', 'title' => 'Division', 'copy' => 'Limit access to every district in one division.'],
                    ];
                @endphp
                @foreach ($accessScopes as $value => $label)
                    <div class="col-md-3">
                        <input class="role-scope-input" type="radio" name="access_scope" id="access_scope_{{ $value }}" value="{{ $value }}" @checked(old('access_scope', $role->access_scope ?? 'global') === $value)>
                        <label class="role-scope-label" for="access_scope_{{ $value }}">
                            <div class="role-scope-card">
                                <span class="role-scope-icon"><i class="fa-solid {{ $scopeMeta[$value]['icon'] }}"></i></span>
                                <div class="role-scope-title">{{ $scopeMeta[$value]['title'] }}</div>
                                <p class="role-scope-copy">{{ $scopeMeta[$value]['copy'] }}</p>
                                <span class="role-scope-check"><i class="fa-solid fa-check"></i></span>
                            </div>
                        </label>
                    </div>
                @endforeach
            </div>
            @error('access_scope')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
        </div>

        <div class="col-12 pt-2">
            <div class="row g-3">
                @php
                    $permissions = [
                        ['field' => 'can_view', 'title' => 'View', 'copy' => 'Allow reading records.', 'icon' => 'fa-eye'],
                        ['field' => 'can_create', 'title' => 'Create', 'copy' => 'Allow new entries.', 'icon' => 'fa-plus'],
                        ['field' => 'can_edit', 'title' => 'Edit', 'copy' => 'Allow record updates.', 'icon' => 'fa-pen'],
                        ['field' => 'can_delete', 'title' => 'Delete', 'copy' => 'Allow removing records.', 'icon' => 'fa-trash'],
                        ['field' => 'can_manage_users', 'title' => 'Manage Users', 'copy' => 'Create and update user accounts.', 'icon' => 'fa-users-gear'],
                        ['field' => 'can_manage_system_settings', 'title' => 'System Settings', 'copy' => 'Open admin settings and role config.', 'icon' => 'fa-gear'],
                    ];
                @endphp
                @foreach ($permissions as $permission)
                    <div class="col-md-2">
                        <input class="role-permission-input" type="checkbox" name="{{ $permission['field'] }}" id="{{ $permission['field'] }}" value="1" @checked(old($permission['field'], $role->{$permission['field']} ?? ($permission['field'] === 'can_view'))) >
                        <label class="role-permission-label" for="{{ $permission['field'] }}">
                            <div class="role-permission-card">
                                
                                <div class="role-permission-title">{{ $permission['title'] }}</div>
                                <p class="role-permission-copy">{{ $permission['copy'] }}</p>
                                <span class="role-switch"></span>
                            </div>
                        </label>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-check mt-2">
                <input class="form-check-input" id="is_system" name="is_system" type="checkbox" value="1" @checked(old('is_system', $role->is_system))>
                <label class="form-check-label fw-semibold" for="is_system">Mark as system role</label>
            </div>
        </div>

        <div class="col-12 d-flex justify-content-end gap-2 pt-2">
            <button class="btn btn-success px-4" type="submit">{{ $submitLabel }}</button>
        </div>
    </div>
</form>
