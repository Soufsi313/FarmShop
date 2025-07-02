@extends('layouts.public')

@section('title', 'Nos Produits Locaux - FarmShop')

@section('content')
<div class="container py-5">
    <!-- Bouton de retour à l'accueil -->
    <div class="row mb-3">
        <div class="col-12">
            <a href="{{ route('welcome') }}" class="btn btn-outline-success">
                <i class="fas fa-arrow-left me-2"></i>Retour à l'accueil
            </a>
        </div>
    </div>

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
                            <div class="card product-card h-100 shadow-sm border-0 @if($product->hasActiveSpecialOffer()) product-card-special @endif" 
                                 data-product-id="{{ $product->id }}" 
                                 data-stock="{{ $product->quantity }}"
                                 data-rental-price="{{ $product->rental_price_per_day ?? 0 }}"
                                 data-deposit-amount="{{ $product->deposit_amount ?? 0 }}"
                                 data-min-rental-days="{{ $product->min_rental_days ?? 1 }}"
                                 data-max-rental-days="{{ $product->max_rental_days ?? 30 }}">
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
                                        @if($product->hasActiveSpecialOffer())
                                            @php
                                                $offer = $product->getActiveSpecialOffer();
                                            @endphp
                                            <span class="badge special-offer-badge promo-badge d-block mb-1 fs-6">
                                                <i class="fas fa-fire fire-icon me-1"></i>-{{ $offer->discount_percentage }}%
                                            </span>
                                        @endif
                                        @if($product->category)
                                            <span class="badge bg-secondary d-block mb-1">
                                                {{ $product->category->name }}
                                            </span>
                                        @endif
                                        @if($product->is_featured)
                                            <span class="badge bg-warning d-block">
                                                <i class="fas fa-star me-1"></i>Vedette
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Badge type de produit (en bas à gauche) -->
                                    <div class="position-absolute bottom-0 start-0 m-2">
                                        @if($product->is_rentable && $product->price <= 0)
                                            <span class="badge bg-info">
                                                <i class="fas fa-calendar-alt me-1"></i>Location
                                            </span>
                                        @elseif($product->price > 0 && !$product->is_rentable)
                                            <span class="badge bg-success">
                                                <i class="fas fa-shopping-cart me-1"></i>Achat
                                            </span>
                                        @elseif($product->price > 0 && $product->is_rentable)
                                            <span class="badge bg-warning">
                                                <i class="fas fa-star me-1"></i>Achat + Location
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
                                        <h5 class="card-title mb-0 flex-grow-1">{{ $product->name }}</h5>
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
                                    
                                    <p class="card-text text-muted flex-grow-1 small mb-3">
                                        {{ Str::limit($product->description, 80) }}
                                    </p>
                                    
                                    <!-- Informations prix et location -->
                                    <div class="mb-3">
                                        @if($product->is_rentable && $product->rental_price_per_day > 0)
                                            <div class="text-center rental-price-box rounded p-2 mb-2">
                                                <div class="h5 text-info mb-0 fw-bold">{{ number_format($product->rental_price_per_day, 2) }}€<small class="fs-6">/jour</small></div>
                                                @if($product->deposit_amount > 0)
                                                    <small class="text-muted">+ {{ number_format($product->deposit_amount, 2) }}€ de caution</small>
                                                @endif
                                                @if($product->price > 0)
                                                    <div class="mt-1">
                                                        @if($product->hasActiveSpecialOffer())
                                                            @php
                                                                $offer = $product->getActiveSpecialOffer();
                                                                $discountedPrice = $product->price * (1 - $offer->discount_percentage / 100);
                                                            @endphp
                                                            <small class="text-decoration-line-through text-muted">Achat: {{ number_format($product->price, 2) }}€/{{ $product->unit_symbol }}</small><br>
                                                            <small class="text-danger fw-bold">PROMO: {{ number_format($discountedPrice, 2) }}€/{{ $product->unit_symbol }}</small><br>
                                                            <small class="text-success">-{{ $offer->discount_percentage }}% dès {{ $offer->min_quantity }}{{ $product->unit_symbol }}</small>
                                                        @else
                                                            <small class="text-success">Achat: {{ number_format($product->price, 2) }}€/{{ $product->unit_symbol }}</small>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            <div class="text-center">
                                                @if($product->hasActiveSpecialOffer())
                                                    @php
                                                        $offer = $product->getActiveSpecialOffer();
                                                        $discountedPrice = $product->price * (1 - $offer->discount_percentage / 100);
                                                    @endphp
                                                    <div class="d-flex align-items-center justify-content-center flex-wrap gap-1">
                                                        <span class="text-decoration-line-through text-muted">{{ number_format($product->price, 2) }}€</span>
                                                        <span class="h5 text-danger mb-0 fw-bold">{{ number_format($discountedPrice, 2) }}€</span>
                                                        <small class="text-muted">/{{ $product->unit_symbol }}</small>
                                                    </div>
                                                    <small class="text-success">
                                                        <i class="fas fa-gift me-1"></i>-{{ $offer->discount_percentage }}% dès {{ $offer->min_quantity }}{{ $product->unit_symbol }}
                                                    </small>
                                                @else
                                                    <span class="h5 text-success mb-0 fw-bold">{{ number_format($product->price, 2) }}€</span>
                                                    <small class="text-muted">/{{ $product->unit_symbol }}</small>
                                                @endif
                                            </div>
                                        @endif
                                        
                                        <!-- Stock -->
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">Stock: {{ $product->quantity }}</small>
                                            @if($product->quantity <= $product->critical_stock_threshold && $product->quantity > 0)
                                                <small class="text-warning">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>Dernières pièces !
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Actions -->
                                    <div class="d-grid gap-2">
                                        <!-- Bouton Voir détails (toujours visible) -->
                                        <a href="{{ route('products.show', $product->slug) }}" 
                                           class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye me-1"></i>Voir détails
                                        </a>
                                        
                                        @auth
                                            <!-- Actions pour utilisateurs connectés -->
                                            @if($product->quantity > 0)
                                                @if($product->is_rentable && $product->price <= 0)
                                                    <!-- Produit location uniquement -->
                                                    <div class="btn-group" role="group">
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
                                                    <!-- Sélecteur de quantité -->
                                                    <div class="mb-2">
                                                        <label class="form-label small mb-1">Quantité</label>
                                                        <div class="input-group input-group-sm">
                                                            <button class="btn btn-outline-secondary" type="button" 
                                                                    onclick="changeProductQuantity({{ $product->id }}, -1)">
                                                                <i class="fas fa-minus"></i>
                                                            </button>
                                                            <input type="number" 
                                                                   class="form-control text-center" 
                                                                   id="quantity-{{ $product->id }}"
                                                                   value="1" 
                                                                   min="1" 
                                                                   max="{{ $product->quantity }}">
                                                            <button class="btn btn-outline-secondary" type="button" 
                                                                    onclick="changeProductQuantity({{ $product->id }}, 1)">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <!-- Boutons d'action -->
                                                    <div class="btn-group w-100" role="group">
                                                        <button class="btn btn-outline-success btn-sm" 
                                                                onclick="addToCartWithQuantity({{ $product->id }})">
                                                            <i class="fas fa-shopping-cart me-1"></i>Panier
                                                        </button>
                                                        <button class="btn btn-success btn-sm" 
                                                                onclick="buyNow({{ $product->id }})">
                                                            <i class="fas fa-bolt me-1"></i>Acheter
                                                        </button>
                                                    </div>
                                                @else
                                                    <!-- Produit achat + location -->
                                                    <!-- Sélecteur de quantité -->
                                                    <div class="mb-2">
                                                        <label class="form-label small mb-1">Quantité</label>
                                                        <div class="input-group input-group-sm">
                                                            <button class="btn btn-outline-secondary" type="button" 
                                                                    onclick="changeProductQuantity({{ $product->id }}, -1)">
                                                                <i class="fas fa-minus"></i>
                                                            </button>
                                                            <input type="number" 
                                                                   class="form-control text-center" 
                                                                   id="quantity-{{ $product->id }}"
                                                                   value="1" 
                                                                   min="1" 
                                                                   max="{{ $product->quantity }}">
                                                            <button class="btn btn-outline-secondary" type="button" 
                                                                    onclick="changeProductQuantity({{ $product->id }}, 1)">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <!-- Boutons d'action -->
                                                    <div class="row g-1">
                                                        <div class="col-6">
                                                            <button class="btn btn-outline-success btn-sm w-100" 
                                                                    onclick="addToCartWithQuantity({{ $product->id }})">
                                                                <i class="fas fa-shopping-cart me-1"></i>Panier
                                                            </button>
                                                        </div>
                                                        <div class="col-6">
                                                            <button class="btn btn-success btn-sm w-100" 
                                                                    onclick="buyNow({{ $product->id }})">
                                                                <i class="fas fa-bolt me-1"></i>Acheter
                                                            </button>
                                                        </div>
                                                        <div class="col-12 mt-1">
                                                            <button class="btn btn-info btn-sm w-100" 
                                                                    onclick="rentNow({{ $product->id }})">
                                                                <i class="fas fa-calendar-check me-1"></i>Louer
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endif
                                            @else
                                                <button class="btn btn-secondary btn-sm" disabled>
                                                    <i class="fas fa-ban me-1"></i>Rupture de stock
                                                </button>
                                            @endif
                                        @else
                                            <!-- Message pour visiteurs non connectés -->
                                            <button class="btn btn-outline-success btn-sm" 
                                                    onclick="showLoginPrompt()">
                                                <i class="fas fa-user-plus me-1"></i>Se connecter pour commander
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

