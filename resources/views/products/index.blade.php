@extends('layouts.public')

@section('title', 'Nos Produits Locaux - FarmShop')

@section('content')
<div class="container py-5">
    <!-- Hero Section -->
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h1 class="display-4 text-success fw-bold mb-3">
                <i class="fas fa-seedling me-3"></i>
                Nos Produits Locaux
            </h1>
            <p class="lead text-muted">
                Découvrez notre sélection de produits frais et locaux, directement de nos producteurs partenaires.
            </p>
        </div>
    </div>

    <!-- Filtres et tri -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="GET" action="{{ route('products.index') }}" class="row g-3">
                        <!-- Recherche -->
                        <div class="col-md-4">
                            <label for="search" class="form-label">Rechercher</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" 
                                       class="form-control" 
                                       id="search" 
                                       name="search" 
                                       value="{{ request('search') }}" 
                                       placeholder="Nom du produit...">
                            </div>
                        </div>

                        <!-- Catégorie -->
                        <div class="col-md-2">
                            <label for="category" class="form-label">Catégorie</label>
                            <select class="form-select" id="category" name="category">
                                <option value="">Toutes les catégories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                            {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Type de produit -->
                        <div class="col-md-2">
                            <label for="product_type" class="form-label">Type</label>
                            <select class="form-select" id="product_type" name="product_type">
                                <option value="">Tous les types</option>
                                <option value="purchase" {{ request('product_type') == 'purchase' ? 'selected' : '' }}>
                                    <i class="fas fa-shopping-cart"></i> Achat
                                </option>
                                <option value="rental" {{ request('product_type') == 'rental' ? 'selected' : '' }}>
                                    <i class="fas fa-calendar-alt"></i> Location
                                </option>
                            </select>
                        </div>

                        <!-- Prix -->
                        <div class="col-md-2">
                            <label for="price_min" class="form-label">Prix min (€)</label>
                            <input type="number" 
                                   class="form-control" 
                                   id="price_min" 
                                   name="price_min" 
                                   value="{{ request('price_min') }}" 
                                   min="0" 
                                   step="0.01"
                                   placeholder="0">
                        </div>

                        <div class="col-md-2">
                            <label for="price_max" class="form-label">Prix max (€)</label>
                            <input type="number" 
                                   class="form-control" 
                                   id="price_max" 
                                   name="price_max" 
                                   value="{{ request('price_max') }}" 
                                   min="0" 
                                   step="0.01"
                                   placeholder="100">
                        </div>

                        <!-- Tri -->
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-filter"></i>
                            </button>
                        </div>
                    </form>

                    <!-- Tri rapide -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="d-flex flex-wrap gap-2">
                                <small class="text-muted align-self-center me-2">Tri rapide :</small>
                                <a href="{{ route('products.index', array_merge(request()->all(), ['sort' => 'name', 'direction' => 'asc'])) }}" 
                                   class="btn btn-outline-secondary btn-sm {{ request('sort') == 'name' ? 'active' : '' }}">
                                    Nom A-Z
                                </a>
                                <a href="{{ route('products.index', array_merge(request()->all(), ['sort' => 'price', 'direction' => 'asc'])) }}" 
                                   class="btn btn-outline-secondary btn-sm {{ request('sort') == 'price' && request('direction') == 'asc' ? 'active' : '' }}">
                                    Prix ↑
                                </a>
                                <a href="{{ route('products.index', array_merge(request()->all(), ['sort' => 'price', 'direction' => 'desc'])) }}" 
                                   class="btn btn-outline-secondary btn-sm {{ request('sort') == 'price' && request('direction') == 'desc' ? 'active' : '' }}">
                                    Prix ↓
                                </a>
                                <a href="{{ route('products.index', array_merge(request()->all(), ['sort' => 'newest'])) }}" 
                                   class="btn btn-outline-secondary btn-sm {{ request('sort') == 'newest' ? 'active' : '' }}">
                                    Nouveautés
                                </a>
                                @if(request()->hasAny(['search', 'category', 'product_type', 'price_min', 'price_max', 'sort']))
                                    <a href="{{ route('products.index') }}" class="btn btn-outline-danger btn-sm">
                                        <i class="fas fa-times me-1"></i>Réinitialiser
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Résultats -->
    <div class="row">
        <div class="col-12">
            @if(isset($products) && $products->count() > 0)
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2 text-success"></i>
                        {{ $products->total() }} produit{{ $products->total() > 1 ? 's' : '' }} trouvé{{ $products->total() > 1 ? 's' : '' }}
                    </h5>
                </div>

                <!-- Grille des produits -->
                <div class="row">
                    @foreach($products as $product)
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card product-card h-100 shadow-sm border-0" 
                                 data-product-id="{{ $product->id }}" 
                                 data-stock="{{ $product->quantity }}">
                                <div class="position-relative">
                                    <!-- Image du produit -->
                                    @if($product->main_image)
                                        <img src="{{ asset('storage/' . $product->main_image) }}" 
                                             class="card-img-top" 
                                             alt="{{ $product->name }}"
                                             style="height: 250px; object-fit: cover;">
                                    @else
                                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                             style="height: 250px;">
                                            <i class="fas fa-image text-muted fa-3x"></i>
                                        </div>
                                    @endif
                                    
                                    <!-- Badges -->
                                    <div class="position-absolute top-0 end-0 m-2">
                                        @if($product->is_rentable && $product->price <= 0)
                                            <span class="badge bg-info mb-1 d-block">
                                                <i class="fas fa-calendar-alt me-1"></i>Location
                                            </span>
                                        @elseif($product->price > 0 && !$product->is_rentable)
                                            <span class="badge bg-success mb-1 d-block">
                                                <i class="fas fa-shopping-cart me-1"></i>Achat
                                            </span>
                                        @elseif($product->price > 0 && $product->is_rentable)
                                            <span class="badge bg-warning mb-1 d-block">
                                                <i class="fas fa-star me-1"></i>Achat + Location
                                            </span>
                                        @endif
                                        
                                        @if($product->is_featured)
                                            <span class="badge bg-warning mb-1 d-block">
                                                <i class="fas fa-star me-1"></i>Vedette
                                            </span>
                                        @endif
                                        @if($product->category)
                                            <span class="badge bg-secondary d-block">
                                                {{ $product->category->name }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Indicateur de stock -->
                                    <div class="position-absolute top-0 start-0 m-2">
                                        @if($product->quantity > $product->critical_stock_threshold)
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i>En stock
                                            </span>
                                        @elseif($product->quantity > 0)
                                            <span class="badge bg-warning">
                                                <i class="fas fa-exclamation-triangle me-1"></i>Stock faible
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times me-1"></i>Rupture
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title mb-0">{{ $product->name }}</h5>
                                        @auth
                                            <div class="dropdown">
                                                <button class="btn btn-link p-0 text-muted" 
                                                        type="button" 
                                                        data-bs-toggle="dropdown">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a class="dropdown-item" href="#" onclick="addToWishlist({{ $product->id }})">
                                                            <i class="fas fa-heart me-2 text-danger"></i>Ajouter aux favoris
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="#" onclick="shareProduct({{ $product->id }})">
                                                            <i class="fas fa-share me-2 text-info"></i>Partager
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        @endauth
                                    </div>
                                    
                                    <p class="card-text text-muted flex-grow-1 small">
                                        {{ Str::limit($product->description, 80) }}
                                    </p>
                                    
                                    <!-- Prix et stock -->
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            @if($product->is_rentable && $product->rental_price_per_day > 0)
                                                <span class="h5 text-info mb-0 fw-bold">{{ number_format($product->rental_price_per_day, 2) }}€</span>
                                                <small class="text-muted d-block">/jour</small>
                                                @if($product->price > 0)
                                                    <small class="text-success">Achat: {{ number_format($product->price, 2) }}€/{{ $product->unit_symbol }}</small>
                                                @endif
                                            @else
                                                <span class="h5 text-success mb-0 fw-bold">{{ number_format($product->price, 2) }}€</span>
                                                <small class="text-muted d-block">/{{ $product->unit_symbol }}</small>
                                            @endif
                                        </div>
                                        <div class="text-end">
                                            <small class="text-muted d-block">Stock: {{ $product->quantity }}</small>
                                            @if($product->quantity <= $product->critical_stock_threshold && $product->quantity > 0)
                                                <small class="text-warning">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>Dernières pièces !
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Actions -->
                                    <div class="d-flex gap-2">
                                        <!-- Bouton Voir (toujours visible) -->
                                        <a href="{{ route('products.show', $product->slug) }}" 
                                           class="btn btn-outline-primary btn-sm flex-fill">
                                            <i class="fas fa-eye me-1"></i>Voir détails
                                        </a>
                                        
                                        @auth
                                            <!-- Actions pour utilisateurs connectés -->
                                            @if($product->quantity > 0)
                                                @if($product->is_rentable && $product->price <= 0)
                                                    <!-- Produit location uniquement -->
                                                    <div class="btn-group flex-fill" role="group">
                                                        <button class="btn btn-outline-info btn-sm" 
                                                                onclick="addToCart({{ $product->id }}, 'rental')">
                                                            <i class="fas fa-calendar-plus me-1"></i>Réserver
                                                        </button>
                                                        <button class="btn btn-info btn-sm" 
                                                                onclick="rentNow({{ $product->id }})">
                                                            <i class="fas fa-calendar-check me-1"></i>Louer
                                                        </button>
                                                    </div>
                                                @elseif($product->price > 0 && !$product->is_rentable)
                                                    <!-- Produit achat uniquement -->
                                                    <div class="btn-group flex-fill" role="group">
                                                        <button class="btn btn-outline-success btn-sm" 
                                                                onclick="addToCart({{ $product->id }}, 'purchase')">
                                                            <i class="fas fa-shopping-cart me-1"></i>Panier
                                                        </button>
                                                        <button class="btn btn-success btn-sm" 
                                                                onclick="buyNow({{ $product->id }})">
                                                            <i class="fas fa-bolt me-1"></i>Acheter
                                                        </button>
                                                    </div>
                                                @else
                                                    <!-- Produit achat + location -->
                                                    <div class="d-flex flex-column gap-1">
                                                        <div class="btn-group" role="group">
                                                            <button class="btn btn-outline-success btn-sm" 
                                                                    onclick="addToCart({{ $product->id }}, 'purchase')">
                                                                <i class="fas fa-shopping-cart me-1"></i>Acheter
                                                            </button>
                                                            <button class="btn btn-outline-info btn-sm" 
                                                                    onclick="addToCart({{ $product->id }}, 'rental')">
                                                                <i class="fas fa-calendar-plus me-1"></i>Louer
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endif
                                            @else
                                                <button class="btn btn-secondary btn-sm flex-fill" disabled>
                                                    <i class="fas fa-ban me-1"></i>Rupture
                                                </button>
                                            @endif
                                        @else
                                            <!-- Message pour visiteurs non connectés -->
                                            <button class="btn btn-outline-success btn-sm flex-fill" 
                                                    onclick="showLoginPrompt()">
                                                <i class="fas fa-user-plus me-1"></i>Se connecter
                                            </button>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if(method_exists($products, 'links'))
                    <div class="d-flex justify-content-center mt-5">
                        <div class="d-flex flex-column align-items-center">
                            {{ $products->withQueryString()->links() }}
                            <small class="text-muted mt-2">
                                Affichage de {{ $products->firstItem() }} à {{ $products->lastItem() }} 
                                sur {{ $products->total() }} résultats
                            </small>
                        </div>
                    </div>
                @endif
            @else
                <!-- Message si aucun produit -->
                <div class="text-center py-5">
                    <div class="card border-0">
                        <div class="card-body">
                            <div class="mb-4">
                                <i class="fas fa-search fa-4x text-muted"></i>
                            </div>
                            <h3 class="text-muted mb-3">Aucun produit trouvé</h3>
                            <p class="text-muted">
                                Désolé, aucun produit ne correspond à vos critères de recherche.
                            </p>
                            <div class="mt-4">
                                <a href="{{ route('products.index') }}" class="btn btn-success me-2">
                                    <i class="fas fa-arrow-left me-2"></i>Voir tous les produits
                                </a>
                                <a href="{{ route('welcome') }}" class="btn btn-outline-success">
                                    <i class="fas fa-home me-2"></i>Retour à l'accueil
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Message d'information pour les livraisons -->
            @if(isset($products) && $products->count() > 0)
                <div class="row mt-5">
                    <div class="col-12">
                        <div class="alert alert-info border-0 shadow-sm">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-truck fa-2x text-info me-3"></i>
                                <div>
                                    <h6 class="alert-heading mb-1">
                                        <i class="fas fa-gift me-2"></i>Livraison gratuite
                                    </h6>
                                    <p class="mb-0">
                                        Pour toute commande supérieure à <strong>25€</strong>, 
                                        profitez de la livraison gratuite directement chez vous !
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal de confirmation de connexion -->
<div class="modal fade" id="loginPromptModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">
                    <i class="fas fa-user-circle text-success me-2"></i>
                    Connexion requise
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <div class="mb-4">
                    <i class="fas fa-shopping-cart fa-3x text-muted"></i>
                </div>
                <h6 class="mb-3">Connectez-vous pour profiter de toutes nos fonctionnalités</h6>
                <p class="text-muted">
                    Pour ajouter des produits au panier, gérer vos favoris et passer commande, 
                    vous devez être connecté.
                </p>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <a href="{{ route('login') }}" class="btn btn-success me-2">
                    <i class="fas fa-sign-in-alt me-1"></i>Se connecter
                </a>
                <a href="{{ route('register') }}" class="btn btn-outline-success">
                    <i class="fas fa-user-plus me-1"></i>Créer un compte
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal d'achat rapide -->
<div class="modal fade" id="buyNowModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">
                    <i class="fas fa-bolt text-warning me-2"></i>
                    Achat rapide
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="buyNowContent">
                    <!-- Le contenu sera injecté dynamiquement -->
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Annuler
                </button>
                <button type="button" class="btn btn-success" onclick="processBuyNow()">
                    <i class="fas fa-credit-card me-1"></i>Finaliser l'achat
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de location rapide -->
<div class="modal fade" id="rentNowModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">
                    <i class="fas fa-calendar-check text-info me-2"></i>
                    Réservation rapide
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="rentNowContent">
                    <!-- Le contenu sera injecté dynamiquement -->
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Annuler
                </button>
                <button type="button" class="btn btn-info" onclick="processRentNow()">
                    <i class="fas fa-calendar-plus me-1"></i>Réserver
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>
.product-card {
    transition: all 0.3s ease;
    border-radius: 10px;
    overflow: hidden;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important;
}

.card-img-top {
    transition: transform 0.3s ease;
}

.product-card:hover .card-img-top {
    transform: scale(1.05);
}

.btn-sm {
    font-size: 0.875rem;
    padding: 0.375rem 0.75rem;
}

.badge {
    font-size: 0.75rem;
}
</style>

<script>
// Fonction pour ajouter au panier
function addToCart(productId, type = 'purchase') {
    // TODO: Implémenter la logique d'ajout au panier avec type
    const actionText = type === 'rental' ? 'réservé' : 'ajouté au panier';
    
    fetch(`/api/cart/add`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: 1,
            type: type
        })
    })
    .then(response => {
        if (response.ok) {
            showToast(`Produit ${actionText} !`, 'success');
        } else {
            showToast(`Erreur lors de l'ajout`, 'error');
        }
    })
    .catch(error => {
        // En attendant l'implémentation complète
        showToast(`Produit ${actionText} ! (Simulation)`, 'success');
    });
}

