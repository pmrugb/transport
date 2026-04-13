<form method="post" action="{{ $formAction }}" id="transporterForm" novalidate>
    @csrf
    @if ($formMethod !== 'post')
        @method($formMethod)
    @endif
    <div class="row g-3">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-1">
                <div>
                    <h3 class="section-title mb-1">Transporter Profile</h3>
                    <p class="section-copy mb-0">Add the transporter identity, contact information, and service area.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="owner_type">Owner Type <span class="text-danger">*</span></label>
            <select class="form-select @error('owner_type') is-invalid @enderror" id="owner_type" name="owner_type" required>
                @foreach ($ownerTypes as $value => $label)
                    <option value="{{ $value }}" @selected(old('owner_type', $operator->owner_type ?? 'private') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('owner_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="name" id="nameLabel">Name <span class="text-danger">*</span></label>
            <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Enter transporter name" type="text" value="{{ old('name', $operator->name) }}" required>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6" id="cnicFieldWrapper">
            <label class="form-label fw-semibold" for="cnic" id="cnicLabel">CNIC <span class="text-danger">*</span></label>
            <input class="form-control @error('cnic') is-invalid @enderror" id="cnic" name="cnic" placeholder="711111-1111111-1" type="text" inputmode="numeric" maxlength="15" value="{{ old('cnic', $operator->cnic) }}" required>
            @error('cnic')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="phone">Phone Number <span class="text-danger">*</span></label>
            <input class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" placeholder="0312-1234567" type="text" inputmode="numeric" maxlength="12" value="{{ old('phone', $operator->phone) }}" required>
            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="district_id">District <span class="text-danger">*</span></label>
            <select class="form-select @error('district_id') is-invalid @enderror" id="district_id" name="district_id" data-placeholder="Select district" required>
                <option value="">Select district</option>
                @foreach ($districts as $district)
                    <option value="{{ $district->id }}" @selected((string) old('district_id', $operator->district_id) === (string) $district->id)>{{ $district->name }}</option>
                @endforeach
            </select>
            @error('district_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="address">Address <span class="text-danger">*</span></label>
            <input class="form-control @error('address') is-invalid @enderror" id="address" name="address" placeholder="Enter address" type="text" value="{{ old('address', $operator->address) }}" required>
            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-12 pt-2">
            <div class="support-box">
                <div class="mb-3">
                    <h4 class="section-title mb-1">Payment Details</h4>
                    <p class="section-copy mb-0">Save the preferred transporter payment channels for faster and more accurate payouts.</p>
                </div>
                <div class="d-flex flex-wrap gap-2 mb-3" id="paymentMethodTabs">
                    <button class="btn btn-outline-secondary payment-method-tab" type="button" data-payment-target="easypaisa">EasyPaisa</button>
                    <button class="btn btn-outline-secondary payment-method-tab" type="button" data-payment-target="jazzcash">JazzCash</button>
                    <button class="btn btn-outline-secondary payment-method-tab" type="button" data-payment-target="bank">Bank</button>
                </div>
                <div class="row g-3">
                    <div class="col-md-6 payment-method-panel" data-payment-panel="easypaisa">
                        <label class="form-label fw-semibold" for="easypaisa_no">EasyPaisa Number</label>
                        <input class="form-control @error('easypaisa_no') is-invalid @enderror" id="easypaisa_no" name="easypaisa_no" placeholder="0312-1234567" type="text" inputmode="numeric" maxlength="12" value="{{ old('easypaisa_no', $operator->easypaisa_no) }}">
                        <div class="form-text">Optional mobile wallet number for EasyPaisa transfers.</div>
                        @error('easypaisa_no')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 payment-method-panel" data-payment-panel="jazzcash">
                        <label class="form-label fw-semibold" for="jazzcash_no">JazzCash Number</label>
                        <input class="form-control @error('jazzcash_no') is-invalid @enderror" id="jazzcash_no" name="jazzcash_no" placeholder="0300-1234567" type="text" inputmode="numeric" maxlength="12" value="{{ old('jazzcash_no', $operator->jazzcash_no) }}">
                        <div class="form-text">Optional mobile wallet number for JazzCash transfers.</div>
                        @error('jazzcash_no')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 payment-method-panel" data-payment-panel="bank">
                        <label class="form-label fw-semibold" for="bank_name">Bank Name</label>
                        <input class="form-control @error('bank_name') is-invalid @enderror" id="bank_name" name="bank_name" placeholder="Enter bank name" type="text" value="{{ old('bank_name', $operator->bank_name) }}">
                        @error('bank_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 payment-method-panel" data-payment-panel="bank">
                        <label class="form-label fw-semibold" for="bank_account_title">Account Title</label>
                        <input class="form-control @error('bank_account_title') is-invalid @enderror" id="bank_account_title" name="bank_account_title" placeholder="Enter account title" type="text" value="{{ old('bank_account_title', $operator->bank_account_title) }}">
                        @error('bank_account_title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 payment-method-panel" data-payment-panel="bank">
                        <label class="form-label fw-semibold" for="bank_account_no">Account Number</label>
                        <input class="form-control @error('bank_account_no') is-invalid @enderror" id="bank_account_no" name="bank_account_no" placeholder="Enter account number" type="text" value="{{ old('bank_account_no', $operator->bank_account_no) }}">
                        @error('bank_account_no')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <label class="form-label fw-semibold" for="remarks">Remarks</label>
            <textarea class="form-control @error('remarks') is-invalid @enderror" id="remarks" name="remarks" placeholder="Add any short details about the transporter." rows="4">{{ old('remarks', $operator->remarks) }}</textarea>
            @error('remarks')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-12 d-flex flex-wrap gap-2">
            <button class="btn btn-success" type="submit">{{ $submitLabel }}</button>
            <a class="btn btn-outline-secondary" href="{{ route('transporters.index') }}">Back</a>
        </div>
    </div>
</form>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('transporterForm');
            const cnicField = document.getElementById('cnic');
            const cnicFieldWrapper = document.getElementById('cnicFieldWrapper');
            const phoneField = document.getElementById('phone');
            const easypaisaField = document.getElementById('easypaisa_no');
            const jazzcashField = document.getElementById('jazzcash_no');
            const ownerTypeField = document.getElementById('owner_type');
            const nameField = document.getElementById('name');
            const nameLabel = document.getElementById('nameLabel');
            const districtField = document.getElementById('district_id');
            const paymentMethodTabs = Array.from(document.querySelectorAll('.payment-method-tab'));
            const paymentMethodPanels = Array.from(document.querySelectorAll('.payment-method-panel'));

            if (!form || !cnicField || !phoneField) {
                return;
            }

            if (window.jQuery && window.jQuery.fn && window.jQuery.fn.select2) {
                [
                    { element: ownerTypeField, placeholder: 'Select owner type' },
                    { element: districtField, placeholder: 'Select district' },
                ].forEach(function (item) {
                    if (!item.element) {
                        return;
                    }

                    const select = window.jQuery(item.element);

                    if (!select.hasClass('select2-hidden-accessible')) {
                        select.select2({
                            width: '100%',
                            placeholder: item.placeholder,
                            allowClear: false,
                        });
                    }
                });
            }

            const formatCnic = function (value) {
                const digits = value.replace(/\D/g, '').slice(0, 13);
                const first = digits.slice(0, 5);
                const second = digits.slice(5, 12);
                const third = digits.slice(12, 13);

                return [first, second, third].filter(Boolean).join('-');
            };

            const formatPhone = function (value) {
                const digits = value.replace(/\D/g, '').slice(0, 11);
                const first = digits.slice(0, 4);
                const second = digits.slice(4, 11);

                return [first, second].filter(Boolean).join('-');
            };

            const formatCompanyPhone = function (value) {
                const digits = value.replace(/\D/g, '').slice(0, 11);
                const first = digits.slice(0, 5);
                const second = digits.slice(5, 11);

                return [first, second].filter(Boolean).join('-');
            };

            const syncOwnerTypeFields = function () {
                const isCompany = ownerTypeField && ownerTypeField.value === 'company';

                if (nameLabel) {
                    nameLabel.innerHTML = (isCompany ? 'Company Name' : 'Name') + ' <span class="text-danger">*</span>';
                }

                if (nameField) {
                    nameField.placeholder = isCompany ? 'Enter company name' : 'Enter transporter name';
                }

                if (phoneField) {
                    phoneField.placeholder = isCompany ? '05811-920792' : '0312-1234567';
                    phoneField.value = isCompany
                        ? formatCompanyPhone(phoneField.value)
                        : formatPhone(phoneField.value);
                }

                if (cnicFieldWrapper && cnicField) {
                    cnicFieldWrapper.classList.toggle('d-none', isCompany);
                    cnicField.required = !isCompany;
                    cnicField.disabled = isCompany;

                    if (isCompany) {
                        cnicField.classList.remove('is-invalid');
                        const feedback = cnicField.parentElement.querySelector('.client-invalid-feedback');

                        if (feedback) {
                            feedback.remove();
                        }
                    }
                }
            };

            const setPaymentMethod = function (method) {
                paymentMethodTabs.forEach(function (button) {
                    const isActive = button.dataset.paymentTarget === method;
                    button.classList.toggle('btn-success', isActive);
                    button.classList.toggle('btn-outline-secondary', !isActive);
                });

                paymentMethodPanels.forEach(function (panel) {
                    const isActive = panel.dataset.paymentPanel === method;
                    panel.classList.toggle('d-none', !isActive);
                });
            };

            const validateField = function (field) {
                if (field.disabled) {
                    field.classList.remove('is-invalid');

                    const disabledFeedback = field.parentElement.querySelector('.client-invalid-feedback');

                    if (disabledFeedback) {
                        disabledFeedback.remove();
                    }

                    return true;
                }

                const value = field.value.trim();
                let message = '';

                if (field.required && !value) {
                    message = 'This field is required.';
                } else if (field.id === 'cnic' && value && !/^\d{5}-\d{7}-\d{1}$/.test(value)) {
                    message = 'CNIC must be in 12345-1234567-1 format.';
                } else if (field.id === 'phone' && ownerTypeField && ownerTypeField.value === 'company' && !/^\d{5}-\d{6}$/.test(value)) {
                    message = 'Company phone number must be in 05811-920792 format.';
                } else if (field.id === 'phone' && ownerTypeField && ownerTypeField.value !== 'company' && !/^\d{4}-\d{7}$/.test(value)) {
                    message = 'Phone number must be in 0312-1234567 format.';
                } else if ((field.id === 'easypaisa_no' || field.id === 'jazzcash_no') && value && !/^\d{4}-\d{7}$/.test(value)) {
                    message = 'Wallet number must be in 0312-1234567 format.';
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
            phoneField.value = formatPhone(phoneField.value);
            syncOwnerTypeFields();
            if (easypaisaField) {
                easypaisaField.value = formatPhone(easypaisaField.value);
            }
            if (jazzcashField) {
                jazzcashField.value = formatPhone(jazzcashField.value);
            }

            if (ownerTypeField) {
                const handleOwnerTypeChange = function () {
                    syncOwnerTypeFields();
                    validateField(cnicField);
                };

                ownerTypeField.addEventListener('change', handleOwnerTypeChange);

                if (window.jQuery) {
                    window.jQuery(ownerTypeField).on('change.select2', handleOwnerTypeChange);
                }
            }

            cnicField.addEventListener('input', function () {
                cnicField.value = formatCnic(cnicField.value);
                validateField(cnicField);
            });

            phoneField.addEventListener('input', function () {
                phoneField.value = ownerTypeField && ownerTypeField.value === 'company'
                    ? formatCompanyPhone(phoneField.value)
                    : formatPhone(phoneField.value);
                validateField(phoneField);
            });

            if (easypaisaField) {
                easypaisaField.addEventListener('input', function () {
                    easypaisaField.value = formatPhone(easypaisaField.value);
                    validateField(easypaisaField);
                });
            }

            if (jazzcashField) {
                jazzcashField.addEventListener('input', function () {
                    jazzcashField.value = formatPhone(jazzcashField.value);
                    validateField(jazzcashField);
                });
            }

            paymentMethodTabs.forEach(function (button) {
                button.addEventListener('click', function () {
                    setPaymentMethod(button.dataset.paymentTarget);
                });
            });

            if (easypaisaField && easypaisaField.value.trim() !== '') {
                setPaymentMethod('easypaisa');
            } else if (jazzcashField && jazzcashField.value.trim() !== '') {
                setPaymentMethod('jazzcash');
            } else if (document.getElementById('bank_name')?.value.trim() !== '' || document.getElementById('bank_account_title')?.value.trim() !== '' || document.getElementById('bank_account_no')?.value.trim() !== '') {
                setPaymentMethod('bank');
            } else {
                setPaymentMethod('easypaisa');
            }

            const requiredFields = Array.from(form.querySelectorAll('[required]'));
            const optionalValidatedFields = [easypaisaField, jazzcashField].filter(Boolean);
            const fieldsToValidate = requiredFields.concat(optionalValidatedFields);

            fieldsToValidate.forEach(function (field) {
                field.addEventListener('input', function () {
                    validateField(field);
                });

                field.addEventListener('change', function () {
                    validateField(field);
                });
            });

            form.addEventListener('submit', function (event) {
                const results = fieldsToValidate.map(validateField);
                const isValid = results.every(Boolean);

                if (!isValid) {
                    event.preventDefault();
                }
            });
        });
    </script>
@endpush
