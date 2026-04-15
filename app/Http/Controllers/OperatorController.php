<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\BuildsExcelExports;
use App\Http\Requests\StoreOperatorRequest;
use App\Models\District;
use App\Models\Operator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\View\View;

class OperatorController extends Controller
{
    use BuildsExcelExports;

    public function index(Request $request): View
    {
        $perPage = $this->resolvePerPage($request);
        $filters = $this->filterValues($request);
        $operatorQuery = $this->filteredOperatorsQuery($request);

        return view('operators.index', [
            ...$this->sharedData(),
            'perPage' => $perPage,
            'search' => $filters['search'],
            'filters' => $filters,
            'exportColumns' => $this->operatorExportColumns(),
            'selectedExportColumns' => $this->selectedOperatorExportColumns($request),
            'operators' => $operatorQuery
                ->paginate($this->paginationSize($perPage, (clone $operatorQuery)->toBase()->getCountForPagination()))
                ->withQueryString(),
        ]);
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $columns = $this->selectedOperatorExportColumns($request);
        $filename = 'transporters-'.now()->format('Ymd-His').'.csv';

        return response()->streamDownload(function () use ($request, $columns): void {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, array_values($columns));

            foreach ($this->filteredOperatorsQuery($request)->cursor() as $operator) {
                fputcsv($handle, array_values($this->operatorExportRow($operator, $columns)));
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function exportExcel(Request $request): BinaryFileResponse
    {
        $columns = $this->selectedOperatorExportColumns($request);
        $rows = $this->filteredOperatorsQuery($request)
            ->get()
            ->map(fn (Operator $operator): array => $this->operatorExportRow($operator, $columns))
            ->all();
        $filename = 'transporters-'.now()->format('Ymd-His').'.xlsx';
        $tempPath = tempnam(sys_get_temp_dir(), 'transporters-xlsx-');

        $this->buildExcelExport($tempPath, $rows, 'Transporters', 'Transporters Export');

        return response()->download(
            $tempPath,
            $filename,
            ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
        )->deleteFileAfterSend(true);
    }

    public function pdfView(Request $request): View
    {
        $columns = $this->selectedOperatorExportColumns($request);
        $rows = $this->filteredOperatorsQuery($request)
            ->get()
            ->map(fn (Operator $operator): array => $this->operatorExportRow($operator, $columns))
            ->all();

        return view('exports.table-pdf', [
            'title' => 'Transporters Export',
            'subtitle' => 'Filtered transporter records with the selected export columns.',
            'columns' => $columns,
            'rows' => $rows,
            'filters' => [
                'Search' => $this->filterValues($request)['search'],
            ],
        ]);
    }

    public function create(): View
    {
        return view('operators.create', [
            ...$this->sharedData(),
            'operator' => new Operator(),
            'formAction' => route('transporters.store'),
            'formMethod' => 'post',
            'submitLabel' => 'Save Transporter',
        ]);
    }

    public function store(StoreOperatorRequest $request): RedirectResponse|JsonResponse
    {
        $operator = Operator::create($request->validated())->load('district');

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Operator saved successfully.',
                'operator' => [
                    'id' => $operator->id,
                    'name' => $operator->name,
                    'owner_type' => $operator->owner_type,
                    'cnic' => $operator->cnic,
                    'phone' => $operator->phone,
                    'district_id' => $operator->district_id,
                    'district_name' => $operator->district?->name,
                ],
            ]);
        }

        return redirect()->route('transporters.create')
            ->with('success', 'Operator saved successfully.');
    }

    public function show(Operator $operator): View
    {
        return view('operators.show', [
            ...$this->sharedData(),
            'operator' => $operator->load('district'),
        ]);
    }

    public function edit(Operator $operator): View
    {
        $this->ensureSuperadmin();

        return view('operators.edit', [
            ...$this->sharedData(),
            'operator' => $operator->load('district'),
            'formAction' => route('transporters.update', $operator),
            'formMethod' => 'put',
            'submitLabel' => 'Save Changes',
        ]);
    }

