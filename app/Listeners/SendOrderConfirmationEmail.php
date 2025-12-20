<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Mail\OrderConfirm;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendOrderConfirmationEmail implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(OrderPlaced $event): void
    {
        Mail::to($event->customerEmail)->queue(new OrderConfirm($event->payment));
    }
}
