<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Post;
use App\Repositories\Contracts\PostRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class PostRepository extends BaseRepository implements PostRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(new Post);
    }

    public function getAllLatest(): Collection
    {
        return Post::latest()->get();
    }

    public function getLatestPaginated(int $perPage = 10): LengthAwarePaginator
    {
        return Post::latest()->paginate($perPage);
    }

    public function getLatestLimit(int $limit): Collection
    {
        return Post::latest()->limit($limit)->get();
    }

    public function findBySlug(string $slug): ?Post
    {
        return Post::where('slug', $slug)->first();
    }

    public function getByCategoryId(int $categoryId, int $perPage = 10): LengthAwarePaginator
    {
        return Post::where('category_id', $categoryId)->paginate($perPage);
    }

    public function createWithSlug(array $data): Post
    {
        $data['slug'] = $this->generateSlug($data['title']);

        return Post::create($data);
    }

    public function updateWithSlug(Post $post, array $data): Post
    {
        if (isset($data['title'])) {
            $data['slug'] = $this->generateSlug($data['title']);
        }

        $post->update($data);

        return $post;
    }

    public function attachTags(Post $post, array $tagIds): void
    {
        $post->tags()->attach($tagIds);
    }

    public function detachTags(Post $post): void
    {
        $post->tags()->detach();
    }

    private function generateSlug(string $title): string
    {
        return strtolower(str_replace(' ', '-', $title));
    }
}
