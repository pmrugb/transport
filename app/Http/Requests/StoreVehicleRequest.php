<?php

namespace App\Http\Requests;

use App\Models\Vehicle;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVehicleRequest extends FormRequest
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
        $vehicleId = $this->route('vehicle')?->id;

        return [
            'transporter_id' => ['required', 'integer', 'exists:transporters,id'],
            'vehicle_type' => ['required', 'integer', 'exists:vehicle_types,id'],
            'registration_no' => [
                'required',
                'string',
                'max:100',
                Rule::unique('vehicles', 'registration_no')->ignore($vehicleId),
            ],
            'chassis_no' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('vehicles', 'chassis_no')->ignore($vehicleId),
            ],
            'route_id' => ['required', 'integer', 'exists:transport_routes,id'],
            'status' => ['required', Rule::in(array_keys(Vehicle::STATUSES))],
            'remarks' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
