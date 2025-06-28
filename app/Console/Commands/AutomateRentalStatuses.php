<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Rental;
use App\Notifications\RentalReminder;
use App\Notifications\RentalOverdue;

class AutomateRentalStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rentals:automate {--dry-run : Afficher les actions sans les exécuter}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatise les statuts de location et envoie les notifications';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        
        if ($isDryRun) {
            $this->info('🔄 Mode DRY-RUN activé - Aucune modification ne sera effectuée');
        }
        
        $this->info('🚀 Début de l\'automatisation des locations...');
        
        // 1. Envoyer les rappels de fin de location
        $this->sendRentalReminders($isDryRun);
        
        // 2. Marquer les locations en retard
        $this->markOverdueRentals($isDryRun);
        
        // 3. Notifier les locations en retard
        $this->notifyOverdueRentals($isDryRun);
        
        // 4. Calculer les amendes de retard
        $this->calculateLatePenalties($isDryRun);
        
        $this->info('✅ Automatisation terminée avec succès !');
        
        return 0;
    }

    /**
     * Envoyer les rappels de fin de location (7 jours avant)
     */
    private function sendRentalReminders($isDryRun)
    {
        $this->info('📧 Vérification des rappels de location...');
        
        $rentalsNeedingReminder = Rental::needingReminder()->get();
        
        if ($rentalsNeedingReminder->isEmpty()) {
            $this->line('   Aucun rappel à envoyer');
            return;
        }
        
        $count = 0;
        foreach ($rentalsNeedingReminder as $rental) {
            $this->line("   📬 Rappel pour location #{$rental->rental_number} (Client: {$rental->user->name})");
            
            if (!$isDryRun) {
                $rental->user->notify(new RentalReminder($rental));
                $rental->update([
                    'reminder_sent' => true,
                    'reminder_sent_at' => now()
                ]);
            }
            
            $count++;
        }
        
        $this->info("   ✅ {$count} rappel(s) envoyé(s)");
    }

    /**
     * Marquer les locations en retard
     */
    private function markOverdueRentals($isDryRun)
    {
        $this->info('⏰ Vérification des locations en retard...');
        
        $activeRentals = Rental::where('status', Rental::STATUS_ACTIVE)
            ->where('end_date', '<', now()->toDateString())
            ->get();
        
        if ($activeRentals->isEmpty()) {
            $this->line('   Aucune location en retard trouvée');
            return;
        }
        
        $count = 0;
        foreach ($activeRentals as $rental) {
            $daysOverdue = now()->diffInDays($rental->end_date, false);
            $this->line("   ⚠️  Location #{$rental->rental_number} en retard de {$daysOverdue} jour(s)");
            
            if (!$isDryRun) {
                $rental->markAsOverdue();
            }
            
            $count++;
        }
        
        $this->info("   ✅ {$count} location(s) marquée(s) en retard");
    }

    /**
     * Notifier les nouvelles locations en retard
     */
    private function notifyOverdueRentals($isDryRun)
    {
        $this->info('🔔 Notification des locations en retard...');
        
        // Locations marquées en retard aujourd'hui
        $newlyOverdueRentals = Rental::where('status', Rental::STATUS_OVERDUE)
            ->whereDate('updated_at', now()->toDateString())
            ->get();
        
        if ($newlyOverdueRentals->isEmpty()) {
            $this->line('   Aucune nouvelle location en retard à notifier');
            return;
        }
        
        $count = 0;
        foreach ($newlyOverdueRentals as $rental) {
            $this->line("   📢 Notification retard pour #{$rental->rental_number}");
            
            if (!$isDryRun) {
                $rental->user->notify(new RentalOverdue($rental));
            }
            
            $count++;
        }
        
        $this->info("   ✅ {$count} notification(s) de retard envoyée(s)");
    }

    /**
     * Calculer et appliquer les amendes de retard
     */
    private function calculateLatePenalties($isDryRun)
    {
        $this->info('💰 Calcul des amendes de retard...');
        
        $overdueRentals = Rental::where('status', Rental::STATUS_OVERDUE)->get();
        
        if ($overdueRentals->isEmpty()) {
            $this->line('   Aucune amende à calculer');
            return;
        }
        
        $totalPenalties = 0;
        $count = 0;
        
        foreach ($overdueRentals as $rental) {
            $daysOverdue = $rental->days_overdue;
            
            if ($daysOverdue > 0) {
                $penaltyAmount = 0;
                
                foreach ($rental->items as $item) {
                    $dailyPenalty = $item->rental_price_per_day * 0.1; // 10% par jour
                    $itemPenalty = $dailyPenalty * $daysOverdue;
                    $penaltyAmount += $itemPenalty;
                }
                
                $this->line("   💸 Amende #{$rental->rental_number}: {$penaltyAmount}€ ({$daysOverdue} jours)");
                
                if (!$isDryRun) {
                    $rental->calculateLatePenalty();
                }
                
                $totalPenalties += $penaltyAmount;
                $count++;
            }
        }
        
        $this->info("   ✅ {$count} amende(s) calculée(s) (Total: {$totalPenalties}€)");
    }
}
