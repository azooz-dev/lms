<?php

declare(strict_types=1);

namespace App\Http\Requests\SubCategory;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSubCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $subCategoryId = $this->route('update');

        return [
            'category_id' => 'required|exists:categories,id',
            'subCategory_name' => 'required|string|max:255|unique:sub_categories,subCategory_name,'.$subCategoryId,
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Please select a category.',
            'category_id.exists' => 'The selected category does not exist.',
            'subCategory_name.required' => 'The subcategory name is required.',
            'subCategory_name.unique' => 'This subcategory name already exists.',
            'subCategory_name.max' => 'The subcategory name must not exceed 255 characters.',
        ];
    }

    public function attributes(): array
    {
        return [
            'category_id' => 'Category',
            'subCategory_name' => 'Subcategory Name',
        ];
    }
}
