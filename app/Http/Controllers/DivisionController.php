<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDivisionRequest;
use App\Models\Division;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DivisionController extends Controller
{
    public function index(Request $request): View
    {
        $perPage = $this->resolvePerPage($request);
        $divisionQuery = Division::query()
            ->withCount('districts')
            ->latest();

        return view('settings.divisions.index', [
            'perPage' => $perPage,
            'divisions' => $divisionQuery
                ->paginate($this->paginationSize($perPage, (clone $divisionQuery)->toBase()->getCountForPagination()))
                ->withQueryString(),
        ]);
    }

    public function edit(Division $division): View
    {
        return view('settings.divisions.edit', [
            'division' => $division,
        ]);
    }

    public function store(StoreDivisionRequest $request): RedirectResponse
    {
        Division::create($request->validated());

        return redirect()->route('settings.divisions.index')
            ->with('success', 'Division created successfully.');
    }

    public function update(StoreDivisionRequest $request, Division $division): RedirectResponse
    {
        $division->update($request->validated());

        return redirect()->route('settings.divisions.edit', $division)
            ->with('success', 'Division updated successfully.');
    }

    public function destroy(Division $division): RedirectResponse
    {
        $division->delete();

        return redirect()->route('settings.divisions.index')
            ->with('success', 'Division deleted successfully.');
    }
}
