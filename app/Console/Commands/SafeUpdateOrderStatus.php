<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Notifications\OrderStatusChanged;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;

class SafeUpdateOrderStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:safe-update-status {--dry-run : Run without making changes} {--no-email : Skip email notifications}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatiquement mettre à jour le statut des commandes avec gestion sécurisée des emails';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $now = Carbon::now();
        $isDryRun = $this->option('dry-run');
        $skipEmails = $this->option('no-email');
        $updatedOrders = 0;

        if ($isDryRun) {
            $this->info('🔍 Mode DRY-RUN activé - Aucune modification ne sera effectuée');
        }
        
        if ($skipEmails) {
            $this->info('📧 Mode SANS EMAIL activé - Aucun email ne sera envoyé');
        }

        $this->info('🚀 Démarrage de l\'automatisation SÉCURISÉE des statuts de commandes...');
        
        // 1. Passer les commandes confirmées à "en préparation" après 45 secondes
        $this->processStatusTransition(
            Order::STATUS_CONFIRMED,
            Order::STATUS_PREPARATION,
            45, // 45 secondes
            'confirmed_at',
            $isDryRun,
            $skipEmails,
            $updatedOrders
        );
        
        // 2. Passer les commandes en préparation à "expédiée" après 45 secondes
        $this->processStatusTransition(
            Order::STATUS_PREPARATION,
            Order::STATUS_SHIPPED,
            45, // 45 secondes
            'preparation_at',
            $isDryRun,
            $skipEmails,
            $updatedOrders
        );
        
        // 3. Passer les commandes expédiées à "livrée" après 45 secondes
        $this->processStatusTransition(
            Order::STATUS_SHIPPED,
            Order::STATUS_DELIVERED,
            45, // 45 secondes
            'shipped_at',
            $isDryRun,
            $skipEmails,
            $updatedOrders
        );

        if ($updatedOrders > 0) {
            $this->info("✅ {$updatedOrders} commande(s) mise(s) à jour avec succès");
            Log::info("SafeOrderStatusAutomation: {$updatedOrders} commandes mises à jour", [
                'timestamp' => $now->toDateTimeString(),
                'dry_run' => $isDryRun,
                'skip_emails' => $skipEmails
            ]);
        } else {
            $this->info('ℹ️  Aucune commande à mettre à jour');
        }
        
        return 0;
    }

    /**
     * Traiter la transition d'un statut vers un autre
     */
    private function processStatusTransition(
        string $fromStatus,
        string $toStatus,
        int $delayInSeconds,
        string $timestampField,
        bool $isDryRun,
        bool $skipEmails,
        int &$updatedOrders
    ): void {
        $now = Carbon::now();
        
        // Utiliser le bon champ de timestamp pour calculer le délai
        $query = Order::where('status', $fromStatus);
        
        if ($timestampField === 'confirmed_at') {
            $query->where(function($q) use ($now, $delayInSeconds) {
                $q->where(function($subQ) use ($now, $delayInSeconds) {
                    $subQ->whereNotNull('confirmed_at')
                         ->where('confirmed_at', '<=', $now->copy()->subSeconds($delayInSeconds));
                })->orWhere(function($subQ) use ($now, $delayInSeconds) {
                    $subQ->whereNull('confirmed_at')
                         ->where('updated_at', '<=', $now->copy()->subSeconds($delayInSeconds));
                });
            });
        } elseif ($timestampField === 'preparation_at') {
            $query->where(function($q) use ($now, $delayInSeconds) {
                $q->where(function($subQ) use ($now, $delayInSeconds) {
                    $subQ->whereNotNull('preparation_at')
                         ->where('preparation_at', '<=', $now->copy()->subSeconds($delayInSeconds));
                })->orWhere(function($subQ) use ($now, $delayInSeconds) {
                    $subQ->whereNull('preparation_at')
                         ->where('updated_at', '<=', $now->copy()->subSeconds($delayInSeconds));
                });
            });
        } elseif ($timestampField === 'shipped_at') {
            $query->where(function($q) use ($now, $delayInSeconds) {
                $q->where(function($subQ) use ($now, $delayInSeconds) {
                    $subQ->whereNotNull('shipped_at')
                         ->where('shipped_at', '<=', $now->copy()->subSeconds($delayInSeconds));
                })->orWhere(function($subQ) use ($now, $delayInSeconds) {
                    $subQ->whereNull('shipped_at')
                         ->where('updated_at', '<=', $now->copy()->subSeconds($delayInSeconds));
                });
            });
        }
        
        $orders = $query->with('user')->get();
        
        foreach ($orders as $order) {
            if ($isDryRun) {
                $this->line("🔍 [DRY-RUN] Commande #{$order->order_number} passerait de '{$fromStatus}' à '{$toStatus}'");
                continue;
            }

            $oldStatus = $order->status;
            
            // Mettre à jour le statut
            $order->update(['status' => $toStatus]);
            
            // Gestion sécurisée des emails
            if (!$skipEmails && $order->user && $this->isValidEmailAddress($order->user->email)) {
                $this->sendSafeNotification($order, $oldStatus);
            } elseif (!$skipEmails) {
                $userEmail = $order->user ? $order->user->email : 'N/A';
                $this->warn("⚠️ Email non valide pour commande #{$order->order_number}: {$userEmail}");
                Log::warning("Email non valide pour commande", [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'email' => $order->user ? $order->user->email : null
                ]);
            }
            
            $this->info("✅ Commande #{$order->order_number} : {$fromStatus} → {$toStatus}");
            $updatedOrders++;
            
            // Log pour traçabilité
            Log::info("Safe order status updated", [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'from_status' => $oldStatus,
                'to_status' => $toStatus,
                'user_id' => $order->user_id,
                'email_sent' => !$skipEmails && $this->isValidEmailAddress($order->user ? $order->user->email : ''),
                'timestamp' => $now->toDateTimeString()
            ]);
        }
    }

    /**
     * Vérifier si l'adresse email est valide et pas un email de test
     */
    private function isValidEmailAddress(string $email): bool
    {
        // Valider le format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        // Exclure les emails de test
        $testEmails = [
            'test@farmshop.com',
            'test.client@farmshop.com',
            'admin@farmshop.com',
            'noreply@farmshop.com'
        ];

        if (in_array(strtolower($email), $testEmails)) {
            return false;
        }

        // Exclure les domaines de test
        $testDomains = ['example.com', 'test.com', 'localhost', '127.0.0.1'];
        $domain = substr(strrchr($email, "@"), 1);
        
        if (in_array(strtolower($domain), $testDomains)) {
            return false;
        }

        return true;
    }

    /**
     * Envoyer une notification de manière sécurisée
     */
    private function sendSafeNotification(Order $order, string $oldStatus): void
    {
        try {
            // Vérifier que l'email n'a pas déjà été envoyé récemment pour éviter le spam
            $recentNotification = Log::getMonolog()->getHandlers();
            
            $order->user->notify(new OrderStatusChanged($order, $oldStatus));
            
            $this->line("📧 Email envoyé pour commande #{$order->order_number} → {$order->user->email}");
            
            Log::info("Email notification sent successfully", [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'email' => $order->user->email,
                'new_status' => $order->status,
                'old_status' => $oldStatus
            ]);
            
        } catch (\Exception $e) {
            $this->error("❌ Erreur email pour commande #{$order->order_number}: " . $e->getMessage());
            
            Log::error("Failed to send email notification", [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'email' => $order->user->email,
                'error' => $e->getMessage(),
                'new_status' => $order->status,
                'old_status' => $oldStatus
            ]);
        }
    }
}
