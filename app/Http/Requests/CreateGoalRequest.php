<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Models\GoalCategory;
use Illuminate\Foundation\Http\FormRequest;

class CreateGoalRequest extends FormRequest
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
        $goalCategoryTable = GoalCategory::getFullTableName();

        return [
            'title' => 'required|string',
            'description' => 'required|string',
            'amount' => 'required|numeric|between:10.00,9999999999.99',
            'monthly_contributions' => 'required|numeric|between:1.00,9999999999.99',
            'user_id' => "required|exists:{$userTableName},id",
            'due_date'=> 'required|date',
            'auto_compute' => 'required|boolean',
            'goal_categories_id' => "required|exists:{$goalCategoryTable},id"
        ];
    }

    public function messages()
    {
        return [
            'amount.between' => 'Oops! Goals can only be between 10 and 9 billion. We tried, to be fair :)',
            'monthly_contributions.between' => 'Monthly contributions should not be less than 1 and cannot be more than 9 billion'
        ];
    }
}
