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
                        @if($product->is_organic)
                            <span class="badge bg-success me-2">
                                <i class="fas fa-leaf me-1"></i>Bio
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
                    
                    <!-- Stock -->
                    <div class="mb-4">
                        @if($product->quantity > 0)
                            <span class="badge bg-success">
                                <i class="fas fa-check me-1"></i>En stock ({{ $product->quantity }})
                            </span>
                        @else
                            <span class="badge bg-danger">
                                <i class="fas fa-times me-1"></i>Rupture de stock
                            </span>
                        @endif
                    </div>
                    
                    <!-- Boutons d'action -->
                    <div class="d-flex gap-2 mb-4">
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Retour au dashboard
                        </a>
                        <button class="btn btn-outline-primary" onclick="addToWishlist({{ $product->id }})">
                            <i class="fas fa-heart me-1"></i>Favoris
                        </button>
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

<script>
// Ajout au panier
document.getElementById('addToCartForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const quantity = document.getElementById('quantity').value;
    alert(`${quantity} {{ $product->unit ?? '' }} ajouté(s) au panier ! (Fonctionnalité à implémenter)`);
});

// Ajout aux favoris
function addToWishlist(productId) {
    alert('Ajouté aux favoris ! (Fonctionnalité à implémenter)');
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
            alert('Lien copié dans le presse-papier !');
        });
    }
}
</script>
@endsection
