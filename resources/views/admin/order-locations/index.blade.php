@extends('admin.layout')

@section('title', 'Gestion des Locations')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-2">
                        <i class="fas fa-calendar-alt me-2 text-info"></i>
                        Gestion des Locations
                    </h1>
                    <p class="text-muted">Gérez toutes les commandes de location</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.locations.dashboard') }}" class="btn btn-outline-primary">
                        <i class="fas fa-chart-line me-2"></i>Tableau de bord
                    </a>
                    <a href="{{ route('admin.locations.export') }}" class="btn btn-success">
                        <i class="fas fa-download me-2"></i>Exporter
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et statistiques -->
    <div class="row mb-4">
        <!-- Statistiques rapides -->
        <div class="col-12 mb-3">
            <div class="row">
                <div class="col-auto">
                    <div class="btn-group" role="group">
                        <input type="radio" class="btn-check" name="statusFilter" id="all" value="" {{ request('status') === '' || request('status') === null ? 'checked' : '' }}>
                        <label class="btn btn-outline-primary" for="all">
                            Toutes ({{ $stats['total'] }})
                        </label>

                        <input type="radio" class="btn-check" name="statusFilter" id="pending" value="pending" {{ request('status') === 'pending' ? 'checked' : '' }}>
                        <label class="btn btn-outline-warning" for="pending">
                            En attente ({{ $stats['pending'] }})
                        </label>

                        <input type="radio" class="btn-check" name="statusFilter" id="confirmed" value="confirmed" {{ request('status') === 'confirmed' ? 'checked' : '' }}>
                        <label class="btn btn-outline-info" for="confirmed">
                            Confirmées ({{ $stats['confirmed'] }})
                        </label>

                        <input type="radio" class="btn-check" name="statusFilter" id="active" value="active" {{ request('status') === 'active' ? 'checked' : '' }}>
                        <label class="btn btn-outline-success" for="active">
                            Actives ({{ $stats['active'] }})
                        </label>

                        <input type="radio" class="btn-check" name="statusFilter" id="overdue" value="overdue" {{ request('status') === 'overdue' ? 'checked' : '' }}>
                        <label class="btn btn-outline-danger" for="overdue">
                            En retard ({{ $stats['overdue'] }})
                        </label>

                        <input type="radio" class="btn-check" name="statusFilter" id="completed" value="completed" {{ request('status') === 'completed' ? 'checked' : '' }}>
                        <label class="btn btn-outline-secondary" for="completed">
                            Terminées ({{ $stats['completed'] }})
                        </label>

                        <input type="radio" class="btn-check" name="statusFilter" id="cancelled" value="cancelled" {{ request('status') === 'cancelled' ? 'checked' : '' }}>
                        <label class="btn btn-outline-dark" for="cancelled">
                            Annulées ({{ $stats['cancelled'] }})
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres avancés -->
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" id="filterForm">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Recherche</label>
                                <input type="text" 
                                       class="form-control" 
                                       name="search" 
                                       value="{{ request('search') }}"
                                       placeholder="Numéro, nom client, email...">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Statut</label>
                                <select class="form-select" name="status" id="statusSelect">
                                    <option value="">Tous les statuts</option>
                                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En attente</option>
                                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmée</option>
                                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Terminée</option>
                                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Annulée</option>
                                    <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>En retard</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Date début</label>
                                <input type="date" 
                                       class="form-control" 
                                       name="date_from" 
                                       value="{{ request('date_from') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Date fin</label>
                                <input type="date" 
                                       class="form-control" 
                                       name="date_to" 
                                       value="{{ request('date_to') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search me-1"></i>Filtrer
                                    </button>
                                    <a href="{{ route('admin.locations.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times me-1"></i>Reset
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des commandes -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-0">
                    @if($orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Commande</th>
                                        <th>Client</th>
                                        <th>Période location</th>
                                        <th>Articles</th>
                                        <th>Montant</th>
                                        <th>Statut</th>
                                        <th>Créée le</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                        <tr>
                                            <td>
                                                <div>
                                                    <strong>{{ $order->order_number }}</strong>
                                                    @if($order->is_overdue)
                                                        <br><small class="text-danger">
                                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                                            {{ now()->diffInDays($order->rental_end_date) }} j. de retard
                                                        </small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $order->user->name }}</strong>
                                                    <br><small class="text-muted">{{ $order->user->email }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="small">
                                                    <strong>{{ $order->rental_start_date->format('d/m/Y') }}</strong>
                                                    <i class="fas fa-arrow-right mx-1"></i>
                                                    <strong>{{ $order->rental_end_date->format('d/m/Y') }}</strong>
                                                    <br><span class="text-muted">({{ $order->duration_days }} jour(s))</span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark">
                                                    {{ $order->items->count() }} article(s)
                                                </span>
                                            </td>
                                            <td>
                                                <div class="small">
                                                    <strong class="text-success">{{ number_format($order->total_amount, 2) }}€</strong>
                                                    @if($order->deposit_amount > 0)
                                                        <br><span class="text-warning">+{{ number_format($order->deposit_amount, 2) }}€ caution</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
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
                                                <span class="badge bg-{{ $statusClass }}">
                                                    {{ $order->status_label }}
                                                </span>
                                            </td>
                                            <td>
                                                <small>{{ $order->created_at->format('d/m/Y H:i') }}</small>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('admin.locations.show', $order) }}" 
                                                       class="btn btn-sm btn-outline-primary"
                                                       title="Voir détails">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    
                                                    @if($order->status === 'pending')
                                                        <button class="btn btn-sm btn-outline-success" 
                                                                onclick="confirmOrder({{ $order->id }})"
                                                                title="Confirmer">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    @endif
                                                    
                                                    @if($order->status === 'confirmed')
                                                        <button class="btn btn-sm btn-outline-info" 
                                                                onclick="markAsPickedUp({{ $order->id }})"
                                                                title="Marquer comme récupéré">
                                                            <i class="fas fa-hand-holding"></i>
                                                        </button>
                                                    @endif
                                                    
                                                    @if($order->status === 'active')
                                                        <button class="btn btn-sm btn-outline-secondary" 
                                                                onclick="markAsReturned({{ $order->id }})"
                                                                title="Marquer comme retourné">
                                                            <i class="fas fa-undo"></i>
                                                        </button>
                                                    @endif
                                                    
                                                    @if($order->can_be_cancelled)
                                                        <button class="btn btn-sm btn-outline-danger" 
                                                                onclick="cancelOrder({{ $order->id }})"
                                                                title="Annuler">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        @if($orders->hasPages())
                            <div class="d-flex justify-content-center p-4">
                                {{ $orders->appends(request()->all())->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                            <h4 class="text-muted">Aucune commande trouvée</h4>
                            <p class="text-muted">Aucune commande de location ne correspond à vos critères.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Gestion des filtres rapides par statut
document.querySelectorAll('input[name="statusFilter"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.getElementById('statusSelect').value = this.value;
        document.getElementById('filterForm').submit();
    });
});

