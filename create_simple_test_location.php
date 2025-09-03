<?php

require_once __DIR__ . '/vendor/autoload.php';

// Booter Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\OrderLocation;
use App\Models\OrderItemLocation;
use Carbon\Carbon;

echo "🔧 Création d'une location test en copiant une existante\n";

try {
    // Prendre une location existante comme modèle
    $model = OrderLocation::first();
    if (!$model) {
        echo "❌ Aucune location existante trouvée\n";
        exit(1);
    }
    
    echo "📋 Modèle basé sur: {$model->order_number}\n";
    
    // Créer des dates dans le passé pour simulation
    $startDate = Carbon::now()->subDays(5); // Commencé il y a 5 jours
    $endDate = Carbon::now()->subDays(2);   // Fini il y a 2 jours
    $rentalDays = $startDate->diffInDays($endDate) + 1;
    
    $orderNumber = 'LOC-TEST-' . now()->format('YmdHis');
    
    // Copier tous les attributs et modifier seulement ce qu'il faut
    $attributes = $model->toArray();
    unset($attributes['id']);
    unset($attributes['created_at']);
    unset($attributes['updated_at']);
    unset($attributes['deleted_at']);
    
    // Modifier les valeurs spécifiques
    $attributes['order_number'] = $orderNumber;
    $attributes['user_id'] = 1; // Utilisateur 1
    $attributes['status'] = 'confirmed';
    $attributes['payment_status'] = 'deposit_paid';
    $attributes['start_date'] = $startDate;
    $attributes['end_date'] = $endDate;
    $attributes['rental_days'] = $rentalDays;
    $attributes['notes'] = 'Location test pour debug workflow';
    $attributes['invoice_number'] = null;
    $attributes['invoice_generated_at'] = null;
    $attributes['confirmed_at'] = null;
    $attributes['started_at'] = null;
    $attributes['completed_at'] = null;
    $attributes['closed_at'] = null;
    $attributes['cancelled_at'] = null;
    
    $orderLocation = OrderLocation::create($attributes);
    
    echo "✅ Location créée: {$orderLocation->order_number}\n";
    echo "🆔 ID: {$orderLocation->id}\n";
    echo "📅 Dates: {$startDate->format('Y-m-d H:i')} → {$endDate->format('Y-m-d H:i')}\n";
    echo "🔄 Statut initial: {$orderLocation->status}\n";
    
    // Copier les items de la location modèle
    $modelItems = $model->items;
    foreach ($modelItems as $item) {
        $itemAttributes = $item->toArray();
        unset($itemAttributes['id']);
        unset($itemAttributes['created_at']);
        unset($itemAttributes['updated_at']);
        $itemAttributes['order_location_id'] = $orderLocation->id;
        
        OrderItemLocation::create($itemAttributes);
    }
    
    echo "📦 {$modelItems->count()} item(s) copié(s)\n";
    
    // Simuler le workflow : confirmed → active → completed
    echo "\n🔄 Passage à 'active'...\n";
    $orderLocation->update(['status' => 'active', 'started_at' => $startDate]);
    $orderLocation->refresh();
    echo "✅ Statut: {$orderLocation->status}\n";
    
    echo "\n🔄 Passage à 'completed' (retour matériel signalé)...\n";
    $orderLocation->update([
        'status' => 'completed',
        'completed_at' => $endDate->copy()->addHours(2),
        'actual_return_date' => $endDate->copy()->addHours(2),
    ]);
    $orderLocation->refresh();
    echo "✅ Statut: {$orderLocation->status}\n";
    
    // Attendre un peu et vérifier
    sleep(2);
    $orderLocation->refresh();
    echo "🔍 Statut après refresh: {$orderLocation->status}\n";
    
    if ($orderLocation->status === 'completed') {
        echo "✅ SUCCÈS: Location reste au statut 'completed'\n";
        echo "🔧 Prête pour test de clôture manuelle\n";
        echo "🌐 URL admin: /admin/rental-orders ou /rental-orders\n";
        
        // Vérifier si elle peut générer une facture
        if ($orderLocation->canGenerateInvoice()) {
            echo "📄 ✅ Peut générer une facture\n";
        } else {
            echo "📄 ❌ Ne peut pas générer une facture\n";
        }
        
    } else {
        echo "❌ ATTENTION: Le statut a changé automatiquement vers: {$orderLocation->status}\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "📍 Ligne: " . $e->getLine() . "\n";
    echo "📂 Fichier: " . $e->getFile() . "\n";
}

echo "\n🏁 Script terminé\n";
