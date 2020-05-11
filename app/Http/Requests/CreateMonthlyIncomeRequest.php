<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class CreateMonthlyIncomeRequest extends FormRequest
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
        $userTableName = User::getFullTableName();

        return [
            'beneficiary' => "required|exists:{$userTableName},id",
            'income_source' => 'string',
            'amount' => 'required|numeric|between:1.00,9999999999.99',
            'date_received' => 'required|date',
            'creator' => "required|exists:{$userTableName},id"
        ];
    }

    public function messages()
    {
        return [
            'amount.between' => 'Monthly income should not be less than 1 and cannot be more than 9 billion'
        ];
    }
}
