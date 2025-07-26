<?php

namespace App\Console\Commands;

use App\Models\SpecialOffer;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CleanExpiredSpecialOffers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'special-offers:clean-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Désactive automatiquement les offres spéciales expirées';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        
        // Trouver les offres spéciales expirées qui sont encore actives
        $expiredOffers = SpecialOffer::where('is_active', true)
            ->where('end_date', '<', $now)
            ->get();
        
        if ($expiredOffers->isEmpty()) {
            $this->info('Aucune offre spéciale expirée trouvée.');
            return;
        }
        
        $count = $expiredOffers->count();
        
        // Désactiver les offres expirées
        SpecialOffer::where('is_active', true)
            ->where('end_date', '<', $now)
            ->update(['is_active' => false]);
        
        $this->info("$count offre(s) spéciale(s) expirée(s) ont été désactivées.");
        
        // Afficher la liste des offres désactivées
        foreach ($expiredOffers as $offer) {
            $this->line("- {$offer->name} (Produit: {$offer->product->name}) - Expirée le {$offer->end_date->format('d/m/Y')}");
        }
    }
}