// Fonction pour louer maintenant
function rentNow(productId) {
    // Afficher une modal de réservation
    showRentNowModal(productId);
}

// Fonction pour acheter maintenant
function buyNow(productId) {
    // Afficher une modal de confirmation avec options de quantité
    showBuyNowModal(productId);
}

// Fonction pour ajouter aux favoris
function addToWishlist(productId) {
    // TODO: Implémenter la logique de wishlist
    showToast('Produit ajouté aux favoris ! (Simulation)', 'success');
}

// Fonction pour partager un produit
function shareProduct(productId) {
    if (navigator.share) {
        navigator.share({
            title: 'Regardez ce produit sur FarmShop',
            url: window.location.href
        });
    } else {
        // Copier l'URL dans le presse-papier
        navigator.clipboard.writeText(window.location.href)
            .then(() => showToast('Lien copié dans le presse-papier !', 'info'));
    }
}

// Fonction pour afficher le modal de connexion
function showLoginPrompt() {
    const modal = new bootstrap.Modal(document.getElementById('loginPromptModal'));
    modal.show();
}

// Fonction pour afficher le modal de location rapide
function showRentNowModal(productId) {
    // Récupérer les informations du produit depuis la page
    const productCard = document.querySelector(`[data-product-id="${productId}"]`);
    if (!productCard) {
        showToast('Erreur lors du chargement du produit', 'error');
        return;
    }

    // Extraire les informations du produit depuis le DOM
    const productName = productCard.querySelector('.card-title').textContent;
    const productPriceElement = productCard.querySelector('.h5.text-info');
    const productPrice = productPriceElement ? productPriceElement.textContent : '0€';
    const productImage = productCard.querySelector('.card-img-top').src;
    const productStock = parseInt(productCard.querySelector('[data-stock]').dataset.stock);

    const product = {
        id: productId,
        name: productName,
        price: productPrice,
        image: productImage,
        stock: productStock
    };

    displayRentNowModal(product);
}

