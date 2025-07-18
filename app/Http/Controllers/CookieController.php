<?php

namespace App\Http\Controllers;

use App\Models\Cookie;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class CookieController extends Controller
{
    /**
     * Obtenir les préférences de cookies pour le visiteur/utilisateur
     */
    public function getPreferences(Request $request): JsonResponse
    {
        $cookie = $this->findOrCreateCookie($request);
        
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
            $cookie = Cookie::where('user_id', Auth::id())
                           ->latest()
                           ->first();
        } else {
            // Pour les visiteurs non connectés
            $cookie = Cookie::where('session_id', $sessionId)
                           ->where('ip_address', $ipAddress)
                           ->latest()
                           ->first();
        }

        // Créer un nouveau cookie si aucun n'existe
        if (!$cookie) {
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
        }

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
}
