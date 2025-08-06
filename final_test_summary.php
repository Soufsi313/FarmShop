<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🎯 RÉSUMÉ FINAL DES LOCATIONS DE TEST CRÉÉES\n";
echo "=============================================\n\n";

$orders = DB::table('order_locations')
    ->where('order_number', 'like', 'LOC-TEST-%-20250805')
    ->orderBy('id')
    ->get();

foreach ($orders as $i => $order) {
    echo ($i + 1) . ". {$order->order_number} (ID: {$order->id})\n";
    echo "   📅 Période: " . $order->start_date . " → " . $order->end_date . " ({$order->rental_days} jours)\n";
    echo "   💰 Montant total: {$order->total_amount}€ (HT: {$order->subtotal}€ + TVA: {$order->tax_amount}€)\n";
    echo "   ⏰ Retard: {$order->late_days} jour(s) = {$order->late_fees}€\n";
    echo "   🔧 Dégâts: {$order->damage_cost}€\n";
    echo "   🎯 Total pénalités: {$order->total_penalties}€\n";
    echo "   💳 Remboursement caution: {$order->deposit_refund}€\n";
    echo "   📊 Status: {$order->status}\n";
    
    // Vérifier si l'item existe
    $item = DB::table('order_item_locations')
        ->where('order_location_id', $order->id)
        ->first();
    
    if ($item) {
        echo "   ✅ Item associé: {$item->product_name}\n";
    } else {
        echo "   ❌ Aucun item associé\n";
    }
    
    echo "\n";
}

echo "📋 INSTRUCTIONS POUR LE TEST D'INSPECTION:\n";
echo "==========================================\n";
echo "1. Connectez-vous à votre interface admin\n";
echo "2. Allez dans 'Locations' ou 'Gestion des locations'\n";
echo "3. Vous devriez voir 3 nouvelles locations avec le statut 'completed'\n";
echo "4. Pour chaque location, vous pouvez :\n";
echo "   • Clôturer la location manuellement\n";
echo "   • Effectuer l'inspection du matériel\n";
echo "   • Valider les frais de retard et dégâts\n";
echo "   • Calculer le remboursement de caution\n\n";

echo "🎨 SCÉNARIOS DE TEST:\n";
echo "=====================\n";
echo "• LOC-TEST-001: Location parfaite (à temps, sans dégâts)\n";
echo "• LOC-TEST-002: Location avec légers problèmes (2j retard, 45€ dégâts)\n";
echo "• LOC-TEST-003: Location problématique (3j retard, 120€ dégâts)\n\n";

echo "✅ Système prêt pour les tests d'inspection en conditions réelles !\n";
