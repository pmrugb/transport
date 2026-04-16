<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments PDF View</title>
    <style>
        :root {
            --ink: #1f2937;
            --muted: #6b7280;
            --line: #d7dee7;
            --soft: #f4f7fb;
            --soft-2: #eef3f8;
            --brand: #295c48;
            --brand-soft: #e7f1ed;
            --danger: #9f2f2f;
            --danger-soft: #fdecec;
            --warning: #8a5a12;
            --warning-soft: #fff4dd;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 24px;
            font-family: "Inter", "Segoe UI", Arial, sans-serif;
            color: var(--ink);
            background: #f3f6fa;
        }

        .page-shell {
            max-width: 1180px;
            margin: 0 auto;
        }

        .toolbar {
            margin-bottom: 18px;
        }

        .toolbar button {
            border: 0;
            border-radius: 10px;
            background: var(--brand);
            color: #fff;
            font-size: 14px;
            font-weight: 700;
            padding: 10px 16px;
            cursor: pointer;
        }

        .report-header {
            background: linear-gradient(135deg, #ffffff 0%, #eef4f8 100%);
            border: 1px solid var(--line);
            border-radius: 18px;
            padding: 24px 26px;
            margin-bottom: 18px;
        }

        .eyebrow {
            margin: 0 0 6px;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--brand);
        }

        .title-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
            margin-bottom: 14px;
        }

        h1 {
            margin: 0;
            font-size: 30px;
            line-height: 1.1;
        }

        .subtitle {
            margin: 8px 0 0;
            font-size: 14px;
            color: var(--muted);
            max-width: 680px;
        }

        .generated-at {
            min-width: 180px;
            text-align: right;
            font-size: 12px;
            color: var(--muted);
        }

        .chip-row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: 1px solid var(--line);
            border-radius: 999px;
            background: #fff;
            padding: 8px 12px;
            font-size: 12px;
            color: var(--ink);
        }

        .chip-label {
            font-weight: 800;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.04em;
            font-size: 11px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
            margin-bottom: 18px;
        }

        .stat-card {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 16px;
            padding: 16px 18px;
        }

        .stat-label {
            margin: 0 0 8px;
            font-size: 12px;
            font-weight: 700;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .stat-value {
            margin: 0;
            font-size: 24px;
            font-weight: 800;
            line-height: 1.1;
        }

        .records-wrap {
            display: grid;
            gap: 16px;
        }

        .payment-card {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 18px;
            overflow: hidden;
            page-break-inside: avoid;
        }

        .payment-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
            padding: 18px 20px 14px;
            background: #fbfdff;
            border-bottom: 1px solid var(--line);
        }

        .payment-card-title {
            margin: 0 0 6px;
            font-size: 20px;
            line-height: 1.2;
        }

        .payment-card-copy {
            margin: 0;
            font-size: 13px;
            color: var(--muted);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            padding: 8px 12px;
            font-size: 12px;
            font-weight: 800;
            white-space: nowrap;
        }

        .status-paid {
            background: var(--brand-soft);
            color: var(--brand);
        }

        .status-rejected {
            background: var(--danger-soft);
            color: var(--danger);
        }

        .status-due {
            background: var(--warning-soft);
            color: var(--warning);
        }

        .payment-meta {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
            padding: 16px 20px 0;
        }

        .meta-box {
            background: var(--soft);
            border: 1px solid #e7edf4;
            border-radius: 14px;
            padding: 12px 14px;
        }

        .meta-box-label {
            margin: 0 0 6px;
            font-size: 11px;
            color: var(--muted);
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .meta-box-value {
            margin: 0;
            font-size: 15px;
            font-weight: 700;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
            padding: 18px 20px 20px;
        }

        .detail-section {
            border: 1px solid var(--line);
            border-radius: 16px;
            overflow: hidden;
        }

        .detail-section-title {
            margin: 0;
            padding: 12px 14px;
            background: var(--soft-2);
            border-bottom: 1px solid var(--line);
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .detail-table {
            width: 100%;
            border-collapse: collapse;
        }

        .detail-table tr + tr td {
            border-top: 1px solid #edf1f6;
        }

        .detail-table td {
            padding: 10px 14px;
            vertical-align: top;
            font-size: 13px;
        }

        .detail-key {
            width: 42%;
            color: var(--muted);
            font-weight: 700;
        }

        .detail-value {
            font-weight: 600;
            word-break: break-word;
        }

        .empty-state {
            background: #fff;
            border: 1px dashed var(--line);
            border-radius: 18px;
            padding: 42px 24px;
            text-align: center;
        }

        .empty-state h2 {
            margin: 0 0 8px;
            font-size: 24px;
        }

        .empty-state p {
            margin: 0;
            color: var(--muted);
        }

        @media (max-width: 980px) {
            .stats-grid,
            .payment-meta,
            .detail-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media print {
            @page {
                size: A4 portrait;
                margin: 12mm;
            }

            body {
                padding: 0;
                background: #fff;
            }

            .toolbar {
                display: none;
            }

            .report-header,
            .stat-card,
            .payment-card,
            .meta-box,
            .detail-section {
                box-shadow: none;
            }

            .report-header,
            .payment-card {
                break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    @php
        $totalRecords = $payments->count();
        $paidAmount = (float) $payments->where('status', 'paid')->sum('total_amount');
        $dueAmount = (float) $payments->where('status', 'due')->sum('total_amount');
        $rejectedCount = $payments->where('status', 'rejected')->count();
    @endphp

    <div class="page-shell">
        <div class="toolbar">
            <button onclick="window.print()">Print / Save PDF</button>
        </div>

        <section class="report-header">
            <p class="eyebrow">Free Public Transport System</p>
            <div class="title-row">
                <div>
                    <h1>Payments Report</h1>
                    <p class="subtitle">A cleaner print-ready summary of filtered transporter payment records, with operational, routing, and settlement details.</p>
                </div>
                <div class="generated-at">
                    <strong>Generated On</strong><br>
                    {{ now()->format('d-m-Y h:i A') }}
                </div>
            </div>

            <div class="chip-row">
                <div class="chip"><span class="chip-label">Status</span> <span>{{ $filters['status'] ?: 'All' }}</span></div>
                <div class="chip"><span class="chip-label">District</span> <span>{{ $districtLabel ?: 'All' }}</span></div>
                <div class="chip"><span class="chip-label">From</span> <span>{{ $filters['from_date'] ?: 'N/A' }}</span></div>
                <div class="chip"><span class="chip-label">To</span> <span>{{ $filters['to_date'] ?: 'N/A' }}</span></div>
            </div>
        </section>

        <section class="stats-grid">
            <div class="stat-card">
                <p class="stat-label">Total Records</p>
                <p class="stat-value">{{ $totalRecords }}</p>
            </div>
            <div class="stat-card">
                <p class="stat-label">Paid Amount</p>
                <p class="stat-value">{{ number_format($paidAmount, 2) }}</p>
            </div>
            <div class="stat-card">
                <p class="stat-label">Due Amount</p>
                <p class="stat-value">{{ number_format($dueAmount, 2) }}</p>
            </div>
            <div class="stat-card">
                <p class="stat-label">Rejected Records</p>
                <p class="stat-value">{{ $rejectedCount }}</p>
            </div>
        </section>

        @if ($payments->isEmpty())
            <section class="empty-state">
                <h2>No payment records found</h2>
                <p>The current filter combination did not return any payment data.</p>
            </section>
        @else
            <section class="records-wrap">
                @foreach ($payments as $payment)
                    @php
                        $trip = $payment->trip;
                        $route = $payment->route;
                        $vehicle = $payment->vehicle;
                        $transporter = $payment->transporter;
                        $statusClass = match ($payment->status) {
                            'paid' => 'status-paid',
                            'rejected' => 'status-rejected',
                            default => 'status-due',
                        };
                    @endphp

                    <article class="payment-card">
                        <div class="payment-card-header">
                            <div>
                                <h2 class="payment-card-title">{{ $transporter?->name ?: 'Unknown Transporter' }}</h2>
                                <p class="payment-card-copy">
                                    {{ $route?->route_name ?: 'Route not available' }}
                                    @if ($route?->starting_point || $route?->ending_point)
                                        | {{ $route?->starting_point ?: 'N/A' }} to {{ $route?->ending_point ?: 'N/A' }}
                                    @endif
                                </p>
                            </div>
                            <span class="status-badge {{ $statusClass }}">
                                {{ \App\Models\TripCost::STATUSES[$payment->status] ?? ucfirst((string) $payment->status) }}
                            </span>
                        </div>

                        <div class="payment-meta">
                            <div class="meta-box">
                                <p class="meta-box-label">Payment Date</p>
                                <p class="meta-box-value">{{ $payment->calculation_date?->format('d-m-Y') ?: 'N/A' }}</p>
                            </div>
                            <div class="meta-box">
                                <p class="meta-box-label">No. of Trips</p>
                                <p class="meta-box-value">{{ $payment->no_of_trips }}</p>
                            </div>
                            <div class="meta-box">
                                <p class="meta-box-label">Fare Amount</p>
                                <p class="meta-box-value">{{ number_format((float) $payment->fare_amount, 2) }}</p>
                            </div>
                            <div class="meta-box">
                                <p class="meta-box-label">Total Amount</p>
                                <p class="meta-box-value">{{ number_format((float) $payment->total_amount, 2) }}</p>
                            </div>
                        </div>

                        <div class="detail-grid">
                            <section class="detail-section">
                                <h3 class="detail-section-title">Transporter Details</h3>
                                <table class="detail-table">
                                    <tr><td class="detail-key">Owner Type</td><td class="detail-value">{{ $transporter?->owner_type ? (\App\Models\Operator::OWNER_TYPES[$transporter->owner_type] ?? ucfirst((string) $transporter->owner_type)) : 'N/A' }}</td></tr>
                                    <tr><td class="detail-key">CNIC</td><td class="detail-value">{{ $transporter?->cnic ?: 'N/A' }}</td></tr>
                                    <tr><td class="detail-key">Phone</td><td class="detail-value">{{ $transporter?->phone ?: 'N/A' }}</td></tr>
                                    <tr><td class="detail-key">District</td><td class="detail-value">{{ $transporter?->district?->name ?: ($route?->district?->name ?: 'N/A') }}</td></tr>
                                    <tr><td class="detail-key">Address</td><td class="detail-value">{{ $transporter?->address ?: 'N/A' }}</td></tr>
                                    <tr><td class="detail-key">EasyPaisa</td><td class="detail-value">{{ $transporter?->easypaisa_no ?: 'N/A' }}</td></tr>
                                </table>
                            </section>

                            <section class="detail-section">
                                <h3 class="detail-section-title">Trip And Vehicle Details</h3>
                                <table class="detail-table">
                                    <tr><td class="detail-key">Trip Date</td><td class="detail-value">{{ $trip?->trip_date?->format('d-m-Y') ?: 'N/A' }}</td></tr>
                                    <tr><td class="detail-key">Trip Status</td><td class="detail-value">{{ $trip?->status ? (\App\Models\TripDetail::STATUSES[$trip->status] ?? ucfirst((string) $trip->status)) : 'N/A' }}</td></tr>
                                    <tr><td class="detail-key">Driver Name</td><td class="detail-value">{{ $trip?->driver_name ?: 'N/A' }}</td></tr>
                                    <tr><td class="detail-key">Driver CNIC</td><td class="detail-value">{{ $trip?->driver_cnic ?: 'N/A' }}</td></tr>
                                    <tr><td class="detail-key">Driver Mobile</td><td class="detail-value">{{ $trip?->driver_mobile ?: 'N/A' }}</td></tr>
                                    <tr><td class="detail-key">Vehicle Registration</td><td class="detail-value">{{ $vehicle?->registration_no ?: 'N/A' }}</td></tr>
                                    <tr><td class="detail-key">Vehicle Type</td><td class="detail-value">{{ $vehicle?->type?->name ?: 'N/A' }}</td></tr>
                                    <tr><td class="detail-key">Chassis No</td><td class="detail-value">{{ $vehicle?->chassis_no ?: 'N/A' }}</td></tr>
                                    <tr><td class="detail-key">Route District</td><td class="detail-value">{{ $route?->district?->name ?: 'N/A' }}</td></tr>
                                    <tr><td class="detail-key">Created By</td><td class="detail-value">{{ $trip?->creator?->name ?: 'N/A' }}</td></tr>
                                </table>
                            </section>

                            <section class="detail-section">
                                <h3 class="detail-section-title">Route Details</h3>
                                <table class="detail-table">
                                    <tr><td class="detail-key">Route</td><td class="detail-value">{{ $route?->route_name ?: 'N/A' }}</td></tr>
                                    <tr><td class="detail-key">Starting Point</td><td class="detail-value">{{ $route?->starting_point ?: 'N/A' }}</td></tr>
                                    <tr><td class="detail-key">Ending Point</td><td class="detail-value">{{ $route?->ending_point ?: 'N/A' }}</td></tr>
                                    <tr><td class="detail-key">Remarks</td><td class="detail-value">{{ $payment->remarks ?: ($trip?->remarks ?: 'N/A') }}</td></tr>
                                </table>
                            </section>

                            <section class="detail-section">
                                <h3 class="detail-section-title">Settlement Details</h3>
                                <table class="detail-table">
                                    <tr><td class="detail-key">Payment Status</td><td class="detail-value">{{ \App\Models\TripCost::STATUSES[$payment->status] ?? ucfirst((string) $payment->status) }}</td></tr>
                                    <tr><td class="detail-key">Payment Date</td><td class="detail-value">{{ $payment->calculation_date?->format('Y-m-d') ?: 'N/A' }}</td></tr>
                                    <tr><td class="detail-key">Fare Amount</td><td class="detail-value">{{ number_format((float) $payment->fare_amount, 2) }}</td></tr>
                                    <tr><td class="detail-key">Trips Count</td><td class="detail-value">{{ $payment->no_of_trips }}</td></tr>
                                    <tr><td class="detail-key">Total Payable</td><td class="detail-value">{{ number_format((float) $payment->total_amount, 2) }}</td></tr>
                                </table>
                            </section>
                        </div>
                    </article>
                @endforeach
            </section>
        @endif
    </div>
</body>
</html>
