<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// Patch pour éviter le problème ReflectionException
if (!function_exists('app')) {
    function app($abstract = null, array $parameters = []) {
        static $app;
        if (!$app) {
            $app = new Application(dirname(__DIR__));
        }
        if (is_null($abstract)) {
            return $app;
        }
        return $app->make($abstract, $parameters);
    }
}

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php', 
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'check.product.availability' => \App\Http\Middleware\CheckProductAvailability::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
