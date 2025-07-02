@extends('layouts.public')

@section('title', 'Mes Locations - ' . config('app.name', 'FarmShop'))

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <!-- En-tête -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h2 mb-2">
                        <i class="fas fa-calendar-check me-2 text-info"></i>
                        Mes Locations
                    </h1>
                    <p class="text-muted">Gérez vos locations de matériel agricole</p>
                </div>
                <div>
                    <a href="{{ route('cart-location.index') }}" class="btn btn-info">
                        <i class="fas fa-calendar-alt me-2"></i>
                        Panier de location
                    </a>
                </div>
            </div>

            @if($orders->count() > 0)
                <!-- Liste des commandes -->
                <div class="row">
                    @foreach($orders as $order)
                        <div class="col-12 mb-4">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-white border-0 py-3">
                                    <div class="row align-items-center">
                                        <div class="col-md-4">
                                            <h5 class="mb-1">
                                                <i class="fas fa-receipt me-2 text-info"></i>
                                                {{ $order->order_number }}
                                            </h5>
                                            <small class="text-muted">
                                                Créée le {{ $order->created_at->format('d/m/Y à H:i') }}
                                            </small>
                                        </div>
                                        <div class="col-md-3 text-center">
                                            @php
                                                $statusClass = [
                                                    'pending' => 'warning',
                                                    'confirmed' => 'info',
                                                    'active' => 'success',
                                                    'completed' => 'secondary',
                                                    'cancelled' => 'danger',
                                                    'overdue' => 'danger'
                                                ][$order->status] ?? 'secondary';
                                            @endphp
                                            <span class="badge bg-{{ $statusClass }} fs-6">
                                                {{ $order->status_label }}
                                            </span>
                                            @if($order->is_overdue)
                                                <br>
                                                <small class="text-danger mt-1 d-block">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                                    En retard
                                                </small>
                                            @endif
                                        </div>
                                        <div class="col-md-3 text-center">
                                            <div class="small text-muted">Période de location</div>
                                            <strong>
                                                {{ $order->rental_start_date->format('d/m/Y') }}
                                                <i class="fas fa-arrow-right mx-2"></i>
                                                {{ $order->rental_end_date->format('d/m/Y') }}
                                            </strong>
                                            <div class="small text-muted">
                                                ({{ $order->duration_days }} jour{{ $order->duration_days > 1 ? 's' : '' }})
                                            </div>
                                        </div>
                                        <div class="col-md-2 text-end">
                                            <div class="small text-muted">Total</div>
                                            <strong class="h5 text-success mb-0">
                                                {{ number_format($order->total_amount, 2) }}€
                                            </strong>
                                            @if($order->deposit_amount > 0)
                                                <div class="small text-warning">
                                                    +{{ number_format($order->deposit_amount, 2) }}€ caution
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <!-- Aperçu des articles -->
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-boxes me-2 text-muted"></i>
                                                <span>
                                                    {{ $order->items->count() }} article{{ $order->items->count() > 1 ? 's' : '' }} :
                                                </span>
                                                <div class="ms-2">
                                                    @foreach($order->items->take(3) as $item)
                                                        <span class="badge bg-light text-dark me-1">
                                                            {{ $item->product_name }}
                                                        </span>
                                                    @endforeach
                                                    @if($order->items->count() > 3)
                                                        <span class="text-muted">
                                                            +{{ $order->items->count() - 3 }} autre{{ $order->items->count() - 3 > 1 ? 's' : '' }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            @if($order->pickup_notes || $order->return_notes)
                                                <div class="mt-2">
                                                    @if($order->pickup_notes)
                                                        <small class="text-muted d-block">
                                                            <i class="fas fa-hand-holding me-1"></i>
                                                            Notes récupération: {{ $order->pickup_notes }}
                                                        </small>
                                                    @endif
                                                    @if($order->return_notes)
                                                        <small class="text-muted d-block">
                                                            <i class="fas fa-undo me-1"></i>
                                                            Notes retour: {{ $order->return_notes }}
                                                        </small>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div class="col-md-4 text-end">
                                            <!-- Alertes importantes -->
                                            @if($order->needs_client_action)
                                                <div class="alert alert-warning alert-sm mb-2">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                                    <strong>Action requise !</strong><br>
                                                    Vous devez clôturer cette location aujourd'hui.
                                                </div>
                                            @endif
                                            
                                            @if($order->is_ready_for_admin_inspection)
                                                <div class="alert alert-info alert-sm mb-2">
                                                    <i class="fas fa-clock me-1"></i>
                                                    <strong>En attente d'inspection</strong><br>
                                                    L'administrateur va inspecter le matériel.
                                                </div>
                                            @endif
                                            
                                            <!-- Actions -->
                                            <div class="btn-group-vertical d-grid gap-2">
                                                <a href="{{ route('order-locations.show', $order) }}" 
                                                   class="btn btn-outline-primary">
                                                    <i class="fas fa-eye me-1"></i>
                                                    Voir détails
                                                </a>
                                                
                                                @if($order->can_be_closed_by_client)
                                                    <button type="button" 
                                                            class="btn btn-warning"
                                                            onclick="closeLocation({{ $order->id }})">
                                                        <i class="fas fa-check-circle me-1"></i>
                                                        Clôturer la location
                                                    </button>
                                                @endif
                                                
                                                @if($order->can_be_cancelled_by_client)
                                                    <button type="button" 
                                                            class="btn btn-outline-danger"
                                                            onclick="cancelOrder({{ $order->id }})">
                                                        <i class="fas fa-times me-1"></i>
                                                        Annuler
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                            @if($order->status === 'confirmed')
                                                <div class="alert alert-info mt-2 py-2 mb-0">
                                                    <i class="fas fa-info-circle me-1"></i>
                                                    <small>Récupération possible dès le {{ $order->rental_start_date->format('d/m/Y') }}</small>
                                                </div>
                                            @elseif($order->status === 'active')
                                                <div class="alert alert-success mt-2 py-2 mb-0">
                                                    <i class="fas fa-calendar-check me-1"></i>
                                                    <small>Retour prévu le {{ $order->rental_end_date->format('d/m/Y') }}</small>
                                                </div>
                                            @elseif($order->is_overdue)
                                                <div class="alert alert-danger mt-2 py-2 mb-0">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                                    <small>Retour en retard ! Contactez-nous.</small>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($orders->hasPages())
                    <div class="d-flex justify-content-center">
                        {{ $orders->links() }}
                    </div>
                @endif
            @else
                <!-- Aucune commande -->
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-calendar-times fa-4x text-muted"></i>
                    </div>
                    <h3 class="text-muted mb-3">Aucune location trouvée</h3>
                    <p class="text-muted mb-4">
                        Vous n'avez pas encore effectué de location de matériel.
                    </p>
                    <div class="d-flex gap-3 justify-content-center">
                        <a href="{{ route('products.index') }}" class="btn btn-success">
                            <i class="fas fa-shopping-bag me-2"></i>
                            Découvrir nos produits
                        </a>
                        <a href="{{ route('cart-location.index') }}" class="btn btn-outline-info">
                            <i class="fas fa-calendar-alt me-2"></i>
                            Mon panier de location
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal d'annulation -->
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2 text-warning"></i>
                    Annuler la location
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir annuler cette location ?</p>
                <div class="mb-3">
                    <label class="form-label">Raison de l'annulation (optionnel)</label>
                    <textarea class="form-control" id="cancelReason" rows="3" 
                              placeholder="Expliquez pourquoi vous annulez cette location..."></textarea>
                </div>
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle me-2"></i>
                    Cette action est irréversible.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Garder la location
                </button>
                <button type="button" class="btn btn-danger" onclick="confirmCancel()">
                    <i class="fas fa-times me-1"></i>
                    Confirmer l'annulation
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let orderToCancel = null;
let orderToClose = null;

function closeLocation(orderId) {
    orderToClose = orderId;
    
    const modalHtml = `
        <div class="modal fade" id="closeModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-check-circle text-warning me-2"></i>
                            Clôturer la location
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="fas fa-info-circle me-2"></i>
                            Vous confirmez avoir terminé d'utiliser le matériel loué et qu'il est prêt à être récupéré par notre équipe.
                        </div>
                        <div class="mb-3">
                            <label for="closeNotes" class="form-label">Notes (optionnel)</label>
                            <textarea class="form-control" id="closeNotes" rows="3" placeholder="Commentaires sur l'état du matériel, problèmes rencontrés, etc."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="button" class="btn btn-warning" onclick="confirmCloseLocation()">
                            <i class="fas fa-check me-1"></i>
                            Confirmer la clôture
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Supprimer le modal existant s'il y en a un
    const existingModal = document.getElementById('closeModal');
    if (existingModal) {
        existingModal.remove();
    }
    
    // Ajouter le nouveau modal
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    
    // Afficher le modal
    new bootstrap.Modal(document.getElementById('closeModal')).show();
}

function confirmCloseLocation() {
    if (!orderToClose) return;
    
    const notes = document.getElementById('closeNotes').value;
    
    // Créer un formulaire pour la soumission
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/commandes-location/${orderToClose}/cloturer`;
    
    // Token CSRF
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    form.appendChild(csrfInput);
    
    // Notes
    if (notes) {
        const notesInput = document.createElement('input');
        notesInput.type = 'hidden';
        notesInput.name = 'client_notes';
        notesInput.value = notes;
        form.appendChild(notesInput);
    }
    
    // Soumettre le formulaire
    document.body.appendChild(form);
    form.submit();
}

function cancelOrder(orderId) {
    orderToCancel = orderId;
    const modal = new bootstrap.Modal(document.getElementById('cancelModal'));
    modal.show();
}

function confirmCancel() {
    if (!orderToCancel) return;
    
    const reason = document.getElementById('cancelReason').value;
    
    fetch(`/commandes-location/${orderToCancel}/annuler`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            reason: reason
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Location annulée avec succès', 'success');
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showToast(data.message || 'Erreur lors de l\'annulation', 'error');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showToast('Erreur lors de l\'annulation', 'error');
    });
    
    // Fermer le modal
    bootstrap.Modal.getInstance(document.getElementById('cancelModal')).hide();
    orderToCancel = null;
}

// Fonction pour afficher les notifications toast
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
