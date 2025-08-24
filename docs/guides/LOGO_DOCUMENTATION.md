# Logo FarmShop - Documentation

## 🎨 Concept Choisi

Le logo FarmShop utilise le **Concept 4 : Minimaliste & Moderne** qui symbolise parfaitement un marché comme lieu de rencontre.

### Symbolisme
- 🌄 **Collines** : Représentent les terroirs et producteurs locaux
- ☀️ **Soleil** : Évoque la chaleur humaine du marché  
- 🌾 **Épis de blé** : Symbolisent les produits frais et naturels
- 🛒 **Icône orange** : Représente le shopping moderne
- 🔵 **Cercle** : Lieu de rencontre et communauté

## 🚀 Utilisation dans Laravel

### Composant Blade

Le logo est disponible via le composant `<x-logo>` :

```blade
<!-- Utilisation basique -->
<x-logo />

<!-- Avec paramètres personnalisés -->
<x-logo size="lg" linkTo="/dashboard" :showText="false" />
```

### Paramètres disponibles

| Paramètre | Valeurs | Description |
|-----------|---------|-------------|
| `size` | `sm`, `md`, `lg`, `xl` | Taille du logo |
| `linkTo` | URL | Lien de destination (défaut: `/`) |
| `showText` | `true`/`false` | Afficher/masquer le texte |

### Tailles disponibles

- **sm** : 32x32px - Pour les favicons, boutons
- **md** : 64x64px - Pour la navigation standard  
- **lg** : 96x96px - Pour les headers importants
- **xl** : 128x128px - Pour les pages d'accueil

## 🌍 Support Multilingue

Le logo s'adapte automatiquement à la langue active :

| Langue | Sous-titre |
|--------|------------|
| 🇫🇷 Français | "Marché Bio" |
| 🇬🇧 Anglais | "Organic Market" |
| 🇳🇱 Néerlandais | "Bio Markt" |

### Configuration

Les traductions sont définies dans :
- `resources/lang/fr/app.php`
- `resources/lang/en/app.php` 
- `resources/lang/nl/app.php`

```php
'logo' => array (
    'main_text' => 'FarmShop',
    'subtitle' => 'Marché Bio',
    'alt' => 'FarmShop - Votre marché agricole en ligne',
),
```

## 🎨 Couleurs du thème

```css
--farm-green-500: #22c55e   /* Vert principal */
--farm-orange: #ea580c      /* Orange e-commerce */
--farm-yellow: #eab308      /* Jaune soleil/blé */
```

## 📁 Structure des fichiers

```
resources/
├── views/
│   └── components/
│       └── logo.blade.php           # Composant principal
├── lang/
│   ├── fr/app.php                   # Traductions FR
│   ├── en/app.php                   # Traductions EN
│   └── nl/app.php                   # Traductions NL
└── layouts/
    └── app.blade.php               # Layout principal (intégré)
```

## 🔧 Intégration dans les vues

### Header/Navigation
```blade
<!-- Dans resources/views/layouts/app.blade.php -->
<div class="flex-shrink-0">
    <x-logo size="md" linkTo="/" />
</div>
```

### Page d'accueil
```blade
<!-- Hero section -->
<div class="text-center">
    <x-logo size="xl" :showText="true" />
    <h1>{{ __('welcome.hero_title') }}</h1>
</div>
```

### Footer
```blade
<!-- Footer -->
<div class="flex items-center justify-center">
    <x-logo size="sm" :showText="false" />
</div>
```

## 🖼️ Favicon

Pour générer les favicons :

1. Ouvrir `favicon_generator.html` 
2. Capturer la version 128x128
3. Utiliser [favicon.io](https://favicon.io) ou [realfavicongenerator.net](https://realfavicongenerator.net)
4. Placer les fichiers dans `public/`

### Code favicon à ajouter

```html
<!-- Dans le <head> -->
<link rel="icon" type="image/x-icon" href="/favicon.ico">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<link rel="manifest" href="/site.webmanifest">
```

## 🎯 Exemples d'utilisation

### Navigation responsive
```blade
<!-- Desktop -->
<div class="hidden md:block">
    <x-logo size="md" />
</div>

<!-- Mobile -->
<div class="block md:hidden">
    <x-logo size="sm" :showText="false" />
</div>
```

### Différents contextes
```blade
<!-- Page de connexion -->
<x-logo size="lg" linkTo="/" />

<!-- Email template -->
<x-logo size="md" linkTo="{{ config('app.url') }}" />

<!-- Admin panel -->
<x-logo size="sm" linkTo="/admin" />
```

## 🔄 Changement de langue

Le logo se met à jour automatiquement selon la langue active :

```blade
<!-- Le sous-titre change selon app()->getLocale() -->
<x-logo size="lg" />

<!-- FR: "Marché Bio" -->
<!-- EN: "Organic Market" -->  
<!-- NL: "Bio Markt" -->
```

## 🎨 Personnalisation

Pour modifier les couleurs, mettre à jour la configuration Tailwind :

```javascript
// Dans app.blade.php
tailwind.config = {
    theme: {
        extend: {
            colors: {
                'farm-green': {
                    500: '#22c55e',  // Vert principal
                },
                'farm-orange': '#ea580c',    // Orange
                'farm-yellow': '#eab308'     // Jaune
            }
        }
    }
}
```

## 📱 Tests

Fichiers de test disponibles :
- `logo_test_multilingue.html` : Test des 3 langues
- `logo_farmshop_concept4_agrandi.html` : Toutes les tailles
- `favicon_generator.html` : Génération favicons

## ✅ Checklist d'intégration

- [x] Composant `<x-logo>` créé
- [x] Support multilingue (FR, EN, NL)
- [x] Intégration dans `app.blade.php`
- [x] Configuration Tailwind mise à jour
- [x] Documentation complète
- [ ] Favicons générés et intégrés
- [ ] Tests sur tous les navigateurs
- [ ] Validation responsive

---

*Logo créé avec Tailwind CSS - Responsive, accessible et multilingue* 🚜🛒
