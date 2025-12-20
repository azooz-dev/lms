<?php

declare(strict_types=1);

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'required|exists:sub_categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'level' => 'nullable|string',
            'duration' => 'nullable|string',
            'resources' => 'nullable|string',
            'selling_price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:selling_price',
            'certificate' => 'nullable|string',
            'prerequisites' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The course title is required.',
            'name.required' => 'The course name is required.',
            'category_id.required' => 'Please select a category.',
            'category_id.exists' => 'The selected category does not exist.',
            'sub_category_id.required' => 'Please select a subcategory.',
            'sub_category_id.exists' => 'The selected subcategory does not exist.',
            'image.image' => 'The file must be an image.',
            'image.max' => 'The image size must not exceed 2MB.',
            'selling_price.required' => 'The selling price is required.',
            'selling_price.numeric' => 'The selling price must be a number.',
            'discount_price.lt' => 'The discount price must be less than the selling price.',
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'Course Title',
            'name' => 'Course Name',
            'category_id' => 'Category',
            'sub_category_id' => 'Subcategory',
            'image' => 'Thumbnail Image',
            'selling_price' => 'Selling Price',
            'discount_price' => 'Discount Price',
        ];
    }
}