// Fonction pour afficher le contenu du modal de location
function displayRentNowModal(product) {
    const today = new Date();
    const tomorrow = new Date(today);
    tomorrow.setDate(tomorrow.getDate() + 1);
    const maxDate = new Date(today);
    maxDate.setDate(maxDate.getDate() + 30); // Max 30 jours

    const content = `
        <div class="row">
            <div class="col-md-4">
                <img src="${product.image}" class="img-fluid rounded" alt="${product.name}">
            </div>
            <div class="col-md-8">
                <h6>${product.name}</h6>
                <p class="text-info h5">${product.price}/jour</p>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="startDate" class="form-label">Date de début</label>
                        <input type="date" 
                               class="form-control" 
                               id="startDate" 
                               min="${tomorrow.toISOString().split('T')[0]}" 
                               max="${maxDate.toISOString().split('T')[0]}"
                               onchange="updateRentalPrice()">
                    </div>
                    <div class="col-md-6">
                        <label for="endDate" class="form-label">Date de fin</label>
                        <input type="date" 
                               class="form-control" 
                               id="endDate" 
                               min="${tomorrow.toISOString().split('T')[0]}" 
                               max="${maxDate.toISOString().split('T')[0]}"
                               onchange="updateRentalPrice()">
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Nombre de jours:</span>
                        <span id="rentalDays">0</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Prix location:</span>
                        <span id="rentalPrice">0€</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Caution:</span>
                        <span id="depositAmount">À définir</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Total:</span>
                        <span class="text-info" id="totalRentalPrice">0€</span>
                    </div>
                </div>

                <div class="alert alert-info small">
                    <i class="fas fa-info-circle me-2"></i>
                    La caution sera restituée après retour du matériel en bon état.
                </div>
            </div>
        </div>
    `;

    document.getElementById('rentNowContent').innerHTML = content;
    
    // Stocker l'ID du produit pour la finalisation
    document.getElementById('rentNowModal').dataset.productId = product.id;
    
    const modal = new bootstrap.Modal(document.getElementById('rentNowModal'));
    modal.show();
}

