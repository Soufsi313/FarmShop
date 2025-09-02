<?php

namespace App\Console\Commands;

use App\Http\Controllers\CartItemLocationController;
use App\Models\CartItemLocation;
use App\Models\Product;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TestTomorrowRestriction extends Command
{
    protected $signature = 'test:tomorrow-restriction';
    protected $description = 'Test de la restriction : pas de location le jour même, minimum demain';

    public function handle()
    {
        $this->info('🧪 Test de la restriction : locations minimum demain');
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
            $cartItem = $cartLocation->addProduct($product, 1, now()->addDay(), now()->addDays(2));
        }

        $this->info("📦 Produit testé: {$product->name}");
        $this->info("🛒 Item du panier ID: {$cartItem->id}");
        $this->info("📅 Aujourd'hui: " . now()->format('d/m/Y'));
        $this->info("📅 Demain: " . now()->addDay()->format('d/m/Y'));
        $this->newLine();

        // Simuler l'authentification
        auth()->login($user);

        $controller = new CartItemLocationController();

        // Test 1: Essayer de louer aujourd'hui (02/09/2025) - DOIT ÉCHOUER
        $this->info('📅 Test 1: Essayer de louer aujourd\'hui (' . now()->format('d/m/Y') . ') - DOIT ÉCHOUER');
        
        $request1 = new Request([
            'start_date' => now()->format('Y-m-d'),  // Aujourd'hui
            'end_date' => now()->addDay()->format('Y-m-d')  // Demain
        ]);

        try {
            $response1 = $controller->updateDates($request1, $cartItem);
            $responseData1 = $response1->getData(true);

            $this->info("📊 Status Code: {$response1->getStatusCode()}");

            if ($responseData1['success']) {
                $this->error("❌ PROBLÈME: La location aujourd'hui a été acceptée alors qu'elle devrait être refusée");
                $this->error("   Message reçu: {$responseData1['message']}");
            } else {
                $this->info("✅ CORRECT: La location aujourd'hui a été refusée");
                $this->info("   Message: {$responseData1['message']}");
            }

        } catch (\Exception $e) {
            $this->error("💥 Exception: {$e->getMessage()}");
        }

        $this->newLine();

        // Test 2: Louer à partir de demain - DOIT RÉUSSIR
        $this->info('📅 Test 2: Louer à partir de demain (' . now()->addDay()->format('d/m/Y') . ') - DOIT RÉUSSIR');
        
        $request2 = new Request([
            'start_date' => now()->addDay()->format('Y-m-d'),  // Demain
            'end_date' => now()->addDays(2)->format('Y-m-d')   // Après-demain
        ]);

        try {
            $response2 = $controller->updateDates($request2, $cartItem);
            $responseData2 = $response2->getData(true);

            $this->info("📊 Status Code: {$response2->getStatusCode()}");

            if ($responseData2['success']) {
                $this->info("✅ CORRECT: La location à partir de demain a été acceptée");
                $this->info("   Message: {$responseData2['message']}");
                
                if (isset($responseData2['data']['date_info'])) {
                    $dateInfo = $responseData2['data']['date_info'];
                    $this->info("📊 Jours ouvrés: {$dateInfo['business_days']}");
                }
            } else {
                $this->error("❌ PROBLÈME: La location à partir de demain a été refusée");
                $this->error("   Message: {$responseData2['message']}");
            }

        } catch (\Exception $e) {
            $this->error("💥 Exception: {$e->getMessage()}");
        }

        $this->newLine();
        $this->info('🏁 Tests terminés');
        return 0;
    }
}
