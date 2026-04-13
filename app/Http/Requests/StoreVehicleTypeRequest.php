<?php

namespace App\Http\Requests;

use App\Models\VehicleType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVehicleTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $vehicleTypeId = $this->route('vehicleType')?->id;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('vehicle_types', 'name')->ignore($vehicleTypeId),
            ],
            'description' => ['nullable', 'string', 'max:2000'],
            'seating_capacity' => ['nullable', 'integer', 'min:1'],
            'status' => ['nullable', Rule::in(array_keys(VehicleType::STATUSES))],
        ];
    }
}
