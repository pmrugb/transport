@extends('layouts.app', ['title' => $pageHeading.' | Free Public Transport System', 'pageBadge' => 'Payments'])

@section('content')
    @php
        $currentStatusRoute = match ($currentStatus) {
            'due' => 'payments.due',
            'paid' => 'payments.paid',
            'on_hold' => 'payments.on-hold',
            'rejected' => 'payments.rejected',
            default => 'payments.index',
        };
    @endphp
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

        .payment-status-modal .modal-dialog {
            max-width: 440px;
        }

        .payment-status-modal .modal-content {
            border: 0;
            border-radius: 1.1rem;
            overflow: hidden;
            box-shadow: 0 28px 56px rgba(32, 52, 84, 0.18);
        }

        .payment-status-modal .modal-header {
            padding: 0.9rem 1rem 0.55rem;
            border-bottom: 0;
        }

        .payment-status-modal .modal-title {
            font-size: 0.98rem;
            font-weight: 800;
            color: #203454;
        }

        .payment-status-modal .modal-body {
            padding: 0 1rem 0.95rem;
        }

        .payment-status-modal .modal-footer {
            padding: 0.75rem 1rem 1rem;
            border-top: 0;
            gap: 0.55rem;
        }

        .payment-status-modal .btn-close {
            transform: scale(0.85);
            opacity: 0.7;
        }

        .payment-status-help {
            font-size: 0.8rem;
            line-height: 1.45;
            color: #6b7a8f;
            margin-bottom: 0.75rem;
        }

        .payment-status-modal .form-label {
            font-size: 0.8rem;
            margin-bottom: 0.35rem;
        }

        .payment-status-modal .form-control {
            min-height: 108px;
            resize: none;
            border-radius: 0.9rem;
            font-size: 0.84rem;
            line-height: 1.45;
            padding: 0.8rem 0.9rem;
        }

        .payment-status-modal .btn {
            border-radius: 0.8rem;
            font-size: 0.82rem;
            font-weight: 700;
            padding: 0.6rem 0.95rem;
        }

        .payment-table-search {
            max-width: 320px;
            margin-top: 0.85rem;
        }

        .payment-table-search .input-group-text {
            border-radius: 0.85rem 0 0 0.85rem;
            background: #f4f7fb;
            border-color: #dbe4ee;
            color: #6b7a8f;
            font-size: 0.82rem;
        }

        .payment-table-search .form-control {
            border-radius: 0 0.85rem 0.85rem 0;
            border-color: #dbe4ee;
            min-height: 40px;
            font-size: 0.84rem;
        }

        .payment-table-search-note {
            margin-top: 0.35rem;
            font-size: 0.76rem;
            color: #7a8799;
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
            <a class="btn {{ $currentStatus === 'on_hold' ? 'btn-success' : 'btn-outline-secondary' }}" href="{{ route('payments.on-hold') }}">On Hold</a>
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

    <section class="row g-4 mt-2" id="paymentsResultsRegion" data-live-region>
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
                            <a class="btn btn-outline-secondary" href="{{ route($currentStatusRoute) }}"><i class="fa-solid fa-rotate-right me-2"></i>Reset</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form method="get" action="{{ route($currentStatusRoute) }}" id="paymentFilters" data-live-submit-target="#paymentsResultsRegion">
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
                                        <option value="{{ $transporter->id }}" @selected((string) $filters['transporter_id'] === (string) $transporter->id)>{{ $transporter->name }}{{ $transporter->cnic ? ' - '.$transporter->cnic : '' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold" for="route_id">Route</label>
                                <select class="form-select" id="route_id" name="route_id">
                                    <option value="">All routes</option>
                                    @foreach ($routes as $route)
                                        <option value="{{ $route->id }}" @selected((string) $filters['route_id'] === (string) $route->id)>{{ $route->route_name }} ({{ $route->starting_point }} → {{ $route->ending_point }})</option>
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
                            <div class="payment-table-search">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
                                    <input class="form-control" id="paymentRecordSearch" type="text" placeholder="Search by trip date, transporter, vehicle, route, trips, fare, or total" autocomplete="off">
                                </div>
                                
                            </div>
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
                                    <tr
                                        data-payment-search="{{ strtolower(implode(' ', array_filter([
                                            $payment->trip?->trip_date?->format('Y-m-d'),
                                            $payment->transporter?->name,
                                            $payment->vehicle?->registration_no,
                                            $payment->route?->route_name,
                                            (string) $payment->no_of_trips,
                                            number_format((float) $payment->fare_amount, 2, '.', ''),
                                            number_format((float) $payment->total_amount, 2, '.', ''),
                                        ]))) }}"
                                    >
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
                                        <td>
                                            <span class="{{ $statusBadges[$payment->status] ?? 'badge-soft-muted' }}">{{ $statuses[$payment->status] ?? ucfirst($payment->status) }}</span>
                                            @if (in_array($payment->status, ['on_hold', 'rejected'], true) && filled($payment->remarks))
                                                <div class="small text-muted mt-1">{{ $payment->remarks }}</div>
                                            @endif
                                        </td>
                                        <td class="text-center text-nowrap">
                                            <div class="action-stack justify-content-center">
                                                <a href="{{ route('payments.show', $payment) }}" class="action-btn btn-view" data-bs-toggle="tooltip" data-bs-placement="top" title="View Payment" aria-label="View Payment">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>
                                                @if ($canManagePayments && in_array($payment->status, ['due', 'on_hold'], true))
                                                    <form method="POST" action="{{ route('payments.approve', $payment) }}" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="action-btn btn-approve border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Approve Payment" aria-label="Approve Payment">
                                                            <i class="fa-solid fa-check"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                @if ($canManagePayments && $payment->status === 'due')
                                                    <button
                                                        type="button"
                                                        class="action-btn border-0"
                                                        style="background:#e8f4fd;color:#0c63e7;"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        title="Put Payment On Hold"
                                                        aria-label="Put Payment On Hold"
                                                        data-bs-target="#paymentStatusReasonModal"
                                                        data-bs-toggle-modal="modal"
                                                        data-payment-action="hold"
                                                        data-payment-id="{{ $payment->id }}"
                                                        data-payment-transporter="{{ $payment->transporter?->name ?: 'N/A' }}"
                                                    >
                                                        <i class="fa-solid fa-pause"></i>
                                                    </button>
                                                @endif
                                                @if ($canManagePayments && in_array($payment->status, ['due', 'on_hold'], true))
                                                    <button
                                                        type="button"
                                                        class="action-btn btn-reject border-0"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        title="Reject Payment"
                                                        aria-label="Reject Payment"
                                                        data-bs-target="#paymentStatusReasonModal"
                                                        data-bs-toggle-modal="modal"
                                                        data-payment-action="reject"
                                                        data-payment-id="{{ $payment->id }}"
                                                        data-payment-transporter="{{ $payment->transporter?->name ?: 'N/A' }}"
                                                    >
                                                        <i class="fa-solid fa-xmark"></i>
                                                    </button>
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

    <div class="modal fade payment-status-modal" id="paymentStatusReasonModal" tabindex="-1" aria-labelledby="paymentStatusReasonModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" id="paymentStatusReasonForm" novalidate>
                    @csrf
                    @method('PATCH')
                    <div class="modal-header">
                        <h5 class="modal-title" id="paymentStatusReasonModalLabel">Update Payment Status</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="payment-status-help" id="paymentStatusReasonHelp">Add a reason for this status update.</p>
                        <div class="mb-0">
                            <label class="form-label fw-semibold" for="payment_status_reason">Reason <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="payment_status_reason" name="reason" rows="4" placeholder="Write the reason here..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-success" type="submit" id="paymentStatusReasonSubmit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        window.appInitPaymentsIndex = function (scope) {
            var root = scope && scope.querySelector ? scope : document;
            var selectAll = root.querySelector('.js-select-all-payments');
            var itemSelector = '.js-payment-checkbox';
            var tooltipElements = root.querySelectorAll('[data-bs-toggle="tooltip"]');
            var modalElement = document.getElementById('paymentStatusReasonModal');
            var modalForm = document.getElementById('paymentStatusReasonForm');
            var modalTitle = document.getElementById('paymentStatusReasonModalLabel');
            var modalHelp = document.getElementById('paymentStatusReasonHelp');
            var modalReasonField = document.getElementById('payment_status_reason');
            var modalSubmitButton = document.getElementById('paymentStatusReasonSubmit');
            var holdActionBaseUrl = @json(url('/payments/__PAYMENT__/hold'));
            var rejectActionBaseUrl = @json(url('/payments/__PAYMENT__/reject'));
            var paymentSearchField = root.querySelector('#paymentRecordSearch');
            var paymentRows = Array.from(root.querySelectorAll('table.table-app tbody tr[data-payment-search]'));

            if (window.bootstrap && window.bootstrap.Tooltip) {
                tooltipElements.forEach(function (element) {
                    window.bootstrap.Tooltip.getOrCreateInstance(element);
                });
            }

            if (paymentSearchField && paymentSearchField.dataset.bound !== 'true') {
                paymentSearchField.dataset.bound = 'true';
                paymentSearchField.addEventListener('keyup', function () {
                    var query = paymentSearchField.value.trim().toLowerCase().replace(/\s+/g, ' ');

                    paymentRows.forEach(function (row) {
                        var searchContent = (row.getAttribute('data-payment-search') || '').toLowerCase().replace(/\s+/g, ' ');
                        row.classList.toggle('d-none', query !== '' && searchContent.indexOf(query) === -1);
                    });
                });
            }

            root.querySelectorAll('[data-bs-toggle-modal="modal"]').forEach(function (button) {
                if (button.dataset.bound === 'true') {
                    return;
                }

                button.dataset.bound = 'true';
                button.addEventListener('click', function () {
                    if (!modalElement || !modalForm || !modalReasonField) {
                        return;
                    }

                    var action = button.getAttribute('data-payment-action');
                    var paymentId = button.getAttribute('data-payment-id');
                    var transporter = button.getAttribute('data-payment-transporter') || 'this transporter';
                    var isHold = action === 'hold';

                    modalForm.action = (isHold ? holdActionBaseUrl : rejectActionBaseUrl).replace('__PAYMENT__', paymentId);
                    modalTitle.textContent = isHold ? 'Put Payment On Hold' : 'Reject Payment';
                    modalHelp.textContent = (isHold ? 'Add the reason for putting this payment on hold for ' : 'Add the reason for rejecting the payment for ') + transporter + '.';
                    modalReasonField.value = '';
                    modalReasonField.placeholder = isHold
                        ? 'Explain why this payment is on hold...'
                        : 'Explain why this payment is rejected...';
                    modalSubmitButton.textContent = isHold ? 'Mark On Hold' : 'Reject Payment';

                    if (window.bootstrap) {
                        window.bootstrap.Modal.getOrCreateInstance(modalElement).show();
                    }
                });
            });

            if (!selectAll || selectAll.dataset.bound === 'true') {
                return;
            }

            selectAll.dataset.bound = 'true';

            var syncSelectAll = function () {
                var checkboxes = Array.from(root.querySelectorAll(itemSelector));
                var checkedCount = checkboxes.filter(function (checkbox) {
                    return checkbox.checked;
                }).length;

                selectAll.checked = checkboxes.length > 0 && checkedCount === checkboxes.length;
                selectAll.indeterminate = checkedCount > 0 && checkedCount < checkboxes.length;
            };

            selectAll.addEventListener('change', function () {
                root.querySelectorAll(itemSelector).forEach(function (checkbox) {
                    checkbox.checked = selectAll.checked;
                });
                syncSelectAll();
            });

            root.querySelectorAll(itemSelector).forEach(function (checkbox) {
                if (checkbox.dataset.bound === 'true') {
                    return;
                }

                checkbox.dataset.bound = 'true';
                checkbox.addEventListener('change', syncSelectAll);
            });

            syncSelectAll();
        };

        document.addEventListener('DOMContentLoaded', function () {
            window.appInitPaymentsIndex(document);
        });

        document.addEventListener('app:fragment-updated', function (event) {
            if (event.detail && event.detail.targetSelector === '#paymentsResultsRegion') {
                window.appInitPaymentsIndex(event.detail.container);
            }
        });
    </script>
@endpush
