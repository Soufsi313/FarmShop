<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 DIAGNOSTIC SYSTÈME DE CAUTION\n";
echo "=================================\n\n";

echo "📊 1. ANALYSE DES CHAMPS DE CAUTION DANS LA BASE\n";
echo "------------------------------------------------\n";

// Vérifier la structure de la table order_locations
$columns = DB::select("DESCRIBE order_locations");
$cautionFields = [];
foreach ($columns as $column) {
    if (strpos(strtolower($column->Field), 'deposit') !== false || 
        strpos(strtolower($column->Field), 'caution') !== false) {
        $cautionFields[] = $column->Field;
        echo "✅ Champ trouvé: {$column->Field} ({$column->Type})\n";
    }
}

echo "\n📋 2. LOCATIONS EXISTANTES - ANALYSE DES CAUTIONS\n";
echo "------------------------------------------------\n";

$locations = DB::table('order_locations')
    ->select('id', 'order_number', 'status', 'deposit_amount', 'penalty_amount', 'deposit_refund', 'total_amount')
    ->orderBy('created_at', 'desc')
    ->limit(5)
    ->get();

foreach ($locations as $loc) {
    echo "🏠 Location #{$loc->order_number}\n";
    echo "   Status: {$loc->status}\n";
    echo "   Total commande: " . ($loc->total_amount ?? 0) . "€\n";
    echo "   Caution payée: " . ($loc->deposit_amount ?? 0) . "€\n";
    echo "   Pénalités: " . ($loc->penalty_amount ?? 0) . "€\n";
    echo "   Remboursement: " . ($loc->deposit_refund ?? 'Non calculé') . "€\n";
    echo "   ---\n";
}

echo "\n🔧 3. VÉRIFICATION LOGIQUE DE CALCUL\n";
echo "------------------------------------\n";

// Prendre un exemple concret
$testLocation = DB::table('order_locations')->first();
if ($testLocation) {
    $depositAmount = $testLocation->deposit_amount ?? 0;
    $penaltyAmount = $testLocation->penalty_amount ?? 0;
    $expectedRefund = max(0, $depositAmount - $penaltyAmount);
    
    echo "📝 Exemple de calcul (Location #{$testLocation->order_number}):\n";
    echo "   Caution payée: {$depositAmount}€\n";
    echo "   Pénalités: {$penaltyAmount}€\n";
    echo "   Remboursement attendu: {$expectedRefund}€\n";
    echo "   Remboursement stocké: " . ($testLocation->deposit_refund ?? 'NULL') . "€\n";
    
    if ($testLocation->deposit_refund != $expectedRefund) {
        echo "   ⚠️ PROBLÈME: Le remboursement stocké ne correspond pas au calcul!\n";
    } else {
        echo "   ✅ Calcul cohérent\n";
    }
}

echo "\n🎯 4. RECHERCHE DE PROBLÈMES DANS LE CODE\n";
echo "----------------------------------------\n";

// Vérifier les endroits où deposit_refund est calculé
$controllers = [
    'app/Http/Controllers/Admin/RentalReturnsController.php',
    'app/Http/Controllers/Admin/OrderLocationController.php'
];

foreach ($controllers as $controller) {
    if (file_exists($controller)) {
        $content = file_get_contents($controller);
        if (strpos($content, 'deposit_refund') !== false) {
            echo "✅ {$controller} utilise deposit_refund\n";
            
            // Chercher les calculs
            if (preg_match('/deposit_refund.*=.*max\(0,.*deposit_amount.*-.*\)/i', $content)) {
                echo "   → Calcul trouvé: max(0, deposit_amount - pénalités)\n";
            } else {
                echo "   ⚠️ Calcul non trouvé ou différent\n";
            }
        }
    }
}

echo "\n💡 5. SUGGESTIONS DE PROBLÈMES POSSIBLES\n";
echo "---------------------------------------\n";
echo "1. Confusion entre deposit_amount (caution) et total_amount (prix total)\n";
echo "2. Calcul incorrect: caution = prix total au lieu de garantie\n";
echo "3. Mélange entre prix de location et caution de garantie\n";
echo "4. Problème dans la logique de création des commandes\n";

echo "\n📱 6. VÉRIFICATION STRUCTURE PRODUITS\n";
echo "------------------------------------\n";

$product = DB::table('products')->where('rental_stock', '>', 0)->first();
if ($product) {
    echo "📦 Exemple produit de location:\n";
    echo "   Nom: {$product->name}\n";
    echo "   Prix/jour: " . ($product->rental_price_per_day ?? 'Non défini') . "€\n";
    echo "   Caution: " . ($product->rental_deposit ?? 'Non définie') . "€\n";
    
    if (!isset($product->rental_deposit) || $product->rental_deposit == 0) {
        echo "   ⚠️ PROBLÈME: Pas de caution définie sur les produits!\n";
    }
}
