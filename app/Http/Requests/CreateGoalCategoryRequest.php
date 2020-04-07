<?php

namespace App\Http\Requests;

use App\Models\GoalCategory;
use Illuminate\Foundation\Http\FormRequest;

class CreateGoalCategoryRequest extends FormRequest
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
        $tableName = GoalCategory::getFullTableName();

        return [
            'title' => "required|string|unique:{$tableName}"
        ];
    }
}
