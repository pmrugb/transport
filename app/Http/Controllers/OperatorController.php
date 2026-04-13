<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOperatorRequest;
use App\Models\District;
use App\Models\Operator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OperatorController extends Controller
{
    public function index(Request $request): View
    {
        $perPage = (int) $request->integer('per_page', 10);
        $perPage = in_array($perPage, [10, 25, 50, 100], true) ? $perPage : 10;

        return view('operators.index', [
            ...$this->sharedData(),
            'perPage' => $perPage,
            'operators' => Operator::query()
                ->select(['id', 'owner_type', 'name', 'cnic', 'phone', 'address', 'district_id', 'created_at'])
                ->with(['district:id,name'])
                ->latest()
                ->paginate($perPage)
                ->withQueryString(),
        ]);
    }

    public function create(): View
    {
        return view('operators.create', [
            ...$this->sharedData(),
            'operator' => new Operator(),
            'formAction' => route('transporters.store'),
            'formMethod' => 'post',
            'submitLabel' => 'Save Transporter',
        ]);
    }

    public function store(StoreOperatorRequest $request): RedirectResponse
    {
        Operator::create($request->validated());

        return redirect()->route('transporters.create')
            ->with('success', 'Operator saved successfully.');
    }

    public function show(Operator $operator): View
    {
        return view('operators.show', [
            ...$this->sharedData(),
            'operator' => $operator->load('district'),
        ]);
    }

    public function edit(Operator $operator): View
    {
        $this->ensureSuperadmin();

        return view('operators.edit', [
            ...$this->sharedData(),
            'operator' => $operator->load('district'),
            'formAction' => route('transporters.update', $operator),
            'formMethod' => 'put',
            'submitLabel' => 'Save Changes',
        ]);
    }

    public function update(StoreOperatorRequest $request, Operator $operator): RedirectResponse
    {
        $this->ensureSuperadmin();

        $operator->update($request->validated());

        return redirect()->route('transporters.edit', $operator)
            ->with('success', 'Transporter updated successfully.');
    }

    public function destroy(Operator $operator): RedirectResponse
    {
        $this->ensureSuperadmin();

        $operator->delete();

        return redirect()->route('transporters.index')
            ->with('success', 'Transporter deleted successfully.');
    }

    private function sharedData(): array
    {
        $stats = Operator::query()
            ->selectRaw('COUNT(*) as total')
            ->selectRaw("SUM(CASE WHEN owner_type = 'company' THEN 1 ELSE 0 END) as companies")
            ->selectRaw("SUM(CASE WHEN owner_type = 'private' THEN 1 ELSE 0 END) as private")
            ->selectRaw('COUNT(DISTINCT district_id) as districts')
            ->first();

        return [
            'ownerTypes' => Operator::OWNER_TYPES,
            'districts' => District::query()->select(['id', 'name'])->orderBy('name')->get(),
            'canManageTransporters' => auth()->user()?->isSuperadmin() ?? false,
            'stats' => [
                'companies' => (int) ($stats?->companies ?? 0),
                'private' => (int) ($stats?->private ?? 0),
                'districts' => (int) ($stats?->districts ?? 0),
                'total' => (int) ($stats?->total ?? 0),
            ],
        ];
    }

    private function ensureSuperadmin(): void
    {
        abort_unless(auth()->user()?->isSuperadmin(), 403);
    }
}
