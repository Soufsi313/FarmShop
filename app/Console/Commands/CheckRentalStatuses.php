<?php

namespace App\Console\Commands;

use App\Services\RentalStatusService;
use Illuminate\Console\Command;

class CheckRentalStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rentals:check-statuses {--force : Force la vérification même si elle a été faite récemment}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vérifie et met à jour tous les statuts des locations selon leurs dates';

    protected $rentalStatusService;

    public function __construct(RentalStatusService $rentalStatusService)
    {
        parent::__construct();
        $this->rentalStatusService = $rentalStatusService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Vérification des statuts des locations...');
        
        try {
            $updated = $this->rentalStatusService->checkAndUpdateAllRentalStatuses();
            
            $this->info('✅ Vérification terminée !');
            
            if (!empty($updated['started'])) {
                $this->info('📅 Locations démarrées : ' . count($updated['started']));
                foreach ($updated['started'] as $rental) {
                    $this->line("  - Commande #{$rental['order_number']} pour {$rental['user_email']}");
                }
            }
            
            if (!empty($updated['reminded'])) {
                $this->info('⏰ Rappels envoyés : ' . count($updated['reminded']));
                foreach ($updated['reminded'] as $rental) {
                    $this->line("  - Commande #{$rental['order_number']} pour {$rental['user_email']}");
                }
            }
            
            if (!empty($updated['ended'])) {
                $this->info('🏁 Locations terminées : ' . count($updated['ended']));
                foreach ($updated['ended'] as $rental) {
                    $this->line("  - Commande #{$rental['order_number']} pour {$rental['user_email']}");
                }
            }
            
            if (!empty($updated['overdue'])) {
                $this->warn('⚠️  Locations en retard : ' . count($updated['overdue']));
                foreach ($updated['overdue'] as $rental) {
                    $this->line("  - Commande #{$rental['order_number']} pour {$rental['user_email']}");
                }
            }
            
            if (empty($updated['started']) && empty($updated['reminded']) && empty($updated['ended']) && empty($updated['overdue'])) {
                $this->info('ℹ️  Aucune mise à jour nécessaire');
            }
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error('❌ Erreur lors de la vérification : ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
