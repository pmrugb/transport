<?php

namespace App\Http\Requests;

use App\Models\District;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDistrictRequest extends FormRequest
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
        $districtId = $this->route('district')?->id;

        return [
            'division_id' => ['required', 'integer', 'exists:divisions,id'],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('districts', 'name')
                    ->ignore($districtId)
                    ->where(fn ($query) => $query->where('division_id', $this->input('division_id'))),
            ],
        ];
    }
}
