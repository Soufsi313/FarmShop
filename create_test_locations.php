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
    echo "🧪 Création de deux locations de test pour validation du workflow factures\n\n";
    
    // Récupérer l'utilisateur et des produits de location
    $user = User::find(1); // Meftah Soufiane
    
    // Sélectionner des produits adaptés à la location
    $product1 = Product::find(258); // Tronçonneuse Professionnelle
    $product2 = Product::find(269); // Fendeuse à Bois 12T
    
    if (!$user || !$product1 || !$product2) {
        echo "❌ Utilisateur ou produits non trouvés\n";
        echo "   Utilisateur: " . ($user ? "OK" : "MANQUANT") . "\n";
        echo "   Produit 1 (ID 258): " . ($product1 ? "OK" : "MANQUANT") . "\n";
        echo "   Produit 2 (ID 269): " . ($product2 ? "OK" : "MANQUANT") . "\n";
        exit(1);
    }
    
    echo "👤 Utilisateur: {$user->name} ({$user->email})\n";
    echo "📦 Produits sélectionnés:\n";
    echo "   - Produit 1: {$product1->name}\n";
    echo "   - Produit 2: {$product2->name}\n";
    
    // === LOCATION 1: TERMINÉE MAIS PAS INSPECTÉE ===
    echo "\n🚀 Création Location 1: TERMINÉE mais PAS INSPECTÉE\n";
    
    $location1 = OrderLocation::create([
        'user_id' => $user->id,
        'order_number' => 'LOC-TERM-' . date('YmdHis'),
        'status' => 'finished', // Location terminée
        'payment_status' => 'deposit_paid',
        'payment_method' => 'stripe',
        'start_date' => now()->subDays(5),
        'end_date' => now()->subDay(),
        'rental_days' => 4,
        'daily_rate' => 80.00,
        'total_rental_cost' => 320.00,
        'subtotal' => 320.00,
        'tax_rate' => 21.00,
        'tax_amount' => 67.20,
        'total_amount' => 387.20,
        'deposit_amount' => 200.00,
        'late_fee_per_day' => 15.00,
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
        // PAS d'inspection
        'late_fees' => 0.00,
        'damage_cost' => 0.00,
        'created_at' => now()->subDays(5),
        'updated_at' => now()
    ]);
    
    // Ajouter un article pour la location 1
    OrderItemLocation::create([
        'order_location_id' => $location1->id,
        'product_id' => $product1->id,
        'product_name' => $product1->name,
        'quantity' => 1,
        'daily_rate' => 80.00,
        'rental_days' => 4,
        'subtotal' => 320.00,
        'total_amount' => 320.00,
        'deposit_per_item' => 200.00,
        'total_deposit' => 200.00,
        'tax_amount' => 67.20,
        'created_at' => now()->subDays(5),
        'updated_at' => now()
    ]);
    
    echo "✅ Location 1 créée: {$location1->order_number}\n";
    echo "   Produit: {$product1->name}\n";
    echo "   Statut: {$location1->status}\n";
    echo "   Inspection: NON (facture initiale)\n";
    echo "   Total: {$location1->total_amount}€\n";
    
    // === LOCATION 2: TERMINÉE ET INSPECTÉE ===
    echo "\n🚀 Création Location 2: TERMINÉE et INSPECTÉE\n";
    
    $location2 = OrderLocation::create([
        'user_id' => $user->id,
        'order_number' => 'LOC-INSP-' . date('YmdHis'),
        'status' => 'finished', // Location terminée
        'payment_status' => 'deposit_paid',
        'payment_method' => 'stripe',
        'start_date' => now()->subDays(7),
        'end_date' => now()->subDays(3),
        'rental_days' => 4,
        'daily_rate' => 120.00,
        'total_rental_cost' => 480.00,
        'subtotal' => 480.00,
        'tax_rate' => 21.00,
        'tax_amount' => 100.80,
        'total_amount' => 655.80, // 580.80 + 75 pénalités
        'deposit_amount' => 300.00,
        'late_fee_per_day' => 25.00,
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
        // AVEC inspection et pénalités
        'inspection_completed_at' => now()->subHours(2),
        'inspection_notes' => 'Matériel retourné avec 1 jour de retard. Rayures mineures sur le boîtier. État général acceptable.',
        'late_fees' => 25.00, // 1 jour de retard
        'late_days' => 1,
        'damage_cost' => 50.00, // Frais pour rayures
        'deposit_refund' => 225.00, // 300 - 75 (25+50)
        'created_at' => now()->subDays(7),
        'updated_at' => now()
    ]);
    
    // Ajouter un article pour la location 2
    OrderItemLocation::create([
        'order_location_id' => $location2->id,
        'product_id' => $product2->id,
        'product_name' => $product2->name,
        'quantity' => 1,
        'daily_rate' => 120.00,
        'rental_days' => 4,
        'subtotal' => 480.00,
        'total_amount' => 480.00,
        'deposit_per_item' => 300.00,
        'total_deposit' => 300.00,
        'tax_amount' => 100.80,
        'created_at' => now()->subDays(7),
        'updated_at' => now()
    ]);
    
    echo "✅ Location 2 créée: {$location2->order_number}\n";
    echo "   Produit: {$product2->name}\n";
    echo "   Statut: {$location2->status}\n";
    echo "   Inspection: OUI (facture finale)\n";
    echo "   Total: {$location2->total_amount}€\n";
    echo "   Pénalités: {$location2->late_fees}€ + {$location2->damage_cost}€\n";
    echo "   Caution libérée: {$location2->deposit_refund}€ (sur {$location2->deposit_amount}€)\n";
    
    // Générer les numéros de facture
    echo "\n📄 Génération des numéros de facture:\n";
    
    try {
        $invoice1 = $location1->generateInvoiceNumber();
        echo "✅ Location 1 - Numéro facture: {$invoice1}\n";
    } catch (Exception $e) {
        echo "❌ Erreur facture location 1: " . $e->getMessage() . "\n";
    }
    
    try {
        $invoice2 = $location2->generateInvoiceNumber();
        echo "✅ Location 2 - Numéro facture: {$invoice2}\n";
    } catch (Exception $e) {
        echo "❌ Erreur facture location 2: " . $e->getMessage() . "\n";
    }
    
    echo "\n🌐 URLs de test:\n";
    echo "📋 Liste des locations: http://127.0.0.1:8000/rental-orders\n";
    echo "📄 Facture Location 1 (INITIALE): http://127.0.0.1:8000/rental-orders/{$location1->id}/invoice\n";
    echo "📄 Facture Location 2 (FINALE): http://127.0.0.1:8000/rental-orders/{$location2->id}/invoice\n";
    echo "🔍 Inspection Location 2: http://127.0.0.1:8000/rental-orders/{$location2->id}/inspection\n";
    
    echo "\n✅ Deux locations de test créées avec succès !\n";
    echo "   Location 1: Facture INITIALE (sans inspection)\n";
    echo "   Location 2: Facture FINALE (avec inspection et pénalités)\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "📍 Fichier: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
