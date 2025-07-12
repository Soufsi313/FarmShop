<?php

namespace App\Http\Middleware;

use App\Models\Cookie;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class CookieConsentMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Ne pas traiter les requêtes AJAX ou les routes d'API cookies
        if ($request->ajax() || $request->wantsJson() || $this->isExcludedRoute($request)) {
            return $next($request);
        }

        // Obtenir ou créer l'enregistrement de cookie pour le visiteur/utilisateur
        $cookie = $this->findOrCreateCookie($request);

        // Ajouter les informations de cookie à la réponse
        $response = $next($request);

        // Ajouter les headers pour le consentement
        if ($cookie) {
            $response->headers->set('X-Cookie-Consent-Status', $cookie->status);
            $response->headers->set('X-Cookie-Consent-ID', $cookie->id);
            
            // Ajouter un indicateur si le consentement est requis
            if ($cookie->status === 'pending') {
                $response->headers->set('X-Cookie-Consent-Required', 'true');
            }
        }

        return $response;
    }

    /**
     * Vérifier si la route est exclue du middleware
     */
    private function isExcludedRoute(Request $request): bool
    {
        $excludedRoutes = [
            'api/cookies/*',
            'api/user',
            'api/logout'
        ];

        $currentPath = $request->path();

        foreach ($excludedRoutes as $pattern) {
            if (fnmatch($pattern, $currentPath)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Trouver ou créer un enregistrement de cookie
     */
    private function findOrCreateCookie(Request $request): ?Cookie
    {
        try {
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

            // Créer un nouveau cookie si aucun n'existe ou si c'est une nouvelle session
            if (!$cookie || $this->shouldCreateNewCookie($cookie, $request)) {
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
        } catch (\Exception $e) {
            // En cas d'erreur, continuer sans bloquer l'application
            \Log::error('Erreur dans CookieConsentMiddleware: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Déterminer s'il faut créer un nouveau cookie
     */
    private function shouldCreateNewCookie(Cookie $cookie, Request $request): bool
    {
        // Créer un nouveau cookie si l'IP a changé pour les visiteurs
        if (Auth::guest() && $cookie->ip_address !== $request->ip()) {
            return true;
        }

        // Créer un nouveau cookie si l'user agent a significativement changé
        if ($this->userAgentChanged($cookie->user_agent, $request->userAgent())) {
            return true;
        }

        // Créer un nouveau cookie s'il est très ancien (plus de 30 jours)
        if ($cookie->created_at->diffInDays(now()) > 30) {
            return true;
        }

        return false;
    }

    /**
     * Vérifier si l'user agent a significativement changé
     */
    private function userAgentChanged(?string $oldUserAgent, ?string $newUserAgent): bool
    {
        if (!$oldUserAgent || !$newUserAgent) {
            return true;
        }

        // Comparer les parties importantes de l'user agent
        $oldBrowser = $this->extractBrowserSignature($oldUserAgent);
        $newBrowser = $this->extractBrowserSignature($newUserAgent);

        return $oldBrowser !== $newBrowser;
    }

    /**
     * Extraire une signature unique du navigateur
     */
    private function extractBrowserSignature(string $userAgent): string
    {
        // Extraire les informations clés pour créer une signature
        preg_match('/Mozilla\/[\d\.]+.*?(\w+)\/[\d\.]+/', $userAgent, $matches);
        return $matches[1] ?? 'unknown';
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
