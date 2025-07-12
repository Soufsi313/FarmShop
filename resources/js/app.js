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
        show() {
            const banner = document.getElementById('cookie-banner');
            if (banner && !this.hasConsent()) {
                banner.classList.remove('hidden');
            }
        },
        
        accept() {
            localStorage.setItem('farmshop_cookie_consent', 'accepted');
            document.getElementById('cookie-banner').classList.add('hidden');
            // Activer les cookies analytiques/marketing ici
            this.enableAnalytics();
        },
        
        decline() {
            localStorage.setItem('farmshop_cookie_consent', 'declined');
            document.getElementById('cookie-banner').classList.add('hidden');
        },
        
        hasConsent() {
            return localStorage.getItem('farmshop_cookie_consent') !== null;
        },
        
        enableAnalytics() {
            // Intégration future avec Google Analytics ou autres
            console.log('Analytics enabled');
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
