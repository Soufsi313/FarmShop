<?php

namespace App\Console\Commands;

use App\Http\Controllers\CartItemLocationController;
use App\Models\CartItemLocation;
use App\Models\Product;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TestSpecificDates extends Command
{
    protected $signature = 'test:specific-dates';
    protected $description = 'Test des cas spécifiques : 03/09 au 03/09 et 03/09 au 07/09';

    public function handle()
    {
        $this->info('🧪 Test des cas spécifiques signalés par l\'utilisateur');
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

        $controller = new CartItemLocationController();

        // Test 1: 03/09 au 03/09 (même jour)
        $this->info('📅 Test 1: du 03/09/2025 au 03/09/2025 (même jour - location 24h)');
        
        $request1 = new Request([
            'start_date' => '2025-09-03',
            'end_date' => '2025-09-03'
        ]);

        try {
            $response1 = $controller->updateDates($request1, $cartItem);
            $responseData1 = $response1->getData(true);

            $this->info("📊 Status Code: {$response1->getStatusCode()}");

            if ($responseData1['success']) {
                $this->info("✅ Succès: {$responseData1['message']}");
                
                if (isset($responseData1['data']['date_adjustments'])) {
                    $adjustments = $responseData1['data']['date_adjustments'];
                    $this->info("📊 Jours ouvrés: {$adjustments['business_days']}");
                    $this->info("📅 Jours calendaires: {$adjustments['total_calendar_days']}");
                }
            } else {
                $this->error("❌ Erreur: {$responseData1['message']}");
            }

        } catch (\Exception $e) {
            $this->error("💥 Exception: {$e->getMessage()}");
        }

        $this->newLine();

        // Test 2: 03/09 au 07/09 (dimanche inclus)
        $this->info('📅 Test 2: du 03/09/2025 au 07/09/2025 (dimanche inclus)');
        
        $request2 = new Request([
            'start_date' => '2025-09-03',
            'end_date' => '2025-09-07'  // Dimanche
        ]);

        try {
            $response2 = $controller->updateDates($request2, $cartItem);
            $responseData2 = $response2->getData(true);

            $this->info("📊 Status Code: {$response2->getStatusCode()}");

            if ($responseData2['success']) {
                $this->info("✅ Succès: {$responseData2['message']}");
                
                if (isset($responseData2['data']['date_adjustments'])) {
                    $adjustments = $responseData2['data']['date_adjustments'];
                    $this->info("🔄 Date début originale: {$adjustments['original_start_date']} → ajustée: {$adjustments['adjusted_start_date']}");
                    $this->info("🔄 Date fin originale: {$adjustments['original_end_date']} → ajustée: {$adjustments['adjusted_end_date']}");
                    $this->info("📊 Jours ouvrés: {$adjustments['business_days']}");
                    $this->info("📅 Jours calendaires: {$adjustments['total_calendar_days']}");
                    $this->info("🚫 Dimanches exclus: {$adjustments['sundays_excluded']}");
                }
            } else {
                $this->error("❌ Erreur: {$responseData2['message']}");
            }

        } catch (\Exception $e) {
            $this->error("💥 Exception: {$e->getMessage()}");
        }

        $this->newLine();
        $this->info('🏁 Tests terminés');
        return 0;
    }
}
