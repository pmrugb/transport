<?php

namespace App\Http\Requests;

use App\Models\Role;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $rawRouteIds = (array) $this->input('route_ids', []);
        $hasAllRoutes = in_array('all_routes', $rawRouteIds, true);
        $normalizedRouteIds = array_values(array_filter($rawRouteIds, fn ($routeId) => $routeId !== 'all_routes'));

        $this->merge([
            'all_routes_access' => $hasAllRoutes ? true : $this->boolean('all_routes_access'),
            'route_ids' => $normalizedRouteIds,
        ]);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->route('user')?->id;
        $isUpdate = $userId !== null;
        $role = Role::query()->where('slug', $this->input('role'))->first();
        $accessScope = $role?->access_scope;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'role' => ['required', 'string', Rule::exists('roles', 'slug')],
            'all_routes_access' => ['nullable', 'boolean'],
            'district_id' => [Rule::requiredIf($accessScope === 'district'), 'nullable', 'integer', 'exists:districts,id'],
            'division_id' => [Rule::requiredIf($accessScope === 'division'), 'nullable', 'integer', 'exists:divisions,id'],
            'route_ids' => [
                Rule::excludeIf($this->boolean('all_routes_access')),
                Rule::requiredIf($accessScope === 'route' && ! $this->boolean('all_routes_access')),
                'nullable',
                'array',
                'min:1',
            ],
            'route_ids.*' => [
                Rule::excludeIf($this->boolean('all_routes_access')),
                'integer',
                'distinct',
                'exists:transport_routes,id',
            ],
            'password' => [$isUpdate ? 'nullable' : 'required', 'string', 'min:8', 'confirmed'],
        ];
    }

    protected function passedValidation(): void
    {
        $role = Role::query()->where('slug', $this->input('role'))->first();

        if (! $role) {
            return;
        }

        if ($role->access_scope !== 'district') {
            $this->merge(['district_id' => null]);
        }

        if ($role->access_scope !== 'division') {
            $this->merge(['division_id' => null]);
        }

        if ($role->access_scope !== 'route') {
            $this->merge([
                'route_id' => null,
                'route_ids' => [],
            ]);
        }

        $this->merge([
            'all_routes_access' => $this->boolean('all_routes_access'),
        ]);

        if ($this->boolean('all_routes_access')) {
            $this->merge([
                'route_id' => null,
                'route_ids' => [],
            ]);
        }

        $routeIds = array_values(array_unique(array_map('intval', (array) $this->input('route_ids', []))));

        $this->merge([
            'route_ids' => $routeIds,
            'route_id' => $routeIds[0] ?? null,
        ]);

        if ($role->access_scope !== 'route') {
            $this->merge([
                'route_id' => null,
                'route_ids' => [],
            ]);
        }
    }
}
