<form method="post" action="{{ $formAction }}" id="challanForm" enctype="multipart/form-data" novalidate>
    @csrf
    @if ($formMethod !== 'post')
        @method($formMethod)
    @endif
    <style>
        .challan-upload-card {
            border: 1px dashed #d8e1dd;
            border-radius: 1rem;
            background: #fbfdfc;
            padding: 1rem;
        }

        .challan-upload-preview {
            position: relative;
            display: none;
            width: min(100%, 240px);
            margin-top: 0.85rem;
        }

        .challan-upload-preview.is-visible {
            display: block;
        }

        .challan-upload-preview img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 1rem;
            border: 1px solid #dce5df;
            background: #fff;
        }

        .challan-upload-file {
            position: relative;
            display: none;
            align-items: center;
            gap: 0.8rem;
            width: min(100%, 320px);
            margin-top: 0.85rem;
            padding: 1rem;
            border-radius: 1rem;
            border: 1px solid #dce5df;
            background: #fff;
        }

        .challan-upload-file.is-visible {
            display: flex;
        }

        .challan-upload-file-icon {
            flex: 0 0 auto;
            width: 2.8rem;
            height: 2.8rem;
            border-radius: 0.9rem;
            background: #fff1f1;
            color: #cd4a4a;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }

        .challan-upload-file-name {
            margin: 0;
            color: #243245;
            font-size: 0.88rem;
            font-weight: 700;
            word-break: break-word;
        }

        .challan-upload-file-meta {
            margin: 0.2rem 0 0;
            color: #6f7d93;
            font-size: 0.78rem;
        }

        .challan-upload-remove {
            position: absolute;
            top: 0.55rem;
            right: 0.55rem;
            width: 2rem;
            height: 2rem;
            border: 0;
            border-radius: 999px;
            background: rgba(24, 32, 41, 0.78);
            color: #fff;
            font-size: 1rem;
            line-height: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .challan-upload-help {
            margin: 0.55rem 0 0;
            color: #6f7d93;
            font-size: 0.82rem;
        }
    </style>
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="challan_date">Challan Date <span class="text-danger">*</span></label>
            <input class="form-control @error('challan_date') is-invalid @enderror" id="challan_date" name="challan_date" type="date" max="{{ now()->toDateString() }}" value="{{ old('challan_date', optional($challan->challan_date)->format('Y-m-d')) }}" required>
            @error('challan_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="route_id">Route</label>
            <select class="form-select @error('route_id') is-invalid @enderror" id="route_id" name="route_id" data-placeholder="Select route" required>
                <option value="">Select route</option>
                @foreach ($routes as $route)
                    <option value="{{ $route->id }}" @selected((string) old('route_id', $challan->route_id) === (string) $route->id)>{{ $route->route_name }} ({{ $route->starting_point }} to {{ $route->ending_point }})</option>
                @endforeach
            </select>
            @error('route_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <input id="starting_point" name="starting_point" type="hidden" value="{{ old('starting_point', $challan->starting_point) }}">
        <input id="ending_point" name="ending_point" type="hidden" value="{{ old('ending_point', $challan->ending_point) }}">
        <input id="district_id" name="district_id" type="hidden" value="{{ old('district_id', $challan->district_id) }}">
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="challan_image">Challan File</label>
            <div class="challan-upload-card">
                <input class="form-control @error('challan_image') is-invalid @enderror" id="challan_image" name="challan_image" type="file" accept=".jpg,.jpeg,.png,.webp,.pdf,image/jpeg,image/png,image/webp,application/pdf">
                @error('challan_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
            

                <div class="challan-upload-preview {{ $challan->challan_image && !str_ends_with(strtolower($challan->challan_image), '.pdf') ? 'is-visible' : '' }}" id="challanImagePreview">
                    <button type="button" class="challan-upload-remove" id="challanImageRemove" aria-label="Remove image">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                    <img
                        id="challanImagePreviewTag"
                        src="{{ $challan->challan_image && !str_ends_with(strtolower($challan->challan_image), '.pdf') ? route('challans.attachment', $challan) : '' }}"
                        alt="Challan preview"
                    >
                </div>
                <div class="challan-upload-file {{ $challan->challan_image && str_ends_with(strtolower($challan->challan_image), '.pdf') ? 'is-visible' : '' }}" id="challanFilePreview">
                    <button type="button" class="challan-upload-remove" id="challanFileRemove" aria-label="Remove file">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                    <span class="challan-upload-file-icon"><i class="fa-solid fa-file-pdf"></i></span>
                    <div>
                        <p class="challan-upload-file-name" id="challanFileName">{{ $challan->challan_image ? basename($challan->challan_image) : 'Selected PDF' }}</p>
                        <p class="challan-upload-file-meta" id="challanFileMeta">{{ $challan->challan_image && str_ends_with(strtolower($challan->challan_image), '.pdf') ? 'Existing PDF file' : 'Ready to upload' }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <label class="form-label fw-semibold" for="remarks">Remarks</label>
            <textarea class="form-control @error('remarks') is-invalid @enderror" id="remarks" name="remarks" rows="4" placeholder="Add any challan remarks or notes.">{{ old('remarks', $challan->remarks) }}</textarea>
            @error('remarks')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-12 d-flex flex-wrap gap-2">
            <button class="btn btn-success" type="submit">{{ $submitLabel }}</button>
            <a class="btn btn-outline-secondary" href="{{ route('challans.index') }}">Back</a>
        </div>
    </div>
</form>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('challanForm');

            if (!form) {
                return;
            }

            if (typeof window.appInitSelect2 === 'function') {
                window.appInitSelect2(form);
            }

            const routeField = document.getElementById('route_id');
            const startingPointField = document.getElementById('starting_point');
            const endingPointField = document.getElementById('ending_point');
            const districtField = document.getElementById('district_id');
            const imageField = document.getElementById('challan_image');
            const imagePreview = document.getElementById('challanImagePreview');
            const imagePreviewTag = document.getElementById('challanImagePreviewTag');
            const imageRemoveButton = document.getElementById('challanImageRemove');
            const filePreview = document.getElementById('challanFilePreview');
            const fileName = document.getElementById('challanFileName');
            const fileMeta = document.getElementById('challanFileMeta');
            const fileRemoveButton = document.getElementById('challanFileRemove');
            let requestToken = 0;
            const existingImageUrl = imagePreviewTag ? imagePreviewTag.getAttribute('src') : '';
            const existingFileName = @json($challan->challan_image ? basename($challan->challan_image) : '');
            const existingIsPdf = @json($challan->challan_image ? str_ends_with(strtolower($challan->challan_image), '.pdf') : false);

            const syncRouteDetails = function () {
                const routeId = routeField.value;

                if (!routeId) {
                    startingPointField.value = '';
                    endingPointField.value = '';
                    districtField.value = '';
                    return;
                }

                const currentRequest = ++requestToken;

                fetch(`{{ route('challans.route-details') }}?route_id=${encodeURIComponent(routeId)}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                })
                    .then(function (response) {
                        if (!response.ok) {
                            throw new Error('Unable to load route details.');
                        }

                        return response.json();
                    })
                    .then(function (payload) {
                        if (currentRequest !== requestToken) {
                            return;
                        }

                        startingPointField.value = payload.starting_point || '';
                        endingPointField.value = payload.ending_point || '';
                        districtField.value = payload.district_id || '';
                    })
                    .catch(function () {
                        if (currentRequest !== requestToken) {
                            return;
                        }

                        startingPointField.value = '';
                        endingPointField.value = '';
                        districtField.value = '';
                    });
            };

            const setPreviewImage = function (src) {
                if (!imagePreview || !imagePreviewTag) {
                    return;
                }

                imagePreviewTag.src = src || '';
                imagePreview.classList.toggle('is-visible', Boolean(src));
            };

            const setPreviewPdf = function (name, meta) {
                if (!filePreview || !fileName || !fileMeta) {
                    return;
                }

                fileName.textContent = name || 'Selected PDF';
                fileMeta.textContent = meta || 'Ready to upload';
                filePreview.classList.toggle('is-visible', Boolean(name));
            };

            const resetImageSelection = function () {
                if (!imageField) {
                    return;
                }

                imageField.value = '';
                setPreviewImage(existingIsPdf ? '' : existingImageUrl);
                setPreviewPdf(existingIsPdf ? existingFileName : '', existingIsPdf ? 'Existing PDF file' : '');
            };

            if (imageField) {
                imageField.addEventListener('change', function (event) {
                    const [file] = event.target.files || [];

                    if (!file) {
                        setPreviewImage(existingIsPdf ? '' : existingImageUrl);
                        setPreviewPdf(existingIsPdf ? existingFileName : '', existingIsPdf ? 'Existing PDF file' : '');
                        return;
                    }

                    if (file.type === 'application/pdf') {
                        setPreviewImage('');
                        setPreviewPdf(file.name, 'PDF selected');
                        return;
                    }

                    if (!file.type.startsWith('image/')) {
                        resetImageSelection();
                        return;
                    }

                    setPreviewPdf('', '');

                    const reader = new FileReader();

                    reader.addEventListener('load', function (loadEvent) {
                        setPreviewImage(String(loadEvent.target?.result || ''));
                    });

                    reader.readAsDataURL(file);
                });
            }

            if (imageRemoveButton) {
                imageRemoveButton.addEventListener('click', resetImageSelection);
            }

            if (fileRemoveButton) {
                fileRemoveButton.addEventListener('click', resetImageSelection);
            }

            routeField.addEventListener('change', syncRouteDetails);
            syncRouteDetails();
        });
    </script>
@endpush