<!-- Modal d'ajout au panier avec sélection de quantité -->
<div class="modal fade" id="addToCartModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">
                    <i class="fas fa-shopping-cart text-success me-2"></i>
                    Ajouter au panier
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="addToCartContent">
                    <!-- Le contenu sera injecté dynamiquement -->
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Annuler
                </button>
                <button type="button" class="btn btn-success" onclick="processAddToCart()">
                    <i class="fas fa-shopping-cart me-1"></i>Ajouter au panier
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
    min-height: 500px; /* Hauteur minimale pour uniformiser */
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important;
}

.card-img-top {
    transition: transform 0.3s ease;
    height: 250px;
    object-fit: cover;
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
    font-weight: 500;
}

/* Amélioration de l'affichage des prix pour les locations */
.rental-price-box {
    background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
    border: 1px solid #b3e5fc;
}

/* Meilleur contraste pour les badges en bas */
.position-absolute.bottom-0 .badge {
    backdrop-filter: blur(4px);
    background-color: rgba(var(--bs-info-rgb), 0.9) !important;
}

.position-absolute.bottom-0 .badge.bg-success {
    background-color: rgba(var(--bs-success-rgb), 0.9) !important;
}

.position-absolute.bottom-0 .badge.bg-warning {
    background-color: rgba(var(--bs-warning-rgb), 0.9) !important;
}
</style>

