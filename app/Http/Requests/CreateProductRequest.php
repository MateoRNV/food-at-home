<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
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
            'name' => ['required', 'unique:products'],
            'type' => ['required', Rule::in(['hot dish', 'cold dish', 'drink', 'dessert'])],
            'description' => ['required', 'max:150'],
            'photo_url' => ['nullable'],
            'price' => ['required', 'regex:/^\d+(\.\d{1,2})?$/'],
        ];
    }

    public function messages(){
        return [
            'name.required' => "The name is required",
            'name.regex' => "The name must be unique",

            'type.required' => "The product type is required",

            'description.required' => "The description is required",
            'description.max' => "Description is too big (max 150)",

            'price.required' => "The price is required",
            'price.regex' => "Insert a valid amount",
        ];
    }
}