    public function update(StoreOperatorRequest $request, Operator $operator): RedirectResponse
    {
        $this->ensureSuperadmin();

        $operator->update($request->validated());

        return redirect()->route('transporters.edit', $operator)
            ->with('success', 'Transporter updated successfully.');
    }

    public function destroy(Operator $operator): RedirectResponse
    {
        $this->ensureSuperadmin();

        $operator->delete();

        return redirect()->route('transporters.index')
            ->with('success', 'Transporter deleted successfully.');
    }

    private function sharedData(): array
    {
        $stats = Operator::query()
            ->selectRaw('COUNT(*) as total')
            ->selectRaw("SUM(CASE WHEN owner_type = 'company' THEN 1 ELSE 0 END) as companies")
            ->selectRaw("SUM(CASE WHEN owner_type = 'private' THEN 1 ELSE 0 END) as private")
            ->selectRaw('COUNT(DISTINCT district_id) as districts')
            ->first();

        return [
            'ownerTypes' => Operator::OWNER_TYPES,
            'districts' => District::query()->select(['id', 'name'])->orderBy('name')->get(),
            'canManageTransporters' => auth()->user()?->isSuperadmin() ?? false,
            'stats' => [
                'companies' => (int) ($stats?->companies ?? 0),
                'private' => (int) ($stats?->private ?? 0),
                'districts' => (int) ($stats?->districts ?? 0),
                'total' => (int) ($stats?->total ?? 0),
            ],
        ];
    }

    private function ensureSuperadmin(): void
    {
        abort_unless(auth()->user()?->isSuperadmin(), 403);
    }

    private function filteredOperatorsQuery(Request $request)
    {
        $filters = $this->filterValues($request);

        return Operator::query()
            ->select(['id', 'owner_type', 'name', 'cnic', 'phone', 'address', 'district_id', 'easypaisa_no', 'jazzcash_no', 'bank_name', 'bank_account_title', 'bank_account_no', 'created_at'])
            ->with(['district:id,name'])
            ->when($filters['search'] !== '', function ($query) use ($filters) {
                $search = $filters['search'];

                $query->where(function ($nestedQuery) use ($search) {
                    $nestedQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('cnic', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%")
                        ->orWhereHas('district', fn ($districtQuery) => $districtQuery->where('name', 'like', "%{$search}%"));
                });
            })
            ->latest();
    }

    private function filterValues(Request $request): array
    {
        return [
            'search' => trim((string) $request->input('search', '')),
        ];
    }

    private function operatorExportColumns(): array
    {
        return [
            'owner_type' => 'Owner Type',
            'name' => 'Name',
            'cnic' => 'CNIC',
            'phone' => 'Phone',
            'address' => 'Address',
            'district' => 'District',
            'easypaisa_no' => 'EasyPaisa Account',
            'jazzcash_no' => 'JazzCash Account',
            'bank_name' => 'Bank Name',
            'bank_account_title' => 'Bank Account Title',
            'bank_account_no' => 'Bank Account Number',
        ];
    }

    private function selectedOperatorExportColumns(Request $request): array
    {
        $available = $this->operatorExportColumns();
        $requested = array_values(array_filter((array) $request->input('columns', array_keys($available)), 'is_string'));
        $selected = array_intersect_key($available, array_flip($requested));

        return $selected !== [] ? $selected : $available;
    }

    private function operatorExportRow(Operator $operator, array $columns): array
    {
        $row = [
            'owner_type' => Operator::OWNER_TYPES[$operator->owner_type] ?? ucfirst((string) $operator->owner_type),
            'name' => $operator->name ?: '',
            'cnic' => $operator->cnic ?: '',
            'phone' => $operator->phone ?: '',
            'address' => $operator->address ?: '',
            'district' => $operator->district?->name ?: '',
            'easypaisa_no' => $operator->easypaisa_no ?: '',
            'jazzcash_no' => $operator->jazzcash_no ?: '',
            'bank_name' => $operator->bank_name ?: '',
            'bank_account_title' => $operator->bank_account_title ?: '',
            'bank_account_no' => $operator->bank_account_no ?: '',
        ];

        $exportRow = [];

        foreach ($columns as $key => $label) {
            $exportRow[$label] = $row[$key] ?? '';
        }

        return $exportRow;
    }
}
