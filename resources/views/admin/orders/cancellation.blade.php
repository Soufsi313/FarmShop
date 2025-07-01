@extends('admin.layout')

@section('title', 'Gestion des Annulations et Retours')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- En-tête -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h2 mb-1">Gestion des Annulations et Retours</h1>
                    <p class="text-muted">Recherchez et gérez les demandes d'annulation et de retour des commandes.</p>
                </div>
            </div>

            <!-- Messages de succès/erreur -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Filtres de recherche -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-search me-2"></i>Rechercher une commande
                    </h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.orders.cancellation') }}">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="search" class="form-label">Numéro de commande ou Client</label>
                                <input type="text" class="form-control" id="search" name="search" 
                                       value="{{ request('search') }}" 
                                       placeholder="Ex: FS202507000001 ou email@client.com">
                            </div>
                            <div class="col-md-2">
                                <label for="status" class="form-label">Statut</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>Tous</option>
                                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmée</option>
                                    <option value="preparation" {{ request('status') === 'preparation' ? 'selected' : '' }}>En préparation</option>
                                    <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Expédiée</option>
                                    <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Livrée</option>
                                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Annulée</option>
                                    <option value="returned" {{ request('status') === 'returned' ? 'selected' : '' }}>Retournée</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="date_from" class="form-label">Du</label>
                                <input type="date" class="form-control" id="date_from" name="date_from" 
                                       value="{{ request('date_from') }}">
                            </div>
                            <div class="col-md-2">
                                <label for="date_to" class="form-label">Au</label>
                                <input type="date" class="form-control" id="date_to" name="date_to" 
                                       value="{{ request('date_to') }}">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fas fa-search me-1"></i>Rechercher
                                </button>
                                <a href="{{ route('admin.orders.cancellation') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tableau des commandes -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list me-2"></i>Commandes trouvées ({{ $orders->total() }})
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Commande</th>
                                        <th>Client</th>
                                        <th>Date</th>
                                        <th>Statut</th>
                                        <th>Montant</th>
                                        <th>Produits</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                        <tr>
                                            <td>
                                                <strong>{{ $order->order_number }}</strong>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $order->user->name }}</strong><br>
                                                    <small class="text-muted">{{ $order->user->email }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    {{ $order->created_at->format('d/m/Y') }}<br>
                                                    <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ 
                                                    $order->status === 'confirmed' ? 'warning' : 
                                                    ($order->status === 'preparation' ? 'info' : 
                                                    ($order->status === 'shipped' ? 'primary' : 
                                                    ($order->status === 'delivered' ? 'success' :
                                                    ($order->status === 'cancelled' ? 'danger' :
                                                    ($order->status === 'returned' ? 'secondary' : 'secondary')))))
                                                }}">
                                                    {{ $statusTranslations[$order->status] ?? ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <strong>{{ number_format($order->total_amount, 2) }}€</strong>
                                            </td>
                                            <td>
                                                <small>
                                                    {{ $order->items->count() }} article(s)<br>
                                                    @php
                                                        $perishableCount = $order->items->filter(function($item) {
                                                            return $item->product ? $item->product->isPerishable() : $item->is_perishable;
                                                        })->count();
                                                        $nonPerishableCount = $order->items->count() - $perishableCount;
                                                    @endphp
                                                    @if($nonPerishableCount > 0)
                                                        <span class="text-success">{{ $nonPerishableCount }} retournable(s)</span>
                                                    @endif
                                                    @if($perishableCount > 0)
                                                        <br><span class="text-warning">{{ $perishableCount }} périssable(s)</span>
                                                    @endif
                                                </small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <!-- Bouton Annulation -->
                                                    @if(in_array($order->status, ['confirmed', 'preparation']))
                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-danger" 
                                                                onclick="showCancelModal({{ $order->id }}, '{{ $order->order_number }}')"
                                                                title="Annuler la commande">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    @endif
                                                    
                                                    <!-- Bouton Retour -->
                                                    @if($order->status === 'delivered')
                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-warning" 
                                                                onclick="showReturnModal({{ $order->id }}, '{{ $order->order_number }}')"
                                                                title="Créer un retour">
                                                            <i class="fas fa-undo"></i>
                                                        </button>
                                                    @endif
                                                    
                                                    <!-- Indicateur pour commandes déjà traitées -->
                                                    @if(in_array($order->status, ['cancelled', 'returned']))
                                                        <span class="badge bg-secondary me-2">
                                                            <i class="fas fa-check"></i> Traité
                                                        </span>
                                                    @endif
                                                    
                                                    <!-- Bouton Voir détails -->
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-info" 
                                                            onclick="showOrderDetails({{ $order->id }})"
                                                            title="Voir les détails">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="card-footer">
                            {{ $orders->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucune commande trouvée</h5>
                            <p class="text-muted">Modifiez vos critères de recherche pour voir plus de résultats.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Annulation -->
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-times text-danger me-2"></i>Annuler la commande
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="cancelForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Attention :</strong> Cette action est irréversible. La commande sera annulée et les produits remis en stock.
                    </div>
                    
                    <div class="mb-3">
                        <label for="cancellation_reason" class="form-label">Raison de l'annulation <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="cancellation_reason" name="cancellation_reason" 
                                  rows="3" required placeholder="Expliquez la raison de l'annulation..."></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="refund_method" class="form-label">Méthode de remboursement <span class="text-danger">*</span></label>
                        <select class="form-select" id="refund_method" name="refund_method" required>
                            <option value="">Choisir...</option>
                            <option value="original">Remboursement sur le moyen de paiement original</option>
                            <option value="store_credit">Avoir magasin</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="admin_notes" class="form-label">Notes administratives</label>
                        <textarea class="form-control" id="admin_notes" name="admin_notes" 
                                  rows="2" placeholder="Notes internes (optionnel)..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times me-1"></i>Confirmer l'annulation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Retour -->
<div class="modal fade" id="returnModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-undo text-warning me-2"></i>Créer un retour
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="returnForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div id="returnEligibilityInfo"></div>
                    <div id="returnItemsSection" style="display: none;">
                        <div class="mb-3">
                            <label class="form-label">Produits à retourner</label>
                            <div id="returnableItems"></div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="return_reason" class="form-label">Raison du retour <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="return_reason" name="return_reason" 
                                      rows="3" required placeholder="Expliquez la raison du retour..."></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="admin_notes_return" class="form-label">Notes administratives</label>
                            <textarea class="form-control" id="admin_notes_return" name="admin_notes" 
                                      rows="2" placeholder="Notes internes (optionnel)..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-warning" id="submitReturnBtn" style="display: none;">
                        <i class="fas fa-undo me-1"></i>Créer le retour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showCancelModal(orderId, orderNumber) {
    const modal = new bootstrap.Modal(document.getElementById('cancelModal'));
    const form = document.getElementById('cancelForm');
    
    form.action = `/admin/orders/${orderId}/cancel`;
    document.querySelector('#cancelModal .modal-title').innerHTML = 
        `<i class="fas fa-times text-danger me-2"></i>Annuler la commande #${orderNumber}`;
    
    modal.show();
}

function showReturnModal(orderId, orderNumber) {
    const modal = new bootstrap.Modal(document.getElementById('returnModal'));
    const form = document.getElementById('returnForm');
    
    form.action = `/admin/orders/${orderId}/return`;
    document.querySelector('#returnModal .modal-title').innerHTML = 
        `<i class="fas fa-undo text-warning me-2"></i>Créer un retour pour #${orderNumber}`;
    
    // Vérifier l'éligibilité au retour
    fetch(`/admin/orders/${orderId}/return-check`)
        .then(response => response.json())
        .then(data => {
            const infoDiv = document.getElementById('returnEligibilityInfo');
            const itemsSection = document.getElementById('returnItemsSection');
            const submitBtn = document.getElementById('submitReturnBtn');
            
            if (data.can_return) {
                infoDiv.innerHTML = `
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>
                        Cette commande est éligible au retour jusqu'au ${data.deadline}.
                        <br>Montant total retournable : <strong>${data.total_returnable_amount}€</strong>
                    </div>
                `;
                
                // Afficher les produits retournables
                let itemsHtml = '';
                data.returnable_items.forEach(item => {
                    itemsHtml += `
                        <div class="form-check border rounded p-2 mb-2">
                            <input class="form-check-input" type="checkbox" name="return_items[${item.id}][item_id]" 
                                   value="${item.id}" id="item_${item.id}" checked>
                            <label class="form-check-label" for="item_${item.id}">
                                <strong>${item.product_name}</strong> - ${item.price}€
                                <br><small class="text-muted">Quantité commandée : ${item.quantity}</small>
                            </label>
                            <input type="hidden" name="return_items[${item.id}][quantity]" value="${item.quantity}">
                        </div>
                    `;
                });
                
                if (data.non_returnable_items.length > 0) {
                    itemsHtml += '<div class="alert alert-warning mt-3"><strong>Produits non retournables :</strong><ul class="mb-0">';
                    data.non_returnable_items.forEach(item => {
                        itemsHtml += `<li>${item.product_name} (${item.reason})</li>`;
                    });
                    itemsHtml += '</ul></div>';
                }
                
                document.getElementById('returnableItems').innerHTML = itemsHtml;
                itemsSection.style.display = 'block';
                submitBtn.style.display = 'inline-block';
            } else {
                infoDiv.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        ${data.reason}
                    </div>
                `;
                itemsSection.style.display = 'none';
                submitBtn.style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            document.getElementById('returnEligibilityInfo').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Erreur lors de la vérification d'éligibilité.
                </div>
            `;
        });
    
    modal.show();
}

function showOrderDetails(orderId) {
    // Ici on pourrait ouvrir une modal avec les détails de la commande
    // Pour l'instant, redirection vers la page de détail
    window.location.href = `/admin/orders/${orderId}`;
}
</script>
@endsection
