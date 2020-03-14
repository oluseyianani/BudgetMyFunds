<?php

namespace App\Http\Requests;

use App\Models\SubCategory;
use Illuminate\Foundation\Http\FormRequest;

class CreateSubCategoryRequest extends FormRequest
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
        $tableName = SubCategory::getFullTableName();

        return [
            'sub_title' => "required|string|unique:{$tableName}",
        ];
    }

    public function messages()
    {
        return [
            'sub_title.required' => 'Subcategory field is required',
            'sub_title.unique' => 'Subcategory already exists'
        ];
    }
}
