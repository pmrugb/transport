<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransportRouteRequest;
use App\Models\District;
use App\Models\TransportRoute;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransportRouteController extends Controller
{
    /**
     * @var array<string, string>
     */
    private const DISTRICT_ROUTE_PREFIXES = [
        'Gilgit' => 'GLT',
        'Skardu' => 'SKD',
        'Hunza' => 'HNZ',
        'Nagar' => 'NGR',
        'Ghizer' => 'GZR',
        'Diamer' => 'DMR',
        'Ghanche' => 'GHC',
        'Kharmang' => 'KMG',
        'Astore' => 'AST',
    ];

    private const HOUR_OPTIONS = [
        '12:00 AM',
        '1:00 AM',
        '2:00 AM',
        '3:00 AM',
        '4:00 AM',
        '5:00 AM',
        '6:00 AM',
        '7:00 AM',
        '8:00 AM',
        '9:00 AM',
        '10:00 AM',
        '11:00 AM',
        '12:00 PM',
        '1:00 PM',
        '2:00 PM',
        '3:00 PM',
        '4:00 PM',
        '5:00 PM',
        '6:00 PM',
        '7:00 PM',
        '8:00 PM',
        '9:00 PM',
        '10:00 PM',
        '11:00 PM',
    ];

    public function index(Request $request): View
    {
        $perPage = $this->resolvePerPage($request);
        $routeQuery = TransportRoute::query()
            ->select([
                'id',
                'route_code',
                'route_name',
                'starting_point',
                'ending_point',
                'timing',
                'total_distance',
                'district_id',
                'created_at',
            ])
            ->with(['district:id,name'])
            ->latest();

        return view('routes.index', [
            ...$this->sharedData(),
            'perPage' => $perPage,
            'routes' => $routeQuery
                ->paginate($this->paginationSize($perPage, (clone $routeQuery)->toBase()->getCountForPagination()))
                ->withQueryString(),
        ]);
    }

    public function create(): View
    {
        return view('routes.create', [
            ...$this->sharedData(),
            'transportRoute' => new TransportRoute(),
            'formAction' => route('routes.store'),
            'formMethod' => 'post',
            'submitLabel' => 'Save Route',
            'timing' => ['start' => old('start_time'), 'end' => old('end_time')],
        ]);
    }

    public function store(StoreTransportRouteRequest $request): RedirectResponse
    {
        $payload = $request->validated();
        $payload['route_code'] = $this->generateRouteCode((int) $payload['district_id']);

        TransportRoute::create($payload);

        return redirect()->route('routes.create')
            ->with('success', 'Route saved successfully.');
    }

    public function show(TransportRoute $transportRoute): View
    {
        return view('routes.show', [
            ...$this->sharedData(),
            'transportRoute' => $transportRoute->load('district'),
        ]);
    }

    public function edit(TransportRoute $transportRoute): View
    {
        $this->ensureSuperadmin();

        return view('routes.edit', [
            ...$this->sharedData(),
            'transportRoute' => $transportRoute->load('district'),
            'formAction' => route('routes.update', $transportRoute),
            'formMethod' => 'put',
            'submitLabel' => 'Save Changes',
            'timing' => $this->extractTiming($transportRoute),
        ]);
    }

    public function update(StoreTransportRouteRequest $request, TransportRoute $transportRoute): RedirectResponse
    {
        $this->ensureSuperadmin();

        $payload = $request->validated();

        if ((int) $transportRoute->district_id !== (int) $payload['district_id']) {
            $payload['route_code'] = $this->generateRouteCode((int) $payload['district_id']);
        }

        $transportRoute->update($payload);

        return redirect()->route('routes.edit', $transportRoute)
            ->with('success', 'Route updated successfully.');
    }

    public function destroy(TransportRoute $transportRoute): RedirectResponse
    {
        $this->ensureSuperadmin();

        $transportRoute->delete();

        return redirect()->route('routes.index')
            ->with('success', 'Route deleted successfully.');
    }

    private function sharedData(): array
    {
        $stats = TransportRoute::query()
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('COUNT(DISTINCT district_id) as districts')
            ->first();

        return [
            'districts' => District::query()->select(['id', 'name'])->orderBy('name')->get(),
            'hourOptions' => self::HOUR_OPTIONS,
            'canManageRoutes' => auth()->user()?->isSuperadmin() ?? false,
            'stats' => [
                'total' => (int) ($stats?->total ?? 0),
                'districts' => (int) ($stats?->districts ?? 0),
            ],
        ];
    }

    private function ensureSuperadmin(): void
    {
        abort_unless(auth()->user()?->isSuperadmin(), 403);
    }

    /**
     * @return array{start:?string,end:?string}
     */
    private function extractTiming(TransportRoute $transportRoute): array
    {
        $timing = explode(' to ', (string) $transportRoute->timing, 2);

        return [
            'start' => old('start_time', $timing[0] ?? null),
            'end' => old('end_time', $timing[1] ?? null),
        ];
    }

    private function generateRouteCode(int $districtId): string
    {
        $district = District::query()->findOrFail($districtId);
        $prefix = self::DISTRICT_ROUTE_PREFIXES[$district->name] ?? strtoupper(substr($district->name, 0, 3));

        $lastCode = TransportRoute::query()
            ->where('district_id', $districtId)
            ->where('route_code', 'like', $prefix.'-RT-%')
            ->orderByDesc('route_code')
            ->value('route_code');

        $lastSequence = 0;

        if (is_string($lastCode) && preg_match('/(\d{2,})$/', $lastCode, $matches) === 1) {
            $lastSequence = (int) $matches[1];
        }

        return sprintf('%s-RT-%02d', $prefix, $lastSequence + 1);
    }
}
