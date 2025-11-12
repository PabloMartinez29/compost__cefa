<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomResetPassword extends Notification
{

    /** @var string */
    protected $resetUrl;

    public function __construct(string $resetUrl)
    {
        $this->resetUrl = $resetUrl;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Restablecimiento de contraseña - COMPOST CEFA')
            ->view('emails.reset-password', [
                'resetUrl' => $this->resetUrl,
                'appName' => config('app.name', 'COMPOST CEFA'),
            ]);
    }
}


