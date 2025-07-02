@extends('admin.layout')

@section('title', 'Détail de la location #' . $orderLocation->order_number)

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-calendar-check text-info me-2"></i>
                Détail de la location #{{ $orderLocation->order_number }}
            </h1>
            <p class="text-muted mb-0">{{ $orderLocation->created_at->format('d/m/Y à H:i') }}</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.locations.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Retour à la liste
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Informations principales -->
        <div class="col-md-8">
            <!-- Statut et actions -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Statut et Actions</h5>
                    <span class="badge badge-{{ $orderLocation->status }} badge-lg">
                        {{ ucfirst(str_replace('_', ' ', $orderLocation->status)) }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Statut actuel :</strong> 
                                <span class="badge badge-{{ $orderLocation->status }}">
                                    {{ ucfirst(str_replace('_', ' ', $orderLocation->status)) }}
                                </span>
                            </p>
                            <p><strong>Date de création :</strong> {{ $orderLocation->created_at->format('d/m/Y à H:i') }}</p>
                            <p><strong>Période de location :</strong></p>
                            <ul class="mb-0">
                                <li>Du {{ $orderLocation->rental_start_date->format('d/m/Y') }}</li>
                                <li>Au {{ $orderLocation->rental_end_date->format('d/m/Y') }}</li>
                                <li>Durée : {{ $orderLocation->rental_start_date->diffInDays($orderLocation->rental_end_date) + 1 }} jour(s)</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <!-- Actions selon le statut -->
                            @if($orderLocation->status === 'pending')
                                <form method="POST" action="{{ route('admin.locations.confirm', $orderLocation) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm me-2">
                                        <i class="fas fa-check me-1"></i> Confirmer
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.locations.cancel', $orderLocation) }}" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="reason" value="Annulée par l'administrateur">
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir annuler cette location ?')">
                                        <i class="fas fa-times me-1"></i> Annuler
                                    </button>
                                </form>
                @elseif($orderLocation->status === 'confirmed')
                <a href="{{ route('admin.locations.pickup.show', $orderLocation) }}" class="btn btn-primary btn-sm me-2">
                    <i class="fas fa-hand-holding me-1"></i> Inspecter et activer
                </a>
                            @elseif($orderLocation->status === 'active')
                                <a href="{{ route('admin.locations.return.show', $orderLocation) }}" class="btn btn-warning btn-sm me-2">
                                    <i class="fas fa-undo me-1"></i> Inspecter et marquer comme terminé
                                </a>
                                <form method="POST" action="{{ route('admin.locations.overdue', $orderLocation) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Marquer cette location comme en retard ?')">
                                        <i class="fas fa-exclamation-triangle me-1"></i> Marquer en retard
                                    </button>
                                </form>
                            @elseif($orderLocation->status === 'pending_inspection')
                                <a href="{{ route('admin.locations.return.show', $orderLocation) }}" class="btn btn-success btn-sm me-2">
                                    <i class="fas fa-search me-1"></i> Procéder à l'inspection
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Produits loués -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Produits loués ({{ $orderLocation->items->count() }})</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    <th>Durée</th>
                                    <th>Prix/jour</th>
                                    <th>Sous-total</th>
                                    <th>Caution</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orderLocation->items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->product && $item->product->image)
                                                <img src="{{ asset('storage/' . $item->product->image) }}" 
                                                     alt="{{ $item->product_name }}" 
                                                     class="me-3" width="50" height="50" 
                                                     style="object-fit: cover; border-radius: 5px;">
                                            @endif
                                            <div>
                                                <h6 class="mb-0">{{ $item->product_name }}</h6>
                                                @if($item->product)
                                                    <small class="text-muted">Réf: {{ $item->product->id }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $item->duration_days }} jour(s)</td>
                                    <td>{{ number_format($item->rental_price_per_day, 2) }} €</td>
                                    <td>{{ number_format($item->subtotal, 2) }} €</td>
                                    <td>{{ number_format($item->deposit_amount, 2) }} €</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Historique des statuts -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Historique</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Commande créée</h6>
                                <p class="timeline-text">{{ $orderLocation->created_at->format('d/m/Y à H:i') }}</p>
                            </div>
                        </div>
                        @if($orderLocation->confirmed_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Commande confirmée</h6>
                                <p class="timeline-text">{{ $orderLocation->confirmed_at->format('d/m/Y à H:i') }}</p>
                            </div>
                        </div>
                        @endif
                        @if($orderLocation->picked_up_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Location activée</h6>
                                <p class="timeline-text">{{ $orderLocation->picked_up_at->format('d/m/Y à H:i') }}</p>
                            </div>
                        </div>
                        @endif
                        @if($orderLocation->returned_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-warning"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Location terminée</h6>
                                <p class="timeline-text">{{ $orderLocation->returned_at->format('d/m/Y à H:i') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar avec informations client et totaux -->
        <div class="col-md-4">
            <!-- Informations client -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Client</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ $orderLocation->user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($orderLocation->user->name) . '&color=7F9CF5&background=EBF4FF' }}" 
                             alt="{{ $orderLocation->user->name }}" 
                             class="rounded-circle me-3" width="50" height="50">
                        <div>
                            <h6 class="mb-0">{{ $orderLocation->user->name }}</h6>
                            <small class="text-muted">{{ $orderLocation->user->email }}</small>
                        </div>
                    </div>
                    <p class="mb-1"><strong>ID Client :</strong> #{{ $orderLocation->user->id }}</p>
                    <p class="mb-0"><strong>Membre depuis :</strong> {{ $orderLocation->user->created_at->format('m/Y') }}</p>
                </div>
            </div>

            <!-- Résumé financier -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Résumé financier</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Sous-total location :</span>
                        <span>{{ number_format($orderLocation->total_amount, 2) }} €</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Caution totale :</span>
                        <span class="text-info"><strong>{{ number_format($orderLocation->deposit_amount, 2) }} €</strong></span>
                    </div>
                    @if($orderLocation->late_fee > 0)
                    <div class="d-flex justify-content-between mb-2 text-danger">
                        <span>Frais de retard :</span>
                        <span>{{ number_format($orderLocation->late_fee, 2) }} €</span>
                    </div>
                    @endif
                    @if($orderLocation->damage_fee > 0)
                    <div class="d-flex justify-content-between mb-2 text-danger">
                        <span>Frais de dégâts :</span>
                        <span>{{ number_format($orderLocation->damage_fee, 2) }} €</span>
                    </div>
                    @endif
                    
                    @if($orderLocation->status === 'completed' && ($orderLocation->late_fee > 0 || $orderLocation->damage_fee > 0))
                    <div class="d-flex justify-content-between mb-2 text-danger">
                        <span><strong>Total pénalités :</strong></span>
                        <span><strong>{{ number_format($orderLocation->total_penalties ?? ($orderLocation->late_fee + $orderLocation->damage_fee), 2) }} €</strong></span>
                    </div>
                    @endif
                    
                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>Total location :</strong>
                        <strong>{{ number_format($orderLocation->total_amount + $orderLocation->late_fee + $orderLocation->damage_fee, 2) }} €</strong>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <strong>Montant payé :</strong>
                        <strong class="text-success">{{ number_format($orderLocation->paid_amount, 2) }} €</strong>
                    </div>
                    
                    @if($orderLocation->status === 'completed')
                    <hr>
                    <h6 class="text-success mb-2">
                        <i class="fas fa-undo me-1"></i>
                        Remboursement de caution
                    </h6>
                    <div class="d-flex justify-content-between">
                        <span>Caution initiale :</span>
                        <span>{{ number_format($orderLocation->deposit_amount, 2) }} €</span>
                    </div>
                    @if($orderLocation->total_penalties > 0)
                    <div class="d-flex justify-content-between text-danger">
                        <span>- Pénalités déduites :</span>
                        <span>{{ number_format($orderLocation->total_penalties, 2) }} €</span>
                    </div>
                    @endif
                    <div class="d-flex justify-content-between border-top pt-2 mt-2">
                        <span><strong>Montant remboursé :</strong></span>
                        <span class="text-success"><strong>{{ number_format($orderLocation->deposit_refund_amount ?? 0, 2) }} €</strong></span>
                    </div>
                    @if($orderLocation->deposit_refunded_at)
                    <small class="text-muted">
                        <i class="fas fa-check-circle me-1"></i>
                        Remboursé le {{ $orderLocation->deposit_refunded_at->format('d/m/Y à H:i') }}
                    </small>
                    @endif
                    @elseif(in_array($orderLocation->status, ['returned', 'active', 'pending_inspection']))
                    <hr>
                    <div class="alert alert-info p-2 mb-0">
                        <small>
                            <i class="fas fa-info-circle me-1"></i>
                            <strong>Caution en cours :</strong> {{ number_format($orderLocation->deposit_amount, 2) }} €<br>
                            Le remboursement sera traité après inspection et finalisation.
                        </small>
                    </div>
                    @endif
                    
                    <small class="text-muted mt-2 d-block">Période: {{ $orderLocation->rental_start_date->format('d/m') }} au {{ $orderLocation->rental_end_date->format('d/m/Y') }}</small>
                </div>
            </div>

            <!-- Informations techniques -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Informations techniques</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Numéro :</strong> {{ $orderLocation->order_number }}</p>
                    <p class="mb-2"><strong>ID Commande :</strong> #{{ $orderLocation->id }}</p>
                    <p class="mb-2"><strong>Créée le :</strong> {{ $orderLocation->created_at->format('d/m/Y H:i') }}</p>
                    <p class="mb-0"><strong>Modifiée le :</strong> {{ $orderLocation->updated_at->format('d/m/Y H:i') }}</p>
                    @if($orderLocation->admin_notes)
                    <hr>
                    <p class="mb-0"><strong>Notes admin :</strong></p>
                    <p class="text-muted">{{ $orderLocation->admin_notes }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.badge-lg {
    font-size: 0.9em;
    padding: 0.5em 0.75em;
}

.badge-pending {
    background-color: #ffc107;
    color: #000;
}

.badge-confirmed {
    background-color: #28a745;
    color: #fff;
}

.badge-picked_up {
    background-color: #17a2b8;
    color: #fff;
}

.badge-returned {
    background-color: #6c757d;
    color: #fff;
}

.badge-cancelled {
    background-color: #dc3545;
    color: #fff;
}

.badge-overdue {
    background-color: #fd7e14;
    color: #fff;
}

.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline:before {
    content: '';
    position: absolute;
    top: 0;
    left: 15px;
    height: 100%;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 0;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #e9ecef;
}

.timeline-content {
    padding-left: 20px;
}

.timeline-title {
    margin-bottom: 5px;
    font-size: 14px;
    font-weight: 600;
}

.timeline-text {
    margin: 0;
    color: #6c757d;
    font-size: 13px;
}
</style>
@endsection
