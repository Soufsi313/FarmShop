<!-- Bannière cookies avec solution de contournement DOM -->
<div x-data="cookieBannerFixed()" x-init="initBanner()">

<!-- Bannière de consentement cookies -->
<div id="cookie-banner" 
     x-show="!hasConsent" 
     class="cookie-banner position-fixed bottom-0 start-0 end-0 bg-white shadow-lg border-top"
     style="z-index: 1050;">
    
    <div class="container-fluid">
        <div class="row align-items-center py-3">
            <div class="col">
                <p class="mb-2 text-dark">
                    <strong>🍪 Nous utilisons des cookies</strong> pour améliorer votre expérience.
                </p>
            </div>
            <div class="col-auto">
                <div class="d-flex gap-2">
                    <button @click="acceptAllAndClose()" class="btn btn-success btn-sm">
                        Tout accepter
                    </button>
                    <button @click="rejectAllAndClose()" class="btn btn-outline-secondary btn-sm">
                        Refuser
                    </button>
                    <button @click="openModalFixed()" class="btn btn-outline-primary btn-sm">
                        Personnaliser
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal avec ID unique pour manipulation DOM directe -->
<div id="cookie-modal-container" 
     x-show="showModal" 
     class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center"
     style="background: rgba(0,0,0,0.7); z-index: 1060; display: none;">
    
    <div class="bg-white rounded-3 shadow-lg p-4 mx-3" style="max-width: 500px;">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">🍪 Préférences de cookies</h5>
            <button @click="closeModalFixed()" class="btn-close"></button>
        </div>

        <!-- Debug -->
        <div class="alert alert-info small mb-3">
            État: <span x-text="showModal ? 'OUVERT' : 'FERMÉ'"></span> | 
            Consentement: <span x-text="hasConsent ? 'OUI' : 'NON'"></span>
        </div>

        <!-- Options -->
        <div class="mb-4">
            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" x-model="preferences.analytics" id="analytics">
                <label class="form-check-label" for="analytics">Cookies analytiques</label>
            </div>
            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" x-model="preferences.marketing" id="marketing">
                <label class="form-check-label" for="marketing">Cookies marketing</label>
            </div>
        </div>

        <!-- Boutons -->
        <div class="d-flex gap-2 mb-3">
            <button @click="rejectAllAndClose()" class="btn btn-outline-secondary flex-fill">
                Refuser tout
            </button>
            <button @click="savePreferencesAndClose()" class="btn btn-primary flex-fill">
                Enregistrer
            </button>
            <button @click="acceptAllAndClose()" class="btn btn-success flex-fill">
                Tout accepter
            </button>
        </div>
        
        <!-- Boutons de secours -->
        <div class="d-flex gap-2">
            <button onclick="forceCloseModal()" class="btn btn-warning btn-sm flex-fill">
                🔧 Fermer (DOM direct)
            </button>
            <button onclick="location.reload()" class="btn btn-secondary btn-sm flex-fill">
                🔄 Recharger page
            </button>
        </div>
    </div>
</div>

<!-- Bouton flottant -->
<button @click="openModalFixed()" 
        x-show="hasConsent"
        class="btn btn-outline-primary btn-sm position-fixed bottom-0 start-0 m-3"
        style="z-index: 1040;">
    <i class="fas fa-cookie-bite"></i> Cookies
</button>

<!-- Bouton reset -->
<button onclick="resetCookies()" 
        class="btn btn-warning btn-sm position-fixed top-0 end-0 m-3"
        style="z-index: 1050;">
    🔧 Reset
</button>

</div>

<script>
function cookieBannerFixed() {
    return {
        hasConsent: false,
        showModal: false,
        preferences: {
            analytics: false,
            marketing: false
        },
        
        initBanner() {
            console.log('🚀 Init banner fixed');
            this.hasConsent = this.getCookie('farmshop_cookie_consent') !== null;
            console.log('Has consent:', this.hasConsent);
        },

        openModalFixed() {
            console.log('🔓 Opening modal with DOM manipulation');
            this.showModal = true;
            
            // Force DOM update
            setTimeout(() => {
                const modal = document.getElementById('cookie-modal-container');
                if (modal) {
                    modal.style.display = 'flex';
                    console.log('✅ Modal forcibly shown via DOM');
                }
            }, 10);
        },

        closeModalFixed() {
            console.log('🔒 Closing modal with DOM manipulation');
            this.showModal = false;
            
            // Force DOM update
            const modal = document.getElementById('cookie-modal-container');
            if (modal) {
                modal.style.display = 'none';
                console.log('✅ Modal forcibly hidden via DOM');
            }
        },

        acceptAllAndClose() {
            console.log('✅ Accept all and close');
            this.preferences.analytics = true;
            this.preferences.marketing = true;
            this.saveConsent();
            this.closeModalFixed();
        },

        rejectAllAndClose() {
            console.log('❌ Reject all and close');
            this.preferences.analytics = false;
            this.preferences.marketing = false;
            this.saveConsent();
            this.closeModalFixed();
        },

        savePreferencesAndClose() {
            console.log('💾 Save preferences and close');
            this.saveConsent();
            this.closeModalFixed();
        },

        saveConsent() {
            console.log('💾 Saving consent...');
            try {
                this.setCookie('farmshop_cookie_consent', 'true', 365);
                this.setCookie('farmshop_cookie_preferences', JSON.stringify(this.preferences), 365);
                
                this.hasConsent = true;
                console.log('✅ Consent saved successfully');
                
                // Show toast
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

// Fonctions globales pour forcer la fermeture
window.forceCloseModal = function() {
    console.log('⚡ Force close via global function');
    const modal = document.getElementById('cookie-modal-container');
    if (modal) {
        modal.style.display = 'none';
        console.log('✅ Modal hidden via global function');
    }
}

window.resetCookies = function() {
    console.log('🗑️ Resetting cookies...');
    document.cookie = "farmshop_cookie_consent=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    document.cookie = "farmshop_cookie_preferences=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    location.reload();
}
</script>

<style>
.cookie-banner {
    border-top: 3px solid #059669;
}
</style>
