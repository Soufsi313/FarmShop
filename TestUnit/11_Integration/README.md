# Tests d'Integration: Scenarios Reels et Cas d'Erreur (11_Integration)

## Description

Ce dossier contient les tests d'integration qui simulent des scenarios reels d'utilisation et testent les cas d'erreur pour s'assurer que le systeme gere correctement les situations invalides.

## Structure des Tests

### 1. test_empty_cart_checkout.php
Teste la tentative de commande avec un panier vide.

**Scenarios testes:**
- Creation d'un utilisateur de test
- Verification que le panier est vide (0 items)
- Tentative de checkout avec panier vide
- Validation que le checkout est bloque
- Tentative de creation directe de commande (contournement)
- Verification des methodes de validation (isEmpty, canCheckout)
- Test des regles de validation Laravel
- Simulation du comportement du CartController
- Verification absence de commandes sans items en base
- Test des messages d'erreur utilisateur

**Validations:**
- Le checkout doit etre bloque si panier vide
- Message d'erreur clair pour l'utilisateur
- Impossible de creer une commande sans items
- Methodes de validation fonctionnent correctement
- Aucune commande orpheline en base de donnees

**Messages d'erreur attendus:**
- "Le panier est vide. Veuillez ajouter des produits avant de passer commande."
- "Votre panier ne contient aucun article."
- "Impossible de passer commande avec un panier vide."

### 2. test_out_of_stock_product.php
Teste la tentative d'ajout au panier d'un produit en rupture de stock.

**Scenarios testes:**
- Recherche ou creation d'un produit avec stock_quantity = 0
- Verification de la disponibilite (is_available)
- Tentative d'ajout au panier d'un produit en rupture
- Test avec quantite superieure au stock disponible
- Verification des methodes hasStock() et isAvailableForPurchase()
- Test du middleware de verification de stock
- Validation Laravel des quantites
- Affichage utilisateur (badges, boutons)
- Alertes de stock faible
- Nettoyage des donnees de test

**Validations:**
- Produit en rupture non ajoutable au panier
- Message d'erreur explicite sur le stock insuffisant
- Verification du stock avant ajout
- Quantite demandee <= stock disponible
- Badge "Rupture de stock" affiche correctement
- Bouton "Ajouter au panier" desactive si stock = 0

**Messages d'erreur attendus:**
- "Le produit 'XXX' est en rupture de stock."
- "Stock insuffisant. Seulement X unites disponibles."

### 3. test_invalid_payment_amount.php
Teste la validation des montants de paiement invalides.

**Scenarios testes:**
- Paiement avec montant zero (0.00 EUR)
- Paiement avec montant negatif (-50.00 EUR)
- Paiement avec montant excessif (> 999999.99 EUR)
- Validation format Stripe (centimes, minimum 0.50 EUR)
- Concordance montant panier vs montant paiement
- Precision decimale (2 decimales maximum)
- Regles de validation Laravel
- Calcul de TVA (20%)
- Conversion de devise (EUR/USD)
- Limites du systeme (min/max)

**Validations:**
- Montant zero refuse
- Montant negatif refuse
- Montant excessif refuse (si > limite)
- Format Stripe respecte (centimes, >= 50 centimes)
- Montants panier et paiement concordants
- Decimales arrondies a 2 chiffres
- Calcul TVA correct
- Limites systeme respectees

**Limites definies:**
- Montant minimum: 0.01 EUR
- Montant maximum: 999999.99 EUR
- Stripe minimum: 0.50 EUR (50 centimes)
- Stripe maximum: 999999.99 EUR
- Precision: 2 decimales

### 4. test_rental_past_date.php
Teste la tentative de location d'un produit avec une date de debut dans le passe.

