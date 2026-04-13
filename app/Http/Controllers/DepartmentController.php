<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDepartmentRequest;
use App\Models\Department;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    public function index(Request $request): View
    {
        $perPage = (int) $request->integer('per_page', 10);
        $perPage = in_array($perPage, [10, 25, 50, 100], true) ? $perPage : 10;

        return view('settings.departments.index', [
            'perPage' => $perPage,
            'statuses' => Department::STATUSES,
            'departments' => Department::query()
                ->latest()
                ->paginate($perPage)
                ->withQueryString(),
        ]);
    }

    public function edit(Department $department): View
    {
        return view('settings.departments.edit', [
            'department' => $department,
            'statuses' => Department::STATUSES,
        ]);
    }

    public function store(StoreDepartmentRequest $request): RedirectResponse
    {
        Department::create($request->validated());

        return redirect()->route('settings.departments.index')
            ->with('success', 'Department created successfully.');
    }

    public function update(StoreDepartmentRequest $request, Department $department): RedirectResponse
    {
        $department->update($request->validated());

        return redirect()->route('settings.departments.edit', $department)
            ->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department): RedirectResponse
    {
        $department->delete();

        return redirect()->route('settings.departments.index')
            ->with('success', 'Department deleted successfully.');
    }
}
