<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleAccessRequest;
use App\Http\Requests\UpdateRoleDetailsRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function create(): View
    {
        return view('settings.roles.create', [
            ...$this->sharedData(),
            'role' => new Role([
                'access_scope' => 'global',
                'can_view' => true,
            ]),
            'formAction' => route('settings.roles.store'),
            'formMethod' => 'post',
            'submitLabel' => 'Add',
            'pageTitle' => 'Add Roles',
            'pageSubtitle' => 'Create a role with clear scope and permissions for system access.',
        ]);
    }

    public function index(Request $request): View
    {
        $perPage = (int) $request->integer('per_page', 10);
        $perPage = in_array($perPage, [10, 25, 50, 100], true) ? $perPage : 10;

        return view('settings.roles.index', [
            ...$this->sharedData(),
            'perPage' => $perPage,
            'roles' => Role::query()->withCount('users')->latest()->paginate($perPage)->withQueryString(),
        ]);
    }

    public function edit(Role $role): View
    {
        return view('settings.roles.edit', [
            ...$this->sharedData(),
            'role' => $role,
            'formAction' => route('settings.roles.update', $role),
            'formMethod' => 'put',
            'submitLabel' => 'Save Changes',
            'pageTitle' => 'Edit Role',
            'pageSubtitle' => 'Update the selected role scope and permission settings.',
        ]);
    }

    public function store(StoreRoleRequest $request): RedirectResponse
    {
        Role::create($request->validated());

        return redirect()->route('settings.roles.index')
            ->with('success', 'Role created successfully.');
    }

    public function update(StoreRoleRequest $request, Role $role): RedirectResponse
    {
        $role->update($request->validated());

        return redirect()->route('settings.roles.edit', $role)
            ->with('success', 'Role updated successfully.');
    }

    public function updateDetails(UpdateRoleDetailsRequest $request, Role $role): RedirectResponse
    {
        $payload = $request->validated();

        if ($role->is_system) {
            unset($payload['slug']);
        }

        $role->update($payload);

        return redirect()->route('settings.roles.index')
            ->with('success', 'Role details updated successfully.');
    }

    public function updateAccess(UpdateRoleAccessRequest $request, Role $role): RedirectResponse
    {
        $role->update($request->validated());

        return redirect()->route('settings.roles.index')
            ->with('success', 'Role access updated successfully.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        if ($role->is_system) {
            return redirect()->route('settings.roles.index')
                ->with('error', 'System roles cannot be deleted.');
        }

        $role->delete();

        return redirect()->route('settings.roles.index')
            ->with('success', 'Role deleted successfully.');
    }

    private function sharedData(): array
    {
        return [
            'accessScopes' => Role::ACCESS_SCOPES,
            'stats' => [
                'total' => Role::count(),
                'system' => Role::query()->where('is_system', true)->count(),
                'custom' => Role::query()->where('is_system', false)->count(),
            ],
        ];
    }
}
