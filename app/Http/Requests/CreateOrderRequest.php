<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
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
            'customer_id' => ['required', 'numeric'],
            'notes' => ['nullable'], 
            'total_price' => ['required', 'numeric'],
            'products' => ['required','array'],
            'products.*.id' => ['required', 'numeric'],
            'products.*.price' => ['required', 'numeric'],
            'products.*.quantity' => ['required', 'integer'],
            'products.*.totalPrice' => ['required', 'numeric'],

        ];
    }


    public function messages(){
        return [
            'customer_id.required' => "You have to provide your customer_id",
            'customer_id.numeric' => "Cutomer Id have to be a number",
            
            'total_price.required' => "You have to provide a total price",
            'total_price.numeric' => "Total price needs to be numeric",

            /*'products.required' => "You have to provide products",
            'products.array' => "Products have to be an array",
            
            'products.*.id.required' => "The product needs an id",
            'products.*.id.numeric' => "The product id needs to be a number",

            'products.*.price.required' => "The product needs a price",
            'products.*.price.numeric' => "The product price needs to be a number",

            'products.*.quantity.required' => "The product needs a quantity",
            'products.*.quantity.integer' => "The product quantity needs to be an integer",

            'products.*.totalPrice.required' => "The product needs a price",
            'products.*.totalPrice.numeric' => "The product price needs to be a number",*/
        ];
    }
}
