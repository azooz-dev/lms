<?php

declare(strict_types=1);

namespace App\Http\Controllers\backend;

use App\Helpers\FlashNotification;
use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Services\CategoryService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function __construct(
        private readonly CategoryService $categoryService
    ) {}

    public function all_categories(): View
    {
        $categories = $this->categoryService->getAllCategories();

        return view('admin.backend.category.all_categories', compact('categories'));
    }

    public function edit_category(string $id): View
    {
        $category = $this->categoryService->findById((int) $id);

        return view('admin.backend.category.edit_category', compact('category'));
    }

    public function add_category(): View
    {
        return view('admin.backend.category.add_category');
    }

    public function store_category(StoreCategoryRequest $request): RedirectResponse
    {
        try {
            $this->categoryService->createCategory(
                $request->validated(),
                $request->file('image')
            );

            return redirect()
                ->route('admin.all_categories')
                ->with(FlashNotification::success('Category added successfully.'));
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with(FlashNotification::error('Oops! Something went wrong. '.$e->getMessage()));
        }
    }

    public function update_category(UpdateCategoryRequest $request, string $id): RedirectResponse
    {
        try {
            $category = $this->categoryService->findById((int) $id);

            $this->categoryService->updateCategory(
                $category,
                $request->validated(),
                $request->file('image')
            );

            return redirect()
                ->route('admin.all_categories')
                ->with(FlashNotification::success('Category updated successfully.'));
        } catch (Exception $e) {
            return back()->with(FlashNotification::error('Oops! Something went wrong.'));
        }
    }

    public function destroy_category(string $id): RedirectResponse
    {
        try {
            $category = $this->categoryService->findById((int) $id);

            if ($this->categoryService->hasSubCategories($category)) {
                return back()->with(
                    FlashNotification::error(
                        'Cannot delete this category because it has subcategories. Please delete all subcategories first.'
                    )
                );
            }

            $this->categoryService->deleteCategory($category);

            return back()->with(FlashNotification::success('Category deleted successfully.'));
        } catch (Exception $e) {
            return back()->with(FlashNotification::error('Oops! Something went wrong.'));
        }
    }
}
