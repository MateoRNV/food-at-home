<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'order_id' => ['required'],
            'product_id' => ['required'],
            'quantity' => ['required'],
            'unit_price' => ['required'] ,
            'sub_total_price' => ['required'] ,
        ];
    }

    public function messages(){
        return [
            'order_id.required' => "You have to provide an order",
            'product_id.required' => "You have to provide a product",
            'quantity.required' => "You have to provide a quantity",
            'unit_price.required' => "You have to provide a unit_price",
            'sub_total_price.required' => "You have to provide a sub_total_price",
        ];
    }
}