// Fonction pour mettre à jour le prix de location
function updateRentalPrice() {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    
    if (startDate && endDate) {
        const start = new Date(startDate);
        const end = new Date(endDate);
        const diffTime = Math.abs(end - start);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        
        if (diffDays > 0) {
            const pricePerDay = parseFloat(document.querySelector('#rentNowContent .text-info.h5').textContent.replace('€/jour', ''));
            const totalPrice = pricePerDay * diffDays;
            const deposit = Math.min(totalPrice * 0.5, 200); // Caution = 50% du prix ou max 200€
            
            document.getElementById('rentalDays').textContent = diffDays;
            document.getElementById('rentalPrice').textContent = totalPrice.toFixed(2) + '€';
            document.getElementById('depositAmount').textContent = deposit.toFixed(2) + '€';
            document.getElementById('totalRentalPrice').textContent = (totalPrice + deposit).toFixed(2) + '€';
        }
    }
}

// Fonction pour traiter la location
function processRentNow() {
    const productId = document.getElementById('rentNowModal').dataset.productId;
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;

    if (!startDate || !endDate) {
        showToast('Veuillez sélectionner les dates de location', 'error');
        return;
    }

    // TODO: Implémenter la logique de réservation
    fetch(`/api/rentals/book`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            product_id: productId,
            start_date: startDate,
            end_date: endDate
        })
    })
    .then(response => {
        if (response.ok) {
            showToast('Réservation effectuée avec succès !', 'success');
            bootstrap.Modal.getInstance(document.getElementById('rentNowModal')).hide();
        } else {
            showToast('Erreur lors de la réservation', 'error');
        }
    })
    .catch(error => {
        // En attendant l'implémentation complète
        const days = document.getElementById('rentalDays').textContent;
        showToast(`Réservation simulée: ${days} jours (du ${startDate} au ${endDate})`, 'success');
        bootstrap.Modal.getInstance(document.getElementById('rentNowModal')).hide();
    });
}
    // Récupérer les informations du produit depuis la page
    const productCard = document.querySelector(`[data-product-id="${productId}"]`);
    if (!productCard) {
        // Fallback: récupérer via AJAX si nécessaire
        fetch(`/api/products/${productId}`)
            .then(response => response.json())
            .then(product => {
                displayBuyNowModal(product);
            })
            .catch(error => {
                showToast('Erreur lors du chargement du produit', 'error');
            });
        return;
    }

    // Extraire les informations du produit depuis le DOM
    const productName = productCard.querySelector('.card-title').textContent;
    const productPrice = productCard.querySelector('.h5.text-success').textContent;
    const productImage = productCard.querySelector('.card-img-top').src;
    const productStock = parseInt(productCard.querySelector('[data-stock]').dataset.stock);

    const product = {
        id: productId,
        name: productName,
        price: productPrice,
        image: productImage,
        stock: productStock
    };

    displayBuyNowModal(product);
}

