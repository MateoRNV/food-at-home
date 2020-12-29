<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateUserRequest extends FormRequest
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
            'name' => ['required','alpha' ],
            'email' => ['required', 'email', 'unique:users'],
            'type' => ['required', Rule::in(['EM', 'EC', 'ED', 'C'])],
            'password' => ['required', 'min:3'] 
        ];
    }

    public function messages(){
        return [
            'name.required' => "You have to provide your name"
        ];
    }
}
