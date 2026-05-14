<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DeletedUserController extends Controller
{
    public function index(Request $request): View
    {
        $this->ensureCanManageUsers();

        $perPage = $this->resolvePerPage($request);
        $search = trim((string) $request->input('search'));

        $deletedUsersQuery = User::onlyTrashed()
            ->with(['accessRole:id,name,slug,access_scope'])
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($innerQuery) use ($search): void {
                    $innerQuery->where('name', 'like', '%'.$search.'%')
                        ->orWhere('email', 'like', '%'.$search.'%');
                });
            })
            ->latest('deleted_at');

        return view('logs.deleted-users', [
            'perPage' => $perPage,
            'search' => $search,
            'stats' => [
                'total' => User::onlyTrashed()->count(),
                'today' => User::onlyTrashed()->whereDate('deleted_at', today())->count(),
                'route_scoped' => User::onlyTrashed()
                    ->whereHas('accessRole', fn ($query) => $query->where('access_scope', 'route'))
                    ->count(),
                'restorable' => User::onlyTrashed()
                    ->get()
                    ->filter(fn (User $user): bool => $user->canRestoreOriginalEmail())
                    ->count(),
            ],
            'deletedUsers' => $deletedUsersQuery
                ->paginate($this->paginationSize($perPage, (clone $deletedUsersQuery)->toBase()->getCountForPagination()))
                ->withQueryString(),
        ]);
    }

    public function restore(Request $request, int $user): RedirectResponse
    {
        $this->ensureCanManageUsers();

        $deletedUser = User::withTrashed()->findOrFail($user);

        abort_unless($deletedUser->trashed(), 404);

        $originalEmail = $deletedUser->softDeletedOriginalEmail();

        if ($originalEmail === null || ! $deletedUser->canRestoreOriginalEmail()) {
            return redirect()->route('logs.deleted-users.index', $request->query())
                ->with('error', 'This user cannot be restored until the original email becomes available.');
        }

        $oldValues = [
            'email' => $deletedUser->email,
            'deleted_at' => $deletedUser->deleted_at?->toDateTimeString(),
        ];

        $deletedUser->restore();
        $deletedUser->forceFill(['email' => $originalEmail])->save();

        AuditLog::recordEvent('user.restored', $request, $deletedUser->fresh(), $oldValues, [
            'email' => $deletedUser->email,
            'deleted_at' => null,
        ]);

        return redirect()->route('logs.deleted-users.index', $request->query())
            ->with('success', 'User restored successfully.');
    }

    private function ensureCanManageUsers(): void
    {
        abort_unless(auth()->user()?->canManageUsers(), 403);
    }
}
