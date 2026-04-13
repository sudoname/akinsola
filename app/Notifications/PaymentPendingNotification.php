<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentPendingNotification extends Notification implements ShouldQueue
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
            ->subject('Payment Processing Started - ' . $this->application->cycle->title)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Congratulations! We are now processing your scholarship payment.')
            ->line('**Amount:** ₦' . number_format($this->application->scholarship_amount, 2))
            ->line('**Scholarship:** ' . $this->application->cycle->title)
            ->line('**Track:** ' . ucfirst($this->application->track))
            ->line('We are currently verifying your bank account information and preparing the payment.')
            ->line('Please ensure your bank account details are correct:')
            ->line('- Account Name: ' . ($this->application->bank_account_name ?: 'Not provided'))
            ->line('- Bank: ' . ($this->application->bank_name ?: 'Not provided'))
            ->line('- Account Number: ' . ($this->application->bank_account_number ?: 'Not provided'))
            ->action('View Your Application', route('applications.show', $this->application))
            ->line('If your bank details are incorrect, please update them immediately in your dashboard.')
            ->line('You will receive another notification once the payment has been sent.')
            ->line('Thank you for your patience!');
    }
}
