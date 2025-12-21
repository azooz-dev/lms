<?php

declare(strict_types=1);

namespace App\Http\Controllers\backend;

use App\Helpers\FlashNotification;
use App\Http\Controllers\Controller;
use App\Services\CourseService;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderService $orderService,
        private readonly CourseService $courseService
    ) {}

    public function pending_order(): View
    {
        $payments = $this->orderService->getPendingPayments();

        return view('admin.backend.order.pending_orders', compact('payments'));
    }

    public function order_details(string $id): View
    {
        $payment = $this->orderService->getPayment((int) $id);

        return view('admin.backend.order.order_details', compact('payment'));
    }

    /**
     * Update order status to confirm
     */
    public function update_order_status(string $id): RedirectResponse
    {
        $payment = $this->orderService->getPayment((int) $id);
        $this->orderService->confirmOrder($payment);

        return redirect()
            ->route('admin.confirm_order')
            ->with(FlashNotification::success('Order confirmed successfully.'));
    }

    public function confirm_order(): View
    {
        $payments = $this->orderService->getConfirmedPayments();

        return view('admin.backend.order.confirm_orders', compact('payments'));
    }

    public function all_instructor_order(string $id): View
    {
        $aggregatedOrders = $this->orderService->getInstructorOrders((int) $id);

        return view('instructor.orders.all_orders', compact('aggregatedOrders'));
    }

    public function instructor_order_details(string $id): View
    {
        $payment = $this->orderService->getPayment((int) $id);

        return view('instructor.orders.order_details', compact('payment'));
    }

    /**
     * Download the invoice for the given payment
     */
    public function instructor_invoice_download(string $id): Response
    {
        $payment = $this->orderService->getPayment((int) $id);
        $pdf = $this->orderService->generateInvoicePdf($payment);

        return $pdf->download('invoice.pdf');
    }

    public function my_courses(string $id): View
    {
        $aggregatedOrders = $this->orderService->getUserCourses((int) $id);

        return view('frontend.dashboard.my_course.courses', compact('aggregatedOrders'));
    }

    public function my_course_details(string $id): View
    {
        $course = $this->courseService->findById((int) $id);

        return view('frontend.course.course_view', compact('course'));
    }

    public function mark_notification_read(string $id): JsonResponse
    {
        $user = Auth::user();
        $this->orderService->markNotificationAsRead($user, $id);
        $notificationData = $this->orderService->getFormattedNotifications($user);

        return response()->json($notificationData);
    }

    public function delete_my_course(string $id): RedirectResponse
    {
        $order = $this->orderService->findOrderById((int) $id);
        $this->orderService->hideOrderFromUser($order);

        return redirect()->back();
    }
}
