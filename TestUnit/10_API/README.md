# Tests Unitaires: Systeme API (10_API)

## Description

Ce dossier contient les tests unitaires pour le systeme API de FarmShop, incluant les routes, les controleurs et les reponses JSON.

## Structure des Tests

### 1. test_api_routes.php
Teste la definition et la structure des routes API.

**Validations:**
- Existence et comptage des routes API
- Routes publiques (register, categories, products, contact)
- Routes protegees (auth, auth:web middleware)
- Groupes de routes (cart, cart-location, products, messages, wishlist, likes, cookies, stripe, blog, newsletter)
- Methodes HTTP utilisees (GET, POST, PUT, DELETE, PATCH)
- Routes nommees vs non nommees
- Routes specifiques par fonctionnalite (panier, location, paiement, cookies, blog)
- Statistiques (controleurs utilises, moyenne routes/controleur)

**Routes testees:**
- Panier (cart): GET, POST, PUT, DELETE
- Location (cart-location, rental): routes de gestion
- Paiement (stripe): webhooks
- Cookies: preferences, accept-all, reject-all, consent
- Blog: posts, categories, comments
- Products: index, search, show, availability
- Messages: inbox, visitor messages, stats

### 2. test_api_controllers.php
Teste la structure et les methodes des controleurs API.

**Controleurs testes:**
- BaseApiController: heritage, annotations OpenAPI/Swagger
- OrderStatusController: getStatus(), triggerNextStatus()
- CategoryController: methodes CRUD
- ProductController: methodes CRUD
- CartController: methodes CRUD
- CartLocationController: methodes de gestion location
- MessageController: methodes de messagerie
- WishlistController: methodes de liste de souhaits
- StripePaymentController: webhook, createPaymentIntent, processPayment
- CookieController: getPreferences, updatePreferences, acceptAll, rejectAll, checkConsent
- BlogPostController: methodes blog
- BlogCategoryController: categories blog
- BlogCommentController: commentaires blog
- RentalController: gestion locations
- RentalCategoryController: categories location
- RentalConstraintController: contraintes location

**Validations:**
- Existence des classes
- Heritage (Controller, BaseApiController)
- Methodes publiques CRUD (index, show, store, update, destroy)
- Methodes specifiques par controleur
- Types de retour (JsonResponse)
- Annotations Swagger/OpenAPI (@OA\Info, @OA\Server, @OA\SecurityScheme)
- Constructeurs et middlewares
- Statistiques (total controleurs, total methodes, moyenne)

### 3. test_api_responses.php
Teste le format et la coherence des reponses JSON.

**Validations:**
- Type de reponse (JsonResponse)
- Codes de statut HTTP:
  - 200 OK
  - 201 Created
  - 400 Bad Request
  - 401 Unauthorized
  - 403 Forbidden
  - 404 Not Found
  - 422 Unprocessable Entity
  - 500 Internal Server Error

**Structures de reponse:**
- Success: {status, message, data}
- Error: {status, message, errors}
- Pagination: {current_page, data, first_page_url, last_page, per_page, total}
- Validation: {message, errors} (code 422)
- Meta-donnees: {version, timestamp, request_id}
- Relations imbriquees: {category, images, etc}

**Autres validations:**
- Headers JSON (Content-Type: application/json)
- Ressources API (Resources)
- Formats de timestamp (ISO8601, datetime, timestamp, Carbon)
- Coherence des reponses (presence systematique de status et message)

## Execution

### Test individuel
```bash
php TestUnit/10_API/test_api_routes.php
php TestUnit/10_API/test_api_controllers.php
php TestUnit/10_API/test_api_responses.php
```

### Tous les tests
```bash
php TestUnit/10_API/run_all_tests.php
```

## Criteres de Reussite

### Routes API
- Toutes les routes API sont enregistrees
- Routes publiques et protegees correctement definies
- Groupes de routes bien organises
- Methodes HTTP appropriees
- Taux de nommage eleve (routes nommees)

### Controleurs API
- Tous les controleurs existent
- Methodes CRUD presentes selon les besoins
- Heritage correct (Controller, BaseApiController)
- Annotations Swagger/OpenAPI presentes sur BaseApiController
- Types de retour corrects (JsonResponse)

### Reponses JSON
- Format JSON valide
- Codes HTTP appropries
- Structure coherente (status, message, data/errors)
- Headers corrects (Content-Type)
- Pagination fonctionnelle
- Validation avec code 422

## Architecture API

### Routes Publiques
- Consultation produits/categories
- Recherche
- Inscription
- Contact visiteur
- Webhooks paiement

### Routes Protegees (auth)
- Profil utilisateur
- Panier (cart, cart-location)
- Liste de souhaits (wishlist)
- Likes produits
- Messages
- Commandes
- Newsletter

### Middlewares Utilises
- web: Sessions web
- auth: Authentification requise
- auth:web: Authentification via session web
- check.product.availability: Verification disponibilite produit

### Groupes Fonctionnels
- /api/cart: Panier achat
- /api/cart-location: Panier location
- /api/products: Produits
- /api/categories: Categories
- /api/rental-categories: Categories location
- /api/messages: Messagerie
- /api/wishlist: Liste souhaits
- /api/likes: Likes
- /api/cookies: Gestion cookies/RGPD
- /api/stripe: Paiement
- /api/blog: Blog
- /api/newsletter: Newsletter

## Documentation API

### Swagger/OpenAPI
- BaseApiController contient les annotations globales
- @OA\Info: Informations API (titre, version, description)
- @OA\Server: Serveurs disponibles (dev, prod)
- @OA\SecurityScheme: Schemas de securite (sanctum, session)
- @OA\Tag: Tags pour organisation

### Format Reponses Standard
```json
{
  "status": "success|error",
  "message": "Message descriptif",
  "data": {} // ou "errors": {}
}
```

### Pagination Standard
```json
{
  "current_page": 1,
  "data": [],
  "first_page_url": "...",
  "last_page": 5,
  "per_page": 15,
  "total": 75
}
```

## Notes Techniques

### Performance
- Routes groupees pour optimisation
- Middleware approprie par route
- Pagination pour grandes listes
- Lazy loading des relations

### Securite
- Routes protegees par middleware auth
- Validation des entrees
- CSRF pour routes web
- Webhooks sans authentification (verification signature)

### Bonnes Pratiques
- Nommage coherent des routes
- Codes HTTP semantiques
- Messages d'erreur clairs
- Structure JSON coherente
- Documentation Swagger/OpenAPI

## Resultats Attendus

Execution rapide (< 1 seconde) avec validation complete du systeme API.
