<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewReviewReceived extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public readonly Review $review
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Review Received')
            ->greeting('Hello '.$notifiable->name.'!')
            ->line('You have received a new review on your course: '.$this->review->course->name)
            ->line('Rating: '.$this->review->rating.' stars')
            ->line('Comment: '.$this->review->comment)
            ->action('View Course', url('/instructor/all/courses/'.$notifiable->id))
            ->line('Thank you for teaching on our platform!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'review_id' => $this->review->id,
            'course_id' => $this->review->course_id,
            'course_name' => $this->review->course->name ?? 'Course',
            'rating' => $this->review->rating,
            'message' => 'New review received on your course',
        ];
    }
}

