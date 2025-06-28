# Archive - Composants cookies

Ce dossier contient les différentes tentatives d'implémentation de la bannière de consentement cookies RGPD.

## Fichiers archivés

- `cookie-banner.blade.php` - Version initiale Alpine.js
- `cookie-banner-simple.blade.php` - Version simplifiée pour debug
- `cookie-banner-fixed.blade.php` - Version corrigée avec transitions
- `cookie-banner-debug.blade.php` - Version avec debug étendu
- `cookie-banner-dom-fix.blade.php` - Version avec manipulation DOM directe
- `cookie-banner-pure-js.blade.php` - Version JavaScript pur
- `alpine-test.blade.php` - Tests de fonctionnement Alpine.js

## Problème identifié

Toutes les versions souffrent du même problème : le modal ne se ferme pas correctement après interaction utilisateur, malgré que l'état JavaScript indique le contraire.

## Pour réactiver plus tard

1. Choisir une des versions dans ce dossier
2. La copier dans le dossier parent `components/`
3. Décommenter l'inclusion dans `welcome.blade.php`
4. Décommenter les routes API dans `routes/api.php`
5. Tester avec une approche technique différente

Le backend (modèle, contrôleur, migration) reste fonctionnel et prêt à être utilisé.
