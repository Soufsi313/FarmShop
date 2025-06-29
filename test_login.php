<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Auth;
use App\Models\User;

echo "=== TEST AUTHENTIFICATION DIRECTE ===\n";

// Test de connexion directe
$credentials = [
    'email' => 's.mef2703@gmail.com',
    'password' => 'blade313.'
];

echo "Tentative de connexion avec:\n";
echo "Email: {$credentials['email']}\n";
echo "Password: {$credentials['password']}\n\n";

if (Auth::attempt($credentials)) {
    echo "✅ CONNEXION RÉUSSIE!\n";
    $user = Auth::user();
    echo "Utilisateur connecté: {$user->name} ({$user->email})\n";
} else {
    echo "❌ ÉCHEC DE CONNEXION\n";
    
    // Vérifions le mot de passe manuellement
    $admin = User::where('email', 's.mef2703@gmail.com')->first();
    if ($admin) {
        echo "Utilisateur trouvé: {$admin->name}\n";
        echo "Hash stocké: " . substr($admin->password, 0, 20) . "...\n";
        
        $check = \Illuminate\Support\Facades\Hash::check('blade313.', $admin->password);
        echo "Vérification Hash: " . ($check ? "✅ OK" : "❌ FAIL") . "\n";
    }
}

Auth::logout();
