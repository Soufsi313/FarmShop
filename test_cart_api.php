<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Http\Controllers\CartController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

echo "=== Test API du panier ===\n\n";

// Récupérer un utilisateur et le connecter
$user = User::first();
if (!$user) {
    echo "Aucun utilisateur trouvé\n";
    exit;
}

Auth::login($user);
echo "Connecté en tant que: {$user->email}\n\n";

// Créer une instance du contrôleur
$controller = new CartController();

// Appeler la méthode index
$response = $controller->index();
$data = json_decode($response->getContent(), true);

echo "Réponse de l'API:\n";
echo "Success: " . ($data['success'] ? 'true' : 'false') . "\n";
echo "Message: " . $data['message'] . "\n\n";

if (isset($data['data']['items'])) {
    echo "Items du panier:\n";
    foreach ($data['data']['items'] as $item) {
        echo "- {$item['product_name']}\n";
        echo "  product_image: {$item['product_image']}\n";
        echo "  product_slug: {$item['product_slug']}\n";
        echo "  is_available: " . ($item['is_available'] ? 'true' : 'false') . "\n";
        
        if (isset($item['product'])) {
            echo "  product->image_url: {$item['product']['image_url']}\n";
        }
        echo "\n";
    }
}

echo "=== Test terminé ===\n";
