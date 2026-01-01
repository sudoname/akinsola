<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationDecision extends Notification implements ShouldQueue
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
        $status = $this->application->status;

        $message = (new MailMessage)
            ->greeting('Hello ' . $notifiable->name . '!');

        // Customize message based on decision
        if ($status === 'approved') {
            $message->subject('Congratulations! Scholarship Application Approved')
                ->line('🎉 We are pleased to inform you that your scholarship application has been **approved**!')
                ->line('**Application Details:**')
                ->line('- Cycle: ' . $cycle->title)
                ->line('- Track: ' . $track)
                ->line('You have been selected as a recipient of the Isan-Ekiti Indigene Scholarship.')
                ->line('**Next Steps:**')
                ->line('1. Log in to your dashboard to view complete details')
                ->line('2. Follow the instructions sent to your email for next steps')
                ->line('3. Contact us if you have any questions')
                ->action('View Application', route('applications.show', $this->application))
                ->line('Congratulations once again, and we look forward to supporting your educational journey!');
        } elseif ($status === 'rejected') {
            $message->subject('Scholarship Application Update - ' . $cycle->title)
                ->line('Thank you for your interest in the Isan-Ekiti Indigene Scholarship Program.')
                ->line('After careful review and consideration, we regret to inform you that your application for the ' . $cycle->title . ' (' . $track . ' Track) was not selected for this cycle.')
                ->line('**Application Details:**')
                ->line('- Cycle: ' . $cycle->title)
                ->line('- Track: ' . $track)
                ->line('We received a large number of qualified applications, and the selection process was highly competitive.')
                ->line('We encourage you to:')
                ->line('- Apply again in future scholarship cycles')
                ->line('- Continue pursuing your educational goals')
                ->line('- Explore other scholarship opportunities')
                ->action('View Application', route('applications.show', $this->application))
                ->line('We appreciate your interest and wish you success in your academic pursuits.');
        } else { // waitlisted
            $message->subject('Scholarship Application Update - Waitlisted')
                ->line('Thank you for applying to the Isan-Ekiti Indigene Scholarship Program.')
                ->line('Your application for the ' . $cycle->title . ' (' . $track . ' Track) has been placed on the **waitlist**.')
                ->line('**Application Details:**')
                ->line('- Cycle: ' . $cycle->title)
                ->line('- Track: ' . $track)
                ->line('Being waitlisted means:')
                ->line('- Your application was competitive and met our criteria')
                ->line('- You may be offered a scholarship if spots become available')
                ->line('- You will be notified immediately if your status changes')
                ->line('No action is required from you at this time. We will contact you if a scholarship becomes available.')
                ->action('View Application', route('applications.show', $this->application))
                ->line('Thank you for your patience and continued interest in our program.');
        }

        return $message;
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
            'status' => $this->application->status,
        ];
    }
}
