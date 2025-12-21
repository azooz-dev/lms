<?php

declare(strict_types=1);

namespace App\Services;

use App\Events\OrderConfirmed;
use App\Models\Order;
use App\Models\Payment;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class OrderService
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly PaymentRepositoryInterface $paymentRepository
    ) {}

    /**
     * Get pending payments
     */
    public function getPendingPayments(): Collection
    {
        return $this->paymentRepository->getPendingPayments();
    }

    /**
     * Get confirmed payments
     */
    public function getConfirmedPayments(): Collection
    {
        return $this->paymentRepository->getConfirmedPayments();
    }

    /**
     * Get a payment by ID
     */
    public function getPayment(int $id): ?Payment
    {
        return $this->paymentRepository->find($id);
    }

    /**
     * Confirm an order by updating payment status and dispatching event
     */
    public function confirmOrder(Payment $payment): void
    {
        $this->paymentRepository->confirm($payment);

        // Dispatch event to handle notifications
        OrderConfirmed::dispatch($payment);
    }

    /**
     * Get aggregated orders for an instructor
     * Groups orders by payment_id and returns the latest order for each group
     */
    public function getInstructorOrders(int $instructorId): Collection
    {
        $orders = $this->orderRepository->getByInstructorId($instructorId);

        return $orders->groupBy('payment_id')
            ->map(function ($group) {
                return $group->sortByDesc('created_at')->first();
            });
    }

    /**
     * Get user's courses (visible orders)
     * Groups orders by course_id and returns the latest order for each group
     */
    public function getUserCourses(int $userId): Collection
    {
        $orders = $this->orderRepository->getVisibleByUserId($userId);

        return $orders->groupBy('course_id')
            ->map(function ($group) {
                return $group->sortByDesc('created_at')->first();
            });
    }

    /**
     * Hide an order from user (soft delete)
     */
    public function hideOrderFromUser(Order $order): void
    {
        $this->orderRepository->hideFromUser($order);
    }

    /**
     * Generate invoice PDF for a payment
     */
    public function generateInvoicePdf(Payment $payment): \Barryvdh\DomPDF\PDF
    {
        // Ensure course images are available for PDF
        $this->prepareCourseImagesForPdf($payment);

        return Pdf::loadView('instructor.orders.invoice_order', compact('payment'))
            ->setPaper('a4')
            ->setOption([
                'tempDir' => public_path(),
                'chroot' => public_path(),
            ]);
    }

    /**
     * Get unread notifications for a user with formatted dates
     */
    public function getFormattedNotifications($user): array
    {
        $notifications = $user->unreadNotifications;

        foreach ($notifications as $notification) {
            $notification->created_date = Carbon::parse($notification->created_at)->diffForHumans();
        }

        return [
            'notifications' => $notifications,
            'count' => $notifications->count(),
        ];
    }

    /**
     * Mark a notification as read
     */
    public function markNotificationAsRead($user, string $notificationId): void
    {
        $notification = $user->notifications->where('id', $notificationId)->first();

        if ($notification) {
            $notification->markAsRead();
        }
    }

    /**
     * Copy course images to public path for PDF generation
     */
    private function prepareCourseImagesForPdf(Payment $payment): void
    {
        // Ensure the destination directory exists
        $destDir = public_path('course/images');
        if (! is_dir($destDir)) {
            mkdir($destDir, 0755, true);
        }

        foreach ($payment->orders as $order) {
            if (! $order->course || empty($order->course->image)) {
                continue;
            }

            $destinationPath = public_path('course/images/'.$order->course->image);
            $sourcePath = Storage::disk('public')->path('upload/course/images/'.$order->course->image);

            if (! file_exists($destinationPath) && file_exists($sourcePath)) {
                copy($sourcePath, $destinationPath);
            }
        }
    }
}
