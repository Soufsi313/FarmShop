<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class FixRentalControllerCode extends Command
{
    protected $signature = 'fix:rental-controller';
    protected $description = 'Corriger le code du RentalController directement';

    public function handle()
    {
        $this->info('=== CORRECTION DU RENTALCONTROLLER ===');
        $this->newLine();

        $controllerPath = app_path('Http/Controllers/RentalController.php');
        
        if (!File::exists($controllerPath)) {
            $this->error("RentalController non trouvé à: {$controllerPath}");
            return;
        }

        $this->info('1. LECTURE DU FICHIER ACTUEL:');
        $content = File::get($controllerPath);
        $this->line("   Taille du fichier: " . strlen($content) . " caractères");

        $this->info('2. CORRECTION DU CODE:');
        $fixes = 0;

        // Fix 1: Corriger la requête principale
        if (strpos($content, "->where('is_rental_available', true)") !== false) {
            $content = str_replace(
                "->where('is_rental_available', true)",
                "",
                $content
            );
            $this->line("   ✅ Supprimé is_rental_available");
            $fixes++;
        }

        // Fix 2: Corriger les types 'both' vers 'mixed'
        if (strpos($content, "['rental', 'both']") !== false) {
            $content = str_replace(
                "['rental', 'both']",
                "['rental', 'mixed']",
                $content
            );
            $this->line("   ✅ Corrigé 'both' vers 'mixed'");
            $fixes++;
        }

        // Fix 3: Corriger rental_price_per_day vers price
        if (strpos($content, 'rental_price_per_day') !== false) {
            $content = str_replace(
                'rental_price_per_day',
                'price',
                $content
            );
            $this->line("   ✅ Corrigé rental_price_per_day vers price");
            $fixes++;
        }

        // Fix 4: Corriger rental_category_id vers category_id
        if (strpos($content, 'rental_category_id') !== false) {
            $content = str_replace(
                'rental_category_id',
                'category_id',
                $content
            );
            $this->line("   ✅ Corrigé rental_category_id vers category_id");
            $fixes++;
        }

        // Fix 5: Corriger les relations rentalCategory
        if (strpos($content, "'rentalCategory'") !== false) {
            $content = str_replace(
                "['category', 'rentalCategory']",
                "['category']",
                $content
            );
            $content = str_replace(
                ", 'rentalCategory'",
                "",
                $content
            );
            $this->line("   ✅ Supprimé rentalCategory relations");
            $fixes++;
        }

        // Fix 6: Corriger le filtrage par catégorie
        if (strpos($content, 'rental_category') !== false) {
            $content = str_replace(
                'rental_category',
                'category',
                $content
            );
            $this->line("   ✅ Corrigé rental_category vers category");
            $fixes++;
        }

        $this->info('3. ECRITURE DU FICHIER CORRIGE:');
        File::put($controllerPath, $content);
        $this->line("   ✅ Fichier sauvegardé");

        $this->newLine();
        $this->info('=== CORRECTION TERMINEE ===');
        $this->line("   Corrections appliquées: {$fixes}");
        $this->line("   Les pages de location devraient maintenant fonctionner !");
        
        // Vider le cache
        $this->call('config:clear');
        $this->call('route:clear');
        $this->call('view:clear');
        
        $this->line("   ✅ Cache vidé");
    }
}
