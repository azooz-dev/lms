<?php

namespace App\Http\Controllers\backend;

use App\Helpers\FlashNotification;
use App\Helpers\ImageResizer;
use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Models\Category;
use Exception;

class CategoryController extends Controller
{
    public function all_categories()
    {
        $categories = Category::latest()->get();

        return view('admin.backend.category.all_categories', compact('categories'));
    }

    public function edit_category(string $id)
    {
        $category = Category::find($id);

        return view('admin.backend.category.edit_category', compact('category'));
    }

    public function add_category()
    {
        return view('admin.backend.category.add_category');
    }

    public function store_category(StoreCategoryRequest $request)
    {
        try {
            $image = $request->file('image');
            $filename = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            $resizedPath = public_path("storage/upload/category_images/{$filename}");
            ImageResizer::resize($image, 370, 246, $resizedPath);

            $data = [
                'category_name' => $request->category_name,
                'category_slug' => strtolower(str_replace(' ', '-', $request->category_name)),
                'image' => $filename,
            ];

            Category::create($data);

            return redirect()
                ->route('admin.all_categories')
                ->with(FlashNotification::success('Category added successfully.'));
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with(FlashNotification::error('Oops! Something went wrong.'.$e->getMessage()));
        }
    }

    /**
     * Update category
     *
     * @param  Request  $request  Request object
     * @param  string  $id  Category id
     * @return RedirectResponse
     *
     * @throws Exception
     */
    public function update_category(UpdateCategoryRequest $request, string $id)
    {
        // Get the category object
        $category = Category::find($id);
        $data = $request->validated();

        try {
            // If the image is provided, upload and resize it
            if ($request->hasFile('image')) {
                $data['image'] = hexdec(uniqid()).'.'.$request->file('image')->getClientOriginalExtension();

                $resizedPath = public_path('storage/upload/category_images/'.$data['image']);
                ImageResizer::resize($request->file('image'), 370, 246, $resizedPath);

                // If the file exists in database and exists in storage folder
                if (! empty($category->image) && file_exists('public/upload/category_images/'.$category->image)) {
                    // Delete the old image from storage
                    unlink('public/upload/category_images/'.$category->image);
                }

                // Update the category with new data
                $data['category_slug'] = strtolower(str_replace(' ', '-', $data['category_name']));
                $category->update($data);

                // Return a success message
                return redirect()
                    ->route('admin.all_categories')
                    ->with(FlashNotification::success('Category updated successfully.'));
            } else {
                // If no image is provided, just update the category with new data
                $data['category_slug'] = strtolower(str_replace(' ', '-', $data['category_name']));
                $category->update($data);

                // Return a success message
                return redirect()
                    ->route('admin.all_categories')
                    ->with(FlashNotification::success('Category updated successfully.'));
            }
        } catch (Exception $e) {
            // Return an error message
            return back()->with(FlashNotification::error('Oops! something went wrong.'));
        }
    }

    public function destroy_category(string $id)
    {
        $category = Category::find($id);

        // Check if the category has any subcategories
        if ($category->subCategories()->exists()) {
            // If subcategories exist, return an error message
            return back()->with(
                FlashNotification::error(
                    'Cannot delete this category because it has subcategories. Please delete all subcategories first.'
                )
            );
        }

        // If no subcategories exist, proceed with the deletion
        try {
            // Check if the category has an image and delete it from storage
            if (! empty($category->image) && file_exists('public/upload/category_images/'.$category->image)) {
                unlink('public/upload/category_images/'.$category->image);
            }

            // Delete the category
            $category->delete();

            // Return a success message
            return back()->with(FlashNotification::success('Category deleted successfully.'));
        } catch (Exception $e) {
            // Return an error message if the deletion fails
            return back()->with(FlashNotification::error('Oops! Something went wrong.'));
        }
    }
}
