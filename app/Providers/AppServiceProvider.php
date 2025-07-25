<?php

namespace App\Providers;

use App\Models\Order;
use App\Observers\OrderObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Enregistrer l'observer pour les commandes
        Order::observe(OrderObserver::class);
        
        // Enregistrer l'observer pour les produits (gestion du stock)
        \App\Models\Product::observe(\App\Observers\ProductStockObserver::class);
    }
}
