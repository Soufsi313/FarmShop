@extends('layouts.admin')

@section('title', 'Gestion des Catégories de Blog - FarmShop Admin')
@section('page-title', 'Gestion des Catégories de Blog')

@push('styles')
<style>
    .category-card {
        transition: all 0.3s ease;
        border: 1px solid #e5e7eb;
    }
    
    .category-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        border-color: #d1d5db;
    }
    
    .stats-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
    }
    
    .stats-card h3 {
        color: rgba(255, 255, 255, 0.9);
    }
    
    .stats-value {
        color: white;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }
    
    .color-picker-preview {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        border: 2px solid #ffffff;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
        flex-shrink: 0;
    }
</style>
@endpush

@section('content')
<div x-data="{
    showCreateModal: false,
    showEditModal: false,
    showDeleteModal: false,
    editingCategory: {
        id: null,
        name: '',
        description: '',
        color: '#8B5CF6',
        sort_order: 0,
        is_active: true
    },
    deletingCategory: {
        id: null,
        name: ''
    },
    editCategory(id, name, description, color, sort_order, is_active) {
        this.editingCategory = {
            id: id,
            name: name,
            description: description || '',
            color: color || '#8B5CF6',
            sort_order: sort_order || 0,
            is_active: is_active
        };
        this.showEditModal = true;
    },
    deleteCategory(id, name) {
        this.deletingCategory = {
            id: id,
            name: name
        };
        this.showDeleteModal = true;
    }
}" class="space-y-6">

    <!-- Messages flash -->
    @if(session('success'))
        <div class="fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-50">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded z-50">
            {{ session('error') }}
        </div>
    @endif

    <!-- En-tête -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                    <svg class="h-8 w-8 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    Catégories de Blog
                </h1>
                <p class="mt-2 text-sm text-gray-600">Organisez vos articles par catégories pour une meilleure navigation</p>
            </div>
            <div class="mt-4 sm:mt-0 sm:flex sm:space-x-3">
                <a href="{{ route('admin.blog.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    Voir Articles
                </a>
                <button @click="showCreateModal = true" 
                        type="button" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 transition-colors">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nouvelle Catégorie
                </button>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="stats-card rounded-lg p-6">
            <h3 class="text-sm font-medium">Total Catégories</h3>
            <p class="text-3xl font-bold stats-value">{{ $categories->total() }}</p>
        </div>
        <div class="bg-white rounded-lg p-6 border border-gray-200">
            <h3 class="text-sm font-medium text-gray-500">Catégories Actives</h3>
            <p class="text-3xl font-bold text-green-600">{{ $categories->where('is_active', true)->count() }}</p>
        </div>
        <div class="bg-white rounded-lg p-6 border border-gray-200">
            <h3 class="text-sm font-medium text-gray-500">Catégories Inactives</h3>
            <p class="text-3xl font-bold text-red-600">{{ $categories->where('is_active', false)->count() }}</p>
        </div>
        <div class="bg-white rounded-lg p-6 border border-gray-200">
            <h3 class="text-sm font-medium text-gray-500">Articles Total</h3>
            <p class="text-3xl font-bold text-blue-600">{{ $categories->sum('articles_count') }}</p>
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="bg-white shadow rounded-lg p-6">
        <form method="GET" action="{{ route('admin.blog-categories.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700">Rechercher</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" 
                           placeholder="Nom de catégorie..." 
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Statut</label>
                    <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
                        <option value="">Tous les statuts</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actives</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactives</option>
                    </select>
                </div>
                <div class="flex items-end space-x-2">
                    <button type="submit" class="flex-1 bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700">
                        Filtrer
                    </button>
                    <a href="{{ route('admin.blog-categories.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Liste des catégories -->
    <div class="bg-white shadow-lg rounded-xl border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Catégories ({{ $categories->total() }})</h3>
        </div>

        @if($categories->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($categories as $category)
                    <div class="p-6 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="color-picker-preview" style="background-color: {{ $category->color ?? '#8B5CF6' }}"></div>
                                <div>
                                    <h4 class="text-lg font-medium text-gray-900">{{ $category->name }}</h4>
                                    @if($category->description)
                                        <p class="text-sm text-gray-600 mt-1">{{ $category->description }}</p>
                                    @endif
                                    <div class="flex items-center space-x-4 mt-2 text-sm text-gray-500">
                                        <span>Ordre: {{ $category->sort_order }}</span>
                                        <span>Articles: {{ $category->articles_count ?? 0 }}</span>
                                        <span class="px-2 py-1 rounded-full text-xs {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button @click="editCategory({{ $category->id }}, '{{ $category->name }}', '{{ $category->description }}', '{{ $category->color }}', {{ $category->sort_order }}, {{ $category->is_active ? 'true' : 'false' }})"
                                        class="text-blue-600 hover:text-blue-900">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <button @click="deleteCategory({{ $category->id }}, '{{ $category->name }}')"
                                        class="text-red-600 hover:text-red-900">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $categories->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune catégorie</h3>
                <p class="mt-1 text-sm text-gray-500">
                    @if(request()->hasAny(['search', 'status']))
                        Aucune catégorie ne correspond à vos critères de recherche.
                    @else
                        Commencez par créer votre première catégorie de blog.
                    @endif
                </p>
                <div class="mt-6">
                    <button @click="showCreateModal = true" type="button" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Créer une catégorie
                    </button>
                </div>
            </div>
        @endif
    </div>

    <!-- Modal Créer Catégorie -->
    <div x-show="showCreateModal" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showCreateModal = false"></div>
            
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form method="POST" action="{{ route('admin.blog-categories.store') }}">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="mb-4">
                            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                <svg class="h-5 w-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Créer une nouvelle catégorie
                            </h3>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Nom de la catégorie *</label>
                                <input type="text" name="name" id="name" required 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
                            </div>
                            
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea name="description" id="description" rows="3" 
                                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500"></textarea>
                            </div>
                            
                            <div>
                                <label for="color" class="block text-sm font-medium text-gray-700">Couleur</label>
                                <input type="color" name="color" id="color" value="#8B5CF6"
                                       class="mt-1 block w-full h-10 border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
                            </div>
                            
                            <div>
                                <label for="sort_order" class="block text-sm font-medium text-gray-700">Ordre d'affichage</label>
                                <input type="number" name="sort_order" id="sort_order" value="0" min="0"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" name="is_active" id="is_active" checked 
                                       class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                <label for="is_active" class="ml-2 block text-sm text-gray-900">Catégorie active</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-purple-600 text-base font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Créer la catégorie
                        </button>
                        <button @click="showCreateModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Annuler
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Modifier Catégorie -->
    <div x-show="showEditModal" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showEditModal = false"></div>
            
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form :action="`{{ route('admin.blog-categories.index') }}/${editingCategory.id}`" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Modifier la catégorie</h3>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="edit_name" class="block text-sm font-medium text-gray-700">Nom de la catégorie *</label>
                                <input type="text" name="name" id="edit_name" required :value="editingCategory.name"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
                            </div>
                            
                            <div>
                                <label for="edit_description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea name="description" id="edit_description" rows="3" x-text="editingCategory.description"
                                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500"></textarea>
                            </div>
                            
                            <div>
                                <label for="edit_color" class="block text-sm font-medium text-gray-700">Couleur</label>
                                <input type="color" name="color" id="edit_color" :value="editingCategory.color"
                                       class="mt-1 block w-full h-10 border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
                            </div>
                            
                            <div>
                                <label for="edit_sort_order" class="block text-sm font-medium text-gray-700">Ordre d'affichage</label>
                                <input type="number" name="sort_order" id="edit_sort_order" min="0" :value="editingCategory.sort_order"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" name="is_active" id="edit_is_active" :checked="editingCategory.is_active"
                                       class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                <label for="edit_is_active" class="ml-2 block text-sm text-gray-900">Catégorie active</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-purple-600 text-base font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Modifier
                        </button>
                        <button @click="showEditModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Annuler
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Supprimer Catégorie -->
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Supprimer la catégorie</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Êtes-vous sûr de vouloir supprimer la catégorie "<span x-text="deletingCategory.name"></span>" ? 
                                    Cette action est irréversible.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <form :action="`{{ route('admin.blog-categories.index') }}/${deletingCategory.id}`" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Supprimer définitivement
                        </button>
                    </form>
                    <button @click="showDeleteModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Annuler
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
console.log('Alpine.js page loaded for blog categories management!');
</script>
@endpush
