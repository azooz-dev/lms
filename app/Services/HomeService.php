<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\CourseRepositoryInterface;
use App\Repositories\Contracts\PostRepositoryInterface;
use App\Repositories\Contracts\ReviewRepositoryInterface;
use App\Repositories\Contracts\SubCategoryRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\WishListRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class HomeService
{
    public function __construct(
        private readonly CategoryRepositoryInterface $categoryRepository,
        private readonly SubCategoryRepositoryInterface $subCategoryRepository,
        private readonly CourseRepositoryInterface $courseRepository,
        private readonly PostRepositoryInterface $postRepository,
        private readonly ReviewRepositoryInterface $reviewRepository,
        private readonly WishListRepositoryInterface $wishListRepository,
        private readonly UserRepositoryInterface $userRepository
    ) {}

    public function getIndexPageData(): array
    {
        return [
            'categories' => $this->categoryRepository->getAllLatest(),
            'courses' => $this->getActiveCourses(6),
            'coursesFeatured' => $this->getFeaturedCourses(6),
            'wishLists' => $this->wishListRepository->all(),
            'posts' => $this->postRepository->getLatestLimit(6),
            'reviews' => $this->reviewRepository->getActiveReviews(),
        ];
    }

    public function getActiveCourses(int $limit): Collection
    {
        return $this->courseRepository->getActiveCoursesLatest($limit);
    }

    public function getFeaturedCourses(int $limit): Collection
    {
        return $this->courseRepository->getFeaturedCourses($limit);
    }

    public function getAllCategories(): Collection
    {
        return $this->categoryRepository->all();
    }

    public function getCategoriesOrdered(): Collection
    {
        return $this->categoryRepository->getAllOrdered('category_name', 'asc');
    }

    public function getCategoryByIdAndSlug(int $id, string $slug): mixed
    {
        return $this->categoryRepository->findByIdAndSlug($id, $slug);
    }

    public function getSubCategoryByIdAndSlug(int $id, string $slug): mixed
    {
        return $this->subCategoryRepository->findByIdAndSlug($id, $slug);
    }

    public function getCourseByIdAndSlug(int $id, string $slug): mixed
    {
        return $this->courseRepository->findByIdAndSlug($id, $slug);
    }

    public function getInstructorById(int $id): mixed
    {
        return $this->userRepository->find($id);
    }

    public function getAllWishLists(): Collection
    {
        return $this->wishListRepository->all();
    }
}
