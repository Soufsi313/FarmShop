<!-- Bannière de consentement cookies RGPD -->
<div id="cookie-banner" 
     x-data="cookieConsent()" 
     x-show="!hasConsent" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform translate-y-full"
     x-transition:enter-end="opacity-100 transform translate-y-0"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="opacity-100 transform translate-y-0"
     x-transition:leave-end="opacity-0 transform translate-y-full"
     class="cookie-banner position-fixed bottom-0 start-0 end-0 z-index-1050 bg-white shadow-lg border-top"
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
                    <button @click="showModal = true" 
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
    
    <div @click.away="showModal = false" 
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
            <button @click="showModal = false" 
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
                <button @click="rejectOptional(); showModal = false" 
                        class="btn btn-outline-secondary flex-fill">
                    Refuser tout
                </button>
                <button @click="savePreferences(); showModal = false" 
                        class="btn btn-primary flex-fill">
                    Enregistrer mes préférences
                </button>
                <button @click="acceptAll(); showModal = false" 
                        class="btn btn-success flex-fill">
                    Tout accepter
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Script Alpine.js pour la gestion des cookies -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
function cookieConsent() {
    return {
        hasConsent: false,
        showModal: false,
        preferences: {
            essential: true,  // Toujours vrai
            analytics: false,
            marketing: false,
            personalization: false
        },

        init() {
            // Configurer le token CSRF pour les requêtes AJAX
            axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Charger le consentement depuis l'API
            this.loadConsentFromAPI();
        },

        async loadConsentFromAPI() {
            try {
                const response = await axios.get('/api/cookies/consent');
                if (response.data.has_consent) {
                    this.hasConsent = true;
                    this.preferences = response.data.preferences;
                } else {
                    this.hasConsent = false;
                }
            } catch (error) {
                console.warn('Erreur lors du chargement des préférences cookies:', error);
                // Fallback sur les cookies locaux
                this.hasConsent = this.getCookie('farmshop_cookie_consent') !== null;
                if (this.hasConsent) {
                    this.loadPreferences();
                }
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
            this.saveConsent();
        },

        saveConsent() {
            // Déterminer le type de consentement
            let consentType = 'custom';
            if (this.preferences.analytics && this.preferences.marketing && this.preferences.personalization) {
                consentType = 'accept_all';
            } else if (!this.preferences.analytics && !this.preferences.marketing && !this.preferences.personalization) {
                consentType = 'reject_all';
            }

            // Sauvegarder via l'API
            this.saveConsentToAPI(consentType)
                .then(() => {
                    // Sauvegarder aussi localement comme fallback
                    this.setCookie('farmshop_cookie_consent', 'true', 365);
                    this.setCookie('farmshop_cookie_preferences', JSON.stringify(this.preferences), 365);
                    
                    // Appliquer les cookies selon les préférences
                    this.applyCookieSettings();
                    
                    this.hasConsent = true;
                    this.showModal = false;
                    
                    // Notification de succès
                    this.showNotification('Vos préférences ont été enregistrées avec succès !', 'success');
                })
                .catch((error) => {
                    console.error('Erreur lors de la sauvegarde:', error);
                    // Fallback sur le stockage local
                    this.setCookie('farmshop_cookie_consent', 'true', 365);
                    this.setCookie('farmshop_cookie_preferences', JSON.stringify(this.preferences), 365);
                    this.applyCookieSettings();
                    this.hasConsent = true;
                    this.showModal = false;
                    this.showNotification('Préférences sauvegardées localement', 'warning');
                });
        },

        async saveConsentToAPI(consentType) {
            try {
                const response = await axios.post('/api/cookies/consent', {
                    consents: this.preferences,
                    consent_type: consentType
                });
                return response.data;
            } catch (error) {
                throw error;
            }
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

        applyCookieSettings() {
            // Google Analytics
            if (this.preferences.analytics) {
                this.loadGoogleAnalytics();
            } else {
                this.removeGoogleAnalytics();
            }

            // Facebook Pixel
            if (this.preferences.marketing) {
                this.loadFacebookPixel();
            } else {
                this.removeFacebookPixel();
            }

            // Autres scripts de personnalisation
            if (this.preferences.personalization) {
                this.loadPersonalizationScripts();
            }
        },

        loadGoogleAnalytics() {
            if (!window.gtag) {
                // Charger Google Analytics seulement si accepté
                const script = document.createElement('script');
                script.async = true;
                script.src = 'https://www.googletagmanager.com/gtag/js?id=GA_MEASUREMENT_ID';
                document.head.appendChild(script);

                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());
                gtag('config', 'GA_MEASUREMENT_ID');
                window.gtag = gtag;
            }
        },

        removeGoogleAnalytics() {
            // Supprimer Google Analytics si refusé
            if (window.gtag) {
                gtag('config', 'GA_MEASUREMENT_ID', {
                    'anonymize_ip': true,
                    'storage': 'none'
                });
            }
        },

        loadFacebookPixel() {
            if (!window.fbq) {
                // Charger Facebook Pixel seulement si accepté
                !function(f,b,e,v,n,t,s)
                {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
                n.callMethod.apply(n,arguments):n.queue.push(arguments)};
                if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
                n.queue=[];t=b.createElement(e);t.async=!0;
                t.src=v;s=b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t,s)}(window, document,'script',
                'https://connect.facebook.net/en_US/fbevents.js');
                fbq('init', 'YOUR_PIXEL_ID');
                fbq('track', 'PageView');
            }
        },

        removeFacebookPixel() {
            // Désactiver Facebook Pixel si refusé
            if (window.fbq) {
                fbq('consent', 'revoke');
            }
        },

        loadPersonalizationScripts() {
            // Charger les scripts de personnalisation
            console.log('Scripts de personnalisation chargés');
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

        deleteCookie(name) {
            this.setCookie(name, '', -1);
        },

        // Méthode pour réinitialiser les préférences
        async resetConsent() {
            try {
                await axios.delete('/api/cookies/consent');
                this.deleteCookie('farmshop_cookie_consent');
                this.deleteCookie('farmshop_cookie_preferences');
            } catch (error) {
                // Fallback sur la suppression locale seulement
                this.deleteCookie('farmshop_cookie_consent');
                this.deleteCookie('farmshop_cookie_preferences');
            }
            
            this.hasConsent = false;
            this.preferences = {
                essential: true,
                analytics: false,
                marketing: false,
                personalization: false
            };
            
            this.showNotification('Préférences réinitialisées', 'info');
        },

        // Notification système
        showNotification(message, type = 'info') {
            // Créer une notification toast
            const toast = document.createElement('div');
            toast.className = `alert alert-${type} position-fixed top-0 end-0 m-3`;
            toast.style.zIndex = '1070';
            toast.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 'info-circle'} me-2"></i>
                    ${message}
                    <button type="button" class="btn-close ms-auto" onclick="this.parentElement.parentElement.remove()"></button>
                </div>
            `;
            document.body.appendChild(toast);
            
            // Supprimer automatiquement après 5 secondes
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.remove();
                }
            }, 5000);
        }
    }
}

// Fonction globale pour rouvrir les préférences depuis le footer
window.reopenCookiePreferences = function() {
    // Rechercher le composant Alpine et ouvrir le modal
    const cookieBanner = document.querySelector('[x-data*="cookieConsent"]');
    if (cookieBanner && cookieBanner._x_dataStack && cookieBanner._x_dataStack[0]) {
        cookieBanner._x_dataStack[0].showModal = true;
    }
}
</script>

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
