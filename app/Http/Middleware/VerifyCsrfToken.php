<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // Exclure les webhooks et autres endpoints qui ne nécessitent pas de CSRF
        'api/stripe/webhook',
        // Exclure les APIs publiques de location
        'api/rentals/*',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Skip CSRF verification for specific API routes
        if ($request->is('api/rentals/*')) {
            return $next($request);
        }

        return parent::handle($request, $next);
    }
}
