<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderLocation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderLocationController extends Controller
{
    /**
     * Vérifier que l'utilisateur est admin avant chaque action
     */
    private function checkAdminAccess()
    {
        if (!Auth::check() || Auth::user()->role !== 'Admin') {
            abort(403, 'Accès refusé. Seuls les administrateurs peuvent accéder au dashboard.');
        }
    }

    /**
     * Afficher la liste des commandes de location (Admin)
     */
    public function index(Request $request)
    {
        $this->checkAdminAccess();

        $query = OrderLocation::with(['user', 'orderItemLocations.product']);

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Filtrage par statut
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtrage par dates de location
        if ($request->filled('start_date')) {
            $query->where('start_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('end_date', '<=', $request->end_date);
        }

        // Filtrage par date de création
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Tri
        $sortBy = $request->get('sort_by', 'recent');
        switch ($sortBy) {
            case 'oldest':
                $query->oldest();
                break;
            case 'start_date':
                $query->orderBy('start_date', 'asc');
                break;
            case 'end_date':
                $query->orderBy('end_date', 'asc');
                break;
            case 'total_asc':
                $query->orderBy('total_amount', 'asc');
                break;
            case 'total_desc':
                $query->orderBy('total_amount', 'desc');
                break;
            default:
                $query->latest();
        }

        $orders = $query->paginate(20);

        // Statistiques pour le dashboard
        $stats = [
            'total_orders' => OrderLocation::count(),
            'active_rentals' => OrderLocation::where('status', 'active')->count(),
            'pending_returns' => OrderLocation::whereIn('status', ['completed', 'closed'])->count(),
            'revenue_month' => OrderLocation::whereMonth('created_at', now()->month)
                                            ->sum('total_amount'),
        ];

        return view('admin.order-locations.index', compact('orders', 'stats'));
    }

    /**
     * Exporter les commandes en CSV
     */
    public function export(Request $request)
    {
        $this->checkAdminAccess();

        $query = OrderLocation::with(['user', 'orderItemLocations.product']);

        // Appliquer les mêmes filtres que l'index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date_from')) {
            $query->where('start_date', '>=', $request->start_date_from);
        }
        if ($request->filled('start_date_to')) {
            $query->where('start_date', '<=', $request->start_date_to);
        }

        if ($request->filled('end_date_from')) {
            $query->where('end_date', '>=', $request->end_date_from);
        }
        if ($request->filled('end_date_to')) {
            $query->where('end_date', '<=', $request->end_date_to);
        }

        $orders = $query->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="commandes_location_' . now()->format('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            
            // En-têtes CSV
            fputcsv($file, [
                'Numéro commande',
                'Client',
                'Email',
                'Date début',
                'Date fin',
                'Jours location',
                'Nombre produits',
                'Montant total',
                'Dépôt',
                'Statut',
                'Date création'
            ]);

            // Données
            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_number,
                    $order->user->name,
                    $order->user->email,
                    $order->start_date->format('d/m/Y'),
                    $order->end_date->format('d/m/Y'),
                    $order->rental_days,
                    $order->orderItemLocations->count(),
                    number_format($order->total_amount, 2, ',', ' '),
                    number_format($order->deposit_amount, 2, ',', ' '),
                    $order->status,
                    $order->created_at->format('d/m/Y H:i')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Afficher le détail d'une commande de location
     */
    public function show(OrderLocation $orderLocation)
    {
        $this->checkAdminAccess();

        $orderLocation->load([
            'user',
            'orderItemLocations.product.category',
        ]);

        return view('admin.order-locations.show', compact('orderLocation'));
    }

    /**
     * Mettre à jour le statut d'une commande de location
     */
    public function updateStatus(Request $request, OrderLocation $orderLocation)
    {
        $this->checkAdminAccess();

        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,active,completed,closed,inspecting,finished,cancelled',
            'notes' => 'nullable|string|max:1000'
        ]);

        // Si c'est une requête AJAX (depuis le bouton de l'interface)
        if ($request->ajax()) {
            $orderLocation->update(['status' => $validated['status']]);
            return response()->json(['success' => true, 'message' => 'Statut mis à jour avec succès']);
        }

        // Valider les transitions de statut pour les requêtes classiques
        $validTransitions = [
            'pending' => ['confirmed', 'cancelled'],
            'confirmed' => ['active', 'cancelled'],
            'active' => ['completed', 'cancelled'],
            'completed' => ['closed', 'inspecting'],
            'closed' => ['inspecting'],
            'inspecting' => ['finished'],
            'finished' => [],
            'cancelled' => []
        ];

        if (!in_array($validated['status'], $validTransitions[$orderLocation->status] ?? [])) {
            return redirect()->back()->with('error', 'Transition de statut invalide.');
        }

        $orderLocation->update($validated);

        return redirect()->back()->with('success', 'Statut de la location mis à jour avec succès.');
    }

    /**
     * Supprimer une commande de location (soft delete)
     */
    public function destroy(OrderLocation $orderLocation)
    {
        $this->checkAdminAccess();

        if ($orderLocation->status === 'active' || $orderLocation->status === 'pending_return') {
            return redirect()->back()->with('error', 'Impossible de supprimer une location active ou en attente de retour.');
        }

        $orderLocation->delete();

        return redirect()->route('admin.order-locations.index')->with('success', 'Commande de location supprimée avec succès.');
    }

    /**
     * Marquer une location comme en inspection
     */
    public function startInspection(OrderLocation $orderLocation)
    {
        $this->checkAdminAccess();

        if ($orderLocation->status !== 'completed') {
            return redirect()->back()->with('error', 'Seules les locations terminées peuvent être inspectées.');
        }

        $orderLocation->update([
            'status' => 'inspecting',
            'inspected_by' => Auth::id(),
            'inspection_started_at' => now()
        ]);

        return redirect()->back()->with('success', 'Inspection démarrée avec succès.');
    }

    /**
     * Finaliser l'inspection et clôturer la location
     */
    public function finalizeInspection(Request $request, OrderLocation $orderLocation)
    {
        $this->checkAdminAccess();

        $request->validate([
            'inspection_notes' => 'nullable|string|max:1000',
            'damage_fee' => 'nullable|numeric|min:0',
            'condition' => 'required|in:good,damaged,lost'
        ]);

        if ($orderLocation->status !== 'inspecting') {
            return redirect()->back()->with('error', 'Cette location n\'est pas en cours d\'inspection.');
        }

        // Mettre à jour la location
        $orderLocation->update([
            'status' => 'finished',
            'inspection_notes' => $request->inspection_notes,
            'damage_fee' => $request->damage_fee ?? 0,
            'condition' => $request->condition,
            'inspection_completed_at' => now(),
            'finished_at' => now()
        ]);

        // 🎯 RÉINCRÉMENTER LE STOCK DE LOCATION
        foreach ($orderLocation->items as $item) {
            if ($item->product) {
                $item->product->increment('rental_stock', $item->quantity);
                
                \Log::info('Stock de location réincrémenté après inspection', [
                    'product_id' => $item->product->id,
                    'product_name' => $item->product->name,
                    'quantity_returned' => $item->quantity,
                    'new_rental_stock' => $item->product->fresh()->rental_stock,
                    'order_location_id' => $orderLocation->id,
                    'condition' => $request->condition
                ]);
            }
        }

        // 🤖 Envoyer message Mr Clank
        $this->sendMrClankMessage($orderLocation);

        return redirect()->back()->with('success', 'Inspection finalisée et stock réincrémenté. Location clôturée avec succès.');
    }

    /**
     * Envoyer un message automatique via Mr Clank
     */
    private function sendMrClankMessage(OrderLocation $orderLocation)
    {
        try {
            // Calculer les montants de caution
            $depositAmount = $orderLocation->deposit_amount;
            $damageFeesTotal = $orderLocation->damage_fee ?? 0;
            $refundAmount = $depositAmount - $damageFeesTotal;
            
            // Créer le message système de Mr Clank
            $message = "🤖 **Mr Clank - Message Automatique**\n\n";
            $message .= "Bonjour {$orderLocation->user->name},\n\n";
            $message .= "Votre location #{$orderLocation->order_number} a été finalisée avec succès !\n\n";
            $message .= "📋 **Détails de l'inspection :**\n";
            $message .= "- État du matériel : " . ucfirst($orderLocation->condition) . "\n";
            $message .= "- Date de retour : " . now()->format('d/m/Y à H:i') . "\n";
            
            $message .= "\n💰 **Détails de la caution :**\n";
            $message .= "- Caution versée : " . number_format($depositAmount, 2) . "€\n";
            
            if ($damageFeesTotal > 0) {
                $message .= "- Frais de dommages : " . number_format($damageFeesTotal, 2) . "€\n";
                $message .= "- **Montant à vous rembourser : " . number_format($refundAmount, 2) . "€**\n";
                $message .= "\n⚠️ Des frais ont été appliqués suite à l'inspection.\n";
            } else {
                $message .= "- **Caution intégralement remboursée : " . number_format($refundAmount, 2) . "€**\n";
                $message .= "\n✅ Aucun dommage constaté !\n";
            }
            
            $message .= "\n🏦 Le remboursement sera effectué sous 3-5 jours ouvrés sur votre moyen de paiement original.\n";
            $message .= "\nMerci de votre confiance !\n\n";
            $message .= "---\n";
            $message .= "🤖 Message automatique généré par Mr Clank\n";
            $message .= "Système de gestion FarmShop";

            // Envoyer le message dans la boîte de réception utilisateur
            \App\Models\Message::create([
                'sender_id' => 1, // ID de Mr Clank (admin système)
                'recipient_id' => $orderLocation->user_id,
                'subject' => "🤖 Location #{$orderLocation->order_number} finalisée - Caution remboursée",
                'message' => $message,
                'is_system' => true,
                'sent_at' => now()
            ]);

            // Envoyer aussi par email
            \Mail::send('emails.mr-clank-rental-finalized', [
                'orderLocation' => $orderLocation,
                'message' => $message,
                'depositAmount' => $depositAmount,
                'damageFeesTotal' => $damageFeesTotal,
                'refundAmount' => $refundAmount
            ], function ($mail) use ($orderLocation) {
                $mail->to($orderLocation->user->email, $orderLocation->user->name)
                     ->subject("🤖 Mr Clank - Location #{$orderLocation->order_number} finalisée");
            });

            \Log::info('Message Mr Clank envoyé', [
                'order_location_id' => $orderLocation->id,
                'user_id' => $orderLocation->user_id,
                'type' => 'rental_finalized',
                'deposit_amount' => $depositAmount,
                'damage_fees' => $damageFeesTotal,
                'refund_amount' => $refundAmount
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur envoi message Mr Clank', [
                'order_location_id' => $orderLocation->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
