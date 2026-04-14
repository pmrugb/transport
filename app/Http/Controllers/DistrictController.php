<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDistrictRequest;
use App\Models\District;
use App\Models\Division;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DistrictController extends Controller
{
    public function index(Request $request): View
    {
        $perPage = $this->resolvePerPage($request);
        $districtQuery = District::query()
            ->with('division')
            ->latest();

        return view('settings.districts.index', [
            'perPage' => $perPage,
            'divisions' => Division::query()->orderBy('name')->get(),
            'districts' => $districtQuery
                ->paginate($this->paginationSize($perPage, (clone $districtQuery)->toBase()->getCountForPagination()))
                ->withQueryString(),
        ]);
    }

    public function edit(District $district): View
    {
        return view('settings.districts.edit', [
            'district' => $district,
            'divisions' => Division::query()->orderBy('name')->get(),
        ]);
    }

    public function store(StoreDistrictRequest $request): RedirectResponse
    {
        $division = Division::query()->findOrFail($request->integer('division_id'));

        District::create([
            'division_id' => $division->id,
            'name' => $request->validated('name'),
            'division_name' => $division->name,
        ]);

        return redirect()->route('settings.districts.index')
            ->with('success', 'District created successfully.');
    }

    public function update(StoreDistrictRequest $request, District $district): RedirectResponse
    {
        $division = Division::query()->findOrFail($request->integer('division_id'));

        $district->update([
            'division_id' => $division->id,
            'name' => $request->validated('name'),
            'division_name' => $division->name,
        ]);

        return redirect()->route('settings.districts.edit', $district)
            ->with('success', 'District updated successfully.');
    }

    public function destroy(District $district): RedirectResponse
    {
        $district->delete();

        return redirect()->route('settings.districts.index')
            ->with('success', 'District deleted successfully.');
    }
}
