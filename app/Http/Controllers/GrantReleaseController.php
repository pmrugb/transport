<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGrantReleaseRequest;
use App\Models\Department;
use App\Models\Grant;
use App\Models\GrantRelease;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GrantReleaseController extends Controller
{
    public function index(Request $request): View
    {
        $perPage = $this->resolvePerPage($request);
        $grantReleaseQuery = GrantRelease::query()
            ->with('grant')
            ->latest();

        return view('grant-releases.index', [
            ...$this->sharedData(),
            'perPage' => $perPage,
            'grantReleases' => $grantReleaseQuery
                ->paginate($this->paginationSize($perPage, (clone $grantReleaseQuery)->toBase()->getCountForPagination()))
                ->withQueryString(),
        ]);
    }

    public function create(): View
    {
        return view('grant-releases.create', [
            ...$this->sharedData(),
            'grantRelease' => new GrantRelease(),
            'formAction' => route('grant-releases.store'),
            'formMethod' => 'post',
            'submitLabel' => 'Save Grant Release',
        ]);
    }

    public function store(StoreGrantReleaseRequest $request): RedirectResponse
    {
        GrantRelease::create($request->validated());

        return redirect()->route('grant-releases.create')
            ->with('success', 'Grant release saved successfully.');
    }

    public function show(GrantRelease $grantRelease): View
    {
        return view('grant-releases.show', [
            ...$this->sharedData(),
            'grantRelease' => $grantRelease->load('grant'),
        ]);
    }

    public function edit(GrantRelease $grantRelease): View
    {
        $this->ensureSuperadmin();

        return view('grant-releases.edit', [
            ...$this->sharedData(),
            'grantRelease' => $grantRelease->load('grant'),
            'formAction' => route('grant-releases.update', $grantRelease),
            'formMethod' => 'put',
            'submitLabel' => 'Save Changes',
        ]);
    }

    public function update(StoreGrantReleaseRequest $request, GrantRelease $grantRelease): RedirectResponse
    {
        $this->ensureSuperadmin();

        $grantRelease->update($request->validated());

        return redirect()->route('grant-releases.edit', $grantRelease)
            ->with('success', 'Grant release updated successfully.');
    }

    public function destroy(GrantRelease $grantRelease): RedirectResponse
    {
        $this->ensureSuperadmin();

        $grantRelease->delete();

        return redirect()->route('grant-releases.index')
            ->with('success', 'Grant release deleted successfully.');
    }

    private function sharedData(): array
    {
        return [
            'grants' => Grant::query()->orderBy('title')->get(),
            'departments' => Department::query()->where('status', 'active')->orderBy('name')->get(),
            'canManageGrantReleases' => auth()->user()?->isSuperadmin() ?? false,
            'stats' => [
                'total' => GrantRelease::count(),
                'grants' => GrantRelease::query()->distinct('grant_id')->count('grant_id'),
                'released' => GrantRelease::query()->sum('release_amount'),
            ],
        ];
    }

    private function ensureSuperadmin(): void
    {
        abort_unless(auth()->user()?->isSuperadmin(), 403);
    }
}
