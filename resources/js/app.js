import './bootstrap';
import Alpine from 'alpinejs'

// Configuration Alpine.js
window.Alpine = Alpine

// Démarrage d'Alpine.js
Alpine.start()

// Fonctions globales pour FarmShop
window.FarmShop = {
    // Gestion des cookies de consentement
    cookieConsent: {
        async show() {
            const banner = document.getElementById('cookie-banner');
            if (banner) {
                try {
                    // Vérifier l'état du consentement via l'API
                    const response = await this.checkConsentStatus();
                    if (response.consent_required) {
                        banner.classList.remove('hidden');
                    }
                } catch (error) {
                    console.error('Erreur lors de la vérification du consentement:', error);
                    // Afficher le banner par défaut en cas d'erreur
                    banner.classList.remove('hidden');
                }
            }
        },
        
        async accept() {
            try {
                const response = await fetch('/api/cookies/accept-all', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    console.log('Cookies acceptés:', data);
                    document.getElementById('cookie-banner').classList.add('hidden');
                    this.enableAnalytics();
                    this.showNotification('Préférences de cookies sauvegardées', 'success');
                } else {
                    throw new Error('Erreur lors de l\'acceptation des cookies');
                }
            } catch (error) {
                console.error('Erreur:', error);
                this.showNotification('Erreur lors de la sauvegarde', 'error');
            }
        },
        
        async decline() {
            try {
                const response = await fetch('/api/cookies/reject-all', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    console.log('Cookies rejetés:', data);
                    document.getElementById('cookie-banner').classList.add('hidden');
                    this.showNotification('Préférences de cookies sauvegardées', 'success');
                } else {
                    throw new Error('Erreur lors du rejet des cookies');
                }
            } catch (error) {
                console.error('Erreur:', error);
                this.showNotification('Erreur lors de la sauvegarde', 'error');
            }
        },
        
        async checkConsentStatus() {
            const response = await fetch('/api/cookies/preferences', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            return await response.json();
        },
        
        async updatePreferences(preferences) {
            try {
                const response = await fetch('/api/cookies/preferences', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify(preferences)
                });
                
                if (response.ok) {
                    const data = await response.json();
                    console.log('Préférences mises à jour:', data);
                    this.showNotification('Préférences sauvegardées avec succès', 'success');
                    return data;
                } else {
                    throw new Error('Erreur lors de la mise à jour');
                }
            } catch (error) {
                console.error('Erreur:', error);
                this.showNotification('Erreur lors de la sauvegarde', 'error');
                throw error;
            }
        },
        
        showCookieSettings() {
            // Ouvrir la modale de préférences détaillées
            if (window.cookieSettingsModal) {
                window.cookieSettingsModal.show();
            }
        },
        
        enableAnalytics() {
            // Activer Google Analytics ou autres trackers si autorisés
            console.log('Analytics enabled - ready for GA4 integration');
        },
        
        showNotification(message, type = 'info') {
            if (window.FarmShop && window.FarmShop.notification) {
                window.FarmShop.notification.show(message, type);
            } else {
                console.log(`${type.toUpperCase()}: ${message}`);
            }
        }
    },
    
    // Utilitaires AJAX
    ajax: {
        async request(url, options = {}) {
            const defaultOptions = {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                }
            };
            
            const config = { ...defaultOptions, ...options };
            
            try {
                const response = await fetch(url, config);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return await response.json();
            } catch (error) {
                console.error('AJAX request failed:', error);
                throw error;
            }
        },
        
        async get(url) {
            return this.request(url);
        },
        
        async post(url, data) {
            return this.request(url, {
                method: 'POST',
                body: JSON.stringify(data)
            });
        }
    },
    
    // Utilitaires UI
    ui: {
        showNotification(message, type = 'info') {
            // Création d'une notification toast
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
                type === 'success' ? 'bg-green-500' : 
                type === 'error' ? 'bg-red-500' : 
                type === 'warning' ? 'bg-yellow-500' : 'bg-blue-500'
            } text-white`;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 5000);
        },
        
        scrollToTop() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }
};

// Initialisation au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    // Afficher le banner de cookies si nécessaire
    FarmShop.cookieConsent.show();
    
    // Smooth scroll pour les ancres
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});
