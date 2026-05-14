<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserPasswordRequest;
use App\Models\AuditLog;
use App\Models\District;
use App\Models\Division;
use App\Models\Role;
use App\Models\TransportRoute;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $this->ensureSuperadmin();

        $perPage = $this->resolvePerPage($request);
        $userQuery = User::query()
            ->select(['id', 'name', 'email', 'role', 'division_id', 'district_id', 'route_id', 'all_routes_access', 'created_at'])
            ->with([
                'district:id,name',
                'division:id,name',
                'route:id,route_name,starting_point,ending_point',
                'routes:id,route_name,starting_point,ending_point',
            ])
            ->latest();

        return view('users.index', [
            ...$this->sharedData(),
            'perPage' => $perPage,
            'users' => $userQuery
                ->paginate($this->paginationSize($perPage, (clone $userQuery)->toBase()->getCountForPagination()))
                ->withQueryString(),
        ]);
    }

    public function create(): View
    {
        $this->ensureSuperadmin();

        return view('users.create', [
            ...$this->sharedData(),
            'user' => new User(),
            'formAction' => route('users.store'),
            'formMethod' => 'post',
            'submitLabel' => 'Save User',
        ]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $this->ensureSuperadmin();

        $payload = $request->validated();
        $routeIds = $payload['route_ids'] ?? [];
        unset($payload['route_ids']);
        $user = User::create($payload);
        $user->routes()->sync($routeIds);

        return redirect()->route('users.create')
            ->with('success', 'User created successfully.');
    }

    public function edit(User $user): View
    {
        $this->ensureSuperadmin();

        return view('users.edit', [
            ...$this->sharedData(),
            'user' => $user,
            'formAction' => route('users.update', $user),
            'formMethod' => 'put',
            'submitLabel' => 'Save Changes',
        ]);
    }

    public function update(StoreUserRequest $request, User $user): RedirectResponse
    {
        $this->ensureSuperadmin();

        $payload = $request->validated();
        $routeIds = $payload['route_ids'] ?? [];
        unset($payload['route_ids']);

        if (blank($payload['password'] ?? null)) {
            unset($payload['password']);
        }

        $user->update($payload);
        $user->routes()->sync($routeIds);

        $redirectTo = $request->input('redirect_to');

        if ($redirectTo === 'index') {
            return redirect()->route('users.index')
                ->with('success', 'User updated successfully.');
        }

        return redirect()->route('users.edit', $user)
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->ensureSuperadmin();

        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        $oldValues = $user->only([
            'name',
            'email',
            'role',
            'district_id',
            'division_id',
            'route_id',
            'all_routes_access',
        ]);

        $user->delete();

        AuditLog::recordEvent('user.deleted', request(), $user, $oldValues, []);

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }

    public function updatePassword(UpdateUserPasswordRequest $request, User $user): RedirectResponse
    {
        $this->ensureSuperadmin();

        $user->update([
            'password' => $request->validated('password'),
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User password updated successfully.');
    }

    private function sharedData(): array
    {
        $stats = User::query()
            ->leftJoin('roles', 'roles.slug', '=', 'users.role')
            ->selectRaw('COUNT(users.id) as total')
            ->selectRaw("SUM(CASE WHEN roles.can_manage_users = 1 OR users.role IN ('super_admin', 'admin') THEN 1 ELSE 0 END) as admins")
            ->selectRaw("SUM(CASE WHEN roles.access_scope IN ('district', 'division', 'department', 'route') THEN 1 ELSE 0 END) as scoped")
            ->first();

        return [
            'roles' => Role::query()->select(['id', 'name', 'slug', 'access_scope'])->orderBy('name')->get(),
            'districts' => District::query()->select(['id', 'name'])->orderBy('name')->get(),
            'divisions' => Division::query()->select(['id', 'name'])->orderBy('name')->get(),
            'routes' => TransportRoute::query()->select(['id', 'route_name', 'starting_point', 'ending_point'])->orderBy('route_name')->get(),
            'stats' => [
                'total' => (int) ($stats?->total ?? 0),
                'admins' => (int) ($stats?->admins ?? 0),
                'scoped' => (int) ($stats?->scoped ?? 0),
            ],
        ];
    }

    private function ensureSuperadmin(): void
    {
        abort_unless(auth()->user()?->canManageUsers(), 403);
    }
}
