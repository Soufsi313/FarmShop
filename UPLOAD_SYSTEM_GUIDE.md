# SYSTÈME D'UPLOAD UNIFIÉ - SOLUTION RAILWAY

## 🚀 Problème Résolu
- **Railway** : Système de fichiers en lecture seule ❌ `storage/app/public` ne persiste pas
- **Solution** : Upload direct dans `public/images` ✅ Accessible et persistent

## 🛠️ Architecture

### Trait `HandlesImageUploads`
- **Auto-détection** : Railway, variables d'environnement, permissions
- **Dual storage** : `public/images` (Railway) ou `storage/app/public` (local)
- **URLs cohérentes** : `/images/...` ou `storage/...`

### Disks Configuration
```php
'railway' => [
    'driver' => 'local',
    'root' => public_path('images'),
    'url' => env('APP_URL').'/images',
    'visibility' => 'public',
]
```

## 📁 Structure
```
public/images/
├── products/
│   ├── gallery/
│   └── additional/
├── special-offers/
└── blog/
    └── articles/
```

## ⚙️ Configuration Railway

### Variables d'environnement
```bash
USE_RAILWAY_STORAGE=true
FILESYSTEM_DISK=railway
```

### Via Railway CLI
```bash
railway variables set USE_RAILWAY_STORAGE=true
railway variables set FILESYSTEM_DISK=railway
```

## 🧪 Tests

### Local
```bash
php artisan test:image-upload
```

### Railway
```bash
railway run php artisan test:image-upload
```

## 📝 Contrôleurs Mis à Jour
- ✅ `ProductController` : `$this->uploadImage()`
- ✅ `SpecialOfferController` : `$this->uploadImage()`  
- ✅ `DashboardController` : `$this->uploadImage()`

## 🔄 Migration des Images Existantes

### Copier storage vers public (si nécessaire)
```bash
# Local
cp -r storage/app/public/* public/images/

# Windows
xcopy storage\app\public\* public\images\ /E /I

# Mettre à jour en BDD les chemins d'images
UPDATE products SET main_image = REPLACE(main_image, 'storage/', '/images/') WHERE main_image LIKE 'storage/%';
```

## 🚀 Déploiement
1. Commit et push
2. Configurer variables Railway  
3. Redéployer
4. Tester uploads via admin

## ✅ Avantages
- 🔄 **Auto-adaptation** : Local ↔ Railway
- 🛡️ **Robuste** : Fallback automatique
- 🎯 **Unifié** : Un seul système pour tous les uploads
- 📱 **URLs stables** : Pas de 404 sur Railway
- 🧹 **Nettoyage** : Suppression intelligente
