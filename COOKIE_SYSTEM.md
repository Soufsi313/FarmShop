# Système de Gestion des Cookies - FarmShop

## Vue d'ensemble

Le système de gestion des cookies de FarmShop offre une solution complète pour le consentement RGPD, la traçabilité et la gestion des préférences utilisateur.

## Fonctionnalités

### 🍪 Types de Cookies Gérés

1. **Cookies Nécessaires** (toujours actifs)
   - Session utilisateur
   - Panier d'achat
   - Authentification
   - Sécurité CSRF

2. **Cookies Analytiques**
   - Google Analytics
   - Hotjar
   - Statistiques de visite

3. **Cookies Marketing**
   - Facebook Pixel
   - Google Ads
   - Remarketing

4. **Cookies de Préférences**
   - Langue
   - Région
   - Paramètres d'affichage

5. **Cookies Réseaux Sociaux**
   - Boutons de partage
   - Widgets Facebook/Twitter
   - Connexion sociale

### 📊 Fonctionnalités Principales

- ✅ **Consentement granulaire** par type de cookie
- ✅ **Traçabilité complète** (visiteurs et utilisateurs connectés)
- ✅ **Interface de gestion admin** avec statistiques
- ✅ **API REST complète** pour les intégrations frontend
- ✅ **Middleware automatique** pour la détection
- ✅ **Service centralisé** pour les vérifications
- ✅ **Migration automatique** visiteur → utilisateur connecté

## Structure de la Base de Données

```sql
CREATE TABLE cookies (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NULL,                    -- Utilisateur connecté
    session_id VARCHAR(255) NULL,          -- Session visiteur
    ip_address VARCHAR(45),                 -- Adresse IP
    user_agent TEXT,                        -- User Agent complet
    
    -- Types de cookies
    necessary BOOLEAN DEFAULT TRUE,
    analytics BOOLEAN DEFAULT FALSE,
    marketing BOOLEAN DEFAULT FALSE,
    preferences BOOLEAN DEFAULT FALSE,
    social_media BOOLEAN DEFAULT FALSE,
    
    -- Métadonnées de consentement
    accepted_at TIMESTAMP NULL,
    rejected_at TIMESTAMP NULL,
    last_updated_at TIMESTAMP NULL,
    preferences_details JSON NULL,
    consent_version VARCHAR(10) DEFAULT '1.0',
    status ENUM('pending', 'accepted', 'rejected', 'partial') DEFAULT 'pending',
    
    -- Traçabilité navigation
    page_url VARCHAR(500) NULL,
    referer VARCHAR(500) NULL,
    browser_info JSON NULL,
    
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    -- Index d'optimisation
    INDEX idx_user_status (user_id, status),
    INDEX idx_session_ip (session_id, ip_address),
    INDEX idx_accepted_at (accepted_at),
    INDEX idx_status (status)
);
```

## API Endpoints

### 🌐 Routes Publiques

```http
GET    /api/cookies/preferences              # Obtenir les préférences actuelles
POST   /api/cookies/preferences              # Mettre à jour les préférences
POST   /api/cookies/accept-all               # Accepter tous les cookies
POST   /api/cookies/reject-all               # Rejeter les cookies optionnels
GET    /api/cookies/consent/{cookieType}     # Vérifier un type spécifique
```

### 🔒 Routes Authentifiées

```http
GET    /api/cookies/history                  # Historique utilisateur
```

### 👑 Routes Admin

```http
GET    /api/admin/cookies                    # Liste des cookies
GET    /api/admin/cookies/stats              # Statistiques globales
GET    /api/admin/cookies/{cookie}           # Détails d'un cookie
DELETE /api/admin/cookies/{cookie}           # Supprimer un cookie
```

## Utilisation

### 1. Vérifier le Consentement

```php
use App\Services\CookieConsentService;

// Vérifier si un type de cookie est autorisé
if (CookieConsentService::isAllowed('analytics')) {
    // Charger Google Analytics
}

// Obtenir tous les types autorisés
$allowedTypes = CookieConsentService::getAllowedCookieTypes();
```

### 2. Définir des Cookies Applicatifs

```php
// Définir un cookie seulement si autorisé
CookieConsentService::setCookie('user_pref', 'dark_mode', 60, 'preferences');

// Obtenir un cookie
$preference = CookieConsentService::getCookie('user_pref');
```

