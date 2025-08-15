<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Models\User;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔧 TEST SYSTÈME SUPPRESSION DE COMPTE\n";
echo "=====================================\n";

try {
    // Vérifier les routes disponibles
    echo "📍 VÉRIFICATION DES ROUTES:\n";
    
    $routes = ['home', 'users.profile', 'users.self-delete', 'account.confirm-deletion'];
    
    foreach ($routes as $routeName) {
        try {
            $url = route($routeName);
            echo "✅ Route '{$routeName}': {$url}\n";
        } catch (\Exception $e) {
            echo "❌ Route '{$routeName}': ERREUR - {$e->getMessage()}\n";
        }
    }
    
    echo "\n📧 VÉRIFICATION UTILISATEUR TEST:\n";
    $testUser = User::where('email', 'saurouk313@gmail.com')->first();
    
    if ($testUser) {
        echo "✅ Utilisateur trouvé: {$testUser->name} ({$testUser->email})\n";
        echo "   - ID: {$testUser->id}\n";
        echo "   - Rôle: {$testUser->role}\n";
        echo "   - Email vérifié: " . ($testUser->email_verified_at ? 'Oui' : 'Non') . "\n";
        echo "   - Newsletter: " . ($testUser->newsletter_subscribed ? 'Oui' : 'Non') . "\n";
    } else {
        echo "❌ Utilisateur de test non trouvé\n";
    }
    
    echo "\n🔧 ROUTES DE SUPPRESSION DE COMPTE:\n";
    echo "- POST /profile/request-delete → users.request-delete\n";
    echo "- DELETE /profile/self-delete → users.self-delete\n";
    echo "- GET /profile/confirm-delete/{user} → account.confirm-deletion\n";
    
    echo "\n✅ VÉRIFICATION TERMINÉE\n";
    echo "La route 'dashboard' a été remplacée par 'users.profile' dans la vue.\n";

} catch (\Exception $e) {
    echo "❌ Erreur lors de la vérification: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
