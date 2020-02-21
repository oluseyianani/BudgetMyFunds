<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CreateCategoryRequest extends FormRequest
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
        $tableName = Category::getFullTableName();
        return [
            'title' => "required|string|unique:{$tableName}",
            'creator' => "required|integer|exists:{$tableName}"
        ];
    }

    public function messages()
    {
        return [
            'title.unique' => 'Category already exists',
            'creator.exists' => 'The selected creator does not exists'
        ];
    }
}
