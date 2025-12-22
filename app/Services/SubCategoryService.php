<?php

declare(strict_types=1);

namespace App\Services;

use App\Helpers\SlugGenerator;
use App\Models\SubCategory;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\SubCategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class SubCategoryService
{
    public function __construct(
        private readonly SubCategoryRepositoryInterface $subCategoryRepository,
        private readonly CategoryRepositoryInterface $categoryRepository
    ) {}

    public function getAllSubCategories(): Collection
    {
        return $this->subCategoryRepository->getAllLatest();
    }

    public function getAllCategories(): Collection
    {
        return $this->categoryRepository->all();
    }

    public function getByCategoryId(int $categoryId): Collection
    {
        return $this->subCategoryRepository->getByCategoryId($categoryId);
    }

    public function findById(int $id): ?SubCategory
    {
        return $this->subCategoryRepository->find($id);
    }

    public function createSubCategory(array $data): SubCategory
    {
        $data['subCategory_slug'] = SlugGenerator::generate($data['subCategory_name']);

        return $this->subCategoryRepository->create($data);
    }

    public function updateSubCategory(SubCategory $subCategory, array $data): SubCategory
    {
        if (isset($data['subCategory_name'])) {
            $data['subCategory_slug'] = SlugGenerator::generate($data['subCategory_name']);
        }

        return $this->subCategoryRepository->update($subCategory, $data);
    }

    public function deleteSubCategory(SubCategory $subCategory): bool
    {
        return $this->subCategoryRepository->delete($subCategory);
    }
}
