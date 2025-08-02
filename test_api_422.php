<?php
require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\CartLocation;
use App\Models\CartItemLocation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\CartItemLocationController;

echo "=== Test API 422 Response ===\n\n";

// Trouver un panier avec des items
$user = User::first();
$cartLocation = $user->activeCartLocation;
$item = $cartLocation->items->first();

echo "📦 Item: {$item->product_name}\n";
echo "🔢 Item ID: {$item->id}\n";
echo "⏱️ Contraintes: {$item->product->min_rental_days}-{$item->product->max_rental_days} jours\n\n";

// Créer une requête avec une période trop courte
$startDate = Carbon::now()->addDays(2)->format('Y-m-d');
$endDate = Carbon::now()->addDays(3)->format('Y-m-d'); // Seulement 1 jour, mais minimum 7

echo "🧪 Test avec période trop courte:\n";
echo "Start: $startDate\n";
echo "End: $endDate\n";
echo "Durée: 1 jour (minimum requis: {$item->product->min_rental_days})\n\n";

// Simuler la requête HTTP
$request = new Request([
    'start_date' => $startDate,
    'end_date' => $endDate
]);

// Créer une instance du contrôleur
$controller = new CartItemLocationController();

// Simuler l'authentification
auth()->login($user);

echo "🔄 Appel de l'API...\n";

try {
    $response = $controller->updateDates($request, $item);
    
    echo "📊 Statut HTTP: " . $response->status() . "\n";
    echo "📝 Contenu de la réponse:\n";
    
    $content = $response->getData(true);
    echo json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
    
} catch (\Illuminate\Validation\ValidationException $e) {
    echo "❌ Erreur de validation capturée:\n";
    echo "Messages: " . json_encode($e->errors(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
    echo "Status: 422\n";
} catch (\Exception $e) {
    echo "❌ Autre erreur: " . $e->getMessage() . "\n";
    echo "Type: " . get_class($e) . "\n";
}

echo "\n=== Test terminé ===\n";
