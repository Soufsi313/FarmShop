<?php

namespace App\Http\Controllers;

use App\Models\Cookie;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Carbon\Carbon;

class CookieController extends Controller
{
    use AuthorizesRequests;

    /**
     * @OA\Get(
     *     path="/api/cookies/preferences",
     *     tags={"Cookies", "GDPR"},
     *     summary="Obtenir les préférences de cookies",
     *     description="Récupère les préférences de cookies de l'utilisateur ou visiteur avec statut de consentement",
     *     @OA\Parameter(
     *         name="force_consent",
     *         in="query",
     *         description="Forcer l'affichage du consentement",
     *         @OA\Schema(type="boolean", example=false)
     *     ),
     *     @OA\Parameter(
     *         name="X-Force-Cookie-Consent",
     *         in="header",
     *         description="Header pour forcer le consentement",
     *         @OA\Schema(type="string", example="true")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Préférences récupérées avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="cookie_id", type="integer", example=1, description="ID du cookie"),
     *                 @OA\Property(
     *                     property="preferences",
     *                     type="object",
     *                     @OA\Property(property="essential", type="boolean", example=true, description="Cookies essentiels (toujours activés)"),
     *                     @OA\Property(property="functional", type="boolean", example=false, description="Cookies fonctionnels"),
     *                     @OA\Property(property="analytics", type="boolean", example=false, description="Cookies d'analyse"),
     *                     @OA\Property(property="marketing", type="boolean", example=false, description="Cookies marketing"),
     *                     @OA\Property(property="personalization", type="boolean", example=false, description="Cookies de personnalisation")
     *                 ),
     *                 @OA\Property(
     *                     property="descriptions",
     *                     type="object",
     *                     description="Descriptions des différents types de cookies"
     *                 ),
     *                 @OA\Property(property="consent_required", type="boolean", example=true, description="Consentement requis")
     *             )
     *         )
     *     )
     * )
     * 
     * Obtenir les préférences de cookies pour le visiteur/utilisateur
     */
    public function getPreferences(Request $request): JsonResponse
    {
        $cookie = $this->findOrCreateCookie($request);
        
        // Si la requête indique que le localStorage est vide, remettre le statut à pending
        $forceConsent = $request->has('force_consent') || 
                       $request->header('X-Force-Cookie-Consent') === 'true';
        
        if ($forceConsent && $cookie->status !== 'pending') {
            $cookie->update(['status' => 'pending']);
            \Log::info('Statut cookie forcé à pending', [
                'cookie_id' => $cookie->id,
                'user_id' => Auth::id(),
                'reason' => 'localStorage nettoyé'
            ]);
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'cookie_id' => $cookie->id,
                'preferences' => $cookie->getPreferencesSummary(),
                'descriptions' => Cookie::getCookieDescriptions(),
                'consent_required' => $cookie->status === 'pending'
            ]
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/cookies/preferences",
     *     tags={"Cookies", "GDPR"},
     *     summary="Mettre à jour les préférences de cookies",
     *     description="Met à jour les préférences de consentement aux cookies pour l'utilisateur ou visiteur",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Préférences de cookies",
     *         @OA\JsonContent(
     *             @OA\Property(property="necessary", type="boolean", example=true, description="Cookies nécessaires (toujours true)"),
     *             @OA\Property(property="analytics", type="boolean", example=false, description="Cookies d'analyse (Google Analytics, etc.)"),
     *             @OA\Property(property="marketing", type="boolean", example=false, description="Cookies marketing (publicités, tracking)"),
     *             @OA\Property(property="preferences", type="boolean", example=true, description="Cookies de préférences (langue, paramètres)"),
     *             @OA\Property(property="social_media", type="boolean", example=false, description="Cookies réseaux sociaux (widgets, partage)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Préférences mises à jour avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Préférences de cookies mises à jour"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="cookie_id", type="integer", example=1),
     *                 @OA\Property(property="preferences", type="object", description="Préférences actualisées"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", description="Date de mise à jour")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erreurs de validation",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     )
     * )
     * 
     * Mettre à jour les préférences de cookies
     */
    public function updatePreferences(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'necessary' => 'boolean',
            'analytics' => 'boolean',
            'marketing' => 'boolean',
            'preferences' => 'boolean',
            'social_media' => 'boolean'
        ]);

