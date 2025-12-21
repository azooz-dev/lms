<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\SubCategory;
use Illuminate\Database\Eloquent\Collection;

interface SubCategoryRepositoryInterface extends RepositoryInterface
{
    public function getAllLatest(): Collection;

    public function getByCategoryId(int $categoryId): Collection;

    public function createWithSlug(array $data): SubCategory;

    public function updateWithSlug(SubCategory $subCategory, array $data): SubCategory;
}
