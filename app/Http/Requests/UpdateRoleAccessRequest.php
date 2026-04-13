<?php

namespace App\Http\Requests;

use App\Models\Role;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleAccessRequest extends FormRequest
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
        return [
            'access_scope' => ['required', Rule::in(array_keys(Role::ACCESS_SCOPES))],
            'can_view' => ['nullable', 'boolean'],
            'can_create' => ['nullable', 'boolean'],
            'can_edit' => ['nullable', 'boolean'],
            'can_delete' => ['nullable', 'boolean'],
            'can_manage_users' => ['nullable', 'boolean'],
            'can_manage_system_settings' => ['nullable', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'can_view' => $this->boolean('can_view', true),
            'can_create' => $this->boolean('can_create'),
            'can_edit' => $this->boolean('can_edit'),
            'can_delete' => $this->boolean('can_delete'),
            'can_manage_users' => $this->boolean('can_manage_users'),
            'can_manage_system_settings' => $this->boolean('can_manage_system_settings'),
        ]);
    }
}
