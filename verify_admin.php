<?php

use App\Models\User;

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Vérification du compte administrateur ===\n";

$admin = User::where('email', 's.mef2703@gmail.com')->first();

if ($admin) {
    echo "✅ Compte trouvé !\n";
    echo "ID: {$admin->id}\n";
    echo "Username: {$admin->username}\n";
    echo "Nom: {$admin->name}\n";
    echo "Email: {$admin->email}\n";
    echo "Rôle: {$admin->role}\n";
    echo "Est Admin: " . ($admin->isAdmin() ? 'Oui' : 'Non') . "\n";
    echo "Newsletter: " . ($admin->newsletter_subscribed ? 'Abonné' : 'Non abonné') . "\n";
    echo "Créé le: {$admin->created_at}\n";
    echo "Mis à jour le: {$admin->updated_at}\n";
} else {
    echo "❌ Compte non trouvé !\n";
}

echo "\n=== Total des utilisateurs en base ===\n";
$totalUsers = User::count();
echo "Nombre total d'utilisateurs: {$totalUsers}\n";
