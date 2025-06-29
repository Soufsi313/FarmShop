<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

// Forcer la suppression
User::where('email', 's.mef2703@gmail.com')->forceDelete();
echo "Admin forcé de supprimer\n";

// Recréer
$admin = User::create([
    'name' => 'Admin User',
    'username' => 'saurouk',
    'email' => 's.mef2703@gmail.com',
    'password' => bcrypt('blade313.'),
    'email_verified_at' => now(),
]);

$admin->assignRole('admin');

echo "✅ Nouvel admin créé: {$admin->email} (ID: {$admin->id})\n";
