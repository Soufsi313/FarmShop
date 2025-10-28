# Tests Unitaires - Authentication

## 📋 Description

Tests de validation du système d'authentification du projet FarmShop. Ces tests vérifient les controllers d'authentification, la configuration, les middleware et les mesures de sécurité.

## 🎯 Tests d'authentification

### 1. LoginController (`test_login_controller.php`)
- ✅ Méthodes: showLoginForm, login, logout
- ✅ Validation: email (required, valid), password (required)
- ✅ Auth::attempt() avec remember me
- ✅ Régénération de session après login
- ✅ Redirection intended
- ✅ Synchronisation cookies (auth_status_changed)
- ✅ Messages d'erreur personnalisés
- ✅ Sécurité: CSRF, throttling, session management

### 2. RegisterController (`test_register_controller.php`)
- ✅ Méthodes: showRegistrationForm, register
- ✅ Validation stricte: username, email (unique), password (min 8, confirmed), terms
- ✅ Hash sécurisé du mot de passe (bcrypt)
- ✅ Création utilisateur avec rôle User par défaut
- ✅ Abonnement newsletter optionnel
- ✅ Envoi email de vérification automatique
- ✅ Messages d'erreur personnalisés
- ✅ Exclusion password du withInput pour sécurité

### 3. EmailVerificationController (`test_email_verification_controller.php`)
- ✅ Méthodes: show, verify, resend
- ✅ Vérification via EmailVerificationRequest (URL signée)
- ✅ Marquage email comme vérifié (fulfill)
- ✅ Renvoi d'email avec vérification état
- ✅ Logging complet des événements (user_id, email, timestamp)
- ✅ Vues: verify-email, email-verified
- ✅ Protection contre spam de renvoi
- ✅ Messages utilisateur clairs

### 4. Authentication System (`test_auth_system.php`)
- ✅ Configuration: guards, providers, password policy
- ✅ User Model: Authenticatable, MustVerifyEmail
- ✅ Attributs cachés: password, remember_token
- ✅ Middleware: auth, guest, verified, throttle
- ✅ Politique mots de passe: expiration, throttle
- ✅ Routes d'authentification complètes
- ✅ Contrôle d'accès par rôle (Admin/User)
- ✅ Sécurité maximale

## 🚀 Utilisation

### Exécuter tous les tests Authentication
```bash
php TestUnit/04_Auth/run_all_tests.php
```

### Exécuter un test spécifique
```bash
# Test LoginController
php TestUnit/04_Auth/test_login_controller.php

# Test RegisterController
php TestUnit/04_Auth/test_register_controller.php

# Test EmailVerificationController
php TestUnit/04_Auth/test_email_verification_controller.php

# Test système global
php TestUnit/04_Auth/test_auth_system.php
```

## 📊 Résultats attendus

Chaque test affiche:
- ✅ Tests réussis avec détails
- 🔒 Mesures de sécurité vérifiées
- ❌ Erreurs si problèmes critiques

Le runner affiche:
- Résumé de chaque composant auth
- Nombre de tests réussis/échoués
- Temps d'exécution total

## 🔍 Points vérifiés

### Controllers
- Existence et instanciation
- Méthodes publiques définies
- Validation des données
- Gestion des sessions

### Sécurité
- Hash des mots de passe (bcrypt)
- Protection CSRF
- Rate limiting (throttle)
- Email verification
- Session regeneration
- URL signées
- Validation stricte

### Fonctionnalités
- Login/Logout
- Registration
- Email verification
- Remember me
- Password reset
- Role-based access

### Configuration
- Guards et providers
- Middleware
- Password policy
- User model compliance

## 🛠️ Dépendances

- Laravel 11+
- Auth Controllers
- User Model (Authenticatable, MustVerifyEmail)
- Middleware d'authentification
- Email notifications

## 📝 Notes

- Tests de structure et configuration uniquement
- Pas de tests fonctionnels avec base de données
- Bootstrap Laravel requis
- Vérification de la conformité aux standards Laravel
- Tests rapides et légers

## 🔐 Mesures de sécurité validées

1. **Mots de passe**
   - Hash bcrypt
   - Minimum 8 caractères
   - Confirmation requise

2. **Sessions**
   - Régénération après login
   - Invalidation après logout
   - Token CSRF régénéré

3. **Email**
   - Vérification obligatoire
   - URL signées avec expiration
   - Logging des vérifications

4. **Validation**
   - Unicité email et username
   - Format email valide
   - Acceptation CGU requise

5. **Protection**
   - Rate limiting
   - Throttling
   - CSRF protection
   - Middleware auth/guest/verified
