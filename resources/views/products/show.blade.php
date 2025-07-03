@extends('layouts.public')

@section('title', $product->name . ' - ' . config('app.name', 'FarmShop'))

@section('content')
<div class="container py-5">
    <!-- Boutons de navigation -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex gap-2 align-items-center">
                <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Retour aux produits
                </a>
                <a href="{{ route('welcome') }}" class="btn btn-outline-success">
                    <i class="fas fa-home me-2"></i>Accueil
                </a>
            </div>
        </div>
    </div>

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
                    
                    <!-- Offre spéciale -->
                    @if($product->hasActiveSpecialOffer())
                        @php
                            $offer = $product->getActiveSpecialOffer();
                        @endphp
                        <div class="special-offer-alert alert border-0 shadow-sm mb-4 fade-in-special">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="fas fa-fire fire-icon fa-2x"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="alert-heading mb-2">
                                        <i class="fas fa-gift me-2"></i>{{ $offer->name }}
                                    </h5>
                                    <p class="mb-2">
                                        <strong>{{ $offer->discount_percentage }}% de remise</strong> 
                                        dès {{ $offer->min_quantity }} {{ $product->unit_symbol }} achetés !
                                    </p>
                                    @if($offer->description)
                                        <p class="mb-2 text-muted">{{ $offer->description }}</p>
                                    @endif
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted offer-countdown">
                                            <i class="fas fa-clock me-1"></i>
                                            Offre valable jusqu'au {{ $offer->end_date->format('d/m/Y à H:i') }}
                                        </small>
                                        <span class="savings-badge badge fs-6">
                                            Économisez {{ number_format($product->price * $offer->min_quantity * $offer->discount_percentage / 100, 2) }}€
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Nom du produit -->
                    <h1 class="h2 mb-3">{{ $product->name }}</h1>
                    
                    <!-- Prix -->
                    <div class="mb-4">
                        @if($product->is_rentable && $product->rental_price_per_day > 0)
                            @if($product->price > 0)
                                <!-- Produit mixte achat + location -->
                                <div class="d-flex flex-column gap-2">
                                    <div>
                                        <span class="badge bg-warning me-2">
                                            <i class="fas fa-star me-1"></i>Achat + Location
                                        </span>
                                    </div>
                                    <div>
                                        <span class="h4 text-success">{{ number_format($product->price, 2) }}€</span>
                                        <span class="text-muted">/{{ $product->unit_symbol }}</span>
                                        <small class="text-muted ms-2">(Achat)</small>
                                    </div>
                                    <div>
                                        <span class="h4 text-info">{{ number_format($product->rental_price_per_day, 2) }}€</span>
                                        <span class="text-muted">/jour</span>
                                        <small class="text-muted ms-2">(Location)</small>
                                    </div>
                                    @if($product->deposit_amount > 0)
                                        <div>
                                            <small class="text-warning">
                                                <i class="fas fa-shield-alt me-1"></i>
                                                Caution: {{ number_format($product->deposit_amount, 2) }}€
                                            </small>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <!-- Produit location uniquement -->
                                <div class="d-flex flex-column gap-2">
                                    <div>
                                        <span class="badge bg-info me-2">
                                            <i class="fas fa-calendar-alt me-1"></i>Location
                                        </span>
                                    </div>
                                    <div>
                                        <span class="h3 text-info">{{ number_format($product->rental_price_per_day, 2) }}€</span>
                                        <span class="text-muted">/jour</span>
                                    </div>
                                    @if($product->deposit_amount > 0)
                                        <div>
                                            <small class="text-warning">
                                                <i class="fas fa-shield-alt me-1"></i>
                                                Caution: {{ number_format($product->deposit_amount, 2) }}€
                                            </small>
                                        </div>
                                    @endif
                                    @if($product->min_rental_days > 1 || $product->max_rental_days < 365)
                                        <div>
                                            <small class="text-muted">
                                                <i class="fas fa-clock me-1"></i>
                                                @if($product->min_rental_days > 1)
                                                    Min: {{ $product->min_rental_days }} jour{{ $product->min_rental_days > 1 ? 's' : '' }}
                                                @endif
                                                @if($product->max_rental_days < 365)
                                                    {{ $product->min_rental_days > 1 ? ' - ' : '' }}Max: {{ $product->max_rental_days }} jours
                                                @endif
                                            </small>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        @else
                            <!-- Produit achat uniquement -->
                            <div class="d-flex flex-column gap-2">
                                <div>
                                    <span class="badge bg-success me-2">
                                        <i class="fas fa-shopping-cart me-1"></i>Achat
                                    </span>
                                </div>
                                <div>
                                    @if($product->hasActiveSpecialOffer())
                                        @php
                                            $offer = $product->getActiveSpecialOffer();
                                            $discountedPrice = $product->price * (1 - $offer->discount_percentage / 100);
                                        @endphp
                                        <div class="price-with-discount d-flex align-items-center flex-wrap gap-2 mb-2">
                                            <span class="original-price h4">
                                                {{ number_format($product->price, 2) }}€
                                            </span>
                                            <span class="discounted-price h3 fw-bold">
                                                {{ number_format($discountedPrice, 2) }}€
                                            </span>
                                            <span class="text-muted">/{{ $product->unit_symbol }}</span>
                                        </div>
                                        <small class="text-success">
                                            <i class="fas fa-percentage me-1"></i>
                                            Prix avec remise de {{ $offer->discount_percentage }}% (dès {{ $offer->min_quantity }} {{ $product->unit_symbol }})
                                        </small>
                                    @else
                                        <span class="h3 text-success">{{ number_format($product->price, 2) }}€</span>
                                        <span class="text-muted">/{{ $product->unit_symbol }}</span>
                                    @endif
                                </div>
                            </div>
                        @endif
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
                                @if($product->is_rentable && $product->rental_price_per_day > 0)
                                    @if($product->price > 0)
                                        <!-- Produit mixte achat + location -->
                                        <!-- Sélecteur de quantité pour achat -->
                                        <div class="mb-3">
                                            <label for="quantity" class="form-label">Quantité (pour achat)</label>
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

                                        <!-- Boutons pour achat -->
                                        <div class="d-flex gap-2 mb-3">
                                            <button class="btn btn-outline-success" onclick="addToCart({{ $product->id }})">
                                                <i class="fas fa-shopping-cart me-1"></i>Ajouter au panier
                                            </button>
                                            <button class="btn btn-success" onclick="buyNowFromDetail({{ $product->id }})">
                                                <i class="fas fa-bolt me-1"></i>Acheter maintenant
                                            </button>
                                        </div>

                                        <!-- Bouton pour location -->
                                        <div class="d-flex gap-2 mb-4">
                                            <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#rentalModal{{ $product->id }}">
                                                <i class="fas fa-calendar-alt me-1"></i>Louer ce produit
                                            </button>
                                            <button class="btn btn-outline-primary" onclick="addToWishlist({{ $product->id }})">
                                                <i class="fas fa-heart me-1"></i>Favoris
                                            </button>
                                        </div>
                                    @else
                                        <!-- Produit location uniquement -->
                                        <div class="d-flex gap-2 mb-4">
                                            <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#rentalModal{{ $product->id }}">
                                                <i class="fas fa-calendar-alt me-1"></i>Louer ce produit
                                            </button>
                                            <button class="btn btn-outline-primary" onclick="addToWishlist({{ $product->id }})">
                                                <i class="fas fa-heart me-1"></i>Favoris
                                            </button>
                                        </div>
                                    @endif
                                @else
                                    <!-- Produit achat uniquement -->
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

                                    <!-- Boutons d'action pour achat -->
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
                                @endif
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
                    
                    <!-- Informations supplémentaires (uniquement pour les produits d'achat) -->
                    @if($product->price > 0)
                        <div class="border rounded p-3 bg-light">
                            <h6 class="mb-2">
                                <i class="fas fa-truck text-success me-2"></i>
                                Livraison
                            </h6>
                            <small class="text-muted">
                                Livraison gratuite à partir de 25€ d'achat
                            </small>
                        </div>
                    @else
                        <!-- Informations pour les produits de location uniquement -->
                        <div class="border rounded p-3 bg-light">
                            <h6 class="mb-2">
                                <i class="fas fa-handshake text-info me-2"></i>
                                Récupération & Retour
                            </h6>
                            <small class="text-muted">
                                Récupération et retour du matériel directement sur place
                            </small>
                        </div>
                    @endif
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

<!-- Modal de location pour produits louables -->
@if($product->is_rentable && $product->rental_price_per_day > 0)
<div class="modal fade" id="rentalModal{{ $product->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-calendar-alt me-2"></i>Louer : {{ $product->name }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="rentalForm{{ $product->id }}">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Date de début</label>
                            <input type="date" class="form-control" name="start_date" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date de fin</label>
                            <input type="date" class="form-control" name="end_date" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="card bg-light">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Prix par jour :</span>
                                    <strong>{{ number_format($product->rental_price_per_day, 2) }}€</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Nombre de jours :</span>
                                    <strong id="rentalDays{{ $product->id }}">0</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Sous-total :</span>
                                    <strong id="rentalSubtotal{{ $product->id }}">0.00€</strong>
                                </div>
                                @if($product->deposit_amount > 0)
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Caution :</span>
                                    <strong class="text-warning">{{ number_format($product->deposit_amount, 2) }}€</strong>
                                </div>
                                @endif
                                <hr class="my-2">
                                <div class="d-flex justify-content-between">
                                    <span><strong>Total à payer :</strong></span>
                                    <strong class="text-primary" id="rentalTotal{{ $product->id }}">0.00€</strong>
                                </div>
                                <small class="text-muted d-block mt-1">
                                    @if($product->deposit_amount > 0)
                                        La caution sera restituée après retour en bon état
                                    @endif
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    @if($product->rental_conditions)
                    <div class="mb-3">
                        <label class="form-label">Conditions de location :</label>
                        <div class="alert alert-info">
                            <small>{{ $product->rental_conditions }}</small>
                        </div>
                    </div>
                    @endif
                    
                    @if($product->min_rental_days > 1 || $product->max_rental_days < 365)
                    <div class="mb-3">
                        <div class="alert alert-warning">
                            <small>
                                <i class="fas fa-info-circle me-1"></i>
                                @if($product->min_rental_days > 1)
                                    Durée minimum : {{ $product->min_rental_days }} jour{{ $product->min_rental_days > 1 ? 's' : '' }}
                                @endif
                                @if($product->max_rental_days < 365)
                                    {{ $product->min_rental_days > 1 ? ' - ' : '' }}Durée maximum : {{ $product->max_rental_days }} jours
                                @endif
                            </small>
                        </div>
                    </div>
                    @endif
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-info" onclick="processRental({{ $product->id }})">
                    <i class="fas fa-calendar-check me-1"></i>Confirmer la location
                </button>
            </div>
        </div>
    </div>

    <!-- Navigation en bas de page -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="d-flex justify-content-center gap-3">
                <a href="{{ route('products.index') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left me-2"></i>Retour aux produits
                </a>
                <a href="{{ route('welcome') }}" class="btn btn-outline-success">
                    <i class="fas fa-home me-2"></i>Accueil
                </a>
                @auth
                    <a href="{{ route('cart.index') }}" class="btn btn-outline-warning">
                        <i class="fas fa-shopping-cart me-2"></i>Mon Panier
                    </a>
                @endauth
            </div>
        </div>
    </div>
</div>
@endif

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

// Gestion des dates de location et calcul automatique
document.addEventListener('DOMContentLoaded', function() {
    @if($product->is_rentable && $product->rental_price_per_day > 0)
    const startDateInput = document.querySelector('#rentalModal{{ $product->id }} input[name="start_date"]');
    const endDateInput = document.querySelector('#rentalModal{{ $product->id }} input[name="end_date"]');
    
    if (startDateInput && endDateInput) {
        // Date minimum = demain (pas de location le jour même)
        const today = new Date();
        const tomorrow = new Date(today);
        tomorrow.setDate(tomorrow.getDate() + 1);
        const tomorrowStr = tomorrow.toISOString().split('T')[0];
        
        startDateInput.min = tomorrowStr;
        endDateInput.min = tomorrowStr;
        
        function calculateRental() {
            const startDate = new Date(startDateInput.value);
            const endDate = new Date(endDateInput.value);
            
            if (startDate && endDate && endDate >= startDate) {
                // Calculer le nombre de jours (incluant le jour de début et de fin)
                const diffTime = Math.abs(endDate - startDate);
                const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24)) + 1; // +1 pour inclure le jour de début
                
                const pricePerDay = {{ $product->rental_price_per_day }};
                const deposit = {{ $product->deposit_amount ?? 0 }};
                const minDays = {{ $product->min_rental_days ?? 1 }};
                const maxDays = {{ $product->max_rental_days ?? 365 }};
                
                // Vérifier les contraintes de durée
                if (diffDays < minDays || diffDays > maxDays) {
                    document.getElementById('rentalDays{{ $product->id }}').textContent = diffDays;
                    document.getElementById('rentalSubtotal{{ $product->id }}').textContent = 'Durée non valide';
                    document.getElementById('rentalTotal{{ $product->id }}').textContent = 'Durée non valide';
                    return;
                }
                
                const subtotal = diffDays * pricePerDay;
                const total = subtotal + deposit;
                
                document.getElementById('rentalDays{{ $product->id }}').textContent = diffDays;
                document.getElementById('rentalSubtotal{{ $product->id }}').textContent = subtotal.toFixed(2) + '€';
                document.getElementById('rentalTotal{{ $product->id }}').textContent = total.toFixed(2) + '€';
            } else {
                document.getElementById('rentalDays{{ $product->id }}').textContent = '0';
                document.getElementById('rentalSubtotal{{ $product->id }}').textContent = '0.00€';
                document.getElementById('rentalTotal{{ $product->id }}').textContent = '{{ number_format($product->deposit_amount ?? 0, 2) }}€';
            }
        }
        
        startDateInput.addEventListener('change', function() {
            // Mettre à jour la date minimum de fin selon la date de début
            const minDays = {{ $product->min_rental_days ?? 1 }};
            const maxDays = {{ $product->max_rental_days ?? 365 }};
            
            if (this.value) {
                const start = new Date(this.value);
                
                // Date minimum de fin = date de début + (minimum de jours - 1)
                const minEndDate = new Date(start);
                minEndDate.setDate(start.getDate() + (minDays - 1));
                
                // Date maximum de fin = date de début + (maximum de jours - 1)
                const maxEndDate = new Date(start);
                maxEndDate.setDate(start.getDate() + (maxDays - 1));
                
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
            
            calculateRental();
        });
        
        endDateInput.addEventListener('change', calculateRental);
    }
    @endif
});

// Traitement de la location
function processRental(productId) {
    const form = document.getElementById(`rentalForm${productId}`);
    const formData = new FormData(form);
    
    const startDate = formData.get('start_date');
    const endDate = formData.get('end_date');
    
    if (!startDate || !endDate) {
        showToast('Veuillez sélectionner les dates de location', 'error');
        return;
    }
    
    const start = new Date(startDate);
    const end = new Date(endDate);
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    // Validation 1: Date de début doit être au minimum demain
    if (start <= today) {
        showToast('La location ne peut pas commencer aujourd\'hui. Choisissez une date à partir de demain.', 'error');
        return;
    }
    
    // Validation 2: Date de fin >= date de début
    if (end < start) {
        showToast('La date de fin doit être postérieure à la date de début', 'error');
        return;
    }
    
    // Vérification des contraintes de durée
    const diffTime = Math.abs(end - start);
    const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24)) + 1; // +1 pour inclure le jour de début
    const minDays = {{ $product->min_rental_days ?? 1 }};
    const maxDays = {{ $product->max_rental_days ?? 365 }};
    
    if (diffDays < minDays) {
        showToast(`Durée minimum de location : ${minDays} jour${minDays > 1 ? 's' : ''}`, 'error');
        return;
    }
    
    if (diffDays > maxDays) {
        showToast(`Durée maximum de location : ${maxDays} jours`, 'error');
        return;
    }
    
    // Ajout au panier de location via la nouvelle API
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
            showToast('Produit ajouté au panier de location !', 'success');
            document.querySelector(`#rentalModal${productId} .btn-close`).click();
            
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

