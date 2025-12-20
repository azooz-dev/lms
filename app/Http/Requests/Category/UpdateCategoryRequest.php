<?php

declare(strict_types=1);

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $categoryId = $this->route('update');

        return [
            'category_name' => 'required|string|max:255|unique:categories,category_name,'.$categoryId,
            'image' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'category_name.required' => 'The category name is required.',
            'category_name.unique' => 'This category name already exists.',
            'category_name.max' => 'The category name must not exceed 255 characters.',
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'The image must be a JPEG, PNG, JPG, GIF, or SVG file.',
            'image.max' => 'The image size must not exceed 2MB.',
        ];
    }

    public function attributes(): array
    {
        return [
            'category_name' => 'Category Name',
            'image' => 'Category Image',
        ];
    }
}

