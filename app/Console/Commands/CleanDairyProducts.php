<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class CleanDairyProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:clean-dairy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Supprimer les anciens produits laitiers non fermiers';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Liste des anciens produits "de grande surface" à supprimer
        $toDelete = [
            'Lait Entier de Vache Bio',
            'Fromage Blanc Fermier 40% MG',
            'Yaourt Nature au Lait Entier Bio', 
            'Beurre Doux de Baratte Bio',
            'Crème Fraîche Épaisse 35% MG',
            'Camembert de Normandie AOP',
            'Chèvre Frais aux Herbes de Provence',
            'Roquefort AOP Caves de Roquefort',
            'Mozzarella de Bufflonne Italienne',
            'Comté AOP 18 Mois d\'Affinage'
        ];

        $this->info('🧹 Nettoyage des anciens produits laitiers non fermiers...');
        $this->line('');

        $deleted = 0;
        
        foreach($toDelete as $name) {
            $product = Product::where('name', $name)->first();
            if($product) {
                $this->line("❌ Supprimé: {$product->name} (SKU: {$product->sku})");
                $product->delete();
                $deleted++;
            } else {
                $this->line("⚠️  Non trouvé: {$name}");
            }
        }

        $this->line('');
        $this->info("✅ Nettoyage terminé: {$deleted} produit(s) supprimé(s)");
        
        // Vérifier ce qui reste
        $remaining = Product::whereHas('category', function($q) {
            $q->where('name', 'Produits laitiers');
        })->count();
        
        $this->info("🥛 Produits fermiers restants: {$remaining}");
        
        return 0;
    }
}
