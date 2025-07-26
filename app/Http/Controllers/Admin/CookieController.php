<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cookie;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CookieController extends Controller
{
    /**
     * Affiche la page principale de gestion des cookies
     */
    public function index()
    {
        return view('admin.cookies.index');
    }

    /**
     * Retourne les statistiques des cookies
     */
    public function stats(): JsonResponse
    {
        try {
            // Statistiques générales
            $totalConsents = Cookie::count();
            $acceptedConsents = Cookie::where('status', 'accepted')->count();
            $rejectedConsents = Cookie::where('status', 'rejected')->count();
            $pendingConsents = Cookie::where('status', 'pending')->count();

            // Statistiques par type de cookie
            $necessaryCount = Cookie::where('necessary', true)->count();
            $analyticsCount = Cookie::where('analytics', true)->count();
            $marketingCount = Cookie::where('marketing', true)->count();
            $preferencesCount = Cookie::where('preferences', true)->count();
            $socialMediaCount = Cookie::where('social_media', true)->count();

            // Statistiques par jour (7 derniers jours)
            $dailyConsents = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i)->format('Y-m-d');
                $accepted = Cookie::whereDate('created_at', $date)
                    ->where('status', 'accepted')
                    ->count();
                $rejected = Cookie::whereDate('created_at', $date)
                    ->where('status', 'rejected')
                    ->count();
                
                $dailyConsents[] = [
                    'date' => $date,
                    'accepted' => $accepted,
                    'rejected' => $rejected
                ];
            }

            $statsData = [
                'total_consents' => $totalConsents,
                'accepted_consents' => $acceptedConsents,
                'rejected_consents' => $rejectedConsents,
                'pending_consents' => $pendingConsents,
                'necessary_count' => $necessaryCount,
                'analytics_count' => $analyticsCount,
                'marketing_count' => $marketingCount,
                'preferences_count' => $preferencesCount,
                'social_media_count' => $socialMediaCount,
                'daily_consents' => $dailyConsents
            ];

            \Log::info('Cookie stats API called', $statsData);

            return response()->json([
                'success' => true,
                'data' => $statsData
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in cookie stats API', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des statistiques',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Retourne la liste paginée des consentements
     */
    public function list(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 10);
            $page = $request->get('page', 1);

            $query = Cookie::with('user')
                ->orderBy('updated_at', 'desc');

            // Filtres optionnels
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }

            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            $cookies = $query->paginate($perPage, ['*'], 'page', $page);

            // Debug: log the data being returned
            \Log::info('Cookie list API called', [
                'total' => $cookies->total(),
                'count' => $cookies->count(),
                'current_page' => $cookies->currentPage(),
                'last_page' => $cookies->lastPage()
            ]);

            return response()->json([
                'success' => true,
                'data' => $cookies,
                'debug' => [
                    'total_in_db' => Cookie::count(),
                    'query_result_count' => $cookies->count(),
                    'pagination_total' => $cookies->total()
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in cookie list API', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des consentements',
                'error' => config('app.debug') ? $e->getMessage() : null,
                'debug' => [
                    'total_in_db' => Cookie::count()
                ]
            ], 500);
        }
    }

    /**
     * Supprime un consentement
     */
    public function destroy($id): JsonResponse
    {
        try {
            $cookie = Cookie::findOrFail($id);
            $cookie->delete();

            return response()->json([
                'success' => true,
                'message' => 'Consentement supprimé avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du consentement',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Exporte les consentements au format CSV
     */
    public function export(Request $request)
    {
        try {
            $query = Cookie::with('user');

            // Filtres optionnels
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            $cookies = $query->orderBy('created_at', 'desc')->get();

            // Créer le CSV
            $filename = 'cookies_export_' . date('Y-m-d_H-i-s') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($cookies) {
                $file = fopen('php://output', 'w');
                
                // En-têtes CSV
                fputcsv($file, [
                    'ID',
                    'Utilisateur',
                    'Email',
                    'Session ID',
                    'Status',
                    'Nécessaires',
                    'Analytiques',
                    'Marketing',
                    'Préférences',
                    'Réseaux sociaux',
                    'Adresse IP',
                    'User Agent',
                    'Créé le',
                    'Mis à jour le'
                ]);

                // Données
                foreach ($cookies as $cookie) {
                    fputcsv($file, [
                        $cookie->id,
                        $cookie->user ? $cookie->user->name : 'Visiteur',
                        $cookie->user ? $cookie->user->email : '',
                        $cookie->session_id,
                        $cookie->status,
                        $cookie->necessary ? 'Oui' : 'Non',
                        $cookie->analytics ? 'Oui' : 'Non',
                        $cookie->marketing ? 'Oui' : 'Non',
                        $cookie->preferences ? 'Oui' : 'Non',
                        $cookie->social_media ? 'Oui' : 'Non',
                        $cookie->ip_address,
                        $cookie->user_agent,
                        $cookie->created_at->format('Y-m-d H:i:s'),
                        $cookie->updated_at->format('Y-m-d H:i:s')
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'export',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Affiche les détails d'un consentement
     */
    public function show($id): JsonResponse
    {
        try {
            $cookie = Cookie::with('user')->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $cookie
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Consentement non trouvé',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 404);
        }
    }

    /**
     * Met à jour le statut d'un consentement
     */
    public function updateStatus(Request $request, $id): JsonResponse
    {
        try {
            $request->validate([
                'status' => 'required|in:pending,accepted,rejected'
            ]);

            $cookie = Cookie::findOrFail($id);
            $cookie->status = $request->status;
            $cookie->save();

            return response()->json([
                'success' => true,
                'message' => 'Statut mis à jour avec succès',
                'data' => $cookie
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du statut',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Supprime tous les consentements expirés
     */
    public function cleanup(): JsonResponse
    {
        try {
            // Supprimer les consentements de plus de 2 ans (RGPD)
            $expiredDate = Carbon::now()->subYears(2);
            $deleted = Cookie::where('created_at', '<', $expiredDate)->delete();

            return response()->json([
                'success' => true,
                'message' => "Nettoyage terminé. {$deleted} consentements supprimés.",
                'deleted_count' => $deleted
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du nettoyage',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
