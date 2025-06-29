@extends('layouts.public')

@section('title', $product->name . ' - ' . config('app.name', 'FarmShop'))

@section('content')
<div class="container py-5">
    @if(isset($product))
        <div class="row">
            <div class="col-lg-6">
                <!-- Images du produit -->
                <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @if($product->main_image)
                            <div class="carousel-item active">
                                <img src="{{ asset('storage/' . $product->main_image) }}" 
                                     class="d-block w-100 rounded" 
                                     alt="{{ $product->name }}"
                                     style="height: 400px; object-fit: cover;">
                            </div>
                        @elseif($product->images && $product->images->count() > 0)
                            @foreach($product->images as $index => $image)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" 
                                         class="d-block w-100 rounded" 
                                         alt="{{ $product->name }}"
                                         style="height: 400px; object-fit: cover;">
                                </div>
                            @endforeach
                        @else
                            <div class="carousel-item active">
                                <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                                     style="height: 400px;">
                                    <i class="fas fa-image text-muted fa-4x"></i>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    @if(($product->images && $product->images->count() > 1) || ($product->main_image && $product->images && $product->images->count() > 0))
                        <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    @endif
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="ps-lg-4">
                    <!-- Badges -->
                    <div class="mb-3">
                        @if($product->is_featured)
                            <span class="badge bg-warning me-2">
                                <i class="fas fa-star me-1"></i>Vedette
                            </span>
                        @endif
                        @if($product->category)
                            <span class="badge bg-primary">
                                {{ $product->category->name }}
                            </span>
                        @endif
                    </div>
                    
                    <!-- Nom du produit -->
                    <h1 class="h2 mb-3">{{ $product->name }}</h1>
                    
                    <!-- Prix -->
                    <div class="mb-4">
                        <span class="h3 text-success">{{ number_format($product->price, 2) }}€</span>
                        <span class="text-muted">/{{ $product->unit_symbol }}</span>
                    </div>
                    
                    <!-- Description -->
                    <div class="mb-4">
                        <h5>Description</h5>
                        <p class="text-muted">{{ $product->description }}</p>
                    </div>
                    
                    <!-- Producteur -->
                    @if($product->producer)
                        <div class="mb-4">
                            <h5>Producteur</h5>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-user-tie text-primary me-2"></i>
                                <span>{{ $product->producer->name }}</span>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Stock et quantité -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                @if($product->quantity > 0)
                                    <span class="badge bg-success">
                                        <i class="fas fa-check me-1"></i>En stock ({{ $product->quantity }})
                                    </span>
                                    @if($product->quantity <= $product->critical_stock_threshold)
                                        <span class="badge bg-warning ms-2">
                                            <i class="fas fa-exclamation-triangle me-1"></i>Stock faible
                                        </span>
                                    @endif
                                @else
                                    <span class="badge bg-danger">
                                        <i class="fas fa-times me-1"></i>Rupture de stock
                                    </span>
                                @endif
                            </div>
                        </div>

                        @auth
                            @if($product->quantity > 0)
                                <!-- Sélecteur de quantité -->
                                <div class="mb-3">
                                    <label for="quantity" class="form-label">Quantité</label>
                                    <div class="input-group" style="max-width: 200px;">
                                        <button class="btn btn-outline-secondary" type="button" onclick="changeQuantity(-1)">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <input type="number" 
                                               class="form-control text-center" 
                                               id="quantity" 
                                               value="1" 
                                               min="1" 
                                               max="{{ $product->quantity }}">
                                        <button class="btn btn-outline-secondary" type="button" onclick="changeQuantity(1)">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Boutons d'action -->
                                <div class="d-flex gap-2 mb-4">
                                    <button class="btn btn-outline-success" onclick="addToCart({{ $product->id }})">
                                        <i class="fas fa-shopping-cart me-1"></i>Ajouter au panier
                                    </button>
                                    <button class="btn btn-success" onclick="buyNowFromDetail({{ $product->id }})">
                                        <i class="fas fa-bolt me-1"></i>Acheter maintenant
                                    </button>
                                    <button class="btn btn-outline-primary" onclick="addToWishlist({{ $product->id }})">
                                        <i class="fas fa-heart me-1"></i>Favoris
                                    </button>
                                </div>
                            @else
                                <div class="mb-4">
                                    <button class="btn btn-secondary" disabled>
                                        <i class="fas fa-ban me-1"></i>Produit en rupture
                                    </button>
                                </div>
                            @endif
                        @else
                            <!-- Message pour visiteurs non connectés -->
                            <div class="alert alert-info">
                                <h6><i class="fas fa-info-circle me-2"></i>Compte requis</h6>
                                <p class="mb-0">
                                    Connectez-vous pour ajouter ce produit au panier et passer commande.
                                </p>
                                <div class="mt-3">
                                    <a href="{{ route('login') }}" class="btn btn-success me-2">
                                        <i class="fas fa-sign-in-alt me-1"></i>Se connecter
                                    </a>
                                    <a href="{{ route('register') }}" class="btn btn-outline-success">
                                        <i class="fas fa-user-plus me-1"></i>Créer un compte
                                    </a>
                                </div>
                            </div>
                        @endauth
                    </div>
                    
                    <!-- Navigation et partage -->
                    <div class="d-flex gap-2 mb-4">
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Retour aux produits
                        </a>
                        <button class="btn btn-outline-secondary" onclick="shareProduct()">
                            <i class="fas fa-share me-1"></i>Partager
                        </button>
                    </div>
                    
                    <!-- Informations supplémentaires -->
                    <div class="border rounded p-3 bg-light">
                        <h6 class="mb-2">
                            <i class="fas fa-truck text-success me-2"></i>
                            Livraison
                        </h6>
                        <small class="text-muted">
                            Livraison gratuite à partir de 25€ d'achat
                        </small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Produits similaires -->
        <div class="row mt-5">
            <div class="col-12">
                <h3 class="mb-4">Produits similaires</h3>
                <div class="row">
                    <!-- Ici vous pourrez ajouter des produits similaires -->
                    <div class="col-12 text-center text-muted py-4">
                        <i class="fas fa-box-open fa-2x mb-2"></i>
                        <p>Bientôt d'autres produits similaires !</p>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="fas fa-exclamation-triangle fa-4x text-warning"></i>
            </div>
            <h3>Produit introuvable</h3>
            <p class="text-muted">Le produit que vous recherchez n'existe pas ou n'est plus disponible.</p>
            <a href="{{ route('products.index') }}" class="btn btn-success">
                <i class="fas fa-arrow-left me-2"></i>Retour aux produits
            </a>
        </div>
    @endif
