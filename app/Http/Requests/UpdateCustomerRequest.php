<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
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
            'name' => ['required', 'min:3', 'regex:/^(?![\s.]+$)[a-zA-Z\s.]*$/'],
            'email' => ['required', 'email'],
            'password' => ['nullable', 'min:3'],
            'address' => ['required', 'min:3'],
            'phone' => ['required', 'between:9,13'],
            'nif' => ['nullable','size:9'],
            'photo_url' => ['nullable']
        ];
    }

    public function messages(){
        return [     
            'name.required' => "You have to provide a name",
            'name.min' => "The name must be at least 3 characters",
            'name.regex' => "The name can only contain letters and spaces",

            'email.required' => "You have to provide your email",
            'email.email' => "Provide a valid email",        
            
            'password.min' => "Password needs to be at least 3 characters",

            'address.required' => "You have to provide your address",
            
            'phone.required' => "You have to provide your phone",
            'phone.between' => "Insert a valid phone number",
            
            'nif.size' => "A NIF consists of 9 numbers"
        ];
    }
}
