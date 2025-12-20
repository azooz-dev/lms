<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\InstructorRegistered;
use App\Mail\InstructorWelcome;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendInstructorWelcomeEmail implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(InstructorRegistered $event): void
    {
        Mail::to($event->instructor->email)->queue(new InstructorWelcome($event->instructor));
    }
}

