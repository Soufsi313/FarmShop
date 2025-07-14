@extends('layouts.admin')

@section('title', 'Gestion des Produits - FarmShop Admin')
@section('page-title', 'Gestion des Produits')

@section('content')
<div x-data="productManager">
    <!-- Header avec bouton d'ajout -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Produits</h2>
            <p class="text-gray-600">Gérez votre catalogue de produits</p>
        </div>
        <a href="{{ route('admin.products.create') }}" 
           class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            <span>Ajouter un produit</span>
        </a>
    </div>

    <!-- Filtres et recherche -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <form id="filterForm">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Recherche</label>
                    <input type="text" 
                           name="search"
                           x-model="searchTerm"
                           placeholder="Nom, SKU, description..."
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catégorie</label>
                    <select name="category" x-model="selectedCategory" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">Toutes les catégories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                    <select name="status" x-model="selectedStatus" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">Tous les statuts</option>
                        <option value="active">✅ Actif</option>
                        <option value="inactive">❌ Inactif</option>
                        <option value="featured">⭐ En vedette</option>
                        <option value="low_stock">⚠️ Stock faible</option>
                        <option value="out_of_stock">🚫 Rupture de stock</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                    <select name="type" x-model="selectedType" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">Tous les types</option>
                        <option value="purchase">🛒 Achat</option>
                        <option value="rental">📅 Location</option>
                        <option value="both">🔄 Achat et Location</option>
                    </select>
                </div>
            </div>
            <div class="mt-4 flex items-center space-x-3">
                <button type="button" @click="clearFilters()" class="text-gray-600 hover:text-gray-800 text-sm">
                    🗑️ Effacer les filtres
                </button>
                <div x-show="loading" class="flex items-center text-green-600 text-sm">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Filtrage en cours...
                </div>
            </div>
        </form>
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
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Supprimer le produit</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Êtes-vous sûr de vouloir supprimer le produit "<span x-text="productToDelete.name"></span>" ? Cette action est irréversible.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <form :action="'/admin/products/' + productToDelete.id" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Supprimer
                        </button>
                    </form>
                    <button @click="showDeleteModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Annuler
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('productManager', () => ({
        searchTerm: '',
        selectedCategory: '',
        selectedStatus: '',
        selectedType: '',
        showDeleteModal: false,
        productToDelete: {},
        loading: false,
        searchTimeout: null,

        init() {
            // Écouter les changements sur tous les inputs et selects
            this.$watch('searchTerm', () => this.debounceFilter());
            this.$watch('selectedCategory', () => this.filterProducts());
            this.$watch('selectedStatus', () => this.filterProducts());
            this.$watch('selectedType', () => this.filterProducts());
        },

        debounceFilter() {
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                this.filterProducts();
            }, 300); // Attendre 300ms après l'arrêt de frappe
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
                } else {
                    console.error('Erreur lors du filtrage:', response.statusText);
                }
            } catch (error) {
                console.error('Erreur lors du filtrage:', error);
            } finally {
                this.loading = false;
            }
        },

        clearFilters() {
            this.searchTerm = '';
            this.selectedCategory = '';
            this.selectedStatus = '';
            this.selectedType = '';
            this.filterProducts();
        },

        deleteProduct(id, name) {
            this.productToDelete = { id, name };
            this.showDeleteModal = true;
        }
    }))
})
</script>
@endsection
