<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 ANALYSE COMPLÈTE SYSTÈME DE PRÉAUTORISATION\n";
echo "==============================================\n\n";

echo "📋 1. STRUCTURE DATABASE - SUPPORT PRÉAUTORISATION\n";
echo "--------------------------------------------------\n";

// Vérifier les champs nécessaires pour la préautorisation
$tables = ['order_locations', 'products'];
foreach ($tables as $table) {
    echo "📊 Table: {$table}\n";
    try {
        $columns = DB::select("DESCRIBE {$table}");
        $importantFields = [];
        
        foreach ($columns as $column) {
            $field = strtolower($column->Field);
            if (strpos($field, 'deposit') !== false || 
                strpos($field, 'caution') !== false ||
                strpos($field, 'rental_deposit') !== false ||
                strpos($field, 'authorization') !== false ||
                strpos($field, 'preauth') !== false) {
                $importantFields[] = $column->Field . ' (' . $column->Type . ')';
            }
        }
        
        if (!empty($importantFields)) {
            echo "  ✅ Champs trouvés: " . implode(', ', $importantFields) . "\n";
        } else {
            echo "  ❌ Aucun champ de caution trouvé\n";
        }
    } catch (Exception $e) {
        echo "  ❌ Erreur: " . $e->getMessage() . "\n";
    }
    echo "\n";
}

echo "💳 2. STRIPE PAYMENT INTENT - CONFIGURATION\n";
echo "------------------------------------------\n";

// Analyser le code de création PaymentIntent
$stripeServiceFile = 'app/Services/StripeService.php';
if (file_exists($stripeServiceFile)) {
    $content = file_get_contents($stripeServiceFile);
    
    echo "🔍 Analyse de createPaymentIntentForRental():\n";
    
    // Chercher la méthode
    if (preg_match('/function createPaymentIntentForRental.*?\{(.*?)\n    \}/s', $content, $matches)) {
        $method = $matches[1];
        
        // Analyser le montant
        if (strpos($method, 'total_amount') !== false) {
            echo "  ✅ Utilise total_amount pour le payment intent\n";
        }
        
        if (strpos($method, 'deposit_amount') !== false) {
            echo "  ✅ Référence deposit_amount trouvée\n";
        }
        
        // Vérifier si c'est une préautorisation ou un paiement direct
        if (strpos($method, 'capture_method') !== false) {
            echo "  ✅ capture_method configuré (préautorisation possible)\n";
        } else {
            echo "  ❌ Pas de capture_method -> PAIEMENT DIRECT\n";
        }
        
        if (strpos($method, 'confirm') !== false) {
            echo "  ⚠️ Confirmation automatique détectée\n";
        }
    }
}

echo "\n🏦 3. LOGIQUE MÉTIER - PAIEMENT LOCATION\n";
echo "---------------------------------------\n";

// Analyser une vraie commande
$location = DB::table('order_locations')
    ->where('payment_status', 'paid')
    ->orderBy('created_at', 'desc')
    ->first();

if ($location) {
    echo "📋 Exemple commande #{$location->order_number}:\n";
    echo "  💰 Total amount: " . number_format($location->total_amount, 2) . "€\n";
    echo "  🏦 Deposit amount: " . number_format($location->deposit_amount ?? 0, 2) . "€\n";
    
    // Calculer ce qui DEVRAIT être payé
    $expectedTotal = ($location->total_amount ?? 0) + ($location->deposit_amount ?? 0);
    echo "  📊 Total théorique: " . number_format($expectedTotal, 2) . "€\n";
    
    echo "\n🔍 ANALYSE DU PROBLÈME:\n";
    if ($location->deposit_amount == 0) {
        echo "  ❌ PROBLÈME: Pas de caution définie\n";
        echo "  → Les produits n'ont pas de rental_deposit\n";
        echo "  → Seul le prix location est payé\n";
    } else {
        echo "  ✅ Caution définie: " . number_format($location->deposit_amount, 2) . "€\n";
        echo "  💳 Payment Intent créé pour: " . number_format($location->total_amount, 2) . "€\n";
        
        if ($location->total_amount == $expectedTotal) {
            echo "  ❌ DÉBIT IMMÉDIAT: Location + Caution payées ensemble\n";
        } else {
            echo "  ✅ PRÉAUTORISATION: Seule la location payée\n";
        }
    }
}

echo "\n📝 4. INTERFACE UTILISATEUR - MESSAGES\n";
echo "------------------------------------\n";

$checkoutFile = 'resources/views/checkout-rental/index.blade.php';
if (file_exists($checkoutFile)) {
    $content = file_get_contents($checkoutFile);
    
    if (strpos($content, 'bloquée') !== false) {
        echo "  ✅ Message 'caution bloquée' trouvé dans checkout\n";
    }
    
    if (strpos($content, 'préautorisation') !== false) {
        echo "  ✅ Référence préautorisation trouvée\n";
    } else {
        echo "  ❌ Pas de mention de préautorisation\n";
    }
}

$paymentFile = 'resources/views/payment/stripe-rental.blade.php';
if (file_exists($paymentFile)) {
    $content = file_get_contents($paymentFile);
    
    if (strpos($content, 'bloquée temporairement') !== false) {
        echo "  ✅ Message 'bloquée temporairement' trouvé\n";
    }
    
    if (strpos($content, 'Total à payer') !== false) {
        echo "  ⚠️ 'Total à payer' affiché (peut être trompeur si préauth)\n";
    }
}

echo "\n🎯 5. VERDICT FINAL\n";
echo "------------------\n";

echo "📊 STATUT ACTUEL:\n";
if (!isset($location) || $location->deposit_amount == 0) {
    echo "❌ AUCUNE CAUTION: Système incomplet\n";
    echo "   → Pas de rental_deposit sur les produits\n";
    echo "   → Pas de préautorisation ni débit\n";
    echo "   → Messages trompeurs sur 'caution bloquée'\n";
} else {
    echo "⚠️ DÉBIT IMMÉDIAT: Pas de préautorisation\n";
    echo "   → Location + Caution payées ensemble\n";
    echo "   → Remboursement après inspection\n";
    echo "   → Messages corrects mais système suboptimal\n";
}

echo "\n💡 RECOMMANDATIONS:\n";
echo "1. Ajouter rental_deposit aux produits\n";
echo "2. Implémenter vraie préautorisation Stripe\n";
echo "3. Corriger les messages utilisateur\n";
echo "4. Tester le workflow complet\n";
