<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface OrderRepositoryInterface extends RepositoryInterface
{
    public function getTotalRevenue(): float;

    public function getPendingOrdersCount(): int;

    public function getCompletedOrdersCount(): int;

    public function getMonthlySales(int $year): Collection;

    public function getDailySales(): Collection;

    public function getRecentOrders(int $limit): Collection;

    public function getByInstructorId(int $instructorId): Collection;

    public function getVisibleByUserId(int $userId): Collection;

    public function existsForUserAndCourses(array $courseIds, int $userId): bool;

    public function getOrdersCountInDateRange(DateTimeInterface $start, DateTimeInterface $end): int;

    public function getRevenueInDateRange(DateTimeInterface $start, DateTimeInterface $end): float;

    public function hideFromUser(Model $order): void;

    // Instructor-specific methods
    public function countByInstructor(int $instructorId): int;

    public function getTotalRevenueByInstructor(int $instructorId): float;

    public function getUniqueStudentsByInstructor(int $instructorId): int;

    public function getPendingOrdersCountByInstructor(int $instructorId): int;

    public function getCompletedOrdersCountByInstructor(int $instructorId): int;

    public function getMonthlySalesByInstructor(int $instructorId, int $year): Collection;

    public function getRecentOrdersByInstructor(int $instructorId, int $limit): Collection;

    public function getOrdersCountByInstructorInDateRange(int $instructorId, DateTimeInterface $start, DateTimeInterface $end): int;

    public function getRevenueByInstructorInDateRange(int $instructorId, DateTimeInterface $start, DateTimeInterface $end): float;

    public function getUniqueStudentsByInstructorInDateRange(int $instructorId, DateTimeInterface $start, DateTimeInterface $end): int;
}
