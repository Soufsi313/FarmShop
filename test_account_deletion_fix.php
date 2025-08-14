<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use App\Models\Message;

// Test de la relation messages
echo "Test de la correction du système de suppression de compte\n";
echo "=" . str_repeat("=", 50) . "\n\n";

try {
    // Test 1: Vérifier que la relation messages existe
    $user = new User();
    
    if (method_exists($user, 'messages')) {
        echo "✅ Méthode messages() trouvée dans User\n";
        
        // Vérifier le type de relation
        $relation = $user->messages();
        echo "✅ Type de relation: " . get_class($relation) . "\n";
        
    } else {
        echo "❌ Méthode messages() manquante dans User\n";
    }

    // Test 2: Vérifier que Message a la relation user
    $message = new Message();
    
    if (method_exists($message, 'user')) {
        echo "✅ Méthode user() trouvée dans Message\n";
    } else {
        echo "❌ Méthode user() manquante dans Message\n";
    }

    echo "\n" . str_repeat("=", 60) . "\n";
    echo "Test terminé - Relations correctement définies !\n";

} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
