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

echo "📋 STRUCTURE DE LA TABLE PRODUCTS\n";
echo "==================================\n";
$columns = DB::select('DESCRIBE products');
foreach($columns as $col) {
    echo "- {$col->Field} ({$col->Type})\n";
}

echo "\n📦 PREMIERS PRODUITS DISPONIBLES:\n";
echo "=================================\n";
$products = DB::table('products')
    ->where('stock', '>', 0)
    ->select('id', 'name', 'price', 'stock')
    ->limit(5)
    ->get();

foreach($products as $product) {
    echo "ID: {$product->id} | {$product->name} | Prix: {$product->price}€ | Stock: {$product->stock}\n";
}
