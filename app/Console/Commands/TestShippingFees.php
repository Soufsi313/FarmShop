<?php

namespace App\Console\Commands;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Console\Command;

class TestShippingFees extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:shipping-fees';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tester le système de frais de livraison automatiques';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚚 TEST DES FRAIS DE LIVRAISON AUTOMATIQUES');
        $this->info('==========================================');
        $this->newLine();

        // Créer ou récupérer un panier de test
        $user = User::first();
        if (!$user) {
            $this->error('Aucun utilisateur trouvé. Créez d\'abord un utilisateur.');
            return 1;
        }

        $cart = Cart::firstOrCreate(
            ['user_id' => $user->id, 'status' => 'active'],
            ['tax_rate' => 20.00]
        );

        $this->info("📦 Test avec le panier ID: {$cart->id}");
        $this->newLine();

        // Tests de différents montants
        $testCases = [
            ['amount' => 15.00, 'description' => 'Panier < 25€ (doit avoir 2.50€ de frais)'],
            ['amount' => 25.00, 'description' => 'Panier = 25€ (doit être gratuit)'],
            ['amount' => 30.00, 'description' => 'Panier > 25€ (doit être gratuit)'],
            ['amount' => 24.99, 'description' => 'Panier 24.99€ (doit avoir 2.50€ de frais)'],
            ['amount' => 50.00, 'description' => 'Panier 50€ (doit être gratuit)']
        ];

        foreach ($testCases as $test) {
            $this->testShippingAmount($cart, $test['amount'], $test['description']);
            $this->newLine();
        }

        $this->info('✅ Tests terminés avec succès !');
        return 0;
    }

    private function testShippingAmount(Cart $cart, float $amount, string $description)
    {
        $this->info("🧪 {$description}");
        
        // Simuler un panier avec ce montant
        $taxAmount = $amount * 0.20; // 20% TVA
        $shippingCost = $cart->calculateShippingCost($amount);
        $freeShippingEligible = $cart->isFreeShippingEligible($amount);
        $total = $amount + $taxAmount + $shippingCost;
        
        // Mettre à jour directement avec tous les calculs
        $cart->update([
            'subtotal' => $amount,
            'tax_amount' => $taxAmount,
            'shipping_cost' => $shippingCost,
            'free_shipping_eligible' => $freeShippingEligible,
            'total' => $total
        ]);
        
        // Obtenir le résumé
        $summary = $cart->getCostSummary();
        
        // Afficher les résultats
        $this->line("   Sous-total: {$summary['subtotal']}€");
        $this->line("   TVA (20%): {$summary['tax_amount']}€");
        $this->line("   Frais livraison: {$summary['shipping_cost']}€");
        $this->line("   Total: {$summary['total']}€");
        $this->line("   🎁 {$summary['shipping_message']}");
        
        if (!$summary['free_shipping_eligible'] && $summary['amount_for_free_shipping'] > 0) {
            $this->line("   💡 Ajoutez {$summary['amount_for_free_shipping']}€ pour la livraison gratuite");
        }
        
        // Vérification des résultats attendus
        if ($amount < 25.00) {
            if ($summary['shipping_cost'] == 2.50) {
                $this->line("   ✅ Frais de livraison corrects");
            } else {
                $this->error("   ❌ Erreur: frais attendus 2.50€, obtenus {$summary['shipping_cost']}€");
            }
        } else {
            if ($summary['shipping_cost'] == 0.00) {
                $this->line("   ✅ Livraison gratuite correcte");
            } else {
                $this->error("   ❌ Erreur: livraison devrait être gratuite, frais: {$summary['shipping_cost']}€");
            }
        }
    }
}
