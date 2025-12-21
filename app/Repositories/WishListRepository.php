<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Wish_list;
use App\Repositories\Contracts\WishListRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class WishListRepository implements WishListRepositoryInterface
{
    public function all(): Collection
    {
        return Wish_list::all();
    }

    public function findByUserAndCourse(int $userId, int $courseId): ?Wish_list
    {
        return Wish_list::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->first();
    }

    public function create(array $data): Wish_list
    {
        return Wish_list::create($data);
    }

    public function delete(Wish_list $wishList): bool
    {
        return $wishList->delete();
    }

    public function getByUserId(int $userId): Collection
    {
        return Wish_list::where('user_id', $userId)->get();
    }
}
