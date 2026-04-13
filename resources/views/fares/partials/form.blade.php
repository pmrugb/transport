<form method="post" action="{{ $formAction }}" id="fareForm" novalidate>
    @csrf
    @if ($formMethod !== 'post')
        @method($formMethod)
    @endif
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="route_id">Route <span class="text-danger">*</span></label>
            <select class="form-select @error('route_id') is-invalid @enderror" id="route_id" name="route_id" data-placeholder="Select route" required>
                <option value="">Select route</option>
                @foreach ($routes as $route)
                    <option value="{{ $route->id }}" @selected((string) old('route_id', $fare->route_id) === (string) $route->id)>{{ $route->starting_point }} to {{ $route->ending_point }}</option>
                @endforeach
            </select>
            @error('route_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="amount">Amount <span class="text-danger">*</span></label>
            <input class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" type="number" step="0.01" min="0" placeholder="e.g. 150.00" value="{{ old('amount', $fare->amount) }}" required>
            @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="effective_from">Effective From</label>
            <input class="form-control @error('effective_from') is-invalid @enderror" id="effective_from" name="effective_from" type="text" data-flatpickr value="{{ old('effective_from', $fare->effective_from?->format('Y-m-d')) }}">
            @error('effective_from')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="effective_to">Effective To</label>
            <input class="form-control @error('effective_to') is-invalid @enderror" id="effective_to" name="effective_to" type="text" data-flatpickr value="{{ old('effective_to', $fare->effective_to?->format('Y-m-d')) }}">
            @error('effective_to')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="status">Status <span class="text-danger">*</span></label>
            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                @foreach ($statuses as $value => $label)
                    <option value="{{ $value }}" @selected(old('status', $fare->status ?? 'active') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-12">
            <label class="form-label fw-semibold" for="remarks">Remarks</label>
            <textarea class="form-control @error('remarks') is-invalid @enderror" id="remarks" name="remarks" rows="4" placeholder="Add remarks for this fare record.">{{ old('remarks', $fare->remarks) }}</textarea>
            @error('remarks')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-12 d-flex flex-wrap gap-2">
            <button class="btn btn-success" type="submit">{{ $submitLabel }}</button>
            <a class="btn btn-outline-secondary" href="{{ route('fares.index') }}">Back</a>
        </div>
    </div>
</form>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('fareForm');

            if (!form) {
                return;
            }

            if (typeof window.appInitSelect2 === 'function') {
                window.appInitSelect2(form);
            }

            if (typeof window.appInitFlatpickr === 'function') {
                window.appInitFlatpickr(form);
            }

            const requiredFields = Array.from(form.querySelectorAll('[required]'));

            const validateField = function (field) {
                const value = field.value.trim();
                let message = '';

                if (!value) {
                    message = 'This field is required.';
                } else if (field.name === 'amount' && Number(value) < 0) {
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
