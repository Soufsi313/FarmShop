@extends('layouts.public')

@section('title', 'Mon Panier - ' . config('app.name', 'FarmShop'))

@section('content')
<div class="container py-5">
    <!-- Boutons de navigation -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex gap-2">
                <a href="{{ route('welcome') }}" class="btn btn-outline-success">
                    <i class="fas fa-home me-2"></i>Accueil
                </a>
                <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-shopping-bag me-2"></i>Continuer mes achats
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- En-tête du panier -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-shopping-cart me-2"></i>Mon Panier</h2>
            </div>

            @if($cartSummary['has_issues'])
                <!-- Alertes de validation -->
                <div class="alert alert-warning">
                    <h6><i class="fas fa-exclamation-triangle me-2"></i>Attention</h6>
                    <ul class="mb-0">
                        @foreach($cartSummary['validation_issues'] as $issue)
                            <li>{{ $issue }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if($cartSummary['items']->count() > 0)
                <!-- Articles du panier -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Articles ({{ $cartSummary['item_count'] }})</h5>
                    </div>
                    <div class="card-body p-0">
                        @foreach($cartSummary['items'] as $item)
                            <div class="cart-item border-bottom p-4" data-cart-id="{{ $item->id }}">
                                <div class="row align-items-center">
                                    <!-- Image du produit -->
                                    <div class="col-md-2">
                                        @if($item->product->main_image)
                                            <img src="{{ asset('storage/' . $item->product->main_image) }}" 
                                                 class="img-fluid rounded" 
                                                 alt="{{ $item->product->name }}"
                                                 style="height: 80px; width: 80px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                 style="height: 80px; width: 80px;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Informations du produit -->
                                    <div class="col-md-4">
                                        <h6 class="mb-1">
                                            <a href="{{ route('products.show', $item->product->slug) }}" 
                                               class="text-decoration-none text-dark">
                                                {{ $item->product->name }}
                                            </a>
                                        </h6>
                                        <small class="text-muted">
                                            <i class="fas fa-tag me-1"></i>{{ $item->product->category->name }}
                                        </small>
                                        @if(!$item->product->is_active)
                                            <div>
                                                <span class="badge bg-danger">Produit non disponible</span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Prix unitaire -->
                                    <div class="col-md-2 text-center">
                                        <strong>{{ number_format($item->unit_price, 2) }}€</strong>
                                        <br>
                                        <small class="text-muted">/{{ $item->product->unit_symbol }}</small>
                                    </div>

                                    <!-- Quantité -->
                                    <div class="col-md-2">
                                        <div class="input-group input-group-sm">
                                            <button class="btn btn-outline-secondary" type="button" 
                                                    onclick="updateCartQuantity({{ $item->id }}, {{ $item->quantity - 1 }})"
                                                    {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <input type="number" class="form-control text-center" 
                                                   value="{{ $item->quantity }}" 
                                                   min="1" 
                                                   max="{{ $item->product->quantity }}"
                                                   onchange="updateCartQuantity({{ $item->id }}, this.value)"
                                                   id="quantity-{{ $item->id }}">
                                            <button class="btn btn-outline-secondary" type="button" 
                                                    onclick="updateCartQuantity({{ $item->id }}, {{ $item->quantity + 1 }})"
                                                    {{ $item->quantity >= $item->product->quantity ? 'disabled' : '' }}>
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                        <small class="text-muted d-block text-center mt-1">
                                            Stock: {{ $item->product->quantity }}
                                        </small>
                                    </div>

                                    <!-- Total et actions -->
                                    <div class="col-md-2 text-end">
                                        <div class="mb-2">
                                            <strong class="cart-item-total">{{ number_format($item->total_price, 2) }}€</strong>
                                        </div>
                                        <button class="btn btn-sm btn-outline-danger" 
                                                onclick="removeFromCart({{ $item->id }})"
                                                title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Actions du panier -->
                <div class="mt-4">
                    <div class="row">
                        <div class="col-md-6">
                            <button class="btn btn-outline-secondary" onclick="clearEntireCart()">>
                                <i class="fas fa-trash me-1"></i>Vider le panier
                            </button>
                        </div>
                        <div class="col-md-6 text-end">
                            <button class="btn btn-outline-primary me-2" onclick="updateAllCart()">
                                <i class="fas fa-sync me-1"></i>Actualiser
                            </button>
                        </div>
                    </div>
                </div>
            @else
                <!-- Panier vide -->
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-shopping-cart fa-4x text-muted mb-4"></i>
                        <h3>Votre panier est vide</h3>
                        <p class="text-muted mb-4">Commencez vos achats en ajoutant des produits à votre panier.</p>
                        <a href="{{ route('products.index') }}" class="btn btn-primary">
                            <i class="fas fa-shopping-bag me-2"></i>Découvrir nos produits
                        </a>
                    </div>
                </div>
            @endif
        </div>

        @if($cartSummary['items']->count() > 0)
            <!-- Résumé de la commande -->
            <div class="col-lg-4">
                <div class="card position-sticky" style="top: 20px;">
                    <div class="card-header">
                        <h5 class="mb-0">Résumé de la commande</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Sous-total ({{ $cartSummary['item_count'] }} articles)</span>
                            <span id="cart-subtotal">{{ number_format($cartSummary['subtotal'], 2) }}€</span>
                        </div>
                        
                        <!-- Détail TVA par taux -->
                        @if(isset($cartSummary['tax_breakdown']) && count($cartSummary['tax_breakdown']) > 0)
                            @foreach($cartSummary['tax_breakdown'] as $taxInfo)
                                <div class="d-flex justify-content-between mb-1 small text-muted">
                                    <span>TVA {{ $taxInfo['rate_percent'] }}% ({{ implode(', ', $taxInfo['categories']) }})</span>
                                    <span>{{ number_format($taxInfo['tax_amount'], 2) }}€</span>
                                </div>
                            @endforeach
                            <div class="d-flex justify-content-between mb-2">
                                <span><strong>Total TVA</strong></span>
                                <span id="cart-tax"><strong>{{ number_format($cartSummary['total_tax_amount'], 2) }}€</strong></span>
                            </div>
                        @else
                            <div class="d-flex justify-content-between mb-2">
                                <span>TVA</span>
                                <span id="cart-tax">0.00€</span>
                            </div>
                        @endif
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total</strong>
                            <strong id="cart-total">{{ number_format($cartSummary['total'], 2) }}€</strong>
                        </div>

                        <!-- Information livraison -->
                        <div class="alert alert-info mb-3">
                            <small>
                                <i class="fas fa-truck me-1"></i>
                                Livraison gratuite à partir de 25€
                            </small>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="d-grid gap-2">
                            @if(!$cartSummary['has_issues'])
                                <button class="btn btn-success" onclick="proceedToCheckout()">
                                    <i class="fas fa-credit-card me-1"></i>Passer commande
                                </button>
                            @else
                                <button class="btn btn-secondary" disabled>
                                    <i class="fas fa-exclamation-triangle me-1"></i>Corriger les erreurs
                                </button>
                            @endif
                            <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left me-1"></i>Continuer mes achats
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@endsection

@section('scripts')
<script>
// Mettre à jour la quantité d'un article
function updateCartQuantity(cartId, newQuantity) {
    if (newQuantity < 1) return;
    
    console.log(`Mise à jour quantité: cartId=${cartId}, newQuantity=${newQuantity}`);
    const url = `/api/cart/items/${cartId}`;
    console.log(`URL de la requête: ${url}`);
    
    fetch(url, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            quantity: parseInt(newQuantity)
        })
    })
    .then(response => {
        console.log(`Réponse reçue: ${response.status} ${response.statusText} de ${response.url}`);
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        
        return response.json();
    })
    .then(data => {
        console.log('Données reçues:', data);
        if (data.success) {
            // Recharger la page pour mettre à jour tous les totaux
            location.reload();
        } else {
            showToast(data.message || 'Erreur lors de la mise à jour', 'error');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showToast('Erreur lors de la mise à jour de la quantité', 'error');
    });
}

