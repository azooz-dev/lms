<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(new Category);
    }

    public function getAllLatest(): Collection
    {
        return Category::latest()->get();
    }

    public function getAllOrdered(string $column, string $direction = 'asc'): Collection
    {
        return Category::orderBy($column, $direction)->get();
    }

    public function findByIdAndSlug(int $id, string $slug): ?Category
    {
        return Category::where('id', $id)->where('category_slug', $slug)->first();
    }

    public function hasSubCategories(Category $category): bool
    {
        return $category->subCategories()->exists();
    }
}