<script>
// Fonction pour ajouter au panier
function addToCart(productId, type = 'purchase') {
    const actionText = type === 'rental' ? 'réservé' : 'ajouté au panier';
    
    fetch(`/api/cart/add`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: 1,
            type: type
        })
    })
    .then(response => {
        console.log('Réponse API:', response.status, response.statusText);
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Données reçues:', data);
        if (data.success) {
            showToast(`Produit ${actionText} !`, 'success');
            // Mettre à jour le compteur du panier
            if (window.updateCartCount) {
                window.updateCartCount();
            }
            // Rediriger vers le panier si type = 'purchase'
            if (type === 'purchase') {
                window.location.href = '/panier';
            }
        } else {
            showToast(data.message || `Erreur lors de l'ajout`, 'error');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showToast(`Erreur lors de l'ajout au panier: ${error.message}`, 'error');
    });
}

// Fonction pour louer maintenant
function rentNow(productId) {
    // Afficher une modal de réservation
    showRentNowModal(productId);
}

// Fonction pour acheter maintenant
function buyNow(productId) {
    // Ajouter au panier puis rediriger
    fetch(`/api/cart/add`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: 1
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showToast('Produit ajouté au panier !', 'success');
            // Rediriger immédiatement vers le panier
            window.location.href = '/panier';
        } else {
            showToast(data.message || 'Erreur lors de l\'ajout', 'error');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showToast(`Erreur lors de l'ajout au panier: ${error.message}`, 'error');
    });
}

