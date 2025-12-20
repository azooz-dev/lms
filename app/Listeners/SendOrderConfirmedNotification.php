<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\OrderConfirmed;
use App\Mail\OrderConfirm;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendOrderConfirmedNotification implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(OrderConfirmed $event): void
    {
        // Send confirmation to customer that order is confirmed
        Mail::to($event->payment->email)->queue(new OrderConfirm($event->payment));
    }
}