// Fonction pour afficher le contenu du modal d'achat
function displayBuyNowModal(product) {
    const content = `
        <div class="row">
            <div class="col-md-4">
                <img src="${product.image}" class="img-fluid rounded" alt="${product.name}">
            </div>
            <div class="col-md-8">
                <h6>${product.name}</h6>
                <p class="text-success h5">${product.price}</p>
                
                <div class="mb-3">
                    <label for="buyNowQuantity" class="form-label">Quantité</label>
                    <div class="input-group" style="max-width: 150px;">
                        <button class="btn btn-outline-secondary" type="button" onclick="changeQuantity(-1)">
                            <i class="fas fa-minus"></i>
                        </button>
                        <input type="number" 
                               class="form-control text-center" 
                               id="buyNowQuantity" 
                               value="1" 
                               min="1" 
                               max="${product.stock || 99}">
                        <button class="btn btn-outline-secondary" type="button" onclick="changeQuantity(1)">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    <small class="text-muted">Stock disponible: ${product.stock || 'N/A'}</small>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Total:</span>
                        <span class="fw-bold text-success" id="totalPrice">${product.price}</span>
                    </div>
                </div>
            </div>
        </div>
    `;

    document.getElementById('buyNowContent').innerHTML = content;
    
    // Stocker l'ID du produit pour la finalisation
    document.getElementById('buyNowModal').dataset.productId = product.id;
    
    const modal = new bootstrap.Modal(document.getElementById('buyNowModal'));
    modal.show();

    // Mettre à jour le prix total quand la quantité change
    document.getElementById('buyNowQuantity').addEventListener('input', updateTotalPrice);
}

