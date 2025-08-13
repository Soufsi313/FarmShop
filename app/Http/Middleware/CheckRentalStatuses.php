<?php

namespace App\Http\Middleware;

use App\Services\RentalStatusService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class CheckRentalStatuses
{
    protected $rentalStatusService;

    public function __construct(RentalStatusService $rentalStatusService)
    {
        $this->rentalStatusService = $rentalStatusService;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier seulement pour les utilisateurs connectés
        if (Auth::check()) {
            $cacheKey = 'rental_status_check_' . Auth::id();
            
            // Vérifier seulement une fois par heure par utilisateur pour éviter la surcharge
            if (!Cache::has($cacheKey)) {
                try {
                    $this->rentalStatusService->checkUserRentalStatuses(Auth::id());
                    
                    // Marquer comme vérifié pour 1 heure
                    Cache::put($cacheKey, true, 3600);
                    
                } catch (\Exception $e) {
                    // En cas d'erreur, ne pas bloquer la requête
                    \Log::error('Erreur lors de la vérification des statuts de location', [
                        'user_id' => Auth::id(),
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }

        return $next($request);
    }
}
