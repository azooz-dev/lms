<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

interface CategoryRepositoryInterface extends RepositoryInterface
{
    public function getAllLatest(): Collection;

    public function getAllOrdered(string $column, string $direction = 'asc'): Collection;

    public function findByIdAndSlug(int $id, string $slug): ?Category;

    public function hasSubCategories(Category $category): bool;
}
