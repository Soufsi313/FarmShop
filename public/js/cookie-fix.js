// Fix pour le bandeau de cookies
// Solution simplifiée : vérifier l'API pour tous les utilisateurs connectés

document.addEventListener('DOMContentLoaded', function() {
    console.log('🍪 Cookie fix - Initialisation...');
    
    // Vérifier si l'utilisateur est connecté
    const isAuthenticated = document.querySelector('meta[name="user-authenticated"]')?.content === 'true';
    console.log('🍪 Utilisateur connecté:', isAuthenticated);
    
    if (isAuthenticated) {
        console.log('🍪 👤 Utilisateur connecté - application du fix');
        
        // Fonction pour forcer la vérification
        function forceCookieCheck() {
            if (window.FarmShop && window.FarmShop.cookieConsent && typeof window.FarmShop.cookieConsent.show === 'function') {
                console.log('🍪 ✅ FarmShop disponible - exécution de show()');
                window.FarmShop.cookieConsent.show();
                return true;
            }
            return false;
        }
        
        // Essayer immédiatement
        if (!forceCookieCheck()) {
            console.log('🍪 ⏳ FarmShop pas encore chargé - attente...');
            
            // Essayer plusieurs fois avec des délais croissants
            let attempts = 0;
            const maxAttempts = 10;
            
            const checkInterval = setInterval(() => {
                attempts++;
                console.log(`🍪 🔄 Tentative ${attempts}/${maxAttempts}`);
                
                if (forceCookieCheck() || attempts >= maxAttempts) {
                    clearInterval(checkInterval);
                    if (attempts >= maxAttempts) {
                        console.error('🍪 ❌ Impossible de charger FarmShop.cookieConsent après', maxAttempts, 'tentatives');
                    }
                }
            }, 200);
        }
    } else {
        console.log('🍪 👻 Utilisateur invité - pas d\'intervention');
    }
});

// Fonction globale pour test manuel
window.testCookieFix = function() {
    console.log('🍪 🧪 Test manuel du fix');
    const isAuth = document.querySelector('meta[name="user-authenticated"]')?.content === 'true';
    console.log('🍪 Utilisateur connecté:', isAuth);
    
    if (window.FarmShop && window.FarmShop.cookieConsent) {
        console.log('🍪 Exécution manuelle de show()');
        window.FarmShop.cookieConsent.show();
    } else {
        console.error('🍪 FarmShop.cookieConsent non disponible');
    }
};
