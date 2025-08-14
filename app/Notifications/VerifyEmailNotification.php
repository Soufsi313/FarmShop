<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class VerifyEmailNotification extends VerifyEmail
{
    // Retire Queueable pour un envoi synchrone

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Vérification de votre adresse email - FarmShop')
            ->greeting('Bonjour ' . ($notifiable->name ?: $notifiable->username) . ' !')
            ->line('Merci de vous être inscrit sur FarmShop. Pour activer votre compte, veuillez cliquer sur le bouton ci-dessous pour vérifier votre adresse email.')
            ->action('Vérifier mon email', $verificationUrl)
            ->line('Ce lien de vérification expirera dans 60 minutes.')
            ->line('Si vous n\'avez pas créé de compte, aucune action n\'est requise de votre part.')
            ->salutation('Cordialement, L\'équipe FarmShop');
    }

    /**
     * Get the verification URL for the given notifiable.
     */
    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }
}
