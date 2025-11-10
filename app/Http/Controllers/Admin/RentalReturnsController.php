<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderLocation;
use App\Models\OrderItemLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class RentalReturnsController extends Controller
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
     * Afficher la liste des retours de location
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

        // Filtrage par statut d'inspection
        if ($request->filled('inspection_status')) {
            $query->where('inspection_status', $request->inspection_status);
        }

        // Filtrage par statut de retour
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // Par défaut, afficher seulement les retours (completed, closed, inspecting, finished)
            $query->whereIn('status', ['completed', 'closed', 'inspecting', 'finished']);
        }

        // Filtrage par dates de retour
        if ($request->filled('return_date_from')) {
            $query->where('end_date', '>=', $request->return_date_from);
        }

        if ($request->filled('return_date_to')) {
            $query->where('end_date', '<=', $request->return_date_to);
        }

        // Tri par défaut par date de fin décroissante
        $orderLocations = $query->orderBy('end_date', 'desc')->paginate(20);

        // Calculer les statistiques
        $stats = [
            'total_returns' => OrderLocation::whereIn('status', ['completed', 'closed', 'inspecting', 'finished'])->count(),
            'pending_inspection' => OrderLocation::where('status', 'closed')->count(),
            'in_inspection' => OrderLocation::where('status', 'inspecting')->count(),
            'completed_inspection' => OrderLocation::where('status', 'finished')->count(),
            'awaiting_return' => OrderLocation::where('status', 'completed')->count(),
            'overdue_returns' => OrderLocation::where('status', 'active')
                ->where('end_date', '<', now())
                ->count(),
            'total_penalties' => OrderLocation::where('status', 'finished')->sum('penalty_amount'),
            'total_damage_costs' => OrderItemLocation::sum('item_damage_cost')
        ];

        return view('admin.rental-returns.index', compact('orderLocations', 'stats'));
    }

    /**
     * Afficher les détails d'un retour de location
     */
    public function show(OrderLocation $orderLocation)
    {
        $this->checkAdminAccess();

        // Charger toutes les relations nécessaires
        $orderLocation->load([
            'user',
            'orderItemLocations.product'
        ]);

        return view('admin.rental-returns.show', compact('orderLocation'));
    }

    /**
     * Démarrer l'inspection d'un retour
     */
    public function startInspection(OrderLocation $orderLocation)
    {
        $this->checkAdminAccess();

        if ($orderLocation->status !== 'closed') {
            return redirect()->back()->with('error', 'Cette location ne peut pas être inspectée dans son état actuel.');
        }

        $orderLocation->update([
            'status' => 'inspecting',
            'inspection_status' => 'in_progress',
            'inspection_started_at' => now()
        ]);

        return redirect()->back()->with('success', 'Inspection démarrée avec succès.');
    }

    /**
     * Finaliser l'inspection
     */
    public function finishInspection(OrderLocation $orderLocation, Request $request)
    {
        $this->checkAdminAccess();

        $request->validate([
            'items' => 'required|array',
            'items.*.condition_at_return' => 'required|in:excellent,good,poor',
            'items.*.item_inspection_notes' => 'nullable|string|max:1000',
            'items.*.has_damages' => 'required|boolean',
            'damage_photos.*' => 'nullable|image|mimes:jpeg,jpg,png|max:5120', // 5MB max
            'late_fees' => 'nullable|numeric|min:0|max:999999.99',
            'damage_cost' => 'nullable|numeric|min:0|max:999999.99',
            'general_notes' => 'nullable|string|max:2000'
        ]);

        DB::beginTransaction();
        try {
            $hasGlobalDamages = false;
            $damagePhotoPaths = [];

            // Gérer l'upload des photos de dommages si elles existent
            if ($request->hasFile('damage_photos')) {
                $uploadPath = 'rental-inspections/' . $orderLocation->id;
                
                foreach ($request->file('damage_photos') as $index => $photo) {
                    $filename = 'damage_' . time() . '_' . $index . '.' . $photo->getClientOriginalExtension();
                    $path = $photo->storeAs($uploadPath, $filename, 'public');
                    $damagePhotoPaths[] = $path;
                }
            }

            // Mettre à jour chaque item avec les résultats de l'inspection
            foreach ($request->items as $itemId => $itemData) {
                $orderItem = $orderLocation->orderItemLocations()->findOrFail($itemId);
                
                $hasDamages = (bool) ($itemData['has_damages'] ?? false);
                if ($hasDamages) {
                    $hasGlobalDamages = true;
                }

                $orderItem->update([
                    'condition_at_return' => $itemData['condition_at_return'],
                    'item_inspection_notes' => $itemData['item_inspection_notes'] ?? null,
                    'has_damages' => $hasDamages
                ]);
            }

            // Récupérer les frais saisis dans le formulaire
            $lateFees = floatval($request->late_fees ?? 0);
            $damageCost = floatval($request->damage_cost ?? 0);
            
            // Calculer le total des pénalités
            $totalPenalties = $lateFees + $damageCost;
            $depositRefund = max(0, $orderLocation->deposit_amount - $totalPenalties);
            
            // Préparer les données pour l'inspection
            $inspectionData = [
                'product_condition' => 'good', // Valeur par défaut, peut être ajustée selon les items
                'has_damages' => $hasGlobalDamages,
                'damage_notes' => $request->general_notes,
                'damage_photos' => $damagePhotoPaths,
                'inspection_notes' => $request->general_notes,
                'manual_damage_cost' => $damageCost // Passer le coût manuel
            ];

            // Mettre à jour les frais avant l'inspection finale
            $orderLocation->update([
                'late_fees' => $lateFees,
                'damage_cost' => $damageCost,
                'total_penalties' => $totalPenalties,
                'deposit_refund' => $depositRefund,
                'auto_calculate_damages' => false // Désactiver le calcul auto
            ]);

            // Utiliser la méthode du modèle pour terminer l'inspection
            $orderLocation->completeInspection($inspectionData);

            DB::commit();

            // Récupérer les valeurs calculées après l'inspection
            $orderLocation->refresh();

            // 🤖 Envoyer message Mr Clank et email d'inspection
            $this->sendMrClankMessage($orderLocation, $totalPenalties, $depositRefund);
            
            // 📧 Envoyer l'email d'inspection au client
            try {
                Mail::to($orderLocation->user->email)->send(new \App\Mail\RentalOrderInspection($orderLocation));
                \Log::info('Email d\'inspection envoyé', [
                    'order_location_id' => $orderLocation->id,
                    'user_email' => $orderLocation->user->email
                ]);
            } catch (\Exception $e) {
                \Log::error('Erreur envoi email d\'inspection', [
                    'order_location_id' => $orderLocation->id,
                    'error' => $e->getMessage()
                ]);
            }

            $statusMessage = $hasGlobalDamages 
                ? 'Inspection terminée avec dommages détectés. Caution capturée: ' . number_format($orderLocation->damage_cost, 2) . '€'
                : 'Inspection terminée sans dommage. Caution libérée: ' . number_format($depositRefund, 2) . '€';

            return redirect()->back()->with('success', $statusMessage);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erreur lors de la finalisation de l\'inspection: ' . $e->getMessage());
        }
    }

    /**
     * Marquer un retour comme reçu (passage de completed à closed)
     */
    public function markAsReturned(OrderLocation $orderLocation)
    {
        $this->checkAdminAccess();

        if ($orderLocation->status !== 'completed') {
            return redirect()->back()->with('error', 'Cette location ne peut pas être marquée comme retournée.');
        }

        $orderLocation->update([
            'status' => 'closed',
            'actual_return_date' => now()
        ]);

        return redirect()->back()->with('success', 'Retour confirmé. Prêt pour inspection.');
    }

    /**
     * Exporter les données des retours
     */
    public function export(Request $request)
    {
        $this->checkAdminAccess();

        $query = OrderLocation::with(['user', 'orderItemLocations.product'])
            ->whereIn('status', ['completed', 'closed', 'inspecting', 'finished']);

        // Appliquer les mêmes filtres que l'index
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('inspection_status')) {
            $query->where('inspection_status', $request->inspection_status);
        }

        $returns = $query->orderBy('end_date', 'desc')->get();

        $data = $returns->map(function ($return) {
            return [
                'Numéro Commande' => $return->order_number,
                'Client' => $return->user->name,
                'Email' => $return->user->email,
                'Statut' => $return->status,
                'Statut Inspection' => $return->inspection_status ?? 'N/A',
                'Date Fin Location' => $return->end_date,
                'Date Retour Effectif' => $return->actual_return_date ?? 'N/A',
                'Dépôt Initial' => number_format($return->deposit_amount, 2),
                'Coûts Dégâts' => number_format($return->penalty_amount ?? 0, 2),
                'Remboursement' => number_format($return->deposit_refund ?? 0, 2),
                'Notes Inspection' => $return->inspection_notes ?? ''
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
            'filename' => 'retours_location_' . now()->format('Y-m-d') . '.csv'
        ]);
    }

    /**
     * Envoyer un message automatique via Mr Clank
     */
    private function sendMrClankMessage(OrderLocation $orderLocation, $totalPenalties, $refundAmount)
    {
        try {
            // Créer le message système de Mr Clank
            $message = "🤖 **Mr Clank - Message Automatique**\n\n";
            $message .= "Bonjour {$orderLocation->user->name},\n\n";
            $message .= "Votre location #{$orderLocation->order_number} a été finalisée avec succès !\n\n";
            $message .= "📋 **Détails de l'inspection :**\n";
            $message .= "- Date de retour : " . now()->format('d/m/Y à H:i') . "\n";
            $message .= "- Statut : Inspection terminée\n";
            
            $message .= "\n💰 **Détails de la caution :**\n";
            $message .= "- Caution versée : " . number_format($orderLocation->deposit_amount, 2) . "€\n";
            
            // Détailler les frais
            if ($orderLocation->late_fees > 0 || $orderLocation->damage_cost > 0) {
                if ($orderLocation->late_fees > 0) {
                    $message .= "- Frais de retard ({$orderLocation->late_days} jour" . ($orderLocation->late_days > 1 ? 's' : '') . ") : " . number_format($orderLocation->late_fees, 2) . "€\n";
                }
                if ($orderLocation->damage_cost > 0) {
                    $message .= "- Frais de dommages : " . number_format($orderLocation->damage_cost, 2) . "€\n";
                }
                $message .= "- **Total des pénalités : " . number_format($totalPenalties, 2) . "€**\n";
                $message .= "- **Montant à vous rembourser : " . number_format($refundAmount, 2) . "€**\n";
                $message .= "\n⚠️ Des pénalités ont été appliquées suite à l'inspection.\n";
            } else {
                $message .= "- **Caution intégralement remboursée : " . number_format($refundAmount, 2) . "€**\n";
                $message .= "\n✅ Aucun problème constaté !\n";
            }
            
            $message .= "\n🏦 Le remboursement sera effectué sous 3-5 jours ouvrés sur votre moyen de paiement original.\n";
            $message .= "\nMerci de votre confiance !\n\n";
            $message .= "---\n";
            $message .= "🤖 Message automatique généré par Mr Clank\n";
            $message .= "Système de gestion FarmShop";

            // Envoyer le message dans la boîte de réception utilisateur
            \App\Models\Message::create([
                'user_id' => $orderLocation->user_id,
                'sender_id' => 103, // ID de Mr Clank 🤖 (system@farmshop.local)
                'type' => 'system',
                'subject' => "🤖 Location #{$orderLocation->order_number} finalisée - Caution remboursée",
                'content' => $message,
                'status' => 'unread',
                'priority' => 'high',
                'is_important' => true,
            ]);

            \Log::info('Message Mr Clank envoyé', [
                'order_location_id' => $orderLocation->id,
                'user_id' => $orderLocation->user_id,
                'type' => 'rental_finalized',
                'deposit_amount' => $orderLocation->deposit_amount,
                'late_fees' => $orderLocation->late_fees ?? 0,
                'damage_costs' => $orderLocation->damage_cost ?? 0,
                'total_penalties' => $totalPenalties,
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
