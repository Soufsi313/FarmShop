# Manuel d'Utilisation - FarmShop
## Plateforme E-commerce de Matériel Agricole

---

**Version :** 1.0.0  
**Date :** 14 août 2025  
**URL d'accès :** http://127.0.0.1:8000  
**Documentation API :** http://127.0.0.1:8000/api/documentation

---

## 📋 Table des Matières

1. [Introduction](#1-introduction)
2. [Accès au Site](#2-accès-au-site)
3. [Création de Compte](#3-création-de-compte)
4. [Navigation et Interface](#4-navigation-et-interface)
5. [Gestion des Produits](#5-gestion-des-produits)
6. [Système de Panier](#6-système-de-panier)
7. [Processus de Commande](#7-processus-de-commande)
8. [Système de Location](#8-système-de-location)
9. [Gestion du Compte Utilisateur](#9-gestion-du-compte-utilisateur)
10. [Blog et Actualités](#10-blog-et-actualités)
11. [Système de Retours](#11-système-de-retours)
12. [Interface Administrateur](#12-interface-administrateur)
13. [Dépannage](#13-dépannage)
14. [Support Technique](#14-support-technique)

---

## 1. Introduction

### Qu'est-ce que FarmShop ?

FarmShop est une plateforme e-commerce spécialisée dans la vente et la location de matériel agricole. Elle permet aux utilisateurs de :

- **Acheter** du matériel agricole neuf et d'occasion
- **Louer** des équipements pour des besoins temporaires
- **Gérer** leurs commandes et locations
- **Consulter** un blog spécialisé
- **Retourner** des produits défectueux

### Fonctionnalités Principales

- ✅ **Catalogue de produits** avec système de filtres avancés
- ✅ **Système dual** : Vente ET Location
- ✅ **Gestion de panier** intelligent
- ✅ **Processus de commande** sécurisé
- ✅ **Système de caution** pour les locations
- ✅ **Inspection automatique** des retours
- ✅ **Blog intégré** avec système de commentaires
- ✅ **Interface admin** complète
- ✅ **API REST** documentée

---

## 2. Accès au Site

### Prérequis Techniques

- **Navigateur web** moderne (Chrome, Firefox, Safari, Edge)
- **Connexion internet** stable
- **JavaScript activé**
- **Cookies autorisés**

### URL d'Accès

🌐 **Site principal :** http://127.0.0.1:8000

### Pages Principales

- **Accueil :** `/`
- **Catalogue :** `/products`
- **Blog :** `/blog`
- **Contact :** `/contact`
- **Connexion :** `/login`
- **Inscription :** `/register`

---

## 3. Création de Compte

### Inscription Utilisateur

1. **Accéder à la page d'inscription**
   - Cliquez sur "S'inscrire" dans le menu principal
   - Ou rendez-vous sur : http://127.0.0.1:8000/register

2. **Remplir le formulaire**
   ```
   Informations requises :
   - Nom complet
   - Adresse email
   - Mot de passe (8 caractères minimum)
   - Confirmation du mot de passe
   - Numéro de téléphone
   - Adresse complète
   ```

3. **Validation du compte**
   - Un email de confirmation sera envoyé
   - Cliquez sur le lien de validation
   - Votre compte sera activé

### Types de Comptes

| Type | Permissions | Fonctionnalités |
|------|-------------|-----------------|
| **Client** | Standard | Achat, Location, Blog |
| **Admin** | Complètes | Gestion site, Statistiques |

---

## 4. Navigation et Interface

### Menu Principal

```
[Logo FarmShop] [Accueil] [Produits] [Blog] [Contact] [Panier] [Compte]
```

### Barre de Recherche

- **Recherche globale** : Saisissez un terme dans la barre de recherche
- **Filtres avancés** : Utilisez les filtres par catégorie, prix, type
- **Résultats en temps réel** : Suggestions automatiques

### Interface Responsive

- ✅ **Desktop** : Interface complète
- ✅ **Tablette** : Navigation adaptée
- ✅ **Mobile** : Version optimisée

---

## 5. Gestion des Produits

### Navigation dans le Catalogue

1. **Accès au catalogue**
   - Menu "Produits" ou http://127.0.0.1:8000/products

2. **Filtrage des produits**
   ```
   Filtres disponibles :
   - Catégorie (Outils, Machines, Équipements)
   - Type (Vente, Location, Les deux)
   - Gamme de prix
   - Disponibilité
   - État (Neuf, Occasion)
   ```

3. **Tri des résultats**
   - Par pertinence
   - Prix croissant/décroissant
   - Nouveautés
   - Popularité

### Fiche Produit

#### Informations Affichées

- **Images** : Galerie photos haute résolution
- **Description** : Détails techniques complets
- **Prix** : Vente et/ou location par jour
- **Stock** : Disponibilité en temps réel
- **Spécifications** : Caractéristiques techniques
- **Avis clients** : Notes et commentaires

#### Actions Disponibles

```
[Ajouter au Panier] [Louer] [Ajouter aux Favoris] [Partager]
```

---

## 6. Système de Panier

### Ajout de Produits

1. **Sélection du produit**
   - Choisissez la quantité
   - Sélectionnez le type (Achat/Location)
   - Pour les locations : définissez les dates

2. **Validation**
   - Cliquez sur "Ajouter au panier"
   - Confirmation visuelle

### Gestion du Panier

#### Interface du Panier

```
┌─────────────────────────────────────────┐
│ MON PANIER                              │
├─────────────────────────────────────────┤
│ Produit A    | Qté: 2 | Prix: 150€     │
│ Produit B    | Loc: 7j| Prix: 35€      │
├─────────────────────────────────────────┤
│ Sous-total:                       185€  │
│ Livraison:                         15€  │
│ Caution (locations):               50€  │
│ TOTAL:                            250€  │
└─────────────────────────────────────────┘
```

#### Actions Possibles

- ✏️ **Modifier les quantités**
- 🗑️ **Supprimer des articles**
- 💾 **Sauvegarder pour plus tard**
- 🧮 **Recalcul automatique**

---

## 7. Processus de Commande

### Étapes de Commande

#### Étape 1 : Révision du Panier
- Vérifiez les articles
- Modifiez si nécessaire
- Cliquez sur "Procéder au paiement"

#### Étape 2 : Informations de Livraison
```
Formulaire à remplir :
- Adresse de livraison
- Instructions spéciales
- Créneau de livraison souhaité
- Personne à contacter
```

#### Étape 3 : Mode de Paiement
- **Carte bancaire** (Visa, Mastercard)
- **PayPal**
- **Virement bancaire**
- **Paiement en magasin**

#### Étape 4 : Confirmation
- Récapitulatif complet
- Validation finale
- Numéro de commande généré

### Suivi de Commande

1. **Email de confirmation** automatique
2. **Espace client** : statut en temps réel
3. **Notifications SMS** (optionnel)

#### Statuts de Commande

| Statut | Description | Délai Moyen |
|--------|-------------|-------------|
| **En attente** | Paiement en cours | 5 min |
| **Confirmée** | Paiement validé | - |
| **Préparation** | Préparation en cours | 1-2 jours |
| **Expédiée** | En cours de livraison | 2-5 jours |
| **Livrée** | Commande reçue | - |

---

## 8. Système de Location

### Comment Louer un Équipement

1. **Sélection du produit**
   - Choisissez un produit avec option "Location"
   - Vérifiez la disponibilité

2. **Configuration de la location**
   ```
   Paramètres à définir :
   - Date de début
   - Date de fin
   - Durée totale (calculée automatiquement)
   - Lieu de récupération/livraison
   ```

3. **Calcul automatique**
   - Prix par jour × Nombre de jours
   - Caution requise
   - Frais de livraison/récupération

### Caution et Garanties

#### Montant de la Caution
- **Calculé automatiquement** selon la valeur de l'équipement
- **Préautorisation** sur votre carte bancaire
- **Remboursement** après inspection du retour

#### Process de Caution
```
Location confirmée → Caution bloquée → Équipement livré → 
Retour de l'équipement → Inspection → Caution libérée/débitée
```

### Inspection et Retour

1. **À la livraison**
   - Vérification de l'état
   - Photos documentaires
   - Signature du bon de livraison

2. **Pendant la location**
   - Utilisation conforme
   - Entretien de base
   - Signalement des problèmes

3. **Au retour**
   - Inspection détaillée
   - Comparaison état initial/final
   - Calcul des éventuelles pénalités

---

## 9. Gestion du Compte Utilisateur

### Mon Espace Client

**Accès :** http://127.0.0.1:8000/account

#### Sections Disponibles

1. **Tableau de Bord**
   - Résumé des commandes récentes
   - Locations en cours
   - Messages importants

2. **Mes Commandes**
   - Historique complet
   - Statut en temps réel
   - Factures téléchargeables

3. **Mes Locations**
   - Locations actives
   - Historique des locations
   - Calendrier de retour

4. **Mes Informations**
   - Données personnelles
   - Adresses de livraison
   - Préférences de contact

5. **Sécurité**
   - Changement de mot de passe
   - Authentification à deux facteurs
   - Sessions actives

### Notifications et Alertes

#### Types de Notifications
- 📧 **Email** : Confirmations, rappels
- 📱 **SMS** : Alertes urgentes
- 🔔 **In-app** : Notifications temps réel

#### Préférences de Notification
```
✅ Confirmations de commande
✅ Mises à jour de livraison
✅ Rappels de retour de location
✅ Promotions et offres spéciales
❌ Newsletter hebdomadaire
```

---

## 10. Blog et Actualités

### Accès au Blog

**URL :** http://127.0.0.1:8000/blog

### Navigation du Blog

#### Catégories d'Articles
- **Actualités** : Nouveautés du secteur
- **Guides** : Tutorials et conseils
- **Tests** : Évaluations d'équipements
- **Témoignages** : Retours d'expérience

#### Fonctionnalités
- 🔍 **Recherche** dans les articles
- 🏷️ **Tags** thématiques
- 📅 **Archive** par date
- ⭐ **Articles populaires**

### Interaction avec les Articles

1. **Lecture d'un article**
   - Contenu complet
   - Images et vidéos
   - Temps de lecture estimé

2. **Système de commentaires**
   - Connexion requise
   - Modération automatique
   - Réponses en fil de discussion

3. **Partage social**
   - Facebook, Twitter, LinkedIn
   - Lien direct
   - Email

### Signalement de Contenu

Si vous trouvez un contenu inapproprié :
1. Cliquez sur "Signaler"
2. Sélectionnez la raison
3. Ajoutez des détails
4. Notre équipe examine sous 24h

---

## 11. Système de Retours

### Politique de Retour

#### Conditions Générales
- **Délai** : 14 jours après réception
- **État** : Produit dans son emballage d'origine
- **Motifs acceptés** : Défaut, erreur, non-conformité

#### Produits Non Retournables
- Équipements personnalisés
- Produits périssables
- Articles endommagés par l'usage

### Processus de Retour

#### Étape 1 : Demande de Retour
1. **Connexion** à votre compte
2. **Mes Commandes** → Sélectionner la commande
3. **Demander un retour** → Remplir le formulaire

#### Étape 2 : Validation Administrative
- Examen de la demande (24-48h)
- Notification par email
- Génération de l'étiquette de retour

#### Étape 3 : Expédition du Retour
- Emballage soigné
- Étiquette de retour collée
- Remise au transporteur

#### Étape 4 : Inspection
- Réception en entrepôt
- Contrôle qualité
- Décision de remboursement

#### Étape 5 : Remboursement
- Traitement sous 5-7 jours
- Même mode de paiement
- Email de confirmation

### Suivi du Retour

```
Demande → En attente → Approuvée → Expédiée → 
Reçue → Inspection → Remboursée
```

---

## 12. Interface Administrateur

### Accès Administrateur

**URL :** http://127.0.0.1:8000/admin  
**Prérequis :** Compte avec privilèges administrateur

### Tableau de Bord Admin

#### Vue d'Ensemble
- 📊 **Statistiques en temps réel**
- 📈 **Graphiques de performance**
- 🚨 **Alertes système**
- 📋 **Tâches en attente**

#### Sections Principales

1. **Gestion des Produits**
   - Ajout/modification de produits
   - Gestion du stock
   - Catégories et attributs

2. **Gestion des Commandes**
   - Traitement des commandes
   - Suivi des livraisons
   - Gestion des retours

3. **Gestion des Utilisateurs**
   - Liste des clients
   - Modération des comptes
   - Support client

4. **Gestion du Blog**
   - Publication d'articles
   - Modération des commentaires
   - Gestion des catégories

5. **Statistiques et Rapports**
   - Ventes par période
   - Produits populaires
   - Analyse des performances

### Fonctionnalités Avancées

#### Gestion du Stock
- **Alertes stock bas**
- **Réapprovisionnement automatique**
- **Suivi des mouvements**

#### Système d'Inspection
- **Workflow d'inspection** des retours
- **Documentation photographique**
- **Calcul automatique** des pénalités

---

## 13. Dépannage

### Problèmes Courants

#### Problème : Impossible de se connecter
**Solutions :**
1. Vérifiez vos identifiants
2. Utilisez "Mot de passe oublié"
3. Videz le cache du navigateur
4. Désactivez temporairement les extensions

#### Problème : Panier qui se vide
**Solutions :**
1. Acceptez les cookies du site
2. Ne naviguez pas en navigation privée
3. Terminez votre commande rapidement
4. Sauvegardez votre panier

#### Problème : Paiement refusé
**Solutions :**
1. Vérifiez les données de votre carte
2. Contactez votre banque
3. Essayez un autre mode de paiement
4. Vérifiez les limites de votre carte

#### Problème : Page ne se charge pas
**Solutions :**
1. Actualisez la page (F5)
2. Vérifiez votre connexion internet
3. Essayez un autre navigateur
4. Contactez le support technique

### Messages d'Erreur

| Code | Message | Solution |
|------|---------|----------|
| **404** | Page non trouvée | Vérifiez l'URL |
| **500** | Erreur serveur | Contactez le support |
| **403** | Accès refusé | Connectez-vous |
| **422** | Données invalides | Vérifiez le formulaire |

---

## 14. Support Technique

### Moyens de Contact

#### Support Principal
- 📧 **Email :** support@farmshop.local
- 📞 **Téléphone :** +33 1 23 45 67 89
- 💬 **Chat en ligne :** Disponible 9h-18h
- 📋 **Formulaire :** http://127.0.0.1:8000/contact

#### Horaires de Support
- **Lundi-Vendredi :** 9h00 - 18h00
- **Samedi :** 9h00 - 12h00
- **Dimanche :** Fermé

#### Urgences (locations)
- 📱 **24h/24 :** +33 6 12 34 56 78

### Documentation Technique

#### Pour les Développeurs
- 📚 **API Documentation :** http://127.0.0.1:8000/api/documentation
- 🔧 **Guide technique :** Disponible sur demande
- 💻 **SDK :** En développement

#### FAQ Technique

**Q : Comment intégrer l'API ?**  
R : Consultez la documentation complète sur /api/documentation

**Q : Le site est-il responsive ?**  
R : Oui, optimisé pour tous les appareils

**Q : Quels navigateurs sont supportés ?**  
R : Chrome, Firefox, Safari, Edge (versions récentes)

---

## 📞 Contacts d'Urgence

| Situation | Contact | Disponibilité |
|-----------|---------|---------------|
| **Problème technique** | support@farmshop.local | 9h-18h |
| **Commande urgente** | +33 1 23 45 67 89 | 9h-18h |
| **Location en panne** | +33 6 12 34 56 78 | 24h/24 |
| **Problème de paiement** | compta@farmshop.local | 9h-17h |

---

**© 2025 FarmShop - Tous droits réservés**  
*Manuel d'utilisation v1.0.0 - Mis à jour le 14 août 2025*
