@extends('layouts.app')

@section('title', $product->name . ' - FarmShop')

@push('meta')
<meta name="description" content="{{ $product->meta_description ?? $product->short_description }}">
<meta name="keywords" content="{{ $product->meta_keywords }}">

<!-- Open Graph -->
<meta property="og:title" content="{{ $product->name }}">
<meta property="og:description" content="{{ $product->short_description }}">
<meta property="og:image" content="{{ $product->main_image ? asset('storage/' . $product->main_image) : '' }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:type" content="product">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $product->name }}">
<meta name="twitter:description" content="{{ $product->short_description }}">
<meta name="twitter:image" content="{{ $product->main_image ? asset('storage/' . $product->main_image) : '' }}">
@endpush

@push('styles')
<style>
    .image-gallery img {
        transition: transform 0.3s ease;
    }
    .image-gallery img:hover {
        transform: scale(1.05);
    }
    .share-button {
        transition: all 0.3s ease;
    }
    .share-button:hover {
        transform: translateY(-2px);
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50" x-data="productDetailPage">
    
    <!-- Breadcrumb -->
    <div class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-4">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-green-600">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                            Accueil
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <a href="{{ route('products.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-green-600 md:ml-2">Produits</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ $product->name }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
            
            <!-- Galerie d'images -->
            <div class="space-y-4">
                <!-- Image principale -->
                <div class="aspect-square bg-white rounded-lg shadow overflow-hidden">
                    @if($product->main_image)
                        <img id="mainImage" 
                             src="{{ asset('storage/' . $product->main_image) }}" 
                             alt="{{ $product->name }}"
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gray-200">
                            <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif
                </div>

                <!-- Galerie d'images miniatures -->
                @if($product->gallery_images && count($product->gallery_images) > 0)
                    <div class="grid grid-cols-4 gap-2">
                        <!-- Image principale en miniature -->
                        @if($product->main_image)
                            <button onclick="changeMainImage('{{ asset('storage/' . $product->main_image) }}')"
                                    class="aspect-square bg-white rounded-lg shadow overflow-hidden border-2 border-green-500">
                                <img src="{{ asset('storage/' . $product->main_image) }}" 
                                     alt="{{ $product->name }}"
                                     class="w-full h-full object-cover">
                            </button>
                        @endif
                        
                        <!-- Images de la galerie -->
                        @foreach($product->gallery_images as $image)
                            <button onclick="changeMainImage('{{ asset('storage/' . $image) }}')"
                                    class="aspect-square bg-white rounded-lg shadow overflow-hidden border-2 border-transparent hover:border-green-500 transition-colors">
                                <img src="{{ asset('storage/' . $image) }}" 
                                     alt="{{ $product->name }}"
                                     class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif

                <!-- Images supplémentaires -->
                @if($product->images && count($product->images) > 0)
                    <div class="border-t pt-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Images supplémentaires</h4>
                        <div class="grid grid-cols-4 gap-2">
                            @foreach($product->images as $image)
                                <button onclick="changeMainImage('{{ asset('storage/' . $image) }}')"
                                        class="aspect-square bg-white rounded-lg shadow overflow-hidden border-2 border-transparent hover:border-green-500 transition-colors">
                                    <img src="{{ asset('storage/' . $image) }}" 
                                         alt="{{ $product->name }}"
                                         class="w-full h-full object-cover">
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Informations produit -->
            <div class="space-y-6">
                <!-- En-tête -->
                <div>
                    <div class="flex items-center space-x-2 mb-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $product->category->name }}
                        </span>
                        
                        @if($product->is_featured)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                ⭐ En vedette
                            </span>
                        @endif

                        @if($product->type === 'sale')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Achat
                            </span>
                        @elseif($product->type === 'rental')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                Location
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                Achat & Location
                            </span>
                        @endif
                    </div>

                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>
                    
                    @if($product->short_description)
                        <p class="text-lg text-gray-600">{{ $product->short_description }}</p>
                    @endif
                </div>

                <!-- Prix -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="text-3xl font-bold text-green-600 mb-2">
                        {{ number_format($product->price, 2) }}€
                        <span class="text-lg font-normal text-gray-500">/ {{ $product->unit_symbol }}</span>
                    </div>
                    
                    @if($product->rental_price_per_day && ($product->type === 'rental' || $product->type === 'both'))
                        <div class="text-lg text-gray-600">
                            Location : {{ number_format($product->rental_price_per_day, 2) }}€/jour
                        </div>
                    @endif

                    <!-- Stock -->
                    <div class="mt-3 flex items-center space-x-2">
                        @if($product->quantity > 0)
                            <span class="text-sm text-gray-600">
                                {{ $product->quantity }} en stock
                            </span>
                            
                            @if($product->quantity <= $product->critical_threshold)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Stock faible
                                </span>
                            @elseif($product->quantity <= $product->low_stock_threshold)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Stock limité
                                </span>
                            @endif
                        @else
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Rupture de stock
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Actions d'achat (pour les produits d'achat) -->
                @if(($product->type === 'sale' || $product->type === 'both') && $product->quantity > 0)
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Commander ce produit</h3>
                        
                        <!-- Sélection de quantité -->
                        <div class="mb-4">
                            <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                Quantité
                            </label>
                            <div class="flex items-center space-x-3">
                                <button type="button" 
                                        onclick="decreaseQuantity()"
                                        class="px-3 py-2 border border-gray-300 rounded-l-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500">
                                    -
                                </button>
                                <input type="number" 
                                       id="quantity" 
                                       x-model="quantity"
                                       min="1" 
                                       max="{{ $product->quantity }}"
                                       class="w-20 px-3 py-2 border-t border-b border-gray-300 text-center focus:outline-none focus:ring-2 focus:ring-green-500">
                                <button type="button" 
                                        onclick="increaseQuantity()"
                                        class="px-3 py-2 border border-gray-300 rounded-r-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500">
                                    +
                                </button>
                                <span class="text-sm text-gray-500">/ {{ $product->quantity }}</span>
                            </div>
                        </div>

                        <!-- Total -->
                        <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-700">Total</span>
                                <span class="text-lg font-bold text-green-600" x-text="formatPrice(quantity * {{ $product->price }})"></span>
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="space-y-3">
                            @auth
                                <button onclick="addToCart({{ $product->id }})" 
                                        class="w-full bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                                    🛒 Ajouter au panier
                                </button>
                                
                                <button onclick="buyNow({{ $product->id }})" 
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                                    ⚡ Acheter maintenant
                                </button>
                            @else
                                <a href="{{ route('login') }}" 
                                   class="block w-full bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium text-center transition-colors">
                                    🛒 Se connecter pour acheter
                                </a>
                            @endauth
                        </div>
                    </div>
                @endif

                <!-- Compteur de likes (visible par tous) -->
                <div class="flex items-center space-x-2 text-lg text-gray-600 mb-4">
                    <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    <span id="likes_count_{{ $product->slug }}" class="font-semibold">{{ $product->getLikesCount() }}</span>
                    <span>{{ $product->getLikesCount() === 1 ? 'personne aime ce produit' : 'personnes aiment ce produit' }}</span>
                </div>

                <!-- Actions utilisateur -->
                <div class="flex items-center justify-between">
                    @auth
                        <div class="flex space-x-4">
                            <!-- Like -->
                            <button onclick="toggleLike('{{ $product->slug }}')" 
                                    id="like_btn_{{ $product->slug }}"
                                    class="flex items-center space-x-2 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors {{ $isLiked ? 'text-red-500 border-red-300' : 'text-gray-700' }}">
                                <svg class="w-5 h-5 {{ $isLiked ? 'text-red-500' : 'text-gray-400' }}" fill="{{ $isLiked ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                                <span>{{ $isLiked ? 'J\'aime' : 'Aimer' }}</span>
                            </button>

                            <!-- Wishlist -->
                            <button onclick="toggleWishlist('{{ $product->slug }}')" 
                                    id="wishlist_btn_{{ $product->slug }}"
                                    class="flex items-center space-x-2 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors {{ $isInWishlist ? 'text-blue-500 border-blue-300' : 'text-gray-700' }}">
                                <svg class="w-5 h-5 {{ $isInWishlist ? 'text-blue-500' : 'text-gray-400' }}" fill="{{ $isInWishlist ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                                </svg>
                                <span>{{ $isInWishlist ? 'Dans ma wishlist' : 'Ajouter à ma wishlist' }}</span>
                            </button>
                        </div>
                    @endauth

                    <!-- Partage -->
                    <div class="flex space-x-2">
                        <button onclick="shareOnFacebook()" 
                                class="share-button p-2 border border-gray-300 rounded-lg hover:bg-blue-50 hover:border-blue-300 text-blue-600 transition-all">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </button>

                        <button onclick="shareOnTwitter()" 
                                class="share-button p-2 border border-gray-300 rounded-lg hover:bg-blue-50 hover:border-blue-300 text-blue-400 transition-all">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                        </button>

                        <button onclick="copyLink()" 
                                class="share-button p-2 border border-gray-300 rounded-lg hover:bg-gray-50 text-gray-600 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Détails du produit -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
            <!-- Description -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Description</h2>
                    <div class="prose prose-green max-w-none">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                </div>
            </div>

            <!-- Informations complémentaires -->
            <div>
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Informations</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">SKU</dt>
                            <dd class="text-sm text-gray-900">{{ $product->sku }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Catégorie</dt>
                            <dd class="text-sm text-gray-900">{{ $product->category->name }}</dd>
                        </div>
                        
                        @if($product->weight)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Poids</dt>
                                <dd class="text-sm text-gray-900">{{ $product->weight }} kg</dd>
                            </div>
                        @endif
                        
                        @if($product->dimensions)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Dimensions</dt>
                                <dd class="text-sm text-gray-900">{{ $product->dimensions }}</dd>
                            </div>
                        @endif
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Disponibilité</dt>
                            <dd class="text-sm">
                                @if($product->quantity > 0)
                                    <span class="text-green-600 font-medium">En stock ({{ $product->quantity }})</span>
                                @else
                                    <span class="text-red-600 font-medium">Rupture de stock</span>
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Produits similaires -->
        @if($similarProducts->count() > 0)
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Produits similaires</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($similarProducts as $similarProduct)
                        <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                            <!-- Image - Cliquable -->
                            <a href="{{ route('products.show', $similarProduct->slug) }}" class="block aspect-square bg-gray-200 hover:opacity-95 transition-opacity">
                                @if($similarProduct->main_image)
                                    <img src="{{ asset('storage/' . $similarProduct->main_image) }}" 
                                         alt="{{ $similarProduct->name }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                            </a>
                            
                            <!-- Contenu -->
                            <div class="p-4">
                                <a href="{{ route('products.show', $similarProduct->slug) }}" class="block hover:text-green-600 transition-colors">
                                    <h3 class="font-semibold text-gray-900 mb-1">{{ $similarProduct->name }}</h3>
                                </a>
                                <p class="text-sm text-gray-500 mb-2">{{ $similarProduct->category->name }}</p>
                                <div class="text-lg font-bold text-green-600 mb-3">
                                    {{ number_format($similarProduct->price, 2) }}€
                                </div>
                                <a href="{{ route('products.show', $similarProduct->slug) }}" 
                                   class="block w-full text-center bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                                    Voir le produit
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('productDetailPage', () => ({
        quantity: 1,
        
        formatPrice(price) {
            return new Intl.NumberFormat('fr-FR', {
                style: 'currency',
                currency: 'EUR'
            }).format(price);
        }
    }))
})

