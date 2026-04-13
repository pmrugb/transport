<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreChallanRequest extends FormRequest
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
            'challan_date' => ['required', 'date', 'before_or_equal:today'],
            'route_id' => ['required', 'integer', 'exists:transport_routes,id'],
            'challan_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:5120'],
            'remarks' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
