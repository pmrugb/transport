@php
    $isCreateForm = ! ($vehicle->exists ?? false);
    $defaultVehicleType = $vehicleTypes->first(fn ($vehicleType) => strtolower((string) $vehicleType->name) === 'suzuki pick up');
    $selectedVehicleType = old('vehicle_type', $vehicle->vehicle_type ?: ($isCreateForm ? $defaultVehicleType?->id : null));
@endphp

<form method="post" action="{{ $formAction }}" id="vehicleForm" novalidate>
    @csrf
    @if ($formMethod !== 'post')
        @method($formMethod)
    @endif
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="transporter_id">Transporter <span class="text-danger">*</span></label>
            <select class="form-select @error('transporter_id') is-invalid @enderror" id="transporter_id" name="transporter_id" data-placeholder="Select transporter" required>
                <option value="">Select transporter</option>
                @foreach ($transporters as $transporter)
                    <option value="{{ $transporter->id }}" @selected((string) old('transporter_id', $vehicle->transporter_id) === (string) $transporter->id)>{{ $transporter->name }}@if($transporter->cnic) - {{ $transporter->cnic }}@endif</option>
                @endforeach
            </select>
            @error('transporter_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="vehicle_type">Vehicle Type <span class="text-danger">*</span></label>
            <select class="form-select @error('vehicle_type') is-invalid @enderror" id="vehicle_type" name="vehicle_type" data-placeholder="Select vehicle type" required>
                <option value="">Select vehicle type</option>
                @foreach ($vehicleTypes as $vehicleType)
                    <option value="{{ $vehicleType->id }}" @selected((string) $selectedVehicleType === (string) $vehicleType->id)>{{ $vehicleType->name }}</option>
                @endforeach
            </select>
            @error('vehicle_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="registration_no">Registration No <span class="text-danger">*</span></label>
            <input class="form-control @error('registration_no') is-invalid @enderror" id="registration_no" name="registration_no" placeholder="Enter registration number" type="text" value="{{ old('registration_no', $vehicle->registration_no) }}" required>
            @error('registration_no')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="chassis_no">Chassis No</label>
            <input class="form-control @error('chassis_no') is-invalid @enderror" id="chassis_no" name="chassis_no" placeholder="Enter chassis number" type="text" value="{{ old('chassis_no', $vehicle->chassis_no) }}">
            @error('chassis_no')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="route_id">Route <span class="text-danger">*</span></label>
            <select class="form-select @error('route_id') is-invalid @enderror" id="route_id" name="route_id" data-placeholder="Select route" required>
                <option value="">Select route</option>
                @foreach ($routes as $route)
                    <option value="{{ $route->id }}" @selected((string) old('route_id', $vehicle->route_id) === (string) $route->id)>{{ $route->route_name }} - {{ $route->starting_point }} to {{ $route->ending_point }}</option>
                @endforeach
            </select>
            @error('route_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="status">Status <span class="text-danger">*</span></label>
            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                @foreach ($statuses as $value => $label)
                    <option value="{{ $value }}" @selected(old('status', $vehicle->status ?? 'active') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-12">
            <label class="form-label fw-semibold" for="remarks">Remarks</label>
            <textarea class="form-control @error('remarks') is-invalid @enderror" id="remarks" name="remarks" placeholder="Add remarks for this vehicle." rows="4">{{ old('remarks', $vehicle->remarks) }}</textarea>
            @error('remarks')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-12 d-flex flex-wrap gap-2">
            <button class="btn btn-success" type="submit">{{ $submitLabel }}</button>
            <a class="btn btn-outline-secondary" href="{{ route('vehicles.index') }}">Back</a>
        </div>
    </div>
</form>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('vehicleForm');

            if (!form) {
                return;
            }

            if (typeof window.appInitSelect2 === 'function') {
                window.appInitSelect2(form);
            }

            const requiredFields = Array.from(form.querySelectorAll('[required]'));

            const validateField = function (field) {
                const value = field.value.trim();
                const message = value ? '' : 'This field is required.';

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