// Fonction pour changer l'image principale
function changeMainImage(src) {
    document.getElementById('mainImage').src = src;
    
    // Mettre à jour les bordures des miniatures
    document.querySelectorAll('.aspect-square button').forEach(btn => {
        btn.classList.remove('border-green-500');
        btn.classList.add('border-transparent');
    });
    
    // Ajouter la bordure à l'image sélectionnée
    event.target.closest('button').classList.remove('border-transparent');
    event.target.closest('button').classList.add('border-green-500');
}

// Gestion des quantités
function increaseQuantity() {
    const input = document.getElementById('quantity');
    const currentValue = parseInt(input.value);
    const maxValue = parseInt(input.getAttribute('max'));
    
    if (currentValue < maxValue) {
        input.value = currentValue + 1;
        Alpine.store('productDetail').quantity = currentValue + 1;
    }
}

function decreaseQuantity() {
    const input = document.getElementById('quantity');
    const currentValue = parseInt(input.value);
    
    if (currentValue > 1) {
        input.value = currentValue - 1;
        Alpine.store('productDetail').quantity = currentValue - 1;
    }
}

// Actions produit
async function addToCart(productId) {
    const quantity = document.getElementById('quantity').value;
    
    try {
        const response = await fetch(`/cart/add-product/${productId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin',
            body: JSON.stringify({ quantity: parseInt(quantity) })
        });

        if (!response.ok) {
            const errorText = await response.text();
            console.error('Response error:', errorText);
            throw new Error(`HTTP ${response.status}: ${errorText}`);
        }

        const data = await response.json();
        
        if (data.success) {
            showNotification('Produit ajouté au panier !', 'success');
            updateCartCount(data.cart_count);
        } else {
            showNotification(data.message || 'Erreur lors de l\'ajout au panier', 'error');
        }
    } catch (error) {
        console.error('Erreur:', error);
        showNotification('Erreur lors de l\'ajout au panier', 'error');
    }
}

async function buyNow(productId) {
    try {
        // Ajouter le produit au panier avec quantité 1
        const response = await fetch(`/cart/add-product/${productId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin',
            body: JSON.stringify({ quantity: 1 })
        });

        if (!response.ok) {
            const errorText = await response.text();
            console.error('Response error:', errorText);
            throw new Error(`HTTP ${response.status}: ${errorText}`);
        }

        const data = await response.json();
        
        if (data.success) {
            // Mettre à jour le compteur du panier dans l'en-tête
            updateCartCount(data.cart_count);
            
            // Rediriger vers le panier
            window.location.href = '/cart';
        } else {
            showNotification(data.message || 'Erreur lors de l\'ajout au panier', 'error');
        }
    } catch (error) {
        console.error('Erreur:', error);
        showNotification('Erreur lors de l\'ajout au panier', 'error');
    }
}

