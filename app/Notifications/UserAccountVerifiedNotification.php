<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserAccountVerifiedNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly string $email,
        private readonly string $accountPassword
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your account has been verified')
            ->greeting('Hello ' . ($notifiable->display_name ?: 'User') . ',')
            ->line('Your account has been verified successfully.')
            ->line('Your login credentials are:')
            ->line('Email: ' . $this->email)
            ->line('Password: ' . $this->accountPassword)
            ->line('For security, you will be asked to change this password after your first login.')
            ->action('Go to Login', route('login'));
    }
}

