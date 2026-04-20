<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChallanRequest;
use App\Models\Challan;
use App\Models\District;
use App\Models\TransportRoute;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ChallanController extends Controller
{
    public function routeDetails(Request $request): JsonResponse
    {
        $this->ensureSuperadmin();

        $route = TransportRoute::query()
            ->findOrFail((int) $request->integer('route_id'));

        return response()->json([
            'starting_point' => $route->starting_point,
            'ending_point' => $route->ending_point,
            'district_id' => $route->district_id,
        ]);
    }

    public function index(Request $request): View
    {
        $this->ensureCanViewChallans();

        $perPage = $this->resolvePerPage($request);
        $challanQuery = Challan::query()
            ->with(['route', 'district'])
            ->orderByDesc('challan_date')
            ->orderByDesc('created_at');

        return view('challans.index', [
            ...$this->sharedData(),
            'perPage' => $perPage,
            'challans' => $challanQuery
                ->paginate($this->paginationSize($perPage, (clone $challanQuery)->toBase()->getCountForPagination()))
                ->withQueryString(),
        ]);
    }

    public function create(): View
    {
        $this->ensureSuperadmin();

        return view('challans.create', [
            ...$this->sharedData(),
            'challan' => new Challan([
                'challan_date' => today(),
            ]),
            'formAction' => route('challans.store'),
            'formMethod' => 'post',
            'submitLabel' => 'Save Challan',
        ]);
    }

    public function store(StoreChallanRequest $request): RedirectResponse
    {
        $this->ensureSuperadmin();

        $payload = $request->validated();
        $payload = $this->hydrateRouteSnapshot($payload);
        $payload['challan_image'] = $this->storeUploadedImage($request, null);

        Challan::create($payload);

        return redirect()->route('challans.create')
            ->with('success', 'Challan saved successfully.');
    }

    public function show(Challan $challan): View
    {
        $this->ensureCanViewChallans();

        return view('challans.show', [
            ...$this->sharedData(),
            'challan' => $challan->load(['route', 'district']),
        ]);
    }

    public function attachment(Challan $challan): BinaryFileResponse
    {
        $this->ensureCanViewChallans();

        abort_unless(
            filled($challan->challan_image) && Storage::disk('public')->exists($challan->challan_image),
            404
        );

        return response()->file(
            Storage::disk('public')->path($challan->challan_image),
            [
                'Content-Disposition' => 'inline; filename="'.basename((string) $challan->challan_image).'"',
            ]
        );
    }

    public function edit(Challan $challan): View
    {
        $this->ensureSuperadmin();

        return view('challans.edit', [
            ...$this->sharedData(),
            'challan' => $challan->load(['route', 'district']),
            'formAction' => route('challans.update', $challan),
            'formMethod' => 'put',
            'submitLabel' => 'Save Changes',
        ]);
    }

    public function update(StoreChallanRequest $request, Challan $challan): RedirectResponse
    {
        $this->ensureSuperadmin();

        $payload = $request->validated();
        $payload = $this->hydrateRouteSnapshot($payload);
        $payload['challan_image'] = $this->storeUploadedImage($request, $challan) ?? $challan->challan_image;

        $challan->update($payload);

        return redirect()->route('challans.edit', $challan)
            ->with('success', 'Challan updated successfully.');
    }

    public function destroy(Challan $challan): RedirectResponse
    {
        $this->ensureSuperadmin();

        $challan->delete();

        return redirect()->route('challans.index')
            ->with('success', 'Challan deleted successfully.');
    }

    private function sharedData(): array
    {
        $routes = TransportRoute::query()
            ->with('district')
            ->orderBy('route_name')
            ->get();

        return [
            'routes' => $routes,
            'districts' => District::query()->orderBy('name')->get(),
            'canViewChallans' => $this->canViewChallans(),
            'canManageChallans' => auth()->user()?->isSuperadmin() ?? false,
            'stats' => [
                'total' => Challan::count(),
                'today' => Challan::query()->whereDate('challan_date', today())->count(),
                'districts' => Challan::query()->distinct('district_id')->count('district_id'),
            ],
        ];
    }

    private function ensureSuperadmin(): void
    {
        abort_unless(auth()->user()?->isSuperadmin(), 403);
    }

    private function ensureCanViewChallans(): void
    {
        abort_unless($this->canViewChallans(), 403);
    }

    private function canViewChallans(): bool
    {
        $user = auth()->user();

        return ($user?->isSuperadmin() ?? false) || ($user?->isNatcoDepartmentUser() ?? false);
    }

    private function hydrateRouteSnapshot(array $payload): array
    {
        if (blank($payload['route_id'] ?? null)) {
            $payload['route_id'] = null;
            $payload['starting_point'] = 'All Routes';
            $payload['ending_point'] = 'All Routes';
            $payload['district_id'] = null;

            return $payload;
        }

        $route = TransportRoute::query()->findOrFail((int) $payload['route_id']);

        $payload['starting_point'] = $route->starting_point;
        $payload['ending_point'] = $route->ending_point;
        $payload['district_id'] = $route->district_id;

        return $payload;
    }

    private function storeUploadedImage(StoreChallanRequest $request, ?Challan $challan): ?string
    {
        if (! $request->hasFile('challan_image')) {
            return null;
        }

        if ($challan?->challan_image) {
            Storage::disk('public')->delete($challan->challan_image);
        }

        return $request->file('challan_image')->store('challans', 'public');
    }
}
