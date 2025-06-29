<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "=== DIAGNOSTIC COMPTE ADMIN ===\n";

$admin = User::where('email', 's.mef2703@gmail.com')->first();

if ($admin) {
    echo "✅ Compte admin trouvé\n";
    echo "ID: {$admin->id}\n";
    echo "Name: {$admin->name}\n";
    echo "Email: {$admin->email}\n";
    echo "Username: " . ($admin->username ?? 'N/A') . "\n";
    echo "Email vérifié: " . ($admin->email_verified_at ? '✅ Oui' : '❌ Non') . "\n";
    echo "Créé le: {$admin->created_at}\n";
    
    // Test des mots de passe possibles
    $passwords = ['blade313.', 'blade313', 'admin123', 'password'];
    
    echo "\n=== TEST MOTS DE PASSE ===\n";
    foreach ($passwords as $password) {
        $check = Hash::check($password, $admin->password);
        echo "'{$password}': " . ($check ? '✅ CORRECT' : '❌ Incorrect') . "\n";
    }
    
    // Réinitialiser le mot de passe
    echo "\n=== RÉINITIALISATION ===\n";
    $admin->password = Hash::make('blade313.');
    $admin->email_verified_at = now();
    $admin->save();
    
    echo "✅ Mot de passe réinitialisé à: blade313.\n";
    echo "✅ Email marqué comme vérifié\n";
    
} else {
    echo "❌ Compte admin non trouvé!\n";
}
