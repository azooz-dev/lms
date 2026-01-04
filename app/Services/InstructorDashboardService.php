<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Coupon;
use App\Models\Course;
use App\Models\Course_Lecture;
use App\Models\Order;
use App\Models\Question;
use App\Models\Review;
use App\Models\Wish_list;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class InstructorDashboardService
{
    /**
     * Get all dashboard statistics for the instructor
     * These are instructor-specific metrics different from admin dashboard
     */
    public function getStatistics(int $instructorId): array
    {
        return [
            // Revenue & Sales
            'totalRevenue' => $this->getTotalRevenue($instructorId),
            'thisMonthEarnings' => $this->getThisMonthEarnings($instructorId),
            'totalOrders' => $this->getTotalOrders($instructorId),
            'completedOrders' => $this->getCompletedOrders($instructorId),
            
            // Student Engagement
            'totalStudents' => $this->getTotalStudents($instructorId),
            'unansweredQuestions' => $this->getUnansweredQuestions($instructorId),
            'totalQuestions' => $this->getTotalQuestions($instructorId),
            'questionResponseRate' => $this->getQuestionResponseRate($instructorId),
            
            // Course Performance
            'totalCourses' => $this->getTotalCourses($instructorId),
            'publishedCourses' => $this->getPublishedCourses($instructorId),
            'draftCourses' => $this->getDraftCourses($instructorId),
            'totalLectures' => $this->getTotalLectures($instructorId),
            
            // Reviews & Ratings
            'totalReviews' => $this->getTotalReviews($instructorId),
            'averageRating' => $this->getAverageRating($instructorId),
            'ratingDistribution' => $this->getRatingDistribution($instructorId),
            
            // Interest & Marketing
            'wishlistCount' => $this->getWishlistCount($instructorId),
            'activeCoupons' => $this->getActiveCoupons($instructorId),
        ];
    }

    /**
     * Get weekly percentage changes for key metrics
     */
    public function getPercentageChanges(int $instructorId): array
    {
        return [
            'revenueChange' => $this->calculateRevenueChange($instructorId),
            'orderChange' => $this->calculateOrderChange($instructorId),
            'studentChange' => $this->calculateStudentChange($instructorId),
            'reviewChange' => $this->calculateReviewChange($instructorId),
        ];
    }

    /**
     * Get monthly sales data for the instructor
     */
    public function getMonthlySales(int $instructorId, ?int $year = null): Collection
    {
        $year = $year ?? (int) date('Y');

        return Order::where('instructor_id', $instructorId)
            ->join('payments', 'orders.payment_id', '=', 'payments.id')
            ->where('payments.status', 'completed')
            ->whereYear('orders.created_at', $year)
            ->selectRaw('MONTH(orders.created_at) as month, SUM(orders.course_price) as total_sales, COUNT(*) as order_count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }

    /**
     * Get top performing courses by revenue
     */
    public function getTopCourses(int $instructorId, int $limit = 5): Collection
    {
        return Order::where('orders.instructor_id', $instructorId)
            ->join('payments', 'orders.payment_id', '=', 'payments.id')
            ->join('courses', 'orders.course_id', '=', 'courses.id')
            ->where('payments.status', 'completed')
            ->selectRaw('
                courses.id,
                courses.title,
                courses.image,
                COUNT(orders.id) as total_orders,
                SUM(orders.course_price) as total_revenue
            ')
            ->groupBy('courses.id', 'courses.title', 'courses.image')
            ->orderByDesc('total_revenue')
            ->limit($limit)
            ->get();
    }

    /**
     * Get recent orders for the instructor with relationships
     */
    public function getRecentOrders(int $instructorId, int $limit = 5): Collection
    {
        return Order::where('instructor_id', $instructorId)
            ->with(['course', 'user', 'payment'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get recent reviews for the instructor
     */
    public function getRecentReviews(int $instructorId, int $limit = 5): Collection
    {
        return Review::where('instructor_id', $instructorId)
            ->where('status', '1')
            ->with(['user', 'course'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get pending questions that need responses
     */
    public function getPendingQuestions(int $instructorId, int $limit = 5): Collection
    {
        return Question::where('instructor_id', $instructorId)
            ->whereDoesntHave('replies')
            ->with(['user', 'course'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get chart data for AJAX requests
     */
    public function getChartData(int $instructorId): array
    {
        return [
            'monthlySales' => $this->getMonthlySales($instructorId),
            'ratingDistribution' => $this->getRatingDistribution($instructorId),
        ];
    }

    // ===== Revenue & Sales Metrics =====

    private function getTotalRevenue(int $instructorId): float
    {
        return (float) Order::where('instructor_id', $instructorId)
            ->join('payments', 'orders.payment_id', '=', 'payments.id')
            ->where('payments.status', 'completed')
            ->sum('orders.course_price');
    }

    private function getThisMonthEarnings(int $instructorId): float
    {
        return (float) Order::where('instructor_id', $instructorId)
            ->join('payments', 'orders.payment_id', '=', 'payments.id')
            ->where('payments.status', 'completed')
            ->whereMonth('orders.created_at', now()->month)
            ->whereYear('orders.created_at', now()->year)
            ->sum('orders.course_price');
    }

    private function getTotalOrders(int $instructorId): int
    {
        return Order::where('instructor_id', $instructorId)->count();
    }

    private function getCompletedOrders(int $instructorId): int
    {
        return Order::where('instructor_id', $instructorId)
            ->join('payments', 'orders.payment_id', '=', 'payments.id')
            ->where('payments.status', 'completed')
            ->count();
    }

    // ===== Student Engagement Metrics =====

    private function getTotalStudents(int $instructorId): int
    {
        return Order::where('instructor_id', $instructorId)
            ->distinct('user_id')
            ->count('user_id');
    }

    private function getTotalQuestions(int $instructorId): int
    {
        return Question::where('instructor_id', $instructorId)->count();
    }

    private function getUnansweredQuestions(int $instructorId): int
    {
        return Question::where('instructor_id', $instructorId)
            ->whereDoesntHave('replies')
            ->count();
    }

    private function getQuestionResponseRate(int $instructorId): float
    {
        $totalQuestions = $this->getTotalQuestions($instructorId);
        if ($totalQuestions === 0) {
            return 100.0;
        }

        $answeredQuestions = Question::where('instructor_id', $instructorId)
            ->whereHas('replies')
            ->count();

        return round(($answeredQuestions / $totalQuestions) * 100, 1);
    }

    // ===== Course Performance Metrics =====

    private function getTotalCourses(int $instructorId): int
    {
        return Course::where('instructor_id', $instructorId)->count();
    }

    private function getPublishedCourses(int $instructorId): int
    {
        return Course::where('instructor_id', $instructorId)
            ->where('status', '1')
            ->count();
    }

    private function getDraftCourses(int $instructorId): int
    {
        return Course::where('instructor_id', $instructorId)
            ->where('status', '0')
            ->count();
    }

    private function getTotalLectures(int $instructorId): int
    {
        return Course_Lecture::whereHas('course', function ($query) use ($instructorId) {
            $query->where('instructor_id', $instructorId);
        })->count();
    }

    // ===== Review & Rating Metrics =====

    private function getTotalReviews(int $instructorId): int
    {
        return Review::where('instructor_id', $instructorId)
            ->where('status', '1')
            ->count();
    }

    private function getAverageRating(int $instructorId): float
    {
        $avgRating = Review::where('instructor_id', $instructorId)
            ->where('status', '1')
            ->avg('rating');

        return round((float) ($avgRating ?? 0), 1);
    }

    private function getRatingDistribution(int $instructorId): array
    {
        $reviews = Review::where('instructor_id', $instructorId)
            ->where('status', '1')
            ->select(DB::raw('rating, COUNT(*) as count'))
            ->groupBy('rating')
            ->orderByDesc('rating')
            ->get();

        $totalReviews = $reviews->sum('count');
        $distribution = [];

        for ($i = 5; $i >= 1; $i--) {
            $ratingData = $reviews->where('rating', $i)->first();
            $count = $ratingData ? $ratingData->count : 0;
            $percentage = $totalReviews > 0 ? round(($count / $totalReviews) * 100) : 0;

            $distribution[] = [
                'rating' => $i,
                'count' => $count,
                'percentage' => $percentage,
            ];
        }

        return $distribution;
    }

    // ===== Interest & Marketing Metrics =====

    private function getWishlistCount(int $instructorId): int
    {
        return Wish_list::whereHas('course', function ($query) use ($instructorId) {
            $query->where('instructor_id', $instructorId);
        })->count();
    }

    private function getActiveCoupons(int $instructorId): int
    {
        return Coupon::where('instructor_id', $instructorId)
            ->where('coupon_validity', '>=', now()->toDateString())
            ->count();
    }

    // ===== Percentage Change Calculations =====

    private function calculateRevenueChange(int $instructorId): float
    {
        $lastWeekRevenue = Order::where('instructor_id', $instructorId)
            ->join('payments', 'orders.payment_id', '=', 'payments.id')
            ->where('payments.status', 'completed')
            ->whereBetween('orders.created_at', [now()->subWeek(), now()])
            ->sum('orders.course_price');

        $previousWeekRevenue = Order::where('instructor_id', $instructorId)
            ->join('payments', 'orders.payment_id', '=', 'payments.id')
            ->where('payments.status', 'completed')
            ->whereBetween('orders.created_at', [now()->subWeeks(2), now()->subWeek()])
            ->sum('orders.course_price');

        return $previousWeekRevenue > 0
            ? round((($lastWeekRevenue - $previousWeekRevenue) / $previousWeekRevenue) * 100, 1)
            : 0;
    }

    private function calculateOrderChange(int $instructorId): float
    {
        $lastWeekOrders = Order::where('instructor_id', $instructorId)
            ->whereBetween('created_at', [now()->subWeek(), now()])
            ->count();

        $previousWeekOrders = Order::where('instructor_id', $instructorId)
            ->whereBetween('created_at', [now()->subWeeks(2), now()->subWeek()])
            ->count();

        return $previousWeekOrders > 0
            ? round((($lastWeekOrders - $previousWeekOrders) / $previousWeekOrders) * 100, 1)
            : 0;
    }

    private function calculateStudentChange(int $instructorId): float
    {
        $lastWeekStudents = Order::where('instructor_id', $instructorId)
            ->whereBetween('created_at', [now()->subWeek(), now()])
            ->distinct('user_id')
            ->count('user_id');

        $previousWeekStudents = Order::where('instructor_id', $instructorId)
            ->whereBetween('created_at', [now()->subWeeks(2), now()->subWeek()])
            ->distinct('user_id')
            ->count('user_id');

        return $previousWeekStudents > 0
            ? round((($lastWeekStudents - $previousWeekStudents) / $previousWeekStudents) * 100, 1)
            : 0;
    }

    private function calculateReviewChange(int $instructorId): float
    {
        $lastWeekReviews = Review::where('instructor_id', $instructorId)
            ->where('status', '1')
            ->whereBetween('created_at', [now()->subWeek(), now()])
            ->count();

        $previousWeekReviews = Review::where('instructor_id', $instructorId)
            ->where('status', '1')
            ->whereBetween('created_at', [now()->subWeeks(2), now()->subWeek()])
            ->count();

        return $previousWeekReviews > 0
            ? round((($lastWeekReviews - $previousWeekReviews) / $previousWeekReviews) * 100, 1)
            : 0;
    }
}
