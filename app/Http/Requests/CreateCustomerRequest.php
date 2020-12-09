<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCustomerRequest extends FormRequest
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
            'name' => ['required', 'min:3', 'alpha'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:3'], 
            'address' => ['required', 'min:3'],
            'phone' => ['required', 'between:9,13'],
            'nif' => ['nullable','size:9'],
        ];
    }

    public function messages(){
        return [
            'name.required' => "You have to provide your name",
            'email.required' => "You have to provide your email",
            'password.required' => "You have to provide your password",
            'address.required' => "You have to provide your address",
            'phone.required' => "You have to provide your phone",
            
        ];
    }
}
