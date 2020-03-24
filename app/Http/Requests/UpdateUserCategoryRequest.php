<?php

namespace App\Http\Requests;

use App\Models\Category;
use App\Models\SubCategory;
use App\Models\UserCategory;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserCategoryRequest extends FormRequest
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
        $userCategoryTableName = UserCategory::getFullTableName();
        $categoryTableName = Category::getFullTableName();
        $subCategoryTableName = SubCategory::getFullTableName();
        $userCategoryId = $this->route('userCategoryId');
        $userId = request()->user()['id'];
        $title = request()->input('title');
        return [
            'title' => [
                'required',
                'string',
                "unique:{$categoryTableName}",
                "unique:{$subCategoryTableName},sub_title",
                Rule::unique($userCategoryTableName)->where('user_id', $userId)->ignore($userCategoryId)
            ]
        ];
    }

    public function messages()
    {
        return [
            'title.unique' => 'A category with that name already exists. Please try another.'
        ];
    }
}
