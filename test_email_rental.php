<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\OrderLocation;
use App\Mail\RentalStartedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

echo "=== TEST D'ENVOI D'EMAIL POUR LOCATION ACTIVE ===\n\n";

try {
    // Récupérer votre commande
    $order = OrderLocation::where('order_number', 'LOC-202508034682')->first();
    
    if (!$order) {
        throw new Exception("Commande LOC-202508034682 non trouvée");
    }
    
    echo "✅ Commande trouvée: {$order->order_number}\n";
    echo "📊 Statut: {$order->status}\n";
    echo "👤 Client: {$order->user->email}\n";
    echo "📅 Démarrée le: " . ($order->started_at ? $order->started_at->format('d/m/Y H:i') : 'Non défini') . "\n\n";
    
    if ($order->status !== 'active') {
        echo "⚠️ La commande n'est pas au statut 'active'. Changement du statut...\n";
        $order->update(['status' => 'active', 'started_at' => now()]);
        echo "✅ Statut mis à jour vers 'active'\n\n";
    }
    
    // Test d'envoi d'email
    echo "📧 Test d'envoi de l'email de location démarrée...\n";
    
    try {
        // Créer et envoyer l'email
        $mail = new RentalStartedMail($order);
        
        Mail::to($order->user->email)->send($mail);
        
        echo "✅ Email envoyé avec succès à {$order->user->email}!\n";
        echo "📬 Vérifiez votre boîte email dans quelques minutes.\n\n";
        
        // Logger l'événement
        Log::info("Email de location démarrée envoyé manuellement", [
            'order_number' => $order->order_number,
            'user_email' => $order->user->email,
            'sent_at' => now()
        ]);
        
    } catch (\Exception $e) {
        echo "❌ Erreur lors de l'envoi de l'email: " . $e->getMessage() . "\n";
        echo "📝 Détails: " . $e->getTraceAsString() . "\n\n";
        
        // Essayer avec un email simple
        echo "🔄 Tentative avec un email simple...\n";
        
        try {
            Mail::raw("Votre location {$order->order_number} est maintenant active!", function ($message) use ($order) {
                $message->to($order->user->email)
                        ->subject("[FarmShop] Votre location est démarrée - {$order->order_number}");
            });
            
            echo "✅ Email simple envoyé avec succès!\n";
            
        } catch (\Exception $e2) {
            echo "❌ Erreur même avec email simple: " . $e2->getMessage() . "\n";
            
            // Vérifier la configuration mail
            echo "\n🔍 Vérification configuration email...\n";
            echo "   MAIL_MAILER: " . config('mail.default') . "\n";
            echo "   MAIL_HOST: " . config('mail.mailers.smtp.host') . "\n";
            echo "   MAIL_PORT: " . config('mail.mailers.smtp.port') . "\n";
            echo "   MAIL_USERNAME: " . config('mail.mailers.smtp.username') . "\n";
            echo "   MAIL_FROM: " . config('mail.from.address') . "\n";
        }
    }
    
    echo "\n=== DÉCLENCHEMENT MANUEL DE L'ÉVÉNEMENT ===\n";
    
    // Déclencher manuellement l'événement de changement de statut
    echo "🔄 Déclenchement de l'événement OrderLocationStatusChanged...\n";
    
    // Importer la classe d'événement
    $event = new \App\Events\OrderLocationStatusChanged($order, 'confirmed', 'active');
    
    // Déclencher l'événement
    event($event);
    
    echo "✅ Événement déclenché! L'email devrait être envoyé via le listener.\n";
    echo "📋 Vérifiez les logs avec: tail -f storage/logs/laravel.log\n\n";
    
    echo "=== RÉSUMÉ ===\n";
    echo "✅ Commande au statut 'active'\n";
    echo "✅ Email testé (vérifiez votre boîte)\n";
    echo "✅ Événement déclenché pour envoi automatique\n";
    echo "💡 Si vous ne recevez pas d'email, vérifiez vos spams!\n";

} catch (\Exception $e) {
    echo "❌ Erreur générale: " . $e->getMessage() . "\n";
    echo "📝 Fichier: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
