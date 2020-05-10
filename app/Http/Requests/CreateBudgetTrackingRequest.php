<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class CreateBudgetTrackingRequest extends FormRequest
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
            'amount_spent' => 'required|numeric|between:0.01,9999999999.99',
            'spender' => "required|integer|exists:{$userTableName},id",
            'reason_for_spend' => 'string'
        ];
    }

    public function messages()
    {
        return [
            'amount_spent.between' => 'Amount spent cannot be less than 0.01 and cannot be more than 9 billion',
            'spender.exists' => 'This user id does not exist'
        ];
    }
}
