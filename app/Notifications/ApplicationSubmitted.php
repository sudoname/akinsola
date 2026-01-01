<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationSubmitted extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Application $application
    ) {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $cycle = $this->application->cycle;
        $track = ucfirst($this->application->track);

        return (new MailMessage)
                    ->subject('Application Submitted Successfully - ' . $cycle->title)
                    ->greeting('Hello ' . $notifiable->name . '!')
                    ->line('Your scholarship application has been submitted successfully.')
                    ->line('**Application Details:**')
                    ->line('- Cycle: ' . $cycle->title)
                    ->line('- Track: ' . $track)
                    ->line('- Submitted: ' . $this->application->submission_at->format('F d, Y g:i A'))
                    ->line('Your application is now under review. You will be notified once a decision has been made.')
                    ->action('View Application', route('applications.show', $this->application))
                    ->line('Results will be released on or after ' . $cycle->results_release_at->format('F d, Y') . '.')
                    ->line('Thank you for applying for the Isan-Ekiti Indigene Scholarship Program!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'application_id' => $this->application->id,
            'cycle_title' => $this->application->cycle->title,
            'track' => $this->application->track,
        ];
    }
}
