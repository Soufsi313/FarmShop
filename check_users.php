<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

echo "=== UTILISATEURS EXISTANTS ===\n";

$users = User::all(['id', 'name', 'email', 'username']);

if ($users->count() === 0) {
    echo "Aucun utilisateur trouvé!\n";
} else {
    foreach ($users as $user) {
        echo "ID: {$user->id}\n";
        echo "Name: {$user->name}\n";
        echo "Email: {$user->email}\n";
        echo "Username: " . ($user->username ?? 'N/A') . "\n";
        echo "---\n";
    }
}

echo "\nTotal: " . $users->count() . " utilisateur(s)\n";
