<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTransportRouteRequest extends FormRequest
{
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

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $startTime = (string) $this->input('start_time', '');
        $endTime = (string) $this->input('end_time', '');

        $this->merge([
            'timing' => $startTime !== '' && $endTime !== ''
                ? sprintf('%s to %s', $startTime, $endTime)
                : null,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'route_name' => ['required', 'string', 'max:255'],
            'starting_point' => ['required', 'string', 'max:255'],
            'ending_point' => ['required', 'string', 'max:255'],
            'start_time' => ['required', 'string', Rule::in(self::HOUR_OPTIONS)],
            'end_time' => ['required', 'string', Rule::in(self::HOUR_OPTIONS)],
            'timing' => ['required', 'string', 'max:255'],
            'total_distance' => ['required', 'integer', 'min:1'],
            'district_id' => ['required', 'integer', 'exists:districts,id'],
            'remarks' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function validated($key = null, $default = null): array
    {
        $validated = parent::validated();

        unset($validated['start_time'], $validated['end_time']);

        return $validated;
    }
}
