<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBrandBlueprintRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'brand_name' => ['required', 'string', 'max:120'],
            'industry' => ['required', 'string', Rule::in(config('blueprint.industries'))],
            'audience' => ['required', 'string', 'max:2000'],
            'price_position' => ['required', 'string', Rule::in(config('blueprint.price_positions'))],
            'vision' => ['required', 'string', 'max:4000'],
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'industry.in' => 'Choose one of the listed categories.',
            'price_position.in' => 'Choose one of the listed price positions.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'brand_name' => trim((string) $this->input('brand_name')),
            'audience' => trim((string) $this->input('audience')),
            'vision' => trim((string) $this->input('vision')),
        ]);
    }
}
