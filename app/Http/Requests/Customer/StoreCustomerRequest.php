<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreCustomerRequest extends FormRequest
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
        $user = auth()->user();
        $shopId = $user && $user->getActiveShop() ? $user->getActiveShop()->id : null;

        return [
            'photo' => 'image|file|max:1024',
            'name' => 'required|string|max:50',
            'email' => 'nullable|email|max:50|unique:customers,email,NULL,id,shop_id,' . $shopId,
            'phone' => 'nullable|string|max:25',
            'address' => 'nullable|string|max:100',
        ];
    }

    /**
     * Handle a failed validation attempt for AJAX requests
     */
    protected function failedValidation(Validator $validator)
    {
        if ($this->ajax() || $this->wantsJson()) {
            throw new HttpResponseException(
                response()->json([
                    'success' => false,
                    'message' => 'Validation errors',
                    'errors' => $validator->errors()
                ], 422)
            );
        }

        parent::failedValidation($validator);
    }
}
