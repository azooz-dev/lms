<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\BlogCategory;
use App\Repositories\Contracts\BlogCategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class BlogCategoryRepository extends BaseRepository implements BlogCategoryRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(new BlogCategory);
    }

    public function getAllLatest(): Collection
    {
        return BlogCategory::latest()->get();
    }

    public function createWithSlug(array $data): BlogCategory
    {
        $data['category_slug'] = $this->generateSlug($data['category_name']);

        return BlogCategory::create($data);
    }

    public function updateWithSlug(BlogCategory $category, array $data): BlogCategory
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
