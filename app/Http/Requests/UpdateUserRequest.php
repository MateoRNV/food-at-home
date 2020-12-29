<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
            'name' => ['required', 'regex:/^[A-Za-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑ ]+$/'],
            'email' => ['required', 'email'],
            'password' => ['nullable', 'min:3'],
            'type' => ['required', Rule::in(['EM', 'EC', 'ED', 'C'])]
        ];
    }

    public function messages(){
        return [
            'name.required' => "You have to provide your name",
            'name.regex' => "The name is invalid",

            'email.required' => "You have to provide an email",
            'email.email' => "Provide a valid email",

            'password.min' => "The password must have at least 3 characters",

            'type.required' => "You have to provide a type",
            'type.rule' => "Invalid type of type", 
        ];
    }
}
