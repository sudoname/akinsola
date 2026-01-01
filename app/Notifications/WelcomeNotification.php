<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
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
        return (new MailMessage)
                    ->subject('Welcome to Isan-Ekiti Scholarship Portal')
                    ->greeting('Welcome, ' . $notifiable->name . '!')
                    ->line('Thank you for registering with the Isan-Ekiti Indigene Scholarship Portal.')
                    ->line('We are excited to have you join our community of scholars and support your educational journey.')
                    ->line('**Getting Started:**')
                    ->line('1. **Complete Your Profile** - Add your personal information and upload your indigene certificate')
                    ->line('2. **Browse Active Cycles** - Check out current scholarship opportunities')
                    ->line('3. **Submit Your Application** - Apply for the track that matches your education level')
                    ->action('Complete Your Profile', route('applicant.profile'))
                    ->line('**Available Tracks:**')
                    ->line('- Secondary School (JSS1 - SS3)')
                    ->line('- University (Undergraduate)')
                    ->line('- Polytechnic (ND & HND)')
                    ->line('If you have any questions, feel free to reach out to us at info@khan.ng or WhatsApp: +234 816 816 6109')
                    ->line('We look forward to supporting your academic excellence!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'user_id' => $notifiable->id,
            'name' => $notifiable->name,
        ];
    }
}
