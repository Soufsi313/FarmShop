<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Test de l'API Cart ===\n";

try {
    // Simuler une authentification
    $user = App\Models\User::first();
    
    if (!$user) {
        echo "Aucun utilisateur trouvé\n";
        exit;
    }
    
    // Simuler la requête comme dans le contrôleur
    Auth::login($user);
    
    echo "Utilisateur connecté : {$user->email}\n";
    
    // Tester la méthode index du CartController
    $controller = new App\Http\Controllers\CartController();
    
    // Simuler la requête
    $request = new \Illuminate\Http\Request();
    
    echo "Appel de la méthode index...\n";
    $response = $controller->index();
    
    echo "Statut de la réponse : " . $response->getStatusCode() . "\n";
    
    $data = $response->getData(true);
    
    if ($data['success']) {
        echo "✅ API réussie !\n";
        echo "Message : " . $data['message'] . "\n";
        echo "Nombre d'articles dans le panier : " . count($data['data']['items']) . "\n";
        echo "Résumé du panier :\n";
        print_r($data['data']['summary']);
    } else {
        echo "❌ Échec de l'API\n";
        print_r($data);
    }
    
} catch (Exception $e) {
    echo "❌ Erreur : " . $e->getMessage() . "\n";
    echo "Fichier : " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "=== Fin du test API ===\n";
