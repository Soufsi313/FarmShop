<?php

use App\Jobs\UpdateOrderStatusJob;
use App\Jobs\UpdateRentalStatusJob;
use App\Jobs\AutoUpdateRentalStatusJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Commande pour mettre à jour automatiquement les statuts des commandes
Artisan::command('orders:update-status', function () {
    UpdateOrderStatusJob::dispatch();
    $this->info('Job de mise à jour des statuts de commandes lancé');
})->purpose('Mettre à jour automatiquement les statuts des commandes');

// Commande pour mettre à jour automatiquement les statuts des locations
Artisan::command('rentals:update-status', function () {
    UpdateRentalStatusJob::dispatch();
    $this->info('Job de mise à jour des statuts de location lancé');
})->purpose('Mettre à jour automatiquement les statuts des locations');

// Commande pour la mise à jour automatique avancée des statuts de location
Artisan::command('rentals:auto-update', function () {
    AutoUpdateRentalStatusJob::dispatch();
    $this->info('🔄 Job de mise à jour automatique des statuts de location lancé');
})->purpose('Mise à jour automatique intelligente des statuts de location');

// ⏰ PROGRAMMATION AUTOMATIQUE
// Vérifier les statuts de commande toutes les 45 secondes
Schedule::command('orders:update-status')->everyMinute()->withoutOverlapping();

// Vérifier les statuts de location toutes les heures
Schedule::command('rentals:auto-update')->hourly()->withoutOverlapping();

// Vérifier spécifiquement les transitions importantes plusieurs fois par jour
Schedule::command('rentals:auto-update')->dailyAt('09:00'); // Matin
Schedule::command('rentals:auto-update')->dailyAt('15:00'); // Après-midi
Schedule::command('rentals:auto-update')->dailyAt('21:00'); // Soir
