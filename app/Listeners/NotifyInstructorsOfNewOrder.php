<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Models\User;
use App\Notifications\OrderComplate;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyInstructorsOfNewOrder implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(OrderPlaced $event): void
    {
        $uniqueInstructorIds = array_unique($event->instructorIds);

        foreach ($uniqueInstructorIds as $instructorId) {
            $instructor = User::find($instructorId);
            $instructor?->notify(new OrderComplate($event->customerName));
        }
    }
}
