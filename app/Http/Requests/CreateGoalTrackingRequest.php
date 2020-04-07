<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateGoalTrackingRequest extends FormRequest
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
            'amount_contributed' => 'required|numeric|between:1.00,9999999999.99'
        ];
    }

    public function messages()
    {
        return [
            'amount_contributed.between' => 'Monthly contributions should not be less than 1 and cannot be more than 9 billion'
        ];
    }
}
