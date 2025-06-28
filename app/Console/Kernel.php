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
