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

echo "🔍 DEBUG DES DONNÉES DE LA COMMANDE LOC-202508034682\n";
echo "=================================================\n\n";

$order = App\Models\OrderLocation::where('order_number', 'LOC-202508034682')->first();

if (!$order) {
    echo "❌ Commande non trouvée\n";
    exit(1);
}

echo "📋 INFORMATIONS DE BASE:\n";
echo "   Numéro: {$order->order_number}\n";
echo "   Status: {$order->status}\n";
echo "   Date de début: {$order->start_date}\n";
echo "   Date de fin: {$order->end_date}\n";
echo "   Jours de location (BDD): {$order->rental_days}\n";
echo "   Montant total: {$order->total_amount}€\n";
echo "   Montant des taxes: {$order->tax_amount}€\n\n";

echo "🧮 CALCULS DE DURÉE:\n";
$startDate = \Carbon\Carbon::parse($order->start_date);
$endDate = \Carbon\Carbon::parse($order->end_date);
$now = \Carbon\Carbon::now();

echo "   Date de début (Carbon): {$startDate->toDateTimeString()}\n";
echo "   Date de fin (Carbon): {$endDate->toDateTimeString()}\n";
echo "   Maintenant (Carbon): {$now->toDateTimeString()}\n";

$diffInDays = $startDate->diffInDays($endDate);
echo "   Différence start->end: {$diffInDays} jours\n";

$actualDays = $startDate->diffInDays($now, false);
echo "   Différence start->now: {$actualDays} jours\n";

// Test du calcul exact utilisé dans l'email
$actualRealDays = $startDate->diffInDays($now, false) + ($startDate->diffInHours($now) % 24) / 24;
echo "   Calcul précis (avec heures): {$actualRealDays} jours\n\n";

echo "📦 PRODUITS DE LA COMMANDE:\n";
if ($order->orderLocationItems) {
    foreach ($order->orderLocationItems as $item) {
        echo "   - Produit ID: {$item->product_id}\n";
        echo "     Quantité: {$item->quantity}\n";
        echo "     Prix unitaire: {$item->unit_price}€\n";
        echo "     Prix total: {$item->total_price}€\n";
        
        if ($item->product) {
            echo "     Nom: {$item->product->name}\n";
            echo "     Prix location/jour: " . ($item->product->rental_price ?? 'N/A') . "€\n";
            echo "     Dépôt: " . ($item->product->deposit_amount ?? 'N/A') . "€\n";
        }
        echo "\n";
    }
} else {
    echo "   ❌ Aucun produit trouvé\n";
}

echo "💰 CALCULS FINANCIERS:\n";
$subtotal = $order->total_amount - $order->tax_amount;
echo "   Sous-total HT: {$subtotal}€\n";
echo "   TVA: {$order->tax_amount}€\n";
echo "   Total TTC: {$order->total_amount}€\n\n";

echo "📅 DATES DE FERMETURE:\n";
$maxCloseDate = $endDate->copy()->addDays(3);
echo "   Date de fin + 3 jours: {$maxCloseDate->toDateString()}\n";
echo "   (C'est la limite pour fermer la commande)\n\n";

echo "🎯 RÉSUMÉ DES ANOMALIES:\n";
if ($actualRealDays < $diffInDays) {
    echo "   ⚠️  Durée réelle ({$actualRealDays}) < Durée prévue ({$diffInDays})\n";
}
if ($order->total_amount == 0) {
    echo "   ⚠️  Prix total à 0€\n";
}
if (!$order->orderLocationItems || $order->orderLocationItems->isEmpty()) {
    echo "   ⚠️  Aucun produit associé\n";
}
