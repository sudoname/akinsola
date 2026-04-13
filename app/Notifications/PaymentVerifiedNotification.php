<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentVerifiedNotification extends Notification implements ShouldQueue
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
            ->subject('Requirements Verified - Payment Ready to Send')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Great news! All requirements have been verified and your payment is ready to be sent.')
            ->line('**Amount:** ₦' . number_format($this->application->scholarship_amount, 2))
            ->line('**Scholarship:** ' . $this->application->cycle->title)
            ->line('Your bank account information has been verified:')
            ->line('- Bank: ' . $this->application->bank_name)
            ->line('- Account Number: ' . $this->application->bank_account_number)
            ->line('Your scholarship payment will be processed within the next few business days.')
            ->action('View Your Application', route('applications.show', $this->application))
            ->line('You will receive a confirmation email once the payment has been sent to your bank account.')
            ->line('Thank you for your patience!');
    }
}
