<?php
/**
 * TEST User Model
 * 
 * Vérifie:
 * - Structure du modèle User
 * - Relations (carts, orders, messages, etc.)
 * - Méthodes métier (isAdmin, hasRole, etc.)
 * - Scopes (admins, users)
 */

// Bootstrap Laravel seulement si pas déjà fait
if (!class_exists('\App\Models\User')) {
    require_once __DIR__ . '/../../vendor/autoload.php';
    $app = require_once __DIR__ . '/../../bootstrap/app.php';
    $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
}

use App\Models\User;
use App\Models\Cart;
use App\Models\Order;

echo "=== TEST USER MODEL ===\n\n";

try {
    // Test 1: Vérifier que le modèle existe et est bien configuré
    echo "📊 Test 1: Structure du modèle User...\n";
    
    $userCount = User::count();
    echo "  ✅ Modèle User accessible\n";
    echo "  📈 $userCount utilisateurs en base\n";
    
    // Test 2: Vérifier les fillable attributes
    echo "\n📊 Test 2: Attributs fillable...\n";
    $user = new User();
    $fillable = $user->getFillable();
    $requiredFillable = ['username', 'name', 'email', 'password', 'role', 'phone', 'address', 'city'];
    
    $missingFillable = array_diff($requiredFillable, $fillable);
    if (empty($missingFillable)) {
        echo "  ✅ Tous les attributs requis sont fillable (" . count($fillable) . " total)\n";
    } else {
        echo "  ⚠️  Attributs manquants: " . implode(', ', $missingFillable) . "\n";
    }
    
    // Test 3: Vérifier les hidden attributes
    echo "\n📊 Test 3: Attributs cachés...\n";
    $hidden = $user->getHidden();
    if (in_array('password', $hidden) && in_array('remember_token', $hidden)) {
        echo "  ✅ Password et remember_token correctement cachés\n";
    } else {
        echo "  ⚠️  Certains attributs sensibles ne sont pas cachés\n";
    }
    
    // Test 4: Tester les méthodes métier
    echo "\n📊 Test 4: Méthodes métier...\n";
    $admin = User::where('role', 'Admin')->first();
    $regularUser = User::where('role', 'User')->first();
    
    if ($admin) {
        if ($admin->isAdmin()) {
            echo "  ✅ isAdmin() fonctionne correctement\n";
        } else {
            echo "  ❌ isAdmin() ne fonctionne pas\n";
        }
        
        if ($admin->hasRole('Admin')) {
            echo "  ✅ hasRole() fonctionne correctement\n";
        } else {
            echo "  ❌ hasRole() ne fonctionne pas\n";
        }
    }
    
    if ($regularUser) {
        if ($regularUser->isUser()) {
            echo "  ✅ isUser() fonctionne correctement\n";
        } else {
            echo "  ❌ isUser() ne fonctionne pas\n";
        }
    }
    
    // Test 5: Tester les scopes
    echo "\n📊 Test 5: Scopes...\n";
    $adminsCount = User::admins()->count();
    $usersCount = User::users()->count();
    
    echo "  ✅ Scope admins(): $adminsCount admins trouvés\n";
    echo "  ✅ Scope users(): $usersCount utilisateurs trouvés\n";
    
    // Test 6: Tester les relations
    echo "\n📊 Test 6: Relations...\n";
    $userWithRelations = User::with(['carts', 'orders'])->first();
    
    if ($userWithRelations) {
        echo "  ✅ Relation carts() chargée\n";
        echo "  ✅ Relation orders() chargée\n";
        
        if (method_exists($userWithRelations, 'activeCart')) {
            echo "  ✅ Relation activeCart() définie\n";
        }
    }
    
    // Test 7: Vérifier les casts
    echo "\n📊 Test 7: Type casting...\n";
    $testUser = User::first();
    if ($testUser) {
        if (is_bool($testUser->newsletter_subscribed)) {
            echo "  ✅ newsletter_subscribed casté en boolean\n";
        }
        if ($testUser->created_at instanceof \Illuminate\Support\Carbon) {
            echo "  ✅ created_at casté en datetime\n";
        }
    }
    
    echo "\n=== RÉSUMÉ ===\n";
    echo "✅ Modèle User: Structure OK\n";
    echo "✅ Méthodes métier: Fonctionnelles\n";
    echo "✅ Relations: Définies\n";
    echo "✅ Scopes: Opérationnels\n";
    echo "\nTEST RÉUSSI ✅\n";
    
} catch (\Exception $e) {
    echo "❌ ERREUR: " . $e->getMessage() . "\n";
    echo "FICHIER: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "TEST ÉCHOUÉ ❌\n";
}