// Actions rapides
function confirmOrder(orderId) {
    if (confirm('Confirmer cette commande de location ?')) {
        fetch(`/admin/locations/${orderId}/confirm`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Commande confirmée avec succès', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showAlert(data.message || 'Erreur lors de la confirmation', 'error');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showAlert('Erreur lors de la confirmation', 'error');
        });
    }
}

function markAsPickedUp(orderId) {
    // Rediriger vers la page de détail pour gérer la récupération complète
    window.location.href = `/admin/locations/${orderId}?action=pickup`;
}

function markAsReturned(orderId) {
    // Rediriger vers la page de détail pour gérer le retour complet
    window.location.href = `/admin/locations/${orderId}?action=return`;
}

function cancelOrder(orderId) {
    const reason = prompt('Raison de l\'annulation:');
    if (reason) {
        fetch(`/admin/locations/${orderId}/cancel`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ reason: reason })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Commande annulée avec succès', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showAlert(data.message || 'Erreur lors de l\'annulation', 'error');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showAlert('Erreur lors de l\'annulation', 'error');
        });
    }
}

function showAlert(message, type) {
    // Créer une alerte Bootstrap
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show position-fixed" 
             style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', alertHtml);
    
    // Auto-supprimer après 5 secondes
    setTimeout(() => {
        const alert = document.querySelector('.alert');
        if (alert) {
            alert.remove();
        }
    }, 5000);
}
</script>
@endsection
