<?php

namespace App\Observers;

use App\Models\Order;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        // DÉSACTIVÉ : Email envoyé seulement après paiement confirmé
        // L'email de confirmation sera envoyé dans updated() quand status = 'confirmed'
        try {
            Log::info("Commande créée {$order->order_number} - Email sera envoyé après paiement");
        } catch (\Exception $e) {
            Log::error("Erreur log création commande {$order->order_number}: " . $e->getMessage());
        }
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        // Vérifier si le statut a changé
        if ($order->isDirty('status')) {
            $oldStatus = $order->getOriginal('status');
            $newStatus = $order->status;

            try {
                // Envoyer email selon le nouveau statut
                switch ($newStatus) {
                    case 'confirmed':
                        Mail::send('emails.order.confirmed', ['order' => $order], function ($message) use ($order) {
                            $message->to($order->user->email, $order->user->name)
                                    ->subject("Votre commande #{$order->order_number} a été confirmée");
                        });
                        break;

                    case 'preparing':
                        Mail::send('emails.order.preparing', ['order' => $order], function ($message) use ($order) {
                            $message->to($order->user->email, $order->user->name)
                                    ->subject("Votre commande #{$order->order_number} est en préparation");
                        });
                        break;

                    case 'shipped':
                        Mail::send('emails.order.shipped', ['order' => $order], function ($message) use ($order) {
                            $message->to($order->user->email, $order->user->name)
                                    ->subject("Votre commande #{$order->order_number} a été expédiée");
                        });
                        break;

                    case 'delivered':
                        Mail::send('emails.order.delivered', ['order' => $order], function ($message) use ($order) {
                            $message->to($order->user->email, $order->user->name)
                                    ->subject("Votre commande #{$order->order_number} a été livrée");
                        });
                        break;

                    case 'cancelled':
                        Mail::send('emails.order.cancelled', ['order' => $order], function ($message) use ($order) {
                            $message->to($order->user->email, $order->user->name)
                                    ->subject("Votre commande #{$order->order_number} a été annulée");
                        });
                        break;
                }

                Log::info("Email de statut '{$newStatus}' envoyé pour la commande {$order->order_number}");
            } catch (\Exception $e) {
                Log::error("Erreur envoi email statut commande {$order->order_number}: " . $e->getMessage());
            }
        }

        // Vérifier si le statut de paiement a changé
        if ($order->isDirty('payment_status')) {
            $newPaymentStatus = $order->payment_status;

            try {
                switch ($newPaymentStatus) {
                    case 'paid':
                        // Pas d'email ici - l'email de confirmation sera envoyé avec le changement de statut
                        Log::info("Paiement confirmé pour la commande {$order->order_number} - Email sera envoyé avec la confirmation de commande");
                        break;

                    case 'failed':
                        Mail::send('emails.order.payment-failed', ['order' => $order], function ($message) use ($order) {
                            $message->to($order->user->email, $order->user->name)
                                    ->subject("Problème de paiement pour votre commande #{$order->order_number}");
                        });
                        Log::info("Email de paiement '{$newPaymentStatus}' envoyé pour la commande {$order->order_number}");
                        break;
                }
            } catch (\Exception $e) {
                Log::error("Erreur envoi email paiement commande {$order->order_number}: " . $e->getMessage());
            }
        }
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        // Restaurer le stock des produits SEULEMENT si la commande était payée
        // (c'est-à-dire si le stock avait été décrémenté)
        if ($order->payment_status === 'paid') {
            foreach ($order->items as $item) {
                $item->product->increment('quantity', $item->quantity);
            }
            Log::info("Stock restauré pour la commande supprimée payée {$order->order_number}");
        } else {
            Log::info("Commande supprimée non payée {$order->order_number} - Stock non restauré (correct)");
        }
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        // Décrémenter à nouveau le stock SEULEMENT si la commande était payée
        // (pour éviter de décrémenter le stock d'une commande qui n'était pas payée)
        if ($order->payment_status === 'paid') {
            foreach ($order->items as $item) {
                $item->product->decrement('quantity', $item->quantity);
            }
            Log::info("Stock décrémenté pour la commande restaurée payée {$order->order_number}");
        } else {
            Log::info("Commande restaurée non payée {$order->order_number} - Stock non décrémenté (correct)");
        }
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        // Restaurer le stock des produits SEULEMENT si la commande était payée
        // (c'est-à-dire si le stock avait été décrémenté)
        if ($order->payment_status === 'paid') {
            foreach ($order->items as $item) {
                $item->product->increment('quantity', $item->quantity);
            }
            Log::info("Stock restauré pour la commande définitivement supprimée payée {$order->order_number}");
        } else {
            Log::info("Commande définitivement supprimée non payée {$order->order_number} - Stock non restauré (correct)");
        }
    }
}
