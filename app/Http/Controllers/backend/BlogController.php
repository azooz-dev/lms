<?php

declare(strict_types=1);

namespace App\Http\Controllers\backend;

use App\Helpers\FlashNotification;
use App\Http\Controllers\Controller;
use App\Http\Requests\Blog\StoreBlogCategoryRequest;
use App\Http\Requests\Blog\StorePostRequest;
use App\Http\Requests\Blog\UpdateBlogCategoryRequest;
use App\Http\Requests\Blog\UpdatePostRequest;
use App\Services\BlogService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function __construct(
        private readonly BlogService $blogService
    ) {}

    public function all_blog_category(): View
    {
        $categories = $this->blogService->getAllCategories();

        return view('admin.backend.blogCategory.all_blog_category', compact('categories'));
    }

    public function store_blog_category(StoreBlogCategoryRequest $request): RedirectResponse
    {
        $this->blogService->createCategory($request->validated());

        return redirect()->back()->with(FlashNotification::success('Blog Category Added Successfully.'));
    }

    public function blog_category_edit(string $id): JsonResponse
    {
        $category = $this->blogService->findCategoryById((int) $id);

        return response()->json(['category' => $category]);
    }

    public function update_blog_category(UpdateBlogCategoryRequest $request, string $id): RedirectResponse
    {
        $category = $this->blogService->findCategoryById((int) $id);
        $this->blogService->updateCategory($category, $request->validated());

        return redirect()->back()->with(FlashNotification::success('Blog Category Updated Successfully.'));
    }

    public function delete_blog_category(string $id): RedirectResponse
    {
        $category = $this->blogService->findCategoryById((int) $id);
        $this->blogService->deleteCategory($category);

        return redirect()->back()->with(FlashNotification::success('Blog Category Deleted Successfully.'));
    }

    public function all_posts(): View
    {
        $posts = $this->blogService->getAllPosts();

        return view('admin.backend.posts.all_posts', compact('posts'));
    }

    public function add_posts(): View
    {
        $categories = $this->blogService->getAllCategories();

        return view('admin.backend.posts.add_posts', compact('categories'));
    }

    public function store_post(StorePostRequest $request, string $id): RedirectResponse
    {
        try {
            $this->blogService->createPost(
                $request->validated(),
                $request->file('image'),
                (int) $id,
                $request->tag
            );

            return redirect()
                ->route('admin.all_posts')
                ->with(FlashNotification::success('Post created successfully.'));
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with(FlashNotification::error('Oops! Something went wrong. '.$e->getMessage()));
        }
    }

    public function post_edit(string $id): View
    {
        $post = $this->blogService->findPostById((int) $id);
        $categories = $this->blogService->getAllCategories();
        $tags = $this->blogService->getPostTags($post);

        return view('admin.backend.posts.edit_post', compact('post', 'categories', 'tags'));
    }

    public function update_post(UpdatePostRequest $request, string $id): RedirectResponse
    {
        try {
            $post = $this->blogService->findPostById((int) $id);

            $this->blogService->updatePost(
                $post,
                $request->validated(),
                $request->file('image'),
                $request->tag
            );

            return redirect()
                ->route('admin.all_posts')
                ->with(FlashNotification::success('Post updated successfully.'));
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with(FlashNotification::error('Oops! Something went wrong. '.$e->getMessage()));
        }
    }

    public function delete_post(string $id): RedirectResponse
    {
        $post = $this->blogService->findPostById((int) $id);
        $this->blogService->deletePost($post);

        return back()->with(FlashNotification::success('Post Deleted Successfully.'));
    }

    public function blog_details(string $slug): View
    {
        $post = $this->blogService->findPostBySlug($slug);
        $categories = $this->blogService->getAllCategories();
        $posts = $this->blogService->getRecentPosts(3);

        return view('frontend.posts.blog_details', compact('post', 'categories', 'posts'));
    }

    public function blog_category_details(string $id): View
    {
        $category = $this->blogService->findCategoryById((int) $id);
        $category_posts = $this->blogService->getPostsByCategoryId((int) $id, 2);
        $categories = $this->blogService->getAllCategories();
        $posts = $this->blogService->getRecentPosts(3);

        return view('frontend.posts.blog_category_details', compact('category', 'categories', 'posts', 'category_posts'));
    }

    public function all_blog(): View
    {
        $posts = $this->blogService->getPostsPaginated(2);
        $categories = $this->blogService->getAllCategories();
        $recentPosts = $this->blogService->getRecentPosts(3);

        return view('frontend.posts.all_posts', compact('posts', 'categories', 'recentPosts'));
    }
}
