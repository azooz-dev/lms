<?php

declare(strict_types=1);

namespace App\Http\Requests\Blog;

use Illuminate\Foundation\Http\FormRequest;

class StoreBlogCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_name' => 'required|string|max:255|unique:blog_categories,category_name',
        ];
    }

    public function messages(): array
    {
        return [
            'category_name.required' => 'The blog category name is required.',
            'category_name.unique' => 'This blog category name already exists.',
            'category_name.max' => 'The category name must not exceed 255 characters.',
        ];
    }

    public function attributes(): array
    {
        return [
            'category_name' => 'Blog Category Name',
        ];
    }
}
