@extends('admin.layout')

@section('title', 'Inspection et retour - Location #' . $orderLocation->order_number)

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-undo text-warning me-2"></i>
                Inspection et retour - Location #{{ $orderLocation->order_number }}
            </h1>
            <p class="text-muted mb-0">Vérifiez l'état de chaque produit retourné par le client</p>
            @if($isOverdue)
                <div class="alert alert-warning mt-2">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Retour en retard !</strong> Cette location devait se terminer le {{ $orderLocation->rental_end_date->format('d/m/Y') }}.
                    @if($daysLate > 0)
                        Retard de {{ $daysLate }} jour(s).
                    @endif
                </div>
            @endif
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.locations.show', $orderLocation) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Retour au détail
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Formulaire d'inspection -->
        <div class="col-md-8">
            <form method="POST" action="{{ route('admin.locations.return', $orderLocation) }}">
                @csrf
                
                <!-- Informations de la location -->
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Informations de la location
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Client :</strong> {{ $orderLocation->user->name }}</p>
                                <p><strong>Email :</strong> {{ $orderLocation->user->email }}</p>
                                <p><strong>Période :</strong> 
                                    Du {{ $orderLocation->rental_start_date->format('d/m/Y') }} 
                                    au {{ $orderLocation->rental_end_date->format('d/m/Y') }}
                                    ({{ $orderLocation->rental_start_date->diffInDays($orderLocation->rental_end_date) + 1 }} jour(s))
                                </p>
                                @if($orderLocation->picked_up_at)
                                    <p><strong>Récupéré le :</strong> {{ $orderLocation->picked_up_at->format('d/m/Y à H:i') }}</p>
                                @endif
                                @if($orderLocation->client_return_date)
                                    <p><strong>Clôturé par le client le :</strong> {{ $orderLocation->client_return_date->format('d/m/Y à H:i') }}</p>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <p><strong>Total location :</strong> {{ number_format($orderLocation->total_amount, 2) }} €</p>
                                <p><strong>Caution totale :</strong> {{ number_format($orderLocation->deposit_amount, 2) }} €</p>
                                <p><strong>Statut :</strong> 
                                    <span class="badge bg-primary">{{ ucfirst(str_replace('_', ' ', $orderLocation->status)) }}</span>
                                </p>
                                @if($isOverdue && $daysLate > 0)
                                    <p><strong>Frais de retard estimés :</strong> 
                                        <span class="text-danger">{{ number_format($daysLate * 10, 2) }} €</span>
                                        <small class="text-muted">({{ $daysLate }} jour(s) × 10€)</small>
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Inspection des produits -->
                <div class="card mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">
                            <i class="fas fa-search me-2"></i>
                            @if($orderLocation->items->count() > 0)
                                Inspection des produits retournés ({{ $orderLocation->items->count() }})
                            @else
                                Finalisation de la location (aucun article)
                            @endif
                        </h5>
                        <small>
                            @if($orderLocation->items->count() > 0)
                                Vérifiez attentivement l'état de chaque produit retourné
                            @else
                                Cette location ne contient pas d'articles à inspecter
                            @endif
                        </small>
                    </div>
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger mb-4">
                                <h6><i class="fas fa-exclamation-triangle me-1"></i> Erreurs de validation :</h6>
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if($orderLocation->items->count() > 0)
                            <!-- Section d'inspection des articles -->
                            @foreach($orderLocation->items as $index => $item)
                            <div class="border rounded p-3 mb-3 @if($loop->last) mb-0 @endif">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="d-flex align-items-center">
                                            @if($item->product && $item->product->image)
                                                <img src="{{ asset('storage/' . $item->product->image) }}" 
                                                     alt="{{ $item->product_name }}" 
                                                     class="me-3" width="80" height="80" 
                                                     style="object-fit: cover; border-radius: 8px;">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center me-3" 
                                                     style="width: 80px; height: 80px; border-radius: 8px;">
                                                    <i class="fas fa-box text-muted fa-2x"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-1">{{ $item->product_name }}</h6>
                                                <small class="text-muted">
                                                    {{ $item->duration_days }} jour(s) - 
                                                    {{ number_format($item->rental_price_per_day, 2) }}€/jour
                                                </small>
                                                <br>
                                                <small class="text-info">
                                                    Caution: {{ number_format($item->deposit_amount, 2) }}€
                                                </small>
                                                @if($item->condition_at_pickup)
                                                    <br>
                                                    <small class="text-success">
                                                        État à la récupération: {{ ucfirst($item->condition_at_pickup) }}
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <!-- État du produit au retour -->
                                                <label for="items[{{ $index }}][condition]" class="form-label">
                                                    <strong>État du produit au retour :</strong>
                                                </label>
                                                <select name="items[{{ $index }}][condition]" 
                                                        id="items[{{ $index }}][condition]" 
                                                        class="form-select @error('items.'.$index.'.condition') is-invalid @enderror" 
                                                        required>
                                                    <option value="">-- Sélectionner l'état --</option>
                                                    <option value="excellent" {{ old('items.'.$index.'.condition') == 'excellent' ? 'selected' : '' }}>
                                                        ✅ Excellent - Comme neuf
                                                    </option>
                                                    <option value="good" {{ old('items.'.$index.'.condition') == 'good' ? 'selected' : '' }}>
                                                        ✔️ Bon - Légères traces d'usure
                                                    </option>
                                                    <option value="fair" {{ old('items.'.$index.'.condition') == 'fair' ? 'selected' : '' }}>
                                                        ⚠️ Correct - Quelques défauts visibles
                                                    </option>
                                                    <option value="poor" {{ old('items.'.$index.'.condition') == 'poor' ? 'selected' : '' }}>
                                                        ❌ Mauvais - Défauts importants
                                                    </option>
                                                </select>
                                                @error('items.'.$index.'.condition')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <!-- Frais de dégâts -->
                                                <label for="items[{{ $index }}][damage_fee]" class="form-label">
                                                    <strong>Frais de dégâts (€) :</strong>
                                                </label>
                                                <input type="number" 
                                                       name="items[{{ $index }}][damage_fee]" 
                                                       id="items[{{ $index }}][damage_fee]" 
                                                       class="form-control @error('items.'.$index.'.damage_fee') is-invalid @enderror" 
                                                       step="0.01" 
                                                       min="0" 
                                                       max="9999.99"
                                                       value="{{ old('items.'.$index.'.damage_fee', 0) }}"
                                                       placeholder="0.00">
                                                @error('items.'.$index.'.damage_fee')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        <!-- Notes sur l'état du produit -->
                                        <div class="mt-3">
                                            <label for="items[{{ $index }}][notes]" class="form-label">
                                                <strong>Notes sur l'état du produit :</strong>
                                            </label>
                                            <textarea name="items[{{ $index }}][notes]" 
                                                      id="items[{{ $index }}][notes]" 
                                                      class="form-control @error('items.'.$index.'.notes') is-invalid @enderror" 
                                                      rows="2" 
                                                      placeholder="Décrivez tout dégât ou problème observé...">{{ old('items.'.$index.'.notes') }}</textarea>
                                            @error('items.'.$index.'.notes')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <!-- ID de l'item (caché) -->
                                        <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <!-- Section pour les commandes sans articles -->
                            <div class="alert alert-info">
                                <h6><i class="fas fa-info-circle me-2"></i>Aucun article à inspecter</h6>
                                <p class="mb-0">Cette location ne contient pas d'articles physiques à inspecter. Vous pouvez toujours appliquer des frais de dégâts si nécessaire (les frais de retard sont gérés séparément).</p>
                            </div>
                            
                            <!-- Frais de dégâts pour les commandes sans articles -->
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="general_damage_fee" class="form-label">
                                        <strong>Frais de dégâts (€) :</strong>
                                    </label>
                                    <input type="number" 
                                           name="general_damage_fee" 
                                           id="general_damage_fee" 
                                           class="form-control @error('general_damage_fee') is-invalid @enderror" 
                                           step="0.01" 
                                           min="0" 
                                           max="9999.99"
                                           value="{{ old('general_damage_fee', 0) }}"
                                           placeholder="0.00">
                                    <small class="form-text text-muted">
                                        Frais de dégâts ou autres pénalités (hors frais de retard)
                                    </small>
                                    @error('general_damage_fee')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Frais de retard -->
                @if($isOverdue)
                <div class="card mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">
                            <i class="fas fa-clock me-2"></i>
                            Frais de retard
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Date de fin prévue :</strong> {{ $orderLocation->rental_end_date->format('d/m/Y') }}</p>
                                <p><strong>Date de retour actuelle :</strong> {{ now()->format('d/m/Y') }}</p>
                                @if($daysLate > 0)
                                    <p><strong>Retard :</strong> <span class="text-danger">{{ $daysLate }} jour(s)</span></p>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label for="late_fee" class="form-label">
                                    <strong>Frais de retard (€) :</strong>
                                </label>
                                <input type="number" 
                                       name="late_fee" 
                                       id="late_fee" 
                                       class="form-control @error('late_fee') is-invalid @enderror" 
                                       step="0.01" 
                                       min="0" 
                                       max="9999.99"
                                       value="{{ old('late_fee', $daysLate * 10) }}"
                                       placeholder="0.00">
                                @error('late_fee')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Tarif standard : 10€ par jour de retard</small>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Gestion de la caution et des pénalités -->
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-euro-sign me-2"></i>
                            Gestion de la caution et remboursement
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-primary">Caution initiale</h6>
                                <p class="h5 text-success">{{ number_format($orderLocation->deposit_amount, 2) }} €</p>
                                
                                <h6 class="text-warning mt-3">Pénalités calculées</h6>
                                <div id="penalty-calculation">
                                    <div class="d-flex justify-content-between">
                                        <span>Frais de retard :</span>
                                        <span id="late-fee-display">
                                            @if($isOverdue)
                                                {{ number_format($daysLate * 10, 2) }} €
                                            @else
                                                0.00 €
                                            @endif
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Frais de dégâts :</span>
                                        <span id="damage-fee-display">0.00 €</span>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between font-weight-bold">
                                        <span>Total pénalités :</span>
                                        <span id="total-penalties-display" class="text-danger">0.00 €</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-success">Remboursement de caution</h6>
                                <div class="alert alert-info">
                                    <div class="d-flex justify-content-between">
                                        <span>Caution :</span>
                                        <span>{{ number_format($orderLocation->deposit_amount, 2) }} €</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>- Pénalités :</span>
                                        <span id="penalty-deduction">0.00 €</span>
                                    </div>
                                    <hr class="my-2">
                                    <div class="d-flex justify-content-between font-weight-bold">
                                        <span>Montant à rembourser :</span>
                                        <span id="refund-amount-display" class="text-success h5">{{ number_format($orderLocation->deposit_amount, 2) }} €</span>
                                    </div>
                                </div>
                                
                                <div class="alert alert-warning">
                                    <i class="fas fa-info-circle me-1"></i>
                                    <small>Le remboursement sera traité automatiquement après validation de l'inspection.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notes générales -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-sticky-note me-2"></i>
                            Notes générales de retour
                        </h5>
                    </div>
                    <div class="card-body">
                        <textarea name="return_notes" 
                                  class="form-control @error('return_notes') is-invalid @enderror" 
                                  rows="4" 
                                  placeholder="Notes générales sur le retour, observations particulières, etc.">{{ old('return_notes') }}</textarea>
                        @error('return_notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">Validation du retour</h6>
                                <small class="text-muted">
                                    En validant, vous confirmez que le matériel a été retourné et que la location est terminée.
                                </small>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-warning btn-lg me-2">
                                    <i class="fas fa-check me-2"></i>
                                    Valider le retour
                                </button>
                                <a href="{{ route('admin.locations.show', $orderLocation) }}" class="btn btn-outline-secondary">
                                    Annuler
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Sidebar avec informations -->
        <div class="col-md-4">
            <!-- Checklist -->
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-clipboard-check me-2"></i>
                        Checklist de retour
                    </h5>
                </div>
                <div class="card-body">
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="check1">
                        <label class="form-check-label" for="check1">
                            Vérifier la complétude du matériel
                        </label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="check2">
                        <label class="form-check-label" for="check2">
                            Inspecter l'état de chaque produit
                        </label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="check3">
                        <label class="form-check-label" for="check3">
                            Documenter les éventuels dégâts
                        </label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="check4">
                        <label class="form-check-label" for="check4">
                            Calculer les frais de retard si applicable
                        </label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="check5">
                        <label class="form-check-label" for="check5">
                            Prendre des photos si nécessaire
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="check6">
                        <label class="form-check-label" for="check6">
                            Gérer le remboursement de caution
                        </label>
                    </div>
                </div>
            </div>

            <!-- Calcul de la caution -->
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-calculator me-2"></i>
                        Calcul de remboursement
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Caution initiale :</span>
                        <span>{{ number_format($orderLocation->deposit_amount, 2) }}€</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Frais de dégâts :</span>
                        <span id="total-damage-fee">0.00€</span>
                    </div>
                    @if($isOverdue)
                    <div class="d-flex justify-content-between mb-2">
                        <span>Frais de retard :</span>
                        <span id="display-late-fee">{{ number_format($daysLate * 10, 2) }}€</span>
                    </div>
                    @endif
                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>À rembourser :</strong>
                        <strong id="refund-amount">{{ number_format($orderLocation->deposit_amount, 2) }}€</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.border {
    border-color: #e3e6f0 !important;
}

.form-check-input:checked {
    background-color: #ffc107;
    border-color: #ffc107;
}

.badge {
    font-size: 0.8em;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Calculer automatiquement les frais et remboursements
    function updateCalculations() {
        let totalDamageFee = 0;
        
        // Calculer le total des frais de dégâts pour les articles
        document.querySelectorAll('input[name*="[damage_fee]"]').forEach(input => {
            const value = parseFloat(input.value) || 0;
            totalDamageFee += value;
        });
        
        // Ajouter les frais généraux pour les commandes sans articles
        const generalDamageFee = parseFloat(document.getElementById('general_damage_fee')?.value || 0);
        totalDamageFee += generalDamageFee;
        
        // Frais de retard
        const lateFee = parseFloat(document.getElementById('late_fee')?.value || 0);
        
        // Caution initiale
        const initialDeposit = {{ $orderLocation->deposit_amount }};
        
        // Total des pénalités
        const totalPenalties = totalDamageFee + lateFee;
        
        // Calcul du remboursement
        const refundAmount = Math.max(0, initialDeposit - totalPenalties);
        
        // Mise à jour de l'affichage
        if (document.getElementById('damage-fee-display')) {
            document.getElementById('damage-fee-display').textContent = totalDamageFee.toFixed(2) + ' €';
        }
        if (document.getElementById('late-fee-display')) {
            document.getElementById('late-fee-display').textContent = lateFee.toFixed(2) + ' €';
        }
        if (document.getElementById('total-penalties-display')) {
            document.getElementById('total-penalties-display').textContent = totalPenalties.toFixed(2) + ' €';
        }
        if (document.getElementById('penalty-deduction')) {
            document.getElementById('penalty-deduction').textContent = totalPenalties.toFixed(2) + ' €';
        }
        if (document.getElementById('refund-amount-display')) {
            document.getElementById('refund-amount-display').textContent = refundAmount.toFixed(2) + ' €';
            
            // Changer la couleur selon le montant
            if (refundAmount < initialDeposit) {
                document.getElementById('refund-amount-display').className = 'text-warning h5';
            } else {
                document.getElementById('refund-amount-display').className = 'text-success h5';
            }
        }
        
        // Mise à jour des anciens éléments si ils existent encore
        if (document.getElementById('total-damage-fee')) {
            document.getElementById('total-damage-fee').textContent = totalDamageFee.toFixed(2) + '€';
        }
        if (document.getElementById('display-late-fee')) {
            document.getElementById('display-late-fee').textContent = lateFee.toFixed(2) + '€';
        }
        if (document.getElementById('refund-amount')) {
            document.getElementById('refund-amount').textContent = refundAmount.toFixed(2) + '€';
        }
    }
    
    // Écouter les changements dans les champs de frais
    document.querySelectorAll('input[name*="[damage_fee]"]').forEach(input => {
        input.addEventListener('input', updateCalculations);
    });
    
    // Écouter le champ de frais généraux pour les commandes sans articles
    if (document.getElementById('general_damage_fee')) {
        document.getElementById('general_damage_fee').addEventListener('input', updateCalculations);
    }
    
    if (document.getElementById('late_fee')) {
        document.getElementById('late_fee').addEventListener('input', updateCalculations);
    }
    
    // Calcul initial
    updateCalculations();
    
    // Checklist pour activer le bouton
    const checkboxes = document.querySelectorAll('.form-check-input[id^="check"]');
    let checkedCount = 0;
    
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                checkedCount++;
            } else {
                checkedCount--;
            }
            
            const submitBtn = document.querySelector('button[type="submit"]');
            if (checkedCount >= 4) {
                submitBtn.classList.remove('btn-outline-warning');
                submitBtn.classList.add('btn-warning');
            } else {
                submitBtn.classList.remove('btn-warning');
                submitBtn.classList.add('btn-outline-warning');
            }
        });
    });
});
</script>
@endsection
