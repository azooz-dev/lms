<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Exception;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    
    public function all_subCategories() {
        $subCategories = SubCategory::latest()->get();
        return view('admin.backend.subCategory.all_subCategories', compact('subCategories'));
    }


    public function add_subCategory() {
        $categories = Category::all();
        return view('admin.backend.subCategory.add_subCategory', compact('categories'));
    }

    public function store_subCategory(Request $request) {

        $data = $request->validate([
            'category_id' => 'required',
            'subCategory_name' => 'required|string',
        ]);

        $data['subCategory_slug'] = strtolower(str_replace(' ', '-', $data['subCategory_name']));

        try {
            SubCategory::create($data);

            $notification = array(
                'message' => 'Subcategory added successfully.',
                'alert-type' => 'success',
            );
            return redirect()->route('admin.all_subCategories')->with($notification);
        } catch(Exception $e) {
            $notification = array(
                'message' => "Oops! something went wrong." . $e->getMessage(),  
                'alert-type' => 'error',
            );
            return redirect()->back()->with($notification);
        }
    }

    public function edit_subCategory(string $id) {
        $subCategory = SubCategory::find($id);
        $categories = Category::all();
        return view('admin.backend.subCategory.edit_subCategory', compact('subCategory', 'categories'));
    }


    public function update_subCategory(Request $request, string $id) {

        $data = $request->validate([
            'category_id' => 'required',
            'subCategory_name' => 'required|string',
        ]);

        $data['subCategory_slug'] = strtolower(str_replace(' ', '-', $data['subCategory_name']));

        try {
            SubCategory::find($id)->update($data);

            $notification = array(
                'message' => 'Subcategory updated successfully.',
                'alert-type' => 'success',
            );
            return redirect()->route('admin.all_subCategories')->with($notification);
        } catch(Exception $e) {
            $notification = array(
                'message' => "Oops! something went wrong." . $e->getMessage(),  
                'alert-type' => 'error',
            );
            return redirect()->back()->with($notification);
        }
    }


    public function destory_subCategory(string $id) {

        try {
            SubCategory::find($id)->delete();
            $notification = array(
                'message' => 'Subcategory deleted successfully.',
                'alert-type' => 'success',
            );
            return redirect()->back()->with($notification);
        } catch(Exception $e) {
            $notification = array(
                'message' => "Oops! something went wrong." . $e->getMessage(),  
                'alert-type' => 'error',
            );
            return redirect()->back()->with($notification);
        }
    }
}