// Supprimer un article du panier
function removeFromCart(cartId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet article ?')) {
        console.log(`Tentative de suppression de l'article ID: ${cartId}`);
        
        fetch(`/api/cart/items/${cartId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            console.log('Réponse reçue:', response.status, response.url);
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Données reçues:', data);
            if (data.success) {
                // Supprimer l'élément du DOM ou recharger la page
                location.reload();
            } else {
                showToast(data.message || 'Erreur lors de la suppression', 'error');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showToast('Erreur lors de la suppression de l\'article', 'error');
        });
    }
}

// Vider tout le panier
function clearEntireCart() {
    if (confirm('Êtes-vous sûr de vouloir vider complètement votre panier ?')) {
        console.log('Tentative de vidage du panier...');
        
        fetch('/api/cart/clear', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            console.log('Réponse reçue:', response.status, response.url);
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Données reçues:', data);
            if (data.success) {
                location.reload();
            } else {
                showToast(data.message || 'Erreur lors du vidage du panier', 'error');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showToast('Erreur lors du vidage du panier', 'error');
        });
    }
}

// Actualiser le panier
function updateAllCart() {
    location.reload();
}

// Procéder au checkout
function proceedToCheckout() {
    // Vérifier si l'utilisateur est connecté
    @guest
        // Si l'utilisateur n'est pas connecté, rediriger vers la connexion
        showToast('Veuillez vous connecter pour continuer', 'warning');
        window.location.href = '/login?redirect=' + encodeURIComponent('/checkout');
        return;
    @endguest
    
    // Si le panier n'est pas vide, rediriger directement vers le checkout
    const cartItems = document.querySelectorAll('.cart-item');
    if (cartItems.length === 0) {
        showToast('Votre panier est vide', 'error');
        return;
    }
    
    // Rediriger vers la page de checkout
    showToast('Redirection vers la commande...', 'success');
    window.location.href = '/checkout';
}

// Fonction toast réutilisée
function showToast(message, type = 'success') {
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
    
    toastElement.addEventListener('hidden.bs.toast', () => {
        toastElement.remove();
    });
}

function createToastContainer() {
    const container = document.createElement('div');
    container.className = 'toast-container position-fixed top-0 end-0 p-3';
    container.style.zIndex = '9999';
    document.body.appendChild(container);
    return container;
}
</script>
@endsection
