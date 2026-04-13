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
        $perPage = (int) $request->integer('per_page', 10);
        $perPage = in_array($perPage, [10, 25, 50, 100], true) ? $perPage : 10;

        return view('fares.index', [
            ...$this->sharedData(),
            'perPage' => $perPage,
            'fares' => Fare::query()
                ->with('route')
                ->latest()
                ->paginate($perPage)
                ->withQueryString(),
        ]);
    }

    public function create(): View
    {
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
        Fare::create($request->validated());

        return redirect()->route('fares.create')
            ->with('success', 'Fare saved successfully.');
    }

    public function show(Fare $fare): View
    {
        return view('fares.show', [
            ...$this->sharedData(),
            'fare' => $fare->load('route'),
        ]);
    }

    public function edit(Fare $fare): View
    {
        $this->ensureSuperadmin();

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
        $this->ensureSuperadmin();

        $fare->update($request->validated());

        return redirect()->route('fares.edit', $fare)
            ->with('success', 'Fare updated successfully.');
    }

    public function destroy(Fare $fare): RedirectResponse
    {
        $this->ensureSuperadmin();

        $fare->delete();

        return redirect()->route('fares.index')
            ->with('success', 'Fare deleted successfully.');
    }

    private function sharedData(): array
    {
        return [
            'routes' => TransportRoute::query()->orderBy('route_name')->get(),
            'statuses' => Fare::STATUSES,
            'canManageFares' => auth()->user()?->isSuperadmin() ?? false,
            'stats' => [
                'total' => Fare::count(),
                'active' => Fare::query()->where('status', 'active')->count(),
                'routes' => Fare::query()->distinct('route_id')->count('route_id'),
            ],
        ];
    }

    private function ensureSuperadmin(): void
    {
        abort_unless(auth()->user()?->isSuperadmin(), 403);
    }
}
