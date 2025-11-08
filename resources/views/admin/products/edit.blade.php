@extends('layouts.admin')

@section('title', 'Modifier le Produit - FarmShop Admin')
@section('page-title', 'Modifier le Produit')

@section('content')
<div x-data="productForm">
    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')
        
        <!-- En-tête -->
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Modifier "{{ $product->name }}"</h2>
                <p class="text-gray-600">Modifiez les informations du produit ci-dessous</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.products.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg transition-colors">
                    Annuler
                </a>
                <a href="{{ route('admin.products.show', $product) }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                    Voir le produit
                </a>
                <button type="submit" 
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition-colors">
                    Sauvegarder
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Colonne principale -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Images -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Images du produit</h3>
                    
                    <!-- Image principale -->
                    <div class="mb-6">
                        <label for="main_image" class="block text-sm font-medium text-gray-700 mb-2">
                            Image principale
                        </label>
                        @if($product->main_image)
                            <div class="mb-4">
                                <img src="{{ asset('storage/' . $product->main_image) }}" 
                                     alt="Image principale" 
                                     class="w-32 h-32 object-cover rounded-lg border">
                                <p class="text-sm text-gray-500 mt-1">Image actuelle</p>
                            </div>
                        @endif
                        <input type="file" 
                               id="main_image" 
                               name="main_image" 
                               accept="image/*"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <p class="mt-1 text-sm text-gray-500">Formats acceptés : JPG, PNG, WEBP (max 2MB)</p>
                        @error('main_image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Galerie d'images -->
                    <div class="mb-6">
                        <label for="gallery_images" class="block text-sm font-medium text-gray-700 mb-2">
                            Galerie d'images
                        </label>
                        @if($product->gallery_images && count($product->gallery_images) > 0)
                            <div class="grid grid-cols-4 gap-4 mb-4">
                                @foreach($product->gallery_images as $index => $image)
                                    <div class="relative gallery-image-{{ $index }}">
                                        <img src="{{ asset('storage/' . $image) }}" 
                                             alt="Image galerie {{ $index + 1 }}" 
                                             class="w-24 h-24 object-cover rounded-lg border">
                                        <button type="button" 
                                                x-on:click="removeGalleryImage('{{ $image }}')"
                                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600">
                                            ×
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        <input type="file" 
                               id="gallery_images" 
                               name="gallery_images[]" 
                               multiple
                               accept="image/*"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <p class="mt-1 text-sm text-gray-500">Sélectionnez plusieurs images (max 10 images, 2MB chacune)</p>
                        @error('gallery_images')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Images supplémentaires -->
                    <div>
                        <label for="images" class="block text-sm font-medium text-gray-700 mb-2">
                            Images supplémentaires
                        </label>
                        @if($product->images && count($product->images) > 0)
                            <div class="grid grid-cols-4 gap-4 mb-4">
                                @foreach($product->images as $index => $image)
                                    <div class="relative additional-image-{{ $index }}">
                                        <img src="{{ asset('storage/' . $image) }}" 
                                             alt="Image supplémentaire {{ $index + 1 }}" 
                                             class="w-24 h-24 object-cover rounded-lg border">
                                        <button type="button" 
                                                x-on:click="removeImage('{{ $image }}')"
                                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600">
                                            ×
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        <input type="file" 
                               id="images" 
                               name="images[]" 
                               multiple
                               accept="image/*"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <p class="mt-1 text-sm text-gray-500">Images pour documentation technique ou détails (max 5 images)</p>
                        @error('images')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Informations générales -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informations générales</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom du produit *</label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $product->name) }}"
                                   x-model="formData.name"
                                   required
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="sku" class="block text-sm font-medium text-gray-700 mb-1">SKU *</label>
                            <input type="text" 
                                   id="sku" 
                                   name="sku" 
                                   value="{{ old('sku', $product->sku) }}"
                                   x-model="formData.sku"
                                   required
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 @error('sku') border-red-500 @enderror">
                            @error('sku')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Catégorie de vente -->
                        <div x-show="formData.type === 'sale' || formData.type === 'both'">
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Catégorie (vente)
                                <span x-show="formData.type === 'sale'">*</span>
                            </label>
                            <select id="category_id" 
                                    name="category_id" 
                                    x-model="formData.category_id"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 @error('category_id') border-red-500 @enderror">
                                <option value="">Sélectionner une catégorie de vente</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Catégorie de location -->
                        <div x-show="formData.type === 'rental' || formData.type === 'both'">
                            <label for="rental_category_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Catégorie (location)
                                <span x-show="formData.type === 'rental'">*</span>
                            </label>
                            <select id="rental_category_id" 
                                    name="rental_category_id" 
                                    x-model="formData.rental_category_id"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 @error('rental_category_id') border-red-500 @enderror">
                                <option value="">Sélectionner une catégorie de location</option>
                                @foreach($rentalCategories as $category)
                                    <option value="{{ $category->id }}" {{ old('rental_category_id', $product->rental_category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('rental_category_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="short_description" class="block text-sm font-medium text-gray-700 mb-1">Description courte</label>
                            <input type="text" 
                                   id="short_description" 
                                   name="short_description" 
                                   value="{{ old('short_description', $product->short_description) }}"
                                   x-model="formData.short_description"
                                   placeholder="Résumé en une ligne du produit"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 @error('short_description') border-red-500 @enderror">
                            @error('short_description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description complète *</label>
                            <textarea id="description" 
                                      name="description" 
                                      x-model="formData.description"
                                      rows="4" 
                                      required
                                      placeholder="Description détaillée du produit"
                                      class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 @error('description') border-red-500 @enderror">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Prix et Stock -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Prix et inventaire</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div x-show="formData.type === 'sale' || formData.type === 'both'">
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Prix de vente (€) *</label>
                            <input type="number" 
                                   id="price" 
                                   name="price" 
                                   value="{{ old('price', $product->price) }}"
                                   x-model="formData.price"
                                   step="0.01" 
                                   min="0" 
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 @error('price') border-red-500 @enderror">
                            @error('price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div x-show="formData.type === 'sale' || formData.type === 'both'">
                            <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantité en stock (vente) *</label>
                            <input type="number" 
                                   id="quantity" 
                                   name="quantity" 
                                   value="{{ old('quantity', $product->quantity) }}"
                                   x-model="formData.quantity"
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
                                   value="{{ old('rental_stock', $product->rental_stock) }}"
                                   x-model="formData.rental_stock"
                                   min="0" 
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 @error('rental_stock') border-red-500 @enderror">
                            @error('rental_stock')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Nombre d'unités disponibles pour la location</p>
                        </div>

                        <div>
                            <label for="unit_symbol" class="block text-sm font-medium text-gray-700 mb-1">Unité *</label>
                            <select id="unit_symbol" 
                                    name="unit_symbol" 
                                    x-model="formData.unit_symbol"
                                    required
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 @error('unit_symbol') border-red-500 @enderror">
                                <option value="">Choisir une unité</option>
                                <option value="kg" {{ old('unit_symbol', $product->unit_symbol) == 'kg' ? 'selected' : '' }}>Kilogramme (kg)</option>
                                <option value="pièce" {{ old('unit_symbol', $product->unit_symbol) == 'pièce' ? 'selected' : '' }}>Pièce</option>
                                <option value="litre" {{ old('unit_symbol', $product->unit_symbol) == 'litre' ? 'selected' : '' }}>Litre</option>
                                <option value="gramme" {{ old('unit_symbol', $product->unit_symbol) == 'gramme' ? 'selected' : '' }}>Gramme</option>
                                <option value="tonne" {{ old('unit_symbol', $product->unit_symbol) == 'tonne' ? 'selected' : '' }}>Tonne</option>
                            </select>
                            @error('unit_symbol')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="weight" class="block text-sm font-medium text-gray-700 mb-1">Poids (kg)</label>
                            <input type="number" 
                                   id="weight" 
                                   name="weight" 
                                   value="{{ old('weight', $product->weight) }}"
                                   x-model="formData.weight"
                                   step="0.001" 
                                   min="0"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 @error('weight') border-red-500 @enderror">
                            @error('weight')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="critical_threshold" class="block text-sm font-medium text-gray-700 mb-1">Seuil critique</label>
                            <input type="number" 
                                   id="critical_threshold" 
                                   name="critical_threshold" 
                                   value="{{ old('critical_threshold', $product->critical_threshold) }}"
                                   x-model="formData.critical_threshold"
                                   min="0"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 @error('critical_threshold') border-red-500 @enderror">
                            @error('critical_threshold')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="low_stock_threshold" class="block text-sm font-medium text-gray-700 mb-1">Seuil stock bas</label>
                            <input type="number" 
                                   id="low_stock_threshold" 
                                   name="low_stock_threshold" 
                                   value="{{ old('low_stock_threshold', $product->low_stock_threshold) }}"
                                   x-model="formData.low_stock_threshold"
                                   min="0"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 @error('low_stock_threshold') border-red-500 @enderror">
                            @error('low_stock_threshold')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- SEO -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Optimisation SEO</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-1">Titre SEO</label>
                            <input type="text" 
                                   id="meta_title" 
                                   name="meta_title" 
                                   value="{{ old('meta_title', $product->meta_title) }}"
                                   x-model="formData.meta_title"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 @error('meta_title') border-red-500 @enderror">
                            @error('meta_title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-1">Description SEO</label>
                            <textarea id="meta_description" 
                                      name="meta_description" 
                                      x-model="formData.meta_description"
                                      rows="3"
                                      class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 @error('meta_description') border-red-500 @enderror">{{ old('meta_description', $product->meta_description) }}</textarea>
                            @error('meta_description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="meta_keywords" class="block text-sm font-medium text-gray-700 mb-1">Mots-clés SEO</label>
                            <input type="text" 
                                   id="meta_keywords" 
                                   name="meta_keywords" 
                                   value="{{ old('meta_keywords', $product->meta_keywords) }}"
                                   x-model="formData.meta_keywords"
                                   placeholder="mot1, mot2, mot3"
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
                
                <!-- Statut -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Statut</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type de produit *</label>
                            <select id="type" 
                                    name="type" 
                                    x-model="formData.type"
                                    @change="toggleRentalFields"
                                    required
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 @error('type') border-red-500 @enderror">
                                <option value="">Choisir un type</option>
                                <option value="sale" {{ old('type', $product->type) == 'sale' ? 'selected' : '' }}>Vente uniquement</option>
                                <option value="rental" {{ old('type', $product->type) == 'rental' ? 'selected' : '' }}>Location uniquement</option>
                                <option value="both" {{ old('type', $product->type) == 'both' ? 'selected' : '' }}>Vente et location</option>
                            </select>
                            @error('type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="is_active" 
                                   name="is_active" 
                                   value="1"
                                   x-model="formData.is_active"
                                   {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                                   class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                            <label for="is_active" class="ml-2 block text-sm text-gray-700">
                                Produit actif
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="is_featured" 
                                   name="is_featured" 
                                   value="1"
                                   x-model="formData.is_featured"
                                   {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}
                                   class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                            <label for="is_featured" class="ml-2 block text-sm text-gray-700">
                                Produit en vedette
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Informations produit -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informations</h3>
                    
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">ID:</span>
                            <span class="font-medium">#{{ $product->id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Slug:</span>
                            <span class="font-medium">{{ $product->slug }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Créé le:</span>
                            <span class="font-medium">{{ $product->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Modifié le:</span>
                            <span class="font-medium">{{ $product->updated_at->format('d/m/Y') }}</span>
                        </div>
                        @if($product->views_count > 0)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Vues:</span>
                            <span class="font-medium">{{ $product->views_count }}</span>
                        </div>
                        @endif
                        @if($product->likes_count > 0)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Likes:</span>
                            <span class="font-medium">{{ $product->likes_count }}</span>
                        </div>
                        @endif
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
            name: '{{ old('name', $product->name) }}',
            sku: '{{ old('sku', $product->sku) }}',
            category_id: '{{ old('category_id', $product->category_id) }}',
            rental_category_id: '{{ old('rental_category_id', $product->rental_category_id) }}',
            description: '{{ old('description', $product->description) }}',
            short_description: '{{ old('short_description', $product->short_description) }}',
            price: '{{ old('price', $product->price) }}',
            quantity: '{{ old('quantity', $product->quantity) }}',
            rental_stock: '{{ old('rental_stock', $product->rental_stock) }}',
            unit_symbol: '{{ old('unit_symbol', $product->unit_symbol) }}',
            weight: '{{ old('weight', $product->weight) }}',
            type: '{{ old('type', $product->type) }}',
            critical_threshold: '{{ old('critical_threshold', $product->critical_threshold) }}',
            low_stock_threshold: '{{ old('low_stock_threshold', $product->low_stock_threshold) }}',
            meta_title: '{{ old('meta_title', $product->meta_title) }}',
            meta_description: '{{ old('meta_description', $product->meta_description) }}',
            meta_keywords: '{{ old('meta_keywords', $product->meta_keywords) }}',
            is_active: {{ old('is_active', $product->is_active) ? 'true' : 'false' }},
            is_featured: {{ old('is_featured', $product->is_featured) ? 'true' : 'false' }}
        },

        imagesToRemove: {
            gallery: [],
            additional: []
        },

        removeGalleryImage(imagePath) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cette image de la galerie ?')) {
                // Masquer l'image visuellement
                event.target.closest('.relative').style.display = 'none';
                
                // Ajouter un champ caché pour indiquer au serveur quelle image supprimer
                const form = document.querySelector('form');
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'remove_gallery_images[]';
                input.value = imagePath;
                form.appendChild(input);
            }
        },

        removeImage(imagePath) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cette image supplémentaire ?')) {
                // Masquer l'image visuellement
                event.target.closest('.relative').style.display = 'none';
                
                // Ajouter un champ caché pour indiquer au serveur quelle image supprimer
                const form = document.querySelector('form');
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'remove_images[]';
                input.value = imagePath;
                form.appendChild(input);
            }
        },

        toggleRentalFields() {
            // Logique pour afficher/masquer les champs de location
            // (peut être étendue si nécessaire)
        }
    }))
})
</script>
@endsection
