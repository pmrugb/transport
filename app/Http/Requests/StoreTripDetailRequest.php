<?php

namespace App\Http\Requests;

use App\Models\Fare;
use App\Models\TripDetail;
use App\Models\Vehicle;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreTripDetailRequest extends FormRequest
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
            'trip_date' => ['required', 'date', 'before_or_equal:today'],
            'route_id' => ['required', 'integer', 'exists:transport_routes,id'],
            'vehicle_id' => ['required', 'integer', 'exists:vehicles,id'],
            'transporter_id' => ['required', 'integer', 'exists:transporters,id'],
            'driver_name' => ['required', 'string', 'max:255'],
            'driver_cnic' => ['required', 'string', 'max:15', 'regex:/^\d{5}-\d{7}-\d{1}$/'],
            'driver_mobile' => ['required', 'string', 'max:12', 'regex:/^\d{4}-\d{7}$/'],
            'fare_id' => ['required', 'integer', 'exists:fares,id'],
            'fare_amount' => ['required', 'numeric', 'min:0'],
            'no_of_trips' => ['required', 'integer', 'min:1'],
            'total_amount' => ['required', 'numeric', 'min:0'],
            'district_id' => ['required', 'integer', 'exists:districts,id'],
            'status' => ['required', Rule::in(array_keys(TripDetail::STATUSES))],
            'remarks' => ['nullable', 'string', 'max:2000'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'driver_cnic' => preg_replace('/\D/', '', (string) $this->input('driver_cnic')) ? substr((string) preg_replace('/\D/', '', (string) $this->input('driver_cnic')), 0, 13) : null,
            'driver_mobile' => preg_replace('/\D/', '', (string) $this->input('driver_mobile')) ? substr((string) preg_replace('/\D/', '', (string) $this->input('driver_mobile')), 0, 11) : null,
        ]);

        if ($this->filled('driver_cnic')) {
            $digits = preg_replace('/\D/', '', (string) $this->input('driver_cnic'));
            $this->merge([
                'driver_cnic' => implode('-', array_filter([
                    substr((string) $digits, 0, 5),
                    substr((string) $digits, 5, 7),
                    substr((string) $digits, 12, 1),
                ])),
            ]);
        }

        if ($this->filled('driver_mobile')) {
            $digits = preg_replace('/\D/', '', (string) $this->input('driver_mobile'));
            $this->merge([
                'driver_mobile' => implode('-', array_filter([
                    substr((string) $digits, 0, 4),
                    substr((string) $digits, 4, 7),
                ])),
            ]);
        }
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $vehicle = Vehicle::query()->with('route')->find($this->integer('vehicle_id'));
            $fare = Fare::query()->find($this->integer('fare_id'));
            if ($vehicle) {
                if ((int) $vehicle->route_id !== $this->integer('route_id')) {
                    $validator->errors()->add('route_id', 'Selected route does not match the selected vehicle.');
                }

                if ((int) $vehicle->transporter_id !== $this->integer('transporter_id')) {
                    $validator->errors()->add('transporter_id', 'Selected transporter does not match the selected vehicle.');
                }

                if ((int) ($vehicle->route?->district_id ?? 0) !== $this->integer('district_id')) {
                    $validator->errors()->add('district_id', 'Selected district does not match the selected route.');
                }
            }

            if ($fare && (int) $fare->route_id !== $this->integer('route_id')) {
                $validator->errors()->add('fare_id', 'Selected fare does not belong to the selected route.');
            }
        });
    }
}
