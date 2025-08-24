# Contraintes de Rupture de Stock

## Fonctionnalités Implémentées

✅ **Empêche l'ajout de produits en rupture de stock au panier d'achat**
✅ **Empêche l'ajout de produits en rupture de stock au panier de location** 
✅ **Empêche la modification de quantité pour des produits en rupture de stock**
✅ **Middleware de validation automatique**
✅ **Observer pour surveiller les changements de stock**
✅ **API de vérification de disponibilité**
✅ **Tests unitaires complets**

## Validation Côté Modèle

### Cart.php (Achats)
```php
public function addProduct(Product $product, int $quantity = 1): CartItem
{
    // Vérifier que le produit n'est pas en rupture de stock
    if ($product->is_out_of_stock) {
        throw new \Exception("Ce produit est en rupture de stock et ne peut pas être acheté");
    }
    // ... reste de la logique
}
```

### CartLocation.php (Locations)
```php
public function addProduct(Product $product, int $quantity, Carbon $startDate, Carbon $endDate, ?string $notes = null): CartItemLocation
{
    // Vérifier que le produit n'est pas en rupture de stock
    if ($product->is_out_of_stock) {
        throw new \Exception("Ce produit est en rupture de stock et ne peut pas être loué");
    }
    // ... reste de la logique
}
```

## Validation Côté API

### Middleware CheckProductAvailability
Appliqué automatiquement aux routes :
- `POST /api/cart/products/{product}` - Ajout au panier d'achat
- `PUT /api/cart/products/{product}` - Modification quantité panier d'achat
- `POST /api/cart-location/products/{product}` - Ajout au panier de location
- `PUT /api/cart-location/products/{product}/quantity` - Modification quantité panier de location

### API de Vérification
```http
POST /api/products/{product}/check-availability
Content-Type: application/json

{
    "type": "sale", // ou "rental"
    "quantity": 2
}
```

Réponse :
```json
{
    "success": true,
    "data": {
        "product_id": 123,
        "product_name": "Tracteur John Deere",
        "available": false,
        "reasons": [
            "Produit en rupture de stock"
        ]
    }
}
```

## Surveillance Automatique (Observer)

Le `ProductStockObserver` surveille :
- 📊 **Changements de quantité** - Met à jour les paniers affectés
- 🚫 **Produits devenus inactifs** - Marque les éléments de panier comme indisponibles
- 📧 **Notifications utilisateurs** - Alerte les clients affectés
- 📝 **Logs détaillés** - Traçabilité complète

## Cas d'Usage Couverts

### ✅ Achat Direct
```php
// Tentative d'achat d'un produit en rupture
$product->quantity = 0;
$cart->addProduct($product, 1); 
// ❌ Exception: "Ce produit est en rupture de stock et ne peut pas être acheté"
```

### ✅ Location
```php
// Tentative de location d'un produit en rupture
$product->quantity = 0;
$cartLocation->addProduct($product, 1, $startDate, $endDate);
// ❌ Exception: "Ce produit est en rupture de stock et ne peut pas être loué"
```

### ✅ Modification de Quantité
```php
// Produit ajouté au panier puis stock épuisé
$cart->addProduct($product, 2); // ✅ OK (stock = 5)
$product->update(['quantity' => 0]); // Stock épuisé
$cart->updateProductQuantity($product, 3);
// ❌ Exception: "Ce produit est en rupture de stock et ne peut pas être modifié"
```

### ✅ Produits Inactifs
```php
// Tentative d'ajout d'un produit inactif
$product->update(['is_active' => false]);
$cart->addProduct($product, 1);
// ❌ Exception: "Ce produit n'est plus disponible"
```

## Tests de Validation

Tous les cas sont couverts par des tests automatisés :
- ✅ Validation attribut `is_out_of_stock`
- ✅ Blocage ajout panier d'achat
- ✅ Blocage ajout panier location  
- ✅ Blocage modification quantité
- ✅ API de vérification disponibilité

```bash
php artisan test tests/Unit/SimpleStockConstraintTest.php
php artisan test tests/Unit/RentalStockConstraintTest.php
```

## Impact Utilisateur

### Interface Publique
- 🚫 Boutons "Ajouter au panier" désactivés pour produits en rupture
- ⚠️ Messages d'alerte clairs sur l'indisponibilité
- 🔍 Vérification temps réel avant ajout au panier

### Dashboard Admin
- 📊 Alertes automatiques via système de messages existant
- 🔔 Notifications temps réel des ruptures de stock
- 📈 Surveillance continue des niveaux de stock

## Sécurité et Performance

- **Validation Multiple** : Côté modèle + API + middleware
- **Prévention Race Conditions** : Observer surveille changements temps réel
- **Traçabilité Complète** : Logs détaillés de tous les événements
- **Tests Automatisés** : Validation continue du comportement
