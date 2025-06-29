<!-- Container global pour la bannière et le modal de cookies -->
<div x-data="{
        hasConsent: false,
        showModal: false,
        preferences: {
            essential: true,
            analytics: false,
            marketing: false,
            personalization: false
        },
        
        init() {
            // Vérifier si l'utilisateur a déjà donné son consentement
            this.hasConsent = this.getCookie('farmshop_cookie_consent') !== null;
            
            // Charger les préférences sauvegardées
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
        },

        rejectOptional() {
            this.preferences = {
                essential: true,
                analytics: false,
                marketing: false,
                personalization: false
            };
            this.saveConsent();
        },

        acceptEssential() {
            this.preferences = {
                essential: true,
                analytics: false,
                marketing: false,
                personalization: false
            };
            this.saveConsent();
        },

        savePreferences() {
            console.log('savePreferences called, showModal:', this.showModal);
            this.saveConsent();
            console.log('savePreferences finished, showModal:', this.showModal);
        },

        openModal() {
            console.log('Opening modal');
            this.showModal = true;
        },

        closeModal() {
            console.log('Closing modal');
            this.showModal = false;
        },

        saveConsent() {
            console.log('saveConsent called, showModal before:', this.showModal);
            // Sauvegarder le consentement global
            this.setCookie('farmshop_cookie_consent', 'true', 365);
            
            // Sauvegarder les préférences détaillées
            this.setCookie('farmshop_cookie_preferences', JSON.stringify(this.preferences), 365);
            
            this.hasConsent = true;
            
            // Forcer la fermeture du modal avec un petit délai pour éviter les conflits
            setTimeout(() => {
                this.showModal = false;
                console.log('Modal closed via timeout');
            }, 100);
            
            console.log('saveConsent finished, showModal after:', this.showModal);
            
            // Notification toast élégante
            this.showToast('✅ Vos préférences ont été enregistrées avec succès !', 'success');
        },

        loadPreferences() {
            const savedPreferences = this.getCookie('farmshop_cookie_preferences');
            if (savedPreferences) {
                try {
                    this.preferences = { ...this.preferences, ...JSON.parse(savedPreferences) };
                } catch (e) {
                    console.warn('Erreur lors du chargement des préférences cookies:', e);
                }
            }
        },

        // Méthodes utilitaires pour les cookies
        setCookie(name, value, days) {
            const expires = new Date(Date.now() + days * 864e5).toUTCString();
            document.cookie = `${name}=${encodeURIComponent(value)}; expires=${expires}; path=/; SameSite=Lax`;
        },

        getCookie(name) {
            return document.cookie.split('; ').reduce((r, v) => {
                const parts = v.split('=');
                return parts[0] === name ? decodeURIComponent(parts[1]) : r;
            }, null);
        },

        // Méthode pour afficher une notification toast
        showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = 'alert alert-' + type + ' alert-dismissible position-fixed top-0 end-0 m-3 fade show';
            toast.style.cssText = 'z-index: 1070; min-width: 300px;';
            toast.innerHTML = '<div class=\"d-flex align-items-center\">' +
                '<i class=\"fas fa-' + (type === 'success' ? 'check-circle' : 'info-circle') + ' me-2\"></i>' +
                '<span>' + message + '</span>' +
                '<button type=\"button\" class=\"btn-close ms-auto\" onclick=\"this.parentElement.parentElement.remove()\"></button>' +
                '</div>';
            document.body.appendChild(toast);
            
            // Animation d'entrée
            setTimeout(() => toast.classList.add('show'), 10);
            
            // Supprimer automatiquement après 4 secondes
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.classList.remove('show');
                    setTimeout(() => toast.remove(), 300);
                }
            }, 4000);
        }
     }">

<!-- Bannière de consentement cookies RGPD -->
<div id="cookie-banner" 
     x-show="!hasConsent" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform translate-y-full"
     x-transition:enter-end="opacity-100 transform translate-y-0"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="opacity-100 transform translate-y-0"
     x-transition:leave-end="opacity-0 transform translate-y-full"
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
                    <!-- Boutons principaux -->
                    <div class="d-flex gap-2">
                        <button @click="acceptAll()" 
                                class="btn btn-success btn-sm px-3">
                            <i class="fas fa-check me-1"></i>
                            Tout accepter
                        </button>
                        <button @click="rejectOptional()" 
                                class="btn btn-outline-secondary btn-sm px-3">
                            <i class="fas fa-times me-1"></i>
                            Refuser
                        </button>
                    </div>
                    
                    <!-- Bouton personnaliser -->
                    <button @click="openModal()" 
                            class="btn btn-outline-primary btn-sm px-3">
                        <i class="fas fa-cog me-1"></i>
                        Personnaliser
                    </button>
                </div>
            </div>

            <!-- Bouton fermer -->
            <div class="col-auto">
                <button @click="acceptEssential()" 
                        class="btn-close" 
                        aria-label="Fermer"></button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de personnalisation des cookies -->
