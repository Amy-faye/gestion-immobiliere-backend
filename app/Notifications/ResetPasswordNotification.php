<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = 'http://localhost:4200/reinitialiser-mot-de-passe?token=' . $this->token
            . '&email=' . urlencode($notifiable->getEmailForPasswordReset());

        return (new MailMessage)
            ->subject('Réinitialisation de votre mot de passe — Holding Baobab SA')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Vous avez demandé la réinitialisation de votre mot de passe sur Baobab Immo.')
            ->action('Réinitialiser mon mot de passe', $url)
            ->line('Ce lien expirera dans 60 minutes.')
            ->line("Si vous n'êtes pas à l'origine de cette demande, vous pouvez ignorer cet email.");
    }
}
