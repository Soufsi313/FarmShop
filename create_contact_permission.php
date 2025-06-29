<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->boot();

use Spatie\Permission\Models\Permission;
use App\Models\User;

// Créer la permission si elle n'existe pas
$permission = Permission::firstOrCreate(['name' => 'manage contacts']);
echo "Permission 'manage contacts' créée ou trouvée.\n";

// Trouver le rôle admin et lui donner la permission
$adminRole = \Spatie\Permission\Models\Role::where('name', 'admin')->first();
if ($adminRole) {
    $adminRole->givePermissionTo('manage contacts');
    echo "Permission assignée au rôle admin.\n";
} else {
    echo "Rôle admin non trouvé.\n";
}

echo "Terminé !\n";
