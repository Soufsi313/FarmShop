<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "=== Création d'un utilisateur admin ===\n";

// Vérifier s'il existe déjà un admin
$admin = User::where('email', 'admin@farmshop.com')->first();

if ($admin) {
    echo "Admin déjà existant: {$admin->email}\n";
    echo "ID: {$admin->id}\n";
    
    // Vérifier les rôles si la table roles existe
    try {
        $roles = $admin->roles ?? [];
        echo "Rôles: " . (count($roles) > 0 ? implode(', ', $roles->pluck('name')->toArray()) : 'Aucun rôle') . "\n";
    } catch (Exception $e) {
        echo "Système de rôles non configuré\n";
    }
} else {
    // Créer un nouvel admin
    $admin = User::create([
        'username' => 'admin',
        'name' => 'Admin FarmShop',
        'email' => 'admin@farmshop.com',
        'password' => Hash::make('admin123'),
        'role' => 'admin',
        'email_verified_at' => now(),
    ]);
    
    echo "Nouvel admin créé:\n";
    echo "Email: {$admin->email}\n";
    echo "Password: admin123\n";
    echo "ID: {$admin->id}\n";
}

// Vérifier la structure de la table users
echo "\n=== Structure de la table users ===\n";
$columns = \Illuminate\Support\Facades\Schema::getColumnListing('users');
echo "Colonnes: " . implode(', ', $columns) . "\n";

// Vérifier s'il y a une colonne role ou is_admin
if (in_array('role', $columns)) {
    echo "Colonne 'role' trouvée\n";
    if ($admin->role !== 'admin') {
        $admin->role = 'admin';
        $admin->save();
        echo "Rôle admin assigné\n";
    }
} elseif (in_array('is_admin', $columns)) {
    echo "Colonne 'is_admin' trouvée\n";
    if (!$admin->is_admin) {
        $admin->is_admin = true;
        $admin->save();
        echo "Flag admin activé\n";
    }
} else {
    echo "Aucune colonne de rôle trouvée - vérifier le système de rôles\n";
}

echo "\n=== Fin ===\n";
