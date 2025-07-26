<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Order;
use App\Models\User;
use App\Http\Controllers\Api\OrderStatusController;
use Illuminate\Http\Request;

// Simuler une requête authentifiée
$user = User::first();
auth('web')->login($user);

$order = Order::find(110);
$controller = new OrderStatusController();

// Créer une requête simulée
$request = Request::create('/api/orders/110/status', 'GET');
$request->setUserResolver(function () use ($user) {
    return $user;
});

try {
    $response = $controller->getStatus($order);
    $content = $response->getContent();
    $data = json_decode($content, true);
    
    echo "✅ API Response:\n";
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
    
    echo "\n🔍 Points clés:\n";
    echo "- Statut de la réponse: {$data['status']}\n";
    echo "- Statut de la commande: {$data['order']['status']}\n";
    echo "- Peut être annulée: " . ($data['order']['can_be_cancelled'] ? 'Oui' : 'Non') . "\n";
    
} catch (Exception $e) {
    echo "❌ Erreur API: {$e->getMessage()}\n";
}