**Scenarios testes:**
- Recherche ou creation d'un produit de location (type: rental ou both)
- Test avec date d'hier (28/10 alors qu'on est le 29/10)
- Test avec differentes dates passees (il y a 2 jours, 1 semaine, 1 mois)
- Validation des dates valides (aujourd'hui, demain, semaine prochaine)
- Test regles validation Laravel (after_or_equal:today)
- Test date de fin avant date de debut
- Test contraintes de duree (min/max rental days)
- Verification types de produits (sale, rental, both)
- Test ajout au panier de location avec date passee
- Messages d'erreur utilisateur

**Validations:**
- Date de debut doit etre >= aujourd'hui
- Date de fin doit etre >= date de debut
- Duree doit respecter min/max rental days
- Produits de location correctement catalogues
- Validation front-end et back-end
- Messages d'erreur clairs

**Messages d'erreur attendus:**
- "La date de debut doit etre aujourd'hui ou dans le futur."
- "La date de debut de location ne peut pas etre dans le passe."
- "La date de fin doit etre posterieure ou egale a la date de debut."
- "La periode de location est invalide."

## Execution

### Test individuel
```bash
php TestUnit/11_Integration/test_empty_cart_checkout.php
php TestUnit/11_Integration/test_out_of_stock_product.php
php TestUnit/11_Integration/test_invalid_payment_amount.php
php TestUnit/11_Integration/test_rental_past_date.php
```

### Tous les tests
```bash
php TestUnit/11_Integration/run_all_tests.php
```

## Criteres de Reussite

### Panier Vide
- Checkout bloque si panier vide
- Message d'erreur clair affiche
- Aucune commande sans items creee
- Validation front-end et back-end

### Rupture de Stock
- Produit en rupture non ajoutable
- Verification du stock en temps reel
- Quantite demandee validee contre stock disponible
- Affichage utilisateur mis a jour (badges, boutons)
- Alertes admin pour stock faible

### Paiement Invalide
- Montants invalides refuses (zero, negatif, excessif)
- Format Stripe respecte
- Concordance panier/paiement verifiee
- Calculs corrects (TVA, conversion)
- Limites systeme respectees

### Location Date Passee
- Date de debut dans le passe refusee
- Validation today/future uniquement
- Date de fin >= date de debut
- Duree respecte contraintes (min/max jours)
- Distinction produits sale/rental/both
- Validation front-end et back-end

## Cas d'Utilisation Reels

### Scenario 1: Utilisateur oublie d'ajouter des produits
1. Utilisateur vide son panier
2. Utilisateur clique sur "Commander"
3. Systeme affiche: "Le panier est vide"
4. Utilisateur redirige vers catalogue

### Scenario 2: Produit populaire en rupture
1. Utilisateur consulte un produit populaire
2. Stock = 0 entre-temps
3. Utilisateur tente d'ajouter au panier
4. Systeme affiche: "Produit en rupture de stock"
5. Badge "Rupture de stock" affiche
6. Bouton "Ajouter au panier" desactive

### Scenario 3: Stock insuffisant
1. Utilisateur demande 10 unites
2. Stock disponible = 2 unites
3. Systeme affiche: "Seulement 2 unites disponibles"
4. Utilisateur ajuste la quantite a 2

### Scenario 4: Manipulation du montant
1. Utilisateur modifie le montant dans le DOM
2. Frontend envoie montant manipule
3. Backend recalcule le montant reel
4. Comparaison: montant panier != montant paiement
5. Paiement refuse

### Scenario 5: Location avec date passee
1. Utilisateur selectionne un produit de location
2. Utilisateur choisit date de debut: hier (28/10)
3. Systeme valide la date
4. Systeme affiche: "La date de debut doit etre aujourd'hui ou dans le futur"
5. Ajout au panier bloque
6. Utilisateur corrige la date (aujourd'hui ou futur)

## Donnees de Test

### Utilisateurs
- test_empty_cart@example.com (test panier vide)
- test_stock@example.com (test rupture stock)

### Produits de Test
- "Produit Test Rupture Stock" (stock = 0)
- "Produit Test Stock Limite" (stock = 2)

### Montants de Test
- Valides: 0.01, 0.50, 100.00, 999999.99 EUR
- Invalides: 0.00, -50.00, 1000000.00 EUR
- Stripe: 10050 centimes = 100.50 EUR

## Architecture

### Validations Multi-Niveaux

#### Front-end
- Verification JavaScript avant envoi
- Desactivation boutons si invalide
- Messages d'erreur en temps reel

#### Back-end
- Validation Laravel (Request)
- Logique metier (Model, Service)
- Verification base de donnees

#### Middleware
- CheckProductAvailability
- Verification session/auth
- Rate limiting

### Gestion des Erreurs

#### Messages Utilisateur
- Clairs et explicites
- En francais
- Avec solutions proposees

#### Logs
- Erreurs critiques loggees
- Tentatives suspectes tracees
- Metriques de disponibilite

## Bonnes Pratiques

### Prevention
- Verification stock en temps reel
- Recalcul montants cote serveur
- Validation systematique des entrees
- Transactions atomiques

### Detection
- Monitoring stock faible
- Alertes admin
- Logs des echecs
- Metriques business

### Recuperation
- Messages d'erreur constructifs
- Suggestions alternatives
- Sauvegarde du panier
- Notification disponibilite

## Metriques

### Performance
- Temps de validation: < 100ms
- Verification stock: < 50ms
- Calcul montants: < 10ms

### Fiabilite
- Taux de faux positifs: 0%
- Taux de faux negatifs: 0%
- Disponibilite: 99.9%

### Experience Utilisateur
- Messages clairs: 100%
- Temps de reponse: < 200ms
- Taux de conversion sauvee: Mesure

### Extensions Futures

### Tests Additionnels
- ~~Location avec dates invalides~~ ✓ Implemente (test_rental_past_date.php)
- Produit inexistant
- Session expiree
- Concurrence (meme produit, 2 utilisateurs)
- Limite de panier (nombre max items)
- Codes promo invalides
- Adresse livraison incomplete
- Location: periode trop courte/longue
- Location: chevauchement dates existantes
- Location: produit non-location (type: sale)

### Ameliorations
- Tests de charge
- Tests de securite (injection, XSS)
- Tests d'accessibilite
- Tests multi-navigateurs
- Tests API (endpoints)

## Notes Techniques

### Nettoyage
- Donnees de test supprimees apres execution
- Utilisateurs de test conserves (reutilisables)
- Transactions rollback si disponible

### Isolation
- Tests independants
- Pas d'effets de bord
- Donnees en lecture seule quand possible

### Reproductibilite
- Donnees de test predefinies
- Scenarios deterministes
- Environnement controle

## Resultats Attendus

Execution rapide (< 5 secondes) avec validation complete des cas d'erreur.
Tous les tests doivent passer, confirmant que le systeme gere correctement les situations invalides.
