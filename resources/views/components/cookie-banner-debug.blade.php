<!-- Bannière de consentement cookies RGPD - Version Debug -->
<div x-data="cookieBannerDebug()" x-init="initBanner()">

<!-- Bannière de consentement cookies -->
<div id="cookie-banner" 
     x-show="!hasConsent" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform translate-y-full"
     x-transition:enter-end="opacity-100 transform translate-y-0"
     class="cookie-banner position-fixed bottom-0 start-0 end-0 bg-white shadow-lg border-top"
     style="z-index: 1050;">
    
    <div class="container-fluid">
        <div class="row align-items-center py-3">
            <!-- Message simplifié -->
            <div class="col">
                <p class="mb-2 text-dark">
                    <strong>🍪 Nous utilisons des cookies</strong> pour améliorer votre expérience.
                </p>
            </div>

            <!-- Boutons d'action -->
            <div class="col-auto">
                <div class="d-flex gap-2">
                    <button @click="acceptAll()" class="btn btn-success btn-sm">
                        Tout accepter
                    </button>
                    <button @click="rejectAll()" class="btn btn-outline-secondary btn-sm">
                        Refuser
                    </button>
                    <button @click="openModal()" class="btn btn-outline-primary btn-sm">
                        Personnaliser
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal simplifié -->
<div x-show="showModal" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center"
     style="background: rgba(0,0,0,0.7); z-index: 1060;"
     x-cloak>
    
    <div class="bg-white rounded-3 shadow-lg p-4 mx-3" 
         style="max-width: 500px; min-width: 400px;">
         
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">🍪 Préférences de cookies</h5>
            <button @click="closeModal()" class="btn-close" aria-label="Fermer"></button>
        </div>

        <!-- Debug info -->
        <div class="alert alert-info small mb-3">
            <strong>Debug:</strong> showModal = <span x-text="showModal"></span>
        </div>

        <!-- Options -->
        <div class="mb-4">
            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" x-model="preferences.analytics" id="analytics">
                <label class="form-check-label" for="analytics">
                    Cookies analytiques
                </label>
            </div>
            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" x-model="preferences.marketing" id="marketing">
                <label class="form-check-label" for="marketing">
                    Cookies marketing
                </label>
            </div>
        </div>

        <!-- Boutons -->
        <div class="d-flex gap-2">
            <button @click="rejectAll()" class="btn btn-outline-secondary flex-fill">
                Refuser tout
            </button>
            <button @click="savePreferencesDebug()" class="btn btn-primary flex-fill">
                Enregistrer
            </button>
            <button @click="acceptAll()" class="btn btn-success flex-fill">
                Tout accepter
            </button>
        </div>
        
        <!-- Bouton de test -->
        <div class="mt-3">
            <button @click="forceCloseModal()" class="btn btn-warning btn-sm w-100">
                🔧 Force Close (Debug)
            </button>
        </div>
    </div>
</div>

<!-- Bouton flottant -->
<button @click="openModal()" 
        x-show="hasConsent"
        class="btn btn-outline-primary btn-sm position-fixed bottom-0 start-0 m-3"
        style="z-index: 1040;">
    <i class="fas fa-cookie-bite"></i> Cookies
</button>

<!-- Bouton de debug -->
<button onclick="resetCookies()" 
        class="btn btn-warning btn-sm position-fixed top-0 end-0 m-3"
        style="z-index: 1050;">
    🔧 Reset
</button>

</div>

<!-- Styles CSS -->
<style>
[x-cloak] { 
    display: none !important; 
}

.cookie-banner {
    border-top: 3px solid #059669;
}
</style>

<!-- JavaScript Debug -->
<script>
function cookieBannerDebug() {
    return {
        hasConsent: false,
        showModal: false,
        preferences: {
            analytics: false,
            marketing: false
        },
        
        initBanner() {
            console.log('🚀 Init banner');
            this.hasConsent = this.getCookie('farmshop_cookie_consent') !== null;
            console.log('Has consent:', this.hasConsent);
        },

        acceptAll() {
            console.log('✅ Accept all clicked');
            this.preferences.analytics = true;
            this.preferences.marketing = true;
            this.saveConsent();
            this.forceCloseModal();
        },

        rejectAll() {
            console.log('❌ Reject all clicked');
            this.preferences.analytics = false;
            this.preferences.marketing = false;
            this.saveConsent();
            this.forceCloseModal();
        },

        savePreferencesDebug() {
            console.log('💾 Save preferences clicked');
            console.log('Current preferences:', this.preferences);
            this.saveConsent();
            this.forceCloseModal();
        },

        openModal() {
            console.log('🔓 Opening modal');
            this.showModal = true;
            console.log('showModal set to:', this.showModal);
        },

        closeModal() {
            console.log('🔒 Closing modal');
            this.showModal = false;
            console.log('showModal set to:', this.showModal);
        },
        
        forceCloseModal() {
            console.log('⚡ Force closing modal');
            this.showModal = false;
            console.log('Modal forcibly closed, showModal:', this.showModal);
            
            // Double vérification après un délai
            setTimeout(() => {
                console.log('⏰ Check after delay, showModal:', this.showModal);
            }, 100);
        },

        saveConsent() {
            console.log('💾 Saving consent...');
            try {
                this.setCookie('farmshop_cookie_consent', 'true', 365);
                this.setCookie('farmshop_cookie_preferences', JSON.stringify(this.preferences), 365);
                
                this.hasConsent = true;
                console.log('✅ Consent saved successfully');
                
                this.showToast('✅ Préférences enregistrées !');
            } catch (error) {
                console.error('❌ Error saving consent:', error);
            }
        },

        setCookie(name, value, days) {
            const expires = new Date(Date.now() + days * 864e5).toUTCString();
            document.cookie = name + '=' + encodeURIComponent(value) + '; expires=' + expires + '; path=/; SameSite=Lax';
        },

        getCookie(name) {
            return document.cookie.split('; ').reduce(function(r, v) {
                const parts = v.split('=');
                return parts[0] === name ? decodeURIComponent(parts[1]) : r;
            }, null);
        },

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
    }
}

// Fonction de reset globale
window.resetCookies = function() {
    console.log('🗑️ Resetting cookies...');
    document.cookie = "farmshop_cookie_consent=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    document.cookie = "farmshop_cookie_preferences=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    location.reload();
}
</script>
