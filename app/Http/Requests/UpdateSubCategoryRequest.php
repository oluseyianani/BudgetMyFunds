<?php

namespace App\Http\Requests;

use App\Models\SubCategory;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSubCategoryRequest extends FormRequest
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
        $tableName = $tableName = SubCategory::getFullTableName();
        $subCategoryId = $this->route('subCategoryId');
        return [
            'sub_title' => [
            'required',
            'string',
            Rule::unique($tableName)->ignore($subCategoryId)]
        ];
    }
}
