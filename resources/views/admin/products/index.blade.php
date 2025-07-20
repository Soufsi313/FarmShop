@extends('layouts.admin')

@section('title', 'Gestion des Produits - FarmShop Admin')
@section('page-title', 'Gestion des Produits')

@section('content')
<div x-data="productManager">
    <!-- Header avec bouton d'ajout -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 mb-6">
        <div class="px-6 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                        <svg class="h-8 w-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        Catalogue de Produits
                    </h1>
                    <p class="mt-2 text-sm text-gray-600">Gérez votre inventaire de produits pour vente et location</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.products.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-green-700 border border-transparent rounded-lg text-sm font-medium text-white hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Nouveau Produit
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total produits</dt>
                            <dd class="text-2xl font-semibold text-gray-900">{{ $products->total() ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Produits actifs</dt>
                            <dd class="text-2xl font-semibold text-gray-900">{{ $products->where('is_active', true)->count() ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Stock faible</dt>
                            <dd class="text-2xl font-semibold text-gray-900">{{ $products->where('quantity', '<=', 10)->count() ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Rupture stock</dt>
                            <dd class="text-2xl font-semibold text-gray-900">{{ $products->where('quantity', '<=', 0)->count() ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et recherche avancée -->
    <div class="bg-white shadow-lg rounded-xl p-8 mb-6">
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Recherche et Filtres Avancés</h3>
            <p class="text-sm text-gray-600">Trouvez rapidement les produits que vous cherchez dans votre catalogue</p>
        </div>
        
        <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="space-y-2">
                    <label class="flex items-center text-sm font-medium text-gray-700">
                        <svg class="inline w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Recherche générale
                    </label>
                    <div class="relative">
                        <input 
                            type="text" 
                            x-model="searchTerm"
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" 
                            placeholder="Nom, SKU, description...">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="flex items-center text-sm font-medium text-gray-700">
                        <svg class="inline w-4 h-4 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        Catégorie
                    </label>
                    <select 
                        x-model="selectedCategory"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white transition-colors">
                        <option value="">Toutes les catégories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">🏷️ {{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="flex items-center text-sm font-medium text-gray-700">
                        <svg class="inline w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Statut du produit
                    </label>
                    <select 
                        x-model="selectedStatus"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white transition-colors">
                        <option value="">Tous les statuts</option>
                        <option value="active">✅ Produits actifs</option>
                        <option value="inactive">❌ Produits inactifs</option>
                        <option value="featured">⭐ Produits en vedette</option>
                        <option value="low_stock">⚠️ Stock faible (≤10)</option>
                        <option value="out_of_stock">🚫 Rupture de stock</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="flex items-center text-sm font-medium text-gray-700">
                        <svg class="inline w-4 h-4 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"/>
                        </svg>
                        Actions rapides
                    </label>
                    <div class="flex space-x-2">
                        <button 
                            @click="filterProducts()" 
                            class="flex-1 inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-green-600 to-green-700 border border-transparent rounded-lg text-sm font-medium text-white hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Actualiser
                        </button>
                        <button 
                            @click="clearFilters()" 
                            class="px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Filtres avancés (section repliable) -->
            <div class="border-t border-gray-200 pt-6">
                <button 
                    @click="showAdvancedFilters = !showAdvancedFilters"
                    class="flex items-center text-sm font-medium text-gray-600 hover:text-gray-800 transition-colors">
                    <svg class="w-4 h-4 mr-2 transform transition-transform" :class="showAdvancedFilters ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    Filtres avancés
                    <span class="ml-2 text-xs text-gray-500">(Type, Prix, Stock, Actions en masse)</span>
                </button>
                
                <div x-show="showAdvancedFilters" x-collapse class="mt-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 p-4 bg-gray-50 rounded-lg">
                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-medium text-gray-700">
                                <svg class="inline w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                                Type de produit
                            </label>
                            <select 
                                x-model="selectedType"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white text-sm">
                                <option value="">Tous les types</option>
                                <option value="purchase">🛒 Achat uniquement</option>
                                <option value="rental">📅 Location uniquement</option>
                                <option value="both">🔄 Achat et Location</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-medium text-gray-700">
                                <svg class="inline w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                </svg>
                                Gamme de prix
                            </label>
                            <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white text-sm">
                                <option value="">Tous les prix</option>
                                <option value="0-50">💰 0€ - 50€</option>
                                <option value="50-100">💰 50€ - 100€</option>
                                <option value="100-500">💰 100€ - 500€</option>
                                <option value="500+">💰 500€ et plus</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-medium text-gray-700">
                                <svg class="inline w-4 h-4 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                                Niveau de stock
                            </label>
                            <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white text-sm">
                                <option value="">Tous les stocks</option>
                                <option value="high">📦 Stock élevé (>50)</option>
                                <option value="medium">📦 Stock moyen (11-50)</option>
                                <option value="low">⚠️ Stock faible (1-10)</option>
                                <option value="empty">🚫 Rupture (0)</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-medium text-gray-700">
                                <svg class="inline w-4 h-4 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Actions en masse
                            </label>
                            <div class="flex space-x-2">
                                <button class="flex-1 px-3 py-2 bg-green-100 text-green-700 rounded-lg text-xs font-medium hover:bg-green-200 transition-colors">
                                    ✅ Activer sélectionnés
                                </button>
                                <button class="flex-1 px-3 py-2 bg-red-100 text-red-700 rounded-lg text-xs font-medium hover:bg-red-200 transition-colors">
                                    ❌ Désactiver sélectionnés
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Indicateur de chargement -->
            <div x-show="loading" class="flex items-center justify-center py-4">
                <div class="flex items-center text-green-600 text-sm">
                    <svg class="animate-spin -ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Filtrage en cours...
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des produits -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div id="productsTable">
            @include('admin.products._table')
        </div>
    </div>

    <!-- Modal de confirmation de suppression -->
    <div x-show="showDeleteModal" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showDeleteModal = false"></div>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                🗑️ Supprimer le produit
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Êtes-vous sûr de vouloir supprimer le produit 
                                    <span class="font-semibold text-gray-900" x-text="productToDelete.name"></span> ? 
                                    Cette action est <span class="text-red-600 font-medium">irréversible</span>.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button 
                        @click="confirmDeleteProduct()"
                        type="button" 
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                        🗑️ Supprimer définitivement
                    </button>
                    <button 
                        @click="showDeleteModal = false" 
                        type="button" 
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                        ❌ Annuler
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('productManager', () => ({
        // États
        loading: false,
        showAdvancedFilters: false,
        showDeleteModal: false,
        
        // Filtres
        searchTerm: '',
        selectedCategory: '',
        selectedStatus: '',
        selectedType: '',
        
        // Modal
        productToDelete: {},
        
        // Timeout pour debounce
        searchTimeout: null,

        init() {
            // Watcher pour la recherche avec debounce
            this.$watch('searchTerm', () => {
                this.debounceSearch();
            });
            
            // Watchers pour les autres filtres
            this.$watch('selectedCategory', () => {
                this.filterProducts();
            });
            
            this.$watch('selectedStatus', () => {
                this.filterProducts();
            });
            
            this.$watch('selectedType', () => {
                this.filterProducts();
            });
        },

        debounceSearch() {
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                this.filterProducts();
            }, 300);
        },

        async filterProducts() {
            this.loading = true;
            
            try {
                // Construire les paramètres de filtre
                const formData = new FormData();
                if (this.searchTerm) formData.append('search', this.searchTerm);
                if (this.selectedCategory) formData.append('category', this.selectedCategory);
                if (this.selectedStatus) formData.append('status', this.selectedStatus);
                if (this.selectedType) formData.append('type', this.selectedType);

                // Construire l'URL avec les paramètres
                const params = new URLSearchParams(formData);
                const url = '{{ route("admin.products.index") }}?' + params.toString();

                // Faire la requête AJAX
                const response = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                if (response.ok) {
                    const html = await response.text();
                    document.getElementById('productsTable').innerHTML = html;
                    
                    // Mettre à jour l'URL du navigateur sans recharger la page
                    window.history.pushState({}, '', url);
                    
                    this.showToast('Filtres appliqués avec succès', 'success');
                } else {
                    console.error('Erreur lors du filtrage:', response.statusText);
                    this.showToast('Erreur lors du filtrage des produits', 'error');
                }
            } catch (error) {
                console.error('Erreur lors du filtrage:', error);
                this.showToast('Erreur lors du filtrage des produits', 'error');
            } finally {
                this.loading = false;
            }
        },

        clearFilters() {
            this.searchTerm = '';
            this.selectedCategory = '';
            this.selectedStatus = '';
            this.selectedType = '';
            this.showAdvancedFilters = false;
            
            // Recharger avec les filtres vides
            this.filterProducts();
        },

        resetFilters() {
            this.clearFilters();
        },

        deleteProduct(id, name) {
            this.productToDelete = { id, name };
            this.showDeleteModal = true;
        },

        async confirmDeleteProduct() {
            try {
                const response = await fetch(`/admin/products/${this.productToDelete.id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    this.showDeleteModal = false;
                    this.showToast('Produit supprimé avec succès', 'success');
                    this.filterProducts(); // Recharger la liste
                } else {
                    throw new Error('Erreur lors de la suppression');
                }
            } catch (error) {
                console.error('Erreur:', error);
                this.showToast('Erreur lors de la suppression du produit', 'error');
            }
        },

        showToast(message, type = 'success') {
            if (window.showToast) {
                window.showToast(message, type);
            } else {
                console.log(`${type.toUpperCase()}: ${message}`);
            }
        },

        formatPrice(price) {
            return new Intl.NumberFormat('fr-FR', {
                style: 'currency',
                currency: 'EUR'
            }).format(price);
        },

        getStatusBadge(product) {
            if (!product.is_active) {
                return { class: 'bg-red-100 text-red-800', text: '❌ Inactif' };
            }
            if (product.quantity <= 0) {
                return { class: 'bg-red-100 text-red-800', text: '🚫 Rupture' };
            }
            if (product.quantity <= 10) {
                return { class: 'bg-yellow-100 text-yellow-800', text: '⚠️ Stock faible' };
            }
            if (product.is_featured) {
                return { class: 'bg-purple-100 text-purple-800', text: '⭐ En vedette' };
            }
            return { class: 'bg-green-100 text-green-800', text: '✅ Actif' };
        },

        getTypeBadge(product) {
            if (product.can_be_purchased && product.can_be_rented) {
                return { class: 'bg-blue-100 text-blue-800', text: '🔄 Achat & Location' };
            } else if (product.can_be_rented) {
                return { class: 'bg-orange-100 text-orange-800', text: '📅 Location' };
            } else {
                return { class: 'bg-green-100 text-green-800', text: '🛒 Achat' };
            }
        }
    }))
})
</script>
@endsection
