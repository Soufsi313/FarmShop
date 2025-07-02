<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\OrderLocation;
use App\Models\User;

echo "🧪 Test de la correction pour les commandes sans articles\n";
echo "====================================================\n\n";

// Trouver une commande en pending_inspection
$orderLocation = OrderLocation::where('status', 'pending_inspection')
    ->with(['user', 'items'])
    ->first();

if (!$orderLocation) {
    echo "❌ Aucune commande en 'pending_inspection' trouvée.\n";
    echo "Création d'une commande test sans articles...\n\n";
    
    // Créer une commande test sans articles
    $user = User::first();
    if (!$user) {
        echo "❌ Aucun utilisateur trouvé. Impossible de créer une commande test.\n";
        exit(1);
    }
    
    $orderLocation = OrderLocation::create([
        'user_id' => $user->id,
        'order_number' => 'LOC-' . date('Ymd') . '-TEST',
        'status' => 'pending_inspection',
        'rental_start_date' => now()->subDays(2),
        'rental_end_date' => now()->subDay(),
        'pickup_date' => now()->subDays(2),
        'client_return_date' => now(),
        'total_amount' => 50.00,
        'deposit_amount' => 25.00,
        'shipping_address' => json_encode([
            'street' => '123 Test Street',
            'city' => 'Test City',
            'postal_code' => '12345',
            'country' => 'France'
        ])
    ]);
    
    echo "✅ Commande test créée : {$orderLocation->order_number}\n\n";
}

echo "📋 Détails de la commande de test :\n";
echo "- Numéro : {$orderLocation->order_number}\n";
echo "- Client : {$orderLocation->user->name}\n";
echo "- Statut : {$orderLocation->status}\n";
echo "- Nombre d'articles : " . $orderLocation->items->count() . "\n";
echo "- Caution : {$orderLocation->deposit_amount}€\n\n";

if ($orderLocation->items->count() === 0) {
    echo "✅ Cette commande n'a pas d'articles - parfait pour tester la correction !\n\n";
    
    echo "🔧 Test de la logique de validation :\n";
    echo "- Validation devrait accepter les champs sans 'items' requis\n";
    echo "- Le champ 'general_damage_fee' devrait être utilisable\n\n";
    
    echo "🌐 URL d'inspection : " . route('admin.locations.return.show', $orderLocation) . "\n\n";
    
    echo "📝 Pour tester manuellement :\n";
    echo "1. Aller sur l'URL d'inspection ci-dessus\n";
    echo "2. Remplir les notes si désiré\n";
    echo "3. Optionnellement, saisir des frais généraux\n";
    echo "4. Cliquer sur 'Valider le retour'\n";
    echo "5. La validation devrait maintenant fonctionner sans erreur 'items required'\n\n";
} else {
    echo "ℹ️ Cette commande a des articles. Pour un test complet, créez une commande sans articles.\n\n";
}

echo "🎯 Correction appliquée :\n";
echo "- Validation conditionnelle dans OrderLocationAdminController::markAsReturned()\n";
echo "- Champ 'items' requis seulement si la commande a des articles\n";
echo "- Nouveau champ 'general_damage_fee' pour les commandes sans articles\n";
echo "- Vue mise à jour pour afficher la section appropriée\n";
echo "- JavaScript mis à jour pour inclure les frais généraux\n\n";

echo "✅ Test terminé. La correction devrait résoudre l'erreur 'The items field is required'.\n";
