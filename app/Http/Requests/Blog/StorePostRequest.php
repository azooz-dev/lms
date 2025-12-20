<?php

declare(strict_types=1);

namespace App\Http\Requests\Blog;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => 'required|exists:blog_categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tag' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Please select a blog category.',
            'category_id.exists' => 'The selected blog category does not exist.',
            'title.required' => 'The post title is required.',
            'title.max' => 'The post title must not exceed 255 characters.',
            'description.required' => 'The post description is required.',
            'image.required' => 'Please upload a featured image.',
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'The image must be a JPEG, PNG, JPG, or GIF file.',
            'image.max' => 'The image size must not exceed 2MB.',
        ];
    }

    public function attributes(): array
    {
        return [
            'category_id' => 'Blog Category',
            'title' => 'Post Title',
            'description' => 'Post Description',
            'image' => 'Featured Image',
            'tag' => 'Tags',
        ];
    }
}

