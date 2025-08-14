<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Newsletter;

echo "🔄 RESTAURATION DES ABONNÉS NEWSLETTER\n";
echo "======================================\n\n";

// Récupérer l'admin
$adminEmail = 's.mef2703@gmail.com';
$admin = User::where('email', $adminEmail)->first();

echo "👤 Admin: {$admin->email}\n";

// Réabonner des utilisateurs réalistes (pas les emails de test trop évidents)
echo "\n📧 Réabonnement d'utilisateurs réalistes...\n";

$realisticEmails = [
    $adminEmail, // Vous
    'jean.dupont.1@orange.fr',
    'marie.martin.2@hotmail.com',
    'pierre.bernard.3@laposte.net',
    'sophie.dubois.4@free.fr',
    'michel.leroy.5@yahoo.fr',
    'nathalie.moreau.6@gmail.com',
    'david.garcia.7@orange.fr',
    'isabelle.rodriguez.8@hotmail.com',
    'philippe.martinez.9@laposte.net',
    'catherine.lopez.10@free.fr'
];

$resubscribed = 0;
foreach ($realisticEmails as $email) {
    $user = User::where('email', $email)->first();
    if ($user) {
        $user->update(['newsletter_subscribed' => true]);
        $resubscribed++;
        echo "   ✅ {$email}\n";
    }
}

echo "\n📊 RÉSULTAT:\n";
echo "   Utilisateurs réabonnés: {$resubscribed}\n";

// Vérifier le statut de la newsletter
$newsletter = Newsletter::orderBy('id', 'desc')->first();
echo "\n📰 NEWSLETTER:\n";
echo "   Titre: {$newsletter->title}\n";
echo "   Status: {$newsletter->status}\n";

// Si elle est déjà envoyée, la remettre en draft pour nouveaux tests
if ($newsletter->status === 'sent') {
    $newsletter->update(['status' => 'draft']);
    echo "   ✅ Newsletter remise en draft pour pouvoir être renvoyée\n";
}

$totalSubscribers = User::where('newsletter_subscribed', true)->count();
echo "\n🎯 ÉTAT FINAL:\n";
echo "   • Total abonnés: {$totalSubscribers}\n";
echo "   • Vous êtes inclus dans les abonnés\n";
echo "   • Quand vous cliquez 'Envoyer maintenant', TOUS reçoivent la newsletter (vous y compris)\n";
echo "   • Le bouton 'Envoyer à moi' reste pour vos tests personnels\n";

echo "\n💡 MAINTENANT:\n";
echo "   1. Allez sur votre dashboard newsletter\n";
echo "   2. Cliquez 'Envoyer maintenant' → Tous les {$totalSubscribers} abonnés la recevront\n";
echo "   3. Vous la recevrez dans votre boîte mail aussi\n";
echo "   4. Le bouton disparaîtra après envoi (newsletter status = 'sent')\n";
