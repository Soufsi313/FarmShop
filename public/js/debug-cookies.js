// Script de debug pour tester la bannière cookies
console.log('🔧 Debug cookies - FarmShop');

// Fonction pour vérifier l'état des cookies
function checkCookieState() {
    const consent = getCookie('farmshop_cookie_consent');
    const preferences = getCookie('farmshop_cookie_preferences');
    
    console.log('📊 État des cookies:');
    console.log('  - Consentement:', consent);
    console.log('  - Préférences:', preferences);
    
    if (preferences) {
        try {
            console.log('  - Préférences détail:', JSON.parse(preferences));
        } catch (e) {
            console.error('  - Erreur parse préférences:', e);
        }
    }
}

// Fonction pour obtenir un cookie
function getCookie(name) {
    return document.cookie.split('; ').reduce((r, v) => {
        const parts = v.split('=');
        return parts[0] === name ? decodeURIComponent(parts[1]) : r;
    }, null);
}

// Fonction pour réinitialiser (même que le bouton Reset)
function debugResetCookies() {
    console.log('🗑️ Réinitialisation des cookies...');
    document.cookie = "farmshop_cookie_consent=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    document.cookie = "farmshop_cookie_preferences=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    console.log('✅ Cookies supprimés, rechargement...');
    location.reload();
}

// Fonction pour ouvrir le modal manuellement
function debugOpenModal() {
    console.log('🔧 Ouverture du modal...');
    const container = document.querySelector('[x-data]');
    if (container && container._x_dataStack && container._x_dataStack[0]) {
        container._x_dataStack[0].openModal();
        console.log('✅ Modal ouvert');
    } else {
        console.error('❌ Impossible de trouver le composant Alpine.js');
    }
}

// Fonction pour fermer le modal manuellement
function debugCloseModal() {
    console.log('🔧 Fermeture du modal...');
    const container = document.querySelector('[x-data]');
    if (container && container._x_dataStack && container._x_dataStack[0]) {
        container._x_dataStack[0].closeModal();
        console.log('✅ Modal fermé');
    } else {
        console.error('❌ Impossible de trouver le composant Alpine.js');
    }
}

// Vérifier l'état au chargement
checkCookieState();

// Rendre les fonctions disponibles globalement
window.debugCookies = {
    check: checkCookieState,
    reset: debugResetCookies,
    openModal: debugOpenModal,
    closeModal: debugCloseModal
};

console.log('🎯 Fonctions de debug disponibles:');
console.log('  - debugCookies.check() : Vérifier l\'état');
console.log('  - debugCookies.reset() : Réinitialiser');
console.log('  - debugCookies.openModal() : Ouvrir modal');
console.log('  - debugCookies.closeModal() : Fermer modal');