        $cookie = $this->findOrCreateCookie($request);
        $cookie->updatePreferences($validated);

        return response()->json([
            'success' => true,
            'message' => 'Préférences de cookies mises à jour avec succès',
            'data' => $cookie->getPreferencesSummary()
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/cookies/accept-all",
     *     tags={"Cookies", "GDPR"},
     *     summary="Accepter tous les cookies",
     *     description="Accepte tous les types de cookies en une seule action",
     *     @OA\Response(
     *         response=200,
     *         description="Tous les cookies acceptés avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Tous les cookies ont été acceptés"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="cookie_id", type="integer", example=1),
     *                 @OA\Property(
     *                     property="preferences",
     *                     type="object",
     *                     @OA\Property(property="necessary", type="boolean", example=true),
     *                     @OA\Property(property="analytics", type="boolean", example=true),
     *                     @OA\Property(property="marketing", type="boolean", example=true),
     *                     @OA\Property(property="preferences", type="boolean", example=true),
     *                     @OA\Property(property="social_media", type="boolean", example=true)
     *                 ),
     *                 @OA\Property(property="status", type="string", example="accepted"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     )
     * )
     * 
     * Accepter tous les cookies
     */
    public function acceptAll(Request $request): JsonResponse
    {
        $cookie = $this->findOrCreateCookie($request);
        $cookie->acceptAll();

        return response()->json([
            'success' => true,
            'message' => 'Tous les cookies ont été acceptés',
            'data' => $cookie->getPreferencesSummary()
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/cookies/reject-all",
     *     tags={"Cookies", "GDPR"},
     *     summary="Rejeter tous les cookies optionnels",
     *     description="Rejette tous les cookies non-essentiels, garde uniquement les cookies nécessaires",
     *     @OA\Response(
     *         response=200,
     *         description="Cookies optionnels rejetés avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Tous les cookies optionnels ont été rejetés"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="cookie_id", type="integer", example=1),
     *                 @OA\Property(
     *                     property="preferences",
     *                     type="object",
     *                     @OA\Property(property="necessary", type="boolean", example=true, description="Toujours activé"),
     *                     @OA\Property(property="analytics", type="boolean", example=false),
     *                     @OA\Property(property="marketing", type="boolean", example=false),
     *                     @OA\Property(property="preferences", type="boolean", example=false),
     *                     @OA\Property(property="social_media", type="boolean", example=false)
     *                 ),
     *                 @OA\Property(property="status", type="string", example="rejected"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     )
     * )
     * 
     * Rejeter tous les cookies optionnels
     */
    public function rejectAll(Request $request): JsonResponse
    {
        $cookie = $this->findOrCreateCookie($request);
        $cookie->rejectAll();

        return response()->json([
            'success' => true,
            'message' => 'Les cookies optionnels ont été rejetés',
            'data' => $cookie->getPreferencesSummary()
        ]);
    }

    /**
     * Obtenir les statistiques globales des cookies (pour admin)
     */
    public function getGlobalStats(): JsonResponse
    {
        $this->authorize('viewAny', Cookie::class);

        $stats = Cookie::getGlobalStats();
        
        // Statistiques par type de cookie
        $cookieTypeStats = [
            'necessary' => Cookie::where('necessary', true)->count(),
            'analytics' => Cookie::where('analytics', true)->count(),
            'marketing' => Cookie::where('marketing', true)->count(),
            'preferences' => Cookie::where('preferences', true)->count(),
            'social_media' => Cookie::where('social_media', true)->count()
        ];

        // Statistiques récentes (30 derniers jours)
        $recentStats = Cookie::recent(30)->get()->groupBy('status')->map->count();

        // Évolution par jour sur les 7 derniers jours
        $dailyStats = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dailyCount = Cookie::whereDate('created_at', $date)->count();
            $dailyStats[] = [
                'date' => $date->format('Y-m-d'),
                'count' => $dailyCount
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'global_stats' => $stats,
                'cookie_type_stats' => $cookieTypeStats,
                'recent_stats' => $recentStats,
                'daily_evolution' => $dailyStats,
                'user_breakdown' => [
                    'authenticated' => Cookie::authenticatedUsers()->count(),
                    'guests' => Cookie::guestUsers()->count()
                ]
            ]
        ]);
    }

    /**
     * Lister tous les cookies (pour admin)
     */
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Cookie::class);

