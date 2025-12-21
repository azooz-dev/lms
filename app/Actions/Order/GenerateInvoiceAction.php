<?php

declare(strict_types=1);

namespace App\Actions\Order;

use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class GenerateInvoiceAction
{
    /**
     * Generate invoice PDF for a payment
     */
    public function handle(Payment $payment): \Barryvdh\DomPDF\PDF
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
