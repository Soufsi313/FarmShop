# Test des Contraintes de Location

Ce document démontre le nouveau système de contraintes de location avec les règles :
- **Durée maximum** : 7 jours
- **Jours disponibles** : Lundi à Samedi uniquement (pas le dimanche)
- **Délai minimum** : Pas de location le jour même, démarrage à partir de demain

## Structure du Système

### 1. Migration de Base de Données
```sql
-- Ajout de champs pour les contraintes de location
ALTER TABLE products ADD COLUMN min_rental_days INT DEFAULT 1;
ALTER TABLE products ADD COLUMN max_rental_days INT DEFAULT 7;
ALTER TABLE products ADD COLUMN available_days JSON DEFAULT '[1,2,3,4,5,6]';
```

### 2. Modèle Product Enrichi
- `isRentable()` : Vérifie si le produit est disponible à la location
- `validateRentalPeriod($start, $end)` : Valide une période de location
- `getNextAvailableStartDate()` : Trouve la prochaine date de début valide
- `calculateRentalCost($start, $end)` : Calcule le coût avec remises éventuelles

### 3. Règle de Validation Personnalisée
`RentalDateValidation` : Valide les dates selon les contraintes business :
- Vérification des jours disponibles
- Validation de la durée (1-7 jours)
- Contrôle du délai minimum (pas le jour même)

### 4. API de Contraintes
Nouvelles routes disponibles :
- `GET /api/rental-constraints/{product}` : Obtenir les contraintes d'un produit
- `POST /api/rental-constraints/{product}/validate` : Valider une période
- `GET /api/rental-constraints/{product}/calendar` : Calendrier de disponibilité
- `GET /api/rental-constraints/{product}/suggestions` : Suggestions de dates optimales

## Exemple d'Utilisation

### 1. Obtenir les contraintes d'un produit
```http
GET /api/rental-constraints/1
```

Réponse :
```json
{
  "success": true,
  "data": {
    "product_id": 1,
    "product_name": "Tracteur agricole",
    "constraints": {
      "min_days": 1,
      "max_days": 7,
      "available_days": [1, 2, 3, 4, 5, 6],
      "available_days_names": ["Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"],
      "daily_price": 25.00,
      "deposit_amount": 200.00,
      "next_available_start": "2024-07-13",
      "business_hours": "Lundi - Samedi"
    },
    "pricing": {
      "daily_price": 25.00,
      "deposit_amount": 200.00,
      "currency": "EUR"
    }
  }
}
```

### 2. Valider une période de location
```http
POST /api/rental-constraints/1/validate
Content-Type: application/json

{
  "start_date": "2024-07-15",
  "end_date": "2024-07-18"
}
```

Réponse success :
```json
{
  "success": true,
  "data": {
    "valid": true,
    "errors": [],
    "duration": 4,
    "cost_details": {
      "duration_days": 4,
      "daily_price": 25.00,
      "subtotal": 100.00,
      "discount_percentage": 0,
      "discount_amount": 0.00,
      "total_cost": 100.00,
      "deposit_required": 200.00,
      "currency": "EUR"
    }
  },
  "message": "Période de location valide"
}
```

Réponse erreur (ex: dimanche inclus) :
```json
{
  "success": false,
  "data": {
    "valid": false,
    "errors": ["Location non disponible le Dimanche (14/07/2024)"],
    "duration": 4
  },
  "message": "Période de location non valide"
}
```

### 3. Obtenir le calendrier de disponibilité
```http
GET /api/rental-constraints/1/calendar?start_date=2024-07-12&end_date=2024-07-26
```

### 4. Obtenir des suggestions de dates
```http
GET /api/rental-constraints/1/suggestions?duration=5&start_from=2024-07-15
```

## Intégration dans le Panier

Les contrôleurs de panier (`CartLocationController`, `CartItemLocationController`) utilisent maintenant la validation `RentalDateValidation` pour s'assurer que :

1. **Ajout au panier** : Les dates respectent les contraintes
2. **Modification des dates** : Les nouvelles dates sont valides
3. **Calcul des prix** : Remises automatiques pour locations longues

## Avantages du Système

1. **Rotation équitable** : Limitation à 7 jours maximum pour tous
2. **Gestion des weekends** : Pas de conflits le dimanche
3. **Planification avancée** : Délai minimum pour préparation
4. **Transparence** : API claire pour les contraintes et disponibilités
5. **Flexibilité** : Configuration par produit possible

## Configuration par Défaut

Tous les produits de location existants reçoivent automatiquement :
- Durée minimale : 1 jour
- Durée maximale : 7 jours
- Jours disponibles : Lundi à Samedi (1,2,3,4,5,6)

Cette configuration garantit une rotation stable et équitable pour tous les utilisateurs.
