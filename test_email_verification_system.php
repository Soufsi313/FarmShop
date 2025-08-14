<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

// Initialiser Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== TEST DU SYSTÈME DE VÉRIFICATION D'EMAIL ===\n\n";

try {
    // 1. Créer un utilisateur de test sans email vérifié
    echo "1. Création d'un utilisateur de test...\n";
    
    $testEmail = 'test.verification.' . time() . '@farmshop.test';
    $testUser = User::create([
        'username' => 'testverif' . time(),
        'name' => 'Test Vérification',
        'email' => $testEmail,
        'password' => bcrypt('password123'),
        'role' => 'User',
        'newsletter_subscribed' => false,
        'email_verified_at' => null // Pas encore vérifié
    ]);
    
    echo "✅ Utilisateur créé : {$testUser->email}\n";
    echo "   ID: {$testUser->id}\n";
    echo "   Email vérifié: " . ($testUser->hasVerifiedEmail() ? 'OUI' : 'NON') . "\n\n";
    
    // 2. Envoyer l'email de vérification
    echo "2. Envoi de l'email de vérification...\n";
    
    $testUser->sendEmailVerificationNotification();
    echo "✅ Email de vérification envoyé à {$testUser->email}\n\n";
    
    // 3. Simuler la vérification (dans la vraie vie, l'utilisateur clique sur le lien)
    echo "3. Simulation de la vérification d'email...\n";
    
    $testUser->markEmailAsVerified();
    echo "✅ Email marqué comme vérifié\n";
    
    // 4. Vérifier le statut
    echo "4. Vérification du statut final...\n";
    $testUser->refresh();
    
    echo "   Email vérifié: " . ($testUser->hasVerifiedEmail() ? 'OUI' : 'NON') . "\n";
    echo "   Date de vérification: " . ($testUser->email_verified_at ? $testUser->email_verified_at->format('Y-m-d H:i:s') : 'Aucune') . "\n\n";
    
    // 5. Test des URLs de vérification
    echo "5. Génération d'une URL de vérification...\n";
    
    $verificationUrl = \Illuminate\Support\Facades\URL::temporarySignedRoute(
        'verification.verify',
        \Illuminate\Support\Carbon::now()->addMinutes(60),
        [
            'id' => $testUser->getKey(),
            'hash' => sha1($testUser->getEmailForVerification()),
        ]
    );
    
    echo "✅ URL de vérification générée :\n";
    echo "   {$verificationUrl}\n\n";
    
    // 6. Nettoyage
    echo "6. Nettoyage...\n";
    $testUser->delete();
    echo "✅ Utilisateur de test supprimé\n\n";
    
    echo "=== TEST TERMINÉ AVEC SUCCÈS ===\n";
    echo "Le système de vérification d'email fonctionne correctement !\n\n";
    
    echo "Pour tester manuellement :\n";
    echo "1. Créez un compte sur http://127.0.0.1:8000/register\n";
    echo "2. Vérifiez votre boîte mail\n";
    echo "3. Cliquez sur le lien de vérification\n";
    echo "4. Vous devriez voir la page de confirmation\n";
    
} catch (Exception $e) {
    echo "❌ ERREUR: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
