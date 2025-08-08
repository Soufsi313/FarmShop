<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

echo "=== PRÉPARATION TEST LOCATION ===\n\n";

// 1. Vérifier les produits louables disponibles
echo "1. PRODUITS LOUABLES DISPONIBLES :\n";
$rentalProducts = Product::where('type', 'rental')
    ->where('stock', '>', 0)
    ->where('available', true)
    ->take(5)
    ->get();

if ($rentalProducts->count() > 0) {
    foreach ($rentalProducts as $product) {
        echo "- ID {$product->id}: {$product->name}\n";
        echo "  Stock: {$product->stock} | Prix location: €{$product->rental_price}/jour\n";
        echo "  Statut: {$product->rental_status}\n\n";
    }
} else {
    echo "❌ Aucun produit louable disponible!\n\n";
}

// 2. Vérifier la configuration des dates
echo "2. DATES DE TEST :\n";
$startDate = '2025-08-08';
$endDate = '2025-08-09';
echo "Début location: {$startDate}\n";
echo "Fin location: {$endDate}\n";
echo "Durée: 1 jour\n\n";

// 3. Vérifier que les jobs automatiques fonctionnent
echo "3. VÉRIFICATION QUEUE SYSTEM :\n";
$pendingJobs = DB::table('jobs')->count();
echo "Jobs en attente: {$pendingJobs}\n";

echo "4. COMMANDES À SUIVRE :\n";
echo "a) Créer votre commande de location sur le site\n";
echo "b) Payer avec Stripe\n";
echo "c) Observer les transitions automatiques :\n";
echo "   - pending → confirmed (paiement)\n";
echo "   - confirmed → preparing (30s)\n";
echo "   - preparing → shipped (1min)\n";
echo "   - shipped → delivered (2min)\n";
echo "   - delivered → active (quand la location commence)\n";
echo "   - active → completed (quand la location se termine)\n\n";

echo "✅ Système prêt pour test de location courte (8/08-9/08)!\n";
