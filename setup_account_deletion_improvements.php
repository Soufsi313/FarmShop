<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔧 Amélioration du système de suppression de compte\n\n";

echo "1. Création de la notification de confirmation de suppression par email...\n";

// 1. Créer la notification personnalisée pour confirmation de suppression
$confirmDeletionNotificationContent = '<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

class ConfirmAccountDeletionNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification\'s delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [\'mail\'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);
        
        return (new MailMessage)
            ->subject(\'🚨 Confirmer la suppression de votre compte FarmShop\')
            ->view(\'emails.account.confirm-deletion\', [
                \'user\' => $notifiable,
                \'url\' => $verificationUrl,
                \'expiration\' => 30
            ]);
    }

    /**
     * Generate the verification URL for account deletion.
     */
    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            \'users.confirm-deletion\',
            Carbon::now()->addMinutes(30),
            [
                \'id\' => $notifiable->getKey(),
                \'hash\' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}';

file_put_contents('app/Notifications/ConfirmAccountDeletionNotification.php', $confirmDeletionNotificationContent);
echo "   ✅ Notification créée : app/Notifications/ConfirmAccountDeletionNotification.php\n";

echo "2. Création du template email de confirmation...\n";

// 2. Créer le template email de confirmation
$confirmDeletionEmailContent = '<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Confirmation de suppression de compte</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #dc3545;
        }
        .header h1 {
            color: #dc3545;
            margin: 0;
            font-size: 28px;
        }
        .header p {
            color: #666;
            margin: 10px 0 0 0;
        }
        .content {
            margin-bottom: 30px;
        }
        .content p {
            margin-bottom: 15px;
        }
        .warning-box {
            background-color: #fff3cd;
            border: 2px solid #ffc107;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }
        .warning-box h3 {
            color: #856404;
            margin-top: 0;
            font-size: 18px;
        }
        .btn {
            display: inline-block;
            padding: 15px 30px;
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            margin: 20px 0;
            font-weight: bold;
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
            transition: all 0.3s ease;
        }
        .btn:hover {
            background: linear-gradient(135deg, #c82333 0%, #a71e2a 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4);
        }
        .btn-cancel {
            display: inline-block;
            padding: 12px 25px;
            background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            margin: 10px 10px 10px 0;
            font-weight: bold;
            box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
        }
        .action-buttons {
            text-align: center;
            margin: 30px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #666;
        }
        .expiration-notice {
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
            text-align: center;
        }
        .data-info {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚨 Confirmation requise</h1>
            <p>Demande de suppression de votre compte FarmShop</p>
        </div>
        
        <div class="content">
            <p>Bonjour <strong>{{ $user->name }}</strong>,</p>
            
            <p>Nous avons reçu une demande de suppression pour votre compte FarmShop associé à l\'adresse email <strong>{{ $user->email }}</strong>.</p>
            
            <div class="warning-box">
                <h3>⚠️ ATTENTION - Action Irréversible</h3>
                <p>La suppression de votre compte entraînera la perte définitive de :</p>
                <ul style="text-align: left; max-width: 300px; margin: 0 auto;">
                    <li>Toutes vos données personnelles</li>
                    <li>Votre historique de commandes</li>
                    <li>Vos favoris et listes de souhaits</li>
                    <li>Tous vos messages</li>
                    <li>Votre abonnement newsletter</li>
                </ul>
            </div>
            
            <div class="data-info">
                <h4>📦 Téléchargement automatique de vos données (GDPR)</h4>
                <p>Conformément au RGPD, si vous confirmez la suppression, un fichier ZIP contenant toutes vos données sera automatiquement téléchargé et vous sera envoyé par email :</p>
                <ul>
                    <li>📄 Données personnelles au format PDF</li>
                    <li>📊 Historique de navigation par section</li>
                    <li>🛒 Historique des commandes</li>
                    <li>💌 Correspondances et messages</li>
                </ul>
            </div>
            
            <p><strong>Si vous souhaitez vraiment supprimer votre compte</strong>, cliquez sur le bouton rouge ci-dessous.</p>
            <p><strong>Si ce n\'était pas votre intention</strong>, ignorez simplement cet email.</p>
            
            <div class="action-buttons">
                <a href="{{ $url }}" class="btn">
                    🗑️ CONFIRMER LA SUPPRESSION
                </a>
                <br>
                <a href="{{ config(\'app.url\') }}" class="btn-cancel">
                    ↩️ Annuler et retourner sur FarmShop
                </a>
            </div>
            
            <div class="expiration-notice">
                <p><strong>⏰ Ce lien expire dans {{ $expiration }} minutes</strong></p>
                <p>Si le lien expire, vous devrez refaire une demande de suppression depuis votre profil.</p>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>FarmShop</strong> - Votre marketplace agricole de confiance</p>
            <p>Cet email a été envoyé automatiquement suite à une demande de suppression de compte.</p>
            <p>Si vous n\'êtes pas à l\'origine de cette demande, contactez-nous immédiatement.</p>
        </div>
    </div>
</body>
</html>';

// S\'assurer que le dossier existe
if (!file_exists('resources/views/emails/account')) {
    mkdir('resources/views/emails/account', 0755, true);
}

file_put_contents('resources/views/emails/account/confirm-deletion.blade.php', $confirmDeletionEmailContent);
echo "   ✅ Template email créé : resources/views/emails/account/confirm-deletion.blade.php\n";

echo "3. Création de la page de confirmation de suppression...\n";

// 3. Créer la page de confirmation de suppression
$deletionSuccessPageContent = '@extends(\'layouts.app\')

@section(\'title\', \'Compte supprimé - FarmShop\')
@section(\'page-title\', \'Suppression confirmée\')

@section(\'content\')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-red-50 to-orange-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <!-- Animation de suppression -->
            <div class="mx-auto h-32 w-32 bg-red-100 rounded-full flex items-center justify-center mb-8 animate-pulse">
                <svg class="h-16 w-16 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            
            <h1 class="text-3xl font-bold text-gray-900 mb-4">
                🗑️ Compte supprimé avec succès
            </h1>
            
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">✅ Suppression confirmée</h2>
                
                <div class="text-left space-y-3">
                    <p class="text-gray-600">
                        <span class="font-semibold">📧 Email de confirmation :</span> 
                        Un email récapitulatif vous a été envoyé
                    </p>
                    
                    <p class="text-gray-600">
                        <span class="font-semibold">📦 Données GDPR :</span> 
                        Téléchargement automatique en cours...
                    </p>
                    
                    <p class="text-gray-600">
                        <span class="font-semibold">🕐 Date de suppression :</span> 
                        {{ now()->format(\'d/m/Y à H:i:s\') }}
                    </p>
                </div>
            </div>
            
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <h3 class="text-lg font-semibold text-yellow-800 mb-2">📋 Récapitulatif</h3>
                <ul class="text-sm text-yellow-700 text-left space-y-1">
                    <li>✅ Données personnelles supprimées</li>
                    <li>✅ Historique des commandes effacé</li>
                    <li>✅ Messages et communications supprimés</li>
                    <li>✅ Abonnement newsletter annulé</li>
                    <li>✅ Archive GDPR générée et envoyée</li>
                </ul>
            </div>
            
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <h3 class="text-lg font-semibold text-blue-800 mb-2">💾 Sauvegarde GDPR</h3>
                <p class="text-sm text-blue-700">
                    Conformément au RGPD, toutes vos données ont été compilées dans un fichier ZIP qui sera téléchargé automatiquement. 
                    Ce fichier contient vos informations personnelles, historique de navigation et données de commandes au format PDF.
                </p>
            </div>
            
            <div class="space-y-4">
                <a href="{{ route(\'home\') }}" 
                   class="w-full bg-gradient-to-r from-green-600 to-green-700 text-white py-3 px-6 rounded-lg font-semibold hover:from-green-700 hover:to-green-800 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 inline-block">
                    🏠 Retourner à l\'accueil
                </a>
                
                <a href="{{ route(\'register\') }}" 
                   class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white py-3 px-6 rounded-lg font-semibold hover:from-blue-700 hover:to-blue-800 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 inline-block">
                    👤 Créer un nouveau compte
                </a>
            </div>
            
            <div class="mt-8 text-sm text-gray-500">
                <p>Merci d\'avoir utilisé FarmShop</p>
                <p>Nous espérons vous revoir bientôt ! 🌱</p>
            </div>
        </div>
    </div>
</div>

<script>
// Déclencher le téléchargement automatique après 2 secondes
setTimeout(function() {
    window.location.href = "{{ route(\'users.download-data\') }}";
}, 2000);
</script>
@endsection';

file_put_contents('resources/views/users/deletion-success.blade.php', $deletionSuccessPageContent);
echo "   ✅ Page de confirmation créée : resources/views/users/deletion-success.blade.php\n";

echo "4. Mise à jour du contrôleur UserController...\n";

echo "🎯 Configuration terminée ! Il reste à appliquer les modifications sur les fichiers existants.\n\n";

echo "📋 Résumé des améliorations à appliquer :\n";
echo "   1. ✅ Notification email de confirmation créée\n";
echo "   2. ✅ Template email de confirmation créé\n";
echo "   3. ✅ Page de confirmation de suppression créée\n";
echo "   4. 🔄 Modifications du UserController nécessaires\n";
echo "   5. 🔄 Ajout des routes de confirmation\n";
echo "   6. 🔄 Interface admin pour restaurer les comptes\n";
echo "   7. 🔄 Téléchargement automatique GDPR amélioré\n\n";

echo "🚀 Prêt pour les modifications des fichiers existants !\n";
?>
