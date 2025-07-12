<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RentalConstraintsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Contraintes de location par défaut selon les spécifications utilisateur
        $defaultConstraints = [
            'min_rental_days' => 1,
            'max_rental_days' => 7, // Maximum 7 jours comme demandé
            'available_days' => [1, 2, 3, 4, 5, 6] // Lundi à Samedi seulement
        ];

        // Mettre à jour tous les produits de location existants
        Product::whereIn('type', ['rental', 'both'])
            ->whereNull('min_rental_days') // Seulement ceux sans contraintes
            ->update($defaultConstraints);

        $this->command->info('Contraintes de location appliquées à tous les produits de location.');
        
        // Afficher les statistiques
        $rentalCount = Product::whereIn('type', ['rental', 'both'])->count();
        $this->command->info("Nombre total de produits de location: {$rentalCount}");
        
        // Vérifier que tous les produits de location ont des contraintes
        $withoutConstraints = Product::whereIn('type', ['rental', 'both'])
            ->whereNull('min_rental_days')
            ->count();
            
        if ($withoutConstraints > 0) {
            $this->command->warn("Attention: {$withoutConstraints} produits de location n'ont toujours pas de contraintes.");
        } else {
            $this->command->info('Tous les produits de location ont maintenant des contraintes configurées.');
        }
    }
}
