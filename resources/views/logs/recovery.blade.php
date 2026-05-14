@extends('layouts.app', ['title' => 'Recovery | Free Public Transport System', 'pageBadge' => 'Logs'])

@section('content')
    <style>
        .recovery-page .page-hero {
            margin-top: -1.6rem;
            margin-left: -1.5rem;
            margin-right: -1.5rem;
            padding: 2.9rem 2.5rem 6.4rem;
            border-radius: 0;
            background: linear-gradient(90deg, #153f2b 0%, #2d6b48 100%);
        }

        .recovery-page .page-eyebrow,
        .recovery-page .page-title,
        .recovery-page .page-subtitle {
            color: #fff;
        }

        .recovery-page .page-eyebrow {
            letter-spacing: 0.18em;
            opacity: 0.82;
        }

        .recovery-page .page-subtitle {
            max-width: 760px;
            color: rgba(255, 255, 255, 0.82);
        }

        .recovery-stat-card {
            border: 0;
            border-radius: 1.7rem;
            box-shadow: 0 22px 48px rgba(27, 44, 82, 0.12);
        }

        .recovery-stat-card .card-body {
            padding: 1.45rem 1.45rem 1.35rem;
        }

        .recovery-stat-card .stat-card-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
        }

        .recovery-stat-card .stat-label {
            margin-bottom: 0.55rem;
            color: #2f3c52;
            font-size: 0.98rem;
            font-weight: 800;
        }

        .recovery-stat-card .stat-value {
            margin: 0;
            color: #1f2f43;
            font-size: clamp(2rem, 3vw, 2.45rem);
            line-height: 1;
            font-weight: 800;
        }

        .recovery-stat-card .stat-note {
            margin: 0.95rem 0 0;
            color: #70809a;
            font-size: 0.94rem;
            line-height: 1.45;
        }

        .recovery-stat-icon {
            width: 3.45rem;
            height: 3.45rem;
            border-radius: 1.15rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #dff0e2;
            color: #2b8a57;
            font-size: 1.35rem;
            flex-shrink: 0;
        }

        .recovery-filter-card,
        .recovery-table-card {
            border: 0;
            border-radius: 1.65rem;
            box-shadow: 0 20px 44px rgba(34, 50, 84, 0.1);
            overflow: hidden;
        }

        .recovery-filter-card .card-header,
        .recovery-table-card .card-header {
            background: #fff;
            border-bottom: 1px solid #ebf0f5;
            padding: 1.35rem 1.5rem;
        }

        .recovery-filter-card .card-body,
        .recovery-table-card .card-body {
            padding: 1.45rem 1.5rem;
        }

        .recovery-filter-toolbar,
        .recovery-filter-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.8rem;
            flex-wrap: wrap;
        }

        .recovery-filter-actions .btn {
            min-width: 0;
            padding: 0.58rem 0.9rem;
            border-radius: 0.85rem;
            font-size: 0.84rem;
            font-weight: 800;
        }

        .recovery-filter-grid {
            row-gap: 0.8rem;
        }

        .recovery-filter-grid .col-form {
            min-width: 0;
        }

        .recovery-filter-card .form-label {
            margin-bottom: 0.4rem;
            font-size: 0.84rem;
            color: #2f3c52;
        }

        .recovery-filter-card .form-control,
        .recovery-filter-card .form-select {
            min-height: 48px;
            border-radius: 1rem;
            font-size: 0.92rem;
            border-color: #dbe4ec;
            box-shadow: none;
        }

        .recovery-filter-card .card-header {
            padding-top: 1.55rem;
            padding-bottom: 1.55rem;
        }

        .recovery-filter-card .form-control:focus,
        .recovery-filter-card .form-select:focus {
            border-color: #6ca985;
            box-shadow: 0 0 0 0.2rem rgba(52, 134, 86, 0.12);
        }

        .recovery-table-card .table-app {
            margin-bottom: 0;
        }

        .recovery-table-card .table-app thead th {
            padding-top: 1rem;
            padding-bottom: 1rem;
            font-size: 0.82rem;
            font-weight: 800;
            color: #3b4a62;
            background: #f7f9fc;
            border-bottom-color: #e8edf3;
        }

        .recovery-table-card .table-app tbody td {
            padding-top: 1rem;
            padding-bottom: 1rem;
            vertical-align: middle;
        }

        .recovery-module-chip,
        .recovery-value-chip,
        .recovery-action-chip {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.35rem;
            border-radius: 999px;
            font-weight: 700;
            white-space: nowrap;
        }

        .recovery-module-chip {
            padding: 0.4rem 0.7rem;
            font-size: 0.78rem;
            color: #2e6c4f;
            background: #edf7ef;
            border: 1px solid #d3e7d8;
        }

        .recovery-value-stack {
            display: flex;
            flex-wrap: wrap;
            gap: 0.45rem;
        }

        .recovery-value-chip {
            padding: 0.4rem 0.65rem;
            font-size: 0.76rem;
            color: #5a6a80;
            background: #f6f8fb;
            border: 1px solid #e4eaf1;
        }

        .recovery-status-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.38rem 0.72rem;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 800;
        }

        .recovery-status-badge.ready {
            color: #2a7d4f;
            background: #eaf7ee;
        }

        .recovery-status-badge.blocked {
            color: #b06c1e;
            background: #fff4e3;
        }

        .recovery-action-chip {
            padding: 0.48rem 0.95rem;
            border: 1px solid #5c8f70;
            background: #fff;
            color: #2f6d4d;
            font-size: 0.8rem;
        }

        .recovery-action-chip:hover {
            background: #f1faf4;
            color: #23573d;
        }

        .recovery-pill-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 88px;
            padding: 0.45rem 0.8rem;
            border-radius: 999px;
            border: 1px solid #5c8f70;
            background: #f8fcf9;
            color: #2f6d4d;
            font-size: 0.8rem;
            font-weight: 700;
        }

        .recovery-pill-btn:hover {
            background: #2f6d4d;
            color: #fff;
        }

        .recovery-compare-modal .modal-dialog {
            max-width: 920px;
        }

        .recovery-compare-modal .modal-content {
            border: 0;
            border-radius: 1.15rem;
            overflow: hidden;
            box-shadow: 0 24px 54px rgba(32, 52, 84, 0.18);
        }

        .recovery-compare-modal .modal-header {
            padding: 0.9rem 1rem 0.55rem;
            border-bottom: 0;
        }

        .recovery-compare-modal .modal-title {
            font-size: 0.98rem;
            font-weight: 800;
            color: #203454;
        }

        .recovery-compare-modal .modal-body {
            padding: 0 1rem 1rem;
        }

        .recovery-compare-modal .btn-close {
            transform: scale(0.85);
            opacity: 0.7;
        }

        .recovery-compare-copy {
            font-size: 0.79rem;
            line-height: 1.5;
            color: #6b7a8f;
            margin: 0 0 0.9rem;
        }

        .recovery-compare-shell {
            background: #fafcfe;
            border: 1px solid #edf2f7;
            border-radius: 1rem;
            padding: 0.8rem 0.85rem 0.85rem;
        }

        .recovery-compare-grid {
            display: grid;
            grid-template-columns: minmax(180px, 220px) minmax(0, 1fr) minmax(0, 1fr);
            gap: 0.7rem;
            align-items: start;
        }

        .recovery-compare-head {
            font-size: 0.72rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #5d6d7e;
            padding: 0 0.2rem 0.35rem;
            border-bottom: 1px solid #e9eff5;
        }

        .recovery-compare-key {
            font-size: 0.78rem;
            font-weight: 700;
            color: #39485b;
            padding: 0.75rem 0.15rem 0;
            line-height: 1.35;
        }

        .recovery-compare-value {
            padding: 0.72rem 0.8rem;
            border-radius: 0.95rem;
            border: 1px solid #dfe8e2;
            color: #39485b;
            min-height: 44px;
            white-space: pre-wrap;
            word-break: break-word;
            font-size: 0.78rem;
            line-height: 1.55;
            background: #fff;
        }

        .recovery-compare-value.deleted {
            background: #fff7f4;
            border-color: #efd7cf;
        }

        .recovery-compare-value.original {
            background: #f4fbf6;
            border-color: #d2e6d7;
        }

        .recovery-empty {
            padding: 2.5rem 1rem;
            text-align: center;
            color: #718096;
        }

        .recovery-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
            padding: 1.2rem 1.5rem 1.35rem;
            border-top: 1px solid #ebf0f5;
            background: #fff;
        }

        .recovery-footer-copy {
            color: #70809a;
            font-size: 0.92rem;
        }

        .recovery-footer-per-page {
            display: inline-flex;
            align-items: center;
            gap: 0.8rem;
            color: #3b4a62;
            font-size: 0.92rem;
        }

        .recovery-footer-per-page .form-select {
            min-width: 118px;
            min-height: 48px;
            border-radius: 1rem;
            border-color: #dbe4ec;
            box-shadow: none;
            font-size: 0.92rem;
        }

        .recovery-footer .pagination {
            margin-bottom: 0;
            gap: 0.55rem;
        }

        .recovery-footer .page-item .page-link {
            min-width: 48px;
            height: 48px;
            border-radius: 1rem;
            border: 1px solid #dbe4ec;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #53637b;
            font-weight: 700;
            box-shadow: none;
        }

        .recovery-footer .page-item.active .page-link {
            border-color: #22553b;
            background: #2d6948;
            color: #fff;
        }

        .recovery-footer .page-item.disabled .page-link {
            border-color: #d7dee8;
            background: #d7dee8;
            color: #8d9ab0;
        }

        @media (max-width: 991.98px) {
            .recovery-page .page-hero {
                margin-top: -1rem;
                margin-left: -1rem;
                margin-right: -1rem;
                padding: 2.2rem 1.25rem 2.2rem;
                border-radius: 0 0 1.4rem 1.4rem;
            }
        }

        @media (max-width: 767.98px) {
            .recovery-filter-card .card-header,
            .recovery-filter-card .card-body,
            .recovery-table-card .card-header,
            .recovery-table-card .card-body,
            .recovery-footer {
                padding-left: 1rem;
                padding-right: 1rem;
            }

            .recovery-footer {
                justify-content: center;
            }

            .recovery-compare-grid {
                grid-template-columns: 1fr;
                gap: 0.55rem;
            }

            .recovery-compare-head {
                display: none;
            }

            .recovery-compare-value::before {
                display: block;
                margin-bottom: 0.35rem;
                font-size: 0.68rem;
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                color: #6b7a8f;
            }

            .recovery-compare-value.deleted::before {
                content: 'Current Deleted Value';
            }

            .recovery-compare-value.original::before {
                content: 'Original Value';
            }
        }
    </style>

    <div class="recovery-page">
        <div class="page-hero d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
            <div>
                <p class="page-eyebrow">Logs</p>
                <h1 class="page-title">Recovery</h1>
                <p class="page-subtitle">Undo soft-delete actions across users, routes, trips, vehicles, settings, and every other recoverable record from one place.</p>
                @include('logs.partials.subnav')
            </div>
        </div>

        <section class="row g-4 stats-overlap">
            <div class="col-sm-6 col-xl-3">
                <div class="card recovery-stat-card">
                    <div class="card-body">
                        <div class="stat-card-head">
                            <div>
                                <p class="stat-label">Recoverable Records</p>
                                <h2 class="stat-value">{{ $stats['total'] }}</h2>
                            </div>
                            <span class="recovery-stat-icon"><i class="fa-solid fa-rotate-left app-icon"></i></span>
                        </div>
                        <p class="stat-note">Every soft-deleted record currently available in Recovery.</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card recovery-stat-card">
                    <div class="card-body">
                        <div class="stat-card-head">
                            <div>
                                <p class="stat-label">Deleted Today</p>
                                <h2 class="stat-value">{{ $stats['today'] }}</h2>
                            </div>
                            <span class="recovery-stat-icon"><i class="fa-solid fa-calendar-day app-icon"></i></span>
                        </div>
                        <p class="stat-note">Records removed today and still eligible for undo.</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card recovery-stat-card">
                    <div class="card-body">
                        <div class="stat-card-head">
                            <div>
                                <p class="stat-label">Modules</p>
                                <h2 class="stat-value">{{ $stats['modules'] }}</h2>
                            </div>
                            <span class="recovery-stat-icon"><i class="fa-solid fa-layer-group app-icon"></i></span>
                        </div>
                        <p class="stat-note">Different sections represented in the recovery queue.</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card recovery-stat-card">
                    <div class="card-body">
                        <div class="stat-card-head">
                            <div>
                                <p class="stat-label">Ready To Undo</p>
                                <h2 class="stat-value">{{ $stats['restorable'] }}</h2>
                            </div>
                            <span class="recovery-stat-icon"><i class="fa-solid fa-arrow-rotate-left app-icon"></i></span>
                        </div>
                        <p class="stat-note">Items that can be restored right now with no conflicts.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="card recovery-filter-card mt-2 mb-4">
            <div class="card-header">
                <div class="recovery-filter-toolbar">
                    <div>
                        <h3 class="section-title mb-0">Recovery Queue</h3>
                    </div>
                    <div class="recovery-filter-actions">
                        <form method="post" action="{{ route('logs.recovery.delete-months', request()->query()) }}" class="d-inline" data-confirm-delete data-confirm-months="true" data-confirm-months-default="1" data-delete-message="Choose how many recent months to keep before permanently deleting older recovery records.">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-outline-danger" type="submit"><i class="fa-solid fa-trash-can me-2"></i>Delete Months Logs</button>
                        </form>
                        <button class="btn btn-success" form="recoveryFilters" type="submit">Filter</button>
                        <a class="btn btn-outline-secondary" href="{{ route('logs.recovery.index') }}">Reset</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form method="get" action="{{ route('logs.recovery.index') }}" id="recoveryFilters">
                    <div class="row recovery-filter-grid">
                        <div class="col-lg-5 col-form">
                            <label class="form-label fw-semibold" for="search">Search</label>
                            <input class="form-control" id="search" name="search" type="text" placeholder="Search by title, module, code, email, or original value" value="{{ $search }}">
                        </div>
                        <div class="col-md-4 col-lg-3 col-form">
                            <label class="form-label fw-semibold" for="type">Module</label>
                            <select class="form-select" id="type" name="type">
                                <option value="">All modules</option>
                                @foreach ($types as $value => $label)
                                    <option value="{{ $value }}" @selected($type === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 col-lg-2 col-form">
                            <label class="form-label fw-semibold" for="from_date">From</label>
                            <input class="form-control" id="from_date" name="from_date" type="date" value="{{ $fromDate }}">
                        </div>
                        <div class="col-md-3 col-lg-2 col-form">
                            <label class="form-label fw-semibold" for="to_date">To</label>
                            <input class="form-control" id="to_date" name="to_date" type="date" value="{{ $toDate }}">
                        </div>
                    </div>
                </form>
            </div>
        </section>

        <section class="card recovery-table-card mb-4">
            <div class="card-header">
                <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-2">
                    <div>
                        <h3 class="section-title mb-1">Recovery Records</h3>
                        <p class="section-copy mb-0">Review the deleted value, the original value to be restored, and whether the undo can proceed safely.</p>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-shell table-wrap">
                    <table class="table table-app align-middle">
                        <thead>
                            <tr>
                                <th>Sr#</th>
                                <th>Module</th>
                                <th>Record</th>
                                <th>Current Deleted Value</th>
                                <th>Original Value</th>
                                <th>Deleted At</th>
                                <th>Status</th>
                                <th class="text-end">Undo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recoveryItems as $item)
                                @php
                                    $currentDeletedValue = (string) ($item['current_value'] ?: '-');
                                    $originalValueText = $item['original_values'] !== []
                                        ? implode("\n", array_map(fn ($field, $value) => ucfirst(str_replace('_', ' ', $field)).': '.$value, array_keys($item['original_values']), $item['original_values']))
                                        : '-';
                                @endphp
                                <tr>
                                    <td>{{ $recoveryItems->firstItem() + $loop->index }}</td>
                                    <td>
                                        <span class="recovery-module-chip">{{ $item['type_label'] }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $item['title'] }}</div>
                                        <div class="text-muted small">ID #{{ $item['id'] }}</div>
                                    </td>
                                    <td class="text-break">
                                        <button
                                            class="recovery-pill-btn"
                                            type="button"
                                            data-bs-toggle="modal"
                                            data-bs-target="#recoveryCompareModal"
                                            data-compare-title="{{ $item['title'] }} Comparison"
                                            data-compare-deleted="{{ $currentDeletedValue }}"
                                            data-compare-original="{{ $originalValueText }}"
                                        >
                                            Compare
                                        </button>
                                    </td>
                                    <td class="text-break">
                                        @if ($item['original_values'] !== [])
                                            <button
                                                class="recovery-pill-btn"
                                                type="button"
                                                data-bs-toggle="modal"
                                                data-bs-target="#recoveryCompareModal"
                                                data-compare-title="{{ $item['title'] }} Comparison"
                                                data-compare-deleted="{{ $currentDeletedValue }}"
                                                data-compare-original="{{ $originalValueText }}"
                                            >
                                                Compare
                                            </button>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-nowrap">{{ $item['deleted_at']?->format('d-m-Y H:i:s') ?: '-' }}</td>
                                    <td>
                                        @if ($item['can_restore'])
                                            <span class="recovery-status-badge ready">Ready</span>
                                        @else
                                            <span class="recovery-status-badge blocked">Blocked</span>
                                            <div class="text-muted small mt-1">{{ $item['reason'] }}</div>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if ($item['can_restore'])
                                            <form method="post" action="{{ route('logs.recovery.restore', ['type' => $item['type'], 'id' => $item['id']] + request()->query()) }}" class="d-inline" data-confirm-delete data-confirm-title="Confirm Recovery" data-confirm-action-label="Undo" data-confirm-action-style="success" data-delete-message="Undo this delete action and restore the record?">
                                                @csrf
                                                @method('PUT')
                                                <button class="recovery-action-chip" type="submit">
                                                    <i class="fa-solid fa-rotate-left"></i>Undo
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-muted small">Resolve the conflict first.</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="recovery-empty">No recoverable records found for the current filters.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($recoveryItems->hasPages())
                <div class="recovery-footer">
                    <div class="recovery-footer-copy">
                        Showing {{ $recoveryItems->firstItem() }} to {{ $recoveryItems->lastItem() }} of {{ $recoveryItems->total() }} entries
                    </div>
                    <form method="get" action="{{ route('logs.recovery.index') }}" class="recovery-footer-per-page">
                        <input type="hidden" name="search" value="{{ $search }}">
                        <input type="hidden" name="type" value="{{ $type }}">
                        <input type="hidden" name="from_date" value="{{ $fromDate }}">
                        <input type="hidden" name="to_date" value="{{ $toDate }}">
                        <select class="form-select" name="per_page" onchange="this.form.submit()">
                            @foreach ([10, 25, 50, 100, 'all'] as $option)
                                <option value="{{ $option }}" @selected((string) $perPage === (string) $option)>{{ $option === 'all' ? 'All' : $option }}</option>
                            @endforeach
                        </select>
                        <span>per page</span>
                    </form>
                    <div>{{ $recoveryItems->links() }}</div>
                </div>
            @endif
        </section>

        <div class="modal fade recovery-compare-modal" id="recoveryCompareModal" tabindex="-1" aria-labelledby="recoveryCompareModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="recoveryCompareModalLabel">Recovery Comparison</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="recovery-compare-copy mb-3">Review the current deleted value and the original value together before restoring the record.</p>
                        <div class="recovery-compare-shell">
                            <div class="recovery-compare-grid">
                                <div class="recovery-compare-head">Field</div>
                                <div class="recovery-compare-head">Current Deleted Value</div>
                                <div class="recovery-compare-head">Original Value</div>

                                <div class="recovery-compare-key">Recovery Value</div>
                                <div class="recovery-compare-value deleted" id="recoveryCompareDeletedValue"></div>
                                <div class="recovery-compare-value original" id="recoveryCompareOriginalValue"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const recoveryCompareModal = document.getElementById('recoveryCompareModal');

                if (!recoveryCompareModal) {
                    return;
                }

                recoveryCompareModal.addEventListener('show.bs.modal', function (event) {
                    const trigger = event.relatedTarget;

                    if (!trigger) {
                        return;
                    }

                    const title = trigger.getAttribute('data-compare-title') || 'Recovery Comparison';
                    const deletedValue = trigger.getAttribute('data-compare-deleted') || '-';
                    const originalValue = trigger.getAttribute('data-compare-original') || '-';

                    const titleElement = recoveryCompareModal.querySelector('#recoveryCompareModalLabel');
                    const deletedElement = recoveryCompareModal.querySelector('#recoveryCompareDeletedValue');
                    const originalElement = recoveryCompareModal.querySelector('#recoveryCompareOriginalValue');

                    if (titleElement) {
                        titleElement.textContent = title;
                    }

                    if (deletedElement) {
                        deletedElement.textContent = deletedValue;
                    }

                    if (originalElement) {
                        originalElement.textContent = originalValue;
                    }
                });
            });
        </script>
    @endpush
@endsection