// Fonction pour ajouter au panier et rediriger
function addToCartAndRedirect(productId) {
    fetch(`/api/cart/add`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: 1
        })
    })
    .then(response => {
        console.log('Réponse API:', response.status, response.statusText);
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Données reçues:', data);
        if (data.success) {
            showToast('Produit ajouté au panier !', 'success');
            // Mettre à jour le compteur du panier
            if (window.updateCartCount) {
                window.updateCartCount();
            }
            // Rediriger vers le panier
            setTimeout(() => {
                window.location.href = '/panier';
            }, 500); // Petit délai pour voir le toast
        } else {
            showToast(data.message || 'Erreur lors de l\'ajout', 'error');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showToast(`Erreur lors de l'ajout au panier: ${error.message}`, 'error');
    });
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
        console.error('Product card not found for ID:', productId);
        return;
    }

    // Extraire les informations du produit depuis le DOM
    const productNameElement = productCard.querySelector('.card-title');
    if (!productNameElement) {
        showToast('Erreur: nom du produit non trouvé', 'error');
        console.error('Product name element not found in card:', productCard);
        return;
    }
    const productName = productNameElement.textContent.trim();
    
    // Chercher le prix de location (element avec class h5 text-info)
    const productPriceElement = productCard.querySelector('.h5.text-info');
    let productPrice = '0€/jour';
    if (productPriceElement) {
        productPrice = productPriceElement.textContent.trim();
    }
    
    // Récupérer l'image
    const productImageElement = productCard.querySelector('.card-img-top');
    let productImage = '';
    if (productImageElement) {
        productImage = productImageElement.src || productImageElement.dataset.src || '';
    }
    
    // Récupérer toutes les données du produit depuis les attributs data-*
    const productStock = parseInt(productCard.dataset.stock) || 0;
    const rentalPrice = parseFloat(productCard.dataset.rentalPrice) || 0;
    const depositAmount = parseFloat(productCard.dataset.depositAmount) || 0;
    const minRentalDays = parseInt(productCard.dataset.minRentalDays) || 1;
    const maxRentalDays = parseInt(productCard.dataset.maxRentalDays) || 30;

    const product = {
        id: productId,
        name: productName,
        price: productPrice,
        image: productImage,
        stock: productStock,
        rentalPricePerDay: rentalPrice,
        depositAmount: depositAmount,
        minRentalDays: minRentalDays,
        maxRentalDays: maxRentalDays
    };

    console.log('Product data extracted for rental:', product);
    displayRentNowModal(product);
}

