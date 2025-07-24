<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Nettoyage des commandes en conflit...\n\n";

// Supprimer toutes les commandes de test pour éviter les conflits
$prefix = 'ORD-' . date('Y') . date('m');
$deletedCount = App\Models\Order::where('order_number', 'like', $prefix . '%')->delete();

echo "Supprimé $deletedCount commandes avec le préfixe $prefix\n";

// Vérifier qu'il n'y a plus de conflits
$remaining = App\Models\Order::where('order_number', 'like', $prefix . '%')->count();
echo "Commandes restantes: $remaining\n";

// Tester la génération d'un nouveau numéro
echo "Prochain numéro qui sera généré: " . App\Models\Order::generateOrderNumber() . "\n";
