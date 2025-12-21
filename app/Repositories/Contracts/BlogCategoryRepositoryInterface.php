<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\BlogCategory;
use Illuminate\Database\Eloquent\Collection;

interface BlogCategoryRepositoryInterface extends RepositoryInterface
{
    public function getAllLatest(): Collection;

    public function createWithSlug(array $data): BlogCategory;

    public function updateWithSlug(BlogCategory $category, array $data): BlogCategory;
}
