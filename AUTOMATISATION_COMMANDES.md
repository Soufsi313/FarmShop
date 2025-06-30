# Automatisation des Statuts de Commandes

## Vue d'ensemble

Le système automatise les changements de statuts des commandes selon le cycle de vie suivant :

1. **En attente** → **Confirmée** (lors de la création)
2. **Confirmée** → **En préparation** (après 1min30)
3. **En préparation** → **Expédiée** (après 1min30 supplémentaire)

## Commande Artisan

### `php artisan orders:update-status`

Cette commande vérifie automatiquement les commandes et met à jour leur statut selon les règles définies.

**Utilisation manuelle :**
```bash
php artisan orders:update-status
```

**Automatisation :**
La commande est automatiquement exécutée toutes les minutes via le scheduler Laravel.

## Règles d'Annulation

- Une commande peut être annulée tant qu'elle n'est **pas expédiée**
- Une fois expédiée, l'annulation n'est plus possible
- L'annulation avant expédition déclenche un remboursement automatique

## Page "Mes Commandes"

### Fonctionnalités

1. **Liste des commandes** avec filtres par :
   - Statut
   - Date de début
   - Date de fin

2. **Actions disponibles** :
   - Voir les détails
   - Télécharger la facture PDF (si confirmée)
   - Annuler la commande (si pas encore expédiée)

3. **Navigation** :
   - Accessible via le menu principal
   - Accessible via le menu déroulant utilisateur

### Routes

```php
GET  /mes-commandes                    // Liste des commandes
GET  /mes-commandes/{order}            // Détails d'une commande
GET  /mes-commandes/{order}/facture    // Télécharger la facture PDF
POST /mes-commandes/{order}/annuler    // Annuler une commande
```

## Fichiers Modifiés

### Backend
- `app/Console/Commands/UpdateOrderStatus.php` - Commande d'automatisation
- `app/Console/Kernel.php` - Planification des tâches
- `app/Models/Order.php` - Méthodes `canBeCancelled()` et `isProcessing()`
- `app/Http/Controllers/OrderController.php` - Méthodes existantes

### Frontend
- `resources/views/orders/index.blade.php` - Page "Mes commandes" complète
- `resources/views/orders/invoice.blade.php` - Template facture PDF (existant)
- `resources/views/navigation-menu.blade.php` - Ajout liens navigation

### Configuration
- `routes/web.php` - Routes des commandes utilisateur (existantes)

## Statuts des Commandes

| Statut | Description | Actions possibles |
|--------|-------------|-------------------|
| `pending` | En attente | Annulation |
| `confirmed` | Confirmée | Annulation, Facture |
| `preparation` | En préparation | Annulation, Facture |
| `shipped` | Expédiée | Facture uniquement |
| `delivered` | Livrée | Facture uniquement |
| `cancelled` | Annulée | Aucune |

## Logs

Les logs de l'automatisation sont stockés dans :
- `storage/logs/order-status-automation.log`

## Développement Local

Pour tester l'automatisation en local :

1. Créer une commande de test
2. Exécuter manuellement : `php artisan orders:update-status`
3. Vérifier les changements de statut

## Production

En production, s'assurer que le cron job Laravel est configuré :

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

## Notes Importantes

- L'automatisation utilise des délais de 90 secondes (1min30)
- Les commandes annulées avant expédition sont automatiquement remboursées
- Les factures PDF sont générées avec DomPDF
- La navigation est responsive et optimisée mobile
