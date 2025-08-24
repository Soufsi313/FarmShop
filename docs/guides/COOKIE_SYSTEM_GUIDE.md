# Système de Gestion des Cookies 🍪

## Vue d'ensemble

Le système de gestion des cookies de FarmShop offre une traçabilité complète et une collecte de données de navigation conforme au RGPD. Il permet aux visiteurs et utilisateurs connectés de gérer finement leurs préférences de cookies.

## Architecture du Système

### 1. Modèle Cookie (`app/Models/Cookie.php`)

Le modèle principal qui stocke toutes les informations de consentement :

**Champs principaux :**
- `user_id` : ID utilisateur (null pour visiteurs)
- `session_id` : ID de session pour visiteurs non connectés
- `ip_address` : Adresse IP du visiteur
- `user_agent` : Informations du navigateur

**Types de cookies :**
- `necessary` : Toujours accepté (session, panier, auth)
- `analytics` : Google Analytics, statistiques (optionnel)
- `marketing` : Publicités, remarketing (optionnel)
- `preferences` : Paramètres utilisateur (optionnel)
- `social_media` : Partage réseaux sociaux (optionnel)

**Statuts possibles :**
- `pending` : En attente de consentement
- `accepted` : Tous les cookies acceptés
- `rejected` : Seuls les cookies nécessaires
- `partial` : Consentement partiel

### 2. Contrôleur Cookie (`app/Http/Controllers/CookieController.php`)

**API publiques :**
- `GET /api/cookies/preferences` - Obtenir les préférences
- `POST /api/cookies/preferences` - Mettre à jour les préférences
- `POST /api/cookies/accept-all` - Accepter tous les cookies
- `POST /api/cookies/reject-all` - Rejeter les cookies optionnels
- `GET /api/cookies/consent/{type}` - Vérifier un type de cookie

**API utilisateurs connectés :**
- `GET /api/cookies/history` - Historique des consentements

**API admin :**
- `GET /api/admin/cookies` - Liste tous les cookies
- `GET /api/admin/cookies/stats` - Statistiques globales
- `GET /api/admin/cookies/{cookie}` - Détails d'un cookie
- `DELETE /api/admin/cookies/{cookie}` - Supprimer un cookie

### 3. Service CookieConsentService (`app/Services/CookieConsentService.php`)

Service centralisé pour gérer les cookies applicatifs :

**Méthodes principales :**
```php
// Vérifier si un type de cookie est autorisé
CookieConsentService::isAllowed('analytics');

// Définir un cookie si autorisé
CookieConsentService::setCookie('user_pref', 'dark_mode', 60, 'preferences');

// Obtenir le statut du consentement
CookieConsentService::getConsentStatus(); // 'pending', 'accepted', etc.

// Générer les scripts autorisés
CookieConsentService::generateScriptTags(); // Google Analytics, Facebook Pixel, etc.
```

### 4. Middleware CookieConsentMiddleware (`app/Http/Middleware/CookieConsentMiddleware.php`)

Middleware qui :
- Crée automatiquement un enregistrement de cookie pour chaque visiteur
- Ajoute des headers HTTP avec le statut du consentement
- Évite les routes d'API pour ne pas impacter les performances

### 5. Politique d'autorisation (`app/Policies/CookiePolicy.php`)

- **Admins** : Accès complet (voir tous les cookies, statistiques, suppression)
- **Utilisateurs** : Peuvent voir/modifier leurs propres cookies
- **Visiteurs** : Peuvent gérer leurs préférences via les API publiques

## Utilisation Pratique

### 1. Bannière de Consentement

```html
<!-- Exemple de bannière de cookies -->
<div class="cookie-banner" id="cookieBanner" style="display: none;">
    <div class="banner-content">
        <h3>🍪 Gestion des Cookies</h3>
        <p>Nous utilisons des cookies pour améliorer votre expérience. Vous pouvez gérer vos préférences.</p>
        
        <div class="banner-actions">
            <button onclick="acceptAllCookies()" class="btn-accept">Accepter tout</button>
            <button onclick="rejectAllCookies()" class="btn-reject">Refuser optionnels</button>
            <button onclick="showPreferences()" class="btn-settings">Gérer mes préférences</button>
        </div>
    </div>
</div>

<script>
// Vérifier si le consentement est requis
fetch('/api/cookies/preferences')
    .then(response => response.json())
    .then(data => {
        if (data.data.consent_required) {
            document.getElementById('cookieBanner').style.display = 'block';
        }
    });

function acceptAllCookies() {
    fetch('/api/cookies/accept-all', { method: 'POST' })
        .then(() => location.reload());
}

function rejectAllCookies() {
    fetch('/api/cookies/reject-all', { method: 'POST' })
        .then(() => document.getElementById('cookieBanner').style.display = 'none');
}
</script>
```

