<?php

namespace App\Services;

use App\Models\Cookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CookieConsentService
{
    /**
     * Vérifier si un type de cookie est autorisé pour l'utilisateur actuel
     */
    public static function isAllowed(string $cookieType, Request $request = null): bool
    {
        if (!$request) {
            $request = request();
        }

        $cookie = self::getCurrentCookie($request);
        
        if (!$cookie) {
            // Si aucun cookie n'est trouvé, seuls les cookies nécessaires sont autorisés
            return $cookieType === 'necessary';
        }

        return $cookie->isAccepted($cookieType);
    }

    /**
     * Obtenir le cookie actuel pour l'utilisateur/visiteur
     */
    public static function getCurrentCookie(Request $request = null): ?Cookie
    {
        if (!$request) {
            $request = request();
        }

        $sessionId = Session::getId();
        $ipAddress = $request->ip();
        
        // Pour les utilisateurs connectés
        if (Auth::check()) {
            return Cookie::where('user_id', Auth::id())
                        ->latest()
                        ->first();
        } else {
            // Pour les visiteurs non connectés
            return Cookie::where('session_id', $sessionId)
                        ->where('ip_address', $ipAddress)
                        ->latest()
                        ->first();
        }
    }

    /**
     * Vérifier si le consentement est requis
     */
    public static function isConsentRequired(Request $request = null): bool
    {
        $cookie = self::getCurrentCookie($request);
        return !$cookie || $cookie->status === 'pending';
    }

    /**
     * Obtenir le statut du consentement
     */
    public static function getConsentStatus(Request $request = null): string
    {
        $cookie = self::getCurrentCookie($request);
        return $cookie ? $cookie->status : 'pending';
    }

    /**
     * Définir un cookie applicatif si autorisé
     */
    public static function setCookie(string $name, string $value, int $minutes = 60, string $type = 'necessary', Request $request = null): bool
    {
        if (!self::isAllowed($type, $request)) {
            return false;
        }

        // Utiliser la fonction cookie() de Laravel pour définir le cookie
        cookie($name, $value, $minutes);
        return true;
    }

    /**
     * Obtenir un cookie applicatif
     */
    public static function getCookie(string $name, string $default = null, Request $request = null)
    {
        if (!$request) {
            $request = request();
        }

        return $request->cookie($name, $default);
    }

    /**
     * Supprimer un cookie applicatif
     */
    public static function forgetCookie(string $name): void
    {
        cookie()->forget($name);
    }

    /**
     * Obtenir les cookies autorisés par catégorie
     */
    public static function getAllowedCookieTypes(Request $request = null): array
    {
        $cookie = self::getCurrentCookie($request);
        
        if (!$cookie) {
            return ['necessary'];
        }

        $allowed = [];
        $types = ['necessary', 'analytics', 'marketing', 'preferences', 'social_media'];
        
        foreach ($types as $type) {
            if ($cookie->isAccepted($type)) {
                $allowed[] = $type;
            }
        }

        return $allowed;
    }

    /**
     * Générer les balises de script pour les cookies autorisés
     */
    public static function generateScriptTags(Request $request = null): array
    {
        $allowedTypes = self::getAllowedCookieTypes($request);
        $scripts = [];

        // Scripts d'analyse (Google Analytics, etc.)
        if (in_array('analytics', $allowedTypes)) {
            $scripts['analytics'] = [
                'google_analytics' => "
                    <!-- Google Analytics -->
                    <script async src=\"https://www.googletagmanager.com/gtag/js?id=GA_MEASUREMENT_ID\"></script>
                    <script>
                      window.dataLayer = window.dataLayer || [];
                      function gtag(){dataLayer.push(arguments);}
                      gtag('js', new Date());
                      gtag('config', 'GA_MEASUREMENT_ID');
                    </script>
                ",
                'hotjar' => "
                    <!-- Hotjar Tracking Code -->
                    <script>
                        (function(h,o,t,j,a,r){
                            h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
                            h._hjSettings={hjid:YOUR_HOTJAR_ID,hjsv:6};
                            a=o.getElementsByTagName('head')[0];
                            r=o.createElement('script');r.async=1;
                            r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
                            a.appendChild(r);
                        })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
                    </script>
                "
            ];
        }

        // Scripts marketing (Facebook Pixel, etc.)
        if (in_array('marketing', $allowedTypes)) {
            $scripts['marketing'] = [
                'facebook_pixel' => "
                    <!-- Facebook Pixel Code -->
                    <script>
                      !function(f,b,e,v,n,t,s)
                      {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
                      n.callMethod.apply(n,arguments):n.queue.push(arguments)};
                      if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
                      n.queue=[];t=b.createElement(e);t.async=!0;
                      t.src=v;s=b.getElementsByTagName(e)[0];
                      s.parentNode.insertBefore(t,s)}(window, document,'script',
                      'https://connect.facebook.net/en_US/fbevents.js');
                      fbq('init', 'YOUR_PIXEL_ID');
                      fbq('track', 'PageView');
                    </script>
                "
            ];
        }

        // Scripts de préférences
        if (in_array('preferences', $allowedTypes)) {
            $scripts['preferences'] = [
                'user_preferences' => "
                    <script>
                        // Script pour mémoriser les préférences utilisateur
                        console.log('Cookies de préférences activés');
                    </script>
                "
            ];
        }

        // Scripts réseaux sociaux
        if (in_array('social_media', $allowedTypes)) {
            $scripts['social_media'] = [
                'facebook_sdk' => "
                    <!-- Facebook SDK -->
                    <script async defer crossorigin=\"anonymous\" 
                            src=\"https://connect.facebook.net/fr_FR/sdk.js#xfbml=1&version=v18.0\">
                    </script>
                ",
                'twitter_widgets' => "
                    <!-- Twitter Widgets -->
                    <script async src=\"https://platform.twitter.com/widgets.js\" charset=\"utf-8\"></script>
                "
            ];
        }

        return $scripts;
    }

    /**
     * Générer le JavaScript pour la bannière de cookies
     */
    public static function generateBannerScript(): string
    {
        return "
        <script>
            // Configuration de la bannière de cookies
            window.CookieConsent = {
                apiUrl: '/api/cookies',
                currentStatus: '" . self::getConsentStatus() . "',
                showBanner: " . (self::isConsentRequired() ? 'true' : 'false') . ",
                
                // Méthodes pour gérer les cookies
                acceptAll: function() {
                    fetch('/api/cookies/accept-all', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                        }
                    }).then(response => response.json())
                      .then(data => {
                          if (data.success) {
                              this.hideBanner();
                              location.reload();
                          }
                      });
                },
                
                rejectAll: function() {
                    fetch('/api/cookies/reject-all', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                        }
                    }).then(response => response.json())
                      .then(data => {
                          if (data.success) {
                              this.hideBanner();
                          }
                      });
                },
                
                hideBanner: function() {
                    const banner = document.querySelector('.cookie-banner');
                    if (banner) banner.style.display = 'none';
                }
            };
            
            // Afficher la bannière si nécessaire
            if (window.CookieConsent.showBanner) {
                document.addEventListener('DOMContentLoaded', function() {
                    // Code pour afficher la bannière
                    console.log('Bannière de cookies à afficher');
                });
            }
        </script>
        ";
    }

    /**
     * Nettoyer les cookies expirés
     */
    public static function cleanupExpiredCookies(): int
    {
        // Supprimer les cookies de plus de 90 jours
        return Cookie::where('created_at', '<', now()->subDays(90))->delete();
    }

    /**
     * Migrer les cookies d'un visiteur vers un utilisateur connecté
     */
    public static function migrateGuestCookies(int $userId, Request $request = null): bool
    {
        if (!$request) {
            $request = request();
        }

        $sessionId = Session::getId();
        $ipAddress = $request->ip();

        // Trouver le cookie du visiteur
        $guestCookie = Cookie::where('session_id', $sessionId)
                            ->where('ip_address', $ipAddress)
                            ->whereNull('user_id')
                            ->latest()
                            ->first();

        if ($guestCookie) {
            // Mettre à jour avec l'ID utilisateur
            $guestCookie->update([
                'user_id' => $userId,
                'session_id' => null
            ]);
            
            return true;
        }

        return false;
    }
}
