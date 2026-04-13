@extends('layouts.app', ['title' => 'All Challans | Free Public Transport System', 'pageBadge' => 'Challan Management'])

@section('content')
    <style>
        .challan-lightbox-image {
            width: 100%;
            max-height: 72vh;
            object-fit: contain;
            border-radius: 1rem;
            background: #f8faf9;
            border: 1px solid #e0e7e2;
            transform-origin: center center;
            transition: transform 0.18s ease;
        }

        .challan-view-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.38rem;
            border: 1px solid #cfe2d6;
            border-radius: 999px;
            background: #f3faf5;
            color: #3f8f5d;
            padding: 0.36rem 0.72rem;
            font-size: 0.76rem;
            font-weight: 700;
            line-height: 1;
            transition: all 0.18s ease;
        }

        .challan-view-btn:hover {
            background: #3f8f5d;
            border-color: #3f8f5d;
            color: #fff;
        }

        .challan-lightbox-pdf {
            display: none;
            width: 100%;
            height: 72vh;
            border: 1px solid #e0e7e2;
            border-radius: 1rem;
            background: #fff;
        }

        .challan-lightbox-pdf.is-visible {
            display: block;
        }

        .challan-lightbox-image.is-hidden {
            display: none;
        }

        .challan-lightbox-toolbar {
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
            margin-bottom: 0.9rem;
        }

        .challan-zoom-btn {
            width: 2.4rem;
            height: 2.4rem;
            border: 1px solid #d6e2da;
            border-radius: 999px;
            background: #f8fbf9;
            color: #355845;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
        }

        .challan-zoom-btn:hover,
        .challan-zoom-btn:focus {
            background: #edf6f0;
            color: #244535;
        }
    </style>

    <div class="page-hero d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
        <div>
            <p class="page-eyebrow">Challan Management</p>
            <h1 class="page-title">All Challans</h1>
            <p class="page-subtitle">Review challan records with route, district, image, and remarks information.</p>
        </div>
    </div>

    <section class="row g-4 stats-overlap">
        <div class="col-sm-6 col-xl-4"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">Total Challans</p><h2 class="stat-value">{{ $stats['total'] }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-file-lines app-icon"></i></span></div><p class="stat-note">All challan records currently stored in the system.</p></div></div></div>
        <div class="col-sm-6 col-xl-4"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">Today</p><h2 class="stat-value">{{ $stats['today'] }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-calendar-day app-icon"></i></span></div><p class="stat-note">Challans recorded for today.</p></div></div></div>
        <div class="col-sm-6 col-xl-4"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">Covered Districts</p><h2 class="stat-value">{{ $stats['districts'] }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-map-location-dot app-icon"></i></span></div><p class="stat-note">Districts linked with challan entries.</p></div></div></div>
    </section>

    <section class="card section-card table-card mb-4">
        <div class="card-header">
            <div class="table-toolbar">
                <div>
                    <h3 class="section-title">Challan Records</h3>
                    <p class="section-copy">Complete listing of all challans added in the system.</p>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-shell table-wrap">
                <table class="table table-app align-middle">
                    <thead>
                        <tr>
                            <th>Sr #</th>
                            <th>Challan Date</th>
                            <th>Route</th>
                            <th>Starting Point</th>
                            <th>Ending Point</th>
                            <th>District</th>
                            <th>Image/PDF</th>
                            @if ($canManageChallans)
                                <th class="text-center">Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($challans as $challan)
                            <tr>
                                <td class="text-nowrap">{{ $challans->firstItem() + $loop->index }}</td>
                                <td class="text-nowrap">{{ optional($challan->challan_date)->format('d-m-Y') }}</td>
                                <td class="fw-semibold text-nowrap">{{ $challan->route?->route_name ?: 'N/A' }}</td>
                                <td class="text-nowrap">{{ $challan->starting_point }}</td>
                                <td class="text-nowrap">{{ $challan->ending_point }}</td>
                                <td class="text-nowrap">{{ $challan->district?->name ?: 'N/A' }}</td>
                                <td class="text-nowrap">
                                    @if ($challan->challan_image)
                                        <button
                                            type="button"
                                            class="challan-view-btn"
                                            data-bs-toggle="modal"
                                            data-bs-target="#challanImageModal"
                                            data-challan-image="{{ asset('storage/'.$challan->challan_image) }}"
                                            data-challan-title="Challan #{{ $challan->id }}"
                                            data-challan-type="{{ str_ends_with(strtolower($challan->challan_image), '.pdf') ? 'pdf' : 'image' }}"
                                        >
                                            <i class="fa-solid {{ str_ends_with(strtolower($challan->challan_image), '.pdf') ? 'fa-file-pdf' : 'fa-image' }}"></i>
                                            {{ str_ends_with(strtolower($challan->challan_image), '.pdf') ? 'View PDF' : 'View Image' }}
                                        </button>
                                    @else
                                        <span class="text-muted">No Image</span>
                                    @endif
                                </td>
                                @if ($canManageChallans)
                                    <td class="text-center text-nowrap">
                                        <div class="action-stack justify-content-center">
                                            <a href="{{ route('challans.show', $challan) }}" class="action-btn btn-view" title="View Challan">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                            <a href="{{ route('challans.edit', $challan) }}" class="action-btn btn-edit" title="Edit Challan">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <form action="{{ route('challans.destroy', $challan) }}" method="POST" class="d-inline" data-confirm-delete data-delete-message="Are you sure you want to delete this challan record?">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="action-btn btn-vacate border-0" title="Delete Challan">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr><td colspan="{{ $canManageChallans ? 8 : 7 }}" class="text-center text-muted py-4">No challans found yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @include('settings.partials.pagination', ['paginator' => $challans, 'perPage' => $perPage])
        </div>
    </section>

    <div class="modal fade" id="challanImageModal" tabindex="-1" aria-labelledby="challanImageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="challanImageModalLabel">Challan Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="challan-lightbox-toolbar">
                        <button type="button" class="challan-zoom-btn" id="challanZoomOutBtn" title="Zoom out" aria-label="Zoom out">
                            <i class="fa-solid fa-magnifying-glass-minus"></i>
                        </button>
                        <button type="button" class="challan-zoom-btn" id="challanZoomInBtn" title="Zoom in" aria-label="Zoom in">
                            <i class="fa-solid fa-magnifying-glass-plus"></i>
                        </button>
                    </div>
                    <img src="" alt="Challan preview" id="challanImageModalPreview" class="challan-lightbox-image">
                    <iframe src="" title="Challan PDF" id="challanPdfModalPreview" class="challan-lightbox-pdf"></iframe>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('challanImageModal');

            if (!modal) {
                return;
            }

            const preview = document.getElementById('challanImageModalPreview');
            const pdfPreview = document.getElementById('challanPdfModalPreview');
            const title = document.getElementById('challanImageModalLabel');
            const zoomInButton = document.getElementById('challanZoomInBtn');
            const zoomOutButton = document.getElementById('challanZoomOutBtn');
            let zoomLevel = 1;

            const applyZoom = function () {
                preview.style.transform = `scale(${zoomLevel})`;
            };

            const setZoomControlsVisibility = function (showControls) {
                if (!zoomInButton || !zoomOutButton) {
                    return;
                }

                zoomInButton.classList.toggle('d-none', !showControls);
                zoomOutButton.classList.toggle('d-none', !showControls);
            };

            if (zoomInButton) {
                zoomInButton.addEventListener('click', function () {
                    zoomLevel = Math.min(3, Number((zoomLevel + 0.25).toFixed(2)));
                    applyZoom();
                });
            }

            if (zoomOutButton) {
                zoomOutButton.addEventListener('click', function () {
                    zoomLevel = Math.max(0.5, Number((zoomLevel - 0.25).toFixed(2)));
                    applyZoom();
                });
            }

            modal.addEventListener('show.bs.modal', function (event) {
                const trigger = event.relatedTarget;

                if (!trigger) {
                    return;
                }

                const fileUrl = trigger.getAttribute('data-challan-image') || '';
                const fileType = trigger.getAttribute('data-challan-type') || 'image';
                zoomLevel = 1;
                applyZoom();

                if (fileType === 'pdf') {
                    preview.classList.add('is-hidden');
                    pdfPreview.classList.add('is-visible');
                    pdfPreview.src = fileUrl;
                    preview.src = '';
                    setZoomControlsVisibility(false);
                } else {
                    preview.classList.remove('is-hidden');
                    pdfPreview.classList.remove('is-visible');
                    preview.src = fileUrl;
                    pdfPreview.src = '';
                    setZoomControlsVisibility(true);
                }

                title.textContent = trigger.getAttribute('data-challan-title') || 'Challan Image';
            });

            modal.addEventListener('hidden.bs.modal', function () {
                zoomLevel = 1;
                applyZoom();
                preview.src = '';
                preview.classList.remove('is-hidden');
                pdfPreview.src = '';
                pdfPreview.classList.remove('is-visible');
                title.textContent = 'Challan Image';
                setZoomControlsVisibility(true);
            });
        });
    </script>
@endpush