// Fonction pour afficher le contenu du modal de location
function displayRentNowModal(product) {
    const today = new Date();
    const tomorrow = new Date(today);
    tomorrow.setDate(tomorrow.getDate() + 1);
    
    // La date minimum est demain (pas de location le jour même)
    const minDate = tomorrow;
    
    // Calculer la date maximum possible selon les contraintes du produit
    // On peut commencer demain au plus tôt et louer pour la durée maximum
    const maxDateFromProduct = new Date(tomorrow);
    maxDateFromProduct.setDate(maxDateFromProduct.getDate() + product.maxRentalDays);
    
    // Format des dates pour les inputs HTML
    const minDateStr = minDate.toISOString().split('T')[0];
    const maxDateStr = maxDateFromProduct.toISOString().split('T')[0];

    const content = `
        <div class="row">
            <div class="col-md-4">
                <img src="${product.image}" class="img-fluid rounded" alt="${product.name}">
            </div>
            <div class="col-md-8">
                <h6>${product.name}</h6>
                <p class="text-info h5">${product.rentalPricePerDay}€/jour</p>
                
                <!-- Contraintes de location -->
                <div class="alert alert-info small mb-3">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Contraintes:</strong><br>
                    • Durée minimum: ${product.minRentalDays} jour${product.minRentalDays > 1 ? 's' : ''}<br>
                    • Durée maximum: ${product.maxRentalDays} jours<br>
                    • Location à partir de demain uniquement
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="startDate" class="form-label">Date de début</label>
                        <input type="date" 
                               class="form-control" 
                               id="startDate" 
                               min="${minDateStr}" 
                               max="${maxDateStr}"
                               value="${minDateStr}"
                               onchange="updateRentalPrice()">
                    </div>
                    <div class="col-md-6">
                        <label for="endDate" class="form-label">Date de fin</label>
                        <input type="date" 
                               class="form-control" 
                               id="endDate" 
                               min="${minDateStr}" 
                               max="${maxDateStr}"
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
                        <span id="depositAmount">${product.depositAmount}€</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Total:</span>
                        <span class="text-info" id="totalRentalPrice">${product.depositAmount}€</span>
                    </div>
                    
                    <!-- Message d'erreur pour les contraintes -->
                    <div id="rentalError" class="alert alert-danger mt-2" style="display: none;">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <span id="rentalErrorMessage"></span>
                    </div>
                </div>

                <div class="alert alert-success small">
                    <i class="fas fa-info-circle me-2"></i>
                    La caution sera restituée après retour du matériel en bon état.
                </div>
            </div>
        </div>
    `;

    document.getElementById('rentNowContent').innerHTML = content;
    
    // Stocker les données du produit pour les calculs
    document.getElementById('rentNowModal').dataset.productId = product.id;
    document.getElementById('rentNowModal').dataset.rentalPrice = product.rentalPricePerDay;
    document.getElementById('rentNowModal').dataset.depositAmount = product.depositAmount;
    document.getElementById('rentNowModal').dataset.minRentalDays = product.minRentalDays;
    document.getElementById('rentNowModal').dataset.maxRentalDays = product.maxRentalDays;

    const modal = new bootstrap.Modal(document.getElementById('rentNowModal'));
    modal.show();
    
    // Configurer les contraintes de dates après l'affichage du modal
    setupDateConstraints();
}

// Fonction pour configurer les contraintes de dates
function setupDateConstraints() {
    const startDateInput = document.getElementById('startDate');
    const endDateInput = document.getElementById('endDate');
    
    if (!startDateInput || !endDateInput) return;
    
    // Event listeners pour les changements de dates
    startDateInput.addEventListener('change', function() {
        updateEndDateConstraints();
        updateRentalPrice();
    });
    
    endDateInput.addEventListener('change', function() {
        updateRentalPrice();
    });
    
    // Initialiser les contraintes pour la date de fin
    updateEndDateConstraints();
}

// Fonction pour mettre à jour les contraintes de la date de fin selon la date de début
function updateEndDateConstraints() {
    const startDateInput = document.getElementById('startDate');
    const endDateInput = document.getElementById('endDate');
    const modal = document.getElementById('rentNowModal');
    
    if (!startDateInput || !endDateInput || !modal) return;
    
    const startDate = startDateInput.value;
    if (!startDate) return;
    
    // Récupérer les contraintes du produit
    const minRentalDays = parseInt(modal.dataset.minRentalDays) || 1;
    const maxRentalDays = parseInt(modal.dataset.maxRentalDays) || 30;
    
    // Calculer les dates min et max pour la date de fin
    const start = new Date(startDate);
    
    // Date minimum de fin = date de début + (minimum de jours - 1)
    // Ex: si je commence le 02/07 pour 1 jour minimum, je finis le 02/07 (même jour = 1 jour)
    // Ex: si je commence le 02/07 pour 5 jours minimum, je finis le 06/07 (02+4 = 5 jours au total)
    const minEndDate = new Date(start);
    minEndDate.setDate(start.getDate() + (minRentalDays - 1));
    
    // Date maximum de fin = date de début + (maximum de jours - 1)
    const maxEndDate = new Date(start);
    maxEndDate.setDate(start.getDate() + (maxRentalDays - 1));
    
    // Appliquer les contraintes
    endDateInput.min = minEndDate.toISOString().split('T')[0];
    endDateInput.max = maxEndDate.toISOString().split('T')[0];
    
    // Si la date de fin actuelle ne respecte pas les contraintes, la corriger
    const currentEndDate = endDateInput.value;
    if (currentEndDate) {
        const endDate = new Date(currentEndDate);
        if (endDate < minEndDate) {
            endDateInput.value = minEndDate.toISOString().split('T')[0];
        } else if (endDate > maxEndDate) {
            endDateInput.value = maxEndDate.toISOString().split('T')[0];
        }
    } else {
        // Si pas de date de fin sélectionnée, proposer le minimum
        endDateInput.value = minEndDate.toISOString().split('T')[0];
    }
}

