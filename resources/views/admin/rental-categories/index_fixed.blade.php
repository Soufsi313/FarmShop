@extends('layouts.admin')

@section('title', __('rental_categories.title'))
@section('page-title', __('rental_categories.page_title'))

@section('content')
<div x-data="rentalCategoryManager">
    <!-- Header avec bouton d'ajout -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ __('rental_categories.header.title') }}</h2>
            <p class="text-gray-600">{{ __('rental_categories.header.description') }}</p>
        </div>
        <a href="{{ route('admin.rental-categories.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            <span>{{ __('rental_categories.header.add_category') }}</span>
        </a>
    </div>

    <!-- Messages de succès/erreur -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
            {{ session('error') }}
        </div>
    @endif

    <!-- Grille des catégories -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($rentalCategories ?? [] as $category)
        <div class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow overflow-hidden">
            <!-- Image de la catégorie -->
            <div class="h-32 bg-gradient-to-br from-indigo-100 to-indigo-200 flex items-center justify-center relative">
                @if($category->image)
                    <img src="{{ asset('storage/' . $category->image) }}"
                         alt="{{ $category->name }}"
                         class="w-full h-full object-cover">
                @elseif($category->icon)
                    <div class="text-4xl">{{ $category->icon }}</div>
                @else
                    <svg class="w-12 h-12 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                @endif
                
                <!-- Badge statut -->
                <div class="absolute top-2 right-2">
                    @if($category->is_active)
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            {{ __('rental_categories.status.active') }}
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            {{ __('rental_categories.status.inactive') }}
                        </span>
                    @endif
                </div>
            </div>

            <!-- Contenu de la carte -->
            <div class="p-4">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $category->name }}</h3>
                    <span class="text-sm text-gray-500 ml-2">{{ $category->display_order ?? 0 }}</span>
                </div>
                
                @if($category->description)
                    <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ Str::limit($category->description, 80) }}</p>
                @endif
                
                <!-- Statistiques -->
                <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                    <span>{{ $category->rental_products_count ?? 0 }} {{ __('rental_categories.stats.products_count') }}</span>
                    <span>{{ $category->created_at->format('d/m/Y') }}</span>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-between">
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.rental-categories.show', $category) }}"
                           class="text-blue-600 hover:text-blue-800 transition-colors"
                           title="{{ __('rental_categories.actions.view') }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </a>
                        <a href="{{ route('admin.rental-categories.edit', $category) }}"
                           class="text-indigo-600 hover:text-indigo-800 transition-colors"
                           title="{{ __('rental_categories.actions.edit') }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </a>
                        <button @click="deleteCategory({{ $category->id }}, '{{ $category->name }}', {{ $category->rental_products_count ?? 0 }})"
                                class="text-red-600 hover:text-red-800 transition-colors"
                                title="{{ __('rental_categories.actions.delete') }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Indicateur de produits -->
                    @if(($category->rental_products_count ?? 0) > 0)
                        <div class="flex items-center text-xs text-gray-500">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            {{ $category->rental_products_count }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('rental_categories.empty.title') }}</h3>
            <p class="mt-1 text-sm text-gray-500">{{ __('rental_categories.empty.description') }}</p>
            <div class="mt-6">
                <a href="{{ route('admin.rental-categories.create') }}"
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    {{ __('rental_categories.empty.add_first') }}
                </a>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if(isset($rentalCategories) && $rentalCategories->hasPages())
    <div class="mt-8">
        {{ $rentalCategories->links() }}
    </div>
    @endif

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
                            <h3 class="text-lg leading-6 font-medium text-gray-900">{{ __('rental_categories.delete_modal.title') }}</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500" x-show="categoryToDelete.rental_products_count === 0">
                                    {{ __('rental_categories.delete_modal.confirm_message') }}
                                    <strong x-text="categoryToDelete.name"></strong>
                                </p>
                                <p class="text-sm text-red-600" x-show="categoryToDelete.rental_products_count > 0">
                                    {{ __('rental_categories.delete_modal.cannot_delete') }}
                                    <strong x-text="categoryToDelete.name"></strong>
                                    (<span x-text="categoryToDelete.rental_products_count"></span> produits)
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <form x-show="categoryToDelete.rental_products_count === 0" :action="'/admin/rental-categories/' + categoryToDelete.id" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            {{ __('rental_categories.delete_modal.delete_button') }}
                        </button>
                    </form>
                    <button @click="showDeleteModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        {{ __('rental_categories.delete_modal.close_button') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('rentalCategoryManager', () => ({
        searchTerm: '',
        selectedStatus: '',
        sortBy: 'name',
        showDeleteModal: false,
        categoryToDelete: {},

        filterCategories() {
            // Cette fonction pourrait être utilisée pour filtrer côté client
            // Pour l'instant, nous utilisons la pagination Laravel côté serveur
        },

        deleteCategory(id, name, rental_products_count) {
            this.categoryToDelete = { id, name, rental_products_count };
            this.showDeleteModal = true;
        }
    }))
})
</script>
@endsection
