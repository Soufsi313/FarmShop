<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 Recherche de votre compte utilisateur\n\n";

// Chercher les comptes admin ou récents
$adminUsers = App\Models\User::where('role', 'Admin')->get();
$recentUsers = App\Models\User::whereNotNull('email_verified_at')
    ->orderBy('created_at', 'desc')
    ->take(5)
    ->get();

echo "👑 Comptes Admin disponibles:\n";
foreach($adminUsers as $user) {
    echo "   ID: {$user->id} - {$user->name} ({$user->email})\n";
}

echo "\n📅 Comptes récents:\n";
foreach($recentUsers as $user) {
    echo "   ID: {$user->id} - {$user->name} ({$user->email}) - {$user->role}\n";
}

echo "\n🎯 Veuillez me dire quel ID d'utilisateur utiliser pour créer votre commande.\n";
echo "   Ou donnez-moi votre email si il n'apparaît pas dans la liste.\n";
