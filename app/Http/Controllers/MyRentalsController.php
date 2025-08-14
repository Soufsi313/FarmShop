<?php

namespace App\Http\Controllers;

use App\Models\OrderLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MyRentalsController extends Controller
{
    /**
     * Afficher la liste des locations de l'utilisateur connecté
     */
    public function index()
    {
        // Rediriger vers la nouvelle page des locations
        return redirect()->route('rental-orders.index');
    }

    /**
     * Afficher le détail d'une location
     */
    public function show(OrderLocation $orderLocation)
    {
        $user = Auth::user();
        
        // Vérifier que la location appartient à l'utilisateur
        if ($orderLocation->user_id !== $user->id) {
            abort(403, 'Cette location ne vous appartient pas');
        }

        // Charger les relations nécessaires
        $orderLocation->load(['items.product', 'user']);

        return view('my-rentals.show', compact('orderLocation'));
    }

    /**
     * Clôturer une location (signaler le retour)
     */
    public function close(Request $request, OrderLocation $orderLocation)
    {
        $user = Auth::user();
        
        // Vérifier que la location appartient à l'utilisateur
        if ($orderLocation->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Cette location ne vous appartient pas'
            ], 403);
        }

        // Vérifier que la location peut être clôturée
        if (!$orderLocation->can_be_closed) {
            return response()->json([
                'success' => false,
                'message' => 'Cette location ne peut pas être clôturée dans son état actuel'
            ], 400);
        }

        try {
            $now = Carbon::now();
            $lateDays = 0;
            $lateFees = 0;

            // Calculer les jours de retard si applicable
            if ($now->isAfter($orderLocation->end_date)) {
                $lateDays = $now->diffInDays($orderLocation->end_date);
                $lateFees = $lateDays * 10; // 10€ par jour de retard
            }

            // Mettre à jour la location
            $orderLocation->update([
                'status' => 'completed',
                'actual_return_date' => $now,
                'late_days' => $lateDays,
                'late_fees' => $lateFees,
                'total_penalties' => $lateFees,
                'completed_at' => $now
            ]);

            return response()->json([
                'success' => true,
                'message' => $lateDays > 0 
                    ? "Location clôturée avec {$lateDays} jour(s) de retard. Pénalité: {$lateFees}€"
                    : 'Location clôturée avec succès',
                'data' => [
                    'late_days' => $lateDays,
                    'late_fees' => $lateFees,
                    'new_status' => 'completed'
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la clôture: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtenir le statut d'une location pour affichage
     */
    private function getRentalDisplayStatus(OrderLocation $orderLocation)
    {
        $now = Carbon::now();
        
        switch ($orderLocation->status) {
            case 'confirmed':
                if ($now->isBefore($orderLocation->start_date)) {
                    return [
                        'label' => 'À venir',
                        'class' => 'bg-blue-100 text-blue-800',
                        'can_close' => false
                    ];
                } elseif ($now->between($orderLocation->start_date, $orderLocation->end_date)) {
                    return [
                        'label' => 'En cours',
                        'class' => 'bg-green-100 text-green-800',
                        'can_close' => true
                    ];
                } else {
                    return [
                        'label' => 'En retard',
                        'class' => 'bg-red-100 text-red-800',
                        'can_close' => true
                    ];
                }
                
            case 'started':
                if ($now->isAfter($orderLocation->end_date)) {
                    return [
                        'label' => 'En retard',
                        'class' => 'bg-red-100 text-red-800',
                        'can_close' => true
                    ];
                } else {
                    return [
                        'label' => 'En cours',
                        'class' => 'bg-green-100 text-green-800',
                        'can_close' => true
                    ];
                }
                
            case 'completed':
                return [
                    'label' => 'Terminée',
                    'class' => 'bg-gray-100 text-gray-800',
                    'can_close' => false
                ];
                
            case 'closed':
                return [
                    'label' => 'Clôturée',
                    'class' => 'bg-gray-100 text-gray-800',
                    'can_close' => false
                ];
                
            case 'finished':
                return [
                    'label' => 'Finalisée',
                    'class' => 'bg-green-100 text-green-800',
                    'can_close' => false
                ];
                
            default:
                return [
                    'label' => 'Statut inconnu',
                    'class' => 'bg-gray-100 text-gray-800',
                    'can_close' => false
                ];
        }
    }

    /**
     * Télécharger la facture d'une location
     */
    public function downloadInvoice(OrderLocation $orderLocation)
    {
        // Vérifier que la location appartient à l'utilisateur connecté
        if ($orderLocation->user_id !== auth()->id()) {
            abort(403, 'Accès non autorisé');
        }

        // Vérifier que la facture peut être générée
        if (!$orderLocation->canGenerateInvoice()) {
            return redirect()->back()->with('error', 'La facture ne peut pas encore être générée pour cette location.');
        }

        try {
            // Générer la facture PDF
            $filePath = $orderLocation->generateInvoicePdf();
            
            if (!file_exists($filePath)) {
                return redirect()->back()->with('error', 'Erreur lors de la génération de la facture.');
            }

            $filename = 'facture-location-' . $orderLocation->invoice_number . '.pdf';

            return response()->download($filePath, $filename, [
                'Content-Type' => 'application/pdf',
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur lors du téléchargement de la facture de location', [
                'order_location_id' => $orderLocation->id,
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Erreur lors du téléchargement de la facture. Veuillez réessayer.');
        }
    }
}
