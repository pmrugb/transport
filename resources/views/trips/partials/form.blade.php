<form method="post" action="{{ $formAction }}" id="tripForm" data-vehicle-details-url="{{ route('trips.vehicle-details') }}" data-route-details-url="{{ route('trips.route-details') }}" novalidate>
    @csrf
    @if ($formMethod !== 'post')
        @method($formMethod)
    @endif

    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label fw-semibold" for="trip_date">Trip Date <span class="text-danger">*</span></label>
            <input class="form-control @error('trip_date') is-invalid @enderror" id="trip_date" name="trip_date" type="date" value="{{ old('trip_date', optional($trip->trip_date)->format('Y-m-d')) }}" required>
            @error('trip_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold" for="vehicle_id">Vehicle <span class="text-danger">*</span></label>
            <select class="form-select @error('vehicle_id') is-invalid @enderror" id="vehicle_id" name="vehicle_id" data-placeholder="Select vehicle" required>
                <option value="">Select vehicle</option>
                @foreach ($vehicles as $vehicle)
                    <option value="{{ $vehicle->id }}" @selected((string) old('vehicle_id', $trip->vehicle_id) === (string) $vehicle->id)>
                        {{ $vehicle->registration_no }}
                        
                    </option>
                @endforeach
            </select>
            @error('vehicle_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold" for="no_of_trips">No. of Trips <span class="text-danger">*</span></label>
            <input class="form-control @error('no_of_trips') is-invalid @enderror" id="no_of_trips" name="no_of_trips" type="number" min="1" step="1" value="{{ old('no_of_trips', $trip->no_of_trips ?? 1) }}" required>
            @error('no_of_trips')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold" for="route_id">Route <span class="text-danger">*</span></label>
            <select class="form-select @error('route_id') is-invalid @enderror" id="route_id" name="route_id" data-placeholder="Select route" required>
                <option value="">Select route</option>
                @foreach ($routes as $route)
                    <option value="{{ $route->id }}" @selected((string) old('route_id', $trip->route_id) === (string) $route->id)>
                        {{ $route->route_name }} ({{ $route->starting_point }} → {{ $route->ending_point }})
                    </option>
                @endforeach
            </select>
            <input type="hidden" id="route_id_hidden" name="route_id" value="{{ old('route_id', $trip->route_id) }}">
            @error('route_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold" for="transporter_id">Transporter <span class="text-danger">*</span></label>
            <select class="form-select @error('transporter_id') is-invalid @enderror" id="transporter_id" name="transporter_id" data-placeholder="Select transporter" required>
                <option value="">Select transporter</option>
                @foreach ($transporters as $transporter)
                    <option value="{{ $transporter->id }}" data-owner-type="{{ $transporter->owner_type }}" @selected((string) old('transporter_id', $trip->transporter_id) === (string) $transporter->id)>
                        {{ $transporter->name }}
                    </option>
                @endforeach
            </select>
            <input type="hidden" id="transporter_id_hidden" name="transporter_id" value="{{ old('transporter_id', $trip->transporter_id) }}">
            @error('transporter_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-4">
            <label class="form-label fw-semibold" for="district_id">District <span class="text-danger">*</span></label>
            <select class="form-select @error('district_id') is-invalid @enderror" id="district_id" name="district_id" data-placeholder="Select district" required>
                <option value="">Select district</option>
                @foreach ($districts as $district)
                    <option value="{{ $district->id }}" @selected((string) old('district_id', $trip->district_id) === (string) $district->id)>
                        {{ $district->name }}
                    </option>
                @endforeach
            </select>
            <input type="hidden" id="district_id_hidden" name="district_id" value="{{ old('district_id', $trip->district_id) }}">
            @error('district_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-4">
            <label class="form-label fw-semibold" for="driver_name">Driver Name <span class="text-danger">*</span></label>
            <input class="form-control @error('driver_name') is-invalid @enderror" id="driver_name" name="driver_name" type="text" placeholder="Enter driver name" value="{{ old('driver_name', $trip->driver_name) }}" required>
            @error('driver_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold" for="driver_cnic">Driver CNIC <span class="text-danger" id="driver_cnic_required">*</span></label>
            <input class="form-control @error('driver_cnic') is-invalid @enderror" id="driver_cnic" name="driver_cnic" type="text" inputmode="numeric" maxlength="15" placeholder="12345-1234567-1" value="{{ old('driver_cnic', $trip->driver_cnic) }}" required>
            @error('driver_cnic')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold" for="driver_mobile">Driver Mobile <span class="text-danger">*</span></label>
            <input class="form-control @error('driver_mobile') is-invalid @enderror" id="driver_mobile" name="driver_mobile" type="text" inputmode="numeric" maxlength="12" placeholder="0312-1234567" value="{{ old('driver_mobile', $trip->driver_mobile) }}" required>
            @error('driver_mobile')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-4">
            <label class="form-label fw-semibold" for="fare_id">Fare <span class="text-danger">*</span></label>
            <select class="form-select @error('fare_id') is-invalid @enderror" id="fare_id" name="fare_id" data-placeholder="Select fare" required>
                <option value="">Select fare</option>
                @foreach ($fares as $fare)
                    <option value="{{ $fare->id }}" @selected((string) old('fare_id', $trip->fare_id) === (string) $fare->id)>
                        {{ $fare->route?->route_name ?: 'Fare #'.$fare->id }} | {{ number_format((float) $fare->amount, 2) }}
                    </option>
                @endforeach
            </select>
            <input type="hidden" id="fare_id_hidden" name="fare_id" value="{{ old('fare_id', $trip->fare_id) }}">
            @error('fare_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold" for="fare_amount">Fare Amount <span class="text-danger">*</span></label>
            <input class="form-control @error('fare_amount') is-invalid @enderror" id="fare_amount" name="fare_amount" type="number" step="0.01" min="0" placeholder="0.00" value="{{ old('fare_amount', $trip->fare_amount) }}" required readonly>
            @error('fare_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold" for="total_amount">Total Amount <span class="text-danger">*</span></label>
            <input class="form-control @error('total_amount') is-invalid @enderror" id="total_amount" name="total_amount" type="number" step="0.01" min="0" placeholder="0.00" value="{{ old('total_amount', $trip->total_amount) }}" required readonly>
            @error('total_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-4">
            <label class="form-label fw-semibold" for="status">Status <span class="text-danger">*</span></label>
            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                @foreach ($statuses as $value => $label)
                    <option value="{{ $value }}" @selected(old('status', $trip->status ?? 'active') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-12">
            <label class="form-label fw-semibold" for="remarks">Remarks</label>
            <textarea class="form-control @error('remarks') is-invalid @enderror" id="remarks" name="remarks" rows="4" placeholder="Add operational notes or remarks for this trip.">{{ old('remarks', $trip->remarks) }}</textarea>
            @error('remarks')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-12 d-flex flex-wrap gap-2">
            <button class="btn btn-success" type="submit">{{ $submitLabel }}</button>
            <a class="btn btn-outline-secondary" href="{{ route('trips.index') }}">Back</a>
        </div>
    </div>
</form>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('tripForm');

            if (!form) {
                return;
            }

            const vehicleField = document.getElementById('vehicle_id');
            const routeField = document.getElementById('route_id');
            const transporterField = document.getElementById('transporter_id');
            const districtField = document.getElementById('district_id');
            const fareField = document.getElementById('fare_id');
            const routeHiddenField = document.getElementById('route_id_hidden');
            const transporterHiddenField = document.getElementById('transporter_id_hidden');
            const districtHiddenField = document.getElementById('district_id_hidden');
            const fareHiddenField = document.getElementById('fare_id_hidden');
            const fareAmountField = document.getElementById('fare_amount');
            const noOfTripsField = document.getElementById('no_of_trips');
            const totalAmountField = document.getElementById('total_amount');
            const driverNameField = document.getElementById('driver_name');
            const cnicField = document.getElementById('driver_cnic');
            const mobileField = document.getElementById('driver_mobile');
            const cnicRequiredMarker = document.getElementById('driver_cnic_required');
            const vehicleDetailsUrl = form.dataset.vehicleDetailsUrl;
            const routeDetailsUrl = form.dataset.routeDetailsUrl;

            const setSelectValue = function (field, value) {
                if (!field) {
                    return;
                }

                const normalizedValue = value === undefined || value === null ? '' : String(value);
                field.value = normalizedValue;

                if (window.jQuery) {
                    window.jQuery(field).trigger('change');
                }
            };

            const syncHiddenFields = function () {
                routeHiddenField.value = routeField.value;
                transporterHiddenField.value = transporterField.value;
                districtHiddenField.value = districtField.value;
                fareHiddenField.value = fareField.value;
            };

            const getSelectedTransporterOwnerType = function () {
                const selectedOption = transporterField.options[transporterField.selectedIndex];

                return selectedOption ? selectedOption.dataset.ownerType || '' : '';
            };

            const updateDriverCnicState = function (ownerType) {
                const isCompany = ownerType === 'company';

                cnicField.required = !isCompany;
                cnicField.placeholder = isCompany ? 'Optional for transport company' : '12345-1234567-1';

                if (cnicRequiredMarker) {
                    cnicRequiredMarker.classList.toggle('d-none', isCompany);
                }

                if (isCompany && !cnicField.value.trim()) {
                    cnicField.classList.remove('is-invalid');

                    const feedback = cnicField.parentElement.querySelector('.client-invalid-feedback');

                    if (feedback) {
                        feedback.remove();
                    }
                }
            };

            const setAutoFilledFieldState = function () {
                const isLocked = !!vehicleField.value;

                [
                    [routeField, routeHiddenField],
                    [transporterField, transporterHiddenField],
                    [districtField, districtHiddenField],
                    [fareField, fareHiddenField],
                ].forEach(function (pair) {
                    const field = pair[0];
                    const hiddenField = pair[1];

                    field.disabled = isLocked;
                    field.classList.toggle('bg-light', isLocked);
                    hiddenField.disabled = !isLocked;
                });

                fareAmountField.readOnly = true;
                totalAmountField.readOnly = true;
                driverNameField.readOnly = isLocked;
                cnicField.readOnly = isLocked;
                mobileField.readOnly = isLocked;

                [driverNameField, cnicField, mobileField, fareAmountField, totalAmountField].forEach(function (field) {
                    field.classList.toggle('bg-light', isLocked);
                });

                syncHiddenFields();
            };

            const requestJson = function (url, params, onSuccess) {
                if (!window.jQuery) {
                    return;
                }

                window.jQuery.ajax({
                    url: url,
                    method: 'GET',
                    data: params,
                    dataType: 'json',
                    success: onSuccess,
                    error: function (xhr) {
                        console.error('Trip Ajax failed', xhr);
                    },
                });
            };

            const formatCnic = function (value) {
                const digits = value.replace(/\D/g, '').slice(0, 13);

                return [
                    digits.slice(0, 5),
                    digits.slice(5, 12),
                    digits.slice(12, 13),
                ].filter(Boolean).join('-');
            };

            const formatMobile = function (value) {
                const digits = value.replace(/\D/g, '').slice(0, 11);

                return [
                    digits.slice(0, 4),
                    digits.slice(4, 11),
                ].filter(Boolean).join('-');
            };

            const syncFareValues = function () {
                if (!fareField.value || !fareAmountField.value) {
                    return;
                }

                const fareAmount = Number(fareAmountField.value || 0);
                const tripCount = Math.max(1, Number(noOfTripsField.value || 1));

                totalAmountField.value = (fareAmount * tripCount).toFixed(2);
                totalAmountField.dataset.autoFilled = 'true';
            };

            const syncFromRoute = function () {
                if (!routeField.value || !routeDetailsUrl) {
                    return;
                }

                requestJson(routeDetailsUrl, { route_id: routeField.value }, function (data) {
                    districtField.value = data.district_id ? String(data.district_id) : '';
                    fareField.value = data.fare_id ? String(data.fare_id) : '';

                    if (window.jQuery) {
                        window.jQuery(districtField).trigger('change.select2');
                        window.jQuery(fareField).trigger('change.select2');
                    }

                    if (data.fare_amount !== null && data.fare_amount !== undefined) {
                        fareAmountField.value = Number(data.fare_amount).toFixed(2);
                    }

                    syncFareValues();
                    syncHiddenFields();
                });
            };

            const syncFromVehicle = function () {
                if (!vehicleField.value || !vehicleDetailsUrl) {
                    return;
                }

                requestJson(vehicleDetailsUrl, { vehicle_id: vehicleField.value }, function (data) {
                    routeField.value = data.route_id ? String(data.route_id) : '';
                    transporterField.value = data.transporter_id ? String(data.transporter_id) : '';
                    districtField.value = data.district_id ? String(data.district_id) : '';
                    fareField.value = data.fare_id ? String(data.fare_id) : '';

                    if (window.jQuery) {
                        window.jQuery(routeField).trigger('change.select2');
                        window.jQuery(transporterField).trigger('change.select2');
                        window.jQuery(districtField).trigger('change.select2');
                        window.jQuery(fareField).trigger('change.select2');
                    }

                    if (data.fare_amount !== null && data.fare_amount !== undefined) {
                        fareAmountField.value = Number(data.fare_amount).toFixed(2);
                    }

                    if (driverNameField) {
                        driverNameField.value = data.driver_name || '';
                    }

                    if (cnicField) {
                        cnicField.value = formatCnic(data.driver_cnic || '');
                    }

                    if (mobileField) {
                        mobileField.value = formatMobile(data.driver_mobile || '');
                    }

                    updateDriverCnicState(data.transporter_owner_type || getSelectedTransporterOwnerType());
                    syncFareValues();
                    setAutoFilledFieldState();
                });
            };

            const validateField = function (field) {
                const value = field.value.trim();
                let message = '';

                if (field.required && !value) {
                    message = 'This field is required.';
                } else if (field.id === 'driver_cnic' && value && !/^\d{5}-\d{7}-\d{1}$/.test(value)) {
                    message = 'CNIC must be in 12345-1234567-1 format.';
                } else if (field.id === 'driver_mobile' && value && !/^\d{4}-\d{7}$/.test(value)) {
                    message = 'Mobile must be in 0312-1234567 format.';
                } else if (field.id === 'no_of_trips' && value && Number(value) < 1) {
                    message = 'Trips must be at least 1.';
                } else if ((field.id === 'fare_amount' || field.id === 'total_amount') && value && Number(value) < 0) {
                    message = 'Amount cannot be negative.';
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

            cnicField.value = formatCnic(cnicField.value);
            mobileField.value = formatMobile(mobileField.value);

            cnicField.addEventListener('input', function () {
                cnicField.value = formatCnic(cnicField.value);
                validateField(cnicField);
            });

            mobileField.addEventListener('input', function () {
                mobileField.value = formatMobile(mobileField.value);
                validateField(mobileField);
            });

            if (window.jQuery) {
                window.jQuery(routeField).on('change', function () {
                    syncFromRoute();
                    validateField(routeField);
                });

                window.jQuery(vehicleField).on('change', function () {
                    syncFromVehicle();
                    validateField(vehicleField);
                });

                window.jQuery(transporterField).on('change', function () {
                    updateDriverCnicState(getSelectedTransporterOwnerType());
                    validateField(transporterField);
                });

                window.jQuery(fareField).on('change', function () {
                    syncFareValues();
                    validateField(fareField);
                });
            } else {
                routeField.addEventListener('change', function () {
                    syncFromRoute();
                    validateField(routeField);
                });

                vehicleField.addEventListener('change', function () {
                    syncFromVehicle();
                    validateField(vehicleField);
                });

                transporterField.addEventListener('change', function () {
                    updateDriverCnicState(getSelectedTransporterOwnerType());
                    validateField(transporterField);
                });

                fareField.addEventListener('change', function () {
                    syncFareValues();
                    validateField(fareField);
                });
            }
            totalAmountField.addEventListener('input', function () {
                totalAmountField.dataset.autoFilled = 'false';
                validateField(totalAmountField);
            });
            noOfTripsField.addEventListener('input', function () {
                syncFareValues();
                validateField(noOfTripsField);
            });

            const requiredFields = Array.from(form.querySelectorAll('[required]'));

            requiredFields.forEach(function (field) {
                field.addEventListener('input', function () {
                    validateField(field);
                });

                field.addEventListener('change', function () {
                    validateField(field);
                });
            });

            if (vehicleField.value) {
                syncFromVehicle();
            } else if (routeField.value) {
                syncFromRoute();
            }

            setAutoFilledFieldState();
            updateDriverCnicState(getSelectedTransporterOwnerType());
            syncFareValues();
            syncHiddenFields();

            form.addEventListener('submit', function (event) {
                syncHiddenFields();
                const isValid = requiredFields.every(validateField);

                if (!isValid) {
                    event.preventDefault();
                }
            });
        });
    </script>
@endpush