<div id="cookie-modal" 
     x-show="showModal" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="modal-backdrop position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center"
     style="background: rgba(0,0,0,0.7); z-index: 1060;">
    
    <div @click.away="closeModal()" 
         class="modal-content bg-white rounded-3 shadow-lg p-0 mx-3" 
         style="max-width: 600px; max-height: 90vh; overflow-y: auto;">
         
        <!-- Header du modal -->
        <div class="modal-header border-bottom p-4">
            <div class="d-flex align-items-center">
                <i class="fas fa-shield-alt text-primary me-3 fa-2x"></i>
                <div>
                    <h4 class="modal-title mb-1">Préférences de cookies</h4>
                    <p class="text-muted mb-0 small">Gérez vos préférences de confidentialité</p>
                </div>
            </div>
            <button @click="closeModal()" 
                    class="btn-close ms-auto" 
                    aria-label="Fermer"></button>
        </div>

        <!-- Corps du modal -->
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

            <!-- Liens utiles -->
            <div class="border-top pt-3">
                <div class="row text-center">
                    <div class="col-6 col-md-4 mb-2">
                        <a href="#" class="text-decoration-none small">
                            <i class="fas fa-file-alt text-muted me-1"></i>
                            Politique de cookies
                        </a>
                    </div>
                    <div class="col-6 col-md-4 mb-2">
                        <a href="#" class="text-decoration-none small">
                            <i class="fas fa-shield-alt text-muted me-1"></i>
                            Confidentialité
                        </a>
                    </div>
                    <div class="col-12 col-md-4 mb-2">
                        <a href="#" class="text-decoration-none small">
                            <i class="fas fa-gavel text-muted me-1"></i>
                            Mentions légales
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer du modal -->
        <div class="modal-footer border-top p-4">
            <div class="d-flex flex-column flex-sm-row gap-2 w-100">
                <button @click="rejectOptional()" 
                        class="btn btn-outline-secondary flex-fill">
                    Refuser tout
                </button>
                <button @click="savePreferences()" 
                        class="btn btn-primary flex-fill">
                    Enregistrer mes préférences
                </button>
                <button @click="acceptAll()" 
                        class="btn btn-success flex-fill">
                    Tout accepter
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Bouton flottant pour gérer les cookies (toujours visible) -->
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

<!-- Bouton de debug pour réinitialiser (visible en développement) -->
<button onclick="resetCookieBanner()" 
        class="btn btn-warning btn-sm position-fixed top-0 end-0 m-3"
        style="z-index: 1050;"
        title="Réinitialiser la bannière de cookies (DEBUG)">
    🔧 Reset Cookies
</button>

</div> <!-- Fermeture du container global x-data -->

<!-- Styles CSS spécifiques pour la bannière cookies -->
<style>
.cookie-banner {
    border-top: 3px solid var(--primary-color, #059669);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
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
    -webkit-backdrop-filter: blur(5px);
}

.cookie-category {
    border-left: 4px solid var(--bs-gray-200);
    padding-left: 1rem;
    transition: border-color 0.3s ease;
}

.cookie-category:hover {
    border-left-color: var(--primary-color, #059669);
}

.form-check-input:checked {
    background-color: var(--primary-color, #059669);
    border-color: var(--primary-color, #059669);
}

.badge {
    font-size: 0.75rem;
}

/* Animation pour l'apparition des éléments */
@keyframes slideInUp {
    from {
        transform: translateY(100%);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.cookie-banner[x-show] {
    animation: slideInUp 0.3s ease-out;
}

/* Responsive */
@media (max-width: 768px) {
    .modal-content {
        margin: 1rem;
        max-height: 95vh;
    }
    
    .cookie-banner .container-fluid {
        padding: 1rem;
    }
}
</style>

<script>
// Fonction globale pour rouvrir les préférences depuis le footer
window.reopenCookiePreferences = function() {
    const container = document.querySelector('[x-data]');
    if (container && container._x_dataStack && container._x_dataStack[0]) {
        container._x_dataStack[0].openModal();
    } else {
        resetCookieBanner();
    }
}

// Fonction de debug pour tester la bannière
window.resetCookieBanner = function() {
    document.cookie = "farmshop_cookie_consent=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    document.cookie = "farmshop_cookie_preferences=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    location.reload();
}
</script>
