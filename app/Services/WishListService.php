<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\WishListRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;

class WishListService
{
    public function __construct(
        private readonly WishListRepositoryInterface $wishListRepository,
        private readonly UserRepositoryInterface $userRepository
    ) {}

    public function toggleWishList(int $userId, int $courseId): array
    {
        $wishList = $this->wishListRepository->findByUserAndCourse($userId, $courseId);

        if ($wishList) {
            $this->wishListRepository->delete($wishList);

            return [
                'success' => true,
                'message' => 'Course has been removed from your wishlist.',
                'action' => 'removed',
            ];
        }

        $this->wishListRepository->create([
            'user_id' => $userId,
            'course_id' => $courseId,
        ]);

        return [
            'success' => true,
            'message' => 'Successfully added to your wishlist.',
            'action' => 'added',
        ];
    }

    public function getUserWishListCourses(int $userId): Collection
    {
        $user = $this->userRepository->find($userId);

        if (! $user) {
            return collect();
        }

        $courses = $user->wishlistCourses;

        foreach ($courses as $course) {
            $course->image = Storage::url('upload/course/images/'.$course->image);
            $course->amount = round(($course->selling_price - $course->discount_price) / $course->selling_price * 100);
            $course->instructor = $course->instructor->name;
        }

        return $courses;
    }

    public function removeFromWishList(int $userId, int $courseId): array
    {
        $wishList = $this->wishListRepository->findByUserAndCourse($userId, $courseId);

        if ($wishList) {
            $this->wishListRepository->delete($wishList);

            return [
                'success' => true,
                'message' => 'Successfully removed course from your wishlist.',
            ];
        }

        return [
            'success' => false,
            'message' => 'The course is not in your wishlist.',
        ];
    }

    public function getAllWishLists(): Collection
    {
        return $this->wishListRepository->all();
    }
}
