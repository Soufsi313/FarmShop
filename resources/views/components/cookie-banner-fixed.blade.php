<!-- Bannière de consentement cookies RGPD - Version corrigée -->
<div x-data="cookieBannerData()" x-init="initBanner()">

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
            <!-- Icône et titre -->
            <div class="col-12 col-lg-auto mb-3 mb-lg-0">
                <div class="d-flex align-items-center">
                    <div class="cookie-icon me-3">
                        <i class="fas fa-cookie-bite text-warning fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="mb-1 fw-bold text-dark">🍪 Gestion des cookies</h6>
                        <small class="text-muted">Respectons votre vie privée</small>
                    </div>
                </div>
            </div>

            <!-- Message principal -->
            <div class="col-12 col-lg mb-3 mb-lg-0">
                <p class="mb-2 text-dark">
                    <strong>Nous utilisons des cookies</strong> pour améliorer votre expérience sur FarmShop, 
                    analyser l'utilisation du site et vous proposer des contenus personnalisés.
                </p>
                <div class="d-flex flex-wrap gap-2 small">
                    <span class="badge bg-success">Essentiels</span>
                    <span class="badge bg-primary" x-show="preferences.analytics">Analytiques</span>
                    <span class="badge bg-warning" x-show="preferences.marketing">Marketing</span>
                    <span class="badge bg-info" x-show="preferences.personalization">Personnalisation</span>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="col-12 col-lg-auto">
                <div class="d-flex flex-column flex-sm-row gap-2">
                    <div class="d-flex gap-2">
                        <button @click="acceptAll()" class="btn btn-success btn-sm px-3">
                            <i class="fas fa-check me-1"></i>
                            Tout accepter
                        </button>
                        <button @click="rejectOptional()" class="btn btn-outline-secondary btn-sm px-3">
                            <i class="fas fa-times me-1"></i>
                            Refuser
                        </button>
                    </div>
                    <button @click="openModal()" class="btn btn-outline-primary btn-sm px-3">
                        <i class="fas fa-cog me-1"></i>
                        Personnaliser
                    </button>
                </div>
            </div>

            <!-- Bouton fermer -->
            <div class="col-auto">
                <button @click="acceptEssential()" class="btn-close" aria-label="Fermer"></button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de personnalisation -->
<div x-show="showModal" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="modal-backdrop position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center"
     style="background: rgba(0,0,0,0.7); z-index: 1060;"
     x-cloak>
    
    <div @click.away="closeModal()" 
         class="modal-content bg-white rounded-3 shadow-lg p-0 mx-3" 
         style="max-width: 600px; max-height: 90vh; overflow-y: auto;">
         
        <!-- Header -->
        <div class="modal-header border-bottom p-4">
            <div class="d-flex align-items-center">
                <i class="fas fa-shield-alt text-primary me-3 fa-2x"></i>
                <div>
                    <h4 class="modal-title mb-1">Préférences de cookies</h4>
                    <p class="text-muted mb-0 small">Gérez vos préférences de confidentialité</p>
                </div>
            </div>
            <button @click="closeModal()" class="btn-close ms-auto" aria-label="Fermer"></button>
        </div>

        <!-- Corps -->
        <div class="modal-body p-4">
            
            <!-- Cookies essentiels -->
            <div class="cookie-category mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="mb-1 fw-bold text-success">
                            <i class="fas fa-shield-alt me-2"></i>
                            Cookies essentiels
                        </h6>
                        <small class="text-muted">Nécessaires au fonctionnement du site</small>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" checked disabled>
                        <label class="form-check-label text-muted small">Toujours actifs</label>
                    </div>
                </div>
                <p class="small text-muted mb-0">
                    Ces cookies sont nécessaires pour vous permettre de naviguer sur le site et d'utiliser 
                    ses fonctionnalités de base (authentification, panier, sécurité).
                </p>
            </div>

            <!-- Cookies analytiques -->
            <div class="cookie-category mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="mb-1 fw-bold text-primary">
                            <i class="fas fa-chart-bar me-2"></i>
                            Cookies analytiques
                        </h6>
                        <small class="text-muted">Pour améliorer nos services</small>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" 
                               x-model="preferences.analytics" 
                               id="analytics-toggle">
                        <label class="form-check-label" for="analytics-toggle"></label>
                    </div>
                </div>
                <p class="small text-muted mb-0">
                    Ces cookies nous aident à comprendre comment vous utilisez notre site 
                    pour améliorer votre expérience (Google Analytics, statistiques de visite).
                </p>
            </div>

            <!-- Cookies marketing -->
            <div class="cookie-category mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="mb-1 fw-bold text-warning">
                            <i class="fas fa-bullhorn me-2"></i>
                            Cookies marketing
                        </h6>
                        <small class="text-muted">Publicités personnalisées</small>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" 
                               x-model="preferences.marketing" 
                               id="marketing-toggle">
                        <label class="form-check-label" for="marketing-toggle"></label>
                    </div>
                </div>
                <p class="small text-muted mb-0">
                    Ces cookies permettent de vous proposer des publicités plus pertinentes 
                    sur d'autres sites web (Facebook Pixel, Google Ads).
                </p>
            </div>

            <!-- Cookies de personnalisation -->
            <div class="cookie-category mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="mb-1 fw-bold text-info">
                            <i class="fas fa-user-cog me-2"></i>
                            Cookies de personnalisation
                        </h6>
                        <small class="text-muted">Contenu adapté à vos préférences</small>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" 
                               x-model="preferences.personalization" 
                               id="personalization-toggle">
                        <label class="form-check-label" for="personalization-toggle"></label>
                    </div>
                </div>
                <p class="small text-muted mb-0">
                    Ces cookies nous permettent de personnaliser le contenu et les fonctionnalités 
                    selon vos préférences (langue, région, produits recommandés).
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="modal-footer border-top p-4">
            <div class="d-flex flex-column flex-sm-row gap-2 w-100">
                <button @click="rejectOptional()" class="btn btn-outline-secondary flex-fill">
                    Refuser tout
                </button>
                <button @click="savePreferences()" class="btn btn-primary flex-fill">
                    Enregistrer mes préférences
                </button>
                <button @click="acceptAll()" class="btn btn-success flex-fill">
                    Tout accepter
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Bouton flottant -->
<button @click="openModal()" 
        x-show="hasConsent"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform scale-75"
        x-transition:enter-end="opacity-100 transform scale-100"
        class="btn btn-outline-primary btn-sm position-fixed bottom-0 start-0 m-3 d-flex align-items-center gap-2"
        style="z-index: 1040; border-radius: 25px; backdrop-filter: blur(10px); background: rgba(255,255,255,0.9);"
        title="Gérer mes préférences de cookies">
    <i class="fas fa-cookie-bite"></i>
    <span class="d-none d-sm-inline">Cookies</span>