// Fonction pour changer la quantité
function changeQuantity(delta) {
    const quantityInput = document.getElementById('buyNowQuantity');
    const currentValue = parseInt(quantityInput.value);
    const newValue = Math.max(1, Math.min(currentValue + delta, parseInt(quantityInput.max)));
    quantityInput.value = newValue;
    updateTotalPrice();
}

// Fonction pour mettre à jour le prix total
function updateTotalPrice() {
    const quantity = parseInt(document.getElementById('buyNowQuantity').value);
    const priceText = document.querySelector('#buyNowContent .text-success.h5').textContent;
    const priceValue = parseFloat(priceText.replace('€', '').replace(',', '.'));
    const total = (priceValue * quantity).toFixed(2);
    document.getElementById('totalPrice').textContent = total + '€';
}

// Fonction pour traiter l'achat
function processBuyNow() {
    const productId = document.getElementById('buyNowModal').dataset.productId;
    const quantity = parseInt(document.getElementById('buyNowQuantity').value);

    // TODO: Implémenter la logique d'achat direct
    // Pour l'instant, simulation
    
    fetch(`/api/orders/buy-now`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: quantity
        })
    })
    .then(response => {
        if (response.ok) {
            showToast('Achat effectué avec succès !', 'success');
            // Fermer le modal
            bootstrap.Modal.getInstance(document.getElementById('buyNowModal')).hide();
            // Rediriger vers la page de confirmation ou de paiement
            // window.location.href = '/orders/confirmation';
        } else {
            showToast('Erreur lors de l\'achat', 'error');
        }
    })
    .catch(error => {
        // En attendant l'implémentation complète
        showToast(`Achat simulé: ${quantity}x produit ${productId}`, 'success');
        bootstrap.Modal.getInstance(document.getElementById('buyNowModal')).hide();
    });
}

