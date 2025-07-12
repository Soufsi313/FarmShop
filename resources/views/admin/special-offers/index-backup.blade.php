@extends('layouts.admin')

@section('title', 'Gestion des Offres Spéciales')

@push('styles')
<style>
    .offer-card {
        @apply bg-white rounded-lg shadow-sm border border-gray-200 hover:border-blue-300 transition-colors duration-200;
    }
    
    .offer-card.inactive {
        @apply opacity-70 bg-gray-50;
    }
    
    .offer-card.expired {
        @apply border-red-200 bg-red-50;
    }
    
    .discount-badge {
        @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium;
    }
    
    .discount-high {
        @apply bg-red-100 text-red-800;
    }
    
    .discount-medium {
        @apply bg-yellow-100 text-yellow-800;
    }
    
    .discount-low {
        @apply bg-green-100 text-green-800;
    }
    
    .status-badge {
        @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium;
    }
    
    .status-active {
        @apply bg-green-100 text-green-800;
    }
    
    .status-inactive {
        @apply bg-gray-100 text-gray-800;
    }
    
    .status-expired {
        @apply bg-red-100 text-red-800;
    }
    
    .status-upcoming {
        @apply bg-blue-100 text-blue-800;
    }
    
    .status-limit-reached {
        @apply bg-orange-100 text-orange-800;
    }
</style>
@endpush

