@extends('admin.layout')

@section('title', 'Tableau de bord - Locations')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-2">
                        <i class="fas fa-calendar-alt me-2 text-info"></i>
                        Tableau de bord des Locations
                    </h1>
                    <p class="text-muted">Vue d'ensemble de l'activité de location</p>
                </div>
                <div>
                    <a href="{{ route('admin.locations.index') }}" class="btn btn-primary">
                        <i class="fas fa-list me-2"></i>Toutes les locations
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-title">Total Locations</h6>
                            <h3 class="mb-0">{{ $stats['total_orders'] }}</h3>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-clipboard-list fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-title">En attente</h6>
                            <h3 class="mb-0">{{ $stats['pending_orders'] }}</h3>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-title">Actives</h6>
                            <h3 class="mb-0">{{ $stats['active_orders'] }}</h3>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-play-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-title">En retard</h6>
                            <h3 class="mb-0">{{ $stats['overdue_orders'] }}</h3>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-title">Inspections</h6>
                            <h3 class="mb-0">{{ $stats['pending_inspection_orders'] }}</h3>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-search fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-title">Récup. auj.</h6>
                            <h3 class="mb-0">{{ $stats['today_pickups'] }}</h3>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-hand-holding fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-secondary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-title">Retours auj.</h6>
                            <h3 class="mb-0">{{ $stats['today_returns'] }}</h3>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-undo fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Activités du jour -->
        <div class="col-lg-6">
            <!-- Récupérations du jour -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-hand-holding me-2"></i>
                        Récupérations du jour ({{ $todayPickups->count() }})
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($todayPickups->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Commande</th>
                                        <th>Client</th>
                                        <th>Articles</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($todayPickups as $order)
                                        <tr>
                                            <td>
                                                <a href="{{ route('admin.locations.show', $order) }}" class="text-decoration-none">
                                                    {{ $order->order_number }}
                                                </a>
                                            </td>
                                            <td>{{ $order->user->name }}</td>
                                            <td>
                                                <small>{{ $order->items->count() }} article(s)</small>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-success" onclick="markAsPickedUp({{ $order->id }})">
                                                    <i class="fas fa-check me-1"></i>Récupéré
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-hand-holding fa-2x mb-2"></i>
                            <p>Aucune récupération prévue aujourd'hui</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Retours du jour -->
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-undo me-2"></i>
                        Retours du jour ({{ $todayReturns->count() }})
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($todayReturns->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Commande</th>
                                        <th>Client</th>
                                        <th>Articles</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($todayReturns as $order)
                                        <tr>
                                            <td>
                                                <a href="{{ route('admin.locations.show', $order) }}" class="text-decoration-none">
                                                    {{ $order->order_number }}
                                                </a>
                                            </td>
                                            <td>{{ $order->user->name }}</td>
                                            <td>
                                                <small>{{ $order->items->count() }} article(s)</small>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-primary" onclick="markAsReturned({{ $order->id }})">
                                                    <i class="fas fa-check me-1"></i>Retourné
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-undo fa-2x mb-2"></i>
                            <p>Aucun retour prévu aujourd'hui</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Commandes récentes et en retard -->
        <div class="col-lg-6">
            <!-- Inspections en attente -->
            @if($pendingInspections->count() > 0)
                <div class="card mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">
                            <i class="fas fa-search me-2"></i>
                            Inspections en attente ({{ $pendingInspections->count() }})
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Commande</th>
                                        <th>Client</th>
                                        <th>Clôturée le</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingInspections as $order)
                                        <tr>
                                            <td>
                                                <a href="{{ route('admin.locations.show', $order) }}" class="text-decoration-none">
                                                    {{ $order->order_number }}
                                                </a>
                                            </td>
                                            <td>{{ $order->user->name }}</td>
                                            <td>
                                                @if($order->client_return_date)
                                                    <small>{{ $order->client_return_date->format('d/m/Y H:i') }}</small>
                                                @else
                                                    <small class="text-muted">Date non renseignée</small>
                                                @endif
                                                @if($order->client_notes)
                                                    <br><small class="text-muted">{{ Str::limit($order->client_notes, 30) }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.locations.return.show', $order) }}" 
                                                   class="btn btn-warning btn-sm">
                                                    <i class="fas fa-search me-1"></i>
                                                    Inspecter
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Commandes en retard -->
            @if($overdueOrders->count() > 0)
                <div class="card mb-4">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Locations en retard ({{ $overdueOrders->count() }})
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Commande</th>
                                        <th>Client</th>
                                        <th>Retard</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($overdueOrders->take(5) as $order)
                                        <tr>
                                            <td>
                                                <a href="{{ route('admin.locations.show', $order) }}" class="text-decoration-none">
                                                    {{ $order->order_number }}
                                                </a>
                                            </td>
                                            <td>{{ $order->user->name }}</td>
                                            <td>
                                                <span class="text-danger">
                                                    {{ now()->diffInDays($order->rental_end_date) }} jour(s)
                                                </span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-warning" onclick="contactClient({{ $order->id }})">
                                                    <i class="fas fa-phone me-1"></i>Contacter
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Commandes récentes -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i>
                        Commandes récentes
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($recentOrders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Commande</th>
                                        <th>Client</th>
                                        <th>Statut</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders->take(8) as $order)
                                        <tr>
                                            <td>
                                                <a href="{{ route('admin.locations.show', $order) }}" class="text-decoration-none">
                                                    {{ $order->order_number }}
                                                </a>
                                            </td>
                                            <td>{{ $order->user->name }}</td>
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
                                                <small>{{ $order->created_at->format('d/m H:i') }}</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-inbox fa-2x mb-2"></i>
                            <p>Aucune commande récente</p>
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
function markAsPickedUp(orderId) {
    // Rediriger vers la page de détail pour gérer la récupération
    window.location.href = `/admin/locations/${orderId}?action=pickup`;
}

function markAsReturned(orderId) {
    // Rediriger vers la page de détail pour gérer le retour
    window.location.href = `/admin/locations/${orderId}?action=return`;
}

function contactClient(orderId) {
    // Logique pour contacter le client (email, téléphone, etc.)
    alert('Fonctionnalité de contact en cours de développement');
}
</script>
@endsection
