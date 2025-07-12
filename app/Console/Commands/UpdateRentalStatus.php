<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\UpdateRentalStatusJob;

class UpdateRentalStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rental:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Met à jour les statuts des commandes de location';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Lancement de la mise à jour des statuts de location...');
        
        // Dispatcher le job
        UpdateRentalStatusJob::dispatch();
        
        $this->info('Job de mise à jour des statuts de location lancé');
        
        return 0;
    }
}
