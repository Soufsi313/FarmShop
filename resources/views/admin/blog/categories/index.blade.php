@extends('layouts.admin')

@section('title', __('app.admin.categories.page_title'))
@section('page-title', __('app.admin.categories.section_title'))

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

    /* Amélioration des champs de formulaire */
    .form-input {
        padding: 12px 16px;
        font-size: 16px;
        line-height: 1.5;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        transition: all 0.2s ease-in-out;
        background-color: #fafafa;
    }

    .form-input:focus {
        outline: none;
        border-color: #8B5CF6;
        background-color: #ffffff;
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
        transform: translateY(-1px);
    }

    .form-input:hover {
        border-color: #d1d5db;
        background-color: #ffffff;
    }

    .form-select {
        padding: 12px 16px 12px 16px;
        font-size: 16px;
        line-height: 1.5;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        transition: all 0.2s ease-in-out;
        background-color: #fafafa;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 12px center;
        background-repeat: no-repeat;
        background-size: 16px 12px;
        padding-right: 48px;
        appearance: none;
    }

    .form-select:focus {
        outline: none;
        border-color: #8B5CF6;
        background-color: #ffffff;
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
        transform: translateY(-1px);
    }

    .form-select:hover {
        border-color: #d1d5db;
        background-color: #ffffff;
    }

    .form-textarea {
        padding: 12px 16px;
        font-size: 16px;
        line-height: 1.5;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        transition: all 0.2s ease-in-out;
        background-color: #fafafa;
        resize: vertical;
        min-height: 120px;
    }

    .form-textarea:focus {
        outline: none;
        border-color: #8B5CF6;
        background-color: #ffffff;
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
        transform: translateY(-1px);
    }

    .form-textarea:hover {
        border-color: #d1d5db;
        background-color: #ffffff;
    }

    .form-label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
    }

    .form-button {
        padding: 12px 24px;
        font-size: 16px;
        font-weight: 600;
        border-radius: 8px;
        transition: all 0.2s ease-in-out;
        cursor: pointer;
        border: 2px solid transparent;
    }

    .form-button:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.12);
    }

    .form-button:active {
        transform: translateY(0);
    }

    .form-button-primary {
        background-color: #8B5CF6;
        color: white;
        border-color: #8B5CF6;
    }

    .form-button-primary:hover {
        background-color: #7C3AED;
        border-color: #7C3AED;
    }

    .form-button-secondary {
        background-color: #6B7280;
        color: white;
        border-color: #6B7280;
    }

    .form-button-secondary:hover {
        background-color: #4B5563;
        border-color: #4B5563;
    }

    .form-color-input {
        width: 100%;
        height: 48px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
    }

    .form-color-input:hover {
        border-color: #d1d5db;
        transform: scale(1.02);
    }

    .form-color-input:focus {
        outline: none;
        border-color: #8B5CF6;
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
    }
</style>
@endpush