// Fonction pour mettre à jour le prix de location avec validation des contraintes
function updateRentalPrice() {
    const startDateInput = document.getElementById('startDate');
    const endDateInput = document.getElementById('endDate');
    const modal = document.getElementById('rentNowModal');
    const errorDiv = document.getElementById('rentalError');
    const errorMessageSpan = document.getElementById('rentalErrorMessage');
    
    if (!startDateInput || !endDateInput || !modal) return;
    
    const startDate = startDateInput.value;
    const endDate = endDateInput.value;
    
    // Réinitialiser l'affichage
    document.getElementById('rentalDays').textContent = '0';
    document.getElementById('rentalPrice').textContent = '0€';
    
    // Récupérer les données du produit
    const rentalPricePerDay = parseFloat(modal.dataset.rentalPrice) || 0;
    const depositAmount = parseFloat(modal.dataset.depositAmount) || 0;
    const minRentalDays = parseInt(modal.dataset.minRentalDays) || 1;
    const maxRentalDays = parseInt(modal.dataset.maxRentalDays) || 30;
    
    // Mettre à jour l'affichage de la caution (fixe)
    document.getElementById('depositAmount').textContent = depositAmount.toFixed(2) + '€';
    
    if (!startDate || !endDate) {
        document.getElementById('totalRentalPrice').textContent = depositAmount.toFixed(2) + '€';
        errorDiv.style.display = 'none';
        return;
    }
    
    const start = new Date(startDate);
    const end = new Date(endDate);
    const today = new Date();
    today.setHours(0, 0, 0, 0); // Reset time for date comparison
    
    // Validation 1: Date de début doit être au minimum demain
    if (start <= today) {
        errorMessageSpan.textContent = 'La location ne peut pas commencer aujourd\'hui. Choisissez une date à partir de demain.';
        errorDiv.style.display = 'block';
        document.getElementById('totalRentalPrice').textContent = depositAmount.toFixed(2) + '€';
        return;
    }
    
    // Validation 2: Date de fin doit être >= date de début
    if (end < start) {
        errorMessageSpan.textContent = 'La date de fin doit être postérieure ou égale à la date de début.';
        errorDiv.style.display = 'block';
        document.getElementById('totalRentalPrice').textContent = depositAmount.toFixed(2) + '€';
        return;
    }
    
    // Calculer le nombre de jours (incluant le jour de début et de fin)
    // Ex: du 02/07 au 02/07 = 1 jour, du 02/07 au 06/07 = 5 jours
    const diffTime = Math.abs(end - start);
    const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24)) + 1; // +1 pour inclure le jour de début
    
    // Validation 3: Respecter la durée minimum
    if (diffDays < minRentalDays) {
        errorMessageSpan.textContent = `La durée minimum de location est de ${minRentalDays} jour${minRentalDays > 1 ? 's' : ''}.`;
        errorDiv.style.display = 'block';
        document.getElementById('totalRentalPrice').textContent = depositAmount.toFixed(2) + '€';
        return;
    }
    
    // Validation 4: Respecter la durée maximum
    if (diffDays > maxRentalDays) {
        errorMessageSpan.textContent = `La durée maximum de location est de ${maxRentalDays} jours.`;
        errorDiv.style.display = 'block';
        document.getElementById('totalRentalPrice').textContent = depositAmount.toFixed(2) + '€';
        return;
    }
    
    // Tout est valide, calculer le prix
    errorDiv.style.display = 'none';
    
    const totalRentalPrice = rentalPricePerDay * diffDays;
    const grandTotal = totalRentalPrice + depositAmount;
    
    // Mettre à jour l'affichage
    document.getElementById('rentalDays').textContent = diffDays;
    document.getElementById('rentalPrice').textContent = totalRentalPrice.toFixed(2) + '€';
    document.getElementById('totalRentalPrice').textContent = grandTotal.toFixed(2) + '€';
}

