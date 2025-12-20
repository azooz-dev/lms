<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\Contracts\CourseRepositoryInterface;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\ReviewRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Collection;

class DashboardService
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly UserRepositoryInterface $userRepository,
        private readonly CourseRepositoryInterface $courseRepository,
        private readonly ReviewRepositoryInterface $reviewRepository
    ) {}

    /**
     * Get all dashboard statistics
     */
    public function getStatistics(): array
    {
        return [
            'totalOrders' => $this->getTotalOrders(),
            'totalRevenue' => $this->getTotalRevenue(),
            'totalCustomers' => $this->getTotalCustomers(),
            'totalCourses' => $this->getTotalCourses(),
            'totalInstructors' => $this->getTotalInstructors(),
            'pendingOrders' => $this->getPendingOrders(),
            'completedOrders' => $this->getCompletedOrders(),
            'totalReviews' => $this->getTotalReviews(),
            'pendingReviews' => $this->getPendingReviews(),
        ];
    }

    /**
     * Get weekly percentage changes for key metrics
     */
    public function getPercentageChanges(): array
    {
        return [
            'orderChange' => $this->calculateOrderChange(),
            'revenueChange' => $this->calculateRevenueChange(),
            'customerChange' => $this->calculateCustomerChange(),
        ];
    }

    /**
     * Get monthly sales data for the given year
     */
    public function getMonthlySales(?int $year = null): Collection
    {
        $year = $year ?? (int) date('Y');

        return $this->orderRepository->getMonthlySales($year);
    }

    /**
     * Get daily sales data for the current month
     */
    public function getDailySales(): Collection
    {
        return $this->orderRepository->getDailySales();
    }

    /**
     * Get recent orders with relationships
     */
    public function getRecentOrders(int $limit = 6): Collection
    {
        return $this->orderRepository->getRecentOrders($limit);
    }

    /**
     * Get chart data for AJAX requests
     */
    public function getChartData(): array
    {
        return [
            'monthlySales' => $this->getMonthlySales(),
            'dailySales' => $this->getDailySales(),
        ];
    }

    /**
     * Get total orders count
     */
    private function getTotalOrders(): int
    {
        return $this->orderRepository->count();
    }

    /**
     * Get total revenue from completed orders
     */
    private function getTotalRevenue(): float
    {
        return $this->orderRepository->getTotalRevenue();
    }

    /**
     * Get total customers count
     */
    private function getTotalCustomers(): int
    {
        return $this->userRepository->countByRole('user');
    }

    /**
     * Get total courses count
     */
    private function getTotalCourses(): int
    {
        return $this->courseRepository->count();
    }

    /**
     * Get total instructors count
     */
    private function getTotalInstructors(): int
    {
        return $this->userRepository->countByRole('instructor');
    }

    /**
     * Get pending orders count
     */
    private function getPendingOrders(): int
    {
        return $this->orderRepository->getPendingOrdersCount();
    }

    /**
     * Get completed orders count
     */
    private function getCompletedOrders(): int
    {
        return $this->orderRepository->getCompletedOrdersCount();
    }

    /**
     * Get total reviews count
     */
    private function getTotalReviews(): int
    {
        return $this->reviewRepository->count();
    }

    /**
     * Get pending reviews count
     */
    private function getPendingReviews(): int
    {
        return $this->reviewRepository->getPendingCount();
    }

    /**
     * Calculate week-over-week order change percentage
     */
    private function calculateOrderChange(): float
    {
        $lastWeekOrders = $this->orderRepository->getOrdersCountInDateRange(now()->subWeek(), now());
        $previousWeekOrders = $this->orderRepository->getOrdersCountInDateRange(now()->subWeeks(2), now()->subWeek());

        return $previousWeekOrders > 0
            ? (($lastWeekOrders - $previousWeekOrders) / $previousWeekOrders) * 100
            : 0;
    }

    /**
     * Calculate week-over-week revenue change percentage
     */
    private function calculateRevenueChange(): float
    {
        $lastWeekRevenue = $this->orderRepository->getRevenueInDateRange(now()->subWeek(), now());
        $previousWeekRevenue = $this->orderRepository->getRevenueInDateRange(now()->subWeeks(2), now()->subWeek());

        return $previousWeekRevenue > 0
            ? (($lastWeekRevenue - $previousWeekRevenue) / $previousWeekRevenue) * 100
            : 0;
    }

    /**
     * Calculate week-over-week customer change percentage
     */
    private function calculateCustomerChange(): float
    {
        $lastWeekCustomers = $this->userRepository->countByRoleInDateRange('user', now()->subWeek(), now());
        $previousWeekCustomers = $this->userRepository->countByRoleInDateRange('user', now()->subWeeks(2), now()->subWeek());

        return $previousWeekCustomers > 0
            ? (($lastWeekCustomers - $previousWeekCustomers) / $previousWeekCustomers) * 100
            : 0;
    }
}