### 2. Modal de Préférences

```html
<!-- Modal de gestion des préférences -->
<div class="cookie-preferences-modal" id="preferencesModal">
    <div class="modal-content">
        <h3>Gestion des Cookies</h3>
        
        <div class="cookie-category">
            <h4>🔒 Cookies Nécessaires</h4>
            <p>Essentiels au fonctionnement du site</p>
            <input type="checkbox" checked disabled> Toujours activés
        </div>
        
        <div class="cookie-category">
            <h4>📊 Cookies Analytiques</h4>
            <p>Nous aident à comprendre l'utilisation du site</p>
            <input type="checkbox" id="analytics" name="analytics">
        </div>
        
        <div class="cookie-category">
            <h4>🎯 Cookies Marketing</h4>
            <p>Personnalisent les publicités</p>
            <input type="checkbox" id="marketing" name="marketing">
        </div>
        
        <div class="cookie-category">
            <h4>⚙️ Cookies de Préférences</h4>
            <p>Mémorisent vos choix personnels</p>
            <input type="checkbox" id="preferences" name="preferences">
        </div>
        
        <div class="cookie-category">
            <h4>📱 Cookies Réseaux Sociaux</h4>
            <p>Permettent le partage social</p>
            <input type="checkbox" id="social_media" name="social_media">
        </div>
        
        <div class="modal-actions">
            <button onclick="savePreferences()">Enregistrer mes choix</button>
            <button onclick="closeModal()">Annuler</button>
        </div>
    </div>
</div>

<script>
function savePreferences() {
    const preferences = {
        analytics: document.getElementById('analytics').checked,
        marketing: document.getElementById('marketing').checked,
        preferences: document.getElementById('preferences').checked,
        social_media: document.getElementById('social_media').checked
    };
    
    fetch('/api/cookies/preferences', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(preferences)
    }).then(() => {
        closeModal();
        location.reload();
    });
}
</script>
```

### 3. Chargement Conditionnel de Scripts

```javascript
// Charger Google Analytics seulement si autorisé
fetch('/api/cookies/consent/analytics')
    .then(response => response.json())
    .then(data => {
        if (data.data.is_accepted) {
            // Charger Google Analytics
            const script = document.createElement('script');
            script.src = 'https://www.googletagmanager.com/gtag/js?id=GA_ID';
            document.head.appendChild(script);
        }
    });

// Ou utiliser le service pour générer tous les scripts
CookieConsentService::generateScriptTags();
```

### 4. Dashboard Admin

Les admins peuvent voir :
- **Statistiques globales** : Taux d'acceptation, répartition par statut
- **Liste des cookies** : Tous les enregistrements avec filtres
- **Évolution temporelle** : Graphiques des consentements
- **Répartition par type** : Utilisateurs vs visiteurs

```php
// Dans le contrôleur admin
Route::get('/admin/cookies/stats', [CookieController::class, 'getGlobalStats']);

// Réponse exemple :
{
    "success": true,
    "data": {
        "global_stats": {
            "total": 1250,
            "accepted": 892,
            "rejected": 201,
            "partial": 157,
            "acceptance_rate": 71.36,
            "rejection_rate": 16.08
        },
        "cookie_type_stats": {
            "necessary": 1250,
            "analytics": 1049,
            "marketing": 892,
            "preferences": 950,
            "social_media": 720
        }
    }
}
```

## Conformité RGPD

✅ **Consentement explicite** : L'utilisateur doit choisir activement
✅ **Granularité** : Choix par type de cookie
✅ **Révocable** : Possibilité de changer d'avis
✅ **Traçabilité** : Historique complet des consentements
✅ **Transparence** : Descriptions claires de chaque type
✅ **Droit à l'oubli** : Suppression possible des données

## Migration et Maintenance

### Nettoyage automatique
```php
// Commande artisan pour nettoyer les anciens cookies
php artisan cookies:cleanup

// Ou via le service
CookieConsentService::cleanupExpiredCookies();
```

### Migration visiteur → utilisateur
```php
// Lors de la connexion, migrer les cookies du visiteur
CookieConsentService::migrateGuestCookies($userId, $request);
```

## Avantages du Système

🎯 **Conformité légale** : Respect du RGPD et directives cookies
📊 **Traçabilité complète** : Chaque action est enregistrée
🔧 **Flexibilité** : Gestion granulaire par type de cookie
📈 **Statistiques** : Données précieuses sur les préférences utilisateurs
🚀 **Performance** : Chargement conditionnel des scripts tiers
🛡️ **Sécurité** : Validation et autorisation appropriées

Le système est maintenant prêt pour la production avec une gestion complète des cookies conforme aux réglementations en vigueur !
