<?php

use App\Jobs\UpdateOrderStatusJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Commande pour mettre à jour automatiquement les statuts des commandes
Artisan::command('orders:update-status', function () {
    UpdateOrderStatusJob::dispatch();
    $this->info('Job de mise à jour des statuts de commandes lancé');
})->purpose('Mettre à jour automatiquement les statuts des commandes');
