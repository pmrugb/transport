<form method="post" action="{{ $formAction }}" id="tripForm" data-vehicle-details-url="{{ route('trips.vehicle-details') }}" data-route-details-url="{{ route('trips.route-details') }}" novalidate>
    @csrf
    @if ($formMethod !== 'post')
        @method($formMethod)
    @endif

    <style>
        .is-half-trip-wrap {
            margin: 0;
            padding: 0.35rem 0 0;
            justify-content: center;
        }

        .is-half-trip-wrap .form-check-input {
            width: 1.15rem;
            height: 1.15rem;
            margin-top: 0;
            margin-left: 0;
        }

        .trip-inline-action {
            font-size: 0.78rem;
            padding: 0.18rem 0.55rem;
        }

        .trip-quick-modal .modal-content {
            border-radius: 1rem;
        }

        .trip-quick-modal .modal-dialog {
            max-width: 760px;
        }

        .trip-quick-modal .modal-header,
        .trip-quick-modal .modal-footer {
            padding: 0.75rem 1rem;
        }

        .trip-quick-modal .modal-body {
            padding: 0.9rem 1rem;
            max-height: min(70vh, 640px);
            overflow-y: auto;
        }

        .trip-quick-modal .form-label {
            margin-bottom: 0.35rem;
            font-size: 0.85rem;
        }

        .trip-quick-modal .form-control,
        .trip-quick-modal .form-select {
            font-size: 0.92rem;
        }
    </style>

    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label fw-semibold" for="trip_date">Trip Date <span class="text-danger">*</span></label>
            <input class="form-control @error('trip_date') is-invalid @enderror" id="trip_date" name="trip_date" type="date" value="{{ old('trip_date', optional($trip->trip_date)->format('Y-m-d')) }}" required>
            @error('trip_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4">
            <div class="d-flex align-items-center justify-content-between gap-2">
                <label class="form-label fw-semibold mb-0" for="vehicle_id">Vehicle <span class="text-danger">*</span></label>
                <button class="btn btn-outline-success trip-inline-action" type="button" data-bs-toggle="modal" data-bs-target="#quickVehicleModal">Add Vehicle</button>
            </div>
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
        @php
            $selectedFareId = old('fare_id', $trip->fare_id);
            $selectedFare = $fares->firstWhere('id', $selectedFareId);
            $initialHalfTrip = old('is_half_trip');
            $isHalfTripChecked = $initialHalfTrip !== null
                ? (bool) $initialHalfTrip
                : ($selectedFare && (float) ($trip->fare_amount ?? 0) > 0 && abs((float) $trip->fare_amount - ((float) $selectedFare->amount / 2)) < 0.01);
        @endphp
        <div class="col-md-2">
            <label class="form-label fw-semibold" for="no_of_trips">No. of Trips <span class="text-danger">*</span></label>
            <input class="form-control @error('no_of_trips') is-invalid @enderror" id="no_of_trips" name="no_of_trips" type="number" min="1" step="1" value="{{ old('no_of_trips', $trip->no_of_trips ?? 1) }}" required>
            @error('no_of_trips')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-2">
            <label class="form-label fw-semibold" for="is_half_trip">Half trip</label>
            <div class="form-check is-half-trip-wrap">
                <input class="form-check-input @error('is_half_trip') is-invalid @enderror" id="is_half_trip" name="is_half_trip" type="checkbox" value="1" @checked($isHalfTripChecked)>
                @error('is_half_trip')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
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
            <div class="d-flex align-items-center justify-content-between gap-2">
                <label class="form-label fw-semibold mb-0" for="transporter_id">Transporter <span class="text-danger">*</span></label>
                <button class="btn btn-outline-success trip-inline-action" type="button" data-bs-toggle="modal" data-bs-target="#quickTransporterModal">Add Transporter</button>
            </div>
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
                    <option value="{{ $fare->id }}" data-amount="{{ number_format((float) $fare->amount, 2, '.', '') }}" @selected((string) old('fare_id', $trip->fare_id) === (string) $fare->id)>
                        {{ $fare->route?->route_name ?: 'Fare #'.$fare->id }} | {{ number_format((float) $fare->amount, 2) }}
                    </option>
                @endforeach
            </select>
            <input type="hidden" id="fare_id_hidden" name="fare_id" value="{{ old('fare_id', $trip->fare_id) }}">
            @error('fare_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold" for="fare_amount">Fare Amount <span class="text-danger">*</span></label>
            <input class="form-control @error('fare_amount') is-invalid @enderror" id="fare_amount" name="fare_amount" type="number" step="0.01" min="0" placeholder="0.00" value="{{ old('fare_amount', $trip->fare_amount) }}" required>
            @error('fare_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold" for="total_amount">Total Amount <span class="text-danger">*</span></label>
            <input class="form-control @error('total_amount') is-invalid @enderror" id="total_amount" name="total_amount" type="number" step="0.01" min="0" placeholder="0.00" value="{{ old('total_amount', $trip->total_amount) }}" required>
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

<div class="modal fade trip-quick-modal" id="quickTransporterModal" tabindex="-1" aria-labelledby="quickTransporterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <form id="quickTransporterForm" action="{{ route('transporters.store') }}" method="post" novalidate>
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="quickTransporterModalLabel">Add Transporter</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold" for="quick_owner_type">Owner Type <span class="text-danger">*</span></label>
                            <select class="form-select" id="quick_owner_type" name="owner_type" required>
                                @foreach ($ownerTypes as $value => $label)
                                    <option value="{{ $value }}" @selected($value === 'private')>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold" for="quick_transporter_name" id="quickTransporterNameLabel">Name <span class="text-danger">*</span></label>
                            <input class="form-control" id="quick_transporter_name" name="name" type="text" placeholder="Enter transporter name" required>
                        </div>
                        <div class="col-12" id="quickTransporterCnicWrapper">
                            <label class="form-label fw-semibold" for="quick_transporter_cnic" id="quickTransporterCnicLabel">CNIC <span class="text-danger">*</span></label>
                            <input class="form-control" id="quick_transporter_cnic" name="cnic" type="text" inputmode="numeric" maxlength="15" placeholder="12345-1234567-1">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold" for="quick_transporter_phone">Phone <span class="text-danger">*</span></label>
                            <input class="form-control" id="quick_transporter_phone" name="phone" type="text" inputmode="numeric" maxlength="12" placeholder="0312-1234567" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold" for="quick_transporter_district">District <span class="text-danger">*</span></label>
                            <select class="form-select" id="quick_transporter_district" name="district_id" required>
                                <option value="">Select district</option>
                                @foreach ($districts as $district)
                                    <option value="{{ $district->id }}" @selected(strtolower((string) $district->name) === 'gilgit')>{{ $district->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold" for="quick_transporter_address">Address <span class="text-danger">*</span></label>
                            <input class="form-control" id="quick_transporter_address" name="address" type="text" value="Gilgit" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold" for="quick_transporter_easypaisa">EasyPaisa Number</label>
                            <div class="form-check mt-2">
                                <label class="form-label" for="quick_transporter_easypaisa_same_as_phone">Same as phone number</label>
                                <input class="form-check-input" id="quick_transporter_easypaisa_same_as_phone" type="checkbox">
                            </div>
                            <input class="form-control" id="quick_transporter_easypaisa" name="easypaisa_no" type="text" inputmode="numeric" maxlength="12" placeholder="0312-1234567">
                            
                            <div class="form-text">Optional mobile wallet number for EasyPaisa transfers.</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold" for="quick_transporter_remarks">Remarks</label>
                            <textarea class="form-control" id="quick_transporter_remarks" name="remarks" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-success" type="submit">Save Transporter</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade trip-quick-modal" id="quickVehicleModal" tabindex="-1" aria-labelledby="quickVehicleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <form id="quickVehicleForm" action="{{ route('vehicles.store') }}" method="post" novalidate>
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="quickVehicleModalLabel">Add Vehicle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold" for="quick_vehicle_transporter_id">Transporter</label>
                            <select class="form-select" id="quick_vehicle_transporter_id" name="transporter_id" required>
                                <option value="">Select transporter</option>
                                @foreach ($transporters as $transporter)
                                    <option value="{{ $transporter->id }}">{{ $transporter->name }}{{ $transporter->cnic ? ' - '.$transporter->cnic : '' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <input id="quick_vehicle_status" name="status" type="hidden" value="active">
                        <div class="col-12">
                            <label class="form-label fw-semibold" for="quick_vehicle_type">Vehicle Type</label>
                            <select class="form-select" id="quick_vehicle_type" name="vehicle_type" required>
                                <option value="">Select vehicle type</option>
                                @foreach ($vehicleTypes as $vehicleType)
                                    <option value="{{ $vehicleType->id }}" @selected(strtolower((string) $vehicleType->name) === 'suzuki pick up')>{{ $vehicleType->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold" for="quick_registration_no">Registration No</label>
                            <input class="form-control" id="quick_registration_no" name="registration_no" type="text" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold" for="quick_chassis_no">Chassis No</label>
                            <input class="form-control" id="quick_chassis_no" name="chassis_no" type="text">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold" for="quick_vehicle_route_id">Route</label>
                            <select class="form-select" id="quick_vehicle_route_id" name="route_id" required>
                                <option value="">Select route</option>
                                @foreach ($routes as $route)
                                    <option value="{{ $route->id }}">{{ $route->route_name }} ({{ $route->starting_point }} → {{ $route->ending_point }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold" for="quick_vehicle_remarks">Remarks</label>
                            <textarea class="form-control" id="quick_vehicle_remarks" name="remarks" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-success" type="submit">Save Vehicle</button>
                </div>
            </form>
        </div>
    </div>
</div>

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
            const halfTripField = document.getElementById('is_half_trip');
            const driverNameField = document.getElementById('driver_name');
            const cnicField = document.getElementById('driver_cnic');
            const mobileField = document.getElementById('driver_mobile');
            const cnicRequiredMarker = document.getElementById('driver_cnic_required');
            const vehicleDetailsUrl = form.dataset.vehicleDetailsUrl;
            const routeDetailsUrl = form.dataset.routeDetailsUrl;
            const quickTransporterForm = document.getElementById('quickTransporterForm');
            const quickVehicleForm = document.getElementById('quickVehicleForm');
            const quickTransporterModalElement = document.getElementById('quickTransporterModal');
            const quickVehicleModalElement = document.getElementById('quickVehicleModal');
            const quickTransporterOwnerTypeField = document.getElementById('quick_owner_type');
            const quickTransporterCnicWrapper = document.getElementById('quickTransporterCnicWrapper');
            const quickTransporterCnicField = document.getElementById('quick_transporter_cnic');
            const quickTransporterNameField = document.getElementById('quick_transporter_name');
            const quickTransporterNameLabel = document.getElementById('quickTransporterNameLabel');
            const quickTransporterPhoneField = document.getElementById('quick_transporter_phone');
            const quickTransporterDistrictField = document.getElementById('quick_transporter_district');
            const quickTransporterAddressField = document.getElementById('quick_transporter_address');
            const quickTransporterEasypaisaField = document.getElementById('quick_transporter_easypaisa');
            const quickTransporterEasypaisaSameAsPhoneField = document.getElementById('quick_transporter_easypaisa_same_as_phone');
            const quickVehicleTransporterField = document.getElementById('quick_vehicle_transporter_id');
            let baseFareAmount = Number(fareAmountField.value || 0);

            [quickTransporterModalElement, quickVehicleModalElement].forEach(function (modalElement) {
                if (modalElement && modalElement.parentElement !== document.body) {
                    document.body.appendChild(modalElement);
                }
            });

            const initModalSelect2 = function (modalElement, fields) {
                if (!modalElement || !window.jQuery || !window.jQuery.fn || !window.jQuery.fn.select2) {
                    return;
                }

                fields.forEach(function (field) {
                    if (!field) {
                        return;
                    }

                    const select = window.jQuery(field);

                    if (select.hasClass('select2-hidden-accessible')) {
                        select.select2('destroy');
                    }

                    select.select2({
                        width: '100%',
                        dropdownParent: window.jQuery(modalElement),
                    });
                });
            };

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

            const upsertSelectOption = function (field, optionValue, optionLabel, selected, dataAttributes) {
                if (!field) {
                    return;
                }

                const normalizedValue = String(optionValue);
                let option = Array.from(field.options).find(function (item) {
                    return item.value === normalizedValue;
                });

                if (!option) {
                    option = document.createElement('option');
                    option.value = normalizedValue;
                    field.appendChild(option);
                }

                option.textContent = optionLabel;

                Object.entries(dataAttributes || {}).forEach(function (entry) {
                    option.dataset[entry[0]] = entry[1];
                });

                if (selected) {
                    field.value = normalizedValue;

                    if (window.jQuery) {
                        window.jQuery(field).trigger('change');
                    }
                }
            };

            const clearAjaxErrors = function (ajaxForm) {
                ajaxForm.querySelectorAll('.is-invalid').forEach(function (field) {
                    field.classList.remove('is-invalid');
                });

                ajaxForm.querySelectorAll('.ajax-invalid-feedback').forEach(function (feedback) {
                    feedback.remove();
                });
            };

            const showAjaxErrors = function (ajaxForm, errors) {
                Object.entries(errors || {}).forEach(function (entry) {
                    const fieldName = entry[0];
                    const messages = entry[1];
                    const field = ajaxForm.querySelector('[name="' + fieldName + '"]');

                    if (!field) {
                        return;
                    }

                    field.classList.add('is-invalid');

                    const feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback ajax-invalid-feedback d-block';
                    feedback.textContent = Array.isArray(messages) ? messages[0] : messages;
                    field.parentElement.appendChild(feedback);
                });
            };

            const submitQuickForm = function (ajaxForm, onSuccess) {
                clearAjaxErrors(ajaxForm);

                fetch(ajaxForm.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: new FormData(ajaxForm),
                }).then(function (response) {
                    if (!response.ok) {
                        return response.json().then(function (data) {
                            throw data;
                        });
                    }

                    return response.json();
                }).then(function (data) {
                    onSuccess(data);
                }).catch(function (error) {
                    if (error && error.errors) {
                        showAjaxErrors(ajaxForm, error.errors);
                        return;
                    }

                    console.error('Quick form submission failed', error);
                });
            };

            const showAppToast = function (title, message, type) {
                if (!window.bootstrap || !window.bootstrap.Toast) {
                    return;
                }

                let container = document.querySelector('.qas-toast-container[data-dynamic-toast-container]');

                if (!container) {
                    container = document.createElement('div');
                    container.className = 'toast-container qas-toast-container position-fixed top-0 end-0 p-3';
                    container.dataset.dynamicToastContainer = 'true';
                    document.body.appendChild(container);
                }

                const toastElement = document.createElement('div');
                const isError = type === 'error';

                toastElement.className = 'toast qas-toast border-0 fade hide ' + (isError ? 'qas-toast-error' : 'qas-toast-success');
                toastElement.setAttribute('role', 'alert');
                toastElement.setAttribute('aria-live', 'assertive');
                toastElement.setAttribute('aria-atomic', 'true');
                toastElement.setAttribute('data-bs-autohide', 'true');
                toastElement.setAttribute('data-bs-delay', '6000');

                toastElement.innerHTML = `
                    <div class="toast-header">
                        <span class="qas-toast-dot ${isError ? 'qas-toast-dot-error' : ''}">
                            <i class="fa-solid ${isError ? 'fa-circle-exclamation' : 'fa-check'} app-icon"></i>
                        </span>
                        <strong class="me-auto">${title}</strong>
                        <small>Now</small>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">${message}</div>
                `;

                container.appendChild(toastElement);

                const toastInstance = window.bootstrap.Toast.getOrCreateInstance(toastElement);
                toastElement.addEventListener('hidden.bs.toast', function () {
                    toastElement.remove();

                    if (!container.children.length) {
                        container.remove();
                    }
                }, { once: true });
                toastInstance.show();
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
                [
                    [routeField, routeHiddenField],
                    [transporterField, transporterHiddenField],
                    [districtField, districtHiddenField],
                    [fareField, fareHiddenField],
                ].forEach(function (pair) {
                    const field = pair[0];
                    const hiddenField = pair[1];

                    field.disabled = false;
                    field.classList.remove('bg-light');
                    hiddenField.disabled = true;
                });

                fareAmountField.readOnly = false;
                totalAmountField.readOnly = false;
                driverNameField.readOnly = false;
                cnicField.readOnly = false;
                mobileField.readOnly = false;

                [driverNameField, cnicField, mobileField, fareAmountField, totalAmountField].forEach(function (field) {
                    field.classList.remove('bg-light');
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

            const formatCompanyPhone = function (value) {
                const digits = value.replace(/\D/g, '').slice(0, 11);

                return [
                    digits.slice(0, 5),
                    digits.slice(5, 11),
                ].filter(Boolean).join('-');
            };

            initModalSelect2(quickTransporterModalElement, [
                document.getElementById('quick_owner_type'),
                document.getElementById('quick_transporter_district'),
            ]);

            initModalSelect2(quickVehicleModalElement, [
                document.getElementById('quick_vehicle_transporter_id'),
                document.getElementById('quick_vehicle_type'),
                document.getElementById('quick_vehicle_route_id'),
            ]);

            const syncFareValues = function () {
                if (!fareField.value || !baseFareAmount) {
                    return;
                }

                const fareAmount = halfTripField.checked ? (baseFareAmount / 2) : baseFareAmount;
                const tripCount = Math.max(1, Number(noOfTripsField.value || 1));

                fareAmountField.value = fareAmount.toFixed(2);
                totalAmountField.value = (fareAmount * tripCount).toFixed(2);
                totalAmountField.dataset.autoFilled = 'true';
            };

            const syncBaseFareFromSelectedFare = function () {
                const selectedOption = fareField.options[fareField.selectedIndex];
                const selectedAmount = selectedOption ? Number(selectedOption.dataset.amount || 0) : 0;

                if (selectedAmount > 0) {
                    baseFareAmount = selectedAmount;
                }
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
                        baseFareAmount = Number(data.fare_amount);
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
                        baseFareAmount = Number(data.fare_amount);
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

            const syncQuickTransporterEasypaisaWithPhone = function () {
                if (quickTransporterEasypaisaSameAsPhoneField && quickTransporterEasypaisaSameAsPhoneField.checked && quickTransporterEasypaisaField && quickTransporterPhoneField) {
                    quickTransporterEasypaisaField.value = quickTransporterPhoneField.value;
                }
            };

            if (quickTransporterOwnerTypeField && quickTransporterCnicField && quickTransporterPhoneField) {
                const syncQuickTransporterOwnerType = function () {
                    const isCompany = quickTransporterOwnerTypeField.value === 'company';

                    if (quickTransporterNameLabel) {
                        quickTransporterNameLabel.innerHTML = (isCompany ? 'Company Name' : 'Name') + ' <span class="text-danger">*</span>';
                    }

                    if (quickTransporterNameField) {
                        quickTransporterNameField.placeholder = isCompany ? 'Enter company name' : 'Enter transporter name';
                    }

                    if (quickTransporterCnicWrapper) {
                        quickTransporterCnicWrapper.classList.toggle('d-none', isCompany);
                    }

                    quickTransporterCnicField.disabled = isCompany;
                    quickTransporterCnicField.required = !isCompany;

                    if (isCompany) {
                        quickTransporterCnicField.value = '';
                        quickTransporterCnicField.classList.remove('is-invalid');
                        const feedback = quickTransporterCnicField.parentElement.querySelector('.ajax-invalid-feedback');

                        if (feedback) {
                            feedback.remove();
                        }

                        quickTransporterPhoneField.placeholder = '05811-920792';
                        quickTransporterPhoneField.value = formatCompanyPhone(quickTransporterPhoneField.value);
                    } else {
                        quickTransporterPhoneField.placeholder = '0312-1234567';
                        quickTransporterPhoneField.value = formatMobile(quickTransporterPhoneField.value);
                    }

                    syncQuickTransporterEasypaisaWithPhone();
                };

                quickTransporterOwnerTypeField.addEventListener('change', syncQuickTransporterOwnerType);
                if (window.jQuery) {
                    window.jQuery(quickTransporterOwnerTypeField).on('change.select2', syncQuickTransporterOwnerType);
                }
                quickTransporterCnicField.addEventListener('input', function () {
                    quickTransporterCnicField.value = formatCnic(quickTransporterCnicField.value);
                });
                quickTransporterPhoneField.addEventListener('input', function () {
                    quickTransporterPhoneField.value = quickTransporterOwnerTypeField.value === 'company'
                        ? formatCompanyPhone(quickTransporterPhoneField.value)
                        : formatMobile(quickTransporterPhoneField.value);
                    syncQuickTransporterEasypaisaWithPhone();
                });
                if (quickTransporterEasypaisaField) {
                    quickTransporterEasypaisaField.addEventListener('input', function () {
                        quickTransporterEasypaisaField.value = formatMobile(quickTransporterEasypaisaField.value);
                    });
                }
                if (quickTransporterEasypaisaSameAsPhoneField) {
                    quickTransporterEasypaisaSameAsPhoneField.addEventListener('change', function () {
                        if (quickTransporterEasypaisaSameAsPhoneField.checked) {
                            syncQuickTransporterEasypaisaWithPhone();
                        }
                    });
                }

                quickTransporterCnicField.value = formatCnic(quickTransporterCnicField.value);
                quickTransporterPhoneField.value = formatMobile(quickTransporterPhoneField.value);
                if (quickTransporterEasypaisaField) {
                    quickTransporterEasypaisaField.value = formatMobile(quickTransporterEasypaisaField.value);
                }
                syncQuickTransporterOwnerType();
            }

            cnicField.addEventListener('input', function () {
                cnicField.value = formatCnic(cnicField.value);
                validateField(cnicField);
            });

            mobileField.addEventListener('input', function () {
                mobileField.value = formatMobile(mobileField.value);
                validateField(mobileField);
            });

            if (quickTransporterForm) {
                quickTransporterForm.addEventListener('submit', function (event) {
                    event.preventDefault();

                    submitQuickForm(quickTransporterForm, function (data) {
                        const operator = data.operator;
                        const optionLabel = operator.name + (operator.cnic ? ' - ' + operator.cnic : '');

                        upsertSelectOption(transporterField, operator.id, operator.name, true, {
                            ownerType: operator.owner_type || '',
                        });
                        upsertSelectOption(quickVehicleTransporterField, operator.id, optionLabel, true, {});
                        updateDriverCnicState(operator.owner_type || '');
                        clearAjaxErrors(quickTransporterForm);
                        quickTransporterForm.reset();
                        quickTransporterOwnerTypeField.value = 'private';
                        quickTransporterOwnerTypeField.dispatchEvent(new Event('change'));
                        if (quickTransporterDistrictField) {
                            const defaultDistrict = Array.from(quickTransporterDistrictField.options).find(function (option) {
                                return option.defaultSelected;
                            });

                            quickTransporterDistrictField.value = defaultDistrict ? defaultDistrict.value : '';

                            if (window.jQuery) {
                                window.jQuery(quickTransporterDistrictField).trigger('change');
                            }
                        }
                        if (quickTransporterAddressField) {
                            quickTransporterAddressField.value = quickTransporterAddressField.defaultValue;
                        }
                        if (quickTransporterEasypaisaSameAsPhoneField) {
                            quickTransporterEasypaisaSameAsPhoneField.checked = false;
                        }
                        if (window.bootstrap) {
                            window.bootstrap.Modal.getOrCreateInstance(quickTransporterModalElement).hide();
                        }
                        showAppToast('Success', data.message || 'Transporter saved successfully.', 'success');
                    });
                });
            }

            if (quickVehicleForm) {
                quickVehicleForm.addEventListener('submit', function (event) {
                    event.preventDefault();

                    submitQuickForm(quickVehicleForm, function (data) {
                        const vehicle = data.vehicle;

                        upsertSelectOption(vehicleField, vehicle.id, vehicle.registration_no, true, {});
                        clearAjaxErrors(quickVehicleForm);
                        quickVehicleForm.reset();
                        document.getElementById('quick_vehicle_status').value = 'active';
                        if (window.bootstrap) {
                            window.bootstrap.Modal.getOrCreateInstance(quickVehicleModalElement).hide();
                        }
                        syncFromVehicle();
                        showAppToast('Success', data.message || 'Vehicle saved successfully.', 'success');
                    });
                });
            }

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
                    syncBaseFareFromSelectedFare();
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
                    syncBaseFareFromSelectedFare();
                    syncFareValues();
                    validateField(fareField);
                });
            }
            halfTripField.addEventListener('change', function () {
                syncFareValues();
            });
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
            syncBaseFareFromSelectedFare();
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
