<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

// Créer les rôles s'ils n'existent pas
$adminRole = Role::firstOrCreate(['name' => 'admin']);
$userRole = Role::firstOrCreate(['name' => 'user']);

// Créer les permissions principales
$permissions = [
    'manage users',
    'manage products', 
    'manage orders',
    'manage categories',
    'manage contacts',
    'manage cookies',
    'manage newsletters',
    'manage_rentals'
];

foreach ($permissions as $permission) {
    Permission::firstOrCreate(['name' => $permission]);
}

// Assigner toutes les permissions au rôle admin
$adminRole->syncPermissions(Permission::all());

// Créer l'utilisateur admin
$admin = User::create([
    'name' => 'Admin User',
    'username' => 'saurouk', 
    'email' => 's.mef2703@gmail.com',
    'password' => bcrypt('blade313.'),
    'email_verified_at' => now(),
]);

// Assigner le rôle admin
$admin->assignRole('admin');

echo "✅ Compte admin créé avec succès!\n";
echo "Email: s.mef2703@gmail.com\n";
echo "Username: saurouk\n";
echo "Mot de passe: blade313.\n";
echo "ID: " . $admin->id . "\n";
