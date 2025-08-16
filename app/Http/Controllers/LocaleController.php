<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class LocaleController extends Controller
{
    /**
     * Change the locale
     */
    public function changeLocale(Request $request, $locale)
    {
        // Vérifier que la langue est supportée
        $supportedLocales = array_keys(config('app.supported_locales'));
        
        if (!in_array($locale, $supportedLocales)) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Langue non supportée'], 400);
            }
            abort(404);
        }
        
        // Stocker la langue en session
        Session::put('locale', $locale);
        
        // Créer le cookie
        $cookie = cookie('locale', $locale, 60 * 24 * 365); // 1 an
        
        // Si c'est une requête AJAX
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'locale' => $locale,
                'message' => 'Langue changée avec succès',
                'redirect_url' => $this->getLocalizedUrl($request, $locale)
            ])->withCookie($cookie);
        }
        
        // Redirection vers l'URL localisée
        $redirectUrl = $this->getLocalizedUrl($request, $locale);
        return redirect($redirectUrl)->withCookie($cookie);
    }
    
    /**
     * Obtenir l'URL localisée basée sur l'URL actuelle
     */
    private function getLocalizedUrl(Request $request, $locale)
    {
        $currentUrl = $request->url();
        $currentPath = $request->path();
        
        // Si on est déjà sur une URL localisée, remplacer la langue
        if (preg_match('/^(fr|en|nl)\//', $currentPath)) {
            $newPath = preg_replace('/^(fr|en|nl)\//', $locale . '/', $currentPath);
        } 
        // Si on est sur l'URL racine
        elseif ($currentPath === '/' || $currentPath === '') {
            $newPath = $locale;
        }
        // Sinon, ajouter le préfixe de langue
        else {
            $newPath = $locale . '/' . $currentPath;
        }
        
        return url($newPath);
    }
}
