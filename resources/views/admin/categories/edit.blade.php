@extends('layouts.admin')

@section('title', 'Modifier la catégorie: ' . $category->name . ' - FarmShop Admin')
@section('page-title', 'Modifier la catégorie')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header avec navigation -->
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.categories.index') }}" 
               class="text-gray-600 hover:text-gray-900 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Modifier la catégorie</h1>
                <p class="text-gray-600">Modifier les informations de "{{ $category->name }}"</p>
            </div>
        </div>
        
        <div class="flex space-x-3">
            <a href="{{ route('admin.categories.show', $category) }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                <span>Voir</span>
            </a>
        </div>
    </div>

    <!-- Formulaire de modification -->
    <div class="bg-white rounded-lg shadow">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Informations de la catégorie</h3>
                <p class="mt-1 text-sm text-gray-600">Modifiez les détails de votre catégorie</p>
            </div>

            <div class="px-6 py-4 space-y-6">
                <!-- Informations générales -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nom de la catégorie -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nom de la catégorie *
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name', $category->name) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                               required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Type alimentaire -->
                    <div>
                        <label for="food_type" class="block text-sm font-medium text-gray-700 mb-2">
                            Type de produits
                        </label>
                        <select name="food_type" 
                                id="food_type" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="alimentaire" {{ old('food_type', $category->food_type) === 'alimentaire' ? 'selected' : '' }}>
                                Alimentaire
                            </option>
                            <option value="non_alimentaire" {{ old('food_type', $category->food_type) === 'non_alimentaire' ? 'selected' : '' }}>
                                Non alimentaire
                            </option>
                        </select>
                        @error('food_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea name="description" 
                              id="description" 
                              rows="4" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                              placeholder="Décrivez cette catégorie et les types de produits qu'elle contient...">{{ old('description', $category->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Champs SEO -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Titre SEO -->
                    <div>
                        <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">
                            Titre SEO
                        </label>
                        <input type="text" 
                               name="meta_title" 
                               id="meta_title" 
                               value="{{ old('meta_title', $category->meta_title) }}"
                               maxlength="255"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('meta_title') border-red-500 @enderror"
                               placeholder="Titre pour les moteurs de recherche">
                        @error('meta_title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Icône -->
                    <div>
                        <label for="icon" class="block text-sm font-medium text-gray-700 mb-2">
                            Icône (emoji)
                        </label>
                        <input type="text" 
                               name="icon" 
                               id="icon" 
                               value="{{ old('icon', $category->icon) }}"
                               maxlength="10"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('icon') border-red-500 @enderror"
                               placeholder="🍎">
                        @error('icon')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Description SEO et Ordre d'affichage -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Description SEO -->
                    <div class="md:col-span-2">
                        <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description SEO
                        </label>
                        <textarea name="meta_description" 
                                  id="meta_description" 
                                  rows="3" 
                                  maxlength="500"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('meta_description') border-red-500 @enderror"
                                  placeholder="Description pour les moteurs de recherche (160 caractères recommandés)">{{ old('meta_description', $category->meta_description) }}</textarea>
                        @error('meta_description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Ordre d'affichage -->
                    <div>
                        <label for="display_order" class="block text-sm font-medium text-gray-700 mb-2">
                            Ordre d'affichage
                        </label>
                        <input type="number" 
                               name="display_order" 
                               id="display_order" 
                               value="{{ old('display_order', $category->display_order) }}"
                               min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('display_order') border-red-500 @enderror">
                        <p class="mt-1 text-xs text-gray-500">0 = en premier</p>
                        @error('display_order')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Options -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Statut -->
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="is_active" 
                                   value="1"
                                   {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <span class="ml-2 text-sm text-gray-700">Catégorie active</span>
                        </label>
                        <p class="mt-1 text-xs text-gray-500">Une catégorie inactive ne sera pas visible sur le site</p>
                    </div>

                    <!-- Retournable -->
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="is_returnable" 
                                   value="1"
                                   {{ old('is_returnable', $category->is_returnable) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <span class="ml-2 text-sm text-gray-700">Produits retournables</span>
                        </label>
                        <p class="mt-1 text-xs text-gray-500">Les produits de cette catégorie peuvent être retournés</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between">
                <div class="flex space-x-3">
                    <a href="{{ route('admin.categories.index') }}" 
                       class="bg-white text-gray-700 px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                        Annuler
                    </a>
                    <a href="{{ route('admin.categories.show', $category) }}" 
                       class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition-colors">
                        Voir la catégorie
                    </a>
                </div>
                
                <button type="submit" 
                        class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition-colors flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Mettre à jour</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Zone de suppression dangereuse -->
    @if($category->products->count() === 0)
    <div class="mt-8 bg-white rounded-lg shadow border-l-4 border-red-500">
        <div class="px-6 py-4">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-lg font-medium text-red-900">Zone de danger</h3>
                    <p class="mt-1 text-sm text-red-700">
                        Cette action est irréversible. La catégorie sera définitivement supprimée.
                    </p>
                </div>
                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" 
                      onsubmit="return confirm('Êtes-vous absolument sûr de vouloir supprimer cette catégorie ? Cette action est irréversible.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition-colors flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        <span>Supprimer définitivement</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @else
    <div class="mt-8 bg-white rounded-lg shadow border-l-4 border-yellow-500">
        <div class="px-6 py-4">
            <div class="flex items-start">
                <svg class="w-6 h-6 text-yellow-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                <div>
                    <h3 class="text-lg font-medium text-yellow-900">Suppression impossible</h3>
                    <p class="mt-1 text-sm text-yellow-700">
                        Cette catégorie contient {{ $category->products->count() }} produit(s) et ne peut pas être supprimée. 
                        Supprimez ou déplacez d'abord tous les produits associés.
                    </p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
// Prévisualisation de l'image
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // Créer ou mettre à jour la prévisualisation
            const preview = document.getElementById('image-preview') || document.createElement('img');
            preview.id = 'image-preview';
            preview.src = e.target.result;
            preview.className = 'mt-2 max-w-xs h-32 object-cover rounded border';
            
            if (!document.getElementById('image-preview')) {
                e.target.closest('.space-y-1').appendChild(preview);
            }
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endsection