        $query = Cookie::with('user')->latest();

        // Filtrage par statut
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtrage par type d'utilisateur
        if ($request->filled('user_type')) {
            if ($request->user_type === 'authenticated') {
                $query->authenticatedUsers();
            } elseif ($request->user_type === 'guest') {
                $query->guestUsers();
            }
        }

        // Filtrage par date
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $cookies = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $cookies
        ]);
    }

    /**
     * Afficher un cookie spécifique (pour admin)
     */
    public function show(Cookie $cookie): JsonResponse
    {
        $this->authorize('view', $cookie);

        return response()->json([
            'success' => true,
            'data' => $cookie->load('user')
        ]);
    }

    /**
     * Supprimer un cookie (pour admin)
     */
    public function destroy(Cookie $cookie): JsonResponse
    {
        $this->authorize('delete', $cookie);

        $cookie->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cookie supprimé avec succès'
        ]);
    }

    /**
     * Vérifier si un type de cookie est accepté
     */
    public function checkConsent(Request $request, string $cookieType): JsonResponse
    {
        $validTypes = ['necessary', 'analytics', 'marketing', 'preferences', 'social_media'];
        
        if (!in_array($cookieType, $validTypes)) {
            return response()->json([
                'success' => false,
                'message' => 'Type de cookie invalide'
            ], 400);
        }

        $cookie = $this->findOrCreateCookie($request);
        $isAccepted = $cookie->isAccepted($cookieType);

        return response()->json([
            'success' => true,
            'data' => [
                'cookie_type' => $cookieType,
                'is_accepted' => $isAccepted,
                'status' => $cookie->status
            ]
        ]);
    }

    /**
     * Obtenir l'historique des consentements pour un utilisateur
     */
    public function getUserHistory(Request $request): JsonResponse
    {
        if (Auth::guest()) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non authentifié'
            ], 401);
        }

        $cookies = Cookie::where('user_id', Auth::id())
                        ->orderBy('created_at', 'desc')
                        ->get();

        return response()->json([
            'success' => true,
            'data' => $cookies->map(function ($cookie) {
                return [
                    'id' => $cookie->id,
                    'status' => $cookie->status,
                    'preferences' => $cookie->getPreferencesSummary(),
                    'created_at' => $cookie->created_at->format('d/m/Y H:i'),
                    'last_updated_at' => $cookie->last_updated_at?->format('d/m/Y H:i'),
                    'page_url' => $cookie->page_url
                ];
            })
        ]);
    }

    /**
     * Trouver ou créer un cookie pour le visiteur/utilisateur
     */
    private function findOrCreateCookie(Request $request): Cookie
    {
        $sessionId = Session::getId();
        $ipAddress = $request->ip();
        $userAgent = $request->userAgent();
        
        // Pour les utilisateurs connectés
        if (Auth::check()) {
            $userId = Auth::id();
            
            // Chercher d'abord un cookie existant pour cet utilisateur
            $cookie = Cookie::where('user_id', $userId)
                           ->latest()
                           ->first();
            
            // Si on trouve un cookie utilisateur, le retourner
            if ($cookie) {
                return $cookie;
            }
            
            // Si pas de cookie utilisateur, chercher un cookie visiteur récent avec la même session/IP
            $guestCookie = Cookie::where(function($query) use ($sessionId, $ipAddress) {
                            $query->where('session_id', $sessionId)
                                  ->orWhere('ip_address', $ipAddress);
                        })
                        ->whereNull('user_id')
                        ->where('created_at', '>=', now()->subHours(24)) // Cookie récent (24h)
                        ->latest()
                        ->first();
            
            if ($guestCookie) {
                // Migrer le cookie visiteur vers l'utilisateur connecté
                $guestCookie->update([
                    'user_id' => $userId,
                    'session_id' => null, // Nettoyer session_id car l'utilisateur est connecté
                    'migrated_at' => now()
                ]);
                
                \Log::info('Cookie visiteur migré vers utilisateur connecté', [
                    'guest_cookie_id' => $guestCookie->id,
                    'user_id' => $userId,
                    'status' => $guestCookie->status,
                    'session_id' => $sessionId
                ]);
                
                return $guestCookie;
            }
        } else {
            // Pour les visiteurs non connectés
            $cookie = Cookie::where('session_id', $sessionId)
                           ->where('ip_address', $ipAddress)
                           ->whereNull('user_id')
                           ->latest()
                           ->first();
            
            // Si on trouve un cookie visiteur, le retourner
            if ($cookie) {
                return $cookie;
            }
        }

        // Créer un nouveau cookie si aucun n'existe
        $cookie = Cookie::create([
            'user_id' => Auth::id(),
            'session_id' => Auth::guest() ? $sessionId : null,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'page_url' => $request->url(),
            'referer' => $request->header('referer'),
            'browser_info' => $this->extractBrowserInfo($request),
            'consent_version' => '1.0',
            'status' => 'pending',
            ...Cookie::getDefaultPreferences()
        ]);
        
        \Log::info('Nouveau cookie créé', [
            'cookie_id' => $cookie->id,
            'user_id' => Auth::id(),
            'session_id' => Auth::guest() ? $sessionId : 'null (user connected)',
            'status' => 'pending'
        ]);

        return $cookie;
    }

    /**
     * Extraire les informations du navigateur
     */
    private function extractBrowserInfo(Request $request): array
    {
        $userAgent = $request->userAgent();
        
        return [
            'user_agent' => $userAgent,
            'accept_language' => $request->header('accept-language'),
            'accept_encoding' => $request->header('accept-encoding'),
            'connection' => $request->header('connection'),
            'platform' => $this->detectPlatform($userAgent),
            'browser' => $this->detectBrowser($userAgent)
        ];
    }

    /**
     * Détecter la plateforme
     */
    private function detectPlatform(string $userAgent): string
    {
        if (stripos($userAgent, 'windows') !== false) return 'Windows';
        if (stripos($userAgent, 'macintosh') !== false) return 'Mac';
        if (stripos($userAgent, 'linux') !== false) return 'Linux';
        if (stripos($userAgent, 'android') !== false) return 'Android';
        if (stripos($userAgent, 'iphone') !== false) return 'iOS';
        return 'Unknown';
    }

    /**
     * Détecter le navigateur
     */
    private function detectBrowser(string $userAgent): string
    {
        if (stripos($userAgent, 'chrome') !== false) return 'Chrome';
        if (stripos($userAgent, 'firefox') !== false) return 'Firefox';
        if (stripos($userAgent, 'safari') !== false) return 'Safari';
        if (stripos($userAgent, 'edge') !== false) return 'Edge';
        if (stripos($userAgent, 'opera') !== false) return 'Opera';
        return 'Unknown';
    }

    /**
     * Obtenir les statistiques des cookies pour l'admin
     */
    public function getAdminStats(): JsonResponse
    {
        $this->authorize('viewAny', Cookie::class);

        $stats = [
            'total_consents' => Cookie::count(),
            'accepted_consents' => Cookie::where('status', 'accepted')->count(),
            'rejected_consents' => Cookie::where('status', 'rejected')->count(),
            'pending_consents' => Cookie::where('status', 'pending')->count(),
            'necessary_count' => Cookie::where('necessary', true)->count(),
            'analytics_count' => Cookie::where('analytics', true)->count(),
            'marketing_count' => Cookie::where('marketing', true)->count(),
            'preferences_count' => Cookie::where('preferences', true)->count(),
            'social_media_count' => Cookie::where('social_media', true)->count(),
        ];

        // Statistiques des 7 derniers jours
        $dailyStats = Cookie::selectRaw('DATE(created_at) as date, status, COUNT(*) as count')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('date', 'status')
            ->orderBy('date')
            ->get()
            ->groupBy('date');

        $dailyConsents = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $dayStats = $dailyStats->get($date, collect());
            
            $dailyConsents[] = [
                'date' => $date,
                'accepted' => $dayStats->where('status', 'accepted')->sum('count'),
                'rejected' => $dayStats->where('status', 'rejected')->sum('count'),
                'pending' => $dayStats->where('status', 'pending')->sum('count'),
            ];
        }

        $stats['daily_consents'] = $dailyConsents;

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Lister tous les cookies pour l'admin
     */
    public function getAdminIndex(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Cookie::class);

        $query = Cookie::with('user')
            ->orderBy('updated_at', 'desc');

        // Filtres
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ip_address', 'like', "%{$search}%")
                  ->orWhere('session_id', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('email', 'like', "%{$search}%");
                  });
            });
        }

        $perPage = $request->get('per_page', 10);
        $cookies = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $cookies
        ]);
    }

    /**
     * Supprimer un consentement (admin seulement)
     */
    public function destroyConsent($id): JsonResponse
    {
        $cookie = Cookie::findOrFail($id);
        $this->authorize('delete', $cookie);

        $cookie->delete();

        return response()->json([
            'success' => true,
            'message' => 'Consentement supprimé avec succès'
        ]);
    }

    /**
     * Exporter les données de cookies
     */
    public function exportCookies(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $this->authorize('viewAny', Cookie::class);

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="cookies-export-' . date('Y-m-d') . '.csv"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            
            // Headers CSV
            fputcsv($file, [
                'ID',
                'Utilisateur',
                'Session ID',
                'IP Address',
                'Status',
                'Essentiels',
                'Analytics',
                'Marketing',
                'Préférences',
                'Réseaux sociaux',
                'Date création',
                'Dernière modification',
                'User Agent'
            ]);

            // Données
            Cookie::with('user')->chunk(1000, function ($cookies) use ($file) {
                foreach ($cookies as $cookie) {
                    fputcsv($file, [
                        $cookie->id,
                        $cookie->user ? $cookie->user->email : 'Visiteur',
                        $cookie->session_id,
                        $cookie->ip_address,
                        $cookie->status,
                        $cookie->necessary ? 'Oui' : 'Non',
                        $cookie->analytics ? 'Oui' : 'Non',
                        $cookie->marketing ? 'Oui' : 'Non',
                        $cookie->preferences ? 'Oui' : 'Non',
                        $cookie->social_media ? 'Oui' : 'Non',
                        $cookie->created_at?->format('Y-m-d H:i:s'),
                        $cookie->updated_at?->format('Y-m-d H:i:s'),
                        $cookie->user_agent
                    ]);
                }
            });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Migrer les cookies visiteurs vers l'utilisateur connecté (méthode utilitaire)
     */
    public function migrateGuestCookies(Request $request): JsonResponse
    {
        if (Auth::guest()) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non authentifié'
            ], 401);
        }

        $userId = Auth::id();
        $sessionId = Session::getId();
        $ipAddress = $request->ip();

        // Trouver les cookies visiteurs récents avec la même session/IP
        $guestCookies = Cookie::where('session_id', $sessionId)
                             ->where('ip_address', $ipAddress)
                             ->whereNull('user_id')
                             ->where('created_at', '>=', now()->subHours(24)) // Seulement les 24 dernières heures
                             ->get();

        $migratedCount = 0;
        foreach ($guestCookies as $guestCookie) {
            // Vérifier qu'il n'y a pas déjà un cookie pour cet utilisateur
            $existingUserCookie = Cookie::where('user_id', $userId)->exists();
            
            if (!$existingUserCookie) {
                $guestCookie->update([
                    'user_id' => $userId,
                    'session_id' => null
                ]);
                $migratedCount++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Migration terminée: {$migratedCount} cookie(s) migrés",
            'data' => [
                'migrated_count' => $migratedCount,
                'user_id' => $userId
            ]
        ]);
    }
}
