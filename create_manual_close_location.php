<?php
require_once __DIR__ . '/vendor/autoload.php';
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
$app = Application::configure(basePath: __DIR__)
    ->withRouting(
        web: __DIR__.'/routes/web.php',
        api: __DIR__.'/routes/api.php',
        commands: __DIR__.'/routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {})
    ->withExceptions(function (Exceptions $exceptions) {})
    ->create();
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
use App\Models\OrderLocation;
use App\Models\OrderItemLocation;
use App\Models\Product;
use App\Models\User;

try {
    echo "🚀 Création d'une location TERMINÉE à clôturer manuellement\n\n";
    
    // Récupérer l'utilisateur et un produit
    $user = User::find(1); // Meftah Soufiane
    $product = Product::find(211); // Tondeuse Autoportée agricole
    
    if (!$user || !$product) {
        echo "❌ Utilisateur ou produit non trouvé\n";
        echo "   Utilisateur: " . ($user ? "OK" : "MANQUANT") . "\n";
        echo "   Produit 211: " . ($product ? "OK" : "MANQUANT") . "\n";
        exit(1);
    }
    
    echo "👤 Utilisateur: {$user->name}\n";
    echo "📦 Produit: {$product->name}\n\n";
    
    // Créer une location TERMINÉE (completed) mais PAS CLÔTURÉE
    $location = OrderLocation::create([
        'user_id' => $user->id,
        'order_number' => 'LOC-COMP-' . date('YmdHis'),
        'status' => 'completed', // TERMINÉE mais pas clôturée
        'payment_status' => 'deposit_paid',
        'payment_method' => 'stripe',
        'start_date' => now()->subDays(3),
        'end_date' => now()->subDay(), // Période terminée hier
        'rental_days' => 2,
        'daily_rate' => 150.00,
        'total_rental_cost' => 300.00,
        'subtotal' => 300.00,
        'tax_rate' => 21.00,
        'tax_amount' => 63.00,
        'total_amount' => 363.00,
        'deposit_amount' => 250.00,
        'late_fee_per_day' => 20.00,
        'billing_address' => json_encode([
            'name' => $user->name,
            'address' => 'Rue Test 123',
            'city' => 'Bruxelles',
            'postal_code' => '1000',
            'country' => 'BE'
        ]),
        'delivery_address' => json_encode([
            'name' => $user->name,
            'address' => 'Rue Test 123',
            'city' => 'Bruxelles',
            'postal_code' => '1000',
            'country' => 'BE'
        ]),
        // AUCUNE inspection - sera faite après clôture manuelle
        'late_fees' => 0.00,
        'damage_cost' => 0.00,
        'created_at' => now()->subDays(3),
        'updated_at' => now()
    ]);
    
    // Ajouter l'article
    OrderItemLocation::create([
        'order_location_id' => $location->id,
        'product_id' => $product->id,
        'product_name' => $product->name,
        'quantity' => 1,
        'daily_rate' => 150.00,
        'rental_days' => 2,
        'subtotal' => 300.00,
        'total_amount' => 300.00,
        'deposit_per_item' => 250.00,
        'total_deposit' => 250.00,
        'tax_amount' => 63.00,
        'created_at' => now()->subDays(3),
        'updated_at' => now()
    ]);
    
    echo "✅ Location créée: {$location->order_number}\n";
    echo "   Produit: {$product->name}\n";
    echo "   Statut: {$location->status} (🟣 Terminée)\n";
    echo "   Période: " . $location->start_date->format('d/m/Y') . " → " . $location->end_date->format('d/m/Y') . "\n";
    echo "   Total: {$location->total_amount}€\n";
    
    // Générer le numéro de facture
    try {
        $invoiceNumber = $location->generateInvoiceNumber();
        echo "   Facture: {$invoiceNumber}\n";
    } catch (Exception $e) {
        echo "   ❌ Erreur facture: " . $e->getMessage() . "\n";
    }
    
    echo "\n🎯 Workflow prévu:\n";
    echo "   1. 🟣 TERMINÉE (statut actuel) - Matériel retourné\n";
    echo "   2. 🟠 CLÔTURÉE (à faire manuellement) - Validation retour\n";
    echo "   3. 🔍 INSPECTION EN COURS (automatique après clôture)\n";
    echo "   4. ✅ INSPECTION TERMINÉE (après saisie résultats)\n";
    
    echo "\n🌐 URL de gestion:\n";
    echo "   Liste locations: http://127.0.0.1:8000/rental-orders\n";
    echo "   Détails: http://127.0.0.1:8000/rental-orders/{$location->id}\n";
    echo "   Facture: http://127.0.0.1:8000/rental-orders/{$location->id}/invoice\n";
    
    echo "\n📋 Actions disponibles:\n";
    echo "   → Vous pouvez maintenant CLÔTURER manuellement cette location\n";
    echo "   → Puis procéder à l'inspection\n";
    echo "   → Tester les deux types de factures (avant/après inspection)\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "📍 Fichier: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
