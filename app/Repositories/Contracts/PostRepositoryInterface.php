<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface PostRepositoryInterface extends RepositoryInterface
{
    public function getAllLatest(): Collection;

    public function getLatestPaginated(int $perPage = 10): LengthAwarePaginator;

    public function getLatestLimit(int $limit): Collection;

    public function findBySlug(string $slug): ?Post;

    public function getByCategoryId(int $categoryId, int $perPage = 10): LengthAwarePaginator;

    public function attachTags(Post $post, array $tagIds): void;

    public function detachTags(Post $post): void;
}
