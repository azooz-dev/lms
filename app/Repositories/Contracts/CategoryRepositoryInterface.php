<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

interface CategoryRepositoryInterface extends RepositoryInterface
{
    public function getAllLatest(): Collection;

    public function hasSubCategories(Category $category): bool;

    public function createWithSlug(array $data): Category;

    public function updateWithSlug(Category $category, array $data): Category;
}
