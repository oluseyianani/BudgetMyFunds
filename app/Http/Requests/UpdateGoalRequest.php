<?php

namespace App\Http\Requests;

use App\Models\GoalCategory;
use Illuminate\Foundation\Http\FormRequest;

class UpdateGoalRequest extends FormRequest
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
        $goalCategoryTableName = GoalCategory::getFullTableName();
        return [
            'title' => 'required|string',
            'description' => 'required|string',
            'goal_categories_id' => "required|integer|exists:{$goalCategoryTableName},id"
        ];
    }

    public function messages()
    {
        return [
            'goal_categories_id.exists' => 'The selected goal category does not exist'
        ];
    }
}
