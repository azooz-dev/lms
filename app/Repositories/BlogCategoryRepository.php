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
}
