<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Order;
use App\Repositories\Contracts\OrderRepositoryInterface;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(new Order);
    }

    public function getTotalRevenue(): float
    {
        return (float) Order::join('payments', 'orders.payment_id', '=', 'payments.id')
            ->where('payments.status', 'completed')
            ->sum('orders.course_price');
    }

    public function getPendingOrdersCount(): int
    {
        return Order::join('payments', 'orders.payment_id', '=', 'payments.id')
            ->where('payments.status', 'pending')
            ->count();
    }

    public function getCompletedOrdersCount(): int
    {
        return Order::join('payments', 'orders.payment_id', '=', 'payments.id')
            ->where('payments.status', 'completed')
            ->count();
    }

    public function getMonthlySales(int $year): Collection
    {
        return Order::join('payments', 'orders.payment_id', '=', 'payments.id')
            ->where('payments.status', 'completed')
            ->whereYear('orders.created_at', $year)
            ->selectRaw('MONTH(orders.created_at) as month, SUM(orders.course_price) as total_sales, COUNT(*) as order_count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }

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

    public function getRecentOrders(int $limit): Collection
    {
        return Order::with(['course', 'user', 'payment'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getByInstructorId(int $instructorId): Collection
    {
        return Order::where('instructor_id', $instructorId)->get();
    }

    public function getVisibleByUserId(int $userId): Collection
    {
        return Order::where('user_id', $userId)
            ->where('is_visible_to_user', '1')
            ->get();
    }

    public function existsForUserAndCourses(array $courseIds, int $userId): bool
    {
        return Order::where(function ($query) use ($courseIds) {
            $query->whereHas('course', function ($query) use ($courseIds) {
                $query->whereIn('course_id', $courseIds);
            });
        })
            ->where('user_id', $userId)
            ->where('is_visible_to_user', '1')
            ->exists();
    }

    public function getOrdersCountInDateRange(DateTimeInterface $start, DateTimeInterface $end): int
    {
        return Order::whereBetween('created_at', [$start, $end])->count();
    }

    public function getRevenueInDateRange(DateTimeInterface $start, DateTimeInterface $end): float
    {
        return (float) Order::join('payments', 'orders.payment_id', '=', 'payments.id')
            ->where('payments.status', 'completed')
            ->whereBetween('orders.created_at', [$start, $end])
            ->sum('orders.course_price');
    }

    public function hideFromUser(Model $order): void
    {
        $order->update(['is_visible_to_user' => '0']);
    }
}
