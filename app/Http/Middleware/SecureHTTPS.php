<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecureHTTPS
{
    public function handle(Request $request, Closure $next)
    {
        // En production, forcer HTTPS
        if (app()->environment('production') && !$request->isSecure()) {
            return redirect()->secure($request->getRequestUri(), 301);
        }

        $response = $next($request);
        
        // Ajouter headers de sécurité seulement si la réponse le permet
        if (method_exists($response, 'header')) {
            $response->header('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
            $response->header('X-Content-Type-Options', 'nosniff');
            $response->header('X-Frame-Options', 'DENY');
            $response->header('Referrer-Policy', 'strict-origin-when-cross-origin');
        }

        return $response;
    }
}