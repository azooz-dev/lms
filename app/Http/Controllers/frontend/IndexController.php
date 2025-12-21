<?php

declare(strict_types=1);

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Services\HomeService;
use Illuminate\View\View;

class IndexController extends Controller
{
    public function __construct(
        private readonly HomeService $homeService
    ) {}

    public function course_details(string $id, string $slug): View
    {
        $course = $this->homeService->getCourseByIdAndSlug((int) $id, $slug);
        $categories = $this->homeService->getCategoriesOrdered();

        return view('frontend.course.course_details', compact('course', 'categories'));
    }

    public function category_courses(string $id, string $slug): View
    {
        $category = $this->homeService->getCategoryByIdAndSlug((int) $id, $slug);
        $categories = $this->homeService->getCategoriesOrdered();

        return view('frontend.category.category_courses', compact('category', 'categories'));
    }

    public function subCategory_courses(string $id, string $slug): View
    {
        $subCategory = $this->homeService->getSubCategoryByIdAndSlug((int) $id, $slug);
        $categories = $this->homeService->getCategoriesOrdered();
        $wishLists = $this->homeService->getAllWishLists();

        return view('frontend.category.subCategory_courses', compact('subCategory', 'categories', 'wishLists'));
    }

    public function instructor_details(string $id): View
    {
        $instructor = $this->homeService->getInstructorById((int) $id);

        return view('frontend.instructor.instructor_details', compact('instructor'));
    }
}
