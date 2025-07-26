<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Suppression de l'utilisateur admin test ===\n";

$user = App\Models\User::where('email', 'admin@farmshop.test')->first();

if ($user) {
    $user->delete();
    echo "✅ Utilisateur admin test supprimé avec succès\n";
} else {
    echo "ℹ️ Aucun utilisateur admin test trouvé\n";
}

echo "\n=== Liste des utilisateurs existants ===\n";
$users = App\Models\User::all();
foreach ($users as $user) {
    echo "- {$user->email} (Role: {$user->role})\n";
}

echo "\n=== Fin ===\n";