async function toggleLike(productSlug) {
    // Debug: vérifier l'état d'authentification
    console.log('Démarrage toggleLike pour:', productSlug);
    console.log('Cookies présents:', document.cookie);
    
    try {
        const response = await fetch(`/web/likes/products/${productSlug}/toggle`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        });

        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);

        // Vérifier si la réponse est ok
        if (!response.ok) {
            // Si c'est une erreur 401, rediriger vers la page de connexion
            if (response.status === 401) {
                showNotification('Vous devez être connecté pour effectuer cette action', 'error');
                setTimeout(() => {
                    window.location.href = '/login';
                }, 2000);
                return;
            }
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        console.log('Response data:', data);
        
        if (data.success) {
            const btn = document.getElementById(`like_btn_${productSlug}`);
            const icon = btn.querySelector('svg');
            const text = btn.querySelector('span');
            const likesCountElement = document.getElementById(`likes_count_${productSlug}`);
            
            // Compatibilité avec les deux formats de réponse
            const isLiked = data.data.is_liked || data.data.liked;
            const likesCount = data.data.likes_count || 0;
            
            // Mettre à jour le bouton
            if (isLiked) {
                icon.classList.remove('text-gray-400');
                icon.classList.add('text-red-500');
                icon.setAttribute('fill', 'currentColor');
                text.textContent = 'J\'aime';
                btn.classList.add('text-red-500', 'border-red-300');
                btn.classList.remove('text-gray-700');
            } else {
                icon.classList.remove('text-red-500');
                icon.classList.add('text-gray-400');
                icon.setAttribute('fill', 'none');
                text.textContent = 'Aimer';
                btn.classList.remove('text-red-500', 'border-red-300');
                btn.classList.add('text-gray-700');
            }
            
            // Mettre à jour le compteur
            if (likesCountElement) {
                likesCountElement.textContent = likesCount;
            }
            
            showNotification(data.message, 'success');
        } else {
            showNotification(data.message || 'Erreur lors de l\'action', 'error');
        }
    } catch (error) {
        console.error('Erreur détaillée:', error);
        
        // Afficher une erreur plus spécifique
        if (error.message.includes('404')) {
            showNotification('Produit non trouvé', 'error');
        } else if (error.message.includes('401')) {
            showNotification('Vous devez être connecté pour effectuer cette action', 'error');
        } else if (error.message.includes('500')) {
            showNotification('Erreur serveur. Veuillez réessayer plus tard.', 'error');
        } else {
            showNotification('Erreur lors de l\'action. Vérifiez votre connexion.', 'error');
        }
    }
}

