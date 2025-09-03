<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use Carbon\Carbon;
use App\Rules\RentalDateValidation;

class TestSundayRestrictions extends Command
{
    protected $signature = 'test:sunday-restrictions';
    protected $description = 'Test les restrictions dimanche pour le système de location';

    public function handle()
    {
        $this->info('🧪 Test des restrictions dimanche - Système de location');
        $this->line(str_repeat('=', 60));
        $this->line('');

        // Récupérer un produit de location pour les tests
        $product = Product::where('type', 'rental')->first();

        if (!$product) {
            $this->error('❌ Aucun produit de location trouvé dans la base de données');
            return 1;
        }

        $this->info("📦 Produit testé : {$product->name}");
        $this->info("🕒 Contraintes : Min {$product->min_rental_days} jour(s)" . 
                   ($product->max_rental_days ? ", Max {$product->max_rental_days} jour(s)" : ""));
        $this->line('');

        // Test 1 : Vérification qu'un dimanche est détecté comme non disponible
        $this->info('Test 1: Vérification du dimanche comme jour non disponible');
        $this->line(str_repeat('-', 50));

        // Trouver le prochain dimanche
        $nextSunday = Carbon::now()->next(Carbon::SUNDAY);
        $isAvailable = $product->isDayAvailable($nextSunday);

        $this->line("📅 Prochain dimanche : {$nextSunday->format('d/m/Y')}");
        $this->line("✅ Disponible : " . ($isAvailable ? "OUI" : "NON"));
        if (!$isAvailable) {
            $this->info("✅ Test réussi : Les dimanches sont bien bloqués");
        } else {
            $this->error("❌ Test échoué : Les dimanches devraient être bloqués");
        }
        $this->line('');

        // Test 2 : Calcul de durée excluant les dimanches
        $this->info('Test 2: Calcul de durée excluant les dimanches');
        $this->line(str_repeat('-', 50));

        // Test du lundi au lundi (incluant un dimanche)
        $startDate = Carbon::now()->next(Carbon::MONDAY);
        $endDate = $startDate->copy()->addWeek(); // Lundi suivant

        $totalDays = $startDate->diffInDays($endDate) + 1;
        $businessDays = $product->calculateRentalDuration($startDate, $endDate);

        $this->line("📅 Période : {$startDate->format('d/m/Y')} → {$endDate->format('d/m/Y')}");
        $this->line("📊 Jours calendaires : {$totalDays}");
        $this->line("💼 Jours ouvrés (sans dimanche) : {$businessDays}");
        $this->line("🚫 Dimanches exclus : " . ($totalDays - $businessDays));

        if ($businessDays == 6 && ($totalDays - $businessDays) == 1) {
            $this->info("✅ Test réussi : Le dimanche est bien exclu du calcul");
        } else {
            $this->error("❌ Test échoué : Le calcul ne semble pas correct");
        }
        $this->line('');

        // Test 3 : Ajustement automatique d'une date dimanche
        $this->info('Test 3: Ajustement automatique d\'une date dimanche');
        $this->line(str_repeat('-', 50));

        $sundayDate = Carbon::now()->next(Carbon::SUNDAY);
        $adjustedDate = $product->adjustDateForBusinessDays($sundayDate);

        $this->line("📅 Date dimanche : {$sundayDate->format('d/m/Y l')}");
        $this->line("📅 Date ajustée : {$adjustedDate->format('d/m/Y l')}");

        if ($adjustedDate->isMonday() && $adjustedDate->gt($sundayDate)) {
            $this->info("✅ Test réussi : Le dimanche est bien décalé au lundi");
        } else {
            $this->error("❌ Test échoué : L'ajustement ne fonctionne pas correctement");
        }
        $this->line('');

        // Test 4 : Validation avec messages explicites
        $this->info('Test 4: Messages de validation explicites');
        $this->line(str_repeat('-', 50));

        // Test de validation d'une date dimanche
        $validator = new RentalDateValidation($product, null, null, 'start');

        $errorMessage = '';
        try {
            $validator->validate('start_date', $sundayDate->format('Y-m-d'), function($message) use (&$errorMessage) {
                $errorMessage = $message;
            });
        } catch (\Exception $e) {
            // La validation peut lever une exception ou appeler le callback
        }

        $this->line("📅 Validation date dimanche : {$sundayDate->format('d/m/Y')}");
        $this->line("💬 Message d'erreur : {$errorMessage}");

        if (strpos($errorMessage, 'dimanche') !== false || strpos($errorMessage, 'fermée') !== false) {
            $this->info("✅ Test réussi : Message explicite pour dimanche");
        } else {
            $this->error("❌ Test échoué : Message pas assez explicite");
        }
        $this->line('');

        $this->line(str_repeat('=', 60));
        $this->info('🏁 Tests terminés !');
        $this->line('');
        $this->info('💡 Points clés implémentés :');
        $this->line('   • Dimanches automatiquement exclus du calcul');
        $this->line('   • Messages d\'erreur explicites et contextuels');
        $this->line('   • Ajustement automatique des dates dimanche');
        $this->line('   • Distinction jours ouvrés / jours calendaires');
        $this->line('   • Validation robuste avec feedback utilisateur');

        return 0;
    }
}
