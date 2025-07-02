@extends('admin.layout')

@section('title', 'Créer une offre spéciale')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-plus text-primary me-2"></i>
                Créer une offre spéciale
            </h1>
            <p class="text-muted mb-0">Configurez une nouvelle offre par quantité</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.special-offers.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Retour à la liste
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Formulaire principal -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-cogs me-2"></i>
                        Configuration de l'offre
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.special-offers.store') }}">
                        @csrf
                        
                        <!-- Informations générales -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="name" class="form-label">
                                    <strong>Nom de l'offre *</strong>
                                </label>
                                <input type="text" 
                                       name="name" 
                                       id="name" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name') }}"
                                       placeholder="Ex: Super Offre Quantité"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="product_id" class="form-label">
                                    <strong>Produit concerné *</strong>
                                </label>
                                <select name="product_id" 
                                        id="product_id" 
                                        class="form-select @error('product_id') is-invalid @enderror" 
                                        required>
                                    <option value="">Sélectionner un produit</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" 
                                                {{ old('product_id') == $product->id ? 'selected' : '' }}
                                                data-price="{{ $product->price }}">
                                            {{ $product->name }} ({{ number_format($product->price, 2) }}€)
                                        </option>
                                    @endforeach
                                </select>
                                @error('product_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label">
                                <strong>Description</strong>
                            </label>
                            <textarea name="description" 
                                      id="description" 
                                      class="form-control @error('description') is-invalid @enderror" 
                                      rows="3"
                                      placeholder="Description de l'offre (optionnel)">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Configuration de la remise -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="min_quantity" class="form-label">
                                    <strong>Quantité minimale *</strong>
                                </label>
                                <input type="number" 
                                       name="min_quantity" 
                                       id="min_quantity" 
                                       class="form-control @error('min_quantity') is-invalid @enderror" 
                                       value="{{ old('min_quantity', 10) }}"
                                       min="1"
                                       placeholder="Ex: 50"
                                       required>
                                @error('min_quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    Nombre d'articles minimum pour bénéficier de l'offre
                                </small>
                            </div>
                            <div class="col-md-6">
                                <label for="discount_percentage" class="form-label">
                                    <strong>Pourcentage de remise * (%)</strong>
                                </label>
                                <input type="number" 
                                       name="discount_percentage" 
                                       id="discount_percentage" 
                                       class="form-control @error('discount_percentage') is-invalid @enderror" 
                                       value="{{ old('discount_percentage', 10) }}"
                                       min="0.01"
                                       max="99.99"
                                       step="0.01"
                                       placeholder="Ex: 75.00"
                                       required>
                                @error('discount_percentage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    Pourcentage de réduction à appliquer
                                </small>
                            </div>
                        </div>

                        <!-- Période de validité -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="start_date" class="form-label">
                                    <strong>Date de début *</strong>
                                </label>
                                <input type="datetime-local" 
                                       name="start_date" 
                                       id="start_date" 
                                       class="form-control @error('start_date') is-invalid @enderror" 
                                       value="{{ old('start_date', now()->format('Y-m-d\TH:i')) }}"
                                       required>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="end_date" class="form-label">
                                    <strong>Date de fin *</strong>
                                </label>
                                <input type="datetime-local" 
                                       name="end_date" 
                                       id="end_date" 
                                       class="form-control @error('end_date') is-invalid @enderror" 
                                       value="{{ old('end_date', now()->addWeek()->format('Y-m-d\TH:i')) }}"
                                       required>
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Options -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input type="checkbox" 
                                       name="is_active" 
                                       id="is_active" 
                                       class="form-check-input" 
                                       value="1"
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label for="is_active" class="form-check-label">
                                    <strong>Activer l'offre immédiatement</strong>
                                </label>
                            </div>
                            <small class="form-text text-muted">
                                Si désactivé, l'offre sera créée mais pas visible pour les clients
                            </small>
                        </div>

                        <!-- Boutons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.special-offers.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Créer l'offre
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Aperçu et aide -->
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-calculator me-2"></i>
                        Aperçu de l'offre
                    </h5>
                </div>
                <div class="card-body">
                    <div id="offer-preview" class="text-center">
                        <p class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Sélectionnez un produit et configurez l'offre pour voir l'aperçu
                        </p>
                    </div>
                </div>
            </div>

            <!-- Conseils -->
            <div class="card mt-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-lightbulb text-warning me-2"></i>
                        Conseils pour une offre efficace
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <small>Choisissez une quantité minimale adaptée au produit</small>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <small>Une remise entre 10% et 30% est généralement attractive</small>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <small>Limitez la durée pour créer un sentiment d'urgence</small>
                        </li>
                        <li class="mb-0">
                            <i class="fas fa-check text-success me-2"></i>
                            <small>Testez avec des produits populaires</small>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Calculateur en temps réel
    function updatePreview() {
        const productSelect = document.getElementById('product_id');
        const quantityInput = document.getElementById('min_quantity');
        const discountInput = document.getElementById('discount_percentage');
        const previewDiv = document.getElementById('offer-preview');

        if (!productSelect.value || !quantityInput.value || !discountInput.value) {
            previewDiv.innerHTML = `
                <p class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    Configurez tous les champs pour voir l'aperçu
                </p>
            `;
            return;
        }

        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const productName = selectedOption.text.split(' (')[0];
        const unitPrice = parseFloat(selectedOption.dataset.price) || 0;
        const quantity = parseInt(quantityInput.value) || 0;
        const discount = parseFloat(discountInput.value) || 0;

        if (unitPrice === 0) return;

        const originalTotal = quantity * unitPrice;
        const discountAmount = originalTotal * (discount / 100);
        const finalTotal = originalTotal - discountAmount;

        previewDiv.innerHTML = `
            <div class="alert alert-info mb-0">
                <h6 class="mb-2">Exemple pour ${quantity} ${productName}:</h6>
                <div class="row text-center">
                    <div class="col-12 mb-2">
                        <small class="text-muted">Prix original</small><br>
                        <strong>${originalTotal.toFixed(2)}€</strong>
                    </div>
                    <div class="col-12 mb-2">
                        <small class="text-muted">Remise (${discount}%)</small><br>
                        <strong class="text-danger">-${discountAmount.toFixed(2)}€</strong>
                    </div>
                    <div class="col-12">
                        <small class="text-muted">Prix final</small><br>
                        <strong class="text-success fs-5">${finalTotal.toFixed(2)}€</strong>
                    </div>
                </div>
            </div>
        `;
    }

    // Écouter les changements
    document.getElementById('product_id').addEventListener('change', updatePreview);
    document.getElementById('min_quantity').addEventListener('input', updatePreview);
    document.getElementById('discount_percentage').addEventListener('input', updatePreview);

    // Validation des dates
    document.getElementById('start_date').addEventListener('change', function() {
        const startDate = new Date(this.value);
        const endDateInput = document.getElementById('end_date');
        const endDate = new Date(endDateInput.value);

        if (endDate <= startDate) {
            const newEndDate = new Date(startDate);
            newEndDate.setDate(newEndDate.getDate() + 7);
            endDateInput.value = newEndDate.toISOString().slice(0, 16);
        }
    });
});
</script>
@endsection
