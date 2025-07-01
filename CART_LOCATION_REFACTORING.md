# Refactorisation du Système de Panier de Location FarmShop

## Vue d'ensemble

Le système de panier de location a été refactorisé pour séparer la logique du panier global et des items individuels, suivant le même pattern que le système de commandes (Order/OrderItem).

## Architecture Avant/Après

### Avant (Ancien système)
- **CartLocation** : Représentait directement un produit dans le panier
- Structure plate, un enregistrement par produit
- Difficile de gérer un panier comme entité globale

### Après (Nouveau système)
- **CartLocation** : Panier global de location d'un utilisateur
- **CartItemLocation** : Lignes individuelles de produits dans ce panier
- Structure hiérarchique similaire à Order/OrderItem

## Modèles

### CartLocation (Panier Global)
**Champs:**
- `user_id` : Propriétaire du panier
- `status` : Statut du panier (draft, pending, confirmed, active, completed, cancelled)
- `total_amount` : Total des prix de location
- `total_deposit` : Total des cautions
- `notes` : Notes optionnelles

**Méthodes principales:**
- `getActiveCartForUser($userId)` : Obtenir le panier actif
- `addItem()` : Ajouter un produit au panier
- `removeItem()` : Supprimer un item
- `clear()` : Vider le panier
- `validate()` : Valider le panier
- `submit()` : Soumettre (draft → pending)
- `confirm()` : Confirmer (pending → confirmed)
- `start()` : Démarrer (confirmed → active)
- `complete()` : Terminer (active → completed)
- `cancel()` : Annuler

### CartItemLocation (Ligne de Produit)
**Champs:**
- `cart_location_id` : Référence au panier parent
- `product_id` : Produit loué
- `product_name`, `product_category`, etc. : Snapshot du produit
- `quantity` : Quantité
- `rental_duration_days` : Durée de location
- `rental_start_date`, `rental_end_date` : Dates de location
- `unit_price_per_day` : Prix unitaire par jour
- `total_price` : Prix total (quantité × prix_unitaire × durée)
- `deposit_amount` : Montant de la caution
- `status` : Statut de l'item

**Méthodes principales:**
- `updateQuantity()` : Modifier la quantité
- `updateDuration()` : Modifier la durée
- `extendRental()` : Prolonger la location
- `validate()` : Valider cet item
- `confirm()`, `start()`, `returnItem()`, `cancel()` : Transitions de statut

## Workflow des Statuts

### CartLocation (Panier)
1. **draft** : Panier en cours de constitution
2. **pending** : Panier soumis, en attente de validation
3. **confirmed** : Location confirmée par l'admin
4. **active** : Location en cours
5. **completed** : Location terminée
6. **cancelled** : Location annulée

### CartItemLocation (Items)
1. **pending** : Item en attente
2. **confirmed** : Item confirmé
3. **active** : Item en location active
4. **returned** : Item retourné
5. **cancelled** : Item annulé

## Routes

### Panier Global (`/panier-location`)
- `GET /` : Afficher le panier
- `POST /ajouter` : Ajouter un produit
- `DELETE /vider` : Vider le panier
- `POST /valider` : Valider le panier
- `POST /soumettre` : Soumettre le panier

### API (`/panier-location/api`)
- `GET /count` : Nombre d'items
- `GET /total` : Totaux du panier
- `POST /quick-add/{product}` : Ajout rapide

### Items Individuels (`/article-location`)
- `GET /{cartItemLocation}` : Afficher un item
- `PUT /{cartItemLocation}` : Modifier un item
- `DELETE /{cartItemLocation}` : Supprimer un item
- `POST /{cartItemLocation}/prolonger` : Prolonger

## Contrôleur

**CartLocationController** adapté pour :
- Gérer les deux entités (panier et items)
- Maintenir la cohérence des totaux
- Supporter les opérations CRUD sur les items
- Gérer les transitions de statut

## Migration

**Fichiers de migration :**
- `2025_07_01_125810_create_cart_item_locations_table.php`
- `2025_07_01_130853_recreate_cart_locations_system.php`

**Actions de migration :**
1. Suppression de l'ancienne table `cart_locations`
2. Création de la nouvelle `cart_locations` (structure panier global)
3. Création de `cart_item_locations` (lignes de produits)

## Tests

**Scripts de test créés :**
- `test_new_cart_location_system.php` : Test complet du workflow
- `test_cart_location_routes.php` : Test des routes
- `verify_cart_location_migration.php` : Vérification de la migration

## Avantages du Nouveau Système

1. **Séparation des responsabilités** : Panier vs Items
2. **Gestion centralisée** : Un panier par utilisateur
3. **Calculs automatiques** : Totaux synchronisés
4. **Workflow cohérent** : Transitions de statut claires
5. **Extensibilité** : Facilite les futures évolutions
6. **Compatibilité** : API similaire pour le front-end

## Utilisation

```php
// Obtenir le panier actif
$cart = CartLocation::getActiveCartForUser($userId);

// Ajouter un produit
$item = $cart->addItem($productId, $quantity, $duration, $startDate);

// Valider et soumettre
$issues = $cart->validate();
if (empty($issues)) {
    $cart->submit();
}

// Workflow admin
$cart->confirm();
$cart->start();
$cart->complete();
```

## Notes Importantes

- Un utilisateur ne peut avoir qu'un seul panier de location actif (status = 'draft')
- Les totaux sont recalculés automatiquement lors des modifications
- Les snapshots de produits sont conservés dans CartItemLocation
- Les relations parent-enfant garantissent la cohérence des données
- Le système supporte les prolongations et modifications dynamiques

## Prochaines Étapes

- [ ] Adapter les vues front-end pour le nouveau système
- [ ] Intégrer avec le système de checkout de location
- [ ] Ajouter des tests automatisés (PHPUnit)
- [ ] Documentation utilisateur/admin