// Fonction pour afficher les notifications toast
function showToast(message, type = 'success') {
    // Créer un élément toast
    const toastContainer = document.querySelector('.toast-container') || createToastContainer();
    
    const toastElement = document.createElement('div');
    toastElement.className = `toast align-items-center text-white bg-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} border-0`;
    toastElement.setAttribute('role', 'alert');
    toastElement.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    toastContainer.appendChild(toastElement);
    
    const toast = new bootstrap.Toast(toastElement);
    toast.show();
    
    // Supprimer le toast après qu'il soit caché
    toastElement.addEventListener('hidden.bs.toast', () => {
        toastElement.remove();
    });
}

// Créer le conteneur de toast s'il n'existe pas
function createToastContainer() {
    const container = document.createElement('div');
    container.className = 'toast-container position-fixed top-0 end-0 p-3';
    container.style.zIndex = '9999';
    document.body.appendChild(container);
    return container;
}

// Auto-submit du formulaire de filtre avec debounce
let filterTimeout;
document.querySelectorAll('#search, #price_min, #price_max').forEach(input => {
    input.addEventListener('input', function() {
        clearTimeout(filterTimeout);
        filterTimeout = setTimeout(() => {
            this.closest('form').submit();
        }, 1000); // Attendre 1 seconde après la dernière saisie
    });
});

// Submit immédiat pour les selects
document.querySelectorAll('#category, #product_type').forEach(select => {
    select.addEventListener('change', function() {
        this.closest('form').submit();
    });
});
</script>
@endsection
