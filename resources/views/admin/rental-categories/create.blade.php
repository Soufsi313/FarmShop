@extends('layouts.admin')

@section('title', 'Nouvelle Catégorie de Location - Dashboard Admin')
@section('page-title', 'Nouvelle Catégorie de Location')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="flex justify-between items-start">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Créer une catégorie de location</h2>
            <p class="text-gray-600">Ajoutez une nouvelle catégorie pour organiser vos produits de location</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.rental-categories.index') }}" 
               class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg transition-colors">
                Annuler
            </a>
        </div>
    </div>

    <!-- Messages d'erreur -->
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <!-- Formulaire -->
    <form method="POST" action="{{ route('admin.rental-categories.store') }}" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Colonne principale -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Informations générales -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informations générales</h3>
                    
                    <div class="space-y-4">
                        <!-- Nom -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                Nom de la catégorie *
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}"
                                   required
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-300 @enderror">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                                Description
                            </label>
                            <textarea id="description" 
                                      name="description" 
                                      rows="4"
                                      class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500 @error('description') border-red-300 @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- SEO -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Référencement SEO</h3>
                    
                    <div class="space-y-4">
                        <!-- Meta Title -->
                        <div>
                            <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-1">
                                Titre SEO (meta title)
                            </label>
                            <input type="text" 
                                   id="meta_title" 
                                   name="meta_title" 
                                   value="{{ old('meta_title') }}"
                                   maxlength="255"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500 @error('meta_title') border-red-300 @enderror">
                            <div class="mt-1 flex justify-between text-xs text-gray-500">
                                <span>Recommandé : 50-60 caractères</span>
                                <span id="meta-title-count">0 caractères</span>
                            </div>
                            @error('meta_title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Meta Description -->
                        <div>
                            <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-1">
                                Description SEO (meta description)
                            </label>
                            <textarea id="meta_description" 
                                      name="meta_description" 
                                      rows="3"
                                      maxlength="500"
                                      class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500 @error('meta_description') border-red-300 @enderror">{{ old('meta_description') }}</textarea>
                            <div class="mt-1 flex justify-between text-xs text-gray-500">
                                <span>Recommandé : 120-160 caractères</span>
                                <span id="meta-description-count">0 caractères</span>
                            </div>
                            @error('meta_description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Aperçu Google -->
                        <div class="p-4 border border-gray-200 rounded-lg bg-gray-50">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Aperçu dans Google</h4>
                            <div class="space-y-1">
                                <div class="text-blue-600 text-lg font-medium" id="preview-title">
                                    Nom de la catégorie
                                </div>
                                <div class="text-green-600 text-sm">
                                    {{ url('/') }}/locations/categories/slug-de-la-categorie
                                </div>
                                <div class="text-gray-600 text-sm" id="preview-description">
                                    Description de la catégorie...
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colonne latérale -->
            <div class="space-y-6">
                <!-- Paramètres -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Paramètres</h3>
                    
                    <div class="space-y-4">
                        <!-- Icône -->
                        <div>
                            <label for="icon" class="block text-sm font-medium text-gray-700 mb-1">
                                Icône (classe CSS)
                            </label>
                            <input type="text" 
                                   id="icon" 
                                   name="icon" 
                                   value="{{ old('icon') }}"
                                   placeholder="fas fa-tools"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500 @error('icon') border-red-300 @enderror">
                            <p class="mt-1 text-xs text-gray-500">Ex: fas fa-tools, fab fa-apple</p>
                            @error('icon')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Ordre d'affichage -->
                        <div>
                            <label for="display_order" class="block text-sm font-medium text-gray-700 mb-1">
                                Ordre d'affichage
                            </label>
                            <input type="number" 
                                   id="display_order" 
                                   name="display_order" 
                                   value="{{ old('display_order', 0) }}"
                                   min="0"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500 @error('display_order') border-red-300 @enderror">
                            <p class="mt-1 text-xs text-gray-500">Plus le nombre est petit, plus la catégorie apparaît en premier</p>
                            @error('display_order')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Statut -->
                        <div>
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="is_active" 
                                       name="is_active" 
                                       value="1"
                                       {{ old('is_active', true) ? 'checked' : '' }}
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="is_active" class="ml-2 block text-sm text-gray-700">
                                    Catégorie active
                                </label>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Les catégories inactives n'apparaissent pas sur le site</p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="space-y-3">
                        <button type="submit" 
                                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                            Créer la catégorie
                        </button>
                        
                        <a href="{{ route('admin.rental-categories.index') }}" 
                           class="w-full bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors text-center block">
                            Annuler
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const metaTitleInput = document.getElementById('meta_title');
    const metaDescriptionInput = document.getElementById('meta_description');
    const descriptionInput = document.getElementById('description');
    
    const previewTitle = document.getElementById('preview-title');
    const previewDescription = document.getElementById('preview-description');
    const metaTitleCount = document.getElementById('meta-title-count');
    const metaDescriptionCount = document.getElementById('meta-description-count');

    // Mise à jour de l'aperçu Google
    function updatePreview() {
        const title = metaTitleInput.value || nameInput.value || 'Nom de la catégorie';
        const description = metaDescriptionInput.value || descriptionInput.value || 'Description de la catégorie...';
        
        previewTitle.textContent = title;
        previewDescription.textContent = description;
    }

    // Compteur de caractères
    function updateCharCount() {
        const metaTitleLength = metaTitleInput.value.length;
        const metaDescriptionLength = metaDescriptionInput.value.length;
        
        metaTitleCount.textContent = `${metaTitleLength} caractères`;
        metaDescriptionCount.textContent = `${metaDescriptionLength} caractères`;
        
        // Couleurs selon la longueur
        if (metaTitleLength > 60 || metaTitleLength < 30) {
            metaTitleCount.className = 'text-xs text-orange-600';
        } else {
            metaTitleCount.className = 'text-xs text-green-600';
        }
        
        if (metaDescriptionLength > 160 || metaDescriptionLength < 120) {
            metaDescriptionCount.className = 'text-xs text-orange-600';
        } else {
            metaDescriptionCount.className = 'text-xs text-green-600';
        }
    }

    // Événements
    nameInput.addEventListener('input', updatePreview);
    metaTitleInput.addEventListener('input', function() {
        updatePreview();
        updateCharCount();
    });
    metaDescriptionInput.addEventListener('input', function() {
        updatePreview();
        updateCharCount();
    });
    descriptionInput.addEventListener('input', updatePreview);

    // Initial
    updatePreview();
    updateCharCount();
});
</script>
@endpush
