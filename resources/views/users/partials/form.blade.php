<form method="post" action="{{ $formAction }}" id="userForm" novalidate>
    @csrf
    @if ($formMethod !== 'post')
        @method($formMethod)
    @endif

    @php
        $selectedRouteIds = collect(old('route_ids', $user->exists ? $user->routes->pluck('id')->all() : ($user->route_id ? [$user->route_id] : [])))
            ->map(fn ($routeId) => (string) $routeId)
            ->all();
        $hasAllRoutesSelected = (bool) old('all_routes_access', $user->all_routes_access);
    @endphp

    <div class="row g-3">
        <div class="col-12">
            <label class="form-label fw-semibold" for="name">Name <span class="text-danger">*</span></label>
            <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" type="text" placeholder="Enter full name" value="{{ old('name', $user->name) }}" required>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-12">
            <label class="form-label fw-semibold" for="email">Email <span class="text-danger">*</span></label>
            <input class="form-control @error('email') is-invalid @enderror" id="email" name="email" type="email" placeholder="Enter email address" value="{{ old('email', $user->email) }}" required>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-12">
            <label class="form-label fw-semibold" for="role">Role <span class="text-danger">*</span></label>
            <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                <option value="">Select role</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->slug }}" data-access-scope="{{ $role->access_scope }}" @selected(old('role', $user->role) === $role->slug)>{{ $role->name }}</option>
                @endforeach
            </select>
            @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-12 d-none" id="districtFieldWrap">
            <label class="form-label fw-semibold" for="district_id">District</label>
            <select class="form-select @error('district_id') is-invalid @enderror" id="district_id" name="district_id">
                <option value="">Select District</option>
                @foreach ($districts as $district)
                    <option value="{{ $district->id }}" @selected((string) old('district_id', $user->district_id) === (string) $district->id)>{{ $district->name }}</option>
                @endforeach
            </select>
            @error('district_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-12 d-none" id="divisionFieldWrap">
            <label class="form-label fw-semibold" for="division_id">Division</label>
            <select class="form-select @error('division_id') is-invalid @enderror" id="division_id" name="division_id">
                <option value="">Select Division</option>
                @foreach ($divisions as $division)
                    <option value="{{ $division->id }}" @selected((string) old('division_id', $user->division_id) === (string) $division->id)>{{ $division->name }}</option>
                @endforeach
            </select>
            @error('division_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-12 d-none" id="routeFieldWrap">
            <label class="form-label fw-semibold" for="route_ids">Routes</label>
            <select class="form-select @error('route_ids') is-invalid @enderror @error('route_ids.*') is-invalid @enderror" id="route_ids" name="route_ids[]" multiple size="6">
                <option value="all_routes" @selected($hasAllRoutesSelected)>All Routes</option>
                @foreach ($routes as $route)
                    <option value="{{ $route->id }}" @selected(in_array((string) $route->id, $selectedRouteIds, true))>{{ $route->route_name }} ({{ $route->starting_point }} to {{ $route->ending_point }})</option>
                @endforeach
            </select>
            <div class="form-text">Select `All Routes` for full access, or hold `Command` on Mac / `Ctrl` on Windows to select multiple routes.</div>
            @error('route_ids')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            @error('route_ids.*')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="col-12 d-none" id="allRoutesAccessWrap">
            <div class="form-check">
                <input class="form-check-input @error('all_routes_access') is-invalid @enderror" id="all_routes_access" name="all_routes_access" type="checkbox" value="1" @checked(old('all_routes_access', $user->all_routes_access))>
                <label class="form-check-label fw-semibold" for="all_routes_access">Allow all routes for this user</label>
            </div>
            <div class="form-text">Use this only when the role is route-scoped but this specific user should still see all routes.</div>
            @error('all_routes_access')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="col-12">
            <label class="form-label fw-semibold" for="password">Password @if ($formMethod === 'post')<span class="text-danger">*</span>@endif</label>
            <div class="position-relative">
                <input class="form-control @error('password') is-invalid @enderror pe-5" id="password" name="password" type="password" placeholder="Enter password" {{ $formMethod === 'post' ? 'required' : '' }}>
                <button class="btn btn-link position-absolute top-50 end-0 translate-middle-y text-muted text-decoration-none" type="button" data-toggle-password="password" tabindex="-1" aria-label="Toggle password visibility">
                    <i class="fa-solid fa-eye"></i>
                </button>
            </div>
            @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="col-12">
            <label class="form-label fw-semibold" for="password_confirmation">Confirm Password @if ($formMethod === 'post')<span class="text-danger">*</span>@endif</label>
            <div class="position-relative">
                <input class="form-control pe-5" id="password_confirmation" name="password_confirmation" type="password" placeholder="Confirm password" {{ $formMethod === 'post' ? 'required' : '' }}>
                <button class="btn btn-link position-absolute top-50 end-0 translate-middle-y text-muted text-decoration-none" type="button" data-toggle-password="password_confirmation" tabindex="-1" aria-label="Toggle confirm password visibility">
                    <i class="fa-solid fa-eye"></i>
                </button>
            </div>
        </div>

        <div class="col-12 d-flex flex-wrap gap-2">
            <button class="btn btn-success" type="submit">{{ $submitLabel }}</button>
            <a class="btn btn-outline-secondary" href="{{ route('users.index') }}">Back</a>
        </div>
    </div>
</form>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const roleField = document.getElementById('role');
            const districtWrap = document.getElementById('districtFieldWrap');
            const districtField = document.getElementById('district_id');
            const divisionWrap = document.getElementById('divisionFieldWrap');
            const divisionField = document.getElementById('division_id');
            const routeWrap = document.getElementById('routeFieldWrap');
            const routeField = document.getElementById('route_ids');
            const allRoutesAccessWrap = document.getElementById('allRoutesAccessWrap');
            const allRoutesAccessField = document.getElementById('all_routes_access');

            const syncAllRoutesOption = function () {
                if (!routeField || !allRoutesAccessField) {
                    return;
                }

                const allRoutesOption = Array.from(routeField.options).find(function (option) {
                    return option.value === 'all_routes';
                });

                if (!allRoutesOption) {
                    return;
                }

                if (allRoutesAccessField.checked) {
                    Array.from(routeField.options).forEach(function (option) {
                        option.selected = option.value === 'all_routes';
                    });

                    return;
                }

                allRoutesOption.selected = false;
            };

            const syncAllRoutesCheckboxFromSelect = function () {
                if (!routeField || !allRoutesAccessField) {
                    return;
                }

                const allRoutesSelected = Array.from(routeField.selectedOptions).some(function (option) {
                    return option.value === 'all_routes';
                });

                if (allRoutesSelected) {
                    allRoutesAccessField.checked = true;

                    Array.from(routeField.options).forEach(function (option) {
                        option.selected = option.value === 'all_routes';
                    });
                } else if (allRoutesAccessField.checked) {
                    allRoutesAccessField.checked = false;
                }
            };

            const syncScopedFields = function () {
                const selectedOption = roleField ? roleField.options[roleField.selectedIndex] : null;
                const accessScope = selectedOption ? selectedOption.dataset.accessScope || '' : '';
                const isDistrictAdmin = accessScope === 'district';
                const isDivisionalAdmin = accessScope === 'division';
                const isRouteScoped = accessScope === 'route';
                const hasAllRoutesAccess = allRoutesAccessField ? allRoutesAccessField.checked : false;

                if (districtWrap) {
                    districtWrap.classList.toggle('d-none', !isDistrictAdmin);
                }

                if (divisionWrap) {
                    divisionWrap.classList.toggle('d-none', !isDivisionalAdmin);
                }

                if (routeWrap) {
                    routeWrap.classList.toggle('d-none', !isRouteScoped);
                }

                if (allRoutesAccessWrap) {
                    allRoutesAccessWrap.classList.add('d-none');
                }

                if (!isDistrictAdmin && districtField) {
                    districtField.value = '';
                }

                if (!isDivisionalAdmin && divisionField) {
                    divisionField.value = '';
                }

                if (!isRouteScoped && routeField) {
                    Array.from(routeField.options).forEach(function (option) {
                        option.selected = false;
                    });
                }

                if (!isRouteScoped && allRoutesAccessField) {
                    allRoutesAccessField.checked = false;
                }

                if (isRouteScoped) {
                    syncAllRoutesOption();
                }
            };

            if (roleField) {
                roleField.addEventListener('change', syncScopedFields);
            }

            if (allRoutesAccessField) {
                allRoutesAccessField.addEventListener('change', syncScopedFields);
            }

            if (routeField) {
                routeField.addEventListener('change', function () {
                    syncAllRoutesCheckboxFromSelect();
                    syncScopedFields();
                });
            }

            syncScopedFields();

            document.querySelectorAll('[data-toggle-password]').forEach(function (button) {
                button.addEventListener('click', function () {
                    const input = document.getElementById(button.dataset.togglePassword);

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
        });
    </script>
@endpush
