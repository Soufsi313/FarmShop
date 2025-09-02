@extends('layouts.admin')

@section('title', __('products.admin.title'))
@section('page-title', __('products.admin.page_title'))

@section('content')
<div class="container mx-auto px-4 py-8" x-data="productManager">
    <!-- Entête avec style moderne -->
    <div class="mb-8">
        <div class="bg-gradient-to-r from-green-600 to-blue-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold flex items-center">
                        <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        {{ __('products.admin.catalog_title') }}
                    </h1>
                    <p class="mt-2 text-green-100">
                        {{ __('products.admin.advanced_interface') }}
                    </p>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold">{{ $stats['total'] ?? 0 }}</div>
                    <div class="text-green-100">{{ __('products.admin.total_products') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-green-600">{{ $stats['total'] ?? 0 }}</div>
                    <div class="text-sm text-green-700">{{ __('products.admin.stats.total_products') }}</div>
                </div>
            </div>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-blue-600">{{ $stats['active'] ?? 0 }}</div>
                    <div class="text-sm text-blue-700">{{ __('products.admin.stats.active_products') }}</div>
                </div>
            </div>
        </div>

        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-yellow-600">{{ $stats['low_stock'] ?? 0 }}</div>
                    <div class="text-sm text-yellow-700">{{ __('products.admin.stats.low_stock') }}</div>
                </div>
            </div>
        </div>

        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-red-600">{{ $stats['out_of_stock'] ?? 0 }}</div>
                    <div class="text-sm text-red-700">{{ __('products.admin.stats.out_of_stock') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div class="flex space-x-3 mb-4 sm:mb-0">
            <a href="{{ route('admin.products.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-green-700 border border-transparent rounded-lg text-sm font-medium text-white hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-lg">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                {{ __('products.admin.actions.new_product') }}
            </a>
            
            <button @click="exportProducts()" 
                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 border border-transparent rounded-lg text-sm font-medium text-white hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-lg">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                {{ __('products.admin.actions.export') }}
            </button>
            
            <button @click="importProducts()" 
                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-purple-700 border border-transparent rounded-lg text-sm font-medium text-white hover:from-purple-700 hover:to-purple-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-200 shadow-lg">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                </svg>
                {{ __('products.admin.actions.import') }}
            </button>
        </div>

        <div class="flex items-center space-x-2 text-sm text-gray-600">
            <span>{{ __('products.admin.display.showing') }}</span>
            <select x-model="itemsPerPage" @change="filterProducts()" 
                    class="border border-gray-300 rounded-lg px-3 py-1 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
                <option value="15">{{ __('products.admin.display.15_per_page') }}</option>
                <option value="30">{{ __('products.admin.display.30_per_page') }}</option>
                <option value="50">{{ __('products.admin.display.50_per_page') }}</option>
                <option value="100">{{ __('products.admin.display.100_per_page') }}</option>
            </select>
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="bg-white shadow-lg rounded-xl p-6 mb-6">
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ __('products.admin.search.title') }}</h3>
            <p class="text-sm text-gray-600">{{ __('products.admin.search.description') }}</p>
        </div>
        
        <form @submit.prevent="filterProducts()" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">{{ __('products.admin.search.general_search') }}</label>
                    <div class="relative">
                        <input type="text" 
                               x-model="searchTerm"
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                               placeholder="{{ __('products.admin.search.placeholder') }}">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">{{ __('products.admin.search.category') }}</label>
                    <select x-model="selectedCategory" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white">
                        <option value="">{{ __('products.admin.search.all_categories') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">{{ __('products.admin.search.product_type') }}</label>
                    <select x-model="selectedType" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white">
                        <option value="">{{ __('products.admin.search.all_types') }}</option>
                        <option value="sale">{{ __('products.admin.types.sale_only') }}</option>
                        <option value="rental">{{ __('products.admin.types.rental_only') }}</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">{{ __('products.admin.search.status') }}</label>
                    <select x-model="selectedStatus" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white">
                        <option value="">{{ __('products.admin.search.all_statuses') }}</option>
                        <option value="active">{{ __('products.admin.statuses.active_only') }}</option>
                        <option value="inactive">{{ __('products.admin.statuses.inactive_only') }}</option>
                        <option value="featured">{{ __('products.admin.statuses.featured') }}</option>
                        <option value="low_stock">{{ __('products.admin.statuses.low_stock') }}</option>
                        <option value="out_of_stock">{{ __('products.admin.statuses.out_of_stock') }}</option>
                    </select>
                </div>

                <div class="flex items-end space-x-2">
                    <button type="submit" 
                            class="flex-1 bg-gradient-to-r from-green-600 to-green-700 text-white px-4 py-2 rounded-lg hover:from-green-700 hover:to-green-800 transition-all duration-200">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        {{ __('products.admin.actions.filter') }}
                    </button>
                    <button type="button" 
                            @click="clearFilters()" 
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <span class="ml-1">{{ __('products.admin.actions.clear_filters') }}</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Liste des produits -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200">
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
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true"
         style="display: none;">
        
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Overlay -->
            <div x-show="showDeleteModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                 @click="showDeleteModal = false"></div>

            <!-- Modal -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div x-show="showDeleteModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Confirmer la suppression
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Êtes-vous sûr de vouloir supprimer le produit "<span x-text="productToDelete?.name" class="font-medium text-gray-900"></span>" ?
                                    Cette action est irréversible.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" 
                            @click="deleteProduct()" 
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                        Supprimer
                    </button>
                    <button type="button" 
                            @click="showDeleteModal = false" 
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                        Annuler
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts Alpine.js pour la gestion des produits -->
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('productManager', () => ({
        searchTerm: '{{ $filters['search'] ?? '' }}',
        selectedCategory: '{{ $filters['category'] ?? '' }}',
        selectedType: '{{ $filters['type'] ?? '' }}',
        selectedStatus: '{{ $filters['status'] ?? '' }}',
        itemsPerPage: 15,
        loading: false,
        selectedProducts: [],
        showDeleteModal: false,
        productToDelete: null,
        showAdvancedFilters: false,
        maxPrice: '',
        minStock: '',

        init() {
            // Initialisation du composant
            console.log('Product Manager initialized');
        },

        filterProducts() {
            this.loading = true;
            
            // Construire l'URL avec les paramètres de recherche
            const params = new URLSearchParams();
            if (this.searchTerm) params.append('search', this.searchTerm);
            if (this.selectedCategory) params.append('category', this.selectedCategory);
            if (this.selectedType) params.append('type', this.selectedType);
            if (this.selectedStatus) params.append('status', this.selectedStatus);
            if (this.itemsPerPage !== 15) params.append('per_page', this.itemsPerPage);
            
            const url = '{{ route("admin.products.index") }}' + (params.toString() ? '?' + params.toString() : '');
            window.location.href = url;
        },

        clearFilters() {
            this.searchTerm = '';
            this.selectedCategory = '';
            this.selectedStatus = '';
            this.selectedType = '';
            this.maxPrice = '';
            this.minStock = '';
            this.filterProducts();
        },

        toggleProduct(productId) {
            const index = this.selectedProducts.indexOf(productId);
            if (index > -1) {
                this.selectedProducts.splice(index, 1);
            } else {
                this.selectedProducts.push(productId);
            }
        },

        toggleSelectAll() {
            const checkboxes = document.querySelectorAll('input[name="product_ids[]"]');
            if (this.selectedProducts.length === checkboxes.length) {
                this.selectedProducts = [];
                checkboxes.forEach(cb => cb.checked = false);
            } else {
                this.selectedProducts = Array.from(checkboxes).map(cb => parseInt(cb.value));
                checkboxes.forEach(cb => cb.checked = true);
            }
        },

        bulkAction(action) {
            if (this.selectedProducts.length === 0) {
                alert('Veuillez sélectionner au moins un produit');
                return;
            }

            alert(`Fonctionnalité en cours de développement. Action demandée: ${action === 'activate' ? 'Activer' : 'Désactiver'} ${this.selectedProducts.length} produit(s).`);
        },

        confirmDelete(productId, productName) {
            this.productToDelete = {
                id: productId,
                name: productName
            };
            this.showDeleteModal = true;
        },

        deleteProduct() {
            if (!this.productToDelete) return;

            // Créer un FormData avec la méthode spoofing DELETE
            const formData = new FormData();
            formData.append('_method', 'DELETE');
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            fetch(`{{ url('admin/products') }}/${this.productToDelete.id}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.showDeleteModal = false;
                    this.productToDelete = null;
                    this.filterProducts();
                    // Optionnel : afficher un message de succès
                    alert('Produit supprimé avec succès!');
                } else {
                    alert('Erreur lors de la suppression: ' + (data.message || 'Erreur inconnue'));
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Erreur lors de la suppression du produit');
                this.showDeleteModal = false;
                this.productToDelete = null;
            });
        },

        exportProducts() {
            alert('Fonctionnalité d\'export en cours de développement');
        },

        importProducts() {
            alert('Fonctionnalité d\'import en cours de développement');
        }
    }));
});
</script>
@endsection
