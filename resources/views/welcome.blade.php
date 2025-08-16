@extends('layouts.app')

@section('title', __('welcome.hero_title'))

@section('content')
<div x-data="farmShopHome">
    <!-- Hero Section -->
    <section class="hero-bg bg-gradient-to-br from-orange-200 via-green-100 to-orange-300 py-20">
        <div class="container mx-auto px-6 text-center">
            <h1 class="text-5xl md:text-6xl font-bold text-farm-green-800 mb-6">
                🌾 {{ __('welcome.hero_title') }}
            </h1>
            <p class="text-xl md:text-2xl text-farm-orange-700 mb-8 max-w-3xl mx-auto">
                {{ __('welcome.hero_subtitle') }}
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('products.index') }}" class="bg-farm-green-600 hover:bg-farm-green-700 text-white px-8 py-4 rounded-lg text-lg font-semibold transition-colors shadow-lg hover:shadow-xl">
                    🛒 {{ __('welcome.shop_now') }}
                </a>
                <a href="{{ route('rentals.index') }}" class="border-2 border-farm-orange-500 text-farm-orange-600 hover:bg-farm-orange-500 hover:text-white px-8 py-4 rounded-lg text-lg font-semibold transition-colors">
                    📅 {{ __('welcome.rent_equipment') }}
                </a>
            </div>
            
            @guest
            <!-- Encart connexion/inscription premium -->
            <div class="mt-12 relative max-w-lg mx-auto z-10">
                <!-- Fond avec effet glassmorphism -->
                <div class="absolute inset-0 bg-gradient-to-br from-white/40 via-orange-100/30 to-green-100/40 backdrop-blur-xl rounded-3xl -z-10"></div>
                <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white/20 to-transparent rounded-3xl -z-10"></div>
                
                <!-- Contenu principal -->
                <div class="relative z-20 p-8 space-y-6">
                    <!-- Titre avec icône animée -->
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-orange-400 via-orange-500 to-red-500 rounded-2xl shadow-xl shadow-orange-200/50 mb-4 animate-pulse">
                            <svg class="w-8 h-8 text-white animate-bounce" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold bg-gradient-to-r from-orange-600 via-red-500 to-green-600 bg-clip-text text-transparent mb-2">
                            {{ __('welcome.join_farmshop') }}
                        </h3>
                        <p class="text-gray-600 text-sm">{{ __('welcome.trusted_partner') }}</p>
                    </div>
                    
                    <!-- Statistiques -->
                    <div class="grid grid-cols-3 gap-4 py-4">
                        <div class="text-center">
                            <div class="text-xl font-bold text-orange-600">1.2k+</div>
                            <div class="text-xs text-gray-500">{{ __('welcome.farmers') }}</div>
                        </div>
                        <div class="text-center border-x border-gray-200">
                            <div class="text-xl font-bold text-green-600">500+</div>
                            <div class="text-xs text-gray-500">{{ __('welcome.equipment') }}</div>
                        </div>
                        <div class="text-center">
                            <div class="text-xl font-bold text-blue-600">98%</div>
                            <div class="text-xs text-gray-500">{{ __('welcome.satisfaction') }}</div>
                        </div>
                    </div>
                    
                    <!-- Boutons avec design moderne -->
                    <div class="space-y-4 relative z-30">
                        <!-- Bouton principal inscription -->
                        <a href="{{ route('register') }}" class="block w-full relative z-40 group overflow-hidden rounded-2xl bg-gradient-to-r from-orange-500 via-red-500 to-orange-600 p-0.5 transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-orange-200/50">
                            <div class="relative rounded-2xl bg-gradient-to-r from-orange-500 via-red-500 to-orange-600 px-6 py-4 text-white transition-all duration-300">
                                <div class="flex items-center justify-center space-x-3">
                                    <svg class="w-5 h-5 group-hover:rotate-12 transition-transform duration-300" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="font-semibold">{{ __('welcome.create_account') }}</span>
                                </div>
                                <div class="absolute top-1 right-1 bg-yellow-400 text-orange-900 text-xs px-2 py-1 rounded-bl-lg rounded-tr-xl font-bold shadow-sm">
                                    {{ __('welcome.free_badge') }}
                                </div>
                            </div>
                        </a>
                        
                        <!-- Séparateur avec animation -->
                        <div class="relative flex items-center justify-center py-2 z-30">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-300"></div>
                            </div>
                            <div class="relative bg-gradient-to-br from-white via-orange-50 to-green-50">
                                <span class="bg-white px-4 py-1 text-sm text-gray-500 rounded-full border border-gray-200 shadow-sm">
                                    {{ __('welcome.already_member') }}
                                </span>
                            </div>
                        </div>
                        
                        <!-- Bouton secondaire connexion -->
                        <a href="{{ route('login') }}" class="block w-full relative z-40 group overflow-hidden rounded-2xl border-2 border-green-400/50 bg-white/90 backdrop-blur-sm p-3 transition-all duration-300 hover:scale-105 hover:border-green-500 hover:shadow-xl hover:shadow-green-200/50 hover:bg-white">
                            <div class="flex items-center justify-center space-x-3 text-green-700 group-hover:text-green-800 transition-colors">
                                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                </svg>
                                <span class="font-semibold">{{ __('welcome.login_button') }}</span>
                            </div>
                        </a>
                    </div>
                    
                    <!-- Footer avec garanties -->
                    <div class="pt-4 border-t border-gray-200/50 relative z-30">
                        <div class="flex items-center justify-center space-x-6 text-xs text-gray-500">
                            <div class="flex items-center space-x-1">
                                <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span>{{ __('welcome.secured') }}</span>
                            </div>
                            <div class="flex items-center space-x-1">
                                <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span>{{ __('welcome.certified_bio') }}</span>
                            </div>
                            <div class="flex items-center space-x-1">
                                <svg class="w-4 h-4 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                                <span>{{ __('welcome.support_24_7') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endguest
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 bg-gradient-to-r from-green-100 via-orange-50 to-green-100">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center text-farm-green-800 mb-12">
                {{ __('welcome.why_choose_title') }}
            </h2>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center p-6 rounded-lg shadow-md border border-farm-green-200 hover:border-farm-orange-300 transition-colors">
                    <div class="text-5xl mb-4">🚜</div>
                    <h3 class="text-2xl font-semibold text-farm-green-700 mb-4">{{ __('welcome.quality_material_title') }}</h3>
                    <p class="text-farm-orange-600">
                        {{ __('welcome.quality_material_desc') }}
                    </p>
                </div>
                <div class="text-center p-6 rounded-lg shadow-md border border-farm-orange-200 hover:border-farm-green-300 transition-colors">
                    <div class="text-5xl mb-4">⚡</div>
                    <h3 class="text-2xl font-semibold text-farm-orange-700 mb-4">{{ __('welcome.fast_availability_title') }}</h3>
                    <p class="text-farm-green-600">
                        {{ __('welcome.fast_availability_desc') }}
                    </p>
                </div>
                <div class="text-center p-6 rounded-lg shadow-md border border-farm-green-200 hover:border-farm-orange-300 transition-colors">
                    <div class="text-5xl mb-4">🛡️</div>
                    <h3 class="text-2xl font-semibold text-farm-green-700 mb-4">{{ __('welcome.secure_payment_title') }}</h3>
                    <p class="text-farm-orange-600">
                        {{ __('welcome.secure_payment_desc') }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-16 bg-gradient-to-br from-orange-100 via-green-50 to-orange-200">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center text-farm-green-800 mb-4">
                🌱 {{ __('welcome.our_products_title') }}
            </h2>
            <p class="text-center text-farm-orange-600 mb-12 text-lg">
                {{ __('welcome.our_products_subtitle') }}
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
                                                🛒 {{ __('welcome.purchase') }}
                                            @elseif($product->type === 'rental')
                                                📅 {{ __("app.content.rental") }}
                                            @else 
                                                🔄 {{ __('welcome.purchase') }}/{{ __("app.content.rental") }}
                                            @endif
                                        </span>
                                    </div>
                                    
                                    <!-- Contenu du produit -->
                                    <div class="p-6 flex flex-col justify-center">
                                        <h3 class="text-2xl font-bold text-farm-green-700 mb-3">
                                            {{ __('app.product_names.' . $product->slug) ?: $product->name }}
                                        </h3>
                                        <p class="text-farm-orange-600 text-lg mb-4 line-clamp-3">
                                            {{ __('app.product_descriptions.' . $product->slug) ?: $product->short_description }}
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
                                                    / {{ __('welcome.per_day') }}
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
                                            💳 {{ __('welcome.deposit') }}: {{ number_format($product->deposit_amount, 2) }}€
                                        </div>
                                        @endif
                                        
                                        <!-- Stock -->
                                        <div class="flex items-center mb-6">
                                            @if($product->quantity > $product->low_stock_threshold)
                                                <span class="inline-flex items-center text-sm text-green-700 bg-green-100 px-3 py-1 rounded-full">
                                                    ✅ {{ __("app.content.in_stock") }} ({{ $product->quantity }})
                                                </span>
                                            @elseif($product->quantity > $product->out_of_stock_threshold)
                                                <span class="inline-flex items-center text-sm text-orange-700 bg-orange-100 px-3 py-1 rounded-full">
                                                    ⚠️ {{ __("app.content.limited_stock") }} ({{ $product->quantity }})
                                                </span>
                                            @else
                                                <span class="inline-flex items-center text-sm text-red-700 bg-red-100 px-3 py-1 rounded-full">
                                                    ❌ {{ __("app.content.low_stock") }} ({{ $product->quantity }})
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <!-- Bouton voir le produit -->
                                        <a href="{{ route('products.show', $product) }}" 
                                           class="inline-flex items-center justify-center bg-farm-green-600 hover:bg-farm-green-700 text-white px-6 py-3 rounded-lg text-lg font-semibold transition-colors">
                                            @if($product->type === 'sale')
                                                🛒 {{ __("app.content.buy_product") }}
                                            @elseif($product->type === 'rental')
                                                📅 {{ __("app.content.rent_product") }}
                                            @else
                                                🔍 {{ __("app.content.view_options") }}
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
                    <span class="text-white font-bold">📖 {{ __("app.welcome.view_catalog") }}</span>
                    <svg class="w-5 h-5 ml-2 text-white" fill="currentColor" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
            
            @else
            <!-- Fallback si aucun produit -->
            <div class="text-center py-12">
                <div class="text-6xl mb-4">🌱</div>
                <h3 class="text-xl font-semibold text-farm-green-700 mb-2">{{ __("app.content.products_in_preparation") }}</h3>
                <p class="text-farm-orange-600">{{ __("app.content.products_coming_soon") }}</p>
                <a href="{{ route('products.index') }}" 
                   class="inline-block mt-4 bg-farm-green-600 hover:bg-farm-green-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                    {{ __("app.content.view_all_products") }}
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
                {{ __("app.welcome.ready_to_modernize_title") }}
            </h2>
            <p class="text-xl mb-8 max-w-2xl mx-auto">
                {{ __("app.welcome.ready_to_modernize_subtitle") }}
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/register" class="bg-white text-farm-green-600 hover:bg-gray-100 px-8 py-4 rounded-lg text-lg font-semibold transition-colors">
                    📝 {{ __("app.welcome.create_account_action") }}
                </a>
                <a href="{{ route('products.index') }}" class="border-2 border-white text-white hover:bg-white hover:text-farm-orange-600 px-8 py-4 rounded-lg text-lg font-semibold transition-colors">
                    🔍 {{ __("app.welcome.explore_catalog") }}
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
                    <p class="text-farm-orange-600">{{ __("app.welcome.available_equipment") }}</p>
                </div>
                <div>
                    <div class="text-4xl font-bold text-farm-orange-600 mb-2">1200+</div>
                    <p class="text-farm-green-600">{{ __("app.welcome.satisfied_customers") }}</p>
                </div>
                <div>
                    <div class="text-4xl font-bold text-farm-green-600 mb-2">98%</div>
                    <p class="text-farm-orange-600">{{ __("app.welcome.satisfaction_rate") }}</p>
                </div>
                <div>
                    <div class="text-4xl font-bold text-farm-orange-600 mb-2">24h</div>
                    <p class="text-farm-green-600">{{ __("app.welcome.customer_support") }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-16 bg-gradient-to-br from-orange-100 via-green-50 to-orange-100">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center text-farm-green-800 mb-12">
                {{ __("app.welcome.customer_testimonials_title") }}
            </h2>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-farm-green-500">
                    <div class="text-farm-orange-400 text-xl mb-4">⭐⭐⭐⭐⭐</div>
                    <p class="text-gray-600 mb-4">
                        {{ __("app.testimonials.testimonial_1") }}
                    </p>
                    <div class="font-semibold text-farm-green-700">{{ __("app.testimonials.author_1") }}</div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-farm-orange-500">
                    <div class="text-farm-orange-400 text-xl mb-4">⭐⭐⭐⭐⭐</div>
                    <p class="text-gray-600 mb-4">
                        {{ __("app.testimonials.testimonial_2") }}
                    </p>
                    <div class="font-semibold text-farm-orange-700">{{ __("app.testimonials.author_2") }}</div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-farm-green-500">
                    <div class="text-farm-orange-400 text-xl mb-4">⭐⭐⭐⭐⭐</div>
                    <p class="text-gray-600 mb-4">
                        "{{ __("app.welcome.customer_support") }} réactif et professionnel. Je recommande FarmShop à tous mes collègues."
                    </p>
                    <div class="font-semibold text-farm-green-700">{{ __("app.testimonials.author_3") }}</div>
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
                    {{ __("app.welcome.join_community_title") }}
                </h2>
                <p class="text-xl text-farm-orange-700 mb-8">
                    {{ __("app.welcome.join_community_subtitle") }}
                </p>
                
                <div class="grid md:grid-cols-3 gap-6 mb-8">
                    <div class="p-4 bg-white rounded-lg shadow-md">
                        <div class="text-3xl mb-3">💝</div>
                        <h3 class="font-semibold text-farm-green-700 mb-2">{{ __("app.welcome.exclusive_offers_title") }}</h3>
                        <p class="text-gray-600 text-sm">{{ __("app.welcome.exclusive_offers_desc") }}</p>
                    </div>
                    <div class="p-4 bg-white rounded-lg shadow-md">
                        <div class="text-3xl mb-3">📋</div>
                        <h3 class="font-semibold text-farm-green-700 mb-2">{{ __("app.welcome.order_tracking_title") }}</h3>
                        <p class="text-gray-600 text-sm">{{ __("app.welcome.order_tracking_desc") }}</p>
                    </div>
                    <div class="p-4 bg-white rounded-lg shadow-md">
                        <div class="text-3xl mb-3">❤️</div>
                        <h3 class="font-semibold text-farm-green-700 mb-2">{{ __("app.welcome.wishlist_title") }}</h3>
                        <p class="text-gray-600 text-sm">{{ __("app.welcome.wishlist_desc") }}</p>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('register') }}" class="bg-farm-green-600 hover:bg-farm-green-700 text-white px-8 py-4 rounded-lg text-lg font-semibold transition-colors shadow-lg hover:shadow-xl">
                        🔥 {{ __("app.welcome.create_free_account") }}
                    </a>
                    <a href="{{ route('login') }}" class="border-2 border-farm-orange-500 text-farm-orange-600 hover:bg-farm-orange-500 hover:text-white px-8 py-4 rounded-lg text-lg font-semibold transition-colors">
                        {{ __("app.auth.already_have_account") }}
                    </a>
                </div>
                
                <p class="text-sm text-gray-600 mt-4">
                    {{ __("app.auth.registration_benefits") }}
                </p>
            </div>
        </div>
    </section>
    @endguest

    <!-- Newsletter Section -->
    <section class="py-16 bg-gradient-to-r from-farm-green-700 to-farm-orange-700 text-white">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold mb-4">
                {{ __("app.welcome.newsletter_title") }}
            </h2>
            <p class="text-lg mb-8">
                {{ __("app.welcome.newsletter_subtitle") }}
            </p>
            <div class="max-w-md mx-auto flex gap-4">
                <input type="email" placeholder="{{ __("app.form.email_placeholder") }}" class="flex-1 px-4 py-3 rounded-lg text-gray-800 focus:outline-none focus:ring-2 focus:ring-farm-orange-400">
                <button class="bg-farm-orange-500 hover:bg-farm-orange-600 px-6 py-3 rounded-lg font-semibold transition-colors">
                    {{ __("app.form.subscribe") }}
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
