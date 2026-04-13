<?php

namespace App\Http\Requests;

use App\Models\Operator;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class StoreOperatorRequest extends FormRequest
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
        $operatorId = $this->route('operator')?->id;
        $isCompany = $this->input('owner_type') === 'company';
        $cnicRules = [
            'nullable',
            'string',
            'size:15',
            'regex:/^\d{5}-\d{7}-\d{1}$/',
            Rule::unique('transporters', 'cnic')->ignore($operatorId),
        ];

        if ($this->input('owner_type') === 'private') {
            array_unshift($cnicRules, 'required');
        }

        return [
            'owner_type' => ['required', Rule::in(array_keys(Operator::OWNER_TYPES))],
            'name' => ['required', 'string', 'max:255'],
            'cnic' => $cnicRules,
            'phone' => [
                'required',
                'string',
                $isCompany ? 'size:12' : 'size:12',
                $isCompany ? 'regex:/^\d{5}-\d{6}$/' : 'regex:/^\d{4}-\d{7}$/',
            ],
            'address' => ['required', 'string', 'max:255'],
            'easypaisa_no' => ['nullable', 'string', 'size:12', 'regex:/^\d{4}-\d{7}$/'],
            'jazzcash_no' => ['nullable', 'string', 'size:12', 'regex:/^\d{4}-\d{7}$/'],
            'bank_name' => ['nullable', 'string', 'max:255'],
            'bank_account_title' => ['nullable', 'string', 'max:255'],
            'bank_account_no' => ['nullable', 'string', 'max:50'],
            'district_id' => ['required', 'integer', 'exists:districts,id'],
            'remarks' => ['nullable', 'string', 'max:2000'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->input('owner_type') === 'company') {
            $this->merge([
                'cnic' => null,
            ]);
        }

        if ($this->filled('phone')) {
            $digits = preg_replace('/\D/', '', (string) $this->input('phone'));

            $this->merge([
                'phone' => $this->input('owner_type') === 'company'
                    ? implode('-', array_filter([
                        substr((string) $digits, 0, 5),
                        substr((string) $digits, 5, 6),
                    ]))
                    : implode('-', array_filter([
                        substr((string) $digits, 0, 4),
                        substr((string) $digits, 4, 7),
                    ])),
            ]);
        }

        foreach (['easypaisa_no', 'jazzcash_no'] as $field) {
            if (! $this->filled($field)) {
                continue;
            }

            $digits = preg_replace('/\D/', '', (string) $this->input($field));

            $this->merge([
                $field => implode('-', array_filter([
                    substr((string) $digits, 0, 4),
                    substr((string) $digits, 4, 7),
                ])),
            ]);
        }
    }
}
