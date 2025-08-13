<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;

class AddRentalStockAndPrices extends Command
{
    protected $signature = 'update:rental-stock-prices';
    protected $description = 'Ajouter 25 unités de stock et des prix réalistes aux produits de location';

    private $pricingRules = [
        // Outils agricoles - Prix journaliers
        'outils' => [
            'bêche' => 8,
            'houe' => 6,
            'fourche' => 7,
            'râteau' => 5,
            'serfouette' => 6,
            'pioche' => 9,
            'transplantoir' => 4,
            'binette' => 5,
            'faux' => 12,
            'sécateur' => 8,
            'ébrancheur' => 15,
            'scie' => 10,
            'plantoir' => 4,
            'hache' => 12,
            'cultivateur' => 8,
            'coupe' => 6,
            'pelle' => 7,
            'serpette' => 5,
            'grelinette' => 12,
            'croc' => 6,
            'émondoir' => 18,
            'faucille' => 5,
            'grattoir' => 3,
            'sarcloir' => 7,
            'échenilloir' => 20,
            'écorçoir' => 8,
            'tire-sève' => 6
        ],
        
        // Machines agricoles - Prix journaliers plus élevés
        'machines' => [
            'motoculteur' => 45,
            'débroussailleuse' => 35,
            'tondeuse' => 40,
            'rotavator' => 55,
            'faucheuse' => 65,
            'broyeur' => 50,
            'épandeur' => 40,
            'pulvérisateur' => 45,
            'motopompe' => 35,
            'fendeuse' => 40,
            'cultivateur' => 50,
            'semoir' => 60,
            'herse' => 45,
            'rouleau' => 35,
            'bineuse' => 40,
            'faneur' => 55,
            'distributeur' => 50,
            'aérateur' => 40,
            'tronçonneuse' => 30,
            'butteur' => 45,
            'décavaillonneuse' => 50,
            'remorque' => 40,
            'souffleur' => 25,
            'tarière' => 35,
            'effeuilleuse' => 45,
            'sous-soleuse' => 55,
            'planteuse' => 60,
            'andaineur' => 50,
            'déchaumeur' => 45,
            'écimeuse' => 40,
            'presse' => 80,
            'trieur' => 50,
            'faneuse' => 55,
            'retourneur' => 45
        ],
        
        // Équipements - Prix variables selon complexité
        'equipement' => [
            'serre' => 25,
            'irrigation' => 20,
            'bâche' => 8,
            'filet' => 12,
            'cuve' => 30,
            'balance' => 25,
            'clôture' => 15,
            'séchoir' => 40,
            'pulvérisateur' => 15,
            'abreuvoir' => 20,
            'big-bags' => 10,
            'thermomètre' => 8,
            'presse' => 35,
            'étiqueteuse' => 20,
            'tunnel' => 30,
            'distributeur' => 25,
            'mélangeur' => 35,
            'station' => 40,
            'compteur' => 15,
            'humidimètre' => 12,
            'aspirateur' => 30,
            'chauffage' => 35,
            'brumisateur' => 25,
            'conteneurs' => 20,
            'désinfecteur' => 30,
            'extracteur' => 40,
            'tapis' => 25,
            'incubateur' => 45,
            'ventilateur' => 20,
            'kit' => 10
        ]
    ];

    public function handle()
    {
        $this->info('=== MISE À JOUR STOCK ET PRIX LOCATION ===');
        $this->newLine();

        $products = Product::where('type', 'rental')->where('is_active', true)->get();
        
        $this->info("Traitement de {$products->count()} produits de location...");
        $this->newLine();

        $updated = 0;
        $errors = 0;

        foreach ($products as $product) {
            try {
                // Déterminer le prix basé sur le nom du produit
                $price = $this->calculatePrice($product->name, $product->category->name ?? '');
                
                // Mettre à jour le produit
                $product->update([
                    'rental_stock' => 25,
                    'price' => $price,
                    'quantity' => 0, // Pas de stock achat pour les produits rental
                    'critical_threshold' => 5,
                    'low_stock_threshold' => 10
                ]);

                $this->line("✅ {$product->name} - Stock: 25, Prix: {$price}€/jour");
                $updated++;

            } catch (\Exception $e) {
                $this->error("❌ Erreur pour {$product->name}: " . $e->getMessage());
                $errors++;
            }
        }

        $this->newLine();
        $this->info("=== RÉSULTATS ===");
        $this->line("✅ Produits mis à jour: $updated");
        if ($errors > 0) {
            $this->error("❌ Erreurs: $errors");
        }
        
        $this->newLine();
        $this->info("🎉 Tous les produits de location ont maintenant 25 unités de stock et des prix réalistes !");
    }

    private function calculatePrice($productName, $categoryName)
    {
        $name = strtolower($productName);
        $category = strtolower($categoryName);

        // Déterminer la catégorie de prix
        if (strpos($category, 'machine') !== false) {
            $priceGroup = $this->pricingRules['machines'];
        } elseif (strpos($category, 'équipement') !== false || strpos($category, 'equipment') !== false) {
            $priceGroup = $this->pricingRules['equipement'];
        } else {
            $priceGroup = $this->pricingRules['outils'];
        }

        // Rechercher un prix basé sur des mots-clés dans le nom
        foreach ($priceGroup as $keyword => $price) {
            if (strpos($name, $keyword) !== false) {
                return $price;
            }
        }

        // Prix par défaut selon la catégorie
        if (strpos($category, 'machine') !== false) {
            return 45; // Prix moyen machines
        } elseif (strpos($category, 'équipement') !== false) {
            return 25; // Prix moyen équipements
        } else {
            return 8; // Prix moyen outils
        }
    }
}
