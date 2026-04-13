<form method="post" action="{{ $formAction }}" id="routeForm" novalidate>
    @csrf
    @if ($formMethod !== 'post')
        @method($formMethod)
    @endif
    <div class="row g-3">
        @if ($transportRoute->exists)
            <div class="col-md-6">
                <label class="form-label fw-semibold" for="route_code">Route Code</label>
                <input class="form-control" id="route_code" type="text" value="{{ $transportRoute->route_code }}" readonly>
            </div>
        @endif
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="route_name">Route Name <span class="text-danger">*</span></label>
            <input class="form-control @error('route_name') is-invalid @enderror" id="route_name" name="route_name" placeholder="e.g. Route Name" type="text" value="{{ old('route_name', $transportRoute->route_name) }}" required>
            @error('route_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="district_id">District <span class="text-danger">*</span></label>
            <select class="form-select @error('district_id') is-invalid @enderror" id="district_id" name="district_id" data-placeholder="Select district" required>
                <option value="">Select district</option>
                @foreach ($districts as $district)
                    <option value="{{ $district->id }}" @selected((string) old('district_id', $transportRoute->district_id) === (string) $district->id)>{{ $district->name }}</option>
                @endforeach
            </select>
            @error('district_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="starting_point">Starting Point <span class="text-danger">*</span></label>
            <input class="form-control @error('starting_point') is-invalid @enderror" id="starting_point" name="starting_point" placeholder="Starting point" type="text" value="{{ old('starting_point', $transportRoute->starting_point) }}" required>
            @error('starting_point')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="ending_point">Ending Point <span class="text-danger">*</span></label>
            <input class="form-control @error('ending_point') is-invalid @enderror" id="ending_point" name="ending_point" placeholder="Ending point" type="text" value="{{ old('ending_point', $transportRoute->ending_point) }}" required>
            @error('ending_point')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="start_time">Start Time <span class="text-danger">*</span></label>
            <select class="form-select @error('start_time') is-invalid @enderror" id="start_time" name="start_time" data-placeholder="Select start time" required>
                <option value="">Select start time</option>
                @foreach ($hourOptions as $hourOption)
                    <option value="{{ $hourOption }}" @selected(($timing['start'] ?? null) === $hourOption)>{{ $hourOption }}</option>
                @endforeach
            </select>
            @error('start_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="end_time">End Time <span class="text-danger">*</span></label>
            <select class="form-select @error('end_time') is-invalid @enderror" id="end_time" name="end_time" data-placeholder="Select end time" required>
                <option value="">Select end time</option>
                @foreach ($hourOptions as $hourOption)
                    <option value="{{ $hourOption }}" @selected(($timing['end'] ?? null) === $hourOption)>{{ $hourOption }}</option>
                @endforeach
            </select>
            @error('end_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="total_distance">Total Distance (km) <span class="text-danger">*</span></label>
            <input class="form-control @error('total_distance') is-invalid @enderror" id="total_distance" name="total_distance" placeholder="e.g. 120" type="number" value="{{ old('total_distance', $transportRoute->total_distance) }}" min="1" required>
            @error('total_distance')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-12">
            <label class="form-label fw-semibold" for="remarks">Remarks</label>
            <textarea class="form-control @error('remarks') is-invalid @enderror" id="remarks" name="remarks" placeholder="Add any route remarks or operational notes." rows="4">{{ old('remarks', $transportRoute->remarks) }}</textarea>
            @error('remarks')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-12 d-flex flex-wrap gap-2">
            <button class="btn btn-success" type="submit">{{ $submitLabel }}</button>
            <a class="btn btn-outline-secondary" href="{{ route('routes.index') }}">Back</a>
        </div>
    </div>
</form>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('routeForm');

            if (!form) {
                return;
            }

            if (typeof window.appInitSelect2 === 'function') {
                window.appInitSelect2(form);
            }

            const requiredFields = Array.from(form.querySelectorAll('[required]'));

            const validateField = function (field) {
                const value = field.value.trim();
                let message = '';

                if (!value) {
                    message = 'This field is required.';
                } else if (field.name === 'total_distance' && Number(value) < 1) {
                    message = 'Total distance must be at least 1.';
                }

                field.classList.toggle('is-invalid', message !== '');

                let feedback = field.parentElement.querySelector('.client-invalid-feedback');

                if (message) {
                    if (!feedback) {
                        feedback = document.createElement('div');
                        feedback.className = 'invalid-feedback client-invalid-feedback';
                        field.parentElement.appendChild(feedback);
                    }

                    feedback.textContent = message;
                    feedback.style.display = 'block';
                } else if (feedback) {
                    feedback.remove();
                }

                return message === '';
            };

            requiredFields.forEach(function (field) {
                field.addEventListener('input', function () {
                    validateField(field);
                });

                field.addEventListener('change', function () {
                    validateField(field);
                });
            });

            form.addEventListener('submit', function (event) {
                const isValid = requiredFields.every(validateField);

                if (!isValid) {
                    event.preventDefault();
                }
            });
        });
    </script>
@endpush
