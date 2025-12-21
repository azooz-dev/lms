<?php

declare(strict_types=1);

namespace App\Http\Controllers\backend;

use App\Helpers\FlashNotification;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubCategory\StoreSubCategoryRequest;
use App\Http\Requests\SubCategory\UpdateSubCategoryRequest;
use App\Services\SubCategoryService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SubCategoryController extends Controller
{
    public function __construct(
        private readonly SubCategoryService $subCategoryService
    ) {}

    public function all_subCategories(): View
    {
        $subCategories = $this->subCategoryService->getAllSubCategories();

        return view('admin.backend.subCategory.all_subCategories', compact('subCategories'));
    }

    public function add_subCategory(): View
    {
        $categories = $this->subCategoryService->getAllCategories();

        return view('admin.backend.subCategory.add_subCategory', compact('categories'));
    }

    public function store_subCategory(StoreSubCategoryRequest $request): RedirectResponse
    {
        try {
            $this->subCategoryService->createSubCategory($request->validated());

            return redirect()
                ->route('admin.all_subCategories')
                ->with(FlashNotification::success('Subcategory added successfully.'));
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with(FlashNotification::error('Oops! Something went wrong. '.$e->getMessage()));
        }
    }

    public function edit_subCategory(string $id): View
    {
        $subCategory = $this->subCategoryService->findById((int) $id);
        $categories = $this->subCategoryService->getAllCategories();

        return view('admin.backend.subCategory.edit_subCategory', compact('subCategory', 'categories'));
    }

    public function update_subCategory(UpdateSubCategoryRequest $request, string $id): RedirectResponse
    {
        try {
            $subCategory = $this->subCategoryService->findById((int) $id);
            $this->subCategoryService->updateSubCategory($subCategory, $request->validated());

            return redirect()
                ->route('admin.all_subCategories')
                ->with(FlashNotification::success('Subcategory updated successfully.'));
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with(FlashNotification::error('Oops! Something went wrong. '.$e->getMessage()));
        }
    }

    public function destroy_subCategory(string $id): RedirectResponse
    {
        try {
            $subCategory = $this->subCategoryService->findById((int) $id);
            $this->subCategoryService->deleteSubCategory($subCategory);

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
