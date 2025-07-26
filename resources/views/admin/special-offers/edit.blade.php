@extends('layouts.admin')

@section('title', 'Modifier une Offre Spéciale')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Modifier l'Offre Spéciale</h1>
                    <p class="text-gray-600 mt-2">{{ $specialOffer->name }}</p>
                </div>
                <a href="{{ route('admin.special-offers.index') }}" 
                   class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Retour
                </a>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-lg shadow-sm p-6" x-data="specialOfferForm()">
            <form action="{{ route('admin.special-offers.update', $specialOffer) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nom de l'offre <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $specialOffer->name) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   placeholder="Ex: Offre spéciale tomates bio"
                                   required>
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Description <span class="text-red-500">*</span>
                            </label>
                            <textarea id="description" 
                                      name="description" 
                                      rows="4"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                      placeholder="Décrivez votre offre spéciale..."
                                      required>{{ old('description', $specialOffer->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Product Selection -->
                        <div>
                            <label for="product_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Produit concerné <span class="text-red-500">*</span>
                            </label>
                            <select id="product_id" 
                                    name="product_id" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    required>
                                <option value="">Sélectionnez un produit</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" 
                                            {{ old('product_id', $specialOffer->product_id) == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }} - {{ $product->price }}€
                                    </option>
                                @endforeach
                            </select>
                            @error('product_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Discount Percentage -->
                        <div>
                            <label for="discount_percentage" class="block text-sm font-medium text-gray-700 mb-2">
                                Pourcentage de réduction <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" 
                                       id="discount_percentage" 
                                       name="discount_percentage" 
                                       value="{{ old('discount_percentage', $specialOffer->discount_percentage) }}"
                                       min="1" 
                                       max="100" 
                                       step="1"
                                       class="w-full px-4 py-3 pr-8 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                       placeholder="10"
                                       required>
                                <span class="absolute right-3 top-3 text-gray-500">%</span>
                            </div>
                            @error('discount_percentage')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Minimum Quantity -->
                        <div>
                            <label for="minimum_quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                Quantité minimum <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" 
                                       id="minimum_quantity" 
                                       name="minimum_quantity" 
                                       value="{{ old('minimum_quantity', $specialOffer->minimum_quantity ?? 1) }}"
                                       min="1" 
                                       step="1"
                                       class="w-full px-4 py-3 pr-8 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                       placeholder="1"
                                       required>
                                <span class="absolute right-3 top-3 text-gray-500">kg</span>
                            </div>
                            @error('minimum_quantity')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-sm text-gray-500 mt-1">Quantité minimum pour bénéficier de l'offre</p>
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="is_active" class="block text-sm font-medium text-gray-700 mb-2">
                                Statut <span class="text-red-500">*</span>
                            </label>
                            <select id="is_active" 
                                    name="is_active" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    required>
                                <option value="1" {{ old('is_active', $specialOffer->is_active ? '1' : '0') === '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('is_active', $specialOffer->is_active ? '1' : '0') === '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('is_active')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Usage Limit -->
                        <div>
                            <label for="usage_limit" class="block text-sm font-medium text-gray-700 mb-2">
                                Limite d'utilisation
                            </label>
                            <input type="number" 
                                   id="usage_limit" 
                                   name="usage_limit" 
                                   value="{{ old('usage_limit', $specialOffer->usage_limit) }}"
                                   min="1" 
                                   step="1"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   placeholder="Laissez vide pour illimité">
                            @error('usage_limit')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-sm text-gray-500 mt-1">Nombre maximum d'utilisations (optionnel)</p>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Date Range -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    Date de début <span class="text-red-500">*</span>
                                </label>
                                <input type="date" 
                                       id="start_date" 
                                       name="start_date" 
                                       value="{{ old('start_date', $specialOffer->start_date->format('Y-m-d')) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                       required>
                                @error('start_date')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    Date de fin <span class="text-red-500">*</span>
                                </label>
                                <input type="date" 
                                       id="end_date" 
                                       name="end_date" 
                                       value="{{ old('end_date', $specialOffer->end_date->format('Y-m-d')) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                       required>
                                @error('end_date')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Image Upload -->
                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                                Image de l'offre
                            </label>
                            <div class="space-y-4">
                                <!-- Current Image -->
                                @if($specialOffer->image)
                                    <div class="mb-4">
                                        <p class="text-sm text-gray-600 mb-2">Image actuelle :</p>
                                        <img src="{{ asset('storage/' . $specialOffer->image) }}" 
                                             alt="Image actuelle" 
                                             class="w-full h-48 object-cover rounded-lg border border-gray-300">
                                    </div>
                                @endif

                                <input type="file" 
                                       id="image" 
                                       name="image" 
                                       accept="image/*"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                       @change="previewImage($event)">
                                @error('image')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror

                                <!-- Image Preview -->
                                <div x-show="imagePreview" class="mt-4">
                                    <p class="text-sm text-gray-600 mb-2">Nouvelle image :</p>
                                    <img x-bind:src="imagePreview" 
                                         alt="Aperçu" 
                                         class="w-full h-48 object-cover rounded-lg border border-gray-300">
                                </div>
                            </div>
                        </div>

                        <!-- Offer Details -->
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <h4 class="font-medium text-gray-900 mb-3">Détails de l'offre</h4>
                            <div class="space-y-2 text-sm text-gray-600">
                                <div class="flex justify-between">
                                    <span>Créée le :</span>
                                    <span>{{ $specialOffer->created_at->format('d/m/Y à H:i') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Dernière modification :</span>
                                    <span>{{ $specialOffer->updated_at->format('d/m/Y à H:i') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Statut actuel :</span>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                                {{ $specialOffer->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $specialOffer->status === 'active' ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.special-offers.index') }}" 
                       class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Annuler
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function specialOfferForm() {
    return {
        imagePreview: null,
        
        previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.imagePreview = e.target.result;
                };
                reader.readAsDataURL(file);
            } else {
                this.imagePreview = null;
            }
        }
    };
}
</script>
@endsection
