<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class RentalConstraintController extends Controller
{
    /**
     * Obtenir les contraintes de location pour un produit
     */
    public function getProductConstraints(Product $product): JsonResponse
    {
        if (!$product->isRentable()) {
            return response()->json([
                'success' => false,
                'message' => 'Ce produit n\'est pas disponible à la location'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'constraints' => $product->getRentalConstraints(),
                'pricing' => [
                    'daily_price' => $product->rental_price_per_day,
                    'deposit_amount' => $product->deposit_amount,
                    'currency' => 'EUR'
                ]
            ]
        ]);
    }

    /**
     * Valider une période de location pour un produit
     */
    public function validateRentalPeriod(Request $request, Product $product): JsonResponse
    {
        if (!$product->isRentable()) {
            return response()->json([
                'success' => false,
                'message' => 'Ce produit n\'est pas disponible à la location'
            ], 400);
        }

        $validated = $request->validate([
            'start_date' => 'required|date',
            // MODIFICATION TEMPORAIRE POUR TESTS : Permettre les locations d'un jour
            'end_date' => 'required|date|after_or_equal:start_date'
        ], [
            'end_date.after_or_equal' => 'La date de fin doit être postérieure ou égale à la date de début',
            'start_date.required' => 'La date de début est obligatoire',
            'end_date.required' => 'La date de fin est obligatoire',
        ]);

        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);

        $validation = $product->validateRentalPeriod($startDate, $endDate);

        return response()->json([
            'success' => $validation['valid'],
            'data' => $validation,
            'message' => $validation['valid'] 
                ? 'Période de location valide' 
                : 'Période de location non valide'
        ], $validation['valid'] ? 200 : 422);
    }

    /**
     * Obtenir le calendrier de disponibilité pour un produit
     */
    public function getAvailabilityCalendar(Request $request, Product $product): JsonResponse
    {
        if (!$product->isRentable()) {
            return response()->json([
                'success' => false,
                'message' => 'Ce produit n\'est pas disponible à la location'
            ], 400);
        }

        $startDate = $request->filled('start_date') 
            ? Carbon::parse($request->start_date)
            : now();
        
        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->end_date) 
            : now()->addMonths(2);

        $calendar = [];
        $current = $startDate->copy()->startOfDay();

        while ($current->lte($endDate)) {
            $dayOfWeek = $current->dayOfWeek === 0 ? 7 : $current->dayOfWeek;
            $isAvailable = $product->isDayAvailable($dayOfWeek) && 
                          $current->gt(now()->startOfDay());

            $calendar[] = [
                'date' => $current->format('Y-m-d'),
                'day_name' => $current->locale('fr')->isoFormat('dddd'),
                'day_of_week' => $dayOfWeek,
                'is_available' => $isAvailable,
                'is_past' => $current->lte(now()->startOfDay()),
                'is_business_day' => in_array($dayOfWeek, [1, 2, 3, 4, 5, 6])
            ];

            $current->addDay();
        }

        return response()->json([
            'success' => true,
            'data' => [
                'product_id' => $product->id,
                'calendar' => $calendar,
                'constraints' => $product->getRentalConstraints(),
                'period' => [
                    'from' => $startDate->format('Y-m-d'),
                    'to' => $endDate->format('Y-m-d')
                ]
            ]
        ]);
    }

    /**
     * Suggérer des dates de location optimales
     */
    public function suggestOptimalDates(Request $request, Product $product): JsonResponse
    {
        if (!$product->isRentable()) {
            return response()->json([
                'success' => false,
                'message' => 'Ce produit n\'est pas disponible à la location'
            ], 400);
        }

        $preferredDuration = $request->integer('duration', $product->min_rental_days);
        $startFromDate = $request->filled('start_from') 
            ? Carbon::parse($request->start_from)
            : now()->addDay();

        // Trouver la prochaine date de début disponible
        $suggestedStart = $product->getNextAvailableStartDate();
        if ($startFromDate->gt($suggestedStart)) {
            $suggestedStart = $startFromDate->copy();
            
            // S'assurer que c'est un jour disponible
            while (!$product->isDayAvailable($suggestedStart->dayOfWeek === 0 ? 7 : $suggestedStart->dayOfWeek)) {
                $suggestedStart->addDay();
            }
        }

        // Calculer plusieurs options de fin
        $options = [];
        for ($duration = $product->min_rental_days; $duration <= min($product->max_rental_days, $preferredDuration + 2); $duration++) {
            $endDate = $suggestedStart->copy()->addDays($duration - 1);
            
            // S'assurer que la date de fin est un jour disponible
            while (!$product->isDayAvailable($endDate->dayOfWeek === 0 ? 7 : $endDate->dayOfWeek)) {
                $endDate->addDay();
                $duration = $suggestedStart->diffInDays($endDate) + 1;
            }

            $validation = $product->validateRentalPeriod($suggestedStart, $endDate);
            
            if ($validation['valid']) {
                $options[] = [
                    'start_date' => $suggestedStart->format('Y-m-d'),
                    'end_date' => $endDate->format('Y-m-d'),
                    'duration_days' => $duration,
                    'total_cost' => $validation['total_cost'],
                    'deposit_required' => $validation['deposit_required'],
                    'is_optimal' => $duration === $preferredDuration
                ];
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'product_id' => $product->id,
                'suggestions' => $options,
                'constraints' => $product->getRentalConstraints(),
                'next_available_start' => $product->getNextAvailableStartDate()->format('Y-m-d')
            ]
        ]);
    }
}
