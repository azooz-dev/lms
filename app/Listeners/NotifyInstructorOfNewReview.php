<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\ReviewSubmitted;
use App\Notifications\NewReviewReceived;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyInstructorOfNewReview implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(ReviewSubmitted $event): void
    {
        $instructor = $event->review->course->instructor ?? null;
        $instructor?->notify(new NewReviewReceived($event->review));
    }
}
