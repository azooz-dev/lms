<?php

namespace App\Http\Controllers\backend;

use App\Helpers\ImageResizer;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;

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

    public function store_category(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            $image = $request->file('image');
            $filename = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            $resizedPath = public_path("storage/upload/category_images/{$filename}");
            ImageResizer::resize($image, 370, 246, $resizedPath);

            $data = [
                'category_name' => $request->category_name,
                'category_slug' => strtolower(str_replace(' ', '-', $request->category_name)),
                'image' => $filename,
            ];

            Category::create($data);

            $notification = array(
                'message' => 'Category added successfully.',
                'alert-type' => 'success',
            );
            return redirect()->route('admin.all_categories')->with($notification);
        } catch (Exception $e) {
            $notification = array(
                'message' => 'Oops! Something went wrong.' . $e->getMessage(),
                'alert-type' => 'error',
            );
            return redirect()->back()->with($notification);
        }
    }


    /**
     * Update category
     *
     * @param Request $request Request object
     * @param string $id      Category id
     *
     * @return RedirectResponse
     * @throws Exception
     */
    public function update_category(Request $request, string $id)
    {
        // Get the category object
        $category = Category::find($id);
        // Validate the request
        $data = $request->validate([
            'category_name' => 'required|string|max:255',
            'image' => 'sometimes|nullable'
        ]);

        try {
            // If the image is provided, upload and resize it
            if ($request->hasFile('image')) {
                $data['image'] = hexdec(uniqid()) . '.' . $request->file('image')->getClientOriginalExtension();

                $resizedPath = public_path('storage/upload/category_images/' . $data['image']);
                resizeAndSaveImage($request->file('image'), 370, 246, $resizedPath);

                // If the file exists in database and exists in storage folder
                if (!empty($category->image) && file_exists('public/upload/category_images/' . $category->image)) {
                    // Delete the old image from storage
                    unlink('public/upload/category_images/' . $category->image);
                }

                // Update the category with new data
                $data['category_slug'] = strtolower(str_replace(' ', '-', $data['category_name']));
                $category->update($data);

                // Return a success message
                $notification = array(
                    'message' => 'Category updated successfully.',
                    'alert-type' => 'success',
                );

                return redirect()->route('admin.all_categories')->with($notification);
            } else {
                // If no image is provided, just update the category with new data
                $data['category_slug'] = strtolower(str_replace(' ', '-', $data['category_name']));
                $category->update($data);

                // Return a success message
                $notification = array(
                    'message' => 'Category updated successfully.',
                    'alert-type' => 'success',
                );
                return redirect()->route('admin.all_categories')->with($notification);
            }
        } catch (Exception $e) {
            // Return an error message
            $notification = array(
                'message' => 'Oops! something went wrong.',
                'alert-type' => 'error',
            );
            return back()->with($notification);
        }
    }


    public function destroy_category(string $id)
    {
        $category = Category::find($id);

        // Check if the category has any subcategories
        if ($category->subCategories()->exists()) {
            // If subcategories exist, return an error message
            $notification = array(
                'message' => 'Cannot delete this category because it has subcategories. Please delete all subcategories first.',
                'alert-type' => 'error',
            );
            return back()->with($notification);
        }

        // If no subcategories exist, proceed with the deletion
        try {
            // Check if the category has an image and delete it from storage
            if (!empty($category->image) && file_exists('public/upload/category_images/' . $category->image)) {
                unlink('public/upload/category_images/' . $category->image);
            }

            // Delete the category
            $category->delete();

            // Return a success message
            $notification = array(
                'message' => 'Category deleted successfully.',
                'alert-type' => 'success',
            );
            return back()->with($notification);
        } catch (Exception $e) {
            // Return an error message if the deletion fails
            $notification = array(
                'message' => 'Oops! Something went wrong.',
                'alert-type' => 'error',
            );
            return back()->with($notification);
        }
    }
}
