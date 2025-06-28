# FarmShop - Site E-commerce Laravel

## 🎯 Vue d'ensemble

Site e-commerce moderne développé avec Laravel, comprenant :
- **Page d'accueil professionnelle** avec design responsive
- **Navigation moderne** avec menu déroulant
- **Footer complet** avec liens utiles
- **Base de données synchronisée** avec toutes les tables nécessaires
- ~~**Bannière de consentement cookies RGPD**~~ *(en attente - voir TODO.md)*

## 🚀 Démarrage rapide

```bash
# Installer les dépendances
composer install
npm install

# Configurer la base de données
cp .env.example .env
php artisan key:generate
php artisan migrate

# Démarrer le serveur
php artisan serve
```

Le site sera accessible sur : http://127.0.0.1:8000

## 📂 Structure des composants

### Pages principales
- `resources/views/welcome.blade.php` - Page d'accueil avec design moderne

### Composants
- `resources/views/components/navigation.blade.php` - Navigation principale
- `resources/views/components/footer.blade.php` - Pied de page
- `resources/views/components/cookie-banner-fixed.blade.php` - Bannière cookies RGPD

### Assets
- `public/css/custom.css` - Styles personnalisés
- `public/js/` - Scripts JavaScript

## 🍪 ~~Gestion des cookies RGPD~~ (EN ATTENTE)

> **Note:** Cette fonctionnalité a été temporairement désactivée en raison de problèmes techniques avec le modal qui ne se ferme pas correctement. Le backend est prêt, seule l'interface frontend pose problème.
>
> **Fichiers archivés:** `resources/views/components/archive/`  
> **Statut:** À reprendre avec une approche technique différente  
> **Impact:** Aucun sur le reste du site qui fonctionne parfaitement

## 🎨 Design et UI

### Technologies utilisées
- **Bootstrap 5** : Framework CSS responsive
- **Font Awesome** : Icônes modernes
- **Alpine.js** : Interactivité JavaScript légère
- **CSS personnalisé** : Animations et style unique

### Fonctionnalités UI
- Design responsive (mobile-first)
- Animations fluides
- Interface moderne et professionnelle
- Accessibilité optimisée

## 🛠️ Tests et debug

### Bouton de debug cookies
- Visible en haut à droite de la page
- Permet de réinitialiser les préférences cookies
- Utile pour tester la bannière

### Validation automatique
- Vérification de la base de données
- Contrôle des fichiers de vues
- Test des routes API
- Validation des assets

## 📝 Notes importantes

### Conformité RGPD
- ✅ Consentement explicite requis
- ✅ Choix granulaire par type de cookie
- ✅ Possibilité de modification des préférences
- ✅ Documentation des finalités

### Performance
- Chargement optimisé des scripts
- CSS minifié et optimisé
- Images responsives
- Cache browser activé

### Sécurité
- Protection CSRF activée
- Cookies sécurisés (SameSite)
- Validation des données
- Échappement automatique des variables

## 🔧 Personnalisation

### Modifier les couleurs
Éditez le fichier `public/css/custom.css` pour personnaliser :
- Couleurs principales
- Animations
- Styles de composants

### Ajouter des types de cookies
1. Modifier le composant `cookie-banner-fixed.blade.php`
2. Mettre à jour le modèle `CookieConsent.php`
3. Adapter la migration si nécessaire

### Personnaliser les textes
Les textes de la bannière cookies sont dans `cookie-banner-fixed.blade.php`
et peuvent être facilement modifiés pour s'adapter à votre contexte.

---

**Développé pour FarmShop** - Site e-commerce moderne avec conformité RGPD 🌱
