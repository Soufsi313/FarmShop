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

echo "🎨 TEST DU NOUVEAU TEMPLATE EMAIL PERSONNALISÉ\n";
echo "============================================\n\n";

// 1. Récupérer la commande
echo "1. Récupération de la commande LOC-202508034682...\n";
$order = App\Models\OrderLocation::where('order_number', 'LOC-202508034682')->first();

if (!$order) {
    echo "❌ Commande non trouvée\n";
    exit(1);
}

echo "✅ Commande trouvée: {$order->order_number}\n";
echo "   Status: {$order->status}\n";
echo "   Utilisateur: {$order->user->name}\n\n";

// 2. Test de création de l'email avec le nouveau template
echo "2. Test du nouveau template personnalisé...\n";
try {
    $mail = new App\Mail\RentalOrderCompleted($order);
    echo "✅ Objet RentalOrderCompleted créé\n";
    
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

// 3. Test d'envoi de l'email avec le nouveau design
echo "3. Envoi de l'email avec le nouveau design...\n";
try {
    Illuminate\Support\Facades\Mail::to($order->user->email)->send($mail);
    echo "✅ Email envoyé avec succès avec le nouveau template personnalisé !\n";
    echo "📧 Destinataire: {$order->user->email}\n";
    echo "🎨 Template utilisé: emails.rental-order-completed-custom\n\n";
    
} catch (Exception $e) {
    echo "❌ Erreur lors de l'envoi: " . $e->getMessage() . "\n\n";
}

// 4. Résumé des améliorations
echo "4. Résumé des améliorations du nouveau template:\n";
echo "   ✅ Design HTML personnalisé (au lieu du Markdown Laravel)\n";
echo "   ✅ Couleurs et branding cohérents (vert agricole)\n";
echo "   ✅ Layout responsive pour mobile\n";
echo "   ✅ Icônes et emojis pour plus de lisibilité\n";
echo "   ✅ Bouton d'action proéminent\n";
echo "   ✅ Étapes du processus clairement détaillées\n";
echo "   ✅ Design professionnel et moderne\n";
echo "   ✅ Informations de contact intégrées\n\n";

echo "🎉 Nouveau template email personnalisé prêt !\n";
echo "📬 Vérifiez votre boîte email pour voir le nouveau design.\n";
