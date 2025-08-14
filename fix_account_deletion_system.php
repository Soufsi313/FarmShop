<?php

/*
 * Script pour corriger le système de suppression de compte
 * Problèmes à résoudre :
 * 1. Email de confirmation avec processus en 2 étapes
 * 2. Page de confirmation après suppression
 * 3. Route de restauration des comptes pour l'admin
 * 4. Téléchargement automatique des données GDPR en ZIP
 */

echo "=== Correction du système de suppression de compte ===\n";

// 1. Créer la notification de demande de suppression
$notificationPath = __DIR__ . '/app/Notifications/ConfirmAccountDeletionNotification.php';
$notificationDir = dirname($notificationPath);

if (!is_dir($notificationDir)) {
    mkdir($notificationDir, 0755, true);
}

$notificationContent = '<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;

class ConfirmAccountDeletionNotification extends Notification
{
    public function via($notifiable)
    {
        return [\'mail\'];
    }

    public function toMail($notifiable)
    {
        $confirmUrl = URL::temporarySignedRoute(
            \'account.confirm-deletion\',
            now()->addMinutes(60),
            [\'user\' => $notifiable->id]
        );

        return (new MailMessage)
            ->subject(\'Confirmation de suppression de compte - FarmShop\')
            ->greeting(\'Bonjour \' . $notifiable->name . \',\')
            ->line(\'Vous avez demandé la suppression de votre compte FarmShop.\')
            ->line(\'Pour confirmer cette action, veuillez cliquer sur le bouton ci-dessous :\')
            ->action(\'Confirmer la suppression\', $confirmUrl)
            ->line(\'Ce lien expire dans 60 minutes.\')
            ->line(\'Si vous n\\\'avez pas demandé cette suppression, ignorez cet email.\')
            ->line(\'⚠️ Attention : Cette action est irréversible et toutes vos données seront supprimées.\')
            ->salutation(\'Cordialement,\')
            ->salutation(\'L\\\'équipe FarmShop\');
    }
}';

file_put_contents($notificationPath, $notificationContent);
echo "✅ Notification de confirmation créée\n";

// 2. Créer la vue de confirmation de suppression
$viewPath = __DIR__ . '/resources/views/auth/account-deletion-requested.blade.php';
$viewDir = dirname($viewPath);

if (!is_dir($viewDir)) {
    mkdir($viewDir, 0755, true);
}

$viewContent = '@extends(\'layouts.app\')

@section(\'title\', \'Suppression de compte demandée\')

@section(\'content\')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">
                        <i class="fas fa-exclamation-triangle"></i>
                        Suppression de compte demandée
                    </h4>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-envelope fa-3x text-warning mb-3"></i>
                        <h5>Un email de confirmation vous a été envoyé</h5>
                    </div>
                    
                    <div class="alert alert-info">
                        <p class="mb-2">
                            <strong>Vérifiez votre boîte email</strong> {{ auth()->user()->email }}
                        </p>
                        <p class="mb-0">
                            Cliquez sur le lien de confirmation pour finaliser la suppression de votre compte.
                        </p>
                    </div>
                    
                    <div class="alert alert-warning">
                        <h6><i class="fas fa-exclamation-triangle"></i> Important :</h6>
                        <ul class="text-left mb-0">
                            <li>Le lien expire dans <strong>60 minutes</strong></li>
                            <li>Cette action est <strong>irréversible</strong></li>
                            <li>Toutes vos données seront définitivement supprimées</li>
                            <li>Vous recevrez un fichier ZIP avec vos données (GDPR)</li>
                        </ul>
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route(\'dashboard\') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Retour au tableau de bord
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection';

file_put_contents($viewPath, $viewContent);
echo "✅ Vue de demande de suppression créée\n";

// 3. Créer la vue de confirmation finale
$finalViewPath = __DIR__ . '/resources/views/auth/account-deleted-success.blade.php';

$finalViewContent = '@extends(\'layouts.app\')

@section(\'title\', \'Compte supprimé avec succès\')

@section(\'content\')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-success">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-check-circle"></i>
                        Compte supprimé avec succès
                    </h4>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-user-times fa-3x text-success mb-3"></i>
                        <h5>Votre compte a été définitivement supprimé</h5>
                    </div>
                    
                    <div class="alert alert-success">
                        <h6><i class="fas fa-download"></i> Téléchargement de vos données</h6>
                        <p class="mb-0">
                            Conformément au RGPD, un fichier ZIP contenant toutes vos données 
                            a été automatiquement téléchargé.
                        </p>
                    </div>
                    
                    <div class="alert alert-info">
                        <h6>Ce fichier contient :</h6>
                        <ul class="text-left mb-0">
                            <li>Vos informations de profil (PDF)</li>
                            <li>Historique de vos commandes (PDF)</li>
                            <li>Historique de vos locations (PDF)</li>
                            <li>Vos messages et communications (PDF)</li>
                            <li>Données de navigation et préférences (PDF)</li>
                        </ul>
                    </div>
                    
                    <div class="mt-4">
                        <p class="text-muted">
                            Merci d\'avoir utilisé FarmShop. Au revoir !
                        </p>
                        <a href="{{ route(\'home\') }}" class="btn btn-primary">
                            <i class="fas fa-home"></i> Retour à l\'accueil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-déconnexion après 5 secondes
setTimeout(function() {
    window.location.href = "{{ route(\'logout\') }}";
}, 5000);
</script>
@endsection';

file_put_contents($finalViewPath, $finalViewContent);
echo "✅ Vue de confirmation finale créée\n";

echo "\n=== Script terminé avec succès ===\n";
echo "Fichiers créés :\n";
echo "- $notificationPath\n";
echo "- $viewPath\n";
echo "- $finalViewPath\n";
echo "\nProchaines étapes :\n";
echo "1. Modifier UserController pour le processus en 2 étapes\n";
echo "2. Ajouter les routes manquantes\n";
echo "3. Implémenter le système GDPR ZIP\n";
echo "4. Ajouter la route restore dans l'admin\n";
