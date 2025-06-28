@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h2">
                    <i class="fas fa-store text-success me-2"></i>
                    Nos Produits Locaux
                </h1>
                <div class="d-flex gap-2">
                    <select class="form-select" id="categoryFilter">
                        <option value="">Toutes les catégories</option>
                        <option value="legumes">Légumes</option>
                        <option value="fruits">Fruits</option>
                        <option value="viande">Viande</option>
                        <option value="fromage">Fromage</option>
                        <option value="pain">Pain & Pâtisserie</option>
                    </select>
                    <select class="form-select" id="sortFilter">
                        <option value="name">Nom A-Z</option>
                        <option value="price_asc">Prix croissant</option>
                        <option value="price_desc">Prix décroissant</option>
                    </select>
                </div>
            </div>

            <!-- Message si aucun produit -->
            @if(isset($products) && $products->count() > 0)
                <div class="row">
                    @foreach($products as $product)
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card product-card h-100 shadow-sm">
                                <div class="position-relative">
                                    @if($product->images && $product->images->count() > 0)
                                        <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" 
                                             class="card-img-top" 
                                             alt="{{ $product->name }}"
                                             style="height: 250px; object-fit: cover;">
                                    @else
                                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                             style="height: 250px;">
                                            <i class="fas fa-image text-muted fa-3x"></i>
                                        </div>
                                    @endif
                                    
                                    @if($product->is_organic)
                                        <span class="badge bg-success position-absolute top-0 end-0 m-2">
                                            <i class="fas fa-leaf me-1"></i>Bio
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">{{ $product->name }}</h5>
                                    <p class="card-text text-muted flex-grow-1">
                                        {{ Str::limit($product->description, 100) }}
                                    </p>
                                    
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="h5 text-success mb-0">{{ number_format($product->price, 2) }}€</span>
                                            <small class="text-muted">/{{ $product->unit }}</small>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('products.show', $product->slug) }}" 
                                               class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye me-1"></i>Voir
                                            </a>
                                            <button class="btn btn-success btn-sm" 
                                                    onclick="addToCart({{ $product->id }})">
                                                <i class="fas fa-shopping-cart me-1"></i>Ajouter
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if(method_exists($products, 'links'))
                    <div class="d-flex justify-content-center mt-4">
                        {{ $products->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-store fa-4x text-muted"></i>
                    </div>
                    <h3 class="text-muted">Aucun produit disponible</h3>
                    <p class="text-muted">Nos producteurs travaillent dur pour vous proposer de nouveaux produits bientôt !</p>
                    <a href="{{ route('home') }}" class="btn btn-success">
                        <i class="fas fa-home me-2"></i>Retour à l'accueil
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.product-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
}

.btn-sm {
    font-size: 0.8rem;
}
</style>

<script>
function addToCart(productId) {
    // Ici vous pourrez ajouter la logique d'ajout au panier
    alert('Produit ajouté au panier ! (Fonctionnalité à implémenter)');
}

// Filtrage des produits (côté client basique)
document.getElementById('categoryFilter').addEventListener('change', function() {
    // Ici vous pourrez ajouter la logique de filtrage
    console.log('Filtre catégorie:', this.value);
});

document.getElementById('sortFilter').addEventListener('change', function() {
    // Ici vous pourrez ajouter la logique de tri
    console.log('Tri:', this.value);
});
</script>
@endsection
