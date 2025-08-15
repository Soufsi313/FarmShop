<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Models\User;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔧 VALIDATION SYSTÈME SUPPRESSION COMPTE - POST-FIX\n";
echo "==================================================\n";

try {
    // Vérifier les routes de redirection dans les erreurs
    echo "📍 TEST ROUTES DE REDIRECTION:\n";
    
    $routes = ['home', 'users.profile'];
    
    foreach ($routes as $routeName) {
        try {
            $url = route($routeName);
            echo "✅ Route '{$routeName}': {$url}\n";
        } catch (\Exception $e) {
            echo "❌ Route '{$routeName}': ERREUR - {$e->getMessage()}\n";
        }
    }
    
    echo "\n📧 UTILISATEUR TEST:\n";
    $testUser = User::where('email', 'saurouk313@gmail.com')->first();
    
    if ($testUser) {
        echo "✅ Utilisateur: {$testUser->name} ({$testUser->email})\n";
        echo "   - Statut: " . ($testUser->deleted_at ? 'SUPPRIMÉ' : 'ACTIF') . "\n";
        
        if (!$testUser->deleted_at) {
            echo "\n🔗 GÉNÉRATION LIEN TEST DE SUPPRESSION:\n";
            
            try {
                // Simuler la génération d'URL signée (comme dans le vrai système)
                $url = URL::temporarySignedRoute(
                    'account.confirm-deletion',
                    now()->addMinutes(60),
                    ['user' => $testUser->id]
                );
                echo "✅ Lien de confirmation généré:\n";
                echo "   {$url}\n";
                echo "\n⚠️  Ce lien expire dans 60 minutes.\n";
                
            } catch (\Exception $e) {
                echo "❌ Erreur génération lien: {$e->getMessage()}\n";
            }
        }
    } else {
        echo "❌ Utilisateur de test non trouvé\n";
    }
    
    echo "\n✅ CORRECTIONS APPLIQUÉES:\n";
    echo "- UserController::confirmSelfDelete() → route('home') au lieu de dashboard\n";
    echo "- account-deletion-requested.blade.php → route('users.profile')\n";
    echo "\n🎯 Le système devrait maintenant fonctionner sans erreur de route.\n";

} catch (\Exception $e) {
    echo "❌ Erreur lors de la validation: " . $e->getMessage() . "\n";
}
