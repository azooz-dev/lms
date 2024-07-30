<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Order;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    public function pending_order() {
        $payments = Payment::where('status', 'pending')->orderBy('id', 'DESC')->get();

        return view('admin.backend.order.pending_orders', compact('payments'));
    }


    public function order_details(string $id) {
        $payment = Payment::find($id);

        return view('admin.backend.order.order_details', compact('payment'));
    }

    /**
     * Update order status to confirm
     *
     * @param string $id Payment ID
     * @return void
     */
    public function update_order_status(string $id) {
        // Update the payment status to confirm
        Payment::find($id)->update([
            'status' => 'Confirm'
        ]);

        // Create a notification to show after updating the order status
        $notification = array(
            'message' => 'Order confirm successfully.',
            'alert-type' => 'success'
        );

        // Redirect back to the previous page with the notification
        return redirect()->route('admin.confirm_order')->with($notification);
    }

    public function confirm_order() {
        $payments = Payment::where('status', 'Confirm')->orderBy('id', 'DESC')->get();

        return view('admin.backend.order.confirm_orders', compact('payments'));
    }


    public function all_instructor_order(string $id) {
        // Fetch all orders for the instructor
        $orders = Order::where('instructor_id', $id)->get();
    
        // Group orders by payment_id and select the latest order for each group
        $aggregatedOrders = $orders->groupBy(function ($order) {
            return $order->payment_id;
        })->map(function ($group) {
            // Sort the orders in the group by created_at descending
            $sortedOrders = $group->sortByDesc('created_at');

            // Select the first order from the sorted list
            return $sortedOrders->first();
        });

        // Pass the final result to the view
        return view('instructor.orders.all_orders', compact('aggregatedOrders'));
    }


    public function instructor_order_details(string $id) {

        $payment = Payment::find($id);

        return view('instructor.orders.order_details', compact('payment'));
    }



    /**
     * Download the invoice for the given payment
     *
     * @param string $id Payment ID
     * @return \Illuminate\Http\Response
     */
    public function instructor_invoice_download(string $id) {
        // Get the payment
        $payment = Payment::find($id);

        foreach($payment->orders as $order) {
            $destinationPath = public_path('course/images/'. $order->course->image);
            $sourcePath = Storage::disk('public')->path('upload/course/images/'. $order->course->image);
        
            // Check if the file does not exist in the destination directory
            if (!file_exists($destinationPath)) {
                // Ensure the source file exists
                    // Copy the file from the source to the destination directory
                    copy($sourcePath, $destinationPath);
            }
        }
        // Load the view and generate the PDF
        $pdf = Pdf::loadView('instructor.orders.invoice_order', compact('payment'))
            // Set the paper size to A4
            ->setPaper('a4')
            // Define the temporary directory and chroot for the PDF generation
            // This is needed because of a bug in the DomPDF library
            ->setOption([
                'tempDir' => public_path(),
                'chroot'  => public_path()
            ]);

        // Download the PDF
        return $pdf->download('invoice.pdf');
    }


    public function my_courses(string $id) {
        // Fetch all orders for the instructor
        $orders = Order::where('user_id', $id)->where('is_visible_to_user', '1')->get();
            
        // Group orders by course_id and select the latest order for each group
        $aggregatedOrders = $orders->groupBy(function ($order) {
            return $order->course_id;
        })->map(function ($group) {
            // Sort the orders in the group by created_at descending
            $sortedOrders = $group->sortByDesc('created_at');

            // Select the first order from the sorted list
            return $sortedOrders->first();
        });

        // Pass the final result to the view
        return view('frontend.dashboard.my_course.courses', compact('aggregatedOrders'));
    }



    public function my_course_details(string $id) {
        $course = Course::find($id);

        return view('frontend.course.course_view', compact('course'));
    }

    public function mark_notification_read(string $id) {
        $user = Auth::user();

        $readNotification = $user->notifications->where('id', $id)->first();

        if($readNotification) {
            $readNotification->markAsRead();
        }

        $notifications = $user->unreadNotifications;

        foreach($notifications as $notification) {
            $notification->created_date = \Carbon\Carbon::parse($notification->created_at)->diffForHumans();
        }
        $count = $notifications->count();

        return response()->json([
            'notifications' => $notifications,
            'count' => $count
        ]);
    }

    public function delete_my_course(string $id) {

        $order = Order::find($id);

        $order->is_visible_to_user = '0';

        $order->save();

        return redirect()->back();
    }
}
