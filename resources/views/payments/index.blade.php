@extends('layouts.app', ['title' => $pageHeading.' | Free Public Transport System', 'pageBadge' => 'Payments'])

@section('content')
    <style>
        .payment-export-card {
            border-radius: 1rem;
        }

        .payment-filter-card {
            border-radius: 1rem;
        }

        .payment-export-actions,
        .payment-filter-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.45rem;
        }

        .payment-export-actions .btn,
        .payment-filter-actions .btn {
            min-width: 0;
            padding: 0.65rem 0.8rem;
            border-radius: 0.75rem;
            font-size: 0.88rem;
            font-weight: 700;
            line-height: 1.1;
        }

        .payment-export-note {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #6b7a8f;
            font-size: 0.84rem;
            margin-bottom: 0;
        }

        .payment-export-note i {
            width: 22px;
            height: 22px;
            border-radius: 999px;
            background: #eef3f7;
            color: #65748b;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
        }

        .payment-filter-card .card-header,
        .payment-export-card .card-body {
            padding: 0.8rem 0.95rem;
        }

        .payment-filter-card .card-body {
            padding: 0.85rem 0.95rem 0.95rem;
        }

        .payment-filter-card .form-label {
            font-size: 0.82rem;
            margin-bottom: 0.35rem;
        }

        .payment-filter-card .form-control,
        .payment-filter-card .form-select {
            min-height: 40px;
            border-radius: 0.8rem;
            font-size: 0.88rem;
            padding-top: 0.45rem;
            padding-bottom: 0.45rem;
        }

        .payment-filter-grid {
            row-gap: 0.7rem;
        }

        .payment-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .payment-filter-title {
            margin: 0;
            font-size: 0.98rem;
            font-weight: 800;
            color: #2c3a4d;
        }

        .payment-filter-actions .btn-outline-secondary {
            min-width: 94px;
        }

        .payment-export-actions .btn i,
        .payment-filter-actions .btn i {
            font-size: 0.82rem;
        }

        .payment-export-actions .btn {
            min-width: 104px;
        }

        .payment-filter-card .card-header {
            border-bottom-width: 1px;
        }

        .payment-filter-card .card-body .row > div {
            margin-bottom: 0;
        }

        @media (max-width: 767.98px) {
            .payment-export-actions,
            .payment-filter-actions {
                width: 100%;
            }

            .payment-export-actions .btn,
            .payment-filter-actions .btn {
                flex: 1 1 120px;
            }
        }
    </style>

    <div class="page-hero d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
        <div>
            <p class="page-eyebrow">Payments</p>
            <h1 class="page-title">{{ $pageHeading }}</h1>
            <p class="page-subtitle">{{ $pageSubtitle }}</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a class="btn {{ $currentStatus === 'due' ? 'btn-success' : 'btn-outline-secondary' }}" href="{{ route('payments.due') }}">Due</a>
            <a class="btn {{ $currentStatus === 'paid' ? 'btn-success' : 'btn-outline-secondary' }}" href="{{ route('payments.paid') }}">Paid</a>
            <a class="btn {{ $currentStatus === 'rejected' ? 'btn-success' : 'btn-outline-secondary' }}" href="{{ route('payments.rejected') }}">Rejected</a>
            <a class="btn {{ $currentStatus === 'all' ? 'btn-success' : 'btn-outline-secondary' }}" href="{{ route('payments.index') }}">All</a>
        </div>
    </div>

    <section class="row g-4 stats-overlap">
        <div class="col-sm-6 col-xl-3"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">Total Payments</p><h2 class="stat-value">{{ $stats['total'] }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-file-invoice-dollar app-icon"></i></span></div><p class="stat-note">All trip cost payment records.</p></div></div></div>
        <div class="col-sm-6 col-xl-3"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">Due Payments</p><h2 class="stat-value">{{ $stats['due'] }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-hourglass-half app-icon"></i></span></div><p class="stat-note">Payments waiting for approval.</p></div></div></div>
        <div class="col-sm-6 col-xl-3"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">Paid Amount</p><h2 class="stat-value stat-value-compact">{{ number_format((float) $stats['paid_amount'], 0) }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-money-check-dollar app-icon"></i></span></div><p class="stat-note">Amount already paid to transporters.</p></div></div></div>
        <div class="col-sm-6 col-xl-3"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">Amount Left</p><h2 class="stat-value stat-value-compact">{{ number_format((float) $stats['amount_left'], 0) }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-wallet app-icon"></i></span></div><p class="stat-note">Amount still pending in due payments.</p></div></div></div>
    </section>

    <section class="row g-4 mt-2">
        <div class="col-12">
            <div class="card section-card payment-export-card">
                <div class="card-body">
                    <div class="payment-toolbar">
                        <p class="payment-export-note">
                            <i class="fa-solid fa-circle-info"></i>
                            Review filtered payment data and export the current results in the format you need.
                        </p>
                        <div class="payment-export-actions">
                            <a class="btn btn-success" href="{{ route('payments.export.csv', request()->query()) }}"><i class="fa-regular fa-file-lines me-2"></i>CSV</a>
                            <a class="btn btn-success" href="{{ route('payments.export.excel', request()->query()) }}"><i class="fa-regular fa-file-excel me-2"></i>Excel</a>
                            <a class="btn btn-danger" href="{{ route('payments.export.pdf-view', request()->query()) }}" target="_blank" rel="noopener"><i class="fa-regular fa-file-pdf me-2"></i>PDF Detailed</a>
                            <a class="btn btn-danger" href="{{ route('payments.export.pdf-table-view', request()->query()) }}" target="_blank" rel="noopener"><i class="fa-regular fa-file-pdf me-2"></i>PDF Table</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card section-card payment-filter-card">
                <div class="card-header">
                    <div class="payment-toolbar">
                        <h3 class="payment-filter-title">Filters</h3>
                        <div class="payment-filter-actions">
                            <button class="btn btn-success" form="paymentFilters" type="submit"><i class="fa-solid fa-filter me-2"></i>Apply Filters</button>
                            <a class="btn btn-outline-secondary" href="{{ route($currentStatus === 'all' ? 'payments.index' : 'payments.'.$currentStatus) }}"><i class="fa-solid fa-rotate-right me-2"></i>Reset</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form method="get" action="{{ route($currentStatus === 'all' ? 'payments.index' : 'payments.'.$currentStatus) }}" id="paymentFilters">
                        <div class="row payment-filter-grid">
                            <div class="col-md-2">
                                <label class="form-label fw-semibold" for="status">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="">All statuses</option>
                                    @foreach ($statuses as $value => $label)
                                        <option value="{{ $value }}" @selected($filters['status'] === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold" for="district_id">District</label>
                                <select class="form-select" id="district_id" name="district_id">
                                    <option value="">All districts</option>
                                    @foreach ($districts as $district)
                                        <option value="{{ $district->id }}" @selected((string) $filters['district_id'] === (string) $district->id)>{{ $district->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold" for="transporter_id">Transporter</label>
                                <select class="form-select" id="transporter_id" name="transporter_id">
                                    <option value="">All transporters</option>
                                    @foreach ($transporters as $transporter)
                                        <option value="{{ $transporter->id }}" @selected((string) $filters['transporter_id'] === (string) $transporter->id)>{{ $transporter->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold" for="route_id">Route</label>
                                <select class="form-select" id="route_id" name="route_id">
                                    <option value="">All routes</option>
                                    @foreach ($routes as $route)
                                        <option value="{{ $route->id }}" @selected((string) $filters['route_id'] === (string) $route->id)>{{ $route->route_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold" for="from_date">From Date</label>
                                <input class="form-control" id="from_date" name="from_date" type="date" value="{{ $filters['from_date'] }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold" for="to_date">To Date</label>
                                <input class="form-control" id="to_date" name="to_date" type="date" value="{{ $filters['to_date'] }}">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card section-card table-card mb-4">
                <div class="card-header">
                    <div class="table-toolbar">
                        <div>
                            <h3 class="section-title">Payment Records</h3>
                            <p class="section-copy">Transporter payment entries generated from saved trip activity.</p>
                        </div>
                        @if ($canManagePayments)
                            <div class="d-flex flex-wrap gap-2">
                                <button class="btn btn-success" form="bulkApprovePaymentsForm" type="submit">
                                    <i class="fa-solid fa-check-double me-2"></i>Approve Selected
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('payments.bulk-approve') }}" id="bulkApprovePaymentsForm" class="d-none">
                        @csrf
                        @method('PATCH')
                    </form>
                    <div class="table-shell table-wrap">
                        <table class="table table-app align-middle">
                            <thead>
                                <tr>
                                    @if ($canManagePayments)
                                        <th class="text-center text-nowrap" style="width: 56px;">
                                            <input type="checkbox" class="form-check-input js-select-all-payments" aria-label="Select all due payments">
                                        </th>
                                    @endif
                                    <th>Sr #</th>
                                    <th>Trip Date</th>
                                    <th>Transporter</th>
                                    <th>Vehicle</th>
                                    <th>Route</th>
                                    <th>Trips</th>
                                    <th>Fare</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($payments as $payment)
                                    <tr>
                                        @if ($canManagePayments)
                                            <td class="text-center">
                                                @if ($payment->status === 'due')
                                                    <input
                                                        type="checkbox"
                                                        name="payment_ids[]"
                                                        value="{{ $payment->id }}"
                                                        class="form-check-input js-payment-checkbox"
                                                        form="bulkApprovePaymentsForm"
                                                        aria-label="Select payment {{ $payment->id }}"
                                                    >
                                                @endif
                                            </td>
                                        @endif
                                        <td>{{ $payments->firstItem() + $loop->index }}</td>
                                        <td class="text-nowrap">{{ $payment->trip?->trip_date?->format('Y-m-d') ?: 'N/A' }}</td>
                                        <td>{{ $payment->transporter?->name ?: 'N/A' }}</td>
                                        <td>{{ $payment->vehicle?->registration_no ?: 'N/A' }}</td>
                                        <td>{{ $payment->route?->route_name ?: 'N/A' }}</td>
                                        <td>{{ $payment->no_of_trips }}</td>
                                        <td>{{ number_format((float) $payment->fare_amount, 2) }}</td>
                                        <td>{{ number_format((float) $payment->total_amount, 2) }}</td>
                                        <td><span class="{{ $statusBadges[$payment->status] ?? 'badge-soft-muted' }}">{{ $statuses[$payment->status] ?? ucfirst($payment->status) }}</span></td>
                                        <td class="text-center text-nowrap">
                                            <div class="action-stack justify-content-center">
                                                <a href="{{ route('payments.show', $payment) }}" class="action-btn btn-view" title="View Payment">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>
                                                @if ($canManagePayments && $payment->status === 'due')
                                                    <form method="POST" action="{{ route('payments.approve', $payment) }}" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="action-btn btn-approve border-0" title="Approve Payment">
                                                            <i class="fa-solid fa-check"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                @if ($canManagePayments && $payment->status === 'due')
                                                    <form method="POST" action="{{ route('payments.reject', $payment) }}" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="action-btn btn-reject border-0" title="Reject Payment">
                                                            <i class="fa-solid fa-xmark"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="{{ $canManagePayments ? 11 : 10 }}" class="text-center text-muted py-4">No payment records found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @include('settings.partials.pagination', ['paginator' => $payments, 'perPage' => $perPage])
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        (function () {
            var selectAll = document.querySelector('.js-select-all-payments');
            var itemSelector = '.js-payment-checkbox';

            if (!selectAll) {
                return;
            }

            var syncSelectAll = function () {
                var checkboxes = Array.from(document.querySelectorAll(itemSelector));
                var checkedCount = checkboxes.filter(function (checkbox) {
                    return checkbox.checked;
                }).length;

                selectAll.checked = checkboxes.length > 0 && checkedCount === checkboxes.length;
                selectAll.indeterminate = checkedCount > 0 && checkedCount < checkboxes.length;
            };

            selectAll.addEventListener('change', function () {
                document.querySelectorAll(itemSelector).forEach(function (checkbox) {
                    checkbox.checked = selectAll.checked;
                });
                syncSelectAll();
            });

            document.querySelectorAll(itemSelector).forEach(function (checkbox) {
                checkbox.addEventListener('change', syncSelectAll);
            });

            syncSelectAll();
        })();
    </script>
@endpush
