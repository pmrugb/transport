<?php

namespace App\Http\Controllers;

use App\Models\Grant;
use App\Models\District;
use App\Models\Operator;
use App\Models\TransportRoute;
use App\Models\TripDetail;
use App\Models\TripCost;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\View\View;
use ZipArchive;

class PaymentController extends Controller
{
    public function index(Request $request): View
    {
        return $this->renderIndex($request, null, 'All Payments', 'Transporter payment records across due, paid, and rejected stages.');
    }

    public function due(Request $request): View
    {
        return $this->renderIndex($request, 'due', 'Due Payments', 'Pending transporter payments that are ready for review and approval.');
    }

    public function approved(Request $request): RedirectResponse
    {
        return redirect()->route('payments.paid', $request->query());
    }

    public function paid(Request $request): View
    {
        return $this->renderIndex($request, 'paid', 'Paid Payments', 'Payments that have already been settled with transporters.');
    }

    public function rejected(Request $request): View
    {
        return $this->renderIndex($request, 'rejected', 'Rejected Payments', 'Payments that were reviewed and rejected.');
    }

    public function show(TripCost $payment): View
    {
        return view('payments.show', [
            ...$this->sharedData(),
            'payment' => $payment->load(['trip.district', 'trip.creator', 'route.district', 'vehicle', 'transporter']),
            'statusBadge' => $this->statusBadge($payment->status),
        ]);
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $filename = 'payments-'.now()->format('Ymd-His').'.csv';

        return response()->streamDownload(function () use ($request): void {
            $handle = fopen('php://output', 'w');

            // Stream rows with a cursor so large exports do not hold the full dataset in memory.
            fputcsv($handle, array_keys($this->paymentExportRow(new TripCost())));

            foreach ($this->filteredPaymentsQuery($request)->cursor() as $payment) {
                fputcsv($handle, array_values($this->paymentExportRow($payment)));
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function exportExcel(Request $request): BinaryFileResponse
    {
        $payments = $this->filteredPaymentsQuery($request)->get();
        $filename = 'payments-'.now()->format('Ymd-His').'.xlsx';
        $tempPath = tempnam(sys_get_temp_dir(), 'payments-xlsx-');

        $this->buildExcelExport($tempPath, $payments->map(fn (TripCost $payment): array => $this->paymentExportRow($payment))->all());

        return response()->download(
            $tempPath,
            $filename,
            ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
        )->deleteFileAfterSend(true);
    }

    public function pdfView(Request $request): ViewContract
    {
        $payments = $this->filteredPaymentsQuery($request)->get();
        $rows = $payments->map(fn (TripCost $payment): array => $this->paymentExportRow($payment))->all();

        return view('payments.exports.pdf-view', [
            'payments' => $payments,
            'exportRows' => $rows,
            'filters' => $this->filterValues($request),
            'districtLabel' => $request->integer('district_id')
                ? District::query()->whereKey($request->integer('district_id'))->value('name')
                : null,
        ]);
    }

    public function pdfTableView(Request $request): ViewContract
    {
        $payments = $this->filteredPaymentsQuery($request)->get();
        $rows = $payments->map(fn (TripCost $payment): array => $this->paymentExportRow($payment))->all();

        return view('payments.exports.pdf-table-view', [
            'payments' => $payments,
            'exportRows' => $rows,
            'filters' => $this->filterValues($request),
            'districtLabel' => $request->integer('district_id')
                ? District::query()->whereKey($request->integer('district_id'))->value('name')
                : null,
        ]);
    }

    public function approve(TripCost $payment): RedirectResponse
    {
        $this->ensureSuperadmin();

        if (in_array($payment->status, ['paid', 'rejected'], true)) {
            return back()->with('error', 'This payment cannot be approved in its current state.');
        }

        $payment->update(['status' => 'paid']);

        return back()->with('success', 'Payment approved successfully and moved to paid payments.');
    }

    public function reject(TripCost $payment): RedirectResponse
    {
        $this->ensureSuperadmin();

        if ($payment->status === 'paid') {
            return back()->with('error', 'Paid payments cannot be rejected.');
        }

        $payment->update(['status' => 'rejected']);

        return back()->with('success', 'Payment rejected successfully.');
    }

    private function renderIndex(Request $request, ?string $status, string $heading, string $subtitle): View
    {
        $perPage = (int) $request->integer('per_page', 10);
        $perPage = in_array($perPage, [10, 25, 50, 100], true) ? $perPage : 10;

        $request->merge([
            'status' => $request->input('status', $status),
        ]);

        $paymentsQuery = $this->filteredPaymentsQuery($request);

        return view('payments.index', [
            ...$this->sharedData(),
            'currentStatus' => $status ?? 'all',
            'filters' => $this->filterValues($request),
            'pageHeading' => $heading,
            'pageSubtitle' => $subtitle,
            'perPage' => $perPage,
            'payments' => $paymentsQuery->paginate($perPage)->withQueryString(),
            'statusBadges' => [
                'due' => $this->statusBadge('due'),
                'paid' => $this->statusBadge('paid'),
                'rejected' => $this->statusBadge('rejected'),
            ],
        ]);
    }

    private function sharedData(): array
    {
        $paymentStats = TripCost::query()
            ->selectRaw('COUNT(*) as total')
            ->selectRaw("SUM(CASE WHEN status = 'due' THEN 1 ELSE 0 END) as due")
            ->selectRaw("COALESCE(SUM(CASE WHEN status = 'paid' THEN total_amount ELSE 0 END), 0) as paid_amount")
            ->first();
        $totalGrantAmount = (float) Grant::query()->sum('total_amount');
        $paidAmount = (float) ($paymentStats?->paid_amount ?? 0);

        return [
            'statuses' => TripCost::STATUSES,
            'districts' => District::query()->select(['id', 'name'])->orderBy('name')->get(),
            'transporters' => Operator::query()->select(['id', 'name'])->orderBy('name')->get(),
            'routes' => TransportRoute::query()->select(['id', 'route_name'])->orderBy('route_name')->get(),
            'canManagePayments' => auth()->user()?->isSuperadmin() ?? false,
            'stats' => [
                'total' => (int) ($paymentStats?->total ?? 0),
                'due' => (int) ($paymentStats?->due ?? 0),
                'paid_amount' => $paidAmount,
                'amount_left' => max($totalGrantAmount - $paidAmount, 0),
            ],
        ];
    }

    private function statusBadge(string $status): string
    {
        return match ($status) {
            'paid' => 'badge-soft-success',
            'rejected' => 'badge-soft-danger',
            default => 'badge-soft-warning',
        };
    }

    private function ensureSuperadmin(): void
    {
        abort_unless(auth()->user()?->isSuperadmin(), 403);
    }

    private function filteredPaymentsQuery(Request $request)
    {
        $filters = $this->filterValues($request);

        return TripCost::query()
            ->select([
                'id',
                'trip_id',
                'route_id',
                'vehicle_id',
                'transporter_id',
                'fare_amount',
                'no_of_trips',
                'total_amount',
                'calculation_date',
                'status',
                'remarks',
                'created_at',
            ])
            ->with([
                'trip:id,district_id,created_by,trip_date,driver_name,driver_cnic,driver_mobile,status,remarks',
                'trip.district:id,name',
                'trip.creator:id,name',
                'route:id,route_name,starting_point,ending_point,district_id',
                'route.district:id,name',
                'vehicle:id,registration_no,chassis_no,vehicle_type',
                'vehicle.type:id,name',
                'transporter:id,name,owner_type,cnic,phone,address,district_id,easypaisa_no,jazzcash_no,bank_name,bank_account_title,bank_account_no',
                'transporter.district:id,name',
            ])
            ->when($filters['status'], fn ($query, $status) => $query->where('status', $status))
            ->when($filters['district_id'], function ($query, $districtId) {
                $query->where(function ($nestedQuery) use ($districtId) {
                    $nestedQuery
                        ->whereHas('trip', fn ($tripQuery) => $tripQuery->where('district_id', $districtId))
                        ->orWhereHas('route', fn ($routeQuery) => $routeQuery->where('district_id', $districtId))
                        ->orWhereHas('transporter', fn ($transporterQuery) => $transporterQuery->where('district_id', $districtId));
                });
            })
            ->when($filters['transporter_id'], fn ($query, $transporterId) => $query->where('transporter_id', $transporterId))
            ->when($filters['route_id'], fn ($query, $routeId) => $query->where('route_id', $routeId))
            ->when($filters['from_date'], fn ($query, $fromDate) => $query->whereDate('calculation_date', '>=', $fromDate))
            ->when($filters['to_date'], fn ($query, $toDate) => $query->whereDate('calculation_date', '<=', $toDate))
            ->latest();
    }

    private function filterValues(Request $request): array
    {
        return [
            'status' => $request->input('status'),
            'district_id' => $request->integer('district_id') ?: null,
            'transporter_id' => $request->integer('transporter_id') ?: null,
            'route_id' => $request->integer('route_id') ?: null,
            'from_date' => $request->input('from_date'),
            'to_date' => $request->input('to_date'),
        ];
    }

    private function paymentExportRow(TripCost $payment): array
    {
        $trip = $payment->trip;
        $transporter = $payment->transporter;
        $vehicle = $payment->vehicle;
        $route = $payment->route;

        return [
            'Payment Date' => $payment->calculation_date?->format('Y-m-d') ?: '',
            'Payment Status' => TripCost::STATUSES[$payment->status] ?? ($payment->status === 'approved' ? 'Paid' : Str::headline($payment->status)),
            'Transporter' => $transporter?->name ?: '',
            'Owner Type' => $transporter?->owner_type ? (Operator::OWNER_TYPES[$transporter->owner_type] ?? Str::headline($transporter->owner_type)) : '',
            'Transporter CNIC' => $transporter?->cnic ?: '',
            'Transporter Phone' => $transporter?->phone ?: '',
            'Transporter Address' => $transporter?->address ?: '',
            'Transporter District' => $transporter?->district?->name ?: ($route?->district?->name ?: ''),
            'EasyPaisa Account' => $transporter?->easypaisa_no ?: '',
            'JazzCash Account' => $transporter?->jazzcash_no ?: '',
            'Bank Name' => $transporter?->bank_name ?: '',
            'Bank Account Title' => $transporter?->bank_account_title ?: '',
            'Bank Account Number' => $transporter?->bank_account_no ?: '',
            'Driver Name' => $trip?->driver_name ?: '',
            'Driver CNIC' => $trip?->driver_cnic ?: '',
            'Driver Mobile' => $trip?->driver_mobile ?: '',
            'Vehicle Registration' => $vehicle?->registration_no ?: '',
            'Vehicle Type' => $vehicle?->type?->name ?: '',
            'Vehicle Chassis No' => $vehicle?->chassis_no ?: '',
            'Route' => $route?->route_name ?: '',
            'Starting Point' => $route?->starting_point ?: '',
            'Ending Point' => $route?->ending_point ?: '',
            'Route District' => $route?->district?->name ?: '',
            'Trip Date' => $trip?->trip_date?->format('Y-m-d') ?: '',
            'No. of Trips' => $payment->no_of_trips,
            'Fare Amount' => (float) $payment->fare_amount,
            'Total Amount' => (float) $payment->total_amount,
            'Trip Status' => $trip?->status ? (TripDetail::STATUSES[$trip->status] ?? Str::headline($trip->status)) : '',
            'Remarks' => $payment->remarks ?: ($trip?->remarks ?: ''),
            'Created By' => $trip?->creator?->name ?: '',
        ];
    }

    private function buildExcelExport(string $path, array $rows): void
    {
        $headers = array_keys($rows[0] ?? [
            'Payment Date' => '',
            'Payment Status' => '',
            'Transporter' => '',
            'Owner Type' => '',
            'Transporter CNIC' => '',
            'Transporter Phone' => '',
            'Transporter Address' => '',
            'Transporter District' => '',
            'EasyPaisa Account' => '',
            'JazzCash Account' => '',
            'Bank Name' => '',
            'Bank Account Title' => '',
            'Bank Account Number' => '',
            'Driver Name' => '',
            'Driver CNIC' => '',
            'Driver Mobile' => '',
            'Vehicle Registration' => '',
            'Vehicle Type' => '',
            'Vehicle Chassis No' => '',
            'Route' => '',
            'Starting Point' => '',
            'Ending Point' => '',
            'Route District' => '',
            'Trip Date' => '',
            'No. of Trips' => '',
            'Fare Amount' => '',
            'Total Amount' => '',
            'Trip Status' => '',
            'Remarks' => '',
            'Created By' => '',
        ]);

        $zip = new ZipArchive();
        $zip->open($path, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        $sheetRows = [];
        $sheetRows[] = $this->buildExcelRow(1, $headers, true);

        foreach (array_values($rows) as $index => $row) {
            $sheetRows[] = $this->buildExcelRow($index + 2, array_values($row), false);
        }

        $sheetXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . '<sheetData>' . implode('', $sheetRows) . '</sheetData>'
            . '</worksheet>';

        $zip->addFromString('[Content_Types].xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            . '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            . '<Default Extension="xml" ContentType="application/xml"/>'
            . '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
            . '<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
            . '<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'
            . '<Override PartName="/docProps/core.xml" ContentType="application/vnd.openxmlformats-package.core-properties+xml"/>'
            . '<Override PartName="/docProps/app.xml" ContentType="application/vnd.openxmlformats-officedocument.extended-properties+xml"/>'
            . '</Types>');
        $zip->addFromString('_rels/.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
            . '<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/package/2006/relationships/metadata/core-properties" Target="docProps/core.xml"/>'
            . '<Relationship Id="rId3" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/extended-properties" Target="docProps/app.xml"/>'
            . '</Relationships>');
        $zip->addFromString('xl/workbook.xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            . '<sheets><sheet name="Payments" sheetId="1" r:id="rId1"/></sheets>'
            . '</workbook>');
        $zip->addFromString('xl/_rels/workbook.xml.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'
            . '<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>'
            . '</Relationships>');
        $zip->addFromString('xl/styles.xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . '<fonts count="2"><font><sz val="11"/><name val="Calibri"/></font><font><b/><sz val="11"/><name val="Calibri"/></font></fonts>'
            . '<fills count="2"><fill><patternFill patternType="none"/></fill><fill><patternFill patternType="gray125"/></fill></fills>'
            . '<borders count="1"><border><left/><right/><top/><bottom/><diagonal/></border></borders>'
            . '<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>'
            . '<cellXfs count="2"><xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/><xf numFmtId="0" fontId="1" fillId="0" borderId="0" xfId="0" applyFont="1"/></cellXfs>'
            . '<cellStyles count="1"><cellStyle name="Normal" xfId="0" builtinId="0"/></cellStyles>'
            . '</styleSheet>');
        $zip->addFromString('xl/worksheets/sheet1.xml', $sheetXml);
        $zip->addFromString('docProps/app.xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Properties xmlns="http://schemas.openxmlformats.org/officeDocument/2006/extended-properties" xmlns:vt="http://schemas.openxmlformats.org/officeDocument/2006/docPropsVTypes">'
            . '<Application>Laravel</Application>'
            . '</Properties>');
        $zip->addFromString('docProps/core.xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<cp:coreProperties xmlns:cp="http://schemas.openxmlformats.org/package/2006/metadata/core-properties" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:dcterms="http://purl.org/dc/terms/" xmlns:dcmitype="http://purl.org/dc/dcmitype/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">'
            . '<dc:title>Payments Export</dc:title>'
            . '<dc:creator>Laravel</dc:creator>'
            . '<cp:lastModifiedBy>Laravel</cp:lastModifiedBy>'
            . '<dcterms:created xsi:type="dcterms:W3CDTF">' . now()->toAtomString() . '</dcterms:created>'
            . '<dcterms:modified xsi:type="dcterms:W3CDTF">' . now()->toAtomString() . '</dcterms:modified>'
            . '</cp:coreProperties>');

        $zip->close();
    }

    private function buildExcelRow(int $rowNumber, array $values, bool $isHeader): string
    {
        $cells = [];

        foreach (array_values($values) as $index => $value) {
            $reference = $this->excelColumnName($index + 1) . $rowNumber;
            $cells[] = $this->buildExcelCell($reference, $value, $isHeader);
        }

        return '<row r="' . $rowNumber . '">' . implode('', $cells) . '</row>';
    }

    private function buildExcelCell(string $reference, mixed $value, bool $isHeader): string
    {
        $style = $isHeader ? ' s="1"' : '';

        if (! $isHeader && is_numeric($value) && $value !== '') {
            return '<c r="' . $reference . '"' . $style . '><v>' . $this->escapeExcelValue((string) $value) . '</v></c>';
        }

        return '<c r="' . $reference . '"' . $style . ' t="inlineStr"><is><t xml:space="preserve">' . $this->escapeExcelValue((string) $value) . '</t></is></c>';
    }

    private function excelColumnName(int $index): string
    {
        $name = '';

        while ($index > 0) {
            $index--;
            $name = chr(65 + ($index % 26)) . $name;
            $index = intdiv($index, 26);
        }

        return $name;
    }

    private function escapeExcelValue(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }
}
