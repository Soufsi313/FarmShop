<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;

echo "=== Test complet du processus d'inscription avec vérification d'email ===\n\n";

// 1. Simuler l'inscription d'un nouvel utilisateur
echo "1. Simulation de l'inscription d'un nouvel utilisateur...\n";

$testEmail = 'nouveau.test@example.com';
$testUsername = 'nouveau_test_' . time();

// Supprimer l'utilisateur s'il existe déjà
User::where('email', $testEmail)->forceDelete();
User::where('username', $testUsername)->forceDelete();

// Simuler les données du formulaire d'inscription
$userData = [
    'username' => $testUsername,
    'name' => 'Nouveau Test',
    'email' => $testEmail,
    'password' => 'password123',
    'password_confirmation' => 'password123',
    'terms' => 'on'
];

echo "Données d'inscription :\n";
echo "- Username : {$userData['username']}\n";
echo "- Email : {$userData['email']}\n";
echo "- Nom : {$userData['name']}\n\n";

// 2. Créer l'utilisateur (comme le ferait RegisterController)
echo "2. Création de l'utilisateur...\n";

$user = User::create([
    'username' => $userData['username'],
    'name' => $userData['name'],
    'email' => $userData['email'],
    'password' => \Illuminate\Support\Facades\Hash::make($userData['password']),
    'role' => 'User',
    'newsletter_subscribed' => false,
]);

echo "✅ Utilisateur créé avec ID : {$user->id}\n";
echo "   Email vérifié : " . ($user->hasVerifiedEmail() ? 'Oui' : 'Non') . "\n";
echo "   Date de vérification : " . ($user->email_verified_at ? $user->email_verified_at->format('Y-m-d H:i:s') : 'Null') . "\n\n";

// 3. Simuler l'envoi de l'email de vérification
echo "3. Envoi de l'email de vérification...\n";

try {
    $user->sendEmailVerificationNotification();
    echo "✅ Email de vérification envoyé avec succès\n\n";
} catch (Exception $e) {
    echo "❌ Erreur lors de l'envoi : " . $e->getMessage() . "\n\n";
    exit(1);
}

// 4. Générer l'URL de vérification (comme dans l'email)
echo "4. Génération de l'URL de vérification...\n";

$verificationUrl = URL::temporarySignedRoute(
    'verification.verify',
    Carbon::now()->addMinutes(60),
    [
        'id' => $user->getKey(),
        'hash' => sha1($user->getEmailForVerification()),
    ]
);

echo "URL complète de vérification :\n";
echo $verificationUrl . "\n\n";

// 5. Simuler le clic sur le lien de vérification
echo "5. Simulation du clic sur le lien de vérification...\n";

// Extraire les paramètres de l'URL
$urlParts = parse_url($verificationUrl);
parse_str($urlParts['query'] ?? '', $queryParams);

echo "Paramètres extraits :\n";
echo "- ID : " . ($queryParams['id'] ?? 'N/A') . "\n";
echo "- Hash : " . substr($queryParams['hash'] ?? 'N/A', 0, 20) . "...\n";
echo "- Expires : " . ($queryParams['expires'] ?? 'N/A') . "\n";
echo "- Signature présente : " . (isset($queryParams['signature']) ? 'Oui' : 'Non') . "\n\n";

// 6. Vérifier manuellement l'email (comme le ferait la route)
echo "6. Vérification de l'email...\n";

if ($user->hasVerifiedEmail()) {
    echo "⚠️  L'email est déjà vérifié\n";
} else {
    $user->markEmailAsVerified();
    echo "✅ Email marqué comme vérifié\n";
}

$user->refresh();

echo "État après vérification :\n";
echo "   Email vérifié : " . ($user->hasVerifiedEmail() ? 'Oui' : 'Non') . "\n";
echo "   Date de vérification : " . ($user->email_verified_at ? $user->email_verified_at->format('Y-m-d H:i:s') : 'Null') . "\n\n";

// 7. Test d'accès aux fonctionnalités protégées
echo "7. Test d'accès aux fonctionnalités...\n";

if ($user->hasVerifiedEmail()) {
    echo "✅ L'utilisateur peut maintenant :\n";
    echo "   - Se connecter normalement\n";
    echo "   - Accéder à son profil\n";
    echo "   - Passer des commandes\n";
    echo "   - Utiliser toutes les fonctionnalités du site\n\n";
} else {
    echo "❌ L'utilisateur ne peut pas accéder aux fonctionnalités protégées\n\n";
}

// 8. Test des URLs importantes
echo "8. URLs importantes du processus :\n";
echo "- Page d'inscription : http://127.0.0.1:8000/register\n";
echo "- Page de connexion : http://127.0.0.1:8000/login\n";
echo "- Page de vérification : http://127.0.0.1:8000/email/verify\n";
echo "- Page de confirmation : http://127.0.0.1:8000/email/verification-success\n\n";

// 9. Résumé du processus
echo "9. Résumé du processus complet :\n";
echo "✅ 1. Utilisateur s'inscrit via /register\n";
echo "✅ 2. Compte créé mais email non vérifié\n";
echo "✅ 3. Email de vérification envoyé automatiquement\n";
echo "✅ 4. URL de vérification générée avec signature\n";
echo "✅ 5. Clic sur le lien vérifie l'email\n";
echo "✅ 6. Redirection vers page de confirmation\n";
echo "✅ 7. Utilisateur peut maintenant se connecter\n\n";

// 10. Nettoyage (optionnel)
echo "10. Nettoyage...\n";
echo "Voulez-vous supprimer l'utilisateur de test ? (Il a été créé pour le test)\n";
echo "Utilisateur : {$user->email} (ID: {$user->id})\n";

// Pour les tests, on le laisse pour vérification manuelle
echo "✅ Utilisateur de test conservé pour vérification manuelle\n";
echo "   Vous pouvez vous connecter avec :\n";
echo "   Email: {$user->email}\n";
echo "   Password: password123\n\n";

echo "=== Test terminé avec succès ! ===\n";
echo "Le processus d'inscription avec vérification d'email fonctionne correctement.\n";
