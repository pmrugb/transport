<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreChallanRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        if ($this->input('route_id') === 'all_routes') {
            $this->merge([
                'route_id' => null,
            ]);
        }
    }

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
            'route_id' => ['nullable', 'integer', 'exists:transport_routes,id'],
            'challan_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:10240'],
            'remarks' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'challan_image.max' => 'The challan file must not be larger than 10 MB.',
            'challan_image.mimes' => 'The challan file must be a JPG, JPEG, PNG, WEBP image, or a PDF.',
            'challan_image.uploaded' => 'The challan file could not be uploaded because it exceeds the server upload limit. Please increase the PHP upload size limit or choose a smaller file.',
        ];
    }
}
