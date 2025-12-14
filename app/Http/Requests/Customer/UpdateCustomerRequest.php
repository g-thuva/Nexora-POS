<?php

namespace App\Http\Requests\Customer;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
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
        return [
            'photo' => [
                'image',
                'file',
                'max:1024'
            ],
            'name' => [
                'nullable',
                'string',
                'max:50'
            ],
            'email' => [
                'nullable',
                'email',
                'max:50',
                Rule::unique('customers', 'email')->ignore($this->customer)->where('shop_id', auth()->user()->getActiveShop()?->id)
            ],
            'phone' => [
                'nullable',
                'string',
                'max:25',
            ],
            'address' => [
                'nullable',
                'string',
                'max:100'
            ],
            'account_holder' => [
                'nullable',
                'string',
                'max:100'
            ],
            'account_number' => [
                'nullable',
                'string',
                'max:50'
            ],
            'bank_name' => [
                'nullable',
                'string',
                'max:100'
            ],
        ];
    }
}
