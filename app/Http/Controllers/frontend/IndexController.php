<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\SubCategory;
use App\Models\User;
// use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function course_details(string $id, string $slug) {
        $course = Course::where('id', $id)->where('slug', $slug)->first();
        $categories = Category::orderBy('category_name', 'asc')->get();
        return view('frontend.course.course_details', compact('course', 'categories'));
    }


    public function category_courses(string $id, string $slug) {
        $category = Category::where('id', $id)->where('category_slug', $slug)->first();
        $categories = Category::orderBy('category_name', 'asc')->get();
        return view('frontend.category.category_courses', compact('category', 'categories'));
    }

    public function subCategory_courses(string $id, string $slug) {
        $subCategory = SubCategory::where('id', $id)->where('subCategory_slug', $slug)->first();
        $categories = Category::orderBy('category_name', 'asc')->get();
        return view('frontend.category.subCategory_courses', compact('subCategory', 'categories'));
    }


    public function instructor_details(string $id) {
        $instructor = User::find($id);
        return view('frontend.instructor.instructor_details', compact('instructor'));
    }
}