async function toggleWishlist(productSlug) {
    try {
        const response = await fetch(`/web/wishlist/products/${productSlug}/toggle`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        });

        // Vérifier si la réponse est ok
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        
        if (data.success) {
            const btn = document.getElementById(`wishlist_btn_${productSlug}`);
            const icon = btn.querySelector('svg');
            const text = btn.querySelector('span');
            
            // Compatibilité avec les deux formats de réponse
            const isWishlisted = data.data.is_wishlisted || data.data.in_wishlist;
            
            if (isWishlisted) {
                icon.classList.remove('text-gray-400');
                icon.classList.add('text-blue-500');
                icon.setAttribute('fill', 'currentColor');
                text.textContent = 'Dans ma wishlist';
            } else {
                icon.classList.remove('text-blue-500');
                icon.classList.add('text-gray-400');
                icon.setAttribute('fill', 'none');
                text.textContent = 'Ajouter à ma wishlist';
            }
            
            showNotification(data.message, 'success');
        } else {
            showNotification(data.message || 'Erreur lors de l\'action', 'error');
        }
    } catch (error) {
        console.error('Erreur détaillée:', error);
        
        // Afficher une erreur plus spécifique
        if (error.message.includes('404')) {
            showNotification('Produit non trouvé', 'error');
        } else if (error.message.includes('401')) {
            showNotification('Vous devez être connecté pour effectuer cette action', 'error');
        } else if (error.message.includes('500')) {
            showNotification('Erreur serveur. Veuillez réessayer plus tard.', 'error');
        } else {
            showNotification('Erreur lors de l\'action. Vérifiez votre connexion.', 'error');
        }
    }
}

// Fonctions de partage
function shareOnFacebook() {
    const url = encodeURIComponent(window.location.href);
    const title = encodeURIComponent('{{ $product->name }}');
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}&t=${title}`, '_blank', 'width=600,height=400');
}

function shareOnTwitter() {
    const url = encodeURIComponent(window.location.href);
    const text = encodeURIComponent('{{ $product->name }} - {{ $product->short_description }}');
    window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}`, '_blank', 'width=600,height=400');
}

function copyLink() {
    navigator.clipboard.writeText(window.location.href).then(() => {
        showNotification('Lien copié dans le presse-papier !', 'success');
    }).catch(() => {
        showNotification('Erreur lors de la copie du lien', 'error');
    });
}

// Fonctions utilitaires
function updateCartCount(count) {
    const cartCountElement = document.querySelector('.cart-count');
    if (cartCountElement) {
        cartCountElement.textContent = count;
        cartCountElement.style.display = count > 0 ? 'inline' : 'none';
    }
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 transition-all duration-300 ${
        type === 'success' ? 'bg-green-500 text-white' : 
        type === 'error' ? 'bg-red-500 text-white' : 
        'bg-blue-500 text-white'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script>
@endpush
@endsection
