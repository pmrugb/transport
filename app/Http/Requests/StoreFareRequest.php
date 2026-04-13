<?php

namespace App\Http\Requests;

use App\Models\Fare;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFareRequest extends FormRequest
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
            'route_id' => ['required', 'integer', 'exists:transport_routes,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'effective_from' => ['nullable', 'date', 'before_or_equal:today'],
            'effective_to' => ['nullable', 'date', 'after_or_equal:effective_from', 'before_or_equal:today'],
            'status' => ['required', Rule::in(array_keys(Fare::STATUSES))],
            'remarks' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
