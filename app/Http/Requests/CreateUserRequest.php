<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => 'number|regex:/^(\+234)[0-9]{10}$/',
            'password' => 'required|string|min:8|confirmed|regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/', //Minimum eight characters, at least one letter and one number
        ];
    }

    public function messages()
    {
        return [
            'password.regex' => 'password should be minimum eight characters, at least one letter and one number'
        ];
    }
}