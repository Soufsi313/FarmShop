<!-- Bannière cookies - Solution JavaScript pur -->
<div id="cookie-banner-container">

<!-- Bannière de consentement -->
<div id="cookie-banner" 
     class="cookie-banner position-fixed bottom-0 start-0 end-0 bg-white shadow-lg border-top"
     style="z-index: 1050; display: none;">
    
    <div class="container-fluid">
        <div class="row align-items-center py-3">
            <div class="col">
                <p class="mb-2 text-dark">
                    <strong>🍪 Nous utilisons des cookies</strong> pour améliorer votre expérience sur FarmShop.
                </p>
            </div>
            <div class="col-auto">
                <div class="d-flex gap-2">
                    <button onclick="cookieManager.acceptAll()" class="btn btn-success btn-sm">
                        Tout accepter
                    </button>
                    <button onclick="cookieManager.rejectAll()" class="btn btn-outline-secondary btn-sm">
                        Refuser
                    </button>
                    <button onclick="cookieManager.openModal()" class="btn btn-outline-primary btn-sm">
                        Personnaliser
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de personnalisation -->
<div id="cookie-modal" 
     class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center"
     style="background: rgba(0,0,0,0.7); z-index: 1060; display: none;">
    
    <div class="bg-white rounded-3 shadow-lg p-4 mx-3" style="max-width: 500px;">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">🍪 Préférences de cookies</h5>
            <button onclick="cookieManager.closeModal()" class="btn-close"></button>
        </div>

        <!-- Options -->
        <div class="mb-4">
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="analytics-check">
                <label class="form-check-label" for="analytics-check">
                    <strong>Cookies analytiques</strong><br>
                    <small class="text-muted">Nous aident à comprendre comment vous utilisez le site</small>
                </label>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="marketing-check">
                <label class="form-check-label" for="marketing-check">
                    <strong>Cookies marketing</strong><br>
                    <small class="text-muted">Publicités personnalisées selon vos intérêts</small>
                </label>
            </div>
        </div>

        <!-- Boutons -->
        <div class="d-flex gap-2">
            <button onclick="cookieManager.rejectAll()" class="btn btn-outline-secondary flex-fill">
                Refuser tout
            </button>
            <button onclick="cookieManager.savePreferences()" class="btn btn-primary flex-fill">
                Enregistrer
            </button>
            <button onclick="cookieManager.acceptAll()" class="btn btn-success flex-fill">
                Tout accepter
            </button>
        </div>
    </div>
</div>

<!-- Bouton flottant (visible quand consentement donné) -->
<button id="cookie-settings-btn" 
        onclick="cookieManager.openModal()"
        class="btn btn-outline-primary btn-sm position-fixed bottom-0 start-0 m-3"
        style="z-index: 1040; display: none;">
    <i class="fas fa-cookie-bite"></i> Cookies
</button>

<!-- Bouton reset -->
<button onclick="cookieManager.reset()" 
        class="btn btn-warning btn-sm position-fixed top-0 end-0 m-3"
        style="z-index: 1050;">
    🔧 Reset
</button>

</div>

<script>
// Gestionnaire de cookies en JavaScript pur
window.cookieManager = {
    
    // Initialisation
    init() {
        console.log('🚀 Cookie Manager Init');
        this.checkConsent();
    },
    
    // Vérifier si consentement existe
    checkConsent() {
        const hasConsent = this.getCookie('farmshop_cookie_consent');
        console.log('Has consent:', hasConsent);
        
        if (hasConsent) {
            this.hideBanner();
            this.showSettingsButton();
            this.loadPreferences();
        } else {
            this.showBanner();
            this.hideSettingsButton();
        }
    },
    
    // Afficher la bannière
    showBanner() {
        const banner = document.getElementById('cookie-banner');
        if (banner) {
            banner.style.display = 'block';
            console.log('✅ Bannière affichée');
        }
    },
    
    // Masquer la bannière
    hideBanner() {
        const banner = document.getElementById('cookie-banner');
        if (banner) {
            banner.style.display = 'none';
            console.log('✅ Bannière masquée');
        }
    },
    
    // Afficher bouton paramètres
    showSettingsButton() {
        const btn = document.getElementById('cookie-settings-btn');
        if (btn) {
            btn.style.display = 'block';
        }
    },
    
    // Masquer bouton paramètres
    hideSettingsButton() {
        const btn = document.getElementById('cookie-settings-btn');
        if (btn) {
            btn.style.display = 'none';
        }
    },
    
    // Ouvrir le modal
    openModal() {
        const modal = document.getElementById('cookie-modal');
        if (modal) {
            modal.style.display = 'flex';
            console.log('🔓 Modal ouvert');
            this.loadPreferences();
        }
    },
    
    // Fermer le modal
    closeModal() {
        const modal = document.getElementById('cookie-modal');
        if (modal) {
            modal.style.display = 'none';
            console.log('🔒 Modal fermé');
        }
    },
    
    // Accepter tous les cookies
    acceptAll() {
        console.log('✅ Accepter tout');
        this.setPreferences({
            analytics: true,
            marketing: true
        });
        this.saveConsent();
        this.closeModal();
        this.hideBanner();
        this.showSettingsButton();
        this.showToast('✅ Tous les cookies acceptés !');
    },
    
    // Refuser tous les cookies optionnels
    rejectAll() {
        console.log('❌ Refuser tout');
        this.setPreferences({
            analytics: false,
            marketing: false
        });
        this.saveConsent();
        this.closeModal();
        this.hideBanner();
        this.showSettingsButton();
        this.showToast('❌ Cookies optionnels refusés');
    },
    
    // Sauvegarder les préférences personnalisées
    savePreferences() {
        console.log('💾 Sauvegarder préférences');
        const analytics = document.getElementById('analytics-check').checked;
        const marketing = document.getElementById('marketing-check').checked;
        
        this.setPreferences({
            analytics: analytics,
            marketing: marketing
        });
        
        this.saveConsent();
        this.closeModal();
        this.hideBanner();
        this.showSettingsButton();
        this.showToast('✅ Préférences enregistrées !');
    },
    
    // Définir les préférences
    setPreferences(prefs) {
        this.preferences = prefs;
        console.log('Préférences définies:', prefs);
        
        // Mettre à jour les checkboxes
        const analyticsCheck = document.getElementById('analytics-check');
        const marketingCheck = document.getElementById('marketing-check');
        
        if (analyticsCheck) analyticsCheck.checked = prefs.analytics;
        if (marketingCheck) marketingCheck.checked = prefs.marketing;
    },
    
    // Charger les préférences sauvegardées
    loadPreferences() {
        const saved = this.getCookie('farmshop_cookie_preferences');
        if (saved) {
            try {
                const prefs = JSON.parse(saved);
                this.setPreferences(prefs);
            } catch (e) {
                console.warn('Erreur chargement préférences:', e);
            }
        }
    },
    
    // Sauvegarder le consentement
    saveConsent() {
        this.setCookie('farmshop_cookie_consent', 'true', 365);
        this.setCookie('farmshop_cookie_preferences', JSON.stringify(this.preferences), 365);
        console.log('✅ Consentement sauvegardé');
    },
    
    // Réinitialiser
    reset() {
        console.log('🗑️ Reset cookies');
        this.deleteCookie('farmshop_cookie_consent');
        this.deleteCookie('farmshop_cookie_preferences');
        location.reload();
    },
    
    // Utilitaires cookies
    setCookie(name, value, days) {
        const expires = new Date(Date.now() + days * 864e5).toUTCString();
        document.cookie = name + '=' + encodeURIComponent(value) + '; expires=' + expires + '; path=/; SameSite=Lax';
    },
    
    getCookie(name) {
        return document.cookie.split('; ').reduce((r, v) => {
            const parts = v.split('=');
            return parts[0] === name ? decodeURIComponent(parts[1]) : r;
        }, null);
    },
    
    deleteCookie(name) {
        document.cookie = name + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    },
    
    // Notification toast
    showToast(message) {
        const toast = document.createElement('div');
        toast.className = 'alert alert-success position-fixed top-0 end-0 m-3';
        toast.style.cssText = 'z-index: 1070; min-width: 300px;';
        toast.textContent = message;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            if (toast.parentElement) {
                toast.remove();
            }
        }, 3000);
    }
};

// Initialiser au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    cookieManager.init();
});

// Fermer le modal en cliquant en dehors
document.addEventListener('click', function(e) {
    const modal = document.getElementById('cookie-modal');
    if (e.target === modal) {
        cookieManager.closeModal();
    }
});
</script>

<style>
.cookie-banner {
    border-top: 3px solid #059669;
    backdrop-filter: blur(10px);
}
</style>
