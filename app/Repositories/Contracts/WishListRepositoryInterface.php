<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Wish_list;
use Illuminate\Database\Eloquent\Collection;

interface WishListRepositoryInterface
{
    public function all(): Collection;

    public function findByUserAndCourse(int $userId, int $courseId): ?Wish_list;

    public function create(array $data): Wish_list;

    public function delete(Wish_list $wishList): bool;

    public function getByUserId(int $userId): Collection;
}
