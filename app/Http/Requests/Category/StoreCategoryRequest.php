<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
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
            'name' => [
                'required',
                'unique:categories,name,NULL,id,shop_id,' . $shopId
            ],
            'slug' => [
                'required',
                'unique:categories,slug,NULL,id,shop_id,' . $shopId,
                'alpha_dash'
            ]
        ];
    }
}
