@extends('layouts.app')

@section('title', 'FarmShop - Votre marketplace agricole')

@section('content')
<div x-data="farmShopHome">
    <!-- Hero Section -->
    <section class="hero-bg bg-gradient-to-br from-orange-200 via-green-100 to-orange-300 py-20">
        <div class="container mx-auto px-6 text-center">
            <h1 class="text-5xl md:text-6xl font-bold text-farm-green-800 mb-6">
                🌾 Bienvenue chez FarmShop
            </h1>
            <p class="text-xl md:text-2xl text-farm-orange-700 mb-8 max-w-3xl mx-auto">
                Votre marketplace agricole de confiance pour acheter et louer du matériel agricole de qualité
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('products.index') }}" class="bg-farm-green-600 hover:bg-farm-green-700 text-white px-8 py-4 rounded-lg text-lg font-semibold transition-colors shadow-lg hover:shadow-xl">
                    🛒 Découvrir nos produits
                </a>
                <a href="{{ route('rentals.index') }}" class="border-2 border-farm-orange-500 text-farm-orange-600 hover:bg-farm-orange-500 hover:text-white px-8 py-4 rounded-lg text-lg font-semibold transition-colors">
                    📅 Louer du matériel
                </a>
            </div>
            
            @guest
            <!-- Raccourci connexion/inscription pour visiteurs -->
            <div class="mt-8 p-4 bg-white/80 backdrop-blur-sm rounded-lg max-w-md mx-auto">
                <p class="text-farm-green-700 font-medium mb-3">👤 Nouveau client ?</p>
                <div class="flex gap-3">
                    <a href="{{ route('register') }}" class="flex-1 bg-farm-orange-500 hover:bg-farm-orange-600 text-white px-4 py-2 rounded-lg text-center font-medium transition-colors">
                        Créer un compte
                    </a>
                    <a href="{{ route('login') }}" class="flex-1 border border-farm-green-500 text-farm-green-700 hover:bg-farm-green-500 hover:text-white px-4 py-2 rounded-lg text-center font-medium transition-colors">
                        Se connecter
                    </a>
                </div>
            </div>
            @endguest
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 bg-gradient-to-r from-green-100 via-orange-50 to-green-100">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center text-farm-green-800 mb-12">
                Pourquoi choisir FarmShop ?
            </h2>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center p-6 rounded-lg shadow-md border border-farm-green-200 hover:border-farm-orange-300 transition-colors">
                    <div class="text-5xl mb-4">🚜</div>
                    <h3 class="text-2xl font-semibold text-farm-green-700 mb-4">Matériel de Qualité</h3>
                    <p class="text-farm-orange-600">
                        Tous nos équipements sont rigoureusement sélectionnés et entretenus pour garantir performance et fiabilité.
                    </p>
                </div>
                <div class="text-center p-6 rounded-lg shadow-md border border-farm-orange-200 hover:border-farm-green-300 transition-colors">
                    <div class="text-5xl mb-4">⚡</div>
                    <h3 class="text-2xl font-semibold text-farm-orange-700 mb-4">Disponibilité Rapide</h3>
                    <p class="text-farm-green-600">
                        Achat immédiat ou location flexible selon vos besoins. Livraison rapide dans toute la région.
                    </p>
                </div>
                <div class="text-center p-6 rounded-lg shadow-md border border-farm-green-200 hover:border-farm-orange-300 transition-colors">
                    <div class="text-5xl mb-4">🛡️</div>
                    <h3 class="text-2xl font-semibold text-farm-green-700 mb-4">Paiement Sécurisé</h3>
                    <p class="text-farm-orange-600">
                        Transactions sécurisées avec Stripe. Vos données personnelles et bancaires sont protégées.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-16 bg-gradient-to-br from-orange-100 via-green-50 to-orange-200">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center text-farm-green-800 mb-4">
                🌱 Les produits que nous vous proposons
            </h2>
            <p class="text-center text-farm-orange-600 mb-12 text-lg">
                Découvrez notre sélection de produits fermiers de qualité
            </p>
            @if($randomProducts->count() > 0)
            <!-- Carrousel de produits -->
            <div class="relative max-w-6xl mx-auto">
                <div class="overflow-hidden rounded-lg">
                    <div id="productCarousel" class="flex transition-transform duration-500 ease-in-out">
                        @foreach($randomProducts as $index => $product)
                        <div class="w-full flex-shrink-0">
                            <div class="bg-white rounded-lg shadow-lg overflow-hidden mx-4">
                                <div class="grid md:grid-cols-2 gap-6">
                                    <!-- Image du produit -->
                                    <div class="h-64 md:h-80 bg-gradient-to-br from-farm-green-100 to-farm-orange-100 flex items-center justify-center relative">
                                        @if($product->main_image)
                                            <img src="{{ Storage::url($product->main_image) }}" 
                                                 alt="{{ $product->image_alt ?? $product->name }}" 
                                                 class="w-full h-full object-cover">
                                        @else
                                            <div class="text-8xl text-farm-green-400">🌱</div>
                                        @endif
                                        
                                        <!-- Badge catégorie -->
                                        <span class="absolute top-4 left-4 bg-farm-green-600 text-white text-sm px-3 py-1 rounded-full font-medium">
                                            {{ $product->category->name }}
                                        </span>
                                        
                                        <!-- Badge type (achat/location) -->
                                        <span class="absolute top-4 right-4 text-white text-sm px-3 py-1 rounded-full font-medium
                                            @if($product->type === 'sale') bg-blue-600
                                            @elseif($product->type === 'rental') bg-purple-600  
                                            @else bg-indigo-600 @endif">
                                            @if($product->type === 'sale') 
                                                🛒 Achat
                                            @elseif($product->type === 'rental')
                                                📅 Location
                                            @else 
                                                🔄 Achat/Location
                                            @endif
                                        </span>
                                    </div>
                                    
                                    <!-- Contenu du produit -->
                                    <div class="p-6 flex flex-col justify-center">
                                        <h3 class="text-2xl font-bold text-farm-green-700 mb-3">
                                            {{ $product->name }}
                                        </h3>
                                        <p class="text-farm-orange-600 text-lg mb-4 line-clamp-3">
                                            {{ $product->short_description }}
                                        </p>
                                        
                                        <!-- Prix et unité -->
                                        <div class="flex items-center justify-between mb-4">
                                            <div class="text-3xl font-bold text-farm-green-600">
                                                @if($product->type === 'rental')
                                                    {{ number_format($product->rental_price_per_day ?? 0, 2) }}€
                                                @elseif($product->type === 'both')
                                                    {{ number_format($product->price, 2) }}€
                                                @else
                                                    {{ number_format($product->price, 2) }}€
                                                @endif
                                            </div>
                                            <div class="text-lg text-gray-500">
                                                @if($product->type === 'rental')
                                                    / jour
                                                @elseif($product->type === 'both')
                                                    / {{ $product->unit_symbol }}
                                                @else
                                                    / {{ $product->unit_symbol }}
                                                @endif
                                            </div>
                                        </div>
                                        
                                        @if($product->type === 'rental' && $product->deposit_amount)
                                        <!-- Caution pour les locations -->
                                        <div class="text-sm text-gray-600 mb-3">
                                            💳 Caution: {{ number_format($product->deposit_amount, 2) }}€
                                        </div>
                                        @endif
                                        
                                        <!-- Stock -->
                                        <div class="flex items-center mb-6">
                                            @if($product->quantity > $product->low_stock_threshold)
                                                <span class="inline-flex items-center text-sm text-green-700 bg-green-100 px-3 py-1 rounded-full">
                                                    ✅ En stock ({{ $product->quantity }})
                                                </span>
                                            @elseif($product->quantity > $product->out_of_stock_threshold)
                                                <span class="inline-flex items-center text-sm text-orange-700 bg-orange-100 px-3 py-1 rounded-full">
                                                    ⚠️ Stock limité ({{ $product->quantity }})
                                                </span>
                                            @else
                                                <span class="inline-flex items-center text-sm text-red-700 bg-red-100 px-3 py-1 rounded-full">
                                                    ❌ Stock faible ({{ $product->quantity }})
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <!-- Bouton voir le produit -->
                                        <a href="{{ route('products.show', $product) }}" 
                                           class="inline-flex items-center justify-center bg-farm-green-600 hover:bg-farm-green-700 text-white px-6 py-3 rounded-lg text-lg font-semibold transition-colors">
                                            @if($product->type === 'sale')
                                                🛒 Acheter ce produit
                                            @elseif($product->type === 'rental')
                                                📅 Louer ce produit
                                            @else
                                                🔍 Voir les options
                                            @endif
                                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- Indicateurs de carrousel -->
                <div class="flex justify-center mt-6 space-x-2">
                    @foreach($randomProducts as $index => $product)
                    <button class="carousel-indicator w-3 h-3 rounded-full transition-colors duration-300" 
                            data-slide="{{ $index }}"
                            data-product-name="{{ $product->name }}"></button>
                    @endforeach
                </div>
            </div>
            
            <!-- Bouton pour voir tous les produits -->
            <div class="text-center mt-12">
                <a href="{{ route('products.index') }}" 
                   class="inline-flex items-center bg-farm-green-700 hover:bg-farm-green-800 text-white px-8 py-4 rounded-lg text-lg font-bold transition-colors shadow-lg hover:shadow-xl border-2 border-white">
                    <span class="text-white font-bold">📖 Accéder au catalogue</span>
                    <svg class="w-5 h-5 ml-2 text-white" fill="currentColor" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
            
            @else
            <!-- Fallback si aucun produit -->
            <div class="text-center py-12">
                <div class="text-6xl mb-4">🌱</div>
                <h3 class="text-xl font-semibold text-farm-green-700 mb-2">Produits en préparation</h3>
                <p class="text-farm-orange-600">Nos produits seront bientôt disponibles !</p>
                <a href="{{ route('products.index') }}" 
                   class="inline-block mt-4 bg-farm-green-600 hover:bg-farm-green-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                    Voir tous nos produits
                </a>
            </div>
            @endif
        </div>
        
        <!-- JavaScript pour le carrousel automatique -->
        @if($randomProducts->count() > 0)
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const carousel = document.getElementById('productCarousel');
                const indicators = document.querySelectorAll('.carousel-indicator');
                const totalSlides = {{ $randomProducts->count() }};
                let currentSlide = 0;
                let autoplayInterval;

                // Mettre à jour les indicateurs
                function updateIndicators() {
                    indicators.forEach((indicator, index) => {
                        if (index === currentSlide) {
                            indicator.classList.remove('bg-gray-300');
                            indicator.classList.add('bg-farm-orange-500');
                        } else {
                            indicator.classList.remove('bg-farm-orange-500');
                            indicator.classList.add('bg-gray-300');
                        }
                    });
                }

                // Aller à un slide spécifique
                function goToSlide(slideIndex) {
                    currentSlide = slideIndex;
                    const translateValue = -currentSlide * 100;
                    carousel.style.transform = `translateX(${translateValue}%)`;
                    updateIndicators();
                }

                // Slide suivant
                function nextSlide() {
                    currentSlide = (currentSlide + 1) % totalSlides;
                    goToSlide(currentSlide);
                }

                // Démarrer l'autoplay
                function startAutoplay() {
                    autoplayInterval = setInterval(nextSlide, 3500); // Change toutes les 3.5 secondes
                }

                // Arrêter l'autoplay
                function stopAutoplay() {
                    clearInterval(autoplayInterval);
                }

                // Initialiser les indicateurs
                updateIndicators();

                // Event listeners pour les indicateurs
                indicators.forEach((indicator, index) => {
                    indicator.addEventListener('click', () => {
                        stopAutoplay();
                        goToSlide(index);
                        setTimeout(startAutoplay, 3000); // Reprendre après 3 secondes
                    });

                    // Tooltip au survol
                    indicator.addEventListener('mouseenter', () => {
                        const productName = indicator.getAttribute('data-product-name');
                        indicator.setAttribute('title', productName);
                    });
                });

                // Pause au survol du carrousel
                carousel.addEventListener('mouseenter', stopAutoplay);
                carousel.addEventListener('mouseleave', startAutoplay);

                // Démarrer l'autoplay
                startAutoplay();
            });
        </script>
        @endif
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-gradient-to-r from-orange-600 via-green-600 to-orange-700 text-white">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-4xl font-bold mb-6">
                Prêt à moderniser votre exploitation ? 🚀
            </h2>
            <p class="text-xl mb-8 max-w-2xl mx-auto">
                Rejoignez des milliers d'agriculteurs qui font confiance à FarmShop pour leurs équipements agricoles.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/register" class="bg-white text-farm-green-600 hover:bg-gray-100 px-8 py-4 rounded-lg text-lg font-semibold transition-colors">
                    📝 Créer un compte
                </a>
                <a href="{{ route('products.index') }}" class="border-2 border-white text-white hover:bg-white hover:text-farm-orange-600 px-8 py-4 rounded-lg text-lg font-semibold transition-colors">
                    🔍 Explorer le catalogue
                </a>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-16 bg-gradient-to-r from-green-50 via-orange-100 to-green-50">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="text-4xl font-bold text-farm-green-600 mb-2">500+</div>
                    <p class="text-farm-orange-600">Équipements disponibles</p>
                </div>
                <div>
                    <div class="text-4xl font-bold text-farm-orange-600 mb-2">1200+</div>
                    <p class="text-farm-green-600">Clients satisfaits</p>
                </div>
                <div>
                    <div class="text-4xl font-bold text-farm-green-600 mb-2">98%</div>
                    <p class="text-farm-orange-600">Taux de satisfaction</p>
                </div>
                <div>
                    <div class="text-4xl font-bold text-farm-orange-600 mb-2">24h</div>
                    <p class="text-farm-green-600">Support client</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-16 bg-gradient-to-br from-orange-100 via-green-50 to-orange-100">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center text-farm-green-800 mb-12">
                Ce que disent nos clients
            </h2>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-farm-green-500">
                    <div class="text-farm-orange-400 text-xl mb-4">⭐⭐⭐⭐⭐</div>
                    <p class="text-gray-600 mb-4">
                        "Excellent service ! J'ai trouvé exactement le tracteur qu'il me fallait. Livraison rapide et matériel en parfait état."
                    </p>
                    <div class="font-semibold text-farm-green-700">- Pierre Martin, Agriculteur</div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-farm-orange-500">
                    <div class="text-farm-orange-400 text-xl mb-4">⭐⭐⭐⭐⭐</div>
                    <p class="text-gray-600 mb-4">
                        "La location m'a permis d'essayer avant d'acheter. Très pratique pour les gros équipements !"
                    </p>
                    <div class="font-semibold text-farm-orange-700">- Marie Dubois, Exploitante</div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-farm-green-500">
                    <div class="text-farm-orange-400 text-xl mb-4">⭐⭐⭐⭐⭐</div>
                    <p class="text-gray-600 mb-4">
                        "Support client réactif et professionnel. Je recommande FarmShop à tous mes collègues."
                    </p>
                    <div class="font-semibold text-farm-green-700">- Jean Lefebvre, GAEC</div>
                </div>
            </div>
        </div>
    </section>

    @guest
    <!-- Section Inscription CTA -->
    <section class="py-16 bg-gradient-to-br from-farm-orange-50 to-farm-green-50">
        <div class="container mx-auto px-6 text-center">
            <div class="max-w-3xl mx-auto">
                <h2 class="text-4xl font-bold text-farm-green-800 mb-6">
                    🚀 Rejoignez la communauté FarmShop
                </h2>
                <p class="text-xl text-farm-orange-700 mb-8">
                    Créez votre compte gratuit et profitez de tous nos services : achats, locations, wishlist et bien plus encore !
                </p>
                
                <div class="grid md:grid-cols-3 gap-6 mb-8">
                    <div class="p-4 bg-white rounded-lg shadow-md">
                        <div class="text-3xl mb-3">💝</div>
                        <h3 class="font-semibold text-farm-green-700 mb-2">Offres exclusives</h3>
                        <p class="text-gray-600 text-sm">Accédez à des prix préférentiels et des promotions réservées aux membres</p>
                    </div>
                    <div class="p-4 bg-white rounded-lg shadow-md">
                        <div class="text-3xl mb-3">📋</div>
                        <h3 class="font-semibold text-farm-green-700 mb-2">Suivi des commandes</h3>
                        <p class="text-gray-600 text-sm">Gérez facilement vos achats et locations depuis votre espace personnel</p>
                    </div>
                    <div class="p-4 bg-white rounded-lg shadow-md">
                        <div class="text-3xl mb-3">❤️</div>
                        <h3 class="font-semibold text-farm-green-700 mb-2">Liste de souhaits</h3>
                        <p class="text-gray-600 text-sm">Sauvegardez vos produits favoris et recevez des alertes de disponibilité</p>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('register') }}" class="bg-farm-green-600 hover:bg-farm-green-700 text-white px-8 py-4 rounded-lg text-lg font-semibold transition-colors shadow-lg hover:shadow-xl">
                        🔥 Créer mon compte gratuit
                    </a>
                    <a href="{{ route('login') }}" class="border-2 border-farm-orange-500 text-farm-orange-600 hover:bg-farm-orange-500 hover:text-white px-8 py-4 rounded-lg text-lg font-semibold transition-colors">
                        J'ai déjà un compte
                    </a>
                </div>
                
                <p class="text-sm text-gray-600 mt-4">
                    ⚡ Inscription rapide • 🔒 100% sécurisé • 📧 Pas de spam
                </p>
            </div>
        </div>
    </section>
    @endguest

    <!-- Newsletter Section -->
    <section class="py-16 bg-gradient-to-r from-farm-green-700 to-farm-orange-700 text-white">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold mb-4">
                📧 Restez informé des nouveautés
            </h2>
            <p class="text-lg mb-8">
                Recevez en avant-première nos nouveaux produits et nos offres exclusives
            </p>
            <div class="max-w-md mx-auto flex gap-4">
                <input type="email" placeholder="Votre adresse email" class="flex-1 px-4 py-3 rounded-lg text-gray-800 focus:outline-none focus:ring-2 focus:ring-farm-orange-400">
                <button class="bg-farm-orange-500 hover:bg-farm-orange-600 px-6 py-3 rounded-lg font-semibold transition-colors">
                    S'abonner
                </button>
            </div>
        </div>
    </section>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('farmShopHome', () => ({
        navigateToCategory(category) {
            window.location.href = `/products?category=${category}`;
        }
    }))
})
</script>
@endsection
