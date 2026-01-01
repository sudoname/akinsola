<?php

namespace App\Notifications;

use App\Models\Cycle;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CyclePublished extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Cycle $cycle
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
        $tracks = collect($this->cycle->tracks_json)->map(function ($track) {
            return match ($track) {
                'secondary' => 'Secondary School',
                'university' => 'University',
                'polytechnic' => 'Polytechnic',
                default => ucfirst($track),
            };
        })->join(', ');

        return (new MailMessage)
                    ->subject('New Scholarship Opportunity - ' . $this->cycle->title)
                    ->greeting('Hello ' . $notifiable->name . '!')
                    ->line('We are excited to announce a new scholarship cycle is now open for applications!')
                    ->line('**Scholarship Cycle Details:**')
                    ->line('📚 **' . $this->cycle->title . '**')
                    ->line('📝 **Description:** ' . ($this->cycle->description ?? 'Apply now for this scholarship opportunity.'))
                    ->line('')
                    ->line('**Available Tracks:** ' . $tracks)
                    ->line('**Application Opens:** ' . $this->cycle->start_at->format('F d, Y g:i A'))
                    ->line('**Deadline:** ' . $this->cycle->deadline_at->format('F d, Y g:i A'))
                    ->line('**Results Release:** ' . $this->cycle->results_release_at->format('F d, Y'))
                    ->line('')
                    ->line('This is your opportunity to receive financial support for your education. Don\'t miss this chance!')
                    ->action('Apply Now', route('dashboard'))
                    ->line('**Application Requirements:**')
                    ->line('• Complete your profile with all required information')
                    ->line('• Upload your scholarship essay (PDF format)')
                    ->line('• Provide necessary supporting documents')
                    ->line('• Submit your application before the deadline')
                    ->line('')
                    ->line('**Important Notes:**')
                    ->line('⏰ Applications must be submitted before the deadline')
                    ->line('📋 Ensure your profile is complete before applying')
                    ->line('📧 You will receive a confirmation email once your application is submitted')
                    ->line('🎓 Results will be communicated via email after the results release date')
                    ->line('')
                    ->line('For more information about eligibility requirements, please visit our website.')
                    ->action('View Eligibility', route('eligibility'))
                    ->line('Thank you for being a part of the Isan-Ekiti Indigene Scholarship Program. We look forward to receiving your application!')
                    ->salutation('Best regards, The Isan-Ekiti Scholarship Team');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'cycle_id' => $this->cycle->id,
            'cycle_title' => $this->cycle->title,
            'deadline_at' => $this->cycle->deadline_at,
            'tracks' => $this->cycle->tracks_json,
        ];
    }
}
