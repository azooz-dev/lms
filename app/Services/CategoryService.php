<?php

declare(strict_types=1);

namespace App\Services;

use App\Helpers\ImageResizer;
use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CategoryService
{
    public function __construct(
        private readonly CategoryRepositoryInterface $categoryRepository
    ) {}

    public function getAllCategories(): Collection
    {
        return $this->categoryRepository->getAllLatest();
    }

    public function findById(int $id): ?Category
    {
        return $this->categoryRepository->find($id);
    }

    public function createCategory(array $data, UploadedFile $image): Category
    {
        $filename = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        $resizedPath = public_path("storage/upload/category_images/{$filename}");
        ImageResizer::resize($image, 370, 246, $resizedPath);

        $data['image'] = $filename;

        return $this->categoryRepository->createWithSlug($data);
    }

    public function updateCategory(Category $category, array $data, ?UploadedFile $image = null): Category
    {
        if ($image) {
            $data['image'] = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            $resizedPath = public_path('storage/upload/category_images/'.$data['image']);
            ImageResizer::resize($image, 370, 246, $resizedPath);

            $this->deleteOldImage($category);
        }

        return $this->categoryRepository->updateWithSlug($category, $data);
    }

    public function deleteCategory(Category $category): bool
    {
        if ($this->categoryRepository->hasSubCategories($category)) {
            return false;
        }

        $this->deleteOldImage($category);

        return $this->categoryRepository->delete($category);
    }

    public function hasSubCategories(Category $category): bool
    {
        return $this->categoryRepository->hasSubCategories($category);
    }

    private function deleteOldImage(Category $category): void
    {
        if (! empty($category->image)) {
            $imagePath = 'public/upload/category_images/'.$category->image;
            if (Storage::exists($imagePath)) {
                Storage::delete($imagePath);
            }
        }
    }
}
