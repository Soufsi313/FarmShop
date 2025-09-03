<?php

namespace App\Console\Commands;

use App\Http\Controllers\CartItemLocationController;
use App\Models\CartItemLocation;
use App\Models\Product;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TestSundayDates extends Command
{
    protected $signature = 'test:sunday-dates';
    protected $description = 'Test spécifique pour les dates incluant un dimanche (02/09 au 07/09)';

    public function handle()
    {
        $this->info('🧪 Test spécifique : 02/09/2025 au 07/09/2025 (dimanche inclus)');
        $this->newLine();

        // Trouver un utilisateur test
        $user = User::first();
        if (!$user) {
            $this->error('❌ Aucun utilisateur trouvé');
            return 1;
        }

        // Trouver un produit de location
        $product = Product::where('is_rental_available', true)->first();
        if (!$product) {
            $this->error('❌ Aucun produit de location trouvé');
            return 1;
        }

        // Créer ou récupérer un panier
        $cartLocation = $user->getOrCreateActiveCartLocation();
        
        // Ajouter un item au panier si nécessaire
        $cartItem = $cartLocation->items()->where('product_id', $product->id)->first();
        if (!$cartItem) {
            $cartItem = $cartLocation->addProduct($product, 1, now(), now()->addDay());
        }

        $this->info("📦 Produit testé: {$product->name}");
        $this->info("🛒 Item du panier ID: {$cartItem->id}");
        $this->newLine();

        // Simuler l'authentification
        auth()->login($user);

        // Test : 02/09/2025 au 07/09/2025 (le 07/09/2025 est un dimanche)
        $this->info('📅 Test : du 02/09/2025 (lundi) au 07/09/2025 (dimanche)');
        
        $request = new Request([
            'start_date' => '2025-09-02', // Lundi
            'end_date' => '2025-09-07'    // Dimanche
        ]);

        $controller = new CartItemLocationController();
        
        try {
            $response = $controller->updateDates($request, $cartItem);
            $responseData = $response->getData(true);

            $this->info("📊 Status Code: {$response->getStatusCode()}");
            $this->newLine();

            if ($responseData['success']) {
                $this->info("✅ Succès: {$responseData['message']}");
                
                if (isset($responseData['data']['date_adjustments'])) {
                    $adjustments = $responseData['data']['date_adjustments'];
                    $this->info("🔄 Date début originale: {$adjustments['original_start_date']} → ajustée: {$adjustments['adjusted_start_date']}");
                    $this->info("🔄 Date fin originale: {$adjustments['original_end_date']} → ajustée: {$adjustments['adjusted_end_date']}");
                    $this->info("📊 Jours ouvrés: {$adjustments['business_days']}");
                    $this->info("📅 Jours calendaires: {$adjustments['total_calendar_days']}");
                    $this->info("🚫 Dimanches exclus: {$adjustments['sundays_excluded']}");
                }
            } else {
                $this->error("❌ Erreur: {$responseData['message']}");
                
                if (isset($responseData['errors'])) {
                    $this->info("🔍 Détails des erreurs:");
                    foreach ($responseData['errors'] as $field => $errors) {
                        foreach ($errors as $error) {
                            $this->line("  • {$error}");
                        }
                    }
                }
            }

        } catch (\Exception $e) {
            $this->error("💥 Exception capturée: {$e->getMessage()}");
        }

        $this->newLine();
        $this->info('🏁 Test terminé');
        return 0;
    }
}
