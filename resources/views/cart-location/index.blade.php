@extends('layouts.public')

@section('title', 'Panier de location - ' . config('app.name', 'FarmShop'))

@section('content')
<div class="container py-5">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 mb-1">
                        <i class="fas fa-calendar-alt text-info me-2"></i>
                        Panier de location
                    </h1>
                    <p class="text-muted mb-0">Gérez vos locations de matériel agricole</p>
                </div>
                <div>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-plus me-1"></i>Continuer mes locations
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if($cartItems && $cartItems->count() > 0)
        <!-- Contenu du panier -->
        <div class="row">
            <div class="col-lg-8">
                <!-- Liste des articles de location -->
                <div class="card shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2"></i>
                            Articles de location ({{ $cartTotal['item_count'] ?? 0 }})
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        @foreach($cartItems as $item)
                            <div class="border-bottom p-4" id="cart-item-{{ $item->id }}">
                                <div class="row align-items-center">
                                    <!-- Image du produit -->
                                    <div class="col-md-2">
                                        @if($item->product->main_image)
                                            <img src="{{ asset('storage/' . $item->product->main_image) }}" 
                                                 alt="{{ $item->product->name }}" 
                                                 class="img-fluid rounded">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 80px;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Informations du produit -->
                                    <div class="col-md-4">
                                        <h6 class="mb-1">{{ $item->product->name }}</h6>
                                        <div class="text-muted small">
                                            <div>
                                                <i class="fas fa-euro-sign me-1"></i>
                                                {{ number_format($item->daily_price, 2) }}€/jour
                                            </div>
                                            @if($item->deposit_amount > 0)
                                                <div class="text-warning">
                                                    <i class="fas fa-shield-alt me-1"></i>
                                                    Caution: {{ number_format($item->deposit_amount, 2) }}€
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Dates de location -->
                                    <div class="col-md-3">
                                        <div class="small">
                                            <div class="mb-1">
                                                <strong>Du:</strong> {{ \Carbon\Carbon::parse($item->start_date)->format('d/m/Y') }}
                                            </div>
                                            <div class="mb-1">
                                                <strong>Au:</strong> {{ \Carbon\Carbon::parse($item->end_date)->format('d/m/Y') }}
                                            </div>
                                            <div class="text-info">
                                                <strong>{{ $item->duration_days }} jour{{ $item->duration_days > 1 ? 's' : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Prix et actions -->
                                    <div class="col-md-3">
                                        <div class="text-end">
                                            <div class="mb-2">
                                                <div class="h6 text-info mb-0">{{ number_format($item->total_price, 2) }}€</div>
                                                <small class="text-muted">{{ $item->duration_days }} × {{ number_format($item->daily_price, 2) }}€</small>
                                            </div>
                                            
                                            <!-- Boutons d'action -->
                                            <div class="btn-group-vertical btn-group-sm" role="group">
                                                <button class="btn btn-outline-primary btn-sm" 
                                                        onclick="editRentalItem({{ $item->id }})"
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#editRentalModal{{ $item->id }}">
                                                    <i class="fas fa-edit me-1"></i>Modifier
                                                </button>
                                                <button class="btn btn-outline-danger btn-sm" 
                                                        onclick="removeRentalItem({{ $item->id }})">
                                                    <i class="fas fa-trash me-1"></i>Supprimer
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal de modification pour chaque article -->
                            <div class="modal fade" id="editRentalModal{{ $item->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">
                                                <i class="fas fa-edit me-2"></i>Modifier la location
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="editRentalForm{{ $item->id }}">
                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label">Date de début</label>
                                                        <input type="date" class="form-control" 
                                                               name="start_date" 
                                                               value="{{ \Carbon\Carbon::parse($item->start_date)->format('Y-m-d') }}" 
                                                               required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Date de fin</label>
                                                        <input type="date" class="form-control" 
                                                               name="end_date" 
                                                               value="{{ \Carbon\Carbon::parse($item->end_date)->format('Y-m-d') }}" 
                                                               required>
                                                    </div>
                                                </div>

                                                <div class="card bg-light">
                                                    <div class="card-body p-3">
                                                        <div class="d-flex justify-content-between mb-2">
                                                            <span>Prix par jour :</span>
                                                            <strong>{{ number_format($item->daily_price, 2) }}€</strong>
                                                        </div>
                                                        <div class="d-flex justify-content-between mb-2">
                                                            <span>Nombre de jours :</span>
                                                            <strong id="editRentalDays{{ $item->id }}">{{ $item->duration_days }}</strong>
                                                        </div>
                                                        <div class="d-flex justify-content-between mb-2">
                                                            <span>Sous-total :</span>
                                                            <strong id="editRentalSubtotal{{ $item->id }}">{{ number_format($item->total_price, 2) }}€</strong>
                                                        </div>
                                                        @if($item->deposit_amount > 0)
                                                        <div class="d-flex justify-content-between mb-2">
                                                            <span>Caution :</span>
                                                            <strong class="text-warning">{{ number_format($item->deposit_amount, 2) }}€</strong>
                                                        </div>
                                                        @endif
                                                        <hr class="my-2">
                                                        <div class="d-flex justify-content-between">
                                                            <span><strong>Total :</strong></span>
                                                            <strong class="text-info" id="editRentalTotal{{ $item->id }}">{{ number_format($item->total_price + $item->deposit_amount, 2) }}€</strong>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                            <button type="button" class="btn btn-primary" onclick="updateRentalItem({{ $item->id }})">
                                                <i class="fas fa-save me-1"></i>Sauvegarder
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Actions sur le panier -->
                <div class="card shadow-sm mt-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">Actions sur le panier</h6>
                                <small class="text-muted">Gérez l'ensemble de votre panier de location</small>
                            </div>
                            <div>
                                <button class="btn btn-outline-danger me-2" onclick="clearRentalCart()">
                                    <i class="fas fa-trash me-1"></i>Vider le panier
                                </button>
                                <button class="btn btn-outline-success" onclick="validateRentalCart()">
                                    <i class="fas fa-check me-1"></i>Valider le panier
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Résumé du panier -->
            <div class="col-lg-4">
                <div class="card shadow-sm position-sticky" style="top: 20px;">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-calculator me-2"></i>
                            Résumé de la commande
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <span>Articles ({{ $cartTotal['item_count'] ?? 0 }}):</span>
                            <strong>{{ number_format($cartTotal['total_price'] ?? 0, 2) }}€</strong>
                        </div>
                        
                        @if(($cartTotal['total_deposit'] ?? 0) > 0)
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-warning">Cautions totales:</span>
                                <strong class="text-warning">{{ number_format($cartTotal['total_deposit'] ?? 0, 2) }}€</strong>
                            </div>
                        @endif
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between mb-4">
                            <span class="h6">Total à payer:</span>
                            <span class="h5 text-info">{{ number_format($cartTotal['total_amount'] ?? 0, 2) }}€</span>
                        </div>
                        
                        @if(($cartTotal['total_deposit'] ?? 0) > 0)
                            <div class="alert alert-info">
                                <small>
                                    <i class="fas fa-info-circle me-1"></i>
                                    Les cautions seront restituées après retour en bon état du matériel.
                                </small>
                            </div>
                        @endif

                        <!-- Bouton de commande -->
                        <button class="btn btn-info btn-lg w-100" onclick="proceedToCheckout()">
                            <i class="fas fa-credit-card me-2"></i>
                            Passer la commande
                        </button>

                        <!-- Informations complémentaires -->
                        <div class="mt-4">
                            <h6 class="mb-2">
                                <i class="fas fa-handshake text-success me-2"></i>
                                Conditions de location
                            </h6>
                            <ul class="list-unstyled small text-muted">
                                <li><i class="fas fa-check text-success me-2"></i>Récupération sur place</li>
                                <li><i class="fas fa-check text-success me-2"></i>Retour en bon état requis</li>
                                <li><i class="fas fa-check text-success me-2"></i>Caution remboursable</li>
                                <li><i class="fas fa-check text-success me-2"></i>Support technique inclus</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Panier vide -->
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-calendar-alt fa-4x text-muted"></i>
                        </div>
                        <h3 class="h4 mb-3">Votre panier de location est vide</h3>
                        <p class="text-muted mb-4">
                            Découvrez notre sélection de matériel agricole à louer et commencez à équiper votre exploitation.
                        </p>
                        <div class="d-flex gap-2 justify-content-center">
                            <a href="{{ route('products.index') }}" class="btn btn-info">
                                <i class="fas fa-calendar-alt me-2"></i>
                                Voir les locations
                            </a>
                            <a href="{{ route('welcome') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-home me-2"></i>
                                Retour à l'accueil
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Navigation en bas de page -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="d-flex justify-content-center gap-3">
                <a href="{{ route('products.index') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left me-2"></i>Continuer mes achats
                </a>
                <a href="{{ route('welcome') }}" class="btn btn-outline-success">
                    <i class="fas fa-home me-2"></i>Accueil
                </a>
                <a href="{{ route('cart.index') }}" class="btn btn-outline-warning">
                    <i class="fas fa-shopping-cart me-2"></i>Panier d'achat
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Fonction pour supprimer un article de location
function removeRentalItem(itemId) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer cet article de votre panier de location ?')) {
        return;
    }
    
    fetch(`/article-location/${itemId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Supprimer visuellement l'élément
            const itemElement = document.getElementById(`cart-item-${itemId}`);
            if (itemElement) {
                itemElement.remove();
            }
            
            showToast('Article supprimé du panier de location', 'success');
            
            // Recharger la page pour mettre à jour les totaux
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showToast(data.message || 'Erreur lors de la suppression', 'error');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showToast('Erreur lors de la suppression: ' + error.message, 'error');
    });
}

// Fonction pour vider le panier de location
function clearRentalCart() {
    if (!confirm('Êtes-vous sûr de vouloir vider complètement votre panier de location ?')) {
        return;
    }
    
    fetch('/panier-location/vider', {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showToast('Panier de location vidé', 'success');
            
            // Recharger la page
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showToast(data.message || 'Erreur lors de la suppression', 'error');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showToast('Erreur lors de la suppression: ' + error.message, 'error');
    });
}

// Fonction pour valider le panier de location
function validateRentalCart() {
    fetch('/panier-location/valider', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showToast('Panier de location validé', 'success');
        } else {
            showToast(data.message || 'Erreur lors de la validation', 'error');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showToast('Erreur lors de la validation: ' + error.message, 'error');
    });
}

// Fonction pour procéder au checkout
function proceedToCheckout() {
    fetch('/panier-location/soumettre', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showToast('Commande soumise avec succès !', 'success');
            
            // Rediriger vers la page de confirmation ou de paiement
            setTimeout(() => {
                if (data.redirect_url) {
                    window.location.href = data.redirect_url;
                } else {
                    window.location.reload();
                }
            }, 1500);
        } else {
            showToast(data.message || 'Erreur lors de la soumission', 'error');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showToast('Erreur lors de la soumission: ' + error.message, 'error');
    });
}

// Fonction pour ouvrir le modal d'édition d'un article de location
function editRentalItem(itemId) {
    // Cette fonction peut être utilisée pour initialiser des données ou des contraintes
    // spécifiques avant l'ouverture du modal
    console.log('Édition de l\'article de location:', itemId);
    
    // Le modal s'ouvre automatiquement grâce à data-bs-toggle="modal"
    // Ici on peut ajouter une logique supplémentaire si nécessaire
}

// Fonction pour modifier un article de location
function updateRentalItem(itemId) {
    const form = document.getElementById(`editRentalForm${itemId}`);
    const formData = new FormData(form);
    
    const startDate = formData.get('start_date');
    const endDate = formData.get('end_date');
    
    if (!startDate || !endDate) {
        showToast('Veuillez sélectionner les dates', 'error');
        return;
    }
    
    fetch(`/article-location/${itemId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            start_date: startDate,
            end_date: endDate
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showToast('Article modifié avec succès', 'success');
            
            // Fermer le modal
            const modal = bootstrap.Modal.getInstance(document.getElementById(`editRentalModal${itemId}`));
            if (modal) {
                modal.hide();
            }
            
            // Recharger la page pour mettre à jour les totaux
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showToast(data.message || 'Erreur lors de la modification', 'error');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showToast('Erreur lors de la modification: ' + error.message, 'error');
    });
}

