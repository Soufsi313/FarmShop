<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class DebugAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // DEBUG : Tracer toutes les informations d'authentification
        Log::info('DebugAuthMiddleware - TRACE AUTH', [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'route_name' => $request->route() ? $request->route()->getName() : 'no_route',
            'auth_check' => Auth::check(),
            'auth_user_id' => Auth::id(),
            'auth_user_name' => Auth::user() ? Auth::user()->name : 'NULL',
            'session_id' => session()->getId(),
            'session_data' => session()->all(),
            'cookies' => $request->cookies->all(),
            'headers' => [
                'X-CSRF-TOKEN' => $request->header('X-CSRF-TOKEN'),
                'Accept' => $request->header('Accept'),
                'Content-Type' => $request->header('Content-Type'),
                'User-Agent' => $request->header('User-Agent'),
            ]
        ]);

        return $next($request);
    }
}
