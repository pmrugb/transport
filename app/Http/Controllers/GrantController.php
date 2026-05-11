<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGrantRequest;
use App\Models\District;
use App\Models\Grant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GrantController extends Controller
{
    public function index(Request $request): View
    {
        $this->ensureCanViewGrants();

        $perPage = $this->resolvePerPage($request);
        $grantQuery = Grant::query()
            ->with('district')
            ->latest();

        return view('grants.index', [
            ...$this->sharedData(),
            'perPage' => $perPage,
            'grants' => $grantQuery
                ->paginate($this->paginationSize($perPage, (clone $grantQuery)->toBase()->getCountForPagination()))
                ->withQueryString(),
        ]);
    }

    public function create(): View
    {
        $this->ensureCanCreateGrants();

        return view('grants.create', [
            ...$this->sharedData(),
            'grant' => new Grant(),
            'formAction' => route('grants.store'),
            'formMethod' => 'post',
            'submitLabel' => 'Save Grant',
        ]);
    }

    public function store(StoreGrantRequest $request): RedirectResponse
    {
        $this->ensureCanCreateGrants();

        Grant::create($request->validated());

        return redirect()->route('grants.create')
            ->with('success', 'Grant saved successfully.');
    }

    public function show(Grant $grant): View
    {
        $this->ensureCanViewGrants();

        return view('grants.show', [
            ...$this->sharedData(),
            'grant' => $grant->load(['district', 'releases']),
        ]);
    }

    public function edit(Grant $grant): View
    {
        $this->ensureCanManageGrants();

        return view('grants.edit', [
            ...$this->sharedData(),
            'grant' => $grant->load('district'),
            'formAction' => route('grants.update', $grant),
            'formMethod' => 'put',
            'submitLabel' => 'Save Changes',
        ]);
    }

    public function update(StoreGrantRequest $request, Grant $grant): RedirectResponse
    {
        $this->ensureCanManageGrants();

        $grant->update($request->validated());

        return redirect()->route('grants.edit', $grant)
            ->with('success', 'Grant updated successfully.');
    }

    public function destroy(Grant $grant): RedirectResponse
    {
        $this->ensureCanManageGrants();

        $grant->delete();

        return redirect()->route('grants.index')
            ->with('success', 'Grant deleted successfully.');
    }

    private function sharedData(): array
    {
        $currentYear = (int) now()->format('Y');
        $financialYearOptions = [];

        for ($year = $currentYear - 5; $year <= $currentYear + 5; $year++) {
            $financialYearOptions[] = sprintf('%d-%d', $year, $year + 1);
        }

        return [
            'districts' => District::query()->orderBy('name')->get(),
            'financialYearOptions' => $financialYearOptions,
            'statuses' => Grant::STATUSES,
            'canManageGrants' => auth()->user()?->canManageGrants() ?? false,
            'stats' => [
                'total' => Grant::count(),
                'active' => Grant::query()->where('status', 'active')->count(),
                'districts' => Grant::query()->distinct('district_id')->count('district_id'),
            ],
        ];
    }

    private function ensureCanViewGrants(): void
    {
        abort_unless(auth()->user()?->canViewGrants(), 403);
    }

    private function ensureCanCreateGrants(): void
    {
        abort_unless(auth()->user()?->canCreateGrants(), 403);
    }

    private function ensureCanManageGrants(): void
    {
        abort_unless(auth()->user()?->canManageGrants(), 403);
    }
}
