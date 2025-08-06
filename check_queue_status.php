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

echo "📊 ÉTAT DE LA QUEUE\n";
echo "==================\n";
echo "Jobs en attente: " . DB::table('jobs')->count() . "\n";
echo "Jobs échoués: " . DB::table('failed_jobs')->count() . "\n";
echo "✅ Queue prête pour de nouveaux jobs automatiques\n";
