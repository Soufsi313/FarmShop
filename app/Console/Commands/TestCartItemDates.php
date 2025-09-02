<?php

namespace App\Console\Commands;

use App\Http\Controllers\CartItemLocationController;
use App\Models\CartItemLocation;
use App\Models\Product;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TestCartItemDates extends Command
{
    protected $signature = 'test:cart-item-dates';
    protected $description = 'Test la mise à jour des dates d\'un item de panier avec messages contextuels';

    public function handle()
    {
        $this->info('🧪 Test des messages contextuels pour la mise à jour des dates');
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

        $this->info("📦 Test avec le produit: {$product->name}");
        $this->info("🛒 Item du panier ID: {$cartItem->id}");
        $this->newLine();

        // Simuler l'authentification
        auth()->login($user);

        // Test 1: Date du dimanche (02/09/2025 est un mardi, donc 01/09/2025 est un lundi et 07/09/2025 est un dimanche)
        $this->info('📅 Test 1: Sélection d\'un dimanche (07/09/2025)');
        $sundayDate = '2025-09-07'; // Dimanche
        
        $request = new Request([
            'start_date' => $sundayDate,
            'end_date' => $sundayDate
        ]);

        $controller = new CartItemLocationController();
        $response = $controller->updateDates($request, $cartItem);
        $responseData = $response->getData(true);

        if ($responseData['success']) {
            $this->info("✅ Succès: {$responseData['message']}");
            if (isset($responseData['data']['date_adjustments'])) {
                $adjustments = $responseData['data']['date_adjustments'];
                $this->info("🔄 Date originale: {$adjustments['original_start_date']} → Date ajustée: {$adjustments['adjusted_start_date']}");
            }
        } else {
            $this->error("❌ Erreur: {$responseData['message']}");
        }
        $this->newLine();

        // Test 2: Dates identiques (même jour - aujourd'hui)
        $this->info('📅 Test 2: Dates identiques (02/09/2025)');
        $today = '2025-09-02'; // Mardi
        
        $request2 = new Request([
            'start_date' => $today,
            'end_date' => $today
        ]);

        $response2 = $controller->updateDates($request2, $cartItem);
        $responseData2 = $response2->getData(true);

        if ($responseData2['success']) {
            $this->info("✅ Succès: {$responseData2['message']}");
            if (isset($responseData2['data']['date_adjustments'])) {
                $adjustments = $responseData2['data']['date_adjustments'];
                $this->info("📊 Jours ouvrés: {$adjustments['business_days']}");
                $this->info("📅 Jours calendaires: {$adjustments['total_calendar_days']}");
            }
        } else {
            $this->error("❌ Erreur: {$responseData2['message']}");
        }
        $this->newLine();

        // Test 3: Période trop courte si min_rental_days > 1
        if ($product->min_rental_days > 1) {
            $this->info("📅 Test 3: Période trop courte (minimum: {$product->min_rental_days} jours)");
            
            $request3 = new Request([
                'start_date' => $today,
                'end_date' => $today
            ]);

            $response3 = $controller->updateDates($request3, $cartItem);
            $responseData3 = $response3->getData(true);

            if ($responseData3['success']) {
                $this->info("✅ Succès: {$responseData3['message']}");
            } else {
                $this->error("❌ Erreur attendue: {$responseData3['message']}");
            }
            $this->newLine();
        }

        $this->info('🏁 Tests terminés');
        return 0;
    }
}
