<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserPasswordRequest;
use App\Models\District;
use App\Models\Division;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $this->ensureSuperadmin();

        $perPage = (int) $request->integer('per_page', 10);
        $perPage = in_array($perPage, [10, 25, 50, 100], true) ? $perPage : 10;

        return view('users.index', [
            ...$this->sharedData(),
            'perPage' => $perPage,
            'users' => User::query()
                ->select(['id', 'name', 'email', 'role', 'division_id', 'district_id', 'created_at'])
                ->with([
                    'district:id,name',
                    'division:id,name',
                ])
                ->latest()
                ->paginate($perPage)
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
        User::create($payload);

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

        if (blank($payload['password'] ?? null)) {
            unset($payload['password']);
        }

        $user->update($payload);

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

        $user->delete();

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
            ->selectRaw('COUNT(*) as total')
            ->selectRaw("SUM(CASE WHEN role IN ('super_admin', 'admin') THEN 1 ELSE 0 END) as admins")
            ->selectRaw("SUM(CASE WHEN role IN ('district_admin', 'divisional_admin') THEN 1 ELSE 0 END) as scoped")
            ->first();

        return [
            'roles' => Role::query()->select(['id', 'name', 'slug'])->orderBy('name')->get(),
            'districts' => District::query()->select(['id', 'name'])->orderBy('name')->get(),
            'divisions' => Division::query()->select(['id', 'name'])->orderBy('name')->get(),
            'stats' => [
                'total' => (int) ($stats?->total ?? 0),
                'admins' => (int) ($stats?->admins ?? 0),
                'scoped' => (int) ($stats?->scoped ?? 0),
            ],
        ];
    }

    private function ensureSuperadmin(): void
    {
        abort_unless(auth()->user()?->isSuperadmin(), 403);
    }
}
