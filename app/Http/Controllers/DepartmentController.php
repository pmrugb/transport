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
        $this->ensureCanManageDepartments();

        $perPage = $this->resolvePerPage($request);
        $departmentQuery = Department::query()
            ->latest();

        return view('settings.departments.index', [
            'perPage' => $perPage,
            'statuses' => Department::STATUSES,
            'departments' => $departmentQuery
                ->paginate($this->paginationSize($perPage, (clone $departmentQuery)->toBase()->getCountForPagination()))
                ->withQueryString(),
        ]);
    }

    public function edit(Department $department): View
    {
        $this->ensureCanManageDepartments();

        return view('settings.departments.edit', [
            'department' => $department,
            'statuses' => Department::STATUSES,
        ]);
    }

    public function store(StoreDepartmentRequest $request): RedirectResponse
    {
        $this->ensureCanManageDepartments();

        Department::create($request->validated());

        return redirect()->route('settings.departments.index')
            ->with('success', 'Department created successfully.');
    }

    public function update(StoreDepartmentRequest $request, Department $department): RedirectResponse
    {
        $this->ensureCanManageDepartments();

        $department->update($request->validated());

        return redirect()->route('settings.departments.edit', $department)
            ->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department): RedirectResponse
    {
        $this->ensureCanManageDepartments();

        $department->delete();

        return redirect()->route('settings.departments.index')
            ->with('success', 'Department deleted successfully.');
    }

    private function ensureCanManageDepartments(): void
    {
        abort_unless(auth()->user()?->canManageDepartments(), 403);
    }
}
