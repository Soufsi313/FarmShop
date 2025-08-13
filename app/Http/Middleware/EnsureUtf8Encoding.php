<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUtf8Encoding
{
    public function handle(Request $request, Closure $next)
    {
        // S'assurer que l'encodage d'entrée est UTF-8
        $request->headers->set('Accept-Charset', 'UTF-8');
        
        $response = $next($request);
        
        // S'assurer que la réponse utilise UTF-8
        if (method_exists($response, 'header')) {
            $response->header('Content-Type', 'text/html; charset=UTF-8');
        }
        
        return $response;
    }
}
