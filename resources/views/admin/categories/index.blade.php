@extends('layouts.admin')

@section('title', 'Gestion des Catégories - FarmShop Admin')
@section('page-title', 'Gestion des Catégories')

@section('content')
<div class="container mx-auto px-4 py-8" x-data="categoryManager">
    <!-- Entête avec style moderne -->
    <div class="mb-8">
        <div class="bg-gradient-to-r from-purple-600 to-blue-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold flex items-center">
                        <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        Gestion des Catégories
                    </h1>
                    <p class="mt-2 text-purple-100">
                        Organisez votre catalogue de produits avec des catégories structurées
                    </p>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold">{{ $stats['total'] ?? count($categories) }}</div>
                    <div class="text-purple-100">Catégories totales</div>
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-green-600">{{ $stats['total'] ?? count($categories) }}</div>
                    <div class="text-sm text-green-700">Total catégories</div>
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
                    <div class="text-2xl font-bold text-blue-600">{{ $stats['active'] ?? $categories->where('is_active', true)->count() }}</div>
                    <div class="text-sm text-blue-700">Actives</div>
                </div>
            </div>
        </div>

        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-orange-600">{{ $stats['with_products'] ?? 0 }}</div>
                    <div class="text-sm text-orange-700">Avec produits</div>
                </div>
            </div>
        </div>

        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-purple-600">{{ $stats['inactive'] ?? $categories->where('is_active', false)->count() }}</div>
                    <div class="text-sm text-purple-700">Inactives</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bouton d'ajout et actions -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-8">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    Navigation des catégories
                </h2>
                <a href="{{ route('admin.categories.create') }}" 
                   class="px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Nouvelle Catégorie
                </a>
            </div>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($categories ?? [] as $category)
                <div class="bg-gradient-to-br from-white to-gray-50 rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden cursor-pointer border border-gray-100 hover:border-purple-200"
                     onclick="window.location.href='{{ route('admin.categories.show', $category) }}'">
                    <!-- Image de la catégorie -->
                    <div class="h-32 bg-gradient-to-br from-purple-100 to-blue-200 flex items-center justify-center relative">
                        @if($category->image)
                            <img src="{{ asset('storage/' . $category->image) }}" 
                                 alt="{{ $category->name }}" 
                                 class="w-full h-full object-cover hover:scale-105 transition-transform">
                        @elseif($category->icon)
                            <div class="text-4xl">{{ $category->icon }}</div>
                        @else
                            <svg class="w-12 h-12 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                        @endif
                        
                        <!-- Badge statut -->
                        <div class="absolute top-2 right-2">
                            @if($category->is_active)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                    ✅ Actif
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                    ❌ Inactif
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Contenu de la carte -->
                    <div class="p-4">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-lg font-semibold text-gray-900 truncate hover:text-purple-600 transition-colors">{{ $category->name }}</h3>
                            <span class="text-sm text-purple-500 ml-2 font-medium">#{{ $category->display_order ?? 0 }}</span>
                        </div>

                        @if($category->description)
                            <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ Str::limit($category->description, 80) }}</p>
                        @endif                        <!-- Statistiques -->
                        <div class="flex items-center justify-between text-sm mb-4">
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-medium">
                                📦 {{ $category->products_count ?? 0 }} produit(s)
                            </span>
                            <span class="text-gray-500">{{ $category->created_at->format('d/m/Y') }}</span>
                        </div>

                        <!-- Actions modernes -->
                        <div class="flex items-center justify-between pt-2 border-t border-gray-100" onclick="event.stopPropagation()">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.categories.show', $category) }}" 
                                   class="text-blue-600 hover:text-blue-800 transition-colors p-1 rounded hover:bg-blue-50"
                                   title="Voir les détails">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('admin.categories.edit', $category) }}" 
                                   class="text-yellow-600 hover:text-yellow-800 transition-colors p-1 rounded hover:bg-yellow-50"
                                   title="Modifier">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-800 transition-colors p-1 rounded hover:bg-red-50"
                                            title="Supprimer"
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?')">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                            
                            @if($category->food_type)
                                <span class="text-xs px-2 py-1 rounded-full {{ $category->food_type === 'alimentaire' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                    {{ $category->food_type === 'alimentaire' ? '🥬 Alimentaire' : '🔧 Non-alimentaire' }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune catégorie</h3>
                    <p class="mt-1 text-sm text-gray-500">Commencez par créer votre première catégorie.</p>
                    <div class="mt-6">
                        <a href="{{ route('admin.categories.create') }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Ajouter une catégorie
                        </a>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Pagination moderne -->
    @if(isset($categories) && $categories->hasPages())
    <div class="mt-8 bg-white rounded-xl shadow-lg border border-gray-200 p-6">
        {{ $categories->links() }}
    </div>
    @endif
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('categoryManager', () => ({
        searchTerm: '',
        selectedStatus: '',
        sortBy: 'name',
        showDeleteModal: false,
        categoryToDelete: {},

        filterCategories() {
            // Cette fonction pourrait être utilisée pour filtrer côté client
            // Pour l'instant, nous utilisons la pagination Laravel côté serveur
        },

        deleteCategory(id, name, products_count) {
            this.categoryToDelete = { id, name, products_count };
            this.showDeleteModal = true;
        }
    }))
})
</script>
@endsection
