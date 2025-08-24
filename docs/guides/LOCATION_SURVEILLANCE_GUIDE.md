# 🔄 GUIDE DE CONFIGURATION - SURVEILLANCE AUTOMATIQUE DES LOCATIONS

## 🚨 **Problème identifié**

Votre commande LOC-202508034682 n'est pas passée automatiquement de "confirmed" à "active" le 04/08 à minuit car :

1. **📦 Queue Worker Laravel** - Consomme trop de mémoire et plante
2. **⏰ Scheduler Laravel** - Pas configuré pour s'exécuter automatiquement  
3. **🔄 Jobs automatiques** - Ne s'exécutent pas sans worker actif

## ✅ **Solution implementée**

### **🛠️ Script de surveillance léger (`monitor_rentals.php`)**

- Accès direct à la base de données (pas de Laravel)
- Consommation mémoire minimale
- Transitions automatiques :
  - `confirmed` → `active` (date de début atteinte)
  - `active` → `completed` (date de fin atteinte)

## 🕒 **Configuration Windows (Planificateur de tâches)**

### **Étape 1 : Ouvrir le Planificateur**
```
Windows + R → taskschd.msc → Entrée
```

### **Étape 2 : Créer une tâche**
```
Action → Créer une tâche de base
Nom: "FarmShop - Surveillance Locations"
Description: "Surveillance automatique des transitions de statuts"
```

### **Étape 3 : Déclencheur**
```
Démarrer: "Selon une planification"
Quotidienne: Tous les jours
Répéter la tâche: Toutes les 30 minutes
Pendant: 24 heures
```

### **Étape 4 : Action**
```
Action: Démarrer un programme
Programme: C:\Users\Master\Desktop\FarmShop\monitor_rentals.bat
Commencer dans: C:\Users\Master\Desktop\FarmShop
```

### **Étape 5 : Conditions (Décocher tout)**
```
❌ Démarrer la tâche seulement si l'ordinateur est inactif
❌ Arrêter la tâche si l'ordinateur n'est plus inactif  
❌ Démarrer seulement si l'ordinateur est sur secteur
```

## 📊 **Surveillance des logs**

### **Vérifier l'exécution :**
```bash
# Voir les logs de surveillance
type logs\rental_monitor.log

# Voir les dernières executions
Get-Content logs\rental_monitor.log -Tail 20
```

## 🧪 **Test manuel**

```bash
# Tester immédiatement
cd "C:\Users\Master\Desktop\FarmShop"
php monitor_rentals.php

# Tester avec le batch
monitor_rentals.bat
```

## 🎯 **Avantages de cette solution**

### ✅ **Fiabilité**
- Pas de dépendance Laravel (problèmes mémoire évités)
- Script léger et rapide
- Accès direct à la base de données

### ✅ **Autonomie**  
- Fonctionne même si le serveur web est éteint
- Pas besoin de queue worker
- Surveillance continue

### ✅ **Simplicité**
- Un seul script PHP simple
- Configuration Windows standard
- Logs faciles à consulter

## 🔧 **Maintenance**

### **Vérification mensuelle :**
```bash
# 1. Vérifier que la tâche Windows est active
schtasks /query /tn "FarmShop - Surveillance Locations"

# 2. Vérifier les logs récents  
Get-Content logs\rental_monitor.log -Tail 50

# 3. Tester manuellement
php monitor_rentals.php
```

---

## 🎉 **Résultat**

Votre commande **LOC-202508034682** est maintenant **ACTIVE** et le système de surveillance automatique est opérationnel pour éviter ce problème à l'avenir.

**📧 Email de notification :** Vous devriez recevoir un email confirmant l'activation de votre location.

**⏰ Prochaine transition :** Passage automatique vers "completed" le 11/08/2025 à minuit.
