<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use App\Models\User;

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== UTILISATEURS EN BASE DE DONNÉES ===\n\n";

$users = User::select('id', 'name', 'email', 'role')->get();

echo "Nombre total d'utilisateurs : " . $users->count() . "\n\n";

foreach ($users as $user) {
    echo "ID: {$user->id} | Nom: {$user->name} | Email: {$user->email} | Rôle: {$user->role}\n";
}

echo "\n=== FIN DE LA LISTE ===\n";
