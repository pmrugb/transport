<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleAccessRequest;
use App\Http\Requests\UpdateRoleDetailsRequest;
use App\Models\AuditLog;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function create(): View
    {
        $this->ensureCanManageRoles();

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
        $this->ensureCanManageRoles();

        $perPage = $this->resolvePerPage($request);
        $roleQuery = Role::query()->withCount('users')->latest();

        return view('settings.roles.index', [
            ...$this->sharedData(),
            'perPage' => $perPage,
            'roles' => $roleQuery->paginate($this->paginationSize($perPage, (clone $roleQuery)->toBase()->getCountForPagination()))->withQueryString(),
        ]);
    }

    public function edit(Role $role): View
    {
        $this->ensureCanManageRoles();

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
        $this->ensureCanManageRoles();

        $role = Role::create($request->validated());

        AuditLog::recordEvent('role.created', $request, $role, [], $role->fresh()->only([
            'name',
            'slug',
            'description',
            'access_scope',
            'sidebar_permissions',
            'page_permissions',
        ]));

        return redirect()->route('settings.roles.index')
            ->with('success', 'Role created successfully.');
    }

    public function update(StoreRoleRequest $request, Role $role): RedirectResponse
    {
        $this->ensureCanManageRoles();

        $oldValues = $role->only([
            'name',
            'slug',
            'description',
            'access_scope',
            'sidebar_permissions',
            'page_permissions',
        ]);

        $role->update($request->validated());

        AuditLog::recordEvent('role.updated', $request, $role, $oldValues, $role->fresh()->only([
            'name',
            'slug',
            'description',
            'access_scope',
            'sidebar_permissions',
            'page_permissions',
        ]));

        return redirect()->route('settings.roles.edit', $role)
            ->with('success', 'Role updated successfully.');
    }

    public function updateDetails(UpdateRoleDetailsRequest $request, Role $role): RedirectResponse
    {
        $this->ensureCanManageRoles();

        $payload = $request->validated();

        if ($role->is_system) {
            unset($payload['slug']);
        }

        $oldValues = $role->only(['name', 'slug', 'description']);
        $role->update($payload);

        AuditLog::recordEvent('role.details_updated', $request, $role, $oldValues, $role->fresh()->only([
            'name',
            'slug',
            'description',
        ]));

        return redirect()->route('settings.roles.index')
            ->with('success', 'Role details updated successfully.');
    }

    public function updateAccess(UpdateRoleAccessRequest $request, Role $role): RedirectResponse
    {
        $this->ensureCanManageRoles();

        $oldValues = $role->only(['access_scope', 'sidebar_permissions', 'page_permissions']);
        $role->update($request->validated());

        AuditLog::recordEvent('role.access_updated', $request, $role, $oldValues, $role->fresh()->only([
            'access_scope',
            'sidebar_permissions',
            'page_permissions',
        ]));

        return redirect()->route('settings.roles.index')
            ->with('success', 'Role access updated successfully.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        $this->ensureCanManageRoles();

        if ($role->is_system) {
            return redirect()->route('settings.roles.index')
                ->with('error', 'System roles cannot be deleted.');
        }

        $oldValues = $role->only([
            'name',
            'slug',
            'description',
            'access_scope',
            'sidebar_permissions',
            'page_permissions',
        ]);

        AuditLog::recordEvent('role.deleted', request(), $role, $oldValues, []);
        $role->delete();

        return redirect()->route('settings.roles.index')
            ->with('success', 'Role deleted successfully.');
    }

    private function sharedData(): array
    {
        return [
            'accessScopes' => Role::ACCESS_SCOPES,
            'sidebarGroups' => Role::SIDEBAR_GROUPS,
            'pagePermissionGroups' => Role::PAGE_PERMISSION_GROUPS,
            'stats' => [
                'total' => Role::count(),
                'system' => Role::query()->where('is_system', true)->count(),
                'custom' => Role::query()->where('is_system', false)->count(),
            ],
        ];
    }

    private function ensureCanManageRoles(): void
    {
        abort_unless(auth()->user()?->canManageRoles(), 403);
    }
}
