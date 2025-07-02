@extends('admin.layout')

@section('title', 'Modifier l\'offre spéciale')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-edit text-primary me-2"></i>
                Modifier l'offre spéciale
            </h1>
            <p class="text-muted mb-0">Modifiez les paramètres de l'offre spéciale</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.special-offers.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Retour à la liste
            </a>
            <a href="{{ route('admin.special-offers.show', $specialOffer) }}" class="btn btn-outline-info">
                <i class="fas fa-eye me-1"></i> Voir
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-form me-2"></i>
                        Formulaire de modification
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.special-offers.update', $specialOffer) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nom de l'offre <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', $specialOffer->name) }}" 
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="product_id" class="form-label">Produit <span class="text-danger">*</span></label>
                                    <select class="form-select @error('product_id') is-invalid @enderror" 
                                            id="product_id" 
                                            name="product_id" 
                                            required>
                                        <option value="">Sélectionner un produit</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" 
                                                    {{ old('product_id', $specialOffer->product_id) == $product->id ? 'selected' : '' }}>
                                                {{ $product->name }} - {{ number_format($product->price, 2) }}€
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('product_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="3">{{ old('description', $specialOffer->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="min_quantity" class="form-label">Quantité minimale <span class="text-danger">*</span></label>
                                    <input type="number" 
                                           class="form-control @error('min_quantity') is-invalid @enderror" 
                                           id="min_quantity" 
                                           name="min_quantity" 
                                           value="{{ old('min_quantity', $specialOffer->min_quantity) }}" 
                                           min="1" 
                                           required>
                                    @error('min_quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="discount_percentage" class="form-label">Remise (%) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control @error('discount_percentage') is-invalid @enderror" 
                                               id="discount_percentage" 
                                               name="discount_percentage" 
                                               value="{{ old('discount_percentage', $specialOffer->discount_percentage) }}" 
                                               min="1" 
                                               max="99" 
                                               step="0.01" 
                                               required>
                                        <span class="input-group-text">%</span>
                                        @error('discount_percentage')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">Date de début <span class="text-danger">*</span></label>
                                    <input type="datetime-local" 
                                           class="form-control @error('start_date') is-invalid @enderror" 
                                           id="start_date" 
                                           name="start_date" 
                                           value="{{ old('start_date', $specialOffer->start_date ? $specialOffer->start_date->format('Y-m-d\TH:i') : '') }}" 
                                           required>
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="end_date" class="form-label">Date de fin <span class="text-danger">*</span></label>
                                    <input type="datetime-local" 
                                           class="form-control @error('end_date') is-invalid @enderror" 
                                           id="end_date" 
                                           name="end_date" 
                                           value="{{ old('end_date', $specialOffer->end_date ? $specialOffer->end_date->format('Y-m-d\TH:i') : '') }}" 
                                           required>
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="is_active" 
                                       name="is_active" 
                                       value="1"
                                       {{ old('is_active', $specialOffer->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Offre active
                                </label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.special-offers.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Aperçu de l'offre actuelle -->
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-eye me-2"></i>
                        Aperçu de l'offre
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Produit :</strong><br>
                        <span class="text-muted">{{ $specialOffer->product->name }}</span>
                    </div>
                    <div class="mb-3">
                        <strong>Prix original :</strong><br>
                        <span class="text-muted">{{ number_format($specialOffer->product->price, 2) }}€</span>
                    </div>
                    <div class="mb-3">
                        <strong>Statut actuel :</strong><br>
                        @if($specialOffer->isActive())
                            <span class="badge bg-success">🟢 Active</span>
                        @elseif($specialOffer->isScheduled())
                            <span class="badge bg-primary">🔵 Programmée</span>
                        @elseif($specialOffer->isExpired())
                            <span class="badge bg-danger">🔴 Expirée</span>
                        @else
                            <span class="badge bg-secondary">⚫ Inactive</span>
                        @endif
                    </div>
                    <div class="mb-3">
                        <strong>Période :</strong><br>
                        <small class="text-muted">
                            Du {{ $specialOffer->start_date ? $specialOffer->start_date->format('d/m/Y H:i') : 'Non définie' }}<br>
                            Au {{ $specialOffer->end_date ? $specialOffer->end_date->format('d/m/Y H:i') : 'Non définie' }}
                        </small>
                    </div>
                </div>
            </div>

            <!-- Aide -->
            <div class="card mt-3">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0">
                        <i class="fas fa-lightbulb me-2"></i>
                        Aide
                    </h6>
                </div>
                <div class="card-body">
                    <small class="text-muted">
                        <strong>💡 Conseils :</strong><br>
                        • La remise s'applique automatiquement quand la quantité minimale est atteinte<br>
                        • Vérifiez qu'il n'y a pas de conflit avec d'autres offres<br>
                        • Une offre inactive n'apparaît pas côté client
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validation des dates
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');
    
    function validateDates() {
        if (startDate.value && endDate.value) {
            if (new Date(startDate.value) >= new Date(endDate.value)) {
                endDate.setCustomValidity('La date de fin doit être postérieure à la date de début');
            } else {
                endDate.setCustomValidity('');
            }
        }
    }
    
    startDate.addEventListener('change', validateDates);
    endDate.addEventListener('change', validateDates);
    
    // Mise à jour de l'aperçu en temps réel
    const quantityInput = document.getElementById('min_quantity');
    const discountInput = document.getElementById('discount_percentage');
    const productSelect = document.getElementById('product_id');
    
    function updatePreview() {
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        if (selectedOption.value) {
            const price = parseFloat(selectedOption.text.split(' - ')[1]?.replace('€', '') || 0);
            const quantity = parseInt(quantityInput.value) || 1;
            const discount = parseFloat(discountInput.value) || 0;
            
            if (price > 0 && discount > 0) {
                const discountedPrice = price * (1 - discount / 100);
                const savings = (price - discountedPrice) * quantity;
                
                console.log('Aperçu calculé:', {
                    price: price,
                    quantity: quantity,
                    discount: discount,
                    discountedPrice: discountedPrice,
                    savings: savings
                });
            }
        }
    }
    
    quantityInput.addEventListener('input', updatePreview);
    discountInput.addEventListener('input', updatePreview);
    productSelect.addEventListener('change', updatePreview);
});
</script>
@endsection
