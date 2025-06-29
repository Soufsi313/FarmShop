<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "=== TEST D'AUTHENTIFICATION ===\n";

// Test pour l'admin
$admin = User::where('email', 's.mef2703@gmail.com')->first();
if ($admin) {
    echo "✅ Admin trouvé: {$admin->email}\n";
    $passwordCheck = Hash::check('blade313.', $admin->password);
    echo "Mot de passe correct: " . ($passwordCheck ? "✅ OUI" : "❌ NON") . "\n";
} else {
    echo "❌ Admin non trouvé!\n";
}

echo "\n";

// Test pour l'utilisateur de test
$testUser = User::where('email', 'test@example.com')->first();
if ($testUser) {
    echo "✅ Utilisateur test trouvé: {$testUser->email}\n";
    $passwordCheck = Hash::check('password123', $testUser->password);
    echo "Mot de passe correct: " . ($passwordCheck ? "✅ OUI" : "❌ NON") . "\n";
} else {
    echo "❌ Utilisateur test non trouvé!\n";
}

echo "\n=== TENTATIVE DE CONNEXION ===\n";
echo "Utilisez ces identifiants:\n";
echo "Admin:\n";
echo "  Email: s.mef2703@gmail.com\n";
echo "  Mot de passe: blade313.\n";
echo "\nUtilisateur test:\n";
echo "  Email: test@example.com\n";
echo "  Mot de passe: password123\n";