@section('content')
<div class="space-y-6" x-data="specialOffersManager()">
    <!-- Header avec statistiques -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Gestion des Offres Spéciales</h1>
                <p class="text-gray-600">Gérez les promotions et réductions sur vos produits</p>
            </div>
            <button 
                @click="openCreateModal()"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                <span>Nouvelle Offre</span>
            </button>
        </div>
        
        <!-- Statistiques rapides -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4" x-show="stats">
            <div class="bg-blue-50 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <dt class="text-sm font-medium text-blue-600">Total Offres</dt>
                        <dd class="text-lg font-semibold text-blue-900" x-text="stats.overview?.total_offers || 0"></dd>
                    </div>
                </div>
            </div>
            
            <div class="bg-green-50 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <dt class="text-sm font-medium text-green-600">Actives</dt>
                        <dd class="text-lg font-semibold text-green-900" x-text="stats.overview?.active_offers || 0"></dd>
                    </div>
                </div>
            </div>
            
            <div class="bg-yellow-50 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <dt class="text-sm font-medium text-yellow-600">À venir</dt>
                        <dd class="text-lg font-semibold text-yellow-900" x-text="stats.overview?.upcoming_offers || 0"></dd>
                    </div>
                </div>
            </div>
            
            <div class="bg-red-50 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <dt class="text-sm font-medium text-red-600">Expirées</dt>
                        <dd class="text-lg font-semibold text-red-900" x-text="stats.overview?.expired_offers || 0"></dd>
                    </div>
                </div>
            </div>
            
            <div class="bg-purple-50 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <dt class="text-sm font-medium text-purple-600">Économies</dt>
                        <dd class="text-lg font-semibold text-purple-900" x-text="formatCurrency(stats.overview?.total_savings_generated || 0)"></dd>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Recherche -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Rechercher</label>
                <input 
                    type="text" 
                    x-model="filters.search"
                    @input.debounce.300ms="loadOffers()"
                    placeholder="Nom, description, produit..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
            </div>
            
            <!-- Filtre par statut -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                <select 
                    x-model="filters.status"
                    @change="loadOffers()"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                    <option value="">Tous les statuts</option>
                    <option value="active">Actives</option>
                    <option value="inactive">Inactives</option>
                    <option value="expired">Expirées</option>
                    <option value="upcoming">À venir</option>
                    <option value="limit_reached">Limite atteinte</option>
                </select>
            </div>
            
            <!-- Tri -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Trier par</label>
                <select 
                    x-model="sortBy"
                    @change="loadOffers()"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                    <option value="created_at">Date de création</option>
                    <option value="name">Nom</option>
                    <option value="discount_percentage">% Réduction</option>
                    <option value="minimum_quantity">Quantité min.</option>
                    <option value="start_date">Date début</option>
                    <option value="end_date">Date fin</option>
                    <option value="usage_count">Utilisations</option>
                </select>
            </div>
            
            <!-- Ordre -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ordre</label>
                <select 
                    x-model="sortOrder"
                    @change="loadOffers()"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                    <option value="desc">Décroissant</option>
                    <option value="asc">Croissant</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Liste des offres -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <!-- Loading -->
        <div x-show="loading" class="flex items-center justify-center py-12">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
        </div>
        
        <!-- Grille des offres -->
        <div x-show="!loading && offers.data && offers.data.length > 0" class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                <template x-for="offer in offers.data" :key="offer.id">
                    <div 
                        class="offer-card"
                        :class="{
                            'inactive': !offer.is_active,
                            'expired': new Date(offer.end_date) < new Date()
                        }"
                    >
                        <div class="p-6">
                            <!-- Header de l'offre -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-1" x-text="offer.name"></h3>
                                    <p class="text-sm text-gray-600" x-text="offer.product?.name || 'Produit supprimé'"></p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <!-- Badge de réduction -->
                                    <span 
                                        class="discount-badge"
                                        :class="{
                                            'discount-high': offer.discount_percentage >= 50,
                                            'discount-medium': offer.discount_percentage >= 20 && offer.discount_percentage < 50,
                                            'discount-low': offer.discount_percentage < 20
                                        }"
                                        x-text="`-${offer.discount_percentage}%`"
                                    ></span>
                                    
                                    <!-- Badge de statut -->
                                    <span 
                                        class="status-badge"
                                        :class="getStatusBadgeClass(offer)"
                                        x-text="getStatusText(offer)"
                                    ></span>
                                </div>
                            </div>
                            
                            <!-- Description -->
                            <p 
                                x-show="offer.description" 
                                class="text-sm text-gray-600 mb-4" 
                                x-text="offer.description"
                            ></p>
                            
                            <!-- Détails de l'offre -->
                            <div class="space-y-2 mb-4">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Quantité minimum:</span>
                                    <span class="font-medium" x-text="offer.minimum_quantity"></span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Période:</span>
                                    <span class="font-medium" x-text="formatDateRange(offer.start_date, offer.end_date)"></span>
                                </div>
                                <div class="flex justify-between text-sm" x-show="offer.usage_limit">
                                    <span class="text-gray-500">Utilisation:</span>
                                    <span class="font-medium" x-text="`${offer.usage_count}/${offer.usage_limit}`"></span>
                                </div>
                                <div class="flex justify-between text-sm" x-show="!offer.usage_limit">
                                    <span class="text-gray-500">Utilisations:</span>
                                    <span class="font-medium" x-text="offer.usage_count"></span>
                                </div>
                            </div>
                            
                            <!-- Actions -->
                            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                <div class="flex items-center space-x-2">
                                    <!-- Toggle actif/inactif -->
                                    <button 
                                        @click="toggleOfferStatus(offer)"
                                        :class="offer.is_active ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-gray-100 text-gray-800 hover:bg-gray-200'"
                                        class="px-3 py-1 rounded-full text-xs font-medium transition-colors"
                                        :title="offer.is_active ? 'Désactiver' : 'Activer'"
                                    >
                                        <span x-text="offer.is_active ? 'Actif' : 'Inactif'"></span>
                                    </button>
                                </div>
                                
                                <div class="flex items-center space-x-2">
                                    <!-- Modifier -->
                                    <button 
                                        @click="editOffer(offer)"
                                        class="text-blue-600 hover:text-blue-800 transition-colors"
                                        title="Modifier"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    
                                    <!-- Supprimer -->
                                    <button 
                                        @click="deleteOffer(offer)"
                                        class="text-red-600 hover:text-red-800 transition-colors"
                                        title="Supprimer"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            
            <!-- Pagination -->
            <div x-show="offers.last_page > 1" class="mt-6 flex items-center justify-between border-t border-gray-200 pt-6">
                <div class="text-sm text-gray-700">
                    Affichage de <span x-text="offers.from || 0"></span> à <span x-text="offers.to || 0"></span> 
                    sur <span x-text="offers.total || 0"></span> offres
                </div>
                <div class="flex items-center space-x-2">
                    <button 
                        @click="changePage(offers.current_page - 1)"
                        :disabled="offers.current_page <= 1"
                        :class="offers.current_page <= 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50'"
                        class="px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white"
                    >
                        Précédent
                    </button>
                    
                    <template x-for="page in getPageRange()" :key="page">
                        <button 
                            @click="changePage(page)"
                            :class="page === offers.current_page ? 'bg-blue-600 text-white' : 'text-gray-700 bg-white hover:bg-gray-50'"
                            class="px-3 py-2 border border-gray-300 rounded-md text-sm font-medium"
                            x-text="page"
                        ></button>
                    </template>
                    
                    <button 
                        @click="changePage(offers.current_page + 1)"
                        :disabled="offers.current_page >= offers.last_page"
                        :class="offers.current_page >= offers.last_page ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50'"
                        class="px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white"
                    >
                        Suivant
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Aucune offre -->
        <div x-show="!loading && (!offers.data || offers.data.length === 0)" class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune offre spéciale</h3>
            <p class="mt-1 text-sm text-gray-500">Commencez par créer votre première offre spéciale.</p>
            <div class="mt-6">
                <button 
                    @click="openCreateModal()"
                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700"
                >
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Nouvelle Offre
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Créer/Modifier Offre -->
    <div 
        x-show="showModal"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;"
    >
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            
            <div 
                x-show="showModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
            >
                <form @submit.prevent="submitForm()">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" x-text="editingOffer ? 'Modifier l\'offre spéciale' : 'Créer une offre spéciale'"></h3>
                        </div>
                        
                        <div class="space-y-4">
                            <!-- Nom -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nom de l'offre *</label>
                                <input 
                                    type="text" 
                                    x-model="form.name"
                                    required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Ex: Promotion Pommes d'automne"
                                >
                            </div>
                            
                            <!-- Description -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea 
                                    x-model="form.description"
                                    rows="3"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Description de l'offre..."
                                ></textarea>
                            </div>
                            
                            <!-- Produit -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Produit *</label>
                                <select 
                                    x-model="form.product_id"
                                    required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                >
                                    <option value="">Sélectionner un produit</option>
                                    <template x-for="product in products" :key="product.id">
                                        <option :value="product.id" x-text="`${product.name} - ${formatCurrency(product.price)}`"></option>
                                    </template>
                                </select>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <!-- Quantité minimum -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Quantité minimum *</label>
                                    <input 
                                        type="number" 
                                        x-model.number="form.minimum_quantity"
                                        min="1"
                                        required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                    >
                                </div>
                                
                                <!-- Pourcentage de réduction -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">% Réduction *</label>
                                    <input 
                                        type="number" 
                                        x-model.number="form.discount_percentage"
                                        min="0.01"
                                        max="100"
                                        step="0.01"
                                        required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                    >
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <!-- Date de début -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Date de début *</label>
                                    <input 
                                        type="datetime-local" 
                                        x-model="form.start_date"
                                        required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                    >
                                </div>
                                
                                <!-- Date de fin -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Date de fin *</label>
                                    <input 
                                        type="datetime-local" 
                                        x-model="form.end_date"
                                        required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                    >
                                </div>
                            </div>
                            
                            <!-- Limite d'usage -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Limite d'utilisation (optionnel)</label>
                                <input 
                                    type="number" 
                                    x-model.number="form.usage_limit"
                                    min="1"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Laissez vide pour illimité"
                                >
                            </div>
                            
                            <!-- Actif -->
                            <div class="flex items-center">
                                <input 
                                    type="checkbox" 
                                    x-model="form.is_active"
                                    id="is_active"
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                >
                                <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                    Offre active
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button 
                            type="submit"
                            :disabled="submitting"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50"
                        >
                            <span x-show="!submitting" x-text="editingOffer ? 'Modifier' : 'Créer'"></span>
                            <span x-show="submitting">Traitement...</span>
                        </button>
                        <button 
                            type="button"
                            @click="closeModal()"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Annuler
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function specialOffersManager() {
    return {
        offers: { data: [] },
        stats: null,
        products: [],
        loading: false,
        submitting: false,
        showModal: false,
        editingOffer: null,
        
        // Filtres
        filters: {
            search: '',
            status: '',
            product_id: ''
        },
        
        // Tri
        sortBy: 'created_at',
        sortOrder: 'desc',
        
        // Formulaire
        form: {
            name: '',
            description: '',
            product_id: '',
            minimum_quantity: 1,
            discount_percentage: 10,
            start_date: '',
            end_date: '',
            is_active: true,
            usage_limit: ''
        },
        
        async init() {
            await this.loadStats();
            await this.loadProducts();
            await this.loadOffers();
        },
        
        async loadStats() {
            try {
                const response = await fetch('/api/admin/special-offers/stats', {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                        'Content-Type': 'application/json'
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    this.stats = data.data;
                }
            } catch (error) {
                console.error('Erreur lors du chargement des statistiques:', error);
            }
        },
        
        async loadProducts() {
            try {
                const response = await fetch('/api/products?per_page=1000', {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                        'Content-Type': 'application/json'
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    this.products = data.data.data || data.data || [];
                }
            } catch (error) {
                console.error('Erreur lors du chargement des produits:', error);
            }
        },
        
        async loadOffers(page = 1) {
            this.loading = true;
            
            try {
                const params = new URLSearchParams({
                    page: page,
                    per_page: 20,
                    sort: this.sortBy,
                    order: this.sortOrder,
                    ...this.filters
                });
                
                const response = await fetch(`/api/special-offers?${params}`, {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                        'Content-Type': 'application/json'
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    this.offers = data.data;
                } else {
                    throw new Error('Erreur lors du chargement');
                }
            } catch (error) {
                console.error('Erreur:', error);
                this.showNotification('Erreur lors du chargement des offres', 'error');
            } finally {
                this.loading = false;
            }
        },
        
        openCreateModal() {
            this.editingOffer = null;
            this.resetForm();
            this.showModal = true;
        },
        
        editOffer(offer) {
            this.editingOffer = offer;
            this.form = {
                name: offer.name,
                description: offer.description || '',
                product_id: offer.product_id,
                minimum_quantity: offer.minimum_quantity,
                discount_percentage: offer.discount_percentage,
                start_date: offer.start_date.slice(0, 16), // Format pour datetime-local
                end_date: offer.end_date.slice(0, 16),
                is_active: offer.is_active,
                usage_limit: offer.usage_limit || ''
            };
            this.showModal = true;
        },
        
        async submitForm() {
            this.submitting = true;
            
            try {
                const url = this.editingOffer 
                    ? `/api/admin/special-offers/${this.editingOffer.id}`
                    : '/api/admin/special-offers';
                
                const method = this.editingOffer ? 'PUT' : 'POST';
                
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(this.form)
                });
                
                const data = await response.json();
                
                if (response.ok) {
                    this.showNotification(data.message, 'success');
                    this.closeModal();
                    await this.loadOffers();
                    await this.loadStats();
                } else {
                    throw new Error(data.message || 'Erreur lors de la sauvegarde');
                }
            } catch (error) {
                console.error('Erreur:', error);
                this.showNotification(error.message, 'error');
            } finally {
                this.submitting = false;
            }
        },
        
        async toggleOfferStatus(offer) {
            try {
                const response = await fetch(`/api/admin/special-offers/${offer.id}/toggle-status`, {
                    method: 'PATCH',
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                        'Content-Type': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if (response.ok) {
                    this.showNotification(data.message, 'success');
                    offer.is_active = !offer.is_active;
                    await this.loadStats();
                } else {
                    throw new Error(data.message || 'Erreur lors du changement de statut');
                }
            } catch (error) {
                console.error('Erreur:', error);
                this.showNotification(error.message, 'error');
            }
        },
        
        async deleteOffer(offer) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cette offre spéciale ?')) {
                return;
            }
            
            try {
                const response = await fetch(`/api/admin/special-offers/${offer.id}`, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                        'Content-Type': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if (response.ok) {
                    this.showNotification(data.message, 'success');
                    await this.loadOffers();
                    await this.loadStats();
                } else {
                    throw new Error(data.message || 'Erreur lors de la suppression');
                }
            } catch (error) {
                console.error('Erreur:', error);
                this.showNotification(error.message, 'error');
            }
        },
        
        closeModal() {
            this.showModal = false;
            this.editingOffer = null;
            this.resetForm();
        },
        
        resetForm() {
            this.form = {
                name: '',
                description: '',
                product_id: '',
                minimum_quantity: 1,
                discount_percentage: 10,
                start_date: '',
                end_date: '',
                is_active: true,
                usage_limit: ''
            };
        },
        
        changePage(page) {
            if (page >= 1 && page <= this.offers.last_page) {
                this.loadOffers(page);
            }
        },
        
        getPageRange() {
            const current = this.offers.current_page;
            const last = this.offers.last_page;
            const range = [];
            
            let start = Math.max(1, current - 2);
            let end = Math.min(last, current + 2);
            
            for (let i = start; i <= end; i++) {
                range.push(i);
            }
            
            return range;
        },
        
        getStatusBadgeClass(offer) {
            const now = new Date();
            const startDate = new Date(offer.start_date);
            const endDate = new Date(offer.end_date);
            
            if (!offer.is_active) return 'status-inactive';
            if (endDate < now) return 'status-expired';
            if (startDate > now) return 'status-upcoming';
            if (offer.usage_limit && offer.usage_count >= offer.usage_limit) return 'status-limit-reached';
            return 'status-active';
        },
        
        getStatusText(offer) {
            const now = new Date();
            const startDate = new Date(offer.start_date);
            const endDate = new Date(offer.end_date);
            
            if (!offer.is_active) return 'Inactive';
            if (endDate < now) return 'Expirée';
            if (startDate > now) return 'À venir';
            if (offer.usage_limit && offer.usage_count >= offer.usage_limit) return 'Limite atteinte';
            return 'Active';
        },
        
        formatCurrency(amount) {
            return new Intl.NumberFormat('fr-FR', {
                style: 'currency',
                currency: 'EUR'
            }).format(amount);
        },
        
        formatDateRange(startDate, endDate) {
            const start = new Date(startDate).toLocaleDateString('fr-FR');
            const end = new Date(endDate).toLocaleDateString('fr-FR');
            return `${start} - ${end}`;
        },
        
        showNotification(message, type = 'info') {
            // Implémentation simple - vous pouvez utiliser une library plus sophistiquée
            if (type === 'success') {
                alert('✅ ' + message);
            } else if (type === 'error') {
                alert('❌ ' + message);
            } else {
                alert('ℹ️ ' + message);
            }
        }
    }
}
</script>
@endpush
@endsection
