<form method="post" action="{{ $formAction }}" id="userForm" novalidate>
    @csrf
    @if ($formMethod !== 'post')
        @method($formMethod)
    @endif

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
                    <option value="{{ $role->slug }}" @selected(old('role', $user->role) === $role->slug)>{{ $role->name }}</option>
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

            const syncScopedFields = function () {
                const role = roleField ? roleField.value : '';
                const isDistrictAdmin = role === 'district_admin';
                const isDivisionalAdmin = role === 'divisional_admin';

                if (districtWrap) {
                    districtWrap.classList.toggle('d-none', !isDistrictAdmin);
                }

                if (divisionWrap) {
                    divisionWrap.classList.toggle('d-none', !isDivisionalAdmin);
                }

                if (!isDistrictAdmin && districtField) {
                    districtField.value = '';
                }

                if (!isDivisionalAdmin && divisionField) {
                    divisionField.value = '';
                }
            };

            if (roleField) {
                roleField.addEventListener('change', syncScopedFields);
                syncScopedFields();
            }

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
