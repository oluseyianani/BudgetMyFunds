<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserProfileRequest extends FormRequest
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
            'photo_url' => 'string|url',
            'first_name' => 'string',
            'last_name' => 'string',
            'gender'    => ['string', 'regex:/^(M|F)$/'],
            'date_of_birth' => 'date_format:Y-m-d',
            'postal_address' => 'string'
        ];
    }

    public function messages()
    {
        return [
            'gender.regex' => 'gender can only be string of \'M\' or \'F\''
        ];
    }
}
