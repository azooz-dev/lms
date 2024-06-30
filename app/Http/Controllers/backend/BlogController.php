<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\Post;
use App\Models\Tag;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;

class BlogController extends Controller
{
    public function all_blog_category() {
        $categories = BlogCategory::latest()->get();

        return view('admin.backend.blogCategory.all_blog_category', compact('categories'));
    }

    public function store_blog_category(Request $request) {

        $data = $request->validate([
            'category_name' => 'required|unique:blog_categories,category_name',
        ]);

        $data['category_slug'] = strtolower(str_replace(' ', '-', $data['category_name']));

        BlogCategory::create($data);

        $notification = [
            'message' => 'Blog Category Added Successfully.',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
    }


    public function blog_category_edit(string $id) {

        $category = BlogCategory::find($id);

        return response()->json(['category' => $category]);
    }

    public function update_blog_category(Request $request, string $id) {

        $data = $request->validate([
            'category_name' => 'required|unique:blog_categories,category_name,' . $id,
        ]);

        $data['category_slug'] = strtolower(str_replace(' ', '-', $data['category_name']));

        BlogCategory::find($id)->update($data);

        $notification = [
            'message' => 'Blog Category Updated Successfully.',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
    }

    public function delete_blog_category(string $id) {

        BlogCategory::find($id)->delete();

        $notification = [
            'message' => 'Blog Category Deleted Successfully.',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
    }



    public function all_posts() {
        $posts = Post::latest()->get();

        return view('admin.backend.posts.all_posts', compact('posts'));
    }

    public function add_posts() {
        $categories = BlogCategory::latest()->get();

        return view('admin.backend.posts.add_posts', compact('categories'));
    }

    public function store_post(Request $request, string $id) {

        $data = $request->validate([
            'category_id' => 'required',
            'title' => 'required|string|max:50',
            'description' => 'required',
            'image' => 'required',
        ]);


        $data['slug'] = strtolower(str_replace(' ', '-', $data['title']));
        $data['category_id'] = $request->category_id;
        $data['admin_id'] = $id;

        try{
            $manager = new ImageManager(new Driver());
            $data['image'] = hexdec(uniqid()) . '.' . $request->file('image')->getClientOriginalExtension();
            $path = $request->file('image')->getRealPath(); // Get the real path of the uploaded file

            // Check if the file exists and is readable
            if (!file_exists($path) || !is_readable($path)) {
                throw new Exception('File not found or not readable.');
            }

            // Process the image
            $img = $manager->read($request->file('image'))->resize(370, 247)->toJpeg(80);
            $img->save('storage/upload/posts_images/' . $data['image']);

            $post = POST::create($data);

            $tags = $request->tag;

            $words = explode(',', $tags);

            foreach ($words as $key => $word) {
                if ($key > 1) {
                    $tag = Tag::create([
                        'name' => $word,
                        'slug' => strtolower(str_replace(' ', '-', $word)),
                    ]);

                    DB::table('post_tag')->insert([
                        'post_id' => $post->id,
                        'tag_id' => $tag->id
                    ]);
                }
            }

            $notification = array(
                'message' => 'Post created successfully.',
                'alert-type' => 'success',
            );
            return redirect()->route('admin.all_posts')->with($notification);
        } catch (Exception $e) {
            $notification = array(
                'message' => 'Oops! Something went wrong. Please try again.',
                'alert-type' => 'error',
            );
            return redirect()->back()->with($notification);
        }
    }

    public function post_edit(string $id) {
        $post = Post::find($id);
        $categories = BlogCategory::latest()->get();

        $tags = implode(", ", $post->tags->pluck('name')->toArray());
        return view('admin.backend.posts.edit_post', compact('post', 'categories', 'tags'));
    }

    /**
     * Updates an existing blog post
     *
     * @param \Illuminate\Http\Request $request The request object
     * @param string $id The ID of the post
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update_post(Request $request, string $id) {

        // Get the post instance
        $post = Post::find($id);

        // Validate the request data
        $data = $request->validate([
            'title' => 'required|string|max:50',
            'description' => 'required',
        ]);

        try {
            // Process the image if new image is uploaded
            if ($request->hasFile('image')) {
                // Create an image manager instance with the GD driver
                $manager = new ImageManager(new Driver());

                // If the file exists in database and exists in storage folder
                if (!empty($post->image) && Storage::exists('public/upload/posts_images/' . $post->image)) {
                    Storage::delete('public/upload/posts_images/' . $post->image);
                }

                // Get the file name with extension
                $data['image'] = hexdec(uniqid()) . '.' . $request->file('image')->getClientOriginalExtension();

                // Process the image
                $img = $manager->read($request->file('image'))->resize(370, 247)->toJpeg(80);

                // Save the image to storage
                $img->save('storage/upload/posts_images/' . $data['image']);
            }

            // Update the post data
            $data['slug'] = strtolower(str_replace(' ', '-', $data['title']));
            $data['category_id'] = $request->category_id;

            // Oops! Something went wrong. Please try again.Method IlluminateDatabaseEloquentCollection::detach does not exist.

            // Update the post
            $post->update($data);

            // Manage tags
            $tags = $request->tag;

            // Detach existing tags
            $post->tags()->detach();

            // Attach new tags
            $words = explode(',', $tags);

            foreach ($words as $word) {
                    $tag = Tag::create([
                        'name' => $word,
                        'slug' => strtolower(str_replace(' ', '-', $word)),
                    ]);

                    DB::table('post_tag')->insert([
                        'post_id' => $post->id,
                        'tag_id' => $tag->id
                    ]);
            }

            // Redirect to all posts page with success notification
            $notification = array(
                'message' => 'Post updated successfully.',
                'alert-type' => 'success',
            );
            return redirect()->route('admin.all_posts')->with($notification);
        } catch (Exception $e) {
            // Redirect to back with error notification
            $notification = array(
                'message' => 'Oops! Something went wrong. Please try again.' . $e->getMessage(),
                'alert-type' => 'error',
            );
            return redirect()->back()->with($notification);
        }
    }

    public function delete_post(string $id) {

        $post = Post::find($id);

        // Detach existing tags
        $post->tags()->detach();

        $post->delete();

        $notification = [
            'message' => 'Post Deleted Successfully.',
            'alert-type' => 'success'
        ];

        return back()->with($notification);
    }

    public function blog_details(string $slug) {

        $post = Post::where('slug', $slug)->first();
        $categories = BlogCategory::latest()->get();
        $posts = Post::latest()->limit(3)->get();

        return view('frontend.posts.blog_details', compact('post', 'categories', 'posts'));
    }

    public function blog_category_details(string $id) {

        $category = BlogCategory::find($id);
        $category_posts = Post::where('category_id', $id)->paginate(2);
        $categories = BlogCategory::latest()->get();
        $posts = Post::latest()->limit(3)->get();

        return view('frontend.posts.blog_category_details', compact('category', 'categories', 'posts', 'category_posts'));
    }


    public function all_blog() {
        $posts = Post::latest()->paginate(2);
        $categories = BlogCategory::latest()->get();
        $recentPosts = Post::latest()->limit(3)->get();

        return view('frontend.posts.all_posts', compact('posts', 'categories', 'recentPosts'));
    }
}