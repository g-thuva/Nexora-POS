<?php

namespace App\Http\Requests\Product;

use Illuminate\Support\Str;
use Illuminate\Foundation\Http\FormRequest;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class StoreProductRequest extends FormRequest
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
            'product_image'     => 'image|file|max:2048',
            'name'              => 'required|string',
            'slug'              => 'required|unique:products,slug,NULL,id,shop_id,' . $shopId,
            'code'              => ['required','regex:/^PRD\d{5}$/','unique:products,code'],
            'category_id'       => 'nullable|integer',
            'unit_id'           => 'required|integer',
            'quantity'          => 'required|integer',
            'buying_price'      => 'required|integer',
            'selling_price'     => 'required|integer',
            'quantity_alert'    => 'required|integer',
            'notes'             => 'nullable|max:1000'
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => Str::slug($this->name, '-'),
            // Generate codes like PRD00001, PRD00002, ...
            'code' => IdGenerator::generate([
                'table'  => 'products',
                'field'  => 'code',
                'length' => 8, // 3 (PRD) + 5 digits = 8 chars total
                'prefix' => 'PRD',
            ]),
            'quantity' => $this->quantity ?? 1,
            'quantity_alert' => $this->quantity_alert ?? 1,
        ]);
    }
}