// Ajout au panier depuis la page de détail
function addToCart(productId) {
    const quantity = document.getElementById('quantity')?.value || 1;
    
    console.log('Tentative d\'ajout au panier:', { productId, quantity });
    
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
        console.log('Réponse API:', response.status, response.statusText);
        if (!response.ok) {
            // Essayons de lire le contenu même en cas d'erreur
            return response.text().then(text => {
                console.error('Contenu de l\'erreur:', text);
                throw new Error(`HTTP ${response.status}: ${response.statusText} - ${text.substring(0, 200)}`);
            });
        }
        // Lire le contenu comme texte d'abord pour le débugger
        return response.text().then(text => {
            console.log('Contenu brut de la réponse:', text);
            try {
                return JSON.parse(text);
            } catch (e) {
                console.error('Erreur de parsing JSON:', e);
                console.error('Contenu qui pose problème:', text.substring(0, 500));
                throw new Error('Réponse non-JSON reçue: ' + text.substring(0, 100));
            }
        });
    })
    .then(data => {
        console.log('Données reçues:', data);
        if (data.success) {
            showToast(`${quantity}x {{ $product->name ?? "" }} ajouté(s) au panier !`, 'success');
            // Mettre à jour le compteur du panier
            if (window.updateCartCount) {
                window.updateCartCount();
            }
        } else {
            showToast(data.message || 'Erreur lors de l\'ajout au panier', 'error');
        }
    })
    .catch(error => {
        console.error('Erreur complète:', error);
        showToast('Erreur lors de l\'ajout au panier: ' + error.message, 'error');
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
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            // Rediriger vers la page de paiement après un court délai
            setTimeout(() => {
                window.location.href = data.redirect_url;
            }, 1000);
        } else {
            showToast(data.message || 'Erreur lors de l\'achat', 'error');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showToast('Erreur lors de l\'achat', 'error');
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
