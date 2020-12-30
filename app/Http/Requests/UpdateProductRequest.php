<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'name' => ['sometimes', 'unique:products,name,'.$this->input('name').',name'],
            'type' => ['sometimes', Rule::in(['hot dish', 'cold dish', 'drink', 'dessert'])],
            'description' => ['sometimes', 'max:200'],
            'photo_url' => ['sometimes'],
            'price' => ['sometimes', 'regex:/^\d+(\.\d{1,2})?$/'],
        ];
    }

    public function messages(){
        return [
            'name.unique' => "The name must be unique",
            'name.regex' => "The name must be valid",

            'type.in' => "The product type is invalid",

            'description.max' => "Description is too big (max 200)",

            'price.regex' => "Insert a valid amount",
        ];
    }
}
