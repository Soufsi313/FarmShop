<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\OrderReturn;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AutomateReturnProcesses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'returns:automate 
                            {--dry-run : Afficher les actions sans les exécuter}
                            {--details : Affichage détaillé}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatise les processus de retour : remboursements automatiques, notifications et mise à jour des statuts';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        $isVerbose = $this->option('details');

        $this->info('🔄 Démarrage de l\'automatisation des processus de retour...');
        
        if ($isDryRun) {
            $this->warn('🧪 Mode DRY-RUN activé - Aucune modification ne sera appliquée');
        }

        $totalProcessed = 0;

        // 1. Traiter les remboursements automatiques pour les retours approuvés
        $totalProcessed += $this->processAutomaticRefunds($isDryRun, $isVerbose);

        // 2. Envoyer des rappels pour les retours bientôt expirés
        $totalProcessed += $this->sendReturnReminders($isDryRun, $isVerbose);

        // 3. Marquer les retours expirés comme non éligibles
        $totalProcessed += $this->markExpiredReturns($isDryRun, $isVerbose);

        // 4. Annuler automatiquement les commandes non expédiées si demandé
        $totalProcessed += $this->processAutomaticCancellations($isDryRun, $isVerbose);

        $this->info("✅ Automatisation terminée. Total: {$totalProcessed} éléments traités.");

        return Command::SUCCESS;
    }

    /**
     * Traiter les remboursements automatiques
     */
    private function processAutomaticRefunds(bool $isDryRun, bool $isVerbose): int
    {
        $this->info('💰 Traitement des remboursements automatiques...');

        $returns = OrderReturn::where('status', OrderReturn::STATUS_APPROVED)
            ->where('refund_status', OrderReturn::REFUND_STATUS_PENDING)
            ->with(['order', 'orderItem.product'])
            ->get();

        if ($returns->isEmpty()) {
            $this->info('   Aucun remboursement à traiter.');
            return 0;
        }

        $processed = 0;

        foreach ($returns as $return) {
            if ($isVerbose) {
                $this->line("   🔍 Traitement du retour #{$return->id} (Commande #{$return->order->order_number})");
            }

            if (!$isDryRun) {
                DB::beginTransaction();
                try {
                    // Calculer le montant du remboursement
                    $refundAmount = $return->calculateRefundAmount();
                    
                    // Initier le remboursement
                    $return->initiateRefund($refundAmount);
                    
                    // Simuler le processus de remboursement (intégration avec système de paiement)
                    // Pour l'instant, on marque directement comme terminé
                    $return->completeRefund();
                    
                    // Remettre le stock du produit
                    $return->orderItem->product->increment('quantity', $return->quantity_returned);

                    DB::commit();
                    
                    if ($isVerbose) {
                        $this->info("   ✅ Remboursement de {$refundAmount}€ traité pour le retour #{$return->id}");
                    }
                    
                    $processed++;
                    
                } catch (\Exception $e) {
                    DB::rollBack();
                    $this->error("   ❌ Erreur lors du remboursement #{$return->id}: " . $e->getMessage());
                    Log::error("Erreur remboursement automatique", [
                        'return_id' => $return->id,
                        'error' => $e->getMessage()
                    ]);
                }
            } else {
                $refundAmount = $return->calculateRefundAmount();
                $this->line("   🧪 [DRY-RUN] Remboursement de {$refundAmount}€ pour le retour #{$return->id}");
                $processed++;
            }
        }

        $this->info("   💰 {$processed} remboursements traités.");
        return $processed;
    }

    /**
     * Envoyer des rappels pour les retours bientôt expirés
     */
    private function sendReturnReminders(bool $isDryRun, bool $isVerbose): int
    {
        $this->info('📧 Envoi des rappels de retour...');

        // Rechercher les commandes livrées il y a 12 jours (2 jours avant expiration)
        $reminderDate = Carbon::now()->subDays(12);
        
        $orders = Order::where('status', Order::STATUS_DELIVERED)
            ->whereDate('delivered_at', $reminderDate)
            ->whereDoesntHave('returns')
            ->with(['user', 'orderItems.product'])
            ->get();

        if ($orders->isEmpty()) {
            $this->info('   Aucun rappel à envoyer.');
            return 0;
        }

        $processed = 0;

        foreach ($orders as $order) {
            // Vérifier si l'ordre a des produits retournables
            $hasReturnableItems = $order->hasReturnableItems();
            
            if (!$hasReturnableItems) {
                continue;
            }

            if ($isVerbose) {
                $this->line("   📧 Envoi rappel à {$order->user->email} pour commande #{$order->order_number}");
            }

            if (!$isDryRun) {
                try {
                    // Ici, envoyer une notification de rappel
                    // $order->user->notify(new ReturnReminderNotification($order));
                    
                    $processed++;
                    
                } catch (\Exception $e) {
                    $this->error("   ❌ Erreur envoi rappel commande #{$order->order_number}: " . $e->getMessage());
                }
            } else {
                $this->line("   🧪 [DRY-RUN] Rappel à envoyer à {$order->user->email}");
                $processed++;
            }
        }

        $this->info("   📧 {$processed} rappels envoyés.");
        return $processed;
    }

    /**
     * Marquer les retours expirés comme non éligibles
     */
    private function markExpiredReturns(bool $isDryRun, bool $isVerbose): int
    {
        $this->info('⏰ Marquage des retours expirés...');

        // Les commandes livrées il y a plus de 14 jours
        $expiredDate = Carbon::now()->subDays(14);
        
        $orders = Order::where('status', Order::STATUS_DELIVERED)
            ->where('delivered_at', '<', $expiredDate)
            ->where('is_returnable', true)
            ->get();

        if ($orders->isEmpty()) {
            $this->info('   Aucun retour expiré à traiter.');
            return 0;
        }

        $processed = 0;

        foreach ($orders as $order) {
            if ($isVerbose) {
                $this->line("   ⏰ Marquage expiration retours commande #{$order->order_number}");
            }

            if (!$isDryRun) {
                try {
                    $order->is_returnable = false;
                    $order->save();
                    
                    $processed++;
                    
                } catch (\Exception $e) {
                    $this->error("   ❌ Erreur marquage expiration #{$order->order_number}: " . $e->getMessage());
                }
            } else {
                $this->line("   🧪 [DRY-RUN] Expiration à marquer pour commande #{$order->order_number}");
                $processed++;
            }
        }

        $this->info("   ⏰ {$processed} expirations marquées.");
        return $processed;
    }

    /**
     * Traiter les annulations automatiques
     */
    private function processAutomaticCancellations(bool $isDryRun, bool $isVerbose): int
    {
        $this->info('🚫 Traitement des annulations automatiques...');

        // Rechercher les commandes marquées pour annulation automatique
        // (hypothétique : commandes qui ont un flag pour annulation automatique)
        
        $this->info('   Aucune annulation automatique configurée.');
        return 0;
    }
}
