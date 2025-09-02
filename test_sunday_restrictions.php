<?php

/**
 * Test des restrictions dimanche pour le système de location
 * Ce script teste les nouvelles fonctionnalités :
 * - Exclusion des dimanches dans le calcul de durée
 * - Messages d'erreur explicites
 * - Ajustement automatique des dates
 */

require_once 'vendor/autoload.php';
require_once 'bootstrap/app.php';

use App\Models\Product;
use Carbon\Carbon;
use App\Rules\RentalDateValidation;

echo "🧪 Test des restrictions dimanche - Système de location\n";
echo "=" . str_repeat("=", 60) . "\n\n";

// Récupérer un produit de location pour les tests
$product = Product::where('type', 'rental')->first();

if (!$product) {
    echo "❌ Aucun produit de location trouvé dans la base de données\n";
    exit(1);
}

echo "📦 Produit testé : {$product->name}\n";
echo "🕒 Contraintes : Min {$product->min_rental_days} jour(s)";
if ($product->max_rental_days) {
    echo ", Max {$product->max_rental_days} jour(s)";
}
echo "\n\n";

// Test 1 : Vérification qu'un dimanche est détecté comme non disponible
echo "Test 1: Vérification du dimanche comme jour non disponible\n";
echo "-" . str_repeat("-", 50) . "\n";

// Trouver le prochain dimanche
$nextSunday = Carbon::now()->next(Carbon::SUNDAY);
$isAvailable = $product->isDayAvailable($nextSunday);

echo "📅 Prochain dimanche : {$nextSunday->format('d/m/Y')}\n";
echo "✅ Disponible : " . ($isAvailable ? "OUI" : "NON") . "\n";
if (!$isAvailable) {
    echo "✅ Test réussi : Les dimanches sont bien bloqués\n";
} else {
    echo "❌ Test échoué : Les dimanches devraient être bloqués\n";
}
echo "\n";

// Test 2 : Calcul de durée excluant les dimanches
echo "Test 2: Calcul de durée excluant les dimanches\n";
echo "-" . str_repeat("-", 50) . "\n";

// Test du lundi au lundi (incluant un dimanche)
$startDate = Carbon::now()->next(Carbon::MONDAY);
$endDate = $startDate->copy()->addWeek(); // Lundi suivant

$totalDays = $startDate->diffInDays($endDate) + 1;
$businessDays = $product->calculateRentalDuration($startDate, $endDate);

echo "📅 Période : {$startDate->format('d/m/Y')} → {$endDate->format('d/m/Y')}\n";
echo "📊 Jours calendaires : {$totalDays}\n";
echo "💼 Jours ouvrés (sans dimanche) : {$businessDays}\n";
echo "🚫 Dimanches exclus : " . ($totalDays - $businessDays) . "\n";

if ($businessDays == 6 && ($totalDays - $businessDays) == 1) {
    echo "✅ Test réussi : Le dimanche est bien exclu du calcul\n";
} else {
    echo "❌ Test échoué : Le calcul ne semble pas correct\n";
}
echo "\n";

// Test 3 : Ajustement automatique d'une date dimanche
echo "Test 3: Ajustement automatique d'une date dimanche\n";
echo "-" . str_repeat("-", 50) . "\n";

$sundayDate = Carbon::now()->next(Carbon::SUNDAY);
$adjustedDate = $product->adjustDateForBusinessDays($sundayDate);

echo "📅 Date dimanche : {$sundayDate->format('d/m/Y l')}\n";
echo "📅 Date ajustée : {$adjustedDate->format('d/m/Y l')}\n";

if ($adjustedDate->isMonday() && $adjustedDate->gt($sundayDate)) {
    echo "✅ Test réussi : Le dimanche est bien décalé au lundi\n";
} else {
    echo "❌ Test échoué : L'ajustement ne fonctionne pas correctement\n";
}
echo "\n";

// Test 4 : Validation avec messages explicites
echo "Test 4: Messages de validation explicites\n";
echo "-" . str_repeat("-", 50) . "\n";

// Test de validation d'une date dimanche
$validator = new RentalDateValidation($product, null, null, 'start');

$errorMessage = '';
try {
    $validator->validate('start_date', $sundayDate->format('Y-m-d'), function($message) use (&$errorMessage) {
        $errorMessage = $message;
    });
} catch (Exception $e) {
    // La validation peut lever une exception ou appeler le callback
}

echo "📅 Validation date dimanche : {$sundayDate->format('d/m/Y')}\n";
echo "💬 Message d'erreur : {$errorMessage}\n";

if (strpos($errorMessage, 'dimanche') !== false || strpos($errorMessage, 'fermée') !== false) {
    echo "✅ Test réussi : Message explicite pour dimanche\n";
} else {
    echo "❌ Test échoué : Message pas assez explicite\n";
}
echo "\n";

// Test 5 : Validation de durée avec dimanches exclus
echo "Test 5: Validation de durée avec exclusion dimanches\n";
echo "-" . str_repeat("-", 50) . "\n";

// Créer une période trop courte en jours ouvrés mais qui semble correcte en jours calendaires
$start = Carbon::now()->next(Carbon::SATURDAY);
$end = $start->copy()->addDay(); // Dimanche, donc 0 jour ouvré

$durationValidator = new RentalDateValidation($product, $start, $end, 'end');
$durationError = '';

try {
    $durationValidator->validate('end_date', $end->format('Y-m-d'), function($message) use (&$durationError) {
        $durationError = $message;
    });
} catch (Exception $e) {
    // 
}

echo "📅 Période : {$start->format('d/m/Y l')} → {$end->format('d/m/Y l')}\n";
echo "💬 Message validation : {$durationError}\n";

if (strpos($durationError, 'ouvrés') !== false) {
    echo "✅ Test réussi : Validation prend en compte les jours ouvrés\n";
} else {
    echo "❌ Test échoué : Validation ne distingue pas jours ouvrés/calendaires\n";
}

echo "\n" . "=" . str_repeat("=", 60) . "\n";
echo "🏁 Tests terminés !\n";
echo "\n💡 Points clés implémentés :\n";
echo "   • Dimanches automatiquement exclus du calcul\n";
echo "   • Messages d'erreur explicites et contextuels\n";
echo "   • Ajustement automatique des dates dimanche\n";
echo "   • Distinction jours ouvrés / jours calendaires\n";
echo "   • Validation robuste avec feedback utilisateur\n";
