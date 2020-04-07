<?php

namespace App\Http\Requests;

use App\Models\GoalCategory;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateGoalCategoryRequest extends FormRequest
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
        $id = (int) $this->route('id');

        return [
            'title' => ['required','string', Rule::unique($tableName)->ignore($id)]
        ];
    }
}
