<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

echo "Test simple d'envoi d'email de vérification\n";

// Trouver un utilisateur existant
$user = User::first();
if (!$user) {
    echo "Aucun utilisateur trouvé\n";
    exit(1);
}

echo "Utilisateur trouvé : {$user->email}\n";
echo "Email vérifié : " . ($user->hasVerifiedEmail() ? 'Oui' : 'Non') . "\n";

try {
    // Marquer comme non vérifié pour le test
    $user->email_verified_at = null;
    $user->save();
    
    echo "Envoi de l'email de vérification...\n";
    $user->sendEmailVerificationNotification();
    echo "✅ Email envoyé avec succès !\n";
    
} catch (Exception $e) {
    echo "❌ Erreur : " . $e->getMessage() . "\n";
    echo "Trace : " . $e->getTraceAsString() . "\n";
}