@section('content')
<div x-data="{
    showCreateModal: false,
    showEditModal: false,
    showDeleteModal: false,
    isSubmitting: false,
    notification: {
        show: false,
        type: 'success', // success, error, warning, info
        title: '',
        message: '',
        timer: null
    },
    newCategory: {
        name: '',
        description: '',
        color: '#8B5CF6',
        sort_order: 0,
        is_active: true
    },
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
    showNotification(type, title, message, duration = 5000) {
        // Effacer le timer précédent s'il existe
        if (this.notification.timer) {
            clearTimeout(this.notification.timer);
        }
        
        this.notification = {
            show: true,
            type: type,
            title: title,
            message: message,
            timer: null
        };
        
        // Auto-fermeture après duration ms
        this.notification.timer = setTimeout(() => {
            this.hideNotification();
        }, duration);
    },
    hideNotification() {
        this.notification.show = false;
        if (this.notification.timer) {
            clearTimeout(this.notification.timer);
            this.notification.timer = null;
        }
    },
    async submitCreateForm() {
        if (!this.newCategory.name) {
            this.showNotification('error', 'Erreur', 'Le nom de la catégorie est requis.');
            return;
        }
        
        this.isSubmitting = true;
        
        try {
            const formData = new FormData();
            formData.append('name', this.newCategory.name);
            formData.append('description', this.newCategory.description);
            formData.append('color', this.newCategory.color);
            formData.append('sort_order', this.newCategory.sort_order);
            formData.append('is_active', this.newCategory.is_active ? '1' : '0');
            formData.append('_token', '{{ csrf_token() }}');
            
            console.log('Sending data:', Object.fromEntries(formData));
            
            const response = await fetch('{{ route("admin.blog-categories.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            console.log('Response status:', response.status);
            
            if (response.ok) {
                const result = await response.text();
                console.log('Response:', result);
                
                // Réinitialiser le formulaire
                this.newCategory = {
                    name: '',
                    description: '',
                    color: '#8B5CF6',
                    sort_order: 0,
                    is_active: true
                };
                
                // Fermer la modal
                this.showCreateModal = false;
                
                // Afficher un message de succès personnalisé
                this.showNotification('success', 'Succès !', 'La catégorie a été créée avec succès.', 4000);
                
                // Recharger la page après un délai pour voir la nouvelle catégorie
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                const errorText = await response.text();
                console.error('Error response:', errorText);
                this.showNotification('error', 'Erreur', 'Erreur lors de la création de la catégorie. Vérifiez la console pour plus de détails.');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showNotification('error', 'Erreur réseau', 'Impossible de contacter le serveur. Vérifiez votre connexion.');
        } finally {
            this.isSubmitting = false;
        }
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
                    {{ __('app.admin.categories.title') }}
                </h1>
                <p class="mt-2 text-sm text-gray-600">{{ __('app.admin.categories.description') }}</p>
            </div>
            <div class="mt-4 sm:mt-0 sm:flex sm:space-x-3">
                <a href="{{ route('admin.blog.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    {{ __('app.admin.categories.view_articles') }}
                </a>
                <button @click="showCreateModal = true" 
                        type="button" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 transition-colors">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    {{ __('app.admin.categories.new_category_btn') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="stats-card rounded-lg p-6">
            <h3 class="text-sm font-medium">{{ __('app.admin.categories.total_stats') }}</h3>
            <p class="text-3xl font-bold stats-value">{{ $categories->total() }}</p>
        </div>
        <div class="bg-white rounded-lg p-6 border border-gray-200">
            <h3 class="text-sm font-medium text-gray-500">{{ __('app.admin.categories.active_stats') }}</h3>
            <p class="text-3xl font-bold text-green-600">{{ $categories->where('is_active', true)->count() }}</p>
        </div>
        <div class="bg-white rounded-lg p-6 border border-gray-200">
            <h3 class="text-sm font-medium text-gray-500">{{ __('app.admin.categories.inactive_stats') }}</h3>
            <p class="text-3xl font-bold text-red-600">{{ $categories->where('is_active', false)->count() }}</p>
        </div>
        <div class="bg-white rounded-lg p-6 border border-gray-200">
            <h3 class="text-sm font-medium text-gray-500">{{ __('app.admin.categories.articles_total') }}</h3>
            <p class="text-3xl font-bold text-blue-600">{{ $categories->sum('articles_count') }}</p>
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="bg-white shadow-lg rounded-xl p-8">
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ __('app.admin.categories.search_and_filters') }}</h3>
            <p class="text-sm text-gray-600">{{ __('app.admin.categories.search_and_filters_description') }}</p>
        </div>
        
        <form method="GET" action="{{ route('admin.blog-categories.index') }}" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="space-y-2">
                    <label for="search" class="form-label">
                        <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        {{ __('app.admin.categories.search_category_placeholder') }}
                    </label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" 
                           placeholder="{{ __('app.admin.categories.search_category_placeholder') }}..." 
                           class="form-input w-full">
                </div>
                
                <div class="space-y-2">
                    <label for="status" class="form-label">
                        <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ __('app.admin.categories.category_status') }}
                    </label>
                    <select name="status" id="status" class="form-select w-full">
                        <option value="">{{ __('app.admin.categories.all_categories') }}</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('app.admin.categories.status_active') }}</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>{{ __('app.admin.categories.status_inactive') }}</option>
                    </select>
                </div>
                
                <div class="space-y-2">
                    <label class="form-label">{{ __('app.admin.categories.actions_title') }}</label>
                    <div class="flex space-x-3">
                        <button type="submit" class="form-button form-button-primary flex-1">
                            <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.414A1 1 0 013 6.707V4z"/>
                            </svg>
                            {{ __('app.admin.categories.filter_button') }}
                        </button>
                        <a href="{{ route('admin.blog-categories.index') }}" class="form-button form-button-secondary">
                            <svg class="inline w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Liste des catégories -->
    <div class="bg-white shadow-lg rounded-xl border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">{{ __('app.admin.categories.categories_count') }} ({{ $categories->total() }})</h3>
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
                                        <span>{{ __('app.admin.categories.order_label') }} {{ $category->sort_order }}</span>
                                        <span>{{ __('app.admin.categories.articles_label') }} {{ $category->articles_count ?? 0 }}</span>
                                        <span class="px-2 py-1 rounded-full text-xs {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $category->is_active ? __('app.admin.categories.status_active') : __('app.admin.categories.status_inactive') }}
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
                <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('app.admin.categories.no_categories_title') }}</h3>
                <p class="mt-1 text-sm text-gray-500">
                    @if(request()->hasAny(['search', 'status']))
                        {{ __('app.admin.categories.no_search_results') }}
                    @else
                        {{ __('app.admin.categories.create_first_blog_category') }}
                    @endif
                </p>
                <div class="mt-6">
                    <button @click="showCreateModal = true" type="button" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        {{ __('app.admin.categories.create_category_btn') }}
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
            
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" @click.stop>
                <form @submit.prevent="submitCreateForm()" method="POST">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="mb-4">
                            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                <svg class="h-5 w-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                {{ __('app.admin.categories.create_new_category') }}
                            </h3>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="space-y-2">
                                <label for="create_name" class="form-label">
                                    <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                    {{ __('app.admin.categories.category_name_label') }}
                                </label>
                                <input type="text" name="name" id="create_name" required x-model="newCategory.name"
                                       placeholder="{{ __('app.admin.categories.category_name_placeholder') }}"
                                       class="form-input w-full">
                            </div>
                            
                            <div class="space-y-2">
                                <label for="create_description" class="form-label">
                                    <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                                    </svg>
                                    {{ __('app.admin.categories.description_label') }}
                                </label>
                                <textarea name="description" id="create_description" rows="4" x-model="newCategory.description"
                                          placeholder="{{ __('app.admin.categories.description_placeholder') }}"
                                          class="form-textarea w-full"></textarea>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label for="create_color" class="form-label">
                                        <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM7 3H5a2 2 0 00-2 2v12a4 4 0 004 4h2V3z"/>
                                        </svg>
                                        {{ __('app.admin.categories.color_label') }}
                                    </label>
                                    <input type="color" name="color" id="create_color" x-model="newCategory.color"
                                           class="form-color-input">
                                </div>
                                
                                <div class="space-y-2">
                                    <label for="create_sort_order" class="form-label">
                                        <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                                        </svg>
                                        {{ __('app.admin.categories.sort_order_label') }}
                                    </label>
                                    <input type="number" name="sort_order" id="create_sort_order" min="0" x-model="newCategory.sort_order"
                                           placeholder="{{ __('app.admin.categories.sort_order_placeholder') }}"
                                           class="form-input w-full">
                                </div>
                            </div>
                            
                            <div class="flex items-center p-4 bg-purple-50 rounded-lg border border-purple-200">
                                <input type="checkbox" name="is_active" id="create_is_active" x-model="newCategory.is_active" 
                                       class="h-5 w-5 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                <label for="create_is_active" class="ml-3 block text-sm font-medium text-purple-900">
                                    <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ __('app.admin.categories.active_visible_category') }}
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-6 py-4 sm:flex sm:flex-row-reverse border-t border-gray-200">
                        <button type="submit" class="form-button form-button-primary sm:ml-3 sm:w-auto w-full">
                            <span x-show="!isSubmitting" class="flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                {{ __('app.admin.categories.create_button') }}
                            </span>
                            <span x-show="isSubmitting" class="flex items-center justify-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                {{ __('app.admin.categories.creating_category') }}
                            </span>
                        </button>
                        <button type="button" @click="showCreateModal = false" class="form-button form-button-secondary mt-3 sm:mt-0 w-full sm:w-auto">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            {{ __('app.admin.categories.cancel_button') }}
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

    <!-- Notification Toast -->
    <div x-show="notification.show" 
         x-transition:enter="transform ease-out duration-300 transition"
         x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
         x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed top-4 right-4 z-50 max-w-sm w-full"
         style="display: none;">
        <div class="bg-white rounded-lg shadow-lg border-l-4 overflow-hidden"
             :class="{
                'border-green-500': notification.type === 'success',
                'border-red-500': notification.type === 'error',
                'border-yellow-500': notification.type === 'warning',
                'border-blue-500': notification.type === 'info'
             }">
            <div class="p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <!-- Icône Success -->
                        <svg x-show="notification.type === 'success'" class="h-6 w-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <!-- Icône Error -->
                        <svg x-show="notification.type === 'error'" class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <!-- Icône Warning -->
                        <svg x-show="notification.type === 'warning'" class="h-6 w-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        <!-- Icône Info -->
                        <svg x-show="notification.type === 'info'" class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium"
                           :class="{
                               'text-green-800': notification.type === 'success',
                               'text-red-800': notification.type === 'error',
                               'text-yellow-800': notification.type === 'warning',
                               'text-blue-800': notification.type === 'info'
                           }"
                           x-text="notification.title"></p>
                        <p class="mt-1 text-sm text-gray-600" x-text="notification.message"></p>
                    </div>
                    <div class="ml-4 flex-shrink-0 flex">
                        <button @click="hideNotification()" class="inline-flex text-gray-400 hover:text-gray-600 focus:outline-none">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Barre de progression -->
            <div class="h-1 w-full bg-gray-200">
                <div class="h-1 bg-gradient-to-r transition-all duration-75 ease-linear"
                     :class="{
                         'from-green-500 to-green-600': notification.type === 'success',
                         'from-red-500 to-red-600': notification.type === 'error',
                         'from-yellow-500 to-yellow-600': notification.type === 'warning',
                         'from-blue-500 to-blue-600': notification.type === 'info'
                     }"
                     x-show="notification.show"
                     x-transition:enter="transition-all duration-5000 ease-linear"
                     x-transition:enter-start="w-full"
                     x-transition:enter-end="w-0">
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
