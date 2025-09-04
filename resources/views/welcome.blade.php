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

    <!-- Products Showcase Section -->
    <section class="py-20 bg-gradient-to-br from-slate-50 via-green-50 to-orange-50 relative overflow-hidden">
        <!-- Background decorative elements -->
        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent"></div>
        <div class="absolute top-10 left-10 w-32 h-32 bg-green-200/20 rounded-full blur-xl"></div>
        <div class="absolute bottom-10 right-10 w-40 h-40 bg-orange-200/20 rounded-full blur-xl"></div>
        
        <div class="container mx-auto px-6 relative z-10">
            <!-- Enhanced Title Section -->
            <div class="text-center mb-16">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-green-400 via-green-500 to-green-600 rounded-3xl shadow-2xl shadow-green-200/50 mb-6 animate-pulse">
                    <span class="text-3xl">🌱</span>
                </div>
                <h2 class="text-5xl md:text-6xl font-black text-transparent bg-clip-text bg-gradient-to-r from-green-600 via-orange-500 to-green-700 mb-4 tracking-tight">
                    {{ __('welcome.our_products_title') }}
                </h2>
                <div class="h-1 w-24 bg-gradient-to-r from-green-500 to-orange-500 mx-auto rounded-full mb-6"></div>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto leading-relaxed font-medium">
                    {{ __('welcome.our_products_subtitle') }}
                </p>
            </div>
            
            @if($randomProducts->count() > 0)
            <!-- Premium Carousel Container -->
            <div class="relative max-w-7xl mx-auto">
                <!-- Carousel Navigation Buttons -->
                <button class="carousel-nav carousel-prev absolute left-4 top-1/2 -translate-y-1/2 z-20 w-14 h-14 bg-white/90 backdrop-blur-sm hover:bg-white shadow-xl hover:shadow-2xl rounded-full flex items-center justify-center transition-all duration-300 group hover:scale-110">
                    <svg class="w-6 h-6 text-gray-700 group-hover:text-green-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                <button class="carousel-nav carousel-next absolute right-4 top-1/2 -translate-y-1/2 z-20 w-14 h-14 bg-white/90 backdrop-blur-sm hover:bg-white shadow-xl hover:shadow-2xl rounded-full flex items-center justify-center transition-all duration-300 group hover:scale-110">
                    <svg class="w-6 h-6 text-gray-700 group-hover:text-green-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
                
                <!-- Enhanced Carousel Container -->
                <div class="overflow-hidden rounded-3xl shadow-2xl bg-gradient-to-br from-white via-gray-50 to-white border border-gray-100">
                    <div id="productCarousel" class="flex transition-all duration-700 ease-out">
                        @foreach($randomProducts as $index => $product)
                        <div class="w-full flex-shrink-0">
                            <div class="bg-gradient-to-br from-white via-gray-50 to-white overflow-hidden group hover:shadow-2xl transition-all duration-500 mx-6 my-8 rounded-2xl border border-gray-100">
                                <div class="grid lg:grid-cols-2 gap-8">
                                    <!-- Premium Product Image Section -->
                                    <div class="relative h-80 lg:h-96 bg-gradient-to-br from-green-50 via-orange-50 to-green-100 rounded-2xl overflow-hidden group-hover:scale-[1.02] transition-transform duration-500">
                                        @if($product->main_image)
                                            <img src="{{ Storage::url($product->main_image) }}" 
                                                 alt="{{ $product->image_alt ?? $product->name }}" 
                                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <div class="text-9xl opacity-40 group-hover:scale-110 transition-transform duration-500">🌱</div>
                                            </div>
                                        @endif
                                        
                                        <!-- Gradient Overlay -->
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                        
                                        <!-- Enhanced Category Badge -->
                                        <div class="absolute top-6 left-6">
                                            <span class="inline-flex items-center bg-white/95 backdrop-blur-sm text-green-700 text-sm font-bold px-4 py-2 rounded-full shadow-lg border border-green-100">
                                                <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                                                {{ $product->category->name }}
                                            </span>
                                        </div>
                                        
                                        <!-- Enhanced Type Badge -->
                                        <div class="absolute top-6 right-6">
                                            <span class="inline-flex items-center text-white text-sm font-bold px-4 py-2 rounded-full shadow-lg backdrop-blur-sm
                                                @if($product->type === 'sale') bg-gradient-to-r from-blue-500 to-blue-600
                                                @elseif($product->type === 'rental') bg-gradient-to-r from-purple-500 to-purple-600  
                                                @else bg-gradient-to-r from-indigo-500 to-indigo-600 @endif">
                                                @if($product->type === 'sale') 
                                                    🛒 {{ __('welcome.purchase') }}
                                                @elseif($product->type === 'rental')
                                                    📅 {{ __("app.content.rental") }}
                                                @else 
                                                    🔄 {{ __('welcome.purchase') }}/{{ __("app.content.rental") }}
                                                @endif
                                            </span>
                                        </div>
                                        
                                        <!-- Floating Action Preview -->
                                        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transform translate-y-4 group-hover:translate-y-0 transition-all duration-300">
                                            <div class="flex items-center space-x-3 bg-white/95 backdrop-blur-sm rounded-full px-6 py-3 shadow-xl">
                                                <span class="text-sm font-medium text-gray-700">Aperçu rapide</span>
                                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Premium Product Content Section -->
                                    <div class="p-8 lg:p-10 flex flex-col justify-center space-y-6">
                                        <!-- Product Title with Animation -->
                                        <div>
                                            <h3 class="text-3xl lg:text-4xl font-black text-gray-800 mb-3 group-hover:text-green-700 transition-colors duration-300 leading-tight">
                                                {{ $product->translated_name }}
                                            </h3>
                                            <div class="h-1 w-16 bg-gradient-to-r from-green-500 to-orange-500 rounded-full group-hover:w-24 transition-all duration-300"></div>
                                        </div>
                                        
                                        <!-- Enhanced Description -->
                                        <p class="text-gray-600 text-lg leading-relaxed line-clamp-3 group-hover:text-gray-700 transition-colors duration-300">
                                            {{ $product->translated_short_description ?: $product->translated_description }}
                                        </p>
                                        
                                        <!-- Premium Price Display -->
                                        <div class="flex items-end justify-between">
                                            <div class="space-y-1">
                                                <div class="text-4xl lg:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-orange-500">
                                                    @if($product->type === 'rental')
                                                        {{ number_format($product->rental_price_per_day ?? 0, 2) }}€
                                                    @elseif($product->type === 'both')
                                                        {{ number_format($product->price, 2) }}€
                                                    @else
                                                        {{ number_format($product->price, 2) }}€
                                                    @endif
                                                </div>
                                                <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">
                                                    @if($product->type === 'rental')
                                                        {{ __('welcome.per_day') }}
                                                    @elseif($product->type === 'both')
                                                        par {{ $product->unit_symbol }}
                                                    @else
                                                        par {{ $product->unit_symbol }}
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            @if($product->type === 'rental' && $product->deposit_amount)
                                            <!-- Premium Deposit Display -->
                                            <div class="text-right">
                                                <div class="text-sm text-gray-500 mb-1">Caution</div>
                                                <div class="text-xl font-bold text-gray-700">{{ number_format($product->deposit_amount, 2) }}€</div>
                                            </div>
                                            @endif
                                        </div>
                                        
                                        <!-- Enhanced Stock Status -->
                                        <div class="flex items-center">
                                            @if($product->quantity > $product->low_stock_threshold)
                                                <div class="inline-flex items-center bg-gradient-to-r from-green-100 to-green-50 text-green-800 px-4 py-2 rounded-full border border-green-200">
                                                    <div class="w-3 h-3 bg-green-500 rounded-full mr-3 animate-pulse"></div>
                                                    <span class="font-semibold">{{ __("app.content.in_stock") }}</span>
                                                    <span class="ml-2 text-green-600 font-bold">({{ $product->quantity }})</span>
                                                </div>
                                            @elseif($product->quantity > $product->out_of_stock_threshold)
                                                <div class="inline-flex items-center bg-gradient-to-r from-orange-100 to-orange-50 text-orange-800 px-4 py-2 rounded-full border border-orange-200">
                                                    <div class="w-3 h-3 bg-orange-500 rounded-full mr-3 animate-pulse"></div>
                                                    <span class="font-semibold">{{ __("app.content.limited_stock") }}</span>
                                                    <span class="ml-2 text-orange-600 font-bold">({{ $product->quantity }})</span>
                                                </div>
                                            @else
                                                <div class="inline-flex items-center bg-gradient-to-r from-red-100 to-red-50 text-red-800 px-4 py-2 rounded-full border border-red-200">
                                                    <div class="w-3 h-3 bg-red-500 rounded-full mr-3 animate-pulse"></div>
                                                    <span class="font-semibold">{{ __("app.content.low_stock") }}</span>
                                                    <span class="ml-2 text-red-600 font-bold">({{ $product->quantity }})</span>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <!-- Premium Action Button -->
                                        <div class="pt-4">
                                            <a href="{{ route('products.show', $product) }}" 
                                               class="group/btn inline-flex items-center justify-center w-full bg-gradient-to-r from-green-600 via-green-500 to-green-600 hover:from-green-700 hover:via-green-600 hover:to-green-700 text-white px-8 py-4 rounded-2xl text-lg font-bold transition-all duration-300 shadow-lg hover:shadow-2xl hover:shadow-green-200/50 hover:-translate-y-1 border border-green-500/20">
                                                <span class="mr-3 text-xl">
                                                    @if($product->type === 'sale')
                                                        🛒
                                                    @elseif($product->type === 'rental')
                                                        📅
                                                    @else
                                                        🔍
                                                    @endif
                                                </span>
                                                <span class="group-hover/btn:mr-1 transition-all duration-200">
                                                    @if($product->type === 'sale')
                                                        {{ __("app.content.buy_product") }}
                                                    @elseif($product->type === 'rental')
                                                        {{ __("app.content.rent_product") }}
                                                    @else
                                                        {{ __("app.content.view_options") }}
                                                    @endif
                                                </span>
                                                <svg class="w-5 h-5 ml-2 group-hover/btn:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- Premium Progress Indicators -->
                <div class="flex justify-center mt-10 space-x-3">
                    @foreach($randomProducts as $index => $product)
                    <button class="carousel-indicator group relative" data-slide="{{ $index }}" data-product-name="{{ $product->name }}">
                        <div class="w-4 h-4 rounded-full bg-gray-300 group-hover:bg-gray-400 transition-all duration-300 relative overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-r from-green-500 to-orange-500 rounded-full transform scale-0 group-hover:scale-100 transition-transform duration-300"></div>
                        </div>
                        <!-- Tooltip -->
                        <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-3 opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none">
                            <div class="bg-gray-900 text-white text-xs font-medium px-3 py-2 rounded-lg whitespace-nowrap shadow-xl">
                                {{ $product->translated_name }}
                                <div class="absolute top-full left-1/2 -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-900"></div>
                            </div>
                        </div>
                    </button>
                    @endforeach
                </div>
                
                <!-- Progress Bar -->
                <div class="mt-8 max-w-md mx-auto">
                    <div class="h-1 bg-gray-200 rounded-full overflow-hidden">
                        <div class="carousel-progress h-full bg-gradient-to-r from-green-500 to-orange-500 rounded-full transition-all duration-700 ease-out" style="width: {{ 100 / $randomProducts->count() }}%"></div>
                    </div>
                </div>
            </div>
            
            <!-- Enhanced CTA Button -->
            <div class="text-center mt-16">
                <a href="{{ route('products.index') }}" 
                   class="group inline-flex items-center bg-gradient-to-r from-green-600 via-green-500 to-green-600 hover:from-green-700 hover:via-green-600 hover:to-green-700 text-white px-12 py-5 rounded-2xl text-xl font-bold transition-all duration-300 shadow-2xl hover:shadow-3xl hover:shadow-green-200/50 hover:-translate-y-2 border border-green-500/20">
                    <span class="text-2xl mr-4 group-hover:scale-110 transition-transform duration-200">📖</span>
                    <span class="group-hover:mr-2 transition-all duration-200">{{ __("app.welcome.view_catalog") }}</span>
                    <svg class="w-6 h-6 ml-3 group-hover:translate-x-2 transition-transform duration-200" fill="currentColor" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
            
            @else
            <!-- Enhanced Fallback if no products -->
            <div class="text-center py-20">
                <div class="inline-flex items-center justify-center w-32 h-32 bg-gradient-to-br from-green-100 via-orange-100 to-green-200 rounded-full mb-8 shadow-2xl">
                    <span class="text-6xl animate-bounce">🌱</span>
                </div>
                <h3 class="text-3xl font-bold text-gray-800 mb-4">{{ __("app.content.products_in_preparation") }}</h3>
                <p class="text-xl text-gray-600 mb-8 max-w-md mx-auto">{{ __("app.content.products_coming_soon") }}</p>
                <a href="{{ route('products.index') }}" 
                   class="inline-flex items-center bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-8 py-4 rounded-2xl text-lg font-bold transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-1">
                    <span class="mr-3">🔍</span>
                    {{ __("app.content.view_all_products") }}
                </a>
            </div>
            @endif
        </div>
        
        <!-- Enhanced JavaScript for Premium Carousel -->
        @if($randomProducts->count() > 0)
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const carousel = document.getElementById('productCarousel');
                const indicators = document.querySelectorAll('.carousel-indicator');
                const progressBar = document.querySelector('.carousel-progress');
                const prevBtn = document.querySelector('.carousel-prev');
                const nextBtn = document.querySelector('.carousel-next');
                const totalSlides = {{ $randomProducts->count() }};
                let currentSlide = 0;
                let autoplayInterval;
                let isTransitioning = false;

                // Update indicators with smooth animation
                function updateIndicators() {
                    indicators.forEach((indicator, index) => {
                        const dot = indicator.querySelector('div');
                        const innerGradient = dot.querySelector('div');
                        
                        if (index === currentSlide) {
                            dot.classList.remove('bg-gray-300');
                            dot.classList.add('bg-gradient-to-r', 'from-green-500', 'to-orange-500');
                            innerGradient.classList.add('scale-100');
                        } else {
                            dot.classList.add('bg-gray-300');
                            dot.classList.remove('bg-gradient-to-r', 'from-green-500', 'to-orange-500');
                            innerGradient.classList.remove('scale-100');
                        }
                    });
                }

                // Update progress bar
                function updateProgressBar() {
                    const progressWidth = ((currentSlide + 1) / totalSlides) * 100;
                    progressBar.style.width = progressWidth + '%';
                }

                // Go to specific slide with enhanced animation
                function goToSlide(slideIndex, direction = 'next') {
                    if (isTransitioning) return;
                    
                    isTransitioning = true;
                    currentSlide = slideIndex;
                    
                    // Enhanced transform with better easing
                    const translateValue = -currentSlide * 100;
                    carousel.style.transform = `translateX(${translateValue}%)`;
                    
                    updateIndicators();
                    updateProgressBar();
                    
                    // Add subtle animation feedback
                    carousel.style.filter = 'brightness(1.05)';
                    setTimeout(() => {
                        carousel.style.filter = 'brightness(1)';
                        isTransitioning = false;
                    }, 350);
                }

                // Previous slide
                function prevSlide() {
                    const prevIndex = currentSlide === 0 ? totalSlides - 1 : currentSlide - 1;
                    goToSlide(prevIndex, 'prev');
                }

                // Next slide
                function nextSlide() {
                    const nextIndex = (currentSlide + 1) % totalSlides;
                    goToSlide(nextIndex, 'next');
                }

                // Enhanced autoplay with pause detection
                function startAutoplay() {
                    autoplayInterval = setInterval(() => {
                        if (!document.hidden && !isTransitioning) {
                            nextSlide();
                        }
                    }, 4000); // Slightly longer for better UX
                }

                // Stop autoplay
                function stopAutoplay() {
                    clearInterval(autoplayInterval);
                }

                // Initialize carousel state
                updateIndicators();
                updateProgressBar();

                // Navigation button events
                if (prevBtn) {
                    prevBtn.addEventListener('click', () => {
                        stopAutoplay();
                        prevSlide();
                        setTimeout(startAutoplay, 4000);
                    });
                }

                if (nextBtn) {
                    nextBtn.addEventListener('click', () => {
                        stopAutoplay();
                        nextSlide();
                        setTimeout(startAutoplay, 4000);
                    });
                }

                // Enhanced indicator events
                indicators.forEach((indicator, index) => {
                    indicator.addEventListener('click', () => {
                        if (index !== currentSlide) {
                            stopAutoplay();
                            goToSlide(index);
                            setTimeout(startAutoplay, 4000);
                        }
                    });
                });

                // Keyboard navigation
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'ArrowLeft') {
                        stopAutoplay();
                        prevSlide();
                        setTimeout(startAutoplay, 4000);
                    } else if (e.key === 'ArrowRight') {
                        stopAutoplay();
                        nextSlide();
                        setTimeout(startAutoplay, 4000);
                    }
                });

                // Touch/Swipe support for mobile
                let startX = 0;
                let startY = 0;
                let isSwipe = false;

                carousel.addEventListener('touchstart', (e) => {
                    startX = e.touches[0].clientX;
                    startY = e.touches[0].clientY;
                    isSwipe = true;
                });

                carousel.addEventListener('touchmove', (e) => {
                    if (!isSwipe) return;
                    
                    const currentX = e.touches[0].clientX;
                    const currentY = e.touches[0].clientY;
                    const diffX = startX - currentX;
                    const diffY = startY - currentY;
                    
                    // Prevent vertical scroll if horizontal swipe
                    if (Math.abs(diffX) > Math.abs(diffY)) {
                        e.preventDefault();
                    }
                });

                carousel.addEventListener('touchend', (e) => {
                    if (!isSwipe) return;
                    
                    const endX = e.changedTouches[0].clientX;
                    const diffX = startX - endX;
                    
                    if (Math.abs(diffX) > 50) { // Minimum swipe distance
                        stopAutoplay();
                        
                        if (diffX > 0) {
                            nextSlide(); // Swipe left = next
                        } else {
                            prevSlide(); // Swipe right = prev
                        }
                        
                        setTimeout(startAutoplay, 4000);
                    }
                    
                    isSwipe = false;
                });

                // Enhanced hover events with better UX
                carousel.addEventListener('mouseenter', () => {
                    stopAutoplay();
                    carousel.style.transform += ' scale(1.005)';
                });

                carousel.addEventListener('mouseleave', () => {
                    startAutoplay();
                    carousel.style.transform = carousel.style.transform.replace(' scale(1.005)', '');
                });

                // Pause on window focus loss
                document.addEventListener('visibilitychange', () => {
                    if (document.hidden) {
                        stopAutoplay();
                    } else {
                        startAutoplay();
                    }
                });

                // Start autoplay
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
