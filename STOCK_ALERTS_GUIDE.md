# Système d'Alertes de Stock 📦

## Vue d'ensemble

Le système d'alertes de stock de FarmShop surveille automatiquement les niveaux de stock et notifie les administrateurs via des messages intégrés lorsque les seuils critiques sont atteints.

## Configuration des Seuils

### Champs dans la table `products`
- **`critical_threshold`** (int, défaut: 5) : Seuil critique - déclenche une alerte urgente
- **`low_stock_threshold`** (int, nullable) : Seuil de stock faible - alerte préventive

### Logique des Seuils
```
Quantité = 0          → 🚨 RUPTURE DE STOCK (critical)
Quantité ≤ critical   → ⚠️ STOCK CRITIQUE (high priority)
Quantité ≤ low_stock  → 📉 STOCK FAIBLE (medium priority)
Quantité > low_stock  → ✅ STOCK NORMAL
```

## Types d'Alertes Automatiques

### 1. 🚨 Rupture de Stock
**Déclencheur :** `quantity = 0`
```json
{
    "type": "stock_alert",
    "subject": "🚨 RUPTURE DE STOCK - Nom du produit",
    "priority": "high",
    "is_important": true,
    "metadata": {
        "alert_type": "out_of_stock",
        "product_id": 123,
        "current_stock": 0,
        "critical_threshold": 5
    }
}
```

### 2. ⚠️ Stock Critique
**Déclencheur :** `quantity ≤ critical_threshold && quantity > 0`
```json
{
    "type": "stock_alert", 
    "subject": "⚠️ STOCK CRITIQUE - Nom du produit",
    "priority": "high",
    "is_important": true,
    "metadata": {
        "alert_type": "critical_stock",
        "current_stock": 3,
        "critical_threshold": 5
    }
}
```

### 3. 📉 Stock Faible
**Déclencheur :** `quantity ≤ low_stock_threshold && quantity > critical_threshold`
```json
{
    "type": "stock_alert",
    "subject": "📉 STOCK FAIBLE - Nom du produit", 
    "priority": "medium",
    "metadata": {
        "alert_type": "low_stock",
        "current_stock": 8,
        "low_stock_threshold": 10
    }
}
```

### 4. ✅ Stock Reconstitué
**Déclencheur :** Passage d'un état critique à normal
```json
{
    "type": "stock_alert",
    "subject": "✅ STOCK RECONSTITUÉ - Nom du produit",
    "priority": "low",
    "metadata": {
        "alert_type": "stock_recovered",
        "current_stock": 25
    }
}
```

## API Endpoints

### Routes Admin - Gestion du Stock

#### 1. Dashboard Stock
```http
GET /api/admin/products/stock/dashboard
```
**Réponse :**
```json
{
    "success": true,
    "data": {
        "statistics": {
            "total_products": 150,
            "out_of_stock": 3,
            "critical_stock": 8, 
            "low_stock": 12,
            "in_stock": 127,
            "alerts": 23
        },
        "urgent_products": [...],
        "stock_evolution": [...],
        "recommendations": [
            {
                "type": "urgent",
                "icon": "🚨",
                "title": "Action immédiate requise",
                "message": "3 produit(s) en rupture de stock",
                "action": "Réapprovisionner immédiatement"
            }
        ]
    }
}
```

#### 2. Alertes de Stock
```http
GET /api/admin/products/stock/alerts
```
**Réponse :**
```json
{
    "success": true,
    "data": {
        "summary": { "out_of_stock": 3, "critical_stock": 8, "low_stock": 12 },
        "alerts_by_type": {
            "out_of_stock": [...],
            "critical_stock": [...],
            "low_stock": [...]
        },
        "total_alerts": 23,
        "urgent_alerts": 11
    }
}
```

#### 3. Mise à Jour de Stock
```http
POST /api/admin/products/{product}/stock/update
Content-Type: application/json

{
    "action": "set|increase|decrease",
    "quantity": 50,
    "reason": "Réapprovisionnement après livraison"
}
```

### Routes Messages - Alertes

#### 1. Alertes de Stock dans Messages
```http
GET /api/messages/stock-alerts?alert_type=out_of_stock&status=unread
```

