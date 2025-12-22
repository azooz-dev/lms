<?php

declare(strict_types=1);

namespace App\Services;

use App\Helpers\SlugGenerator;
use App\Models\BlogCategory;
use App\Models\Post;
use App\Models\Tag;
use App\Repositories\Contracts\BlogCategoryRepositoryInterface;
use App\Repositories\Contracts\PostRepositoryInterface;
use App\Repositories\Contracts\TagRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;

class BlogService
{
    public function __construct(
        private readonly BlogCategoryRepositoryInterface $blogCategoryRepository,
        private readonly PostRepositoryInterface $postRepository,
        private readonly TagRepositoryInterface $tagRepository
    ) {}

    // Blog Category Methods
    public function getAllCategories(): Collection
    {
        return $this->blogCategoryRepository->getAllLatest();
    }

    public function findCategoryById(int $id): ?BlogCategory
    {
        return $this->blogCategoryRepository->find($id);
    }

    public function createCategory(array $data): BlogCategory
    {
        $data['category_slug'] = SlugGenerator::generate($data['category_name']);

        return $this->blogCategoryRepository->create($data);
    }

    public function updateCategory(BlogCategory $category, array $data): BlogCategory
    {
        if (isset($data['category_name'])) {
            $data['category_slug'] = SlugGenerator::generate($data['category_name']);
        }

        return $this->blogCategoryRepository->update($category, $data);
    }

    public function deleteCategory(BlogCategory $category): bool
    {
        return $this->blogCategoryRepository->delete($category);
    }

    // Post Methods
    public function getAllPosts(): Collection
    {
        return $this->postRepository->getAllLatest();
    }

    public function getPostsPaginated(int $perPage = 10): LengthAwarePaginator
    {
        return $this->postRepository->getLatestPaginated($perPage);
    }

    public function getRecentPosts(int $limit = 3): Collection
    {
        return $this->postRepository->getLatestLimit($limit);
    }

    public function findPostById(int $id): ?Post
    {
        return $this->postRepository->find($id);
    }

    public function findPostBySlug(string $slug): ?Post
    {
        return $this->postRepository->findBySlug($slug);
    }

    public function getPostsByCategoryId(int $categoryId, int $perPage = 2): LengthAwarePaginator
    {
        return $this->postRepository->getByCategoryId($categoryId, $perPage);
    }

    public function createPost(array $data, UploadedFile $image, int $adminId, ?string $tags = null): Post
    {
        $data['admin_id'] = $adminId;
        $data['image'] = $this->processImage($image);
        $data['slug'] = SlugGenerator::generate($data['title']);

        $post = $this->postRepository->create($data);

        if ($tags) {
            $this->processTags($post, $tags);
        }

        return $post;
    }

    public function updatePost(Post $post, array $data, ?UploadedFile $image = null, ?string $tags = null): Post
    {
        if ($image) {
            $this->deletePostImage($post);
            $data['image'] = $this->processImage($image);
        }

        if (isset($data['title'])) {
            $data['slug'] = SlugGenerator::generate($data['title']);
        }

        $post = $this->postRepository->update($post, $data);

        // Handle tags
        $this->postRepository->detachTags($post);
        if ($tags) {
            $this->processTags($post, $tags);
        }

        return $post;
    }

    public function deletePost(Post $post): bool
    {
        $this->postRepository->detachTags($post);
        $this->deletePostImage($post);

        return $this->postRepository->delete($post);
    }

    public function getPostTags(Post $post): string
    {
        return implode(', ', $post->tags->pluck('name')->toArray());
    }

    // Tag Methods
    public function findTagById(int $id): ?Tag
    {
        return $this->tagRepository->find($id);
    }

    private function processImage(UploadedFile $image): string
    {
        $manager = new ImageManager(new Driver);
        $filename = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();

        $img = $manager->read($image)->resize(370, 247)->toJpeg(80);
        $img->save('storage/upload/posts_images/'.$filename);

        return $filename;
    }

    private function processTags(Post $post, string $tags): void
    {
        $words = explode(',', $tags);

        foreach ($words as $word) {
            $word = trim($word);
            if (empty($word)) {
                continue;
            }

            $tagData = [
                'name' => $word,
                'slug' => SlugGenerator::generate($word),
            ];

            $tag = $this->tagRepository->create($tagData);
            $this->postRepository->attachTags($post, [$tag->id]);
        }
    }

    private function deletePostImage(Post $post): void
    {
        if (! empty($post->image) && Storage::exists('public/upload/posts_images/'.$post->image)) {
            Storage::delete('public/upload/posts_images/'.$post->image);
        }
    }
}
