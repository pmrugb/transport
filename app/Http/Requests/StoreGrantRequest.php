<?php

namespace App\Http\Requests;

use App\Models\Grant;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreGrantRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'total_amount' => ['required', 'numeric', 'min:0'],
            'financial_year' => ['required', 'string', 'max:20'],
            'district_id' => ['nullable', 'integer', 'exists:districts,id'],
            'start_date' => ['nullable', 'date', 'before_or_equal:today'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date', 'before_or_equal:today'],
            'status' => ['required', Rule::in(array_keys(Grant::STATUSES))],
            'remarks' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