</button>

<!-- Bouton de debug -->
<button onclick="resetCookieBanner()" 
        class="btn btn-warning btn-sm position-fixed top-0 end-0 m-3"
        style="z-index: 1050;"
        title="Réinitialiser la bannière de cookies (DEBUG)">
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
    backdrop-filter: blur(10px);
}

.cookie-icon {
    animation: bounce 2s infinite;
}

@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-5px); }
}

.modal-backdrop {
    backdrop-filter: blur(5px);
}

.cookie-category {
    border-left: 4px solid #e5e7eb;
    padding-left: 1rem;
    transition: border-color 0.3s ease;
}

.cookie-category:hover {
    border-left-color: #059669;
}

.form-check-input:checked {
    background-color: #059669;
    border-color: #059669;
}

@media (max-width: 768px) {
    .modal-content {
        margin: 1rem;
        max-height: 95vh;
    }
}
</style>

<!-- JavaScript -->
<script>
function cookieBannerData() {
    return {
        hasConsent: false,
        showModal: false,
        preferences: {
            essential: true,
            analytics: false,
            marketing: false,
            personalization: false
        },
        
        initBanner() {
            this.hasConsent = this.getCookie('farmshop_cookie_consent') !== null;
            if (this.hasConsent) {
                this.loadPreferences();
            }
        },

        acceptAll() {
            this.preferences = {
                essential: true,
                analytics: true,
                marketing: true,
                personalization: true
            };
            this.saveConsent();
            this.closeModal();
        },

        rejectOptional() {
            this.preferences = {
                essential: true,
                analytics: false,
                marketing: false,
                personalization: false
            };
            this.saveConsent();
            this.closeModal();
        },

        acceptEssential() {
            this.preferences = {
                essential: true,
                analytics: false,
                marketing: false,
                personalization: false
            };
            this.saveConsent();
            this.closeModal();
        },

        savePreferences() {
            this.saveConsent();
            this.closeModal();
        },

        openModal() {
            this.showModal = true;
        },

        closeModal() {
            this.showModal = false;
            console.log('Modal fermé');
        },

        saveConsent() {
            try {
                // Sauvegarder les cookies
                this.setCookie('farmshop_cookie_consent', 'true', 365);
                this.setCookie('farmshop_cookie_preferences', JSON.stringify(this.preferences), 365);
                
                // Mettre à jour l'état
                this.hasConsent = true;
                this.showModal = false;
                
                // Afficher la notification
                this.showToast('✅ Préférences enregistrées avec succès !');
                
                console.log('Consentement sauvegardé:', this.preferences);
            } catch (error) {
                console.error('Erreur lors de la sauvegarde:', error);
                this.showToast('❌ Erreur lors de la sauvegarde');
            }
        },

        loadPreferences() {
            const saved = this.getCookie('farmshop_cookie_preferences');
            if (saved) {
                try {
                    this.preferences = Object.assign(this.preferences, JSON.parse(saved));
                } catch (e) {
                    console.warn('Erreur chargement préférences:', e);
                }
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
            
            setTimeout(function() {
                if (toast.parentElement) {
                    toast.remove();
                }
            }, 3000);
        }
    }
}

// Fonctions globales
window.reopenCookiePreferences = function() {
    const container = document.querySelector('[x-data]');
    if (container && container._x_dataStack && container._x_dataStack[0]) {
        container._x_dataStack[0].openModal();
    }
}

window.resetCookieBanner = function() {
    document.cookie = "farmshop_cookie_consent=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    document.cookie = "farmshop_cookie_preferences=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    location.reload();
}
</script>
