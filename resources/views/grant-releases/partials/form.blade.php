<form method="post" action="{{ $formAction }}" id="grantReleaseForm" novalidate>
    @csrf
    @if ($formMethod !== 'post')
        @method($formMethod)
    @endif
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="grant_id">Grant <span class="text-danger">*</span></label>
            <select class="form-select @error('grant_id') is-invalid @enderror" id="grant_id" name="grant_id" required>
                <option value="">Select grant</option>
                @foreach ($grants as $grant)
                    <option value="{{ $grant->id }}" @selected((string) old('grant_id', $grantRelease->grant_id) === (string) $grant->id)>{{ $grant->title }}</option>
                @endforeach
            </select>
            @error('grant_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="release_amount">Release Amount <span class="text-danger">*</span></label>
            <input class="form-control @error('release_amount') is-invalid @enderror" id="release_amount" name="release_amount" type="number" step="0.01" min="0" value="{{ old('release_amount', $grantRelease->release_amount) }}" placeholder="e.g. 500000.00" required>
            @error('release_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="release_date">Release Date <span class="text-danger">*</span></label>
            <input class="form-control @error('release_date') is-invalid @enderror" id="release_date" name="release_date" type="date" value="{{ old('release_date', $grantRelease->release_date?->format('Y-m-d')) }}" required>
            @error('release_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="installment_no">Installment No <span class="text-danger">*</span></label>
            <input class="form-control @error('installment_no') is-invalid @enderror" id="installment_no" name="installment_no" type="number" min="1" value="{{ old('installment_no', $grantRelease->installment_no) }}" placeholder="e.g. 1" required>
            @error('installment_no')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="released_by">Released By <span class="text-danger">*</span></label>
            <select class="form-select @error('released_by') is-invalid @enderror" id="released_by" name="released_by" required>
                <option value="">Select department</option>
                @foreach ($departments as $department)
                    <option value="{{ $department->name }}" @selected(old('released_by', $grantRelease->released_by) === $department->name)>{{ $department->name }}</option>
                @endforeach
            </select>
            @error('released_by')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-12">
            <label class="form-label fw-semibold" for="remarks">Remarks</label>
            <textarea class="form-control @error('remarks') is-invalid @enderror" id="remarks" name="remarks" rows="4" placeholder="Add remarks for this release.">{{ old('remarks', $grantRelease->remarks) }}</textarea>
            @error('remarks')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-12 d-flex flex-wrap gap-2">
            <button class="btn btn-success" type="submit">{{ $submitLabel }}</button>
            <a class="btn btn-outline-secondary" href="{{ route('grant-releases.index') }}">Back</a>
        </div>
    </div>
</form>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('grantReleaseForm');

            if (!form) {
                return;
            }

            const requiredFields = Array.from(form.querySelectorAll('[required]'));

            const validateField = function (field) {
                const value = field.value.trim();
                let message = '';

                if (!value) {
                    message = 'This field is required.';
                } else if (field.name === 'release_amount' && Number(value) < 0) {
                    message = 'Release amount cannot be negative.';
                } else if (field.name === 'installment_no' && Number(value) < 1) {
                    message = 'Installment number must be at least 1.';
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