</div>

@endsection

@section('scripts')
<script>
// Gestion de la quantité
function changeQuantity(delta) {
    const quantityInput = document.getElementById('quantity');
    if (!quantityInput) return;
    
    const currentValue = parseInt(quantityInput.value);
    const newValue = Math.max(1, Math.min(currentValue + delta, parseInt(quantityInput.max)));
    quantityInput.value = newValue;
}

// Ajout au panier depuis la page de détail
function addToCart(productId) {
    const quantity = document.getElementById('quantity')?.value || 1;
    
    fetch(`/api/cart/add`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: parseInt(quantity)
        })
    })
    .then(response => {
        if (response.ok) {
            showToast(`${quantity}x {{ $product->name ?? "" }} ajouté(s) au panier !`, 'success');
        } else {
            showToast('Erreur lors de l\'ajout au panier', 'error');
        }
    })
    .catch(error => {
        // En attendant l'implémentation complète
        showToast(`${quantity}x {{ $product->name ?? "" }} ajouté(s) au panier ! (Simulation)`, 'success');
    });
}

// Achat immédiat depuis la page de détail
function buyNowFromDetail(productId) {
    const quantity = document.getElementById('quantity')?.value || 1;
    
    fetch(`/api/orders/buy-now`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: parseInt(quantity)
        })
    })
    .then(response => {
        if (response.ok) {
            showToast('Achat effectué avec succès !', 'success');
            // Rediriger vers la page de confirmation
            // window.location.href = '/orders/confirmation';
        } else {
            showToast('Erreur lors de l\'achat', 'error');
        }
    })
    .catch(error => {
        // En attendant l'implémentation complète
        showToast(`Achat effectué: ${quantity}x {{ $product->name ?? "" }} (Simulation)`, 'success');
    });
}

// Ajout aux favoris
function addToWishlist(productId) {
    fetch(`/api/wishlist/add`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            product_id: productId
        })
    })
    .then(response => {
        if (response.ok) {
            showToast('Produit ajouté aux favoris !', 'success');
        } else {
            showToast('Erreur lors de l\'ajout aux favoris', 'error');
        }
    })
    .catch(error => {
        // En attendant l'implémentation complète
        showToast('{{ $product->name ?? "" }} ajouté aux favoris ! (Simulation)', 'success');
    });
}

// Partage du produit
function shareProduct() {
    if (navigator.share) {
        navigator.share({
            title: '{{ $product->name ?? "" }}',
            text: 'Découvrez ce produit local sur FarmShop',
            url: window.location.href
        });
    } else {
        navigator.clipboard.writeText(window.location.href).then(() => {
            showToast('Lien copié dans le presse-papier !', 'info');
        });
    }
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
</script>
