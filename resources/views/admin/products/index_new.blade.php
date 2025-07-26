@extends('layouts.admin')

@section('title', 'Gestion des Produits - FarmShop Admin')
@section('page-title', 'Gestion des Produits')

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
                        Catalogue de Produits
                    </h1>
                    <p class="mt-2 text-green-100">
                        Interface avancée de gestion de l'inventaire avec recherche et filtres
                    </p>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold">{{ $stats['total'] ?? 0 }}</div>
                    <div class="text-green-100">Produits totaux</div>
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
                    <div class="text-sm text-green-700">Total produits</div>
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
                    <div class="text-sm text-blue-700">Produits actifs</div>
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
                    <div class="text-sm text-yellow-700">Stock faible</div>
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
                    <div class="text-sm text-red-700">Rupture stock</div>
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
                Nouveau Produit
            </a>
            
            <button @click="exportProducts()" 
                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 border border-transparent rounded-lg text-sm font-medium text-white hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-lg">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Exporter
            </button>
            
            <button @click="importProducts()" 
                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-purple-700 border border-transparent rounded-lg text-sm font-medium text-white hover:from-purple-700 hover:to-purple-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-200 shadow-lg">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                </svg>
                Importer
            </button>
        </div>

        <div class="flex items-center space-x-2 text-sm text-gray-600">
            <span>Affichage :</span>
            <select x-model="itemsPerPage" @change="filterProducts()" 
                    class="border border-gray-300 rounded-lg px-3 py-1 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
                <option value="15">15 par page</option>
                <option value="30">30 par page</option>
                <option value="50">50 par page</option>
                <option value="100">100 par page</option>
            </select>
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="bg-white shadow-lg rounded-xl p-6 mb-6">
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Recherche et Filtres</h3>
            <p class="text-sm text-gray-600">Trouvez rapidement les produits de votre catalogue</p>
        </div>
        
        <form @submit.prevent="filterProducts()" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">Recherche générale</label>
                    <div class="relative">
                        <input type="text" 
                               x-model="searchTerm"
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                               placeholder="Nom, SKU, description...">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">Catégorie</label>
                    <select x-model="selectedCategory" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white">
                        <option value="">Toutes les catégories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">Statut</label>
                    <select x-model="selectedStatus" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white">
                        <option value="">Tous les statuts</option>
                        <option value="active">Actifs uniquement</option>
                        <option value="inactive">Inactifs uniquement</option>
                        <option value="featured">En vedette</option>
                        <option value="low_stock">Stock faible</option>
                        <option value="out_of_stock">Rupture de stock</option>
                    </select>
                </div>

                <div class="flex items-end space-x-2">
                    <button type="submit" 
                            class="flex-1 bg-gradient-to-r from-green-600 to-green-700 text-white px-4 py-2 rounded-lg hover:from-green-700 hover:to-green-800 transition-all duration-200">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Filtrer
                    </button>
                    <button type="button" 
                            @click="clearFilters()" 
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
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
</div>

<!-- Scripts Alpine.js pour la gestion des produits -->
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('productManager', () => ({
        searchTerm: '',
        selectedCategory: '',
        selectedStatus: '',
        itemsPerPage: 15,
        loading: false,
        selectedProducts: [],
        showDeleteModal: false,
        productToDelete: null,
        showAdvancedFilters: false,
        selectedType: '',
        maxPrice: '',
        minStock: '',

        init() {
            // Initialisation du composant
            console.log('Product Manager initialized');
        },

        filterProducts() {
            this.loading = true;
            
            const formData = new FormData();
            formData.append('search', this.searchTerm);
            formData.append('category', this.selectedCategory);
            formData.append('status', this.selectedStatus);
            formData.append('per_page', this.itemsPerPage);
            
            fetch('{{ route("admin.products.index") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                document.getElementById('productsTable').innerHTML = html;
                this.loading = false;
            })
            .catch(error => {
                console.error('Erreur lors du filtrage:', error);
                this.loading = false;
            });
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

            if (confirm(`Êtes-vous sûr de vouloir ${action === 'activate' ? 'activer' : 'désactiver'} les produits sélectionnés ?`)) {
                const formData = new FormData();
                formData.append('action', action);
                formData.append('product_ids', JSON.stringify(this.selectedProducts));
                
                fetch('{{ route("admin.products.bulk-action") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.filterProducts();
                        this.selectedProducts = [];
                    }
                });
            }
        },

        confirmDelete(product) {
            this.productToDelete = product;
            this.showDeleteModal = true;
        },

        deleteProduct() {
            if (!this.productToDelete) return;

            fetch(`{{ route("admin.products.destroy", ":id") }}`.replace(':id', this.productToDelete.id), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.showDeleteModal = false;
                    this.productToDelete = null;
                    this.filterProducts();
                }
            });
        },

        exportProducts() {
            window.location.href = '{{ route("admin.products.export") }}';
        },

        importProducts() {
            // Ouvrir modal d'import ou rediriger vers page d'import
            window.location.href = '{{ route("admin.products.import") }}';
        }
    }));
});
</script>
@endsection
