<?php
// Vérifier et nettoyer les retours en conflit
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== VÉRIFICATION DES RETOURS EN CONFLIT ===\n\n";

// Chercher les retours existants qui peuvent causer un conflit
$returns = \App\Models\OrderReturn::where('return_number', 'LIKE', 'RET202507011055%')->get();

echo "Retours trouvés avec le pattern RET202507011055* :\n";
foreach ($returns as $return) {
    echo "  - ID: {$return->id}\n";
    echo "  - Numéro: {$return->return_number}\n";
    echo "  - Commande: {$return->order_id}\n";
    echo "  - Statut: {$return->status}\n";
    echo "  - Créé le: {$return->created_at}\n";
    echo "  ---\n";
}

if ($returns->count() > 0) {
    echo "\nSuppression des retours en conflit...\n";
    foreach ($returns as $return) {
        echo "Suppression du retour #{$return->id} ({$return->return_number})\n";
        $return->delete();
    }
    echo "Nettoyage terminé !\n";
} else {
    echo "Aucun retour en conflit trouvé.\n";
}

// Vérifier tous les retours pour la commande 8
$order8Returns = \App\Models\OrderReturn::where('order_id', 8)->get();
echo "\nRetours existants pour la commande 8 :\n";
foreach ($order8Returns as $return) {
    echo "  - ID: {$return->id}, Numéro: {$return->return_number}, Statut: {$return->status}\n";
}

echo "\nVérification terminée !\n";
