<?php

namespace App\Http\Requests;

use App\Models\Role;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $roleId = $this->route('role')?->id;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'name')->ignore($roleId),
            ],
            'slug' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9_]+$/',
                Rule::unique('roles', 'slug')->ignore($roleId),
            ],
            'description' => ['nullable', 'string', 'max:1000'],
            'access_scope' => ['required', Rule::in(array_keys(Role::ACCESS_SCOPES))],
            'can_view' => ['nullable', 'boolean'],
            'can_create' => ['nullable', 'boolean'],
            'can_edit' => ['nullable', 'boolean'],
            'can_delete' => ['nullable', 'boolean'],
            'can_manage_users' => ['nullable', 'boolean'],
            'can_manage_system_settings' => ['nullable', 'boolean'],
            'is_system' => ['nullable', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $name = (string) $this->input('name', '');
        $slug = (string) $this->input('slug', '');

        $this->merge([
            'slug' => Str::of($slug !== '' ? $slug : $name)->lower()->replace('-', '_')->slug('_')->value(),
            'access_scope' => $this->input('access_scope', 'global'),
            'can_view' => $this->boolean('can_view', true),
            'can_create' => $this->boolean('can_create'),
            'can_edit' => $this->boolean('can_edit'),
            'can_delete' => $this->boolean('can_delete'),
            'can_manage_users' => $this->boolean('can_manage_users'),
            'can_manage_system_settings' => $this->boolean('can_manage_system_settings'),
            'is_system' => $this->boolean('is_system'),
        ]);
    }
}
