<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use App\Notifications\VerifyEmailNotification;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Test du système de vérification d'email ===\n\n";

// Créer un utilisateur de test
echo "1. Création d'un utilisateur de test...\n";
$testEmail = 'test-verification@example.com';

// Supprimer l'utilisateur s'il existe déjà
User::where('email', $testEmail)->forceDelete();

$user = User::create([
    'username' => 'test_verification',
    'name' => 'Test Verification',
    'email' => $testEmail,
    'password' => \Illuminate\Support\Facades\Hash::make('password123'),
    'role' => 'User',
    'newsletter_subscribed' => false,
]);

echo "✅ Utilisateur créé : {$user->email}\n";
echo "   Email vérifié : " . ($user->hasVerifiedEmail() ? 'Oui' : 'Non') . "\n\n";

// Test envoi notification
echo "2. Test d'envoi de la notification de vérification...\n";
try {
    $user->sendEmailVerificationNotification();
    echo "✅ Notification envoyée avec succès\n\n";
} catch (Exception $e) {
    echo "❌ Erreur lors de l'envoi : " . $e->getMessage() . "\n\n";
}

// Générer l'URL de vérification manuellement
echo "3. Génération de l'URL de vérification...\n";
$verificationUrl = \Illuminate\Support\Facades\URL::temporarySignedRoute(
    'verification.verify',
    \Illuminate\Support\Carbon::now()->addMinutes(60),
    [
        'id' => $user->getKey(),
        'hash' => sha1($user->getEmailForVerification()),
    ]
);

echo "URL de vérification :\n";
echo $verificationUrl . "\n\n";

// Test de validation de l'URL
echo "4. Test de validation de l'URL...\n";
$urlParts = parse_url($verificationUrl);
parse_str($urlParts['query'] ?? '', $queryParams);

echo "ID utilisateur : " . ($queryParams['id'] ?? 'N/A') . "\n";
echo "Hash : " . substr($queryParams['hash'] ?? 'N/A', 0, 10) . "...\n";
echo "Signature : " . (isset($queryParams['signature']) ? 'Présente' : 'Absente') . "\n\n";

// Vérifier manuellement
echo "5. Simulation de vérification...\n";
$user->markEmailAsVerified();
$user->refresh();

echo "✅ Email marqué comme vérifié\n";
echo "   Email vérifié : " . ($user->hasVerifiedEmail() ? 'Oui' : 'Non') . "\n";
echo "   Date de vérification : " . ($user->email_verified_at ? $user->email_verified_at->format('Y-m-d H:i:s') : 'N/A') . "\n\n";

// Nettoyage
echo "6. Nettoyage...\n";
$user->forceDelete();
echo "✅ Utilisateur de test supprimé\n\n";

echo "=== Test terminé ===\n";
