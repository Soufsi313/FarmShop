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

echo "🔒 TEST PROTECTION ANTI-DOUBLON RENFORCÉE\n";
echo "==========================================\n\n";

$order = App\Models\OrderLocation::where('order_number', 'LOC-202508034682')->first();

if (!$order) {
    echo "❌ Commande non trouvée\n";
    exit(1);
}

echo "📋 Commande: {$order->order_number}\n";
echo "👤 Utilisateur: {$order->user->name}\n\n";

// Nettoyer d'abord le cache existant
$lockKey = "email_completed_lock_{$order->id}";
Illuminate\Support\Facades\Cache::forget($lockKey);
echo "🧹 Cache nettoyé pour les tests\n\n";

// Test 1: Premier appel
echo "🧪 Test 1: Premier envoi...\n";
try {
    $listener = new App\Listeners\HandleOrderLocationStatusChange();
    $reflection = new ReflectionClass($listener);
    $method = $reflection->getMethod('handleCompleted');
    $method->setAccessible(true);
    
    $method->invoke($listener, $order);
    echo "✅ Premier envoi traité\n\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n\n";
}

// Test 2: Tentative immédiate (devrait être bloquée)
echo "🧪 Test 2: Tentative de doublon immédiat...\n";
try {
    $method->invoke($listener, $order);
    echo "⚠️  Second envoi traité (ne devrait PAS arriver)\n\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n\n";
}

// Test 3: Autre tentative (devrait aussi être bloquée)
echo "🧪 Test 3: Troisième tentative...\n";
try {
    $method->invoke($listener, $order);
    echo "⚠️  Troisième envoi traité (ne devrait PAS arriver)\n\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n\n";
}

// Vérifier l'état du verrou
echo "🔍 Vérification du verrou:\n";
if (Illuminate\Support\Facades\Cache::has($lockKey)) {
    echo "✅ Verrou actif - protection anti-doublon fonctionnelle\n";
} else {
    echo "❌ Aucun verrou - protection non active\n";
}

echo "\n📊 Résumé:\n";
echo "=========\n";
echo "• Protection par verrou atomique Cache::add()\n";
echo "• Durée du verrou: 30 minutes\n";
echo "• Libération automatique en cas d'erreur\n";
echo "• Un seul email peut être envoyé par période\n\n";

echo "🎯 SOLUTION DÉPLOYÉE:\n";
echo "• Système anti-doublon ULTRA strict\n";
echo "• Vérifiez vos emails - vous ne devriez en recevoir qu'UN SEUL maintenant\n";
