<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\SubCategory;
use App\Repositories\Contracts\SubCategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class SubCategoryRepository extends BaseRepository implements SubCategoryRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(new SubCategory);
    }

    public function getAllLatest(): Collection
    {
        return SubCategory::latest()->get();
    }

    public function getByCategoryId(int $categoryId): Collection
    {
        return SubCategory::where('category_id', $categoryId)->get();
    }

    public function createWithSlug(array $data): SubCategory
    {
        $data['subCategory_slug'] = $this->generateSlug($data['subCategory_name']);

        return SubCategory::create($data);
    }

    public function updateWithSlug(SubCategory $subCategory, array $data): SubCategory
    {
        if (isset($data['subCategory_name'])) {
            $data['subCategory_slug'] = $this->generateSlug($data['subCategory_name']);
        }

        $subCategory->update($data);

        return $subCategory;
    }

    private function generateSlug(string $name): string
    {
        return strtolower(str_replace(' ', '-', $name));
    }
}
