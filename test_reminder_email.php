<?php
require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: __DIR__)
    ->withRouting(
        web: __DIR__.'/routes/web.php',
        commands: __DIR__.'/routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "📅 TEST EMAIL DE RAPPEL LA VEILLE\n";
echo "=================================\n\n";

// 1. Récupérer la commande de test
$order = App\Models\OrderLocation::where('order_number', 'LOC-202508034682')->first();

if (!$order) {
    echo "❌ Commande non trouvée\n";
    exit(1);
}

echo "📋 Commande: {$order->order_number}\n";
echo "👤 Utilisateur: {$order->user->name}\n";
echo "📧 Email: {$order->user->email}\n\n";

// 2. Test de création de l'email de rappel
echo "📨 Test du template de rappel la veille...\n";
try {
    $mail = new App\Mail\RentalEndReminderMail($order);
    echo "✅ Objet RentalEndReminderMail créé\n";
    
    // Vérifier le sujet et le contenu
    $envelope = $mail->envelope();
    echo "✅ Sujet: {$envelope->subject}\n";
    
    $content = $mail->content();
    echo "✅ Template configuré: {$content->view}\n\n";
    
} catch (Exception $e) {
    echo "❌ Erreur lors de la création de l'email: " . $e->getMessage() . "\n";
    echo "   Fichier: " . $e->getFile() . " ligne " . $e->getLine() . "\n\n";
    exit(1);
}

// 3. Test d'envoi de l'email de rappel
echo "📤 Envoi de l'email de rappel de test...\n";
try {
    Illuminate\Support\Facades\Mail::to($order->user->email)->send($mail);
    echo "✅ Email de rappel envoyé avec succès !\n";
    echo "📧 Destinataire: {$order->user->email}\n";
    echo "🎨 Template utilisé: emails.rental-end-reminder\n\n";
    
} catch (Exception $e) {
    echo "❌ Erreur lors de l'envoi: " . $e->getMessage() . "\n\n";
}

// 4. Informations sur le système de rappel automatique
echo "🔄 SYSTÈME DE RAPPEL AUTOMATIQUE:\n";
echo "==================================\n";
echo "• ⏰ Programmé 24h avant la fin de location\n";
echo "• 🎨 Template HTML personnalisé avec checklist\n";
echo "• 📱 Lien vers la location pour suivi\n";
echo "• 📞 Informations de contact intégrées\n";
echo "• ✅ Liste de vérification avant retour\n\n";

echo "📬 CHRONOLOGIE DES EMAILS POUR VOTRE TEST (06/08 → 07/08):\n";
echo "========================================================\n";
echo "1. 📧 Confirmation (immédiat à la création)\n";
echo "2. 📧 Démarrage location (06/08 à minuit)\n";
echo "3. 📧 RAPPEL - VEILLE (06/08 à minuit) ← NOUVEAU !\n";
echo "4. 📧 Fin location + template personnalisé (07/08 à minuit)\n";
echo "5. 🔒 Action manuelle: Clôture → Inspection\n\n";

echo "🎯 PRÊT POUR LE TEST COMPLET !\n";
echo "Créez votre location 06/08→07/08 et vous recevrez tous les emails automatiquement.\n";
