<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments PDF Table View</title>
    <style>
        :root {
            --ink: #1f2937;
            --muted: #6b7280;
            --line: #d7dee7;
            --header: #eef3f8;
            --brand: #295c48;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 22px;
            font-family: "Inter", "Segoe UI", Arial, sans-serif;
            color: var(--ink);
            background: #f5f7fb;
        }

        .page-shell {
            max-width: 1400px;
            margin: 0 auto;
        }

        .toolbar {
            margin-bottom: 16px;
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
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 18px;
            padding: 22px 24px;
            margin-bottom: 16px;
        }

        .eyebrow {
            margin: 0 0 6px;
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: var(--brand);
        }

        .header-row {
            display: flex;
            justify-content: space-between;
            gap: 18px;
            align-items: flex-start;
            margin-bottom: 14px;
        }

        h1 {
            margin: 0;
            font-size: 28px;
            line-height: 1.1;
        }

        .subtitle {
            margin: 8px 0 0;
            color: var(--muted);
            font-size: 14px;
        }

        .generated-at {
            text-align: right;
            font-size: 12px;
            color: var(--muted);
            min-width: 180px;
        }

        .filter-row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .filter-chip {
            display: inline-flex;
            gap: 8px;
            align-items: center;
            border: 1px solid var(--line);
            border-radius: 999px;
            padding: 7px 12px;
            font-size: 12px;
            background: #fbfdff;
        }

        .filter-chip strong {
            color: var(--muted);
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.04em;
        }

        .summary-row {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
            margin-bottom: 16px;
        }

        .summary-card {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 16px;
            padding: 14px 16px;
        }

        .summary-card p {
            margin: 0;
        }

        .summary-label {
            font-size: 12px;
            color: var(--muted);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 8px !important;
        }

        .summary-value {
            font-size: 22px;
            font-weight: 800;
        }

        .table-card {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 18px;
            overflow: hidden;
        }

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        th,
        td {
            border-bottom: 1px solid #e8edf3;
            padding: 8px 10px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background: var(--header);
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #415166;
            white-space: nowrap;
            position: sticky;
            top: 0;
        }

        tbody tr:nth-child(even) {
            background: #fbfdff;
        }

        .empty-state {
            padding: 36px 24px;
            text-align: center;
            color: var(--muted);
        }

        @media print {
            @page {
                size: A4 landscape;
                margin: 10mm;
            }

            body {
                padding: 0;
                background: #fff;
            }

            .toolbar {
                display: none;
            }

            .report-header,
            .summary-card,
            .table-card {
                box-shadow: none;
            }

            th {
                position: static;
            }
        }
    </style>
</head>
<body>
    @php
        $totalRecords = count($exportRows);
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
            <div class="header-row">
                <div>
                    <h1>Payments Report Table</h1>
                    <p class="subtitle">Table-style PDF formatted like a print-friendly spreadsheet for quick review and filing.</p>
                </div>
                <div class="generated-at">
                    <strong>Generated On</strong><br>
                    {{ now()->format('d-m-Y h:i A') }}
                </div>
            </div>

            <div class="filter-row">
                <div class="filter-chip"><strong>Status</strong> <span>{{ $filters['status'] ?: 'All' }}</span></div>
                <div class="filter-chip"><strong>District</strong> <span>{{ $districtLabel ?: 'All' }}</span></div>
                <div class="filter-chip"><strong>From</strong> <span>{{ $filters['from_date'] ?: 'N/A' }}</span></div>
                <div class="filter-chip"><strong>To</strong> <span>{{ $filters['to_date'] ?: 'N/A' }}</span></div>
            </div>
        </section>

        <section class="summary-row">
            <div class="summary-card"><p class="summary-label">Records</p><p class="summary-value">{{ $totalRecords }}</p></div>
            <div class="summary-card"><p class="summary-label">Paid Amount</p><p class="summary-value">{{ number_format($paidAmount, 2) }}</p></div>
            <div class="summary-card"><p class="summary-label">Due Amount</p><p class="summary-value">{{ number_format($dueAmount, 2) }}</p></div>
            <div class="summary-card"><p class="summary-label">Rejected</p><p class="summary-value">{{ $rejectedCount }}</p></div>
        </section>

        <section class="table-card">
            @if (empty($exportRows))
                <div class="empty-state">No payment records found for the current filters.</div>
            @else
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                @foreach (array_keys($exportRows[0]) as $heading)
                                    <th>{{ $heading }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($exportRows as $row)
                                <tr>
                                    @foreach ($row as $value)
                                        <td>{{ $value !== '' ? $value : 'N/A' }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>
    </div>
</body>
</html>
