@extends('layouts.admin')

@section('title', 'Ajouter un Produit - FarmShop Admin')
@section('page-title', 'Ajouter un Produit')

@section('content')
<div x-data="productForm">
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        
        <!-- En-tête -->
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Ajouter un nouveau produit</h2>
                <p class="text-gray-600">Complétez les informations ci-dessous pour créer un produit</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.products.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg transition-colors">
                    Annuler
                </a>
                <button type="submit" 
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition-colors">
                    Créer le produit
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Colonne principale -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Informations générales -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informations générales</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom du produit *</label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}"
                                   x-model="formData.name"
                                   @input="generateSKU"
                                   required
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="sku" class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                            <input type="text" 
                                   id="sku" 
                                   name="sku" 
                                   value="{{ old('sku') }}"
                                   x-model="formData.sku"
                                   placeholder="Généré automatiquement"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 @error('sku') border-red-500 @enderror">
                            @error('sku')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Catégorie *</label>
                            <select id="category_id" 
                                    name="category_id" 
                                    required
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 @error('category_id') border-red-500 @enderror">
                                <option value="">Sélectionner une catégorie</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="short_description" class="block text-sm font-medium text-gray-700 mb-1">Description courte</label>
                            <textarea id="short_description" 
                                      name="short_description" 
                                      rows="2"
                                      placeholder="Résumé du produit en quelques mots..."
                                      class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 @error('short_description') border-red-500 @enderror">{{ old('short_description') }}</textarea>
                            @error('short_description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description complète *</label>
                            <textarea id="description" 
                                      name="description" 
                                      rows="6"
                                      required
                                      placeholder="Description détaillée du produit, ses caractéristiques, ses avantages..."
                                      class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Prix et type -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Prix et disponibilité</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type de produit *</label>
                            <select id="type" 
                                    name="type" 
                                    x-model="formData.type"
                                    @change="updatePriceFields"
                                    required
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 @error('type') border-red-500 @enderror">
                                <option value="">Sélectionner un type</option>
                                <option value="sale" {{ old('type') == 'sale' ? 'selected' : '' }}>Vente uniquement</option>
                                <option value="rental" {{ old('type') == 'rental' ? 'selected' : '' }}>Location uniquement</option>
                                <option value="both" {{ old('type') == 'both' ? 'selected' : '' }}>Vente et Location</option>
                            </select>
                            @error('type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="unit_symbol" class="block text-sm font-medium text-gray-700 mb-1">Unité *</label>
                            <select id="unit_symbol" 
                                    name="unit_symbol" 
                                    required
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 @error('unit_symbol') border-red-500 @enderror">
                                <option value="">Sélectionner une unité</option>
                                <option value="pièce" {{ old('unit_symbol') == 'pièce' ? 'selected' : '' }}>Pièce</option>
                                <option value="kg" {{ old('unit_symbol') == 'kg' ? 'selected' : '' }}>Kilogramme</option>
                                <option value="gramme" {{ old('unit_symbol') == 'gramme' ? 'selected' : '' }}>Gramme</option>
                                <option value="tonne" {{ old('unit_symbol') == 'tonne' ? 'selected' : '' }}>Tonne</option>
                                <option value="litre" {{ old('unit_symbol') == 'litre' ? 'selected' : '' }}>Litre</option>
                            </select>
                            @error('unit_symbol')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Prix d'achat -->
                        <div x-show="formData.type === 'sale' || formData.type === 'both'">
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Prix de vente (€) *</label>
                            <input type="number" 
                                   id="price" 
                                   name="price" 
                                   value="{{ old('price') }}"
                                   step="0.01"
                                   min="0"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 @error('price') border-red-500 @enderror">
                            @error('price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Prix de location -->
                        <div x-show="formData.type === 'rental' || formData.type === 'both'">
                            <label for="rental_price_per_day" class="block text-sm font-medium text-gray-700 mb-1">Prix de location/jour (€)</label>
                            <input type="number" 
                                   id="rental_price_per_day" 
                                   name="rental_price_per_day" 
                                   value="{{ old('rental_price_per_day') }}"
                                   step="0.01"
                                   min="0"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 @error('rental_price_per_day') border-red-500 @enderror">
                            @error('rental_price_per_day')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Caution pour location -->
                        <div x-show="formData.type === 'rental' || formData.type === 'both'">
                            <label for="deposit_amount" class="block text-sm font-medium text-gray-700 mb-1">Caution (€)</label>
                            <input type="number" 
                                   id="deposit_amount" 
                                   name="deposit_amount" 
                                   value="{{ old('deposit_amount') }}"
                                   step="0.01"
                                   min="0"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 @error('deposit_amount') border-red-500 @enderror">
                            @error('deposit_amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Durée minimum de location -->
                        <div x-show="formData.type === 'rental' || formData.type === 'both'">
                            <label for="min_rental_days" class="block text-sm font-medium text-gray-700 mb-1">Durée minimum (jours)</label>
                            <input type="number" 
                                   id="min_rental_days" 
                                   name="min_rental_days" 
                                   value="{{ old('min_rental_days', 1) }}"
                                   min="1"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 @error('min_rental_days') border-red-500 @enderror">
                            @error('min_rental_days')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Durée maximum de location -->
                        <div x-show="formData.type === 'rental' || formData.type === 'both'">
                            <label for="max_rental_days" class="block text-sm font-medium text-gray-700 mb-1">Durée maximum (jours) - <span class="text-sm text-gray-500">Laissez vide pour aucune limite</span></label>
                            <input type="number" 
                                   id="max_rental_days" 
                                   name="max_rental_days" 
                                   value="{{ old('max_rental_days') }}"
                                   min="1"
                                   placeholder="Optionnel - aucune limite si vide"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 @error('max_rental_days') border-red-500 @enderror">
                            @error('max_rental_days')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Si vide, il n'y aura pas de limite de durée maximum pour la location</p>
                        </div>
                    </div>
                </div>

                <!-- Gestion du stock -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Gestion du stock</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div x-show="formData.type === 'sale' || formData.type === 'both'">
                            <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantité en stock (vente) *</label>
                            <input type="number" 
                                   id="quantity" 
                                   name="quantity" 
                                   value="{{ old('quantity', 0) }}"
                                   min="0"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 @error('quantity') border-red-500 @enderror">
                            @error('quantity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div x-show="formData.type === 'rental' || formData.type === 'both'">
                            <label for="rental_stock" class="block text-sm font-medium text-gray-700 mb-1">Stock location *</label>
                            <input type="number" 
                                   id="rental_stock" 
                                   name="rental_stock" 
                                   value="{{ old('rental_stock', 0) }}"
                                   min="0"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 @error('rental_stock') border-red-500 @enderror">
                            @error('rental_stock')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Nombre d'unités disponibles pour la location</p>
                        </div>

                        <div>
                            <label for="critical_threshold" class="block text-sm font-medium text-gray-700 mb-1">Seuil critique *</label>
                            <input type="number" 
                                   id="critical_threshold" 
                                   name="critical_threshold" 
                                   value="{{ old('critical_threshold', 5) }}"
                                   min="0"
                                   required
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 @error('critical_threshold') border-red-500 @enderror">
                            @error('critical_threshold')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="low_stock_threshold" class="block text-sm font-medium text-gray-700 mb-1">Seuil de stock faible</label>
                            <input type="number" 
                                   id="low_stock_threshold" 
                                   name="low_stock_threshold" 
                                   value="{{ old('low_stock_threshold') }}"
                                   min="0"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 @error('low_stock_threshold') border-red-500 @enderror">
                            @error('low_stock_threshold')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Caractéristiques physiques -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Caractéristiques physiques</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="weight" class="block text-sm font-medium text-gray-700 mb-1">Poids (kg)</label>
                            <input type="number" 
                                   id="weight" 
                                   name="weight" 
                                   value="{{ old('weight') }}"
                                   step="0.001"
                                   min="0"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 @error('weight') border-red-500 @enderror">
                            @error('weight')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="dimensions" class="block text-sm font-medium text-gray-700 mb-1">Dimensions</label>
                            <input type="text" 
                                   id="dimensions" 
                                   name="dimensions" 
                                   value="{{ old('dimensions') }}"
                                   placeholder="ex: 120x80x150 cm"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 @error('dimensions') border-red-500 @enderror">
                            @error('dimensions')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
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
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 @error('meta_title') border-red-500 @enderror">
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
                                      class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 @error('meta_description') border-red-500 @enderror">{{ old('meta_description') }}</textarea>
                            @error('meta_description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="meta_keywords" class="block text-sm font-medium text-gray-700 mb-1">Mots-clés SEO</label>
                            <input type="text" 
                                   id="meta_keywords" 
                                   name="meta_keywords" 
                                   value="{{ old('meta_keywords') }}"
                                   placeholder="mot-clé1, mot-clé2, mot-clé3"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 @error('meta_keywords') border-red-500 @enderror">
                            @error('meta_keywords')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colonne latérale -->
            <div class="space-y-6">
                <!-- Statut et visibilité -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Statut et visibilité</h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="is_active" 
                                   name="is_active" 
                                   value="1"
                                   {{ old('is_active', true) ? 'checked' : '' }}
                                   class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                            <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                Produit actif
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="is_featured" 
                                   name="is_featured" 
                                   value="1"
                                   {{ old('is_featured') ? 'checked' : '' }}
                                   class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                            <label for="is_featured" class="ml-2 block text-sm text-gray-900">
                                Produit en vedette
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Images -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Images</h3>
                    
                    <div class="space-y-4">
                        <!-- Image principale -->
                        <div>
                            <label for="main_image" class="block text-sm font-medium text-gray-700 mb-1">Image principale</label>
                            <input type="file" 
                                   id="main_image" 
                                   name="main_image" 
                                   accept="image/*"
                                   @change="previewMainImage"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 @error('main_image') border-red-500 @enderror">
                            @error('main_image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            
                            <!-- Prévisualisation image principale -->
                            <div x-show="mainImagePreview" class="mt-2">
                                <img :src="mainImagePreview" class="w-full h-32 object-cover rounded-lg" alt="Prévisualisation">
                            </div>
                        </div>

                        <!-- Texte alternatif -->
                        <div>
                            <label for="image_alt" class="block text-sm font-medium text-gray-700 mb-1">Texte alternatif</label>
                            <input type="text" 
                                   id="image_alt" 
                                   name="image_alt" 
                                   value="{{ old('image_alt') }}"
                                   placeholder="Description de l'image pour l'accessibilité"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 @error('image_alt') border-red-500 @enderror">
                            @error('image_alt')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Galerie d'images -->
                        <div>
                            <label for="gallery_images" class="block text-sm font-medium text-gray-700 mb-1">Galerie d'images</label>
                            <input type="file" 
                                   id="gallery_images" 
                                   name="gallery_images[]" 
                                   accept="image/*"
                                   multiple
                                   @change="previewGalleryImages"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 @error('gallery_images.*') border-red-500 @enderror">
                            @error('gallery_images.*')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            
                            <!-- Prévisualisation galerie -->
                            <div x-show="galleryPreviews.length > 0" class="mt-2 grid grid-cols-2 gap-2">
                                <template x-for="(preview, index) in galleryPreviews" :key="index">
                                    <img :src="preview" class="w-full h-20 object-cover rounded-lg" alt="Prévisualisation galerie">
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="space-y-3">
                        <button type="submit" 
                                class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors font-medium">
                            Créer le produit
                        </button>
                        <a href="{{ route('admin.products.index') }}" 
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
    Alpine.data('productForm', () => ({
        formData: {
            name: '',
            sku: '',
            type: ''
        },
        mainImagePreview: null,
        galleryPreviews: [],

        generateSKU() {
            if (this.formData.name && !this.formData.sku) {
                const cleanName = this.formData.name
                    .toUpperCase()
                    .replace(/[^A-Z0-9]/g, '')
                    .substring(0, 6);
                this.formData.sku = cleanName + Math.floor(Math.random() * 100).toString().padStart(2, '0');
            }
        },

        updatePriceFields() {
            // Cette fonction est appelée quand le type de produit change
            // pour afficher/masquer les champs de prix appropriés
        },

        previewMainImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.mainImagePreview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },

        previewGalleryImages(event) {
            const files = Array.from(event.target.files);
            this.galleryPreviews = [];
            
            files.forEach(file => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.galleryPreviews.push(e.target.result);
                };
                reader.readAsDataURL(file);
            });
        }
    }))
})
</script>
@endsection