// Calculer le prix en temps réel lors de la modification
document.addEventListener('DOMContentLoaded', function() {
    @if($cartItems && $cartItems->count() > 0)
        @foreach($cartItems as $item)
            (function(itemId, dailyPrice, depositAmount, minRentalDays, maxRentalDays) {
                const startDateInput = document.querySelector(`#editRentalForm${itemId} input[name="start_date"]`);
                const endDateInput = document.querySelector(`#editRentalForm${itemId} input[name="end_date"]`);
                
                if (startDateInput && endDateInput) {
                    // Date minimum = demain (pas de location le jour même)
                    const today = new Date();
                    const tomorrow = new Date(today);
                    tomorrow.setDate(tomorrow.getDate() + 1);
                    const tomorrowStr = tomorrow.toISOString().split('T')[0];
                    
                    startDateInput.min = tomorrowStr;
                    endDateInput.min = tomorrowStr;
                    
                    function calculateEditRental() {
                        const startDate = new Date(startDateInput.value);
                        const endDate = new Date(endDateInput.value);
                        
                        if (startDate && endDate && endDate >= startDate) {
                            // Calculer le nombre de jours (incluant le jour de début et de fin)
                            const diffTime = Math.abs(endDate - startDate);
                            const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24)) + 1; // +1 pour inclure le jour de début
                            
                            // Vérifier les contraintes de durée
                            if (diffDays < minRentalDays || diffDays > maxRentalDays) {
                                document.getElementById(`editRentalDays${itemId}`).textContent = diffDays;
                                document.getElementById(`editRentalSubtotal${itemId}`).textContent = 'Durée non valide';
                                document.getElementById(`editRentalTotal${itemId}`).textContent = 'Durée non valide';
                                return;
                            }
                            
                            const subtotal = diffDays * dailyPrice;
                            const total = subtotal + depositAmount;
                            
                            document.getElementById(`editRentalDays${itemId}`).textContent = diffDays;
                            document.getElementById(`editRentalSubtotal${itemId}`).textContent = subtotal.toFixed(2) + '€';
                            document.getElementById(`editRentalTotal${itemId}`).textContent = total.toFixed(2) + '€';
                        }
                    }
                    
                    startDateInput.addEventListener('change', function() {
                        // Mettre à jour la date minimum de fin selon la date de début
                        if (this.value) {
                            const start = new Date(this.value);
                            
                            // Date minimum de fin = date de début + (minimum de jours - 1)
                            const minEndDate = new Date(start);
                            minEndDate.setDate(start.getDate() + (minRentalDays - 1));
                            
                            // Date maximum de fin = date de début + (maximum de jours - 1)
                            const maxEndDate = new Date(start);
                            maxEndDate.setDate(start.getDate() + (maxRentalDays - 1));
                            
                            endDateInput.min = minEndDate.toISOString().split('T')[0];
                            endDateInput.max = maxEndDate.toISOString().split('T')[0];
                            
                            // Si la date de fin actuelle ne respecte pas les contraintes, la corriger
                            const currentEndDate = endDateInput.value;
                            if (currentEndDate) {
                                const endDate = new Date(currentEndDate);
                                if (endDate < minEndDate) {
                                    endDateInput.value = minEndDate.toISOString().split('T')[0];
                                } else if (endDate > maxEndDate) {
                                    endDateInput.value = maxEndDate.toISOString().split('T')[0];
                                }
                            }
                        }
                        calculateEditRental();
                    });
                    
                    endDateInput.addEventListener('change', calculateEditRental);
                }
            })({{ $item->id }}, {{ $item->daily_price }}, {{ $item->deposit_amount ?? 0 }}, {{ $item->product->min_rental_days ?? 1 }}, {{ $item->product->max_rental_days ?? 365 }});
        @endforeach
    @endif
});

// Fonction pour afficher les notifications toast (réutilisation de la fonction du produit)
function showToast(message, type = 'success') {
    const toastContainer = document.querySelector('.toast-container') || createToastContainer();
    
    const toastElement = document.createElement('div');
    toastElement.className = `toast align-items-center text-white bg-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} border-0`;
    toastElement.setAttribute('role', 'alert');
    toastElement.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    toastContainer.appendChild(toastElement);
    
    const toast = new bootstrap.Toast(toastElement);
    toast.show();
    
    toastElement.addEventListener('hidden.bs.toast', () => {
        toastElement.remove();
    });
}

function createToastContainer() {
    const container = document.createElement('div');
    container.className = 'toast-container position-fixed top-0 end-0 p-3';
    container.style.zIndex = '9999';
    document.body.appendChild(container);
    return container;
}
</script>
@endsection
