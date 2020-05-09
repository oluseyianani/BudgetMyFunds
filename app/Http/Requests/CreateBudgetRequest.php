<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Foundation\Http\FormRequest;

class CreateBudgetRequest extends FormRequest
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
        $categoryTableName = Category::getFullTableName();
        $subCategoryTableName = SubCategory::getFullTableName();
        $userTableName = User::getFullTableName();

        return [
            'title' => 'required|string',
            'category_id' => "required|integer|exists:{$categoryTableName},id",
            'sub_category_id' => "integer|exists:{$subCategoryTableName},id",
            'user_id' => "required|integer|exists:{$userTableName},id",
            'dedicated_amount' => 'required|numeric|between:0.01,9999999999.99',
            'budget_for_month' => 'required|date'
        ];
    }

    public function messages()
    {
        return [
            'dedicated_amount.between' => 'Dedicated amount cannot be less than 0.01 and cannot be more than 9 billion'
        ];
    }
}
