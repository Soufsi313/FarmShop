// JavaScript à injecter pour corriger le problème de cookies
// À placer à la fin du body de app.blade.php

document.addEventListener('DOMContentLoaded', function() {
    console.log('🍪 PATCH COOKIE - Initialisation');
    
    // Attendre que le script principal soit chargé
    setTimeout(function() {
        // Vérifier si l'utilisateur est connecté
        const isAuth = document.querySelector('meta[name="user-authenticated"]')?.content === 'true';
        
        if (isAuth) {
            console.log('🍪 PATCH - Utilisateur connecté détecté');
            console.log('🍪 PATCH - Forçage de la vérification API');
            
            // Forcer l'appel à l'API pour les utilisateurs connectés
            if (window.FarmShop && window.FarmShop.cookieConsent && typeof window.FarmShop.cookieConsent.show === 'function') {
                // Appeler directement show() qui va faire l'appel API
                window.FarmShop.cookieConsent.show();
                console.log('🍪 PATCH - show() exécuté');
            } else {
                console.error('🍪 PATCH - FarmShop.cookieConsent non disponible');
            }
        } else {
            console.log('🍪 PATCH - Utilisateur invité, pas d\'intervention');
        }
    }, 1000); // Attendre 1 seconde pour être sûr que tout est chargé
});
