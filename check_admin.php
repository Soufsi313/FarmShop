<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use App\Models\BlogPost;

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Chercher les utilisateurs admin
$admins = User::all();
echo "Tous les utilisateurs:" . PHP_EOL;
foreach ($admins as $admin) {
    echo "ID: {$admin->id} - {$admin->name} ({$admin->email})" . PHP_EOL;
}

echo PHP_EOL . "Nombre total d'articles actuels: " . BlogPost::count() . PHP_EOL;
