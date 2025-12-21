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

    public function hasSubCategories(Category $category): bool
    {
        return $category->subCategories()->exists();
    }

    public function createWithSlug(array $data): Category
    {
        $data['category_slug'] = $this->generateSlug($data['category_name']);

        return Category::create($data);
    }

    public function updateWithSlug(Category $category, array $data): Category
    {
        if (isset($data['category_name'])) {
            $data['category_slug'] = $this->generateSlug($data['category_name']);
        }

        $category->update($data);

        return $category;
    }

    private function generateSlug(string $name): string
    {
        return strtolower(str_replace(' ', '-', $name));
    }
}
