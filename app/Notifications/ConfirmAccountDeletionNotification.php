<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;

class ConfirmAccountDeletionNotification extends Notification
{
    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $confirmUrl = URL::temporarySignedRoute(
            'account.confirm-deletion',
            now()->addMinutes(60),
            ['user' => $notifiable->id]
        );

        return (new MailMessage)
            ->subject('Confirmation de suppression de compte - FarmShop')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Vous avez demandé la suppression de votre compte FarmShop.')
            ->line('Pour confirmer cette action, veuillez cliquer sur le bouton ci-dessous :')
            ->action('Confirmer la suppression', $confirmUrl)
            ->line('Ce lien expire dans 60 minutes.')
            ->line('Si vous n\'avez pas demandé cette suppression, ignorez cet email.')
            ->line('⚠️ Attention : Cette action est irréversible et toutes vos données seront supprimées.')
            ->salutation('Cordialement,')
            ->salutation('L\'équipe FarmShop');
    }
}