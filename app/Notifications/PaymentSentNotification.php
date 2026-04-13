<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentSentNotification extends Notification implements ShouldQueue
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
            ->subject('Payment Sent - ' . $this->application->cycle->title)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Excellent news! Your scholarship payment has been sent to your bank account.')
            ->line('**Amount:** ₦' . number_format($this->application->scholarship_amount, 2))
            ->line('**Bank:** ' . $this->application->bank_name)
            ->line('**Account Number:** ' . $this->application->bank_account_number)
            ->line('**Payment Sent:** ' . $this->application->payment_sent_at->format('F d, Y'))
            ->line('Please allow 1-3 business days for the funds to appear in your account, depending on your bank.')
            ->action('Confirm Receipt When Received', route('applications.show', $this->application))
            ->line('Once you receive the payment in your account, please confirm receipt by clicking the button in your dashboard.')
            ->line('Congratulations on your scholarship award!')
            ->line('Thank you for being part of our scholarship program!');
    }
}
