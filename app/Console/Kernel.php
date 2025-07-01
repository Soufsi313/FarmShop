<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Automatiser les changements de statuts des commandes toutes les 30 secondes
        $schedule->command('orders:update-status')
            ->everyThirtySeconds()
            ->withoutOverlapping()
            ->runInBackground()
            ->appendOutputTo(storage_path('logs/order-status-automation.log'));

        // Automatiser les changements de statuts des commandes toutes les heures
        $schedule->command('orders:automate-statuses')
            ->hourly()
            ->withoutOverlapping()
            ->runInBackground()
            ->appendOutputTo(storage_path('logs/order-automation.log'));

        // Nettoyer les paniers abandonnés plus de 7 jours
        $schedule->command('cart:cleanup')
            ->daily()
            ->at('02:00');

        // Envoyer les notifications de stock faible tous les matins à 8h
        $schedule->command('products:check-low-stock')
            ->dailyAt('08:00');

        // Automatisation des statuts de location (3 fois par jour)
        $schedule->command('rentals:automate')
            ->dailyAt('09:00')
            ->withoutOverlapping()
            ->runInBackground()
            ->appendOutputTo(storage_path('logs/rental-automation.log'));
            
        $schedule->command('rentals:automate')
            ->dailyAt('15:00')
            ->withoutOverlapping()
            ->runInBackground()
            ->appendOutputTo(storage_path('logs/rental-automation.log'));
            
        $schedule->command('rentals:automate')
            ->dailyAt('21:00')
            ->withoutOverlapping()
            ->runInBackground()
            ->appendOutputTo(storage_path('logs/rental-automation.log'));

        // Automatisation des processus de retour (2 fois par jour)
        $schedule->command('returns:automate')
            ->dailyAt('10:00')
            ->withoutOverlapping()
            ->runInBackground()
            ->appendOutputTo(storage_path('logs/return-automation.log'));
            
        $schedule->command('returns:automate')
            ->dailyAt('16:00')
            ->withoutOverlapping()
            ->runInBackground()
            ->appendOutputTo(storage_path('logs/return-automation.log'));
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
