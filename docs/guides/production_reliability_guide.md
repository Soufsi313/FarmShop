# 🏭 GUIDE DE FIABILITÉ PRODUCTION - FARMSHOP

## 🚨 **Problème : Arrêt machine**

Quand votre serveur Windows s'arrête/redémarre :
- ❌ Worker Laravel s'arrête
- ❌ Jobs en attente s'accumulent  
- ❌ Transitions automatiques bloquées
- ❌ Clients ne reçoivent plus d'emails

## ✅ **Solution multi-niveaux**

### **Niveau 1 : Service Windows persistant**

```batch
# Créer un service Windows qui redémarre automatiquement
sc create "FarmShopQueue" binPath="C:\Users\Master\Desktop\FarmShop\queue_service.bat" start=auto
sc description "FarmShopQueue" "Service de queue FarmShop pour traitement automatique"
sc config "FarmShopQueue" obj="LocalSystem" password=""
```

### **Niveau 2 : Surveillance indépendante** 

**Planificateur Windows :**
- Nom : `FarmShop-Monitor-Rentals`
- Déclencheur : **Toutes les 15 minutes**
- Conditions : ✅ **Exécuter même si utilisateur absent**
- Action : `C:\Users\Master\Desktop\FarmShop\monitor_rentals.bat`

### **Niveau 3 : Script de démarrage automatique**

**Fichier : `startup_farmshop.bat`**
```batch
@echo off
REM Démarrage automatique au boot Windows
cd /d "C:\Users\Master\Desktop\FarmShop"

REM 1. Redémarrer le worker de queue
taskkill /f /im php.exe 2>nul
start /MIN "FarmShop Queue" php artisan queue:work --daemon --tries=3

REM 2. Traiter les jobs en retard immédiatement  
php artisan queue:work --once --tries=1

REM 3. Vérifier les transitions de location en retard
php monitor_rentals.php
```

## 🔄 **Récupération après arrêt**

### **Étapes automatiques :**

1. **Au redémarrage Windows :**
   - Service FarmShop se relance automatiquement
   - Planificateur reprend la surveillance
   - Worker traite les jobs accumulés

2. **Traitement des retards :**
   ```php
   // monitor_rentals.php traite automatiquement :
   - Locations confirmées → active (si date dépassée)
   - Locations actives → completed (si date dépassée)  
   - Calcul des frais de retard
   ```

3. **Rattrapage des emails :**
   ```php
   // Jobs en attente sont retraités automatiquement
   - Confirmations de location
   - Rappels de fin
   - Notifications de retard
   ```

## ⚙️ **Configuration manuelle recommandée**

### **1. Service Windows (Administrateur requis)**

```powershell
# Exécuter en tant qu'administrateur
New-Service -Name "FarmShopQueue" -BinaryPathName "C:\Users\Master\Desktop\FarmShop\queue_service_wrapper.exe" -StartupType Automatic -Description "FarmShop Queue Worker Service"
```

### **2. Tâche programmée survie**

```batch
# Ouvrir : taskschd.msc
# Créer : "FarmShop-AutoRestart"
# Déclencheur : À l'ouverture de session + Toutes les heures
# Action : C:\Users\Master\Desktop\FarmShop\ensure_running.bat
# Conditions : ❌ Décocher toutes les conditions d'alimentation
```

### **3. Surveillance externe (optionnel)**

**Service de monitoring externe :**
- Pingdom, UptimeRobot, ou StatusCake
- Vérification HTTP toutes les 5 minutes  
- Alerte SMS/Email si le site ne répond pas

## 📋 **Checklist de déploiement production**

### **Avant déploiement :**
- [ ] Service Windows configuré et testé
- [ ] Planificateur de tâches actif  
- [ ] Scripts de redémarrage automatique
- [ ] Test de coupure/redémarrage machine
- [ ] Logs de surveillance configurés
- [ ] Monitoring externe activé

### **Tests de robustesse :**
```batch
# 1. Test redémarrage forcé
shutdown /r /t 0

# 2. Vérifier après redémarrage (5 min)
php check_queue_status.php
Get-Content storage\logs\laravel.log -Tail 20

# 3. Test coupure worker
taskkill /f /im php.exe
# Attendre 2 minutes, vérifier relance automatique
```

## 🎯 **Avantages de cette configuration**

### ✅ **Haute disponibilité**
- Redémarrage automatique après coupure
- Surveillance continue même utilisateur absent
- Rattrapage automatique des retards

### ✅ **Résilience** 
- Multi-niveaux de sécurité
- Surveillance indépendante de Laravel
- Logs détaillés pour diagnostic

### ✅ **Transparence utilisateur**
- Transitions continuent même après coupure  
- Emails envoyés automatiquement au redémarrage
- Aucune intervention manuelle requise

## 🔧 **Maintenance recommandée**

### **Hebdomadaire :**
```batch
# Vérifier les logs
Get-Content storage\logs\laravel.log | Select-String "ERROR"
Get-Content logs\rental_monitor.log -Tail 50

# Vérifier le service
sc query FarmShopQueue
```

### **Mensuelle :**
```batch
# Nettoyer les anciens jobs
php artisan queue:clear
php artisan queue:flush

# Restart complet  
sc stop FarmShopQueue
sc start FarmShopQueue
```

---

**💡 En résumé :** Votre système peut être 100% autonome avec cette configuration. Les locations continueront à être traitées automatiquement même après des coupures de courant ou redémarrages surprise.
