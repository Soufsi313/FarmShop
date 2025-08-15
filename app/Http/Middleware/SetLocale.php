<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Langues supportées
        $supportedLocales = ['fr', 'en', 'nl'];
        $defaultLocale = 'fr';
        
        // Priorité 1: Paramètre URL ?lang=xx
        if ($request->has('lang') && in_array($request->get('lang'), $supportedLocales)) {
            $locale = $request->get('lang');
            Session::put('locale', $locale);
        }
        // Priorité 2: Session
        elseif (Session::has('locale') && in_array(Session::get('locale'), $supportedLocales)) {
            $locale = Session::get('locale');
        }
        // Priorité 3: Header Accept-Language du navigateur
        elseif ($request->header('Accept-Language')) {
            $browserLang = substr($request->header('Accept-Language'), 0, 2);
            if (in_array($browserLang, $supportedLocales)) {
                $locale = $browserLang;
                Session::put('locale', $locale);
            } else {
                $locale = $defaultLocale;
                Session::put('locale', $locale);
            }
        }
        // Priorité 4: Langue par défaut
        else {
            $locale = $defaultLocale;
            Session::put('locale', $locale);
        }
        
        // Appliquer la langue
        App::setLocale($locale);
        
        return $next($request);
    }
}
