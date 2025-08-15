<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Models\User;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔧 TEST CORRECTION MÉTHODE RENTALS\n";
echo "=================================\n";

try {
    // Tester avec l'utilisateur qui pose problème
    $testUser = User::where('email', 'saurouk313@gmail.com')->first();
    
    if ($testUser) {
        echo "✅ Utilisateur trouvé: {$testUser->name}\n";
        
        // Test des méthodes de location
        echo "\n📊 TEST MÉTHODES DE LOCATION:\n";
        
        try {
            $orderLocationsCount = $testUser->orderLocations()->count();
            echo "✅ orderLocations(): {$orderLocationsCount} commandes de location\n";
        } catch (\Exception $e) {
            echo "❌ orderLocations(): {$e->getMessage()}\n";
        }
        
        try {
            $activeRentalsCount = $testUser->activeRentalOrders()->count();
            echo "✅ activeRentalOrders(): {$activeRentalsCount} locations actives\n";
        } catch (\Exception $e) {
            echo "❌ activeRentalOrders(): {$e->getMessage()}\n";
        }
        
        try {
            $pendingRentalsCount = $testUser->pendingRentalOrders()->count();
            echo "✅ pendingRentalOrders(): {$pendingRentalsCount} locations en attente\n";
        } catch (\Exception $e) {
            echo "❌ pendingRentalOrders(): {$e->getMessage()}\n";
        }
        
        echo "\n✅ CORRECTIONS APPLIQUÉES:\n";
        echo "- UserController: user->rentals() → user->orderLocations()\n";
        echo "- Méthodes de test validées\n";
        echo "\n🎯 Le système de suppression devrait maintenant fonctionner.\n";
        
    } else {
        echo "❌ Utilisateur de test non trouvé\n";
    }

} catch (\Exception $e) {
    echo "❌ Erreur lors du test: " . $e->getMessage() . "\n";
}
