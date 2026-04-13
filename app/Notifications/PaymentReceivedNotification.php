<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentReceivedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Application $application
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Payment Receipt Confirmed - ' . $this->application->user->name)
            ->greeting('Payment Confirmation')
            ->line('The scholarship recipient has confirmed receipt of payment:')
            ->line('**Recipient:** ' . $this->application->user->name)
            ->line('**Email:** ' . $this->application->user->email)
            ->line('**Amount:** ₦' . number_format($this->application->scholarship_amount, 2))
            ->line('**Scholarship:** ' . $this->application->cycle->title)
            ->line('**Confirmed On:** ' . $this->application->payment_received_at->format('F d, Y'))
            ->action('View Application', url('/admin/applications/' . $this->application->id))
            ->line('This completes the payment cycle for this application.');
    }
}
