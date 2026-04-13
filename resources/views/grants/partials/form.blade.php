<form method="post" action="{{ $formAction }}" id="grantForm" novalidate>
    @csrf
    @if ($formMethod !== 'post')
        @method($formMethod)
    @endif
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="title">Title <span class="text-danger">*</span></label>
            <input class="form-control @error('title') is-invalid @enderror" id="title" name="title" type="text" value="{{ old('title', $grant->title) }}" placeholder="Enter grant title" required>
            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="total_amount">Total Amount <span class="text-danger">*</span></label>
            <input class="form-control @error('total_amount') is-invalid @enderror" id="total_amount" name="total_amount" type="number" step="0.01" min="0" value="{{ old('total_amount', $grant->total_amount) }}" placeholder="e.g. 5000000.00" required>
            @error('total_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="financial_year">Financial Year <span class="text-danger">*</span></label>
            <select class="form-select @error('financial_year') is-invalid @enderror" id="financial_year" name="financial_year" required>
                <option value="">Select financial year</option>
                @foreach ($financialYearOptions as $financialYearOption)
                    <option value="{{ $financialYearOption }}" @selected(old('financial_year', $grant->financial_year) === $financialYearOption)>{{ $financialYearOption }}</option>
                @endforeach
            </select>
            @error('financial_year')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="district_id">District</label>
            <select class="form-select @error('district_id') is-invalid @enderror" id="district_id" name="district_id">
                <option value="">Select district</option>
                @foreach ($districts as $district)
                    <option value="{{ $district->id }}" @selected((string) old('district_id', $grant->district_id) === (string) $district->id)>{{ $district->name }}</option>
                @endforeach
            </select>
            @error('district_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="start_date">Start Date</label>
            <input class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" type="date" value="{{ old('start_date', $grant->start_date?->format('Y-m-d')) }}">
            @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="end_date">End Date</label>
            <input class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" type="date" value="{{ old('end_date', $grant->end_date?->format('Y-m-d')) }}">
            @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="status">Status <span class="text-danger">*</span></label>
            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                @foreach ($statuses as $value => $label)
                    <option value="{{ $value }}" @selected(old('status', $grant->status ?? 'active') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-12">
            <label class="form-label fw-semibold" for="remarks">Remarks</label>
            <textarea class="form-control @error('remarks') is-invalid @enderror" id="remarks" name="remarks" rows="4" placeholder="Add remarks for this grant.">{{ old('remarks', $grant->remarks) }}</textarea>
            @error('remarks')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-12 d-flex flex-wrap gap-2">
            <button class="btn btn-success" type="submit">{{ $submitLabel }}</button>
            <a class="btn btn-outline-secondary" href="{{ route('grants.index') }}">Back</a>
        </div>
    </div>
</form>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('grantForm');

            if (!form) {
                return;
            }

            const requiredFields = Array.from(form.querySelectorAll('[required]'));

            const validateField = function (field) {
                const value = field.value.trim();
                let message = '';

                if (!value) {
                    message = 'This field is required.';
                } else if (field.name === 'total_amount' && Number(value) < 0) {
                    message = 'Total amount cannot be negative.';
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
                const results = requiredFields.map(validateField);
                const isValid = results.every(Boolean);

                if (!isValid) {
                    event.preventDefault();
                }
            });
        });
    </script>
@endpush
