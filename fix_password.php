<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "=== CORRECTION MOT DE PASSE ADMIN ===\n";

$admin = User::where('email', 's.mef2703@gmail.com')->first();

if ($admin) {
    // Corriger le mot de passe
    $admin->password = Hash::make('blade313');
    $admin->save();
    
    echo "✅ Mot de passe corrigé pour: {$admin->email}\n";
    echo "Nouveau mot de passe: blade313\n";
    
    // Vérifier que ça fonctionne
    $check = Hash::check('blade313', $admin->password);
    echo "Vérification: " . ($check ? "✅ OK" : "❌ FAIL") . "\n";
    
} else {
    echo "❌ Admin non trouvé!\n";
}

echo "\nUtilisez maintenant:\n";
echo "Email: s.mef2703@gmail.com\n";
echo "Mot de passe: blade313\n";
