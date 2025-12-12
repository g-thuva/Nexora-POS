<?php

namespace App\Http\Requests\Unit;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUnitRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        // Units are universal - globally unique validation
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('units', 'name')->ignore($this->unit)
            ],
            'slug' => [
                'required',
                'alpha_dash',
                'max:255',
                Rule::unique('units', 'slug')->ignore($this->unit)
            ],
            'short_code' => [
                'required',
                'string',
                'max:10',
                Rule::unique('units', 'short_code')->ignore($this->unit)
                    ->where('shop_id', $shopId)
            ]
        ];
    }
}
