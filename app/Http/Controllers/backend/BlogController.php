<?php

namespace App\Http\Controllers\backend;

use App\Helpers\FlashNotification;
use App\Http\Controllers\Controller;
use App\Http\Requests\Blog\StoreBlogCategoryRequest;
use App\Http\Requests\Blog\StorePostRequest;
use App\Http\Requests\Blog\UpdateBlogCategoryRequest;
use App\Http\Requests\Blog\UpdatePostRequest;
use App\Models\BlogCategory;
use App\Models\Post;
use App\Models\Tag;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;

class BlogController extends Controller
{
    public function all_blog_category()
    {
        $categories = BlogCategory::latest()->get();

        return view('admin.backend.blogCategory.all_blog_category', compact('categories'));
    }

    public function store_blog_category(StoreBlogCategoryRequest $request)
    {
        $data = $request->validated();
        $data['category_slug'] = strtolower(str_replace(' ', '-', $data['category_name']));

        BlogCategory::create($data);

        return redirect()->back()->with(FlashNotification::success('Blog Category Added Successfully.'));
    }

    public function blog_category_edit(string $id)
    {
        $category = BlogCategory::find($id);

        return response()->json(['category' => $category]);
    }

    public function update_blog_category(UpdateBlogCategoryRequest $request, string $id)
    {
        $data = $request->validated();
        $data['category_slug'] = strtolower(str_replace(' ', '-', $data['category_name']));

        BlogCategory::find($id)->update($data);

        return redirect()->back()->with(FlashNotification::success('Blog Category Updated Successfully.'));
    }

    public function delete_blog_category(string $id)
    {
        BlogCategory::find($id)->delete();

        return redirect()->back()->with(FlashNotification::success('Blog Category Deleted Successfully.'));
    }

    public function all_posts()
    {
        $posts = Post::latest()->get();

        return view('admin.backend.posts.all_posts', compact('posts'));
    }

    public function add_posts()
    {
        $categories = BlogCategory::latest()->get();

        return view('admin.backend.posts.add_posts', compact('categories'));
    }

    public function store_post(StorePostRequest $request, string $id)
    {
        $data = $request->validated();

        $data['slug'] = strtolower(str_replace(' ', '-', $data['title']));
        $data['admin_id'] = $id;

        try {
            $manager = new ImageManager(new Driver);
            $data['image'] = hexdec(uniqid()).'.'.$request->file('image')->getClientOriginalExtension();
            $path = $request->file('image')->getRealPath();

            if (! file_exists($path) || ! is_readable($path)) {
                throw new Exception('File not found or not readable.');
            }

            $img = $manager->read($request->file('image'))->resize(370, 247)->toJpeg(80);
            $img->save('storage/upload/posts_images/'.$data['image']);

            $post = Post::create($data);

            if ($request->has('tag') && ! empty($request->tag)) {
                $tags = $request->tag;
                $words = explode(',', $tags);

                foreach ($words as $word) {
                    $tag = Tag::create([
                        'name' => trim($word),
                        'slug' => strtolower(str_replace(' ', '-', trim($word))),
                    ]);

                    DB::table('post_tag')->insert([
                        'post_id' => $post->id,
                        'tag_id' => $tag->id,
                    ]);
                }
            }

            return redirect()
                ->route('admin.all_posts')
                ->with(FlashNotification::success('Post created successfully.'));
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with(FlashNotification::error('Oops! Something went wrong. '.$e->getMessage()));
        }
    }

    public function post_edit(string $id)
    {
        $post = Post::find($id);
        $categories = BlogCategory::latest()->get();

        $tags = implode(', ', $post->tags->pluck('name')->toArray());

        return view('admin.backend.posts.edit_post', compact('post', 'categories', 'tags'));
    }

    /**
     * Updates an existing blog post
     *
     * @param  UpdatePostRequest  $request  The validated request object
     * @param  string  $id  The ID of the post
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update_post(UpdatePostRequest $request, string $id)
    {
        $post = Post::find($id);
        $data = $request->validated();

        try {
            // Process the image if new image is uploaded
            if ($request->hasFile('image')) {
                $manager = new ImageManager(new Driver);

                // Delete old image if exists
                if (! empty($post->image) && Storage::exists('public/upload/posts_images/'.$post->image)) {
                    Storage::delete('public/upload/posts_images/'.$post->image);
                }

                $data['image'] = hexdec(uniqid()).'.'.$request->file('image')->getClientOriginalExtension();
                $img = $manager->read($request->file('image'))->resize(370, 247)->toJpeg(80);
                $img->save('storage/upload/posts_images/'.$data['image']);
            }

            // Update the post data
            $data['slug'] = strtolower(str_replace(' ', '-', $data['title']));
            if ($request->filled('category_id')) {
                $data['category_id'] = $request->category_id;
            }

            $post->update($data);

            // Manage tags
            $post->tags()->detach();

            if ($request->has('tag') && ! empty($request->tag)) {
                $words = explode(',', $request->tag);

                foreach ($words as $word) {
                    $tag = Tag::create([
                        'name' => trim($word),
                        'slug' => strtolower(str_replace(' ', '-', trim($word))),
                    ]);

                    DB::table('post_tag')->insert([
                        'post_id' => $post->id,
                        'tag_id' => $tag->id,
                    ]);
                }
            }

            return redirect()
                ->route('admin.all_posts')
                ->with(FlashNotification::success('Post updated successfully.'));
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with(FlashNotification::error('Oops! Something went wrong. '.$e->getMessage()));
        }
    }

    public function delete_post(string $id)
    {
        $post = Post::find($id);
        $post->tags()->detach();
        $post->delete();

        return back()->with(FlashNotification::success('Post Deleted Successfully.'));
    }

    public function blog_details(string $slug)
    {
        $post = Post::where('slug', $slug)->first();
        $categories = BlogCategory::latest()->get();
        $posts = Post::latest()->limit(3)->get();

        return view('frontend.posts.blog_details', compact('post', 'categories', 'posts'));
    }

    public function blog_category_details(string $id)
    {
        $category = BlogCategory::find($id);
        $category_posts = Post::where('category_id', $id)->paginate(2);
        $categories = BlogCategory::latest()->get();
        $posts = Post::latest()->limit(3)->get();

        return view('frontend.posts.blog_category_details', compact('category', 'categories', 'posts', 'category_posts'));
    }

    public function all_blog()
    {
        $posts = Post::latest()->paginate(2);
        $categories = BlogCategory::latest()->get();
        $recentPosts = Post::latest()->limit(3)->get();

        return view('frontend.posts.all_posts', compact('posts', 'categories', 'recentPosts'));
    }
}
