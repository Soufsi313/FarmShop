<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Structure de la table newsletters ===\n";
$columns = DB::select('DESCRIBE newsletters');
foreach ($columns as $column) {
    echo sprintf("%-20s %-15s %-10s %-10s\n", 
        $column->Field, 
        $column->Type, 
        $column->Null,
        $column->Default ?? 'NULL'
    );
}

echo "\n=== Utilisateur admin pour created_by ===\n";
$admin = App\Models\User::where('role', 'admin')->first();
if ($admin) {
    echo "Admin trouvé: ID {$admin->id}, Email: {$admin->email}\n";
} else {
    echo "Aucun admin trouvé, recherche du premier utilisateur...\n";
    $firstUser = App\Models\User::first();
    if ($firstUser) {
        echo "Premier utilisateur: ID {$firstUser->id}, Email: {$firstUser->email}\n";
    }
}