// Fonction pour traiter la location
function processRentNow() {
    const modal = document.getElementById('rentNowModal');
    const productId = modal.dataset.productId;
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;

    if (!startDate || !endDate) {
        showToast('Veuillez sélectionner les dates de location', 'error');
        return;
    }

    // Récupérer les contraintes du produit
    const minRentalDays = parseInt(modal.dataset.minRentalDays) || 1;
    const maxRentalDays = parseInt(modal.dataset.maxRentalDays) || 30;

    const start = new Date(startDate);
    const end = new Date(endDate);
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    // Validation complète avant envoi
    
    // 1. Date de début doit être au minimum demain
    if (start <= today) {
        showToast('La location ne peut pas commencer aujourd\'hui. Choisissez une date à partir de demain.', 'error');
        return;
    }
    
    // 2. Date de fin >= date de début
    if (end < start) {
        showToast('La date de fin doit être postérieure ou égale à la date de début', 'error');
        return;
    }
    
    // 3. Calculer le nombre de jours et vérifier les contraintes
    // Ex: du 02/07 au 02/07 = 1 jour, du 02/07 au 06/07 = 5 jours
    const diffTime = Math.abs(end - start);
    const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24)) + 1; // +1 pour inclure le jour de début
    
    if (diffDays < minRentalDays) {
        showToast(`La durée minimum de location est de ${minRentalDays} jour${minRentalDays > 1 ? 's' : ''}.`, 'error');
        return;
    }
    
    if (diffDays > maxRentalDays) {
        showToast(`La durée maximum de location est de ${maxRentalDays} jours.`, 'error');
        return;
    }

    // Vérifier qu'il n'y a pas d'erreur affichée
    const errorDiv = document.getElementById('rentalError');
    if (errorDiv && errorDiv.style.display !== 'none') {
        showToast('Veuillez corriger les erreurs avant de continuer', 'error');
        return;
    }

    // Tout est valide, envoyer la demande
    fetch(`/panier-location/ajouter`, {
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
        console.log('Réponse ajout panier location:', response.status, response.statusText);
        if (!response.ok) {
            return response.text().then(text => {
                console.error('Erreur contenu:', text);
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            });
        }
        return response.json();
    })
    .then(data => {
        console.log('Données reçues:', data);
        if (data.success) {
            const daysText = diffDays === 1 ? 'jour' : 'jours';
            showToast(`Produit ajouté au panier de location pour ${diffDays} ${daysText} !`, 'success');
            bootstrap.Modal.getInstance(document.getElementById('rentNowModal')).hide();
            
            // Mettre à jour le compteur du panier de location
            if (window.updateRentalCartCount) {
                window.updateRentalCartCount();
            }
        } else {
            showToast(data.message || 'Erreur lors de l\'ajout au panier de location', 'error');
        }
    })
    .catch(error => {
        console.error('Erreur lors de l\'ajout au panier de location:', error);
        showToast('Erreur lors de l\'ajout au panier de location: ' + error.message, 'error');
    });
}