#### 2. Marquer Alertes comme Lues
```http
POST /api/messages/stock-alerts/mark-read
```

#### 3. Compteur d'Alertes Non Lues
```http
GET /api/messages/unread-count
```
**Réponse :**
```json
{
    "success": true,
    "data": {
        "stock_alerts": 15,
        "urgent_alerts": 8,
        "total_unread": 42
    }
}
```

## Déclenchement Automatique

### Dans le Modèle Product
Les alertes sont automatiquement créées lors de :
- `decreaseStock($quantity)` : Vente, location, perte
- `increaseStock($quantity)` : Réapprovisionnement, retour
- `setStock($quantity)` : Mise à jour manuelle admin

### Événements Temps Réel
```php
// Événement diffusé en temps réel
StockUpdated::dispatch($product, $oldQuantity, $newQuantity, $changeType);
```

**Channels WebSocket :**
- `admin-dashboard` : Mises à jour en temps réel du dashboard
- `stock-alerts` : Notifications instantanées des alertes

## Éviter les Doublons

### Protection Anti-Spam
- **Délai minimum :** 1 heure entre alertes identiques
- **Vérification de statut :** Pas d'alerte si le statut n'a pas changé
- **Cache des alertes :** Les alertes récentes sont mémorisées

### Exemple de Code
```php
// Éviter les doublons récents
$recentAlert = Message::where('type', 'stock_alert')
    ->where('metadata->product_id', $this->id)
    ->where('metadata->alert_type', $alertType)
    ->where('created_at', '>', now()->subHour())
    ->exists();

if ($recentAlert) {
    return; // Pas de nouvelle alerte
}
```

## Interface Dashboard Admin

### Widget Alertes Stock
```html
<!-- Badge notifications -->
<div class="stock-alerts-badge">
    <span class="badge badge-danger" id="urgent-count">8</span>
    <span class="badge badge-warning" id="total-alerts">23</span>
</div>

<!-- Panneau alertes -->
<div class="stock-alerts-panel">
    <div class="alert alert-danger" v-for="product in urgentProducts">
        🚨 {{ product.name }} - Stock: {{ product.quantity }}
        <button @click="goToProduct(product.id)">Voir produit</button>
    </div>
</div>
```

### JavaScript Temps Réel
```javascript
// Écoute des événements WebSocket
Echo.private('admin-dashboard')
    .listen('StockUpdated', (e) => {
        updateStockDisplay(e.product_id, e.new_quantity, e.stock_status);
        
        if (e.is_critical || e.is_out_of_stock) {
            showStockAlert(e);
        }
    });

// Actualisation du badge
function updateAlertsBadge() {
    fetch('/api/messages/unread-count')
        .then(response => response.json())
        .then(data => {
            document.getElementById('urgent-count').textContent = data.data.urgent_alerts;
            document.getElementById('total-alerts').textContent = data.data.stock_alerts;
        });
}
```

## Logs et Audit

### Enregistrement des Changements
Chaque modification de stock génère un log :
```php
Message::create([
    'type' => 'stock_change_log',
    'subject' => "Modification stock - {$product->name}",
    'content' => "Stock modifié: {$oldQuantity} → {$newQuantity}\nRaison: {$reason}",
    'metadata' => [
        'product_id' => $product->id,
        'old_quantity' => $oldQuantity,
        'new_quantity' => $newQuantity,
        'difference' => $newQuantity - $oldQuantity,
        'reason' => $reason,
        'admin_id' => Auth::id()
    ]
]);
```

## Exemple d'Utilisation

### Scénario : Vente d'un Produit
1. **Client achète** 2 unités d'un produit (stock: 6 → 4)
2. **Stock ≤ critical_threshold** (5) → Alerte critique envoyée
3. **Admin reçoit notification** avec bouton "Voir produit"
4. **Admin réapprovisionne** via l'interface
5. **Stock reconstitué** → Notification de récupération

### Monitoring Automatique
- **Vérification continue** des seuils
- **Notifications instantanées** aux admins
- **Tableau de bord** avec métriques en temps réel
- **Historique complet** des changements

Le système garantit qu'aucune rupture de stock ne passe inaperçue et facilite la gestion proactive des inventaires ! 📈
