@extends('layouts.app', ['title' => 'Audit Logs | Free Public Transport System', 'pageBadge' => 'Logs'])

@section('content')
    <style>
        .audit-filter-card {
            border-radius: 1rem;
        }

        .audit-filter-toolbar,
        .audit-filter-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .audit-filter-actions .btn {
            min-width: 0;
            padding: 0.65rem 0.9rem;
            border-radius: 0.8rem;
            font-size: 0.88rem;
            font-weight: 700;
        }

        .audit-filter-card .form-label {
            font-size: 0.82rem;
            margin-bottom: 0.35rem;
        }

        .audit-filter-card .form-control,
        .audit-filter-card .form-select {
            min-height: 40px;
            border-radius: 0.8rem;
            font-size: 0.88rem;
        }

        .audit-filter-grid {
            row-gap: 0.7rem;
        }

        .audit-pill-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 84px;
            padding: 0.45rem 0.75rem;
            border-radius: 999px;
            border: 1px solid #466c54;
            background: #f8fcf9;
            color: #466c54;
            font-size: 0.8rem;
            font-weight: 700;
        }

        .audit-pill-btn:hover {
            background: #466c54;
            color: #fff;
        }

        .audit-text-modal .modal-dialog {
            max-width: 760px;
        }

        .audit-text-modal .modal-content {
            border: 0;
            border-radius: 1.15rem;
            overflow: hidden;
            box-shadow: 0 24px 54px rgba(32, 52, 84, 0.18);
        }

        .audit-text-modal .modal-header {
            padding: 0.9rem 1rem 0.55rem;
            border-bottom: 0;
        }

        .audit-text-modal .modal-title {
            font-size: 0.98rem;
            font-weight: 800;
            color: #203454;
        }

        .audit-text-modal .modal-body {
            padding: 0 1rem 1rem;
        }

        .audit-text-modal .btn-close {
            transform: scale(0.85);
            opacity: 0.7;
        }

        .audit-text-pre {
            white-space: pre-wrap;
            word-break: break-word;
            margin: 0;
            color: #39485b;
            font-size: 0.78rem;
            line-height: 1.55;
            background: #f8fafc;
            border: 1px solid #e4ebf2;
            border-radius: 0.95rem;
            padding: 0.85rem 0.95rem;
        }

        .audit-compare-modal .modal-dialog {
            max-width: 920px;
        }

        .audit-compare-modal .modal-content {
            border: 0;
            border-radius: 1.2rem;
            overflow: hidden;
            box-shadow: 0 24px 54px rgba(32, 52, 84, 0.18);
        }

        .audit-compare-modal .modal-header {
            padding: 0.95rem 1.1rem 0.55rem;
            border-bottom: 0;
        }

        .audit-compare-modal .modal-title {
            font-size: 1rem;
            font-weight: 800;
            color: #203454;
        }

        .audit-compare-modal .modal-body {
            padding: 0 1.1rem 1.1rem;
        }

        .audit-compare-modal .btn-close {
            transform: scale(0.85);
            opacity: 0.7;
        }

        .audit-compare-copy {
            font-size: 0.79rem;
            line-height: 1.5;
            color: #6b7a8f;
            margin: 0 0 0.9rem;
        }

        .audit-compare-shell {
            background: #fafcfe;
            border: 1px solid #edf2f7;
            border-radius: 1rem;
            padding: 0.8rem 0.85rem 0.85rem;
        }

        .audit-compare-grid {
            display: grid;
            grid-template-columns: minmax(140px, 180px) minmax(0, 1fr) minmax(0, 1fr);
            gap: 0.7rem;
            align-items: start;
        }

        .audit-compare-head {
            font-size: 0.72rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #5d6d7e;
            padding: 0 0.2rem 0.35rem;
            border-bottom: 1px solid #e9eff5;
        }

        .audit-compare-key {
            font-size: 0.78rem;
            font-weight: 700;
            color: #39485b;
            padding: 0.75rem 0.15rem 0;
            border-top: 0;
            line-height: 1.35;
        }

        .audit-compare-value {
            padding: 0.72rem 0.8rem;
            border-radius: 0.95rem;
            border: 1px solid #dfe8e2;
            background: #ffffff;
            color: #39485b;
            min-height: 44px;
            white-space: pre-wrap;
            word-break: break-word;
            font-size: 0.77rem;
            line-height: 1.5;
            font-family: inherit;
        }

        .audit-compare-spacer {
            border-bottom: 0;
            padding-bottom: 0;
        }

        .audit-compare-value.old {
            background: #fff7f4;
            border-color: #efd7cf;
        }

        .audit-compare-value.new {
            background: #f4fbf6;
            border-color: #d2e6d7;
        }

        @media (max-width: 767.98px) {
            .audit-compare-modal .modal-body {
                padding: 0 0.9rem 0.95rem;
            }

            .audit-compare-shell {
                padding: 0.75rem;
            }

            .audit-compare-grid {
                grid-template-columns: 1fr;
                gap: 0.55rem;
            }

            .audit-compare-head {
                display: none;
            }

            .audit-compare-key {
                padding-top: 0.8rem;
            }

            .audit-compare-value::before {
                display: block;
                margin-bottom: 0.35rem;
                font-size: 0.68rem;
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                color: #6b7a8f;
            }

            .audit-compare-value.old::before {
                content: 'Old Value';
            }

            .audit-compare-value.new::before {
                content: 'New Value';
            }
        }
    </style>

    <div class="page-hero d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
        <div>
            <p class="page-eyebrow">Logs</p>
            <h1 class="page-title">Audit Logs</h1>
            <p class="page-subtitle">Track who changed payments, roles, and trip records across the portal.</p>
        </div>
    </div>

    <section class="row g-4 stats-overlap">
        <div class="col-sm-6 col-xl-3"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">Total Events</p><h2 class="stat-value">{{ $stats['total'] }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-clipboard-list app-icon"></i></span></div><p class="stat-note">All captured audit activity.</p></div></div></div>
        <div class="col-sm-6 col-xl-3"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">Today</p><h2 class="stat-value">{{ $stats['today'] }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-calendar-day app-icon"></i></span></div><p class="stat-note">Audit events recorded today.</p></div></div></div>
        <div class="col-sm-6 col-xl-3"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">Payment Actions</p><h2 class="stat-value">{{ $stats['payments'] }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-money-check-dollar app-icon"></i></span></div><p class="stat-note">Payment approvals and status updates.</p></div></div></div>
        <div class="col-sm-6 col-xl-3"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">Roles & Trips</p><h2 class="stat-value">{{ $stats['roles_trips'] }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-pen-to-square app-icon"></i></span></div><p class="stat-note">Role changes plus trip edits and deletes.</p></div></div></div>
    </section>

    <section class="card section-card audit-filter-card mt-2 mb-4">
        <div class="card-header">
            <div class="audit-filter-toolbar">
                <h3 class="section-title mb-0">Audit Logs</h3>
                <div class="audit-filter-actions">
                    <form method="post" action="{{ route('logs.audit.delete-months', request()->query()) }}" class="d-inline" data-confirm-delete data-delete-message="Are you sure you want to delete all previous months audit logs?">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-outline-danger" type="submit"><i class="fa-solid fa-trash-can me-2"></i>Delete Months Logs</button>
                    </form>
                    <button class="btn btn-success" form="auditLogFilters" type="submit">Filter</button>
                    <a class="btn btn-outline-secondary" href="{{ route('logs.audit.index') }}">Reset</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form method="get" action="{{ route('logs.audit.index') }}" id="auditLogFilters">
                <div class="row audit-filter-grid">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold" for="user_id">User</label>
                        <select class="form-select" id="user_id" name="user_id">
                            <option value="">All users</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" @selected((string) $filters['user_id'] === (string) $user->id)>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold" for="action">Action</label>
                        <select class="form-select" id="action" name="action">
                            <option value="">All actions</option>
                            @foreach ($actions as $value => $label)
                                <option value="{{ $value }}" @selected($filters['action'] === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold" for="subject_type">Subject Type</label>
                        <select class="form-select" id="subject_type" name="subject_type">
                            <option value="">All subjects</option>
                            @foreach ($subjectTypes as $value => $label)
                                <option value="{{ $value }}" @selected($filters['subject_type'] === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold" for="from_date">From</label>
                        <input class="form-control" id="from_date" name="from_date" type="date" value="{{ $filters['from_date'] }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold" for="to_date">To</label>
                        <input class="form-control" id="to_date" name="to_date" type="date" value="{{ $filters['to_date'] }}">
                    </div>
                </div>
            </form>
        </div>
    </section>

    <section class="card section-card table-card mb-4">
        <div class="card-body">
            <div class="table-shell table-wrap">
                <table class="table table-app align-middle">
                    <thead>
                        <tr>
                            <th>Sr#</th>
                            <th>Date/Time</th>
                            <th>User</th>
                            <th>Action</th>
                            <th>Subject</th>
                            <th>Label</th>
                            <th>Changes</th>
                            <th>Meta</th>
                            <th>URL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                            <tr>
                                <td>{{ $logs->firstItem() + $loop->index }}</td>
                                <td class="text-nowrap">{{ $log->created_at?->copy()->timezone('Asia/Karachi')->format('d-m-Y H:i:s') }}</td>
                                <td>{{ $log->user?->name ?: 'Guest/System' }}</td>
                                <td>{{ \App\Models\AuditLog::ACTION_LABELS[$log->action] ?? $log->action }}</td>
                                <td>{{ $log->subjectTypeLabel() }}</td>
                                <td>{{ $log->auditable_label ?: '-' }}</td>
                                <td>
                                    @if ($log->hasValueChanges())
                                        <button
                                            class="audit-pill-btn"
                                            type="button"
                                            data-bs-toggle="modal"
                                            data-bs-target="#auditCompareModal"
                                            data-modal-title="Compare Changes"
                                            data-old-values="{{ json_encode($log->changedOldValues(), JSON_UNESCAPED_SLASHES) }}"
                                            data-new-values="{{ json_encode($log->changedNewValues(), JSON_UNESCAPED_SLASHES) }}"
                                        >
                                            Compare
                                        </button>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if ($log->meta)
                                        <button class="audit-pill-btn" type="button" data-bs-toggle="modal" data-bs-target="#auditTextModal" data-modal-title="Meta" data-modal-content="{{ json_encode($log->meta, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}">Meta</button>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if ($log->url)
                                        <button class="audit-pill-btn" type="button" data-bs-toggle="modal" data-bs-target="#auditTextModal" data-modal-title="URL" data-modal-content="{{ $log->url }}">URL</button>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="text-center text-muted py-4">No audit logs found yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @include('settings.partials.pagination', ['paginator' => $logs, 'perPage' => $perPage])
        </div>
    </section>

    <div class="modal fade audit-text-modal" id="auditTextModal" tabindex="-1" aria-labelledby="auditTextModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="auditTextModalLabel">Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <pre class="audit-text-pre" id="auditTextModalBody"></pre>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade audit-compare-modal" id="auditCompareModal" tabindex="-1" aria-labelledby="auditCompareModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="auditCompareModalLabel">Compare Changes</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="audit-compare-copy">Review the changed field on the left, with previous value and updated value shown side by side.</p>
                    <div class="audit-compare-shell">
                        <div class="audit-compare-grid" id="auditCompareGrid"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('auditTextModal');
            const compareModal = document.getElementById('auditCompareModal');

            if (!modal) {
                return;
            }

            const title = document.getElementById('auditTextModalLabel');
            const body = document.getElementById('auditTextModalBody');

            modal.addEventListener('show.bs.modal', function (event) {
                const trigger = event.relatedTarget;

                if (!trigger) {
                    return;
                }

                title.textContent = trigger.getAttribute('data-modal-title') || 'Details';
                body.textContent = trigger.getAttribute('data-modal-content') || '-';
            });

            modal.addEventListener('hidden.bs.modal', function () {
                title.textContent = 'Details';
                body.textContent = '';
            });

            if (!compareModal) {
                return;
            }

            const compareTitle = document.getElementById('auditCompareModalLabel');
            const compareGrid = document.getElementById('auditCompareGrid');

            compareModal.addEventListener('show.bs.modal', function (event) {
                const trigger = event.relatedTarget;

                if (!trigger) {
                    return;
                }

                compareTitle.textContent = trigger.getAttribute('data-modal-title') || 'Compare Changes';

                const oldValues = JSON.parse(trigger.getAttribute('data-old-values') || '{}');
                const newValues = JSON.parse(trigger.getAttribute('data-new-values') || '{}');
                const keys = Array.from(new Set([
                    ...Object.keys(oldValues),
                    ...Object.keys(newValues),
                ]));

                compareGrid.innerHTML = '';

                [
                    '',
                    'Old Value',
                    'New Value',
                ].forEach(function (heading, index) {
                    const cell = document.createElement('div');
                    cell.className = index === 0 ? 'audit-compare-head audit-compare-spacer' : 'audit-compare-head';
                    cell.textContent = heading;
                    compareGrid.appendChild(cell);
                });

                keys.forEach(function (key) {
                    const keyCell = document.createElement('div');
                    keyCell.className = 'audit-compare-key';
                    keyCell.textContent = formatFieldLabel(key);
                    compareGrid.appendChild(keyCell);

                    const oldCell = document.createElement('div');
                    oldCell.className = 'audit-compare-value old';
                    oldCell.textContent = formatCompareValue(oldValues[key]);
                    compareGrid.appendChild(oldCell);

                    const newCell = document.createElement('div');
                    newCell.className = 'audit-compare-value new';
                    newCell.textContent = formatCompareValue(newValues[key]);
                    compareGrid.appendChild(newCell);
                });
            });

            compareModal.addEventListener('hidden.bs.modal', function () {
                compareTitle.textContent = 'Compare Changes';
                compareGrid.innerHTML = '';
            });

            function formatCompareValue(value) {
                if (value === undefined || value === null || value === '') {
                    return '-';
                }

                if (isIsoDateString(value)) {
                    return formatIsoDateString(value);
                }

                if (typeof value === 'object') {
                    return JSON.stringify(value, null, 2);
                }

                return String(value);
            }

            function formatFieldLabel(value) {
                return String(value)
                    .replaceAll('_', ' ')
                    .replace(/\b\w/g, function (char) {
                        return char.toUpperCase();
                    });
            }

            function isIsoDateString(value) {
                return typeof value === 'string'
                    && /^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}/.test(value);
            }

            function formatIsoDateString(value) {
                const match = String(value).match(
                    /^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2})(?::(\d{2}))?(?:\.\d+)?Z?$/
                );

                if (!match) {
                    return value;
                }

                const formatted = String(value).replace('T', ' ');

                if (/ 00:00:00(?:\.\d+)?Z?$/.test(formatted)) {
                    return formatted.replace(/ 00:00:00(?:\.\d+)?Z?$/, '');
                }

                return formatted;
            }
        });
    </script>
@endpush
