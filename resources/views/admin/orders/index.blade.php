@extends('admin.layout')

@section('title', 'Gestion des Commandes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-shopping-cart me-2"></i>Gestion des Commandes</h2>
    <div>
        <button class="btn btn-outline-success" onclick="exportOrders()">
            <i class="fas fa-download me-2"></i>Exporter
        </button>
    </div>
</div>

<!-- Statistiques rapides -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Nouvelles</h6>
                        <h3>{{ $stats['pending'] ?? 0 }}</h3>
                    </div>
                    <i class="fas fa-clock fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">En traitement</h6>
                        <h3>{{ $stats['processing'] ?? 0 }}</h3>
                    </div>
                    <i class="fas fa-cog fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Livrées</h6>
                        <h3>{{ $stats['delivered'] ?? 0 }}</h3>
                    </div>
                    <i class="fas fa-check fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">CA du mois</h6>
                        <h3>{{ number_format($stats['monthly_revenue'] ?? 0, 0) }}€</h3>
                    </div>
                    <i class="fas fa-euro-sign fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filtres -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <input type="text" class="form-control" name="search" placeholder="N° commande, client..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select class="form-select" name="status">
                    <option value="">Tous les statuts</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>En traitement</option>
                    <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Expédiée</option>
                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Livrée</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulée</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-2">
                <select class="form-select" name="sort">
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Plus récentes</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Plus anciennes</option>
                    <option value="amount_desc" {{ request('sort') == 'amount_desc' ? 'selected' : '' }}>Montant ↓</option>
                    <option value="amount_asc" {{ request('sort') == 'amount_asc' ? 'selected' : '' }}>Montant ↑</option>
                </select>
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Table des commandes -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>N° Commande</th>
                        <th>Client</th>
                        <th>Articles</th>
                        <th>Montant</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td>
                            <strong>#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</strong>
                        </td>
                        <td>
                            <div>
                                <strong>{{ $order->user->name ?? 'Client supprimé' }}</strong>
                                @if($order->user)
                                    <br><small class="text-muted">{{ $order->user->email }}</small>
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $order->items_count ?? $order->items->count() }} article(s)</span>
                            @if($order->items->count() > 0)
                                <br><small class="text-muted">{{ $order->items->first()->product->name ?? 'Produit supprimé' }}{{ $order->items->count() > 1 ? '...' : '' }}</small>
                            @endif
                        </td>
                        <td>
                            <strong>{{ number_format($order->total_amount, 2) }} €</strong>
                            @if($order->discount_amount > 0)
                                <br><small class="text-success">-{{ number_format($order->discount_amount, 2) }} €</small>
                            @endif
                        </td>
                        <td>
                            @php
                                $statusColors = [
                                    'pending' => 'warning',
                                    'processing' => 'info',
                                    'shipped' => 'primary',
                                    'delivered' => 'success',
                                    'cancelled' => 'danger'
                                ];
                                $statusLabels = [
                                    'pending' => 'En attente',
                                    'processing' => 'En traitement',
                                    'shipped' => 'Expédiée',
                                    'delivered' => 'Livrée',
                                    'cancelled' => 'Annulée'
                                ];
                            @endphp
                            <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }}">
                                {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                            </span>
                        </td>
                        <td>
                            {{ $order->created_at->format('d/m/Y') }}
                            <br><small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#viewOrderModal{{ $order->id }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        @foreach(['pending' => 'En attente', 'processing' => 'En traitement', 'shipped' => 'Expédiée', 'delivered' => 'Livrée', 'cancelled' => 'Annulée'] as $status => $label)
                                            @if($status !== $order->status)
                                            <li>
                                                <a class="dropdown-item" href="#" onclick="updateOrderStatus({{ $order->id }}, '{{ $status }}')">
                                                    {{ $label }}
                                                </a>
                                            </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                                <button class="btn btn-sm btn-outline-success" onclick="printInvoice({{ $order->id }})">
                                    <i class="fas fa-print"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Aucune commande trouvée</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $orders->links() }}
    </div>
</div>

<!-- Modals de visualisation des commandes -->
@foreach($orders as $order)
<div class="modal fade" id="viewOrderModal{{ $order->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Commande #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6>Informations client</h6>
                        <p class="mb-1"><strong>{{ $order->user->name ?? 'Client supprimé' }}</strong></p>
                        @if($order->user)
                            <p class="mb-1">{{ $order->user->email }}</p>
                        @endif
                        <p class="mb-0"><small class="text-muted">Commande passée le {{ $order->created_at->format('d/m/Y à H:i') }}</small></p>
                    </div>
                    <div class="col-md-6">
                        <h6>Adresse de livraison</h6>
                        @if($order->shipping_address)
                            @php
                                $address = json_decode($order->shipping_address, true);
                            @endphp
                            @if($address)
                                <p class="mb-0">
                                    {{ $address['street'] ?? '' }}<br>
                                    @if(!empty($address['additional_info']))
                                        {{ $address['additional_info'] }}<br>
                                    @endif
                                    {{ $address['postal_code'] ?? '' }} {{ $address['city'] ?? '' }}<br>
                                    {{ $address['country'] ?? '' }}
                                </p>
                            @else
                                <p class="text-muted mb-0">Format d'adresse invalide</p>
                            @endif
                        @else
                            <p class="text-muted mb-0">Non renseignée</p>
                        @endif
                    </div>
                </div>
                
                <h6>Articles commandés</h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th>Prix unitaire</th>
                                <th>Quantité</th>
                                <th>Sous-total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->product_name ?? 'Produit supprimé' }}</td>
                                <td>{{ number_format($item->unit_price, 2) }} €</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->total_price, 2) }} €</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            @if($order->discount_amount > 0)
                            <tr>
                                <td colspan="3"><strong>Sous-total</strong></td>
                                <td><strong>{{ number_format($order->total_amount + $order->discount_amount, 2) }} €</strong></td>
                            </tr>
                            <tr>
                                <td colspan="3"><strong>Remise</strong></td>
                                <td><strong class="text-success">-{{ number_format($order->discount_amount, 2) }} €</strong></td>
                            </tr>
                            @endif
                            <tr class="table-dark">
                                <td colspan="3"><strong>Total</strong></td>
                                <td><strong>{{ number_format($order->total_amount, 2) }} €</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-success" onclick="printInvoice({{ $order->id }})">
                    <i class="fas fa-print me-2"></i>Imprimer la facture
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach

<script>
function updateOrderStatus(orderId, status) {
    if (confirm('Êtes-vous sûr de vouloir modifier le statut de cette commande ?')) {
        fetch(`/admin/orders/${orderId}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ status: status })
        }).then(() => {
            location.reload();
        });
    }
}

function printInvoice(orderId) {
    window.open(`/admin/orders/${orderId}/invoice`, '_blank');
}

function exportOrders() {
    const params = new URLSearchParams(window.location.search);
    window.location.href = `/admin/orders/export?${params.toString()}`;
}
</script>
@endsection
