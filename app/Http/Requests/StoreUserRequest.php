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

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->route('user')?->id;
        $isUpdate = $userId !== null;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'role' => ['required', 'string', Rule::exists('roles', 'slug')],
            'district_id' => [Rule::requiredIf($this->input('role') === 'district_admin'), 'nullable', 'integer', 'exists:districts,id'],
            'division_id' => [Rule::requiredIf($this->input('role') === 'divisional_admin'), 'nullable', 'integer', 'exists:divisions,id'],
            'password' => [$isUpdate ? 'nullable' : 'required', 'string', 'min:8', 'confirmed'],
        ];
    }

    protected function passedValidation(): void
    {
        $role = Role::query()->where('slug', $this->input('role'))->first();

        if (! $role) {
            return;
        }

        if ($role->slug !== 'district_admin') {
            $this->merge(['district_id' => null]);
        }

        if ($role->slug !== 'divisional_admin') {
            $this->merge(['division_id' => null]);
        }
    }
}
