@extends('layouts.app', ['title' => 'Payment Details | Free Public Transport System', 'pageBadge' => 'Payments'])

@section('content')
    <style>
        .payment-summary-card {
            border-radius: 1.25rem;
            border: 1px solid #e4ebf2;
            background: linear-gradient(135deg, #ffffff 0%, #f6faf7 100%);
            box-shadow: 0 20px 40px rgba(32, 52, 84, 0.08);
        }

        .payment-summary-strip {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 0.85rem;
        }

        .payment-mini-card {
            border-radius: 0.95rem;
            background: #f8fafc;
            border: 1px solid #e5ebf1;
            padding: 0.8rem 0.9rem;
        }

        .payment-mini-card .mini-note {
            margin-bottom: 0.25rem;
            font-size: 0.76rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .payment-mini-card .payment-value {
            margin: 0;
            font-size: 1rem;
            font-weight: 700;
            color: #203454;
            line-height: 1.3;
        }

        .payment-section + .payment-section {
            margin-top: 1.25rem;
        }

        .payment-section-title {
            margin-bottom: 0.85rem;
            font-size: 0.98rem;
            font-weight: 800;
            color: #203454;
        }

        .payment-hero-actions {
            display: inline-flex;
            align-items: center;
            justify-content: flex-end;
            flex-wrap: wrap;
            gap: 0.65rem;
        }

        .payment-hero-actions form {
            margin: 0;
        }

        .payment-top-action {
            width: 42px;
            height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.9rem;
            border: 1px solid rgba(255, 255, 255, 0.22);
            background: rgba(255, 255, 255, 0.08);
            color: #fff;
            box-shadow: 0 12px 28px rgba(10, 24, 18, 0.18);
            transition: transform 0.14s ease, box-shadow 0.14s ease, background-color 0.14s ease;
        }

        .payment-top-action:hover,
        .payment-top-action:focus {
            transform: translateY(-1px);
            box-shadow: 0 16px 30px rgba(10, 24, 18, 0.22);
            color: #fff;
        }

        .payment-top-action.is-back:hover,
        .payment-top-action.is-back:focus {
            background: rgba(255, 255, 255, 0.14);
        }

        .payment-top-action.is-approve {
            background: #2563eb;
            border-color: #2563eb;
        }

        .payment-top-action.is-approve:hover,
        .payment-top-action.is-approve:focus {
            background: #1d4ed8;
        }

        .payment-top-action.is-reject {
            background: #dc3545;
            border-color: #dc3545;
        }

        .payment-top-action.is-reject:hover,
        .payment-top-action.is-reject:focus {
            background: #bb2d3b;
        }

        .payment-top-action.is-hold {
            background: #0ea5e9;
            border-color: #0ea5e9;
        }

        .payment-top-action.is-hold:hover,
        .payment-top-action.is-hold:focus {
            background: #0284c7;
        }

        .payment-detail-tile {
            border-radius: 0.95rem;
            background: #f8fafc;
            border: 1px solid #e5ebf1;
            padding: 0.8rem 0.95rem;
            min-height: 88px;
        }

        .payment-detail-tile .mini-note {
            margin-bottom: 0.22rem;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .payment-detail-value {
            margin: 0;
            font-size: 0.95rem;
            font-weight: 700;
            color: #203454;
            line-height: 1.35;
        }

        .payment-detail-subvalue {
            margin-top: 0.2rem;
            font-size: 0.8rem;
            color: #6d7a8d;
            line-height: 1.35;
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

        @media (max-width: 991.98px) {
            .payment-summary-strip {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 575.98px) {
            .payment-summary-strip {
                grid-template-columns: 1fr;
            }

            .payment-hero-actions {
                justify-content: flex-start;
            }
        }
    </style>

    <div class="page-hero d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
        <div>
            <p class="page-eyebrow">Payments</p>
            <h1 class="page-title">{{ $payment->transporter?->name ?: 'Transporter Payment' }}</h1>
            <p class="page-subtitle">Complete payment summary for the selected transporter trip entry, with route, vehicle, driver, and fare details.</p>
        </div>
        <div class="payment-hero-actions">
            <a class="payment-top-action is-back text-decoration-none" href="{{ route('payments.index') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Back to Payments" aria-label="Back to Payments">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            @if (auth()->user()?->isSuperadmin())
                <button class="payment-top-action border-0" type="button" style="background:#fff3cd;color:#997404;" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Payment Status" aria-label="Edit Payment Status" data-payment-action="edit-status">
                    <i class="fa-solid fa-pen-to-square"></i>
                </button>
            @endif
            @if ($canManagePayments && in_array($payment->status, ['due', 'on_hold'], true))
                <form method="POST" action="{{ route('payments.approve', $payment) }}">
                    @csrf
                    @method('PATCH')
                    <button class="payment-top-action is-approve border-0" type="submit" data-bs-toggle="tooltip" data-bs-placement="top" title="Approve Payment" aria-label="Approve Payment">
                        <i class="fa-solid fa-check"></i>
                    </button>
                </form>
            @endif
            @if ($canManagePayments && $payment->status === 'due')
                <button class="payment-top-action is-hold border-0" type="button" data-bs-toggle="tooltip" data-bs-placement="top" title="Put Payment On Hold" aria-label="Put Payment On Hold" data-payment-action="hold">
                    <i class="fa-solid fa-pause"></i>
                </button>
            @endif
            @if ($canManagePayments && in_array($payment->status, ['due', 'on_hold'], true))
                <button class="payment-top-action is-reject border-0" type="button" data-bs-toggle="tooltip" data-bs-placement="top" title="Reject Payment" aria-label="Reject Payment" data-payment-action="reject">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            @endif
        </div>
    </div>

    <section class="row g-4 stats-overlap">
        <div class="col-12">
            <div class="card payment-summary-card">
                <div class="card-body p-4">
                    <div class="payment-summary-strip">
                        <div class="payment-mini-card">
                            <p class="mini-note">Payment Status</p>
                            <p class="payment-value"><span class="{{ $statusBadge }}">{{ $statuses[$payment->status] ?? ucfirst($payment->status) }}</span></p>
                        </div>
                        <div class="payment-mini-card">
                            <p class="mini-note">Trip Date</p>
                            <p class="payment-value">{{ $payment->calculation_date?->format('Y-m-d') ?: 'N/A' }}</p>
                        </div>
                        <div class="payment-mini-card">
                            <p class="mini-note">Total Trips</p>
                            <p class="payment-value">{{ $payment->no_of_trips }}</p>
                        </div>
                        <div class="payment-mini-card">
                            <p class="mini-note">Total Payable</p>
                            <p class="payment-value">{{ number_format((float) $payment->total_amount, 2) }}</p>
                        </div>
                    </div>
                    @php
                        $transporter = $payment->transporter;
                        $hasBankDetails = filled($transporter?->bank_name) || filled($transporter?->bank_account_title) || filled($transporter?->bank_account_no);
                        $hasTransporterCnic = filled($transporter?->cnic);
                        $hasEasyPaisa = filled($transporter?->easypaisa_no);
                        $hasJazzCash = filled($transporter?->jazzcash_no);
                        $hasReason = filled($payment->remarks);
                        $approvalDate = $payment->status === 'paid' ? $payment->updated_at?->format('Y-m-d') : null;
                    @endphp

                    <div class="row g-3 mt-1">
                        @if ($hasTransporterCnic)
                            <div class="col-md-4">
                                <div class="payment-detail-tile">
                                    <p class="mini-note">Transporter CNIC</p>
                                    <p class="payment-detail-value">{{ $transporter->cnic }}</p>
                                </div>
                            </div>
                        @endif

                        @if ($hasEasyPaisa)
                            <div class="col-md-4">
                                <div class="payment-detail-tile">
                                    <p class="mini-note">EasyPaisa</p>
                                    <p class="payment-detail-value">{{ $transporter->easypaisa_no }}</p>
                                </div>
                            </div>
                        @endif

                        @if ($approvalDate)
                            <div class="col-md-4">
                                <div class="payment-detail-tile">
                                    <p class="mini-note">Approve Date</p>
                                    <p class="payment-detail-value">{{ $approvalDate }}</p>
                                </div>
                            </div>
                        @endif

                        @if ($hasReason)
                            <div class="col-md-4">
                                <div class="payment-detail-tile">
                                    <p class="mini-note">Reason</p>
                                    <p class="payment-detail-value">{{ $payment->remarks }}</p>
                                </div>
                            </div>
                        @endif

                        @if ($hasJazzCash)
                            <div class="col-md-4">
                                <div class="payment-detail-tile">
                                    <p class="mini-note">JazzCash</p>
                                    <p class="payment-detail-value">{{ $transporter->jazzcash_no }}</p>
                                </div>
                            </div>
                        @endif

                        @if ($hasBankDetails)
                            <div class="col-md-4">
                                <div class="payment-detail-tile">
                                    <p class="mini-note">Bank Name</p>
                                    <p class="payment-detail-value">{{ $transporter?->bank_name ?: 'Not provided' }}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="payment-detail-tile">
                                    <p class="mini-note">Account Title</p>
                                    <p class="payment-detail-value">{{ $transporter?->bank_account_title ?: 'Not provided' }}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="payment-detail-tile">
                                    <p class="mini-note">Account Number</p>
                                    <p class="payment-detail-value">{{ $transporter?->bank_account_no ?: 'Not provided' }}</p>
                                </div>
                            </div>
                        @endif

                        @if (! $hasEasyPaisa && ! $hasJazzCash && ! $hasBankDetails)
                            <div class="col-12">
                                <div class="payment-detail-tile">
                                    <p class="mini-note">Payment Method</p>
                                    <p class="payment-detail-value">No payment details saved for this transporter.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="row g-4 mt-2">
        <div class="col-12 col-xl-6">
            <div class="card section-card h-100">
                <div class="card-header">
                    <h3 class="section-title">Transporter & Trip</h3>
                    <p class="section-copy">Primary payment record information for the assigned transporter and trip.</p>
                </div>
                <div class="card-body">
                    <div class="payment-section">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="payment-detail-tile">
                                    <p class="mini-note">Transporter Name</p>
                                    <p class="payment-detail-value">{{ $payment->transporter?->name ?: 'N/A' }}</p>
                                    <p class="payment-detail-subvalue">{{ $payment->transporter?->phone ?: 'Phone not available' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="payment-detail-tile">
                                    <p class="mini-note">Vehicle Number</p>
                                    <p class="payment-detail-value">{{ $payment->vehicle?->registration_no ?: 'N/A' }}</p>
                                    <p class="payment-detail-subvalue">{{ $payment->vehicle?->vehicleType?->name ?: 'Vehicle type not available' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="payment-detail-tile">
                                    <p class="mini-note">Route Name</p>
                                    <p class="payment-detail-value">{{ $payment->route?->route_name ?: 'N/A' }}</p>
                                    <p class="payment-detail-subvalue">{{ $payment->route?->starting_point ?: 'N/A' }} to {{ $payment->route?->ending_point ?: 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="payment-detail-tile">
                                    <p class="mini-note">District</p>
                                    <p class="payment-detail-value">{{ $payment->route?->district?->name ?: ($payment->trip?->district?->name ?: 'N/A') }}</p>
                                    <p class="payment-detail-subvalue">{{ $payment->route?->timing ?: 'Route timing not available' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="payment-detail-tile">
                                    <p class="mini-note">Driver Name</p>
                                    <p class="payment-detail-value">{{ $payment->trip?->driver_name ?: 'N/A' }}</p>
                                    <p class="payment-detail-subvalue">{{ $payment->trip?->driver_mobile ?: 'Mobile not available' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="payment-detail-tile">
                                    <p class="mini-note">Driver CNIC</p>
                                    <p class="payment-detail-value">{{ $payment->trip?->driver_cnic ?: 'N/A' }}</p>
                                    <p class="payment-detail-subvalue">Saved by {{ $payment->trip?->creator?->name ?: 'system user' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-6">
            <div class="card section-card h-100">
                <div class="card-header">
                    <h3 class="section-title">Fare & Payment Breakdown</h3>
                    <p class="section-copy">Calculated fare values used to build this transporter payment entry.</p>
                </div>
                <div class="card-body">
                    <div class="payment-section">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="payment-detail-tile">
                                    <p class="mini-note">Fare Per Trip</p>
                                    <p class="payment-detail-value">{{ number_format((float) $payment->fare_amount, 2) }}</p>
                                    <p class="payment-detail-subvalue">Base fare linked to the selected route.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="payment-detail-tile">
                                    <p class="mini-note">Number of Trips</p>
                                    <p class="payment-detail-value">{{ $payment->no_of_trips }}</p>
                                    <p class="payment-detail-subvalue">Total trips recorded for this entry.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="payment-detail-tile">
                                    <p class="mini-note">Calculation Date</p>
                                    <p class="payment-detail-value">{{ $payment->calculation_date?->format('Y-m-d') ?: 'N/A' }}</p>
                                    <p class="payment-detail-subvalue">Payment generated against this trip date.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="payment-detail-tile">
                                    <p class="mini-note">Total Amount</p>
                                    <p class="payment-detail-value">{{ number_format((float) $payment->total_amount, 2) }}</p>
                                    <p class="payment-detail-subvalue">Fare amount multiplied by total trips.</p>
                                </div>
                            </div>
                        </div>
                    </div>
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
                        <div class="mb-3" id="paymentStatusSelectWrapper">
                            <label class="form-label fw-semibold" for="payment_status_value">Status <span class="text-danger">*</span></label>
                            <select class="form-select" id="payment_status_value" name="status" data-no-select2>
                                @foreach ($statuses as $statusValue => $statusLabel)
                                    <option value="{{ $statusValue }}">{{ $statusLabel }}</option>
                                @endforeach
                            </select>
                        </div>
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

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                if (!window.bootstrap) {
                    return;
                }

                if (window.bootstrap.Tooltip) {
                    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (element) {
                        window.bootstrap.Tooltip.getOrCreateInstance(element);
                    });
                }

                var modalElement = document.getElementById('paymentStatusReasonModal');
                var modalForm = document.getElementById('paymentStatusReasonForm');
                var modalTitle = document.getElementById('paymentStatusReasonModalLabel');
                var modalHelp = document.getElementById('paymentStatusReasonHelp');
                var modalStatusSelectWrapper = document.getElementById('paymentStatusSelectWrapper');
                var modalStatusField = document.getElementById('payment_status_value');
                var modalReasonField = document.getElementById('payment_status_reason');
                var modalSubmitButton = document.getElementById('paymentStatusReasonSubmit');
                var updateStatusUrl = @json(route('payments.status.update', $payment));
                var holdUrl = @json(route('payments.hold', $payment));
                var rejectUrl = @json(route('payments.reject', $payment));
                var currentStatus = @json($payment->status);
                var currentReason = @json($payment->remarks);
                var shouldOpenEditModal = @json(request()->query('edit') === 'status');

                var syncReasonRequirement = function () {
                    if (!modalStatusField || !modalReasonField) {
                        return;
                    }

                    var reasonRequired = ['on_hold', 'rejected'].includes(modalStatusField.value);
                    modalReasonField.required = reasonRequired;
                    modalReasonField.previousElementSibling.innerHTML = reasonRequired
                        ? 'Reason <span class="text-danger">*</span>'
                        : 'Reason <span class="text-muted">(optional)</span>';
                    modalReasonField.placeholder = reasonRequired
                        ? (modalStatusField.value === 'on_hold'
                            ? 'Explain why this payment is on hold...'
                            : 'Explain why this payment is rejected...')
                        : 'Add an optional note for this status change...';
                };

                var openStatusEditor = function (config) {
                    if (!modalElement || !modalForm || !modalReasonField || !modalStatusField) {
                        return;
                    }

                    var mode = config.mode || 'edit';

                    if (mode === 'edit') {
                        modalForm.action = updateStatusUrl;
                        modalTitle.textContent = 'Edit Payment Status';
                        modalHelp.textContent = 'Update the payment status if this record was marked incorrectly.';
                        modalStatusSelectWrapper.classList.remove('d-none');
                        modalStatusField.value = config.status || currentStatus;
                        modalReasonField.value = config.reason ?? (currentReason || '');
                        modalSubmitButton.textContent = 'Update Status';
                    } else {
                        var isHold = mode === 'hold';
                        modalForm.action = isHold ? holdUrl : rejectUrl;
                        modalTitle.textContent = isHold ? 'Put Payment On Hold' : 'Reject Payment';
                        modalHelp.textContent = isHold
                            ? 'Add the reason for putting this payment on hold.'
                            : 'Add the reason for rejecting this payment.';
                        modalStatusSelectWrapper.classList.add('d-none');
                        modalStatusField.value = isHold ? 'on_hold' : 'rejected';
                        modalReasonField.value = '';
                        modalSubmitButton.textContent = isHold ? 'Mark On Hold' : 'Reject Payment';
                    }

                    syncReasonRequirement();
                    window.bootstrap.Modal.getOrCreateInstance(modalElement).show();
                };

                if (modalStatusField) {
                    modalStatusField.addEventListener('change', syncReasonRequirement);
                }

                document.querySelectorAll('[data-payment-action]').forEach(function (button) {
                    button.addEventListener('click', function () {
                        var action = button.getAttribute('data-payment-action');

                        if (action === 'edit-status') {
                            openStatusEditor({
                                mode: 'edit',
                            });
                            return;
                        }

                        openStatusEditor({
                            mode: action,
                        });
                    });
                });

                if (shouldOpenEditModal) {
                    openStatusEditor({
                        mode: 'edit',
                    });
                }
            });
        </script>
    @endpush
@endsection
