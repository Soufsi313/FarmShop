<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\OrderLocation;
use Illuminate\Support\Facades\DB;

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Diagnostic du problème d'inspection ===\n\n";

// Récupérer la commande
$order = OrderLocation::with(['items'])->find(26);
if (!$order) {
    echo "❌ Commande non trouvée.\n";
    exit(1);
}

echo "📦 Commande: {$order->order_number} (ID: {$order->id})\n";
echo "   - Statut: {$order->status}\n";
echo "   - Articles: {$order->items->count()}\n\n";

// Simuler les données du formulaire
$formData = [
    'return_notes' => 'Test d\'inspection automatique',
    'items' => [],
    'late_fee' => 0
];

foreach ($order->items as $item) {
    $formData['items'][] = [
        'id' => $item->id,
        'condition' => 'excellent',
        'notes' => 'Bon état général',
        'damage_fee' => 0
    ];
}

echo "📋 Données de test du formulaire:\n";
echo "   - Notes générales: {$formData['return_notes']}\n";
echo "   - Frais de retard: {$formData['late_fee']}€\n";
echo "   - Nombre d'articles: " . count($formData['items']) . "\n\n";

foreach ($formData['items'] as $index => $itemData) {
    echo "   Article " . ($index + 1) . ":\n";
    echo "     - ID: {$itemData['id']}\n";
    echo "     - État: {$itemData['condition']}\n";
    echo "     - Notes: {$itemData['notes']}\n";
    echo "     - Frais de dégâts: {$itemData['damage_fee']}€\n\n";
}

// Test de la validation Laravel
echo "=== Test de validation ===\n";
$validator = \Illuminate\Support\Facades\Validator::make($formData, [
    'return_notes' => 'nullable|string|max:1000',
    'items' => 'required|array',
    'items.*.id' => 'required|exists:order_item_locations,id',
    'items.*.condition' => 'required|in:excellent,good,fair,poor',
    'items.*.notes' => 'nullable|string|max:500',
    'items.*.damage_fee' => 'nullable|numeric|min:0|max:9999.99',
    'late_fee' => 'nullable|numeric|min:0|max:9999.99'
]);

if ($validator->fails()) {
    echo "❌ Erreurs de validation:\n";
    foreach ($validator->errors()->all() as $error) {
        echo "   - {$error}\n";
    }
} else {
    echo "✅ Validation réussie\n";
}

echo "\n=== Test des méthodes du modèle ===\n";

// Vérifier si la commande peut être retournée
echo "   - Peut être retournée: " . ($order->can_be_returned ? 'Oui' : 'Non') . "\n";
echo "   - Statut actuel: {$order->status}\n";
echo "   - Statuts acceptés: 'active' ou 'pending_inspection'\n";

// Test manuel de la méthode markAsReturned
try {
    echo "\n=== Simulation de markAsReturned ===\n";
    
    // Calculer les frais de retard
    $lateFee = $formData['late_fee'] ?? 0;
    if ($order->is_overdue && $lateFee === 0) {
        $daysLate = now()->diffInDays($order->rental_end_date);
        $lateFee = $daysLate * 10;
        echo "   - Frais de retard calculés: {$lateFee}€ ({$daysLate} jours)\n";
    }
    
    // Tester l'appel à la méthode markAsReturned
    $result = $order->markAsReturned($formData['return_notes']);
    
    if ($result) {
        echo "✅ markAsReturned a réussi\n";
        echo "   - Nouveau statut: {$order->fresh()->status}\n";
    } else {
        echo "❌ markAsReturned a échoué\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Erreur lors de markAsReturned: {$e->getMessage()}\n";
    echo "   - Ligne: {$e->getLine()}\n";
    echo "   - Fichier: {$e->getFile()}\n";
}

echo "\n✅ Diagnostic terminé !\n";
