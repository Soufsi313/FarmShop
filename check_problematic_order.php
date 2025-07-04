<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Order;
use App\Models\User;

echo "🔍 Recherche de la commande problématique FS20250701471...\n\n";

// Rechercher la commande
$order = Order::where('order_number', 'like', '%FS20250701471%')->first();

if ($order) {
    echo "✅ Commande trouvée :\n";
    echo "- Numéro : {$order->order_number}\n";
    echo "- Status : {$order->status}\n";
    echo "- Email utilisateur : {$order->user->email}\n";
    echo "- Date création : {$order->created_at}\n";
    echo "- Date mise à jour : {$order->updated_at}\n\n";
    
    echo "🗑️ Suppression de cette commande de test...\n";
    $order->delete();
    echo "✅ Commande supprimée !\n\n";
} else {
    echo "❌ Aucune commande trouvée avec ce numéro.\n\n";
}

// Rechercher l'utilisateur de test
$testUser = User::where('email', 'test.client@farmshop.com')->first();

if ($testUser) {
    echo "✅ Utilisateur de test trouvé :\n";
    echo "- Nom : {$testUser->name}\n";
    echo "- Email : {$testUser->email}\n";
    echo "- Nombre de commandes : " . $testUser->orders()->count() . "\n\n";
    
    echo "🗑️ Suppression de cet utilisateur de test...\n";
    $testUser->orders()->delete(); // Supprimer toutes ses commandes
    $testUser->delete();
    echo "✅ Utilisateur de test supprimé !\n\n";
} else {
    echo "❌ Aucun utilisateur de test trouvé.\n\n";
}

echo "✨ Nettoyage terminé !\n";
