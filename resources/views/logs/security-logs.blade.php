@extends('layouts.app', ['title' => 'Security Logs | Free Public Transport System', 'pageBadge' => 'Logs'])

@section('content')
    <style>
        .security-filter-card {
            border-radius: 1rem;
        }

        .security-filter-toolbar,
        .security-filter-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .security-filter-actions .btn {
            min-width: 0;
            padding: 0.65rem 0.9rem;
            border-radius: 0.8rem;
            font-size: 0.88rem;
            font-weight: 700;
        }

        .security-filter-card .form-label {
            font-size: 0.82rem;
            margin-bottom: 0.35rem;
        }

        .security-filter-card .form-control,
        .security-filter-card .form-select {
            min-height: 40px;
            border-radius: 0.8rem;
            font-size: 0.88rem;
        }

        .security-filter-grid {
            row-gap: 0.7rem;
        }

        .security-pill-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 72px;
            padding: 0.45rem 0.75rem;
            border-radius: 999px;
            border: 1px solid #466c54;
            background: #f8fcf9;
            color: #466c54;
            font-size: 0.8rem;
            font-weight: 700;
        }

        .security-pill-btn:hover {
            background: #466c54;
            color: #fff;
        }

        .security-risk-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.3rem 0.65rem;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .security-risk-badge.low {
            background: #eef7f0;
            color: #3d8d59;
        }

        .security-risk-badge.medium {
            background: #fff7ea;
            color: #c58b2d;
        }

        .security-risk-badge.high {
            background: #fff0f0;
            color: #d64d4d;
        }

        .security-text-modal .modal-dialog {
            max-width: 760px;
        }

        .security-text-pre {
            white-space: pre-wrap;
            word-break: break-word;
            margin: 0;
            color: #39485b;
        }
    </style>

    <div class="page-hero d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
        <div>
            <p class="page-eyebrow">Logs</p>
            <h1 class="page-title">Security Logs</h1>
            <p class="page-subtitle">Track login activity, captcha failures, and other security-related portal events.</p>
        </div>
    </div>

    <section class="row g-4 stats-overlap">
        <div class="col-sm-6 col-xl-3"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">Total Events</p><h2 class="stat-value">{{ $stats['total'] }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-shield-halved app-icon"></i></span></div><p class="stat-note">All captured security events.</p></div></div></div>
        <div class="col-sm-6 col-xl-3"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">Today</p><h2 class="stat-value">{{ $stats['today'] }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-calendar-day app-icon"></i></span></div><p class="stat-note">Security events recorded today.</p></div></div></div>
        <div class="col-sm-6 col-xl-3"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">Failed Logins</p><h2 class="stat-value">{{ $stats['failed_logins'] }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-user-lock app-icon"></i></span></div><p class="stat-note">Failed login and captcha events.</p></div></div></div>
        <div class="col-sm-6 col-xl-3"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">High Risk</p><h2 class="stat-value">{{ $stats['high_risk'] }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-triangle-exclamation app-icon"></i></span></div><p class="stat-note">Events currently marked high risk.</p></div></div></div>
    </section>

    <section class="card section-card security-filter-card mt-2 mb-4">
        <div class="card-header">
            <div class="security-filter-toolbar">
                <h3 class="section-title mb-0">Security Logs</h3>
                <div class="security-filter-actions">
                    <form method="post" action="{{ route('logs.security.delete-months', request()->query()) }}" class="d-inline" data-confirm-delete data-delete-message="Are you sure you want to delete all previous months security logs?">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-outline-danger" type="submit"><i class="fa-solid fa-trash-can me-2"></i>Delete Months Logs</button>
                    </form>
                    <button class="btn btn-success" form="securityLogFilters" type="submit">Filter</button>
                    <a class="btn btn-outline-secondary" href="{{ route('logs.security.index') }}">Reset</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form method="get" action="{{ route('logs.security.index') }}" id="securityLogFilters">
                <div class="row security-filter-grid">
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
                        <label class="form-label fw-semibold" for="event_type">Event Type</label>
                        <select class="form-select" id="event_type" name="event_type">
                            <option value="">All event types</option>
                            @foreach ($eventTypes as $value => $label)
                                <option value="{{ $value }}" @selected($filters['event_type'] === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold" for="ip">IP</label>
                        <input class="form-control" id="ip" name="ip" type="text" placeholder="e.g. 127.0.0.1" value="{{ $filters['ip'] }}">
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
                            <th>Event</th>
                            <th>Email Attempted</th>
                            <th>Attempts</th>
                            <th>Requests</th>
                            <th>IP</th>
                            <th>User Agent</th>
                            <th>URL</th>
                            <th>Risk</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                            <tr>
                                <td>{{ $logs->firstItem() + $loop->index }}</td>
                                <td class="text-nowrap">{{ $log->created_at->format('d-m-Y H:i:s') }}</td>
                                <td>{{ $log->user?->name ?: 'Guest/System' }}</td>
                                <td>{{ \App\Models\SecurityLog::EVENT_LABELS[$log->event_type] ?? $log->event_type }}</td>
                                <td>{{ $log->email_attempted ?: '-' }}</td>
                                <td>{{ $log->attempts ?: '-' }}</td>
                                <td>{{ $log->request_count ?: '-' }}</td>
                                <td>{{ $log->ip_address ?: '-' }}</td>
                                <td>
                                    @if ($log->user_agent)
                                        <button
                                            class="security-pill-btn"
                                            type="button"
                                            data-bs-toggle="modal"
                                            data-bs-target="#securityTextModal"
                                            data-modal-title="User Agent"
                                            data-modal-content="{{ $log->user_agent }}"
                                        >
                                            Agent
                                        </button>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if ($log->url)
                                        <button
                                            class="security-pill-btn"
                                            type="button"
                                            data-bs-toggle="modal"
                                            data-bs-target="#securityTextModal"
                                            data-modal-title="URL"
                                            data-modal-content="{{ $log->url }}"
                                        >
                                            URL
                                        </button>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td><span class="security-risk-badge {{ $log->risk_level }}">{{ \App\Models\SecurityLog::RISK_LABELS[$log->risk_level] ?? ucfirst($log->risk_level) }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="11" class="text-center text-muted py-4">No security logs found yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @include('settings.partials.pagination', ['paginator' => $logs, 'perPage' => $perPage])
        </div>
    </section>

    <div class="modal fade security-text-modal" id="securityTextModal" tabindex="-1" aria-labelledby="securityTextModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="securityTextModalLabel">Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <pre class="security-text-pre" id="securityTextModalBody"></pre>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('securityTextModal');

            if (!modal) {
                return;
            }

            const title = document.getElementById('securityTextModalLabel');
            const body = document.getElementById('securityTextModalBody');

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
        });
    </script>
@endpush
