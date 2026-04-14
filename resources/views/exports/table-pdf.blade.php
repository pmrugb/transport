<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        :root {
            --ink: #1f2937;
            --muted: #6b7280;
            --line: #d7dee7;
            --header: #eef3f8;
            --brand: #295c48;
            --soft: #f8fbf9;
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 22px;
            font-family: "Inter", "Segoe UI", Arial, sans-serif;
            color: var(--ink);
            background: #f5f7fb;
        }
        .page-shell { max-width: 1400px; margin: 0 auto; }
        .toolbar { margin-bottom: 16px; }
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
        h1 { margin: 0; font-size: 28px; line-height: 1.1; }
        .subtitle { margin: 8px 0 0; color: var(--muted); font-size: 14px; }
        .generated-at { text-align: right; font-size: 12px; color: var(--muted); min-width: 180px; }
        .filter-row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 12px;
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
        .table-card {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 18px;
            overflow: hidden;
        }
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 11px; }
        th, td {
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
        tbody tr:nth-child(even) { background: #fbfdff; }
        .empty-state { padding: 36px 24px; text-align: center; color: var(--muted); }
        @media print {
            @page { size: A4 landscape; margin: 10mm; }
            body { padding: 0; background: #fff; }
            .toolbar { display: none; }
            th { position: static; }
        }
    </style>
</head>
<body>
    <div class="page-shell">
        <div class="toolbar">
            <button type="button" onclick="window.print()">Print / Save PDF</button>
        </div>

        <section class="report-header">
            <p class="eyebrow">Export Report</p>
            <div class="header-row">
                <div>
                    <h1>{{ $title }}</h1>
                </div>
                <div class="generated-at">
                    <div><strong>Generated:</strong> {{ now()->format('Y-m-d H:i') }}</div>
                    <div><strong>Rows:</strong> {{ count($rows) }}</div>
                </div>
            </div>

            @if (!empty($filters))
                <div class="filter-row">
                    @foreach ($filters as $label => $value)
                        @if ($value !== null && $value !== '')
                            <span class="filter-chip"><strong>{{ $label }}</strong> <span>{{ $value }}</span></span>
                        @endif
                    @endforeach
                </div>
            @endif
        </section>

        <section class="table-card">
            <div class="table-wrap">
                @if ($rows !== [])
                    <table>
                        <thead>
                            <tr>
                                @foreach (array_values($columns) as $column)
                                    <th>{{ $column }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)
                                <tr>
                                    @foreach (array_values($row) as $value)
                                        <td>{{ $value === '' ? 'N/A' : $value }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="empty-state">No records found for the selected filters.</div>
                @endif
            </div>
        </section>
    </div>
</body>
</html>
