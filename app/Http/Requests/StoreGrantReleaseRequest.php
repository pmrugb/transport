<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreGrantReleaseRequest extends FormRequest
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
            'grant_id' => ['required', 'integer', 'exists:grants,id'],
            'release_amount' => ['required', 'numeric', 'min:0'],
            'release_date' => ['required', 'date', 'before_or_equal:today'],
            'installment_no' => ['required', 'integer', 'min:1'],
            'released_by' => ['required', 'string', 'exists:departments,name'],
            'remarks' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
