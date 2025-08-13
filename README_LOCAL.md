# 🚀 FarmShop - Guide de Développement Local

## 📋 Configuration Automatique

Votre FarmShop est configuré pour un **développement local permanent** avec tous les outils nécessaires.

## 🎯 Démarrage Rapide

### Option 1: Script automatique (Recommandé)
```bash
# Double-cliquez sur le fichier
farmshop.bat
```

### Option 2: PowerShell avancé
```powershell
# Dans PowerShell
.\farmshop.ps1
```

### Option 3: Démarrage manuel
```bash
# Terminal 1: Serveur Laravel
php artisan serve

# Terminal 2: Worker de queue (OBLIGATOIRE pour les emails)
php artisan queue:work --daemon

# Terminal 3: Ngrok (si vous testez les webhooks)
ngrok http 8000
```

## 🛠️ Scripts Disponibles

| Script | Description |
|--------|-------------|
| `start-local.bat` | Démarre serveur + worker + navigateur |
| `check-system.bat` | Vérifie l'état du système |
| `clean-local.bat` | Nettoie cache et logs |
| `farmshop.bat` | Menu interactif complet |
| `farmshop.ps1` | Version PowerShell avancée |

## ⚙️ Configuration Locale

### Base de données
- **MariaDB** sur `localhost:3306`
- Base: `farmshop`
- User: `root` (sans mot de passe)

### Stripe (Test)
- ✅ Clés de test configurées
- ✅ Webhook secret configuré
- 🔗 Tunnel ngrok: `https://1c8dfcac9da4.ngrok-free.app`

### Emails
- ✅ Gmail SMTP configuré
- ✅ Transitions automatiques actives
- ⏱️ Délais: confirmed(15s)→preparing(15s)→shipped(15s)→delivered

### Timezone
- 🇧🇪 **Europe/Brussels** (heure belge)
- Affichage automatique CEST/CET

## 🔄 Workflow de Développement

### 1. Démarrage quotidien
```bash
# Lancez simplement
farmshop.bat
# Choisissez option 1
```

### 2. Test d'une commande
1. Ouvrez http://localhost:8000
2. Passez une commande
3. Les emails arrivent automatiquement (vérifiez s.mef2703@gmail.com)
4. Suivez les transitions: confirmed → preparing → shipped → delivered

### 3. Debugging
```bash
# Vérifier les dernières commandes
farmshop.bat → option 5

# Vérifier les logs
tail -f storage/logs/laravel.log
```

### 4. Si quelque chose ne marche pas
```bash
# Nettoyage complet
farmshop.bat → option 3

# Redémarrage du worker de queue
farmshop.bat → option 4
```

## 📧 Système d'Emails

### Status des transitions automatiques
- ✅ **confirmed**: Email de confirmation envoyé
- ✅ **preparing**: Email "commande en préparation" 
- ✅ **shipped**: Email "commande expédiée"
- ✅ **delivered**: Email "commande livrée"

### Vérification des emails
Tous les emails sont envoyés à: **s.mef2703@gmail.com**

## 🚨 Points Importants

### ⚠️ Worker de Queue = OBLIGATOIRE
**Sans le worker de queue, les transitions s'arrêtent !**

Le worker doit TOUJOURS être actif:
```bash
php artisan queue:work --daemon
```

### ✅ Vérifications automatiques
```bash
# Vérifier si le worker tourne
farmshop.bat → option 2

# Voir le statut des commandes
php check_last_order.php
```

## 🔧 Maintenance

### Nettoyage régulier
```bash
# Cache Laravel
php artisan cache:clear
php artisan config:clear

# Logs trop volumineux
echo. > storage/logs/laravel.log

# Jobs échoués
php artisan queue:flush
```

### Backup base de données
```bash
mysqldump -u root farmshop > backup_farmshop.sql
```

## 📞 Support

En cas de problème:
1. Vérifiez que MariaDB est démarré
2. Vérifiez que le worker de queue tourne
3. Consultez `storage/logs/laravel.log`
4. Utilisez `farmshop.bat` option 2 pour un diagnostic

---
🍎 **FarmShop v1.1.0** - Environnement de développement local
