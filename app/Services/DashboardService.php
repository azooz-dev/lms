<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Course;
use App\Models\Order;
use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Collection;

class DashboardService
{
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

        return Order::join('payments', 'orders.payment_id', '=', 'payments.id')
            ->where('payments.status', 'completed')
            ->whereYear('orders.created_at', $year)
            ->selectRaw('MONTH(orders.created_at) as month, SUM(orders.course_price) as total_sales, COUNT(*) as order_count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }

    /**
     * Get daily sales data for the current month
     */
    public function getDailySales(): Collection
    {
        return Order::join('payments', 'orders.payment_id', '=', 'payments.id')
            ->where('payments.status', 'completed')
            ->whereMonth('orders.created_at', date('m'))
            ->whereYear('orders.created_at', date('Y'))
            ->selectRaw('DATE(orders.created_at) as date, SUM(orders.course_price) as total_sales, COUNT(*) as order_count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    /**
     * Get recent orders with relationships
     */
    public function getRecentOrders(int $limit = 6): Collection
    {
        return Order::with(['course', 'user', 'payment'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
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
        return Order::count();
    }

    /**
     * Get total revenue from completed orders
     */
    private function getTotalRevenue(): float
    {
        return (float) Order::join('payments', 'orders.payment_id', '=', 'payments.id')
            ->where('payments.status', 'completed')
            ->sum('orders.course_price');
    }

    /**
     * Get total customers count
     */
    private function getTotalCustomers(): int
    {
        return User::where('role', 'user')->count();
    }

    /**
     * Get total courses count
     */
    private function getTotalCourses(): int
    {
        return Course::count();
    }

    /**
     * Get total instructors count
     */
    private function getTotalInstructors(): int
    {
        return User::where('role', 'instructor')->count();
    }

    /**
     * Get pending orders count
     */
    private function getPendingOrders(): int
    {
        return Order::join('payments', 'orders.payment_id', '=', 'payments.id')
            ->where('payments.status', 'pending')
            ->count();
    }

    /**
     * Get completed orders count
     */
    private function getCompletedOrders(): int
    {
        return Order::join('payments', 'orders.payment_id', '=', 'payments.id')
            ->where('payments.status', 'completed')
            ->count();
    }

    /**
     * Get total reviews count
     */
    private function getTotalReviews(): int
    {
        return Review::count();
    }

    /**
     * Get pending reviews count
     */
    private function getPendingReviews(): int
    {
        return Review::where('status', '0')->count();
    }

    /**
     * Calculate week-over-week order change percentage
     */
    private function calculateOrderChange(): float
    {
        $lastWeekOrders = Order::whereBetween('created_at', [now()->subWeek(), now()])->count();
        $previousWeekOrders = Order::whereBetween('created_at', [now()->subWeeks(2), now()->subWeek()])->count();

        return $previousWeekOrders > 0
            ? (($lastWeekOrders - $previousWeekOrders) / $previousWeekOrders) * 100
            : 0;
    }

    /**
     * Calculate week-over-week revenue change percentage
     */
    private function calculateRevenueChange(): float
    {
        $lastWeekRevenue = Order::join('payments', 'orders.payment_id', '=', 'payments.id')
            ->where('payments.status', 'completed')
            ->whereBetween('orders.created_at', [now()->subWeek(), now()])
            ->sum('orders.course_price');

        $previousWeekRevenue = Order::join('payments', 'orders.payment_id', '=', 'payments.id')
            ->where('payments.status', 'completed')
            ->whereBetween('orders.created_at', [now()->subWeeks(2), now()->subWeek()])
            ->sum('orders.course_price');

        return $previousWeekRevenue > 0
            ? (($lastWeekRevenue - $previousWeekRevenue) / $previousWeekRevenue) * 100
            : 0;
    }

    /**
     * Calculate week-over-week customer change percentage
     */
    private function calculateCustomerChange(): float
    {
        $lastWeekCustomers = User::where('role', 'user')
            ->whereBetween('created_at', [now()->subWeek(), now()])
            ->count();

        $previousWeekCustomers = User::where('role', 'user')
            ->whereBetween('created_at', [now()->subWeeks(2), now()->subWeek()])
            ->count();

        return $previousWeekCustomers > 0
            ? (($lastWeekCustomers - $previousWeekCustomers) / $previousWeekCustomers) * 100
            : 0;
    }
}

