<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFareRequest;
use App\Models\Fare;
use App\Models\TransportRoute;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FareController extends Controller
{
    public function index(Request $request): View
    {
        $this->ensureCanViewFares();

        $perPage = $this->resolvePerPage($request);
        $fareQuery = Fare::query()
            ->with('route')
            ->latest();

        return view('fares.index', [
            ...$this->sharedData(),
            'perPage' => $perPage,
            'fares' => $fareQuery
                ->paginate($this->paginationSize($perPage, (clone $fareQuery)->toBase()->getCountForPagination()))
                ->withQueryString(),
        ]);
    }

    public function create(): View
    {
        $this->ensureCanCreateFares();

        return view('fares.create', [
            ...$this->sharedData(),
            'fare' => new Fare(),
            'formAction' => route('fares.store'),
            'formMethod' => 'post',
            'submitLabel' => 'Save Fare',
        ]);
    }

    public function store(StoreFareRequest $request): RedirectResponse
    {
        $this->ensureCanCreateFares();

        Fare::create($request->validated());

        return redirect()->route('fares.create')
            ->with('success', 'Fare saved successfully.');
    }

    public function show(Fare $fare): View
    {
        $this->ensureCanViewFares();

        return view('fares.show', [
            ...$this->sharedData(),
            'fare' => $fare->load('route'),
        ]);
    }

    public function edit(Fare $fare): View
    {
        $this->ensureCanManageFares();

        return view('fares.edit', [
            ...$this->sharedData(),
            'fare' => $fare->load('route'),
            'formAction' => route('fares.update', $fare),
            'formMethod' => 'put',
            'submitLabel' => 'Save Changes',
        ]);
    }

    public function update(StoreFareRequest $request, Fare $fare): RedirectResponse
    {
        $this->ensureCanManageFares();

        $fare->update($request->validated());

        return redirect()->route('fares.edit', $fare)
            ->with('success', 'Fare updated successfully.');
    }

    public function destroy(Fare $fare): RedirectResponse
    {
        $this->ensureCanManageFares();

        $fare->delete();

        return redirect()->route('fares.index')
            ->with('success', 'Fare deleted successfully.');
    }

    private function sharedData(): array
    {
        return [
            'routes' => TransportRoute::query()->orderBy('route_name')->get(),
            'statuses' => Fare::STATUSES,
            'canManageFares' => auth()->user()?->canManageFares() ?? false,
            'stats' => [
                'total' => Fare::count(),
                'active' => Fare::query()->where('status', 'active')->count(),
                'routes' => Fare::query()->distinct('route_id')->count('route_id'),
            ],
        ];
    }

    private function ensureCanViewFares(): void
    {
        abort_unless(auth()->user()?->canViewFares(), 403);
    }

    private function ensureCanCreateFares(): void
    {
        abort_unless(auth()->user()?->canCreateFares(), 403);
    }

    private function ensureCanManageFares(): void
    {
        abort_unless(auth()->user()?->canManageFares(), 403);
    }
}
