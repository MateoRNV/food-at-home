<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', ],
            'email' => ['required', 'email', 'unique:users'],
            'type' => ['required', Rule::in(['EM', 'EC', 'ED', 'C'])],
            'password' => ['required', 'min:3'] 
        ];
    }
}