// Fonction pour afficher le modal d'achat rapide
function showBuyNowModal(productId) {
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

// Fonction pour ajouter au panier avec sélection de quantité
function addToCartWithQuantity(productId) {
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

    displayAddToCartModal(product);
}

// Fonction pour afficher le contenu du modal d'ajout au panier
function displayAddToCartModal(product) {
    const content = `
        <div class="row">
            <div class="col-md-4">
                <img src="${product.image}" class="img-fluid rounded" alt="${product.name}">
            </div>
            <div class="col-md-8">
                <h6>${product.name}</h6>
                <p class="text-success h5">${product.price}</p>
                
                <div class="mb-3">
                    <label for="addToCartQuantity" class="form-label">Quantité désirée</label>
                    <div class="input-group" style="max-width: 200px;">
                        <button class="btn btn-outline-secondary" type="button" onclick="changeAddToCartQuantity(-1)">
                            <i class="fas fa-minus"></i>
                        </button>
                        <input type="number" 
                               class="form-control text-center" 
                               id="addToCartQuantity" 
                               value="1" 
                               min="1" 
                               max="${product.stock}">
                        <button class="btn btn-outline-secondary" type="button" onclick="changeAddToCartQuantity(1)">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    <small class="text-muted">Stock disponible: ${product.stock}</small>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Total:</span>
                        <span class="fw-bold text-success" id="addToCartTotalPrice">${product.price}</span>
                    </div>
                </div>
            </div>
        </div>
    `;

    document.getElementById('addToCartContent').innerHTML = content;
    
    // Stocker l'ID du produit pour la finalisation
    document.getElementById('addToCartModal').dataset.productId = product.id;
    
    const modal = new bootstrap.Modal(document.getElementById('addToCartModal'));
    modal.show();

    // Mettre à jour le prix total quand la quantité change
    document.getElementById('addToCartQuantity').addEventListener('input', updateAddToCartTotalPrice);
}

// Fonction pour changer la quantité dans le modal d'ajout au panier
function changeAddToCartQuantity(delta) {
    const quantityInput = document.getElementById('addToCartQuantity');
    const currentValue = parseInt(quantityInput.value);
    const newValue = Math.max(1, Math.min(currentValue + delta, parseInt(quantityInput.max)));
    quantityInput.value = newValue;
    updateAddToCartTotalPrice();
}

// Fonction pour mettre à jour le prix total dans le modal d'ajout au panier
function updateAddToCartTotalPrice() {
    const quantity = parseInt(document.getElementById('addToCartQuantity').value);
    const priceText = document.querySelector('#addToCartContent .text-success.h5').textContent;
    const priceValue = parseFloat(priceText.replace('€', '').replace(',', '.'));
    const total = (priceValue * quantity).toFixed(2);
    document.getElementById('addToCartTotalPrice').textContent = total + '€';
}

// Fonction pour traiter l'ajout au panier
function processAddToCart() {
    const productId = document.getElementById('addToCartModal').dataset.productId;
    const quantity = parseInt(document.getElementById('addToCartQuantity').value);

    fetch(`/api/cart/add`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: quantity
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showToast(`${quantity} produit(s) ajouté(s) au panier !`, 'success');
            // Fermer le modal
            bootstrap.Modal.getInstance(document.getElementById('addToCartModal')).hide();
            // Mettre à jour le compteur du panier
            if (window.updateCartCount) {
                window.updateCartCount();
            }
        } else {
            showToast(data.message || 'Erreur lors de l\'ajout', 'error');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showToast(`Erreur lors de l'ajout au panier: ${error.message}`, 'error');
    });
}

// Fonctions pour le sélecteur de quantité sur les cartes produit
function changeProductQuantity(productId, delta) {
    const quantityInput = document.getElementById(`quantity-${productId}`);
    const currentValue = parseInt(quantityInput.value);
    const maxValue = parseInt(quantityInput.max);
    const newValue = Math.max(1, Math.min(currentValue + delta, maxValue));
    quantityInput.value = newValue;
}

// Fonction pour ajouter au panier avec la quantité sélectionnée
function addToCartWithQuantity(productId) {
    const quantityInput = document.getElementById(`quantity-${productId}`);
    const quantity = parseInt(quantityInput.value);

    fetch(`/api/cart/add`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: quantity,
            type: 'purchase'
        })
    })
    .then(response => {
        console.log('Réponse API:', response.status, response.statusText);
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Données reçues:', data);
        if (data.success) {
            showToast(`${quantity} produit(s) ajouté(s) au panier !`, 'success');
            // Mettre à jour le compteur du panier
            if (window.updateCartCount) {
                window.updateCartCount();
            }
        } else {
            showToast(data.message || 'Erreur lors de l\'ajout', 'error');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showToast(`Erreur lors de l'ajout au panier: ${error.message}`, 'error');
    });
}
</script>
@endsection
