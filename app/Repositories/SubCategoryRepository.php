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

    public function findByIdAndSlug(int $id, string $slug): ?SubCategory
    {
        return SubCategory::where('id', $id)->where('subCategory_slug', $slug)->first();
    }
}
