<?php

namespace App\Http\Controllers\backend;

use App\Helpers\FlashNotification;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubCategory\StoreSubCategoryRequest;
use App\Http\Requests\SubCategory\UpdateSubCategoryRequest;
use App\Models\Category;
use App\Models\SubCategory;
use Exception;

class SubCategoryController extends Controller
{
    public function all_subCategories()
    {
        $subCategories = SubCategory::latest()->get();

        return view('admin.backend.subCategory.all_subCategories', compact('subCategories'));
    }

    public function add_subCategory()
    {
        $categories = Category::all();

        return view('admin.backend.subCategory.add_subCategory', compact('categories'));
    }

    public function store_subCategory(StoreSubCategoryRequest $request)
    {
        $data = $request->validated();
        $data['subCategory_slug'] = strtolower(str_replace(' ', '-', $data['subCategory_name']));

        try {
            SubCategory::create($data);

            return redirect()
                ->route('admin.all_subCategories')
                ->with(FlashNotification::success('Subcategory added successfully.'));
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with(FlashNotification::error('Oops! Something went wrong. '.$e->getMessage()));
        }
    }

    public function edit_subCategory(string $id)
    {
        $subCategory = SubCategory::find($id);
        $categories = Category::all();

        return view('admin.backend.subCategory.edit_subCategory', compact('subCategory', 'categories'));
    }

    public function update_subCategory(UpdateSubCategoryRequest $request, string $id)
    {
        $data = $request->validated();
        $data['subCategory_slug'] = strtolower(str_replace(' ', '-', $data['subCategory_name']));

        try {
            SubCategory::find($id)->update($data);

            return redirect()
                ->route('admin.all_subCategories')
                ->with(FlashNotification::success('Subcategory updated successfully.'));
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with(FlashNotification::error('Oops! Something went wrong. '.$e->getMessage()));
        }
    }

    public function destroy_subCategory(string $id)
    {
        try {
            SubCategory::find($id)->delete();

            return redirect()
                ->back()
                ->with(FlashNotification::success('Subcategory deleted successfully.'));
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with(FlashNotification::error('Oops! Something went wrong. '.$e->getMessage()));
        }
    }
}