### 3. Frontend JavaScript

```javascript
// Configuration automatique disponible
window.CookieConsent.acceptAll();
window.CookieConsent.rejectAll();

// API manuelle
fetch('/api/cookies/preferences', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
    },
    body: JSON.stringify({
        analytics: true,
        marketing: false,
        preferences: true,
        social_media: false
    })
});
```

## Middleware Automatique

Le middleware `CookieConsentMiddleware` :
- Détecte automatiquement les nouveaux visiteurs
- Crée les enregistrements de tracking
- Ajoute des headers de consentement
- Gère la migration visiteur → utilisateur

```http
# Headers ajoutés automatiquement
X-Cookie-Consent-Status: pending|accepted|rejected|partial
X-Cookie-Consent-ID: 123
X-Cookie-Consent-Required: true
```

## Exemples d'Utilisation Frontend

### 1. Bannière de Cookies Simple

```html
<div class="cookie-banner" id="cookieBanner" style="display: none;">
    <div class="cookie-content">
        <h3>🍪 Gestion des Cookies</h3>
        <p>Nous utilisons des cookies pour améliorer votre expérience.</p>
        
        <div class="cookie-actions">
            <button onclick="acceptAllCookies()">Tout Accepter</button>
            <button onclick="rejectAllCookies()">Tout Refuser</button>
            <button onclick="showPreferences()">Gérer</button>
        </div>
    </div>
</div>

<script>
function acceptAllCookies() {
    window.CookieConsent.acceptAll();
}

function rejectAllCookies() {
    window.CookieConsent.rejectAll();
}

// Afficher la bannière si nécessaire
if (window.CookieConsent.showBanner) {
    document.getElementById('cookieBanner').style.display = 'block';
}
</script>
```

### 2. Modal de Préférences Détaillées

```html
<div class="cookie-modal" id="cookieModal">
    <div class="modal-content">
        <h2>Préférences de Cookies</h2>
        
        <div class="cookie-category">
            <label>
                <input type="checkbox" checked disabled> 
                Cookies Nécessaires
            </label>
            <p>Essentiels au fonctionnement du site</p>
        </div>
        
        <div class="cookie-category">
            <label>
                <input type="checkbox" id="analytics"> 
                Cookies Analytiques
            </label>
            <p>Google Analytics, statistiques de visite</p>
        </div>
        
        <div class="cookie-category">
            <label>
                <input type="checkbox" id="marketing"> 
                Cookies Marketing
            </label>
            <p>Publicités personnalisées, remarketing</p>
        </div>
        
        <div class="modal-actions">
            <button onclick="savePreferences()">Sauvegarder</button>
            <button onclick="closeModal()">Annuler</button>
        </div>
    </div>
</div>
```

## Statistiques Admin

Le tableau de bord admin fournit :

```json
{
    "global_stats": {
        "total": 1547,
        "accepted": 892,
        "rejected": 234,
        "partial": 321,
        "pending": 100,
        "acceptance_rate": 57.66,
        "rejection_rate": 15.13
    },
    "cookie_type_stats": {
        "necessary": 1547,
        "analytics": 1045,
        "marketing": 678,
        "preferences": 987,
        "social_media": 543
    },
    "user_breakdown": {
        "authenticated": 1204,
        "guests": 343
    }
}
```

## Conformité RGPD

✅ **Consentement explicite** pour chaque type de cookie  
✅ **Possibilité de retrait** du consentement  
✅ **Traçabilité complète** des choix utilisateur  
✅ **Granularité** par catégorie de cookies  
✅ **Informations claires** sur l'utilisation  
✅ **Durée de conservation** limitée (90 jours)  
✅ **Droit à l'effacement** pour les admins  

## Maintenance

### Nettoyage Automatique

```php
// Nettoyer les cookies expirés (> 90 jours)
use App\Services\CookieConsentService;

$deleted = CookieConsentService::cleanupExpiredCookies();
```

### Migration Visiteur → Utilisateur

```php
// Lors de l'inscription/connexion
CookieConsentService::migrateGuestCookies($userId, $request);
```

## Configuration

Les descriptions et exemples de cookies sont configurables dans :
```php
Cookie::getCookieDescriptions()
```

Le système est entièrement personnalisable selon les besoins spécifiques de l'application.
