@extends('layouts.admin')

@section('title', 'Ajouter une Catégorie - FarmShop Admin')
@section('page-title', 'Ajouter une Catégorie')

@section('content')
<div x-data="categoryForm">
    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        
        <!-- En-tête -->
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Ajouter une nouvelle catégorie</h2>
                <p class="text-gray-600">Créez une catégorie pour organiser vos produits</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.categories.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg transition-colors">
                    Annuler
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                    Créer la catégorie
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Colonne principale -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Informations générales -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informations générales</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom de la catégorie *</label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}"
                                   required
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea id="description" 
                                      name="description" 
                                      rows="4"
                                      placeholder="Description de la catégorie..."
                                      class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="icon" class="block text-sm font-medium text-gray-700 mb-1">Icône (emoji)</label>
                                <input type="text" 
                                       id="icon" 
                                       name="icon" 
                                       value="{{ old('icon') }}"
                                       placeholder="🚜"
                                       maxlength="10"
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('icon') border-red-500 @enderror">
                                <p class="mt-1 text-xs text-gray-500">Optionnel : utilisez un emoji pour représenter la catégorie</p>
                                @error('icon')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="display_order" class="block text-sm font-medium text-gray-700 mb-1">Ordre d'affichage</label>
                                <input type="number" 
                                       id="display_order" 
                                       name="display_order" 
                                       value="{{ old('display_order', 0) }}"
                                       min="0"
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('display_order') border-red-500 @enderror">
                                <p class="mt-1 text-xs text-gray-500">0 = en premier, plus le nombre est élevé, plus la catégorie apparaît en bas</p>
                                @error('display_order')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SEO -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Référencement (SEO)</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-1">Titre SEO</label>
                            <input type="text" 
                                   id="meta_title" 
                                   name="meta_title" 
                                   value="{{ old('meta_title') }}"
                                   maxlength="255"
                                   placeholder="Titre pour les moteurs de recherche"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('meta_title') border-red-500 @enderror">
                            @error('meta_title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-1">Description SEO</label>
                            <textarea id="meta_description" 
                                      name="meta_description" 
                                      rows="3"
                                      maxlength="500"
                                      placeholder="Description pour les moteurs de recherche"
                                      class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('meta_description') border-red-500 @enderror">{{ old('meta_description') }}</textarea>
                            @error('meta_description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colonne latérale -->
            <div class="space-y-6">
                
                <!-- Type de catégorie -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Type de catégorie</h3>
                    
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <input type="radio" 
                                   id="food_type_alimentaire" 
                                   name="food_type" 
                                   value="alimentaire"
                                   {{ old('food_type', 'alimentaire') === 'alimentaire' ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                            <label for="food_type_alimentaire" class="ml-2 block text-sm text-gray-900">
                                🍎 Alimentaire
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input type="radio" 
                                   id="food_type_non_alimentaire" 
                                   name="food_type" 
                                   value="non_alimentaire"
                                   {{ old('food_type') === 'non_alimentaire' ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                            <label for="food_type_non_alimentaire" class="ml-2 block text-sm text-gray-900">
                                🔧 Non alimentaire
                            </label>
                        </div>
                    </div>
                    @error('food_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Statut -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Statut</h3>
                    
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="is_active" 
                               name="is_active" 
                               value="1"
                               {{ old('is_active', true) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-900">
                            Catégorie active
                        </label>
                    </div>
                    <p class="mt-2 text-xs text-gray-500">Une catégorie inactive ne sera pas visible sur le site</p>
                </div>

                <!-- Image -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Image de la catégorie</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Image</label>
                            <input type="file" 
                                   id="image" 
                                   name="image" 
                                   accept="image/*"
                                   @change="previewImage"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('image') border-red-500 @enderror">
                            <p class="mt-1 text-xs text-gray-500">Formats acceptés : JPG, PNG, GIF (max 2MB)</p>
                            @error('image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Prévisualisation de l'image -->
                        <div x-show="imagePreview" class="mt-4">
                            <p class="text-sm font-medium text-gray-700 mb-2">Prévisualisation :</p>
                            <div class="relative">
                                <img :src="imagePreview" 
                                     class="w-full h-32 object-cover rounded-lg border border-gray-200" 
                                     alt="Prévisualisation">
                                <button type="button" 
                                        @click="removePreview"
                                        class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Suggestions d'icônes -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Icônes suggérées</h3>
                    
                    <div class="grid grid-cols-4 gap-2">
                        <button type="button" 
                                @click="selectIcon('🚜')"
                                class="p-2 text-2xl hover:bg-gray-100 rounded-md transition-colors border border-gray-200">
                            🚜
                        </button>
                        <button type="button" 
                                @click="selectIcon('🌾')"
                                class="p-2 text-2xl hover:bg-gray-100 rounded-md transition-colors border border-gray-200">
                            🌾
                        </button>
                        <button type="button" 
                                @click="selectIcon('🔧')"
                                class="p-2 text-2xl hover:bg-gray-100 rounded-md transition-colors border border-gray-200">
                            🔧
                        </button>
                        <button type="button" 
                                @click="selectIcon('⚙️')"
                                class="p-2 text-2xl hover:bg-gray-100 rounded-md transition-colors border border-gray-200">
                            ⚙️
                        </button>
                        <button type="button" 
                                @click="selectIcon('🔩')"
                                class="p-2 text-2xl hover:bg-gray-100 rounded-md transition-colors border border-gray-200">
                            🔩
                        </button>
                        <button type="button" 
                                @click="selectIcon('🏗️')"
                                class="p-2 text-2xl hover:bg-gray-100 rounded-md transition-colors border border-gray-200">
                            🏗️
                        </button>
                        <button type="button" 
                                @click="selectIcon('🌱')"
                                class="p-2 text-2xl hover:bg-gray-100 rounded-md transition-colors border border-gray-200">
                            🌱
                        </button>
                        <button type="button" 
                                @click="selectIcon('📦')"
                                class="p-2 text-2xl hover:bg-gray-100 rounded-md transition-colors border border-gray-200">
                            📦
                        </button>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="space-y-3">
                        <button type="submit" 
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors font-medium">
                            Créer la catégorie
                        </button>
                        <a href="{{ route('admin.categories.index') }}" 
                           class="w-full bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg transition-colors font-medium text-center block">
                            Annuler
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('categoryForm', () => ({
        imagePreview: null,

        previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.imagePreview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },

        removePreview() {
            this.imagePreview = null;
            document.getElementById('image').value = '';
        },

        selectIcon(icon) {
            document.getElementById('icon').value = icon;
        }
    }))
})
</script>
@endsection
