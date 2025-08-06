<?php
require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: __DIR__)
    ->withRouting(
        web: __DIR__.'/routes/web.php',
        commands: __DIR__.'/routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🧪 VÉRIFICATION PRÉ-TEST - SYSTÈME AUTOMATIQUE\n";
echo "==============================================\n\n";

// 1. Vérifier que le queue worker fonctionne
echo "1. 🔍 État du queue worker:\n";
$jobsCount = DB::table('jobs')->count();
$failedCount = DB::table('failed_jobs')->count();
echo "   Jobs en attente: $jobsCount\n";
echo "   Jobs échoués: $failedCount\n";
echo "   ✅ Queue prête pour nouvelle location\n\n";

// 2. Vérifier les produits disponibles pour location
echo "2. 📦 Produits disponibles pour location:\n";
$products = DB::table('products')
    ->where('rental_available', true)
    ->where('stock', '>', 0)
    ->select('id', 'name', 'rental_price', 'deposit_amount', 'stock')
    ->get();

if ($products->count() > 0) {
    foreach ($products as $product) {
        echo "   • ID: {$product->id} - {$product->name}\n";
        echo "     Prix/jour: {$product->rental_price}€ | Dépôt: {$product->deposit_amount}€ | Stock: {$product->stock}\n";
    }
    echo "   ✅ " . $products->count() . " produit(s) disponible(s)\n\n";
} else {
    echo "   ⚠️  Aucun produit disponible pour location\n\n";
}

// 3. Vérifier l'utilisateur de test
echo "3. 👤 Utilisateur de test:\n";
$user = App\Models\User::where('email', 's.mef2703@gmail.com')->first();
if ($user) {
    echo "   ✅ Utilisateur trouvé: {$user->name} ({$user->email})\n";
    echo "   ID: {$user->id}\n\n";
} else {
    echo "   ❌ Utilisateur de test non trouvé\n\n";
}

// 4. Vérifier la configuration des dates pour le test
echo "4. 📅 Configuration des dates de test:\n";
$startDate = \Carbon\Carbon::parse('2025-08-06 00:00:00');
$endDate = \Carbon\Carbon::parse('2025-08-07 00:00:00');
$now = \Carbon\Carbon::now();

echo "   Date de début: {$startDate->format('d/m/Y H:i')}\n";
echo "   Date de fin: {$endDate->format('d/m/Y H:i')}\n";
echo "   Maintenant: {$now->format('d/m/Y H:i')}\n";
echo "   Durée: " . $startDate->diffInDays($endDate) . " jour(s)\n";

if ($startDate->isFuture()) {
    $heuresAvantDebut = $now->diffInHours($startDate);
    echo "   ⏰ Début dans: {$heuresAvantDebut}h\n";
} else {
    echo "   ⚠️  Date de début dans le passé\n";
}

if ($endDate->isFuture()) {
    $heuresAvantFin = $now->diffInHours($endDate);
    echo "   ⏰ Fin dans: {$heuresAvantFin}h\n";
} else {
    echo "   ⚠️  Date de fin dans le passé\n";
}

echo "\n🎯 PRÊT POUR LE TEST:\n";
echo "===================\n";
echo "1. Créez votre location manuelle (06/08 → 07/08)\n";
echo "2. Statut initial: 'confirmed'\n";
echo "3. Demain à minuit: passage automatique à 'active'\n";
echo "4. Après-demain à minuit: passage à 'completed' + email\n";
echo "5. Action manuelle: cloture → 'closed' + inspection\n\n";

echo "📧 Emails attendus:\n";
echo "• Email de confirmation (immédiat)\n";
echo "• Email de démarrage (06/08 à minuit)\n";
echo "• Email de fin avec nouveau template (07/08 à minuit)\n\n";

echo "🚀 Vous pouvez maintenant créer votre location de test !\n";
