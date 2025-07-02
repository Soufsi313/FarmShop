@extends('layouts.public')

@section('title', 'Location ' . $orderLocation->order_number . ' - ' . config('app.name', 'FarmShop'))

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <!-- En-tête -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('order-locations.index') }}">Mes Locations</a>
                            </li>
                            <li class="breadcrumb-item active">{{ $orderLocation->order_number }}</li>
                        </ol>
                    </nav>
                    <h1 class="h2 mb-2">
                        <i class="fas fa-receipt me-2 text-info"></i>
                        Location {{ $orderLocation->order_number }}
                    </h1>
                    <p class="text-muted">
                        Créée le {{ $orderLocation->created_at->format('d/m/Y à H:i') }}
                    </p>
                </div>
                <div>
                    @php
                        $statusClass = [
                            'pending' => 'warning',
                            'confirmed' => 'info', 
                            'active' => 'success',
                            'completed' => 'secondary',
                            'cancelled' => 'danger',
                            'overdue' => 'danger'
                        ][$orderLocation->status] ?? 'secondary';
                    @endphp
                    <span class="badge bg-{{ $statusClass }} fs-5 px-3 py-2">
                        {{ $orderLocation->status_label }}
                    </span>
                </div>
            </div>

            <div class="row">
                <!-- Informations générales -->
                <div class="col-lg-8">
                    <!-- Détails de la location -->
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                Détails de la location
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-muted">Période de location</h6>
                                    <p class="mb-3">
                                        <strong>{{ $orderLocation->rental_start_date->format('d/m/Y') }}</strong>
                                        <i class="fas fa-arrow-right mx-2 text-muted"></i>
                                        <strong>{{ $orderLocation->rental_end_date->format('d/m/Y') }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            ({{ $orderLocation->duration_days }} jour{{ $orderLocation->duration_days > 1 ? 's' : '' }})
                                        </small>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted">Dates importantes</h6>
                                    @if($orderLocation->confirmed_at)
                                        <small class="d-block text-success">
                                            <i class="fas fa-check me-1"></i>
                                            Confirmée le {{ $orderLocation->confirmed_at->format('d/m/Y à H:i') }}
                                        </small>
                                    @endif
                                    @if($orderLocation->picked_up_at)
                                        <small class="d-block text-info">
                                            <i class="fas fa-hand-holding me-1"></i>
                                            Récupérée le {{ $orderLocation->picked_up_at->format('d/m/Y à H:i') }}
                                        </small>
                                    @endif
                                    @if($orderLocation->returned_at)
                                        <small class="d-block text-secondary">
                                            <i class="fas fa-undo me-1"></i>
                                            Retournée le {{ $orderLocation->returned_at->format('d/m/Y à H:i') }}
                                        </small>
                                    @endif
                                </div>
                            </div>
                            
                            @if($orderLocation->pickup_notes || $orderLocation->return_notes || $orderLocation->admin_notes)
                                <hr>
                                <h6 class="text-muted">Notes</h6>
                                @if($orderLocation->pickup_notes)
                                    <div class="alert alert-info py-2">
                                        <strong>Récupération :</strong> {{ $orderLocation->pickup_notes }}
                                    </div>
                                @endif
                                @if($orderLocation->return_notes)
                                    <div class="alert alert-success py-2">
                                        <strong>Retour :</strong> {{ $orderLocation->return_notes }}
                                    </div>
                                @endif
                                @if($orderLocation->admin_notes)
                                    <div class="alert alert-warning py-2">
                                        <strong>Administration :</strong> {{ $orderLocation->admin_notes }}
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>

                    <!-- Articles loués -->
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-boxes me-2"></i>
                                Articles loués ({{ $orderLocation->items->count() }})
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Produit</th>
                                            <th class="text-center">Prix/jour</th>
                                            <th class="text-center">Durée</th>
                                            <th class="text-center">Sous-total</th>
                                            <th class="text-center">Caution</th>
                                            <th class="text-center">Total</th>
                                            @if($orderLocation->status !== 'pending')
                                                <th class="text-center">État</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($orderLocation->items as $item)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if($item->product && $item->product->main_image)
                                                            <img src="{{ asset('storage/' . $item->product->main_image) }}"
                                                                 alt="{{ $item->product_name }}"
                                                                 class="me-3 rounded"
                                                                 style="width: 50px; height: 50px; object-fit: cover;">
                                                        @else
                                                            <div class="me-3 bg-light rounded d-flex align-items-center justify-content-center"
                                                                 style="width: 50px; height: 50px;">
                                                                <i class="fas fa-image text-muted"></i>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <h6 class="mb-1">{{ $item->product_name }}</h6>
                                                            <small class="text-muted">
                                                                {{ $item->rental_start_date->format('d/m') }} - {{ $item->rental_end_date->format('d/m') }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <strong>{{ number_format($item->rental_price_per_day, 2) }}€</strong>
                                                </td>
                                                <td class="text-center">
                                                    {{ $item->duration_days }} jour{{ $item->duration_days > 1 ? 's' : '' }}
                                                </td>
                                                <td class="text-center">
                                                    <strong class="text-success">{{ number_format($item->subtotal, 2) }}€</strong>
                                                </td>
                                                <td class="text-center">
                                                    @if($item->deposit_amount > 0)
                                                        <span class="text-warning">{{ number_format($item->deposit_amount, 2) }}€</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <strong>{{ number_format($item->total_with_deposit, 2) }}€</strong>
                                                    @if($item->total_fees > 0)
                                                        <br>
                                                        <small class="text-danger">
                                                            +{{ number_format($item->total_fees, 2) }}€ frais
                                                        </small>
                                                    @endif
                                                </td>
                                                @if($orderLocation->status !== 'pending')
                                                    <td class="text-center">
                                                        @if($item->condition_at_pickup)
                                                            <small class="d-block">
                                                                <strong>Récup.:</strong> {{ $item->condition_at_pickup_label }}
                                                            </small>
                                                        @endif
                                                        @if($item->condition_at_return)
                                                            <small class="d-block">
                                                                <strong>Retour:</strong> {{ $item->condition_at_return_label }}
                                                            </small>
                                                        @endif
                                                        @if(!$item->condition_at_pickup && !$item->condition_at_return)
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Gestion de la caution -->
                    @if($orderLocation->deposit_amount > 0)
                        <div class="card mt-4">
                            <div class="card-header bg-warning text-dark">
                                <h5 class="mb-0">
                                    <i class="fas fa-shield-alt me-2"></i>
                                    Gestion de la caution
                                </h5>
                            </div>
                            <div class="card-body">
                                <!-- Explication du système de caution -->
                                <div class="alert alert-info">
                                    <h6 class="alert-heading">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Comment fonctionne la caution ?
                                    </h6>
                                    <p class="mb-2">La caution de <strong>{{ number_format($orderLocation->deposit_amount, 2) }}€</strong> garantit le bon état du matériel loué.</p>
                                    <ul class="mb-2">
                                        <li>Elle est bloquée lors de la confirmation de votre commande</li>
                                        <li>Elle vous sera remboursée après inspection du matériel retourné</li>
                                        <li>Des frais peuvent être déduits en cas de retard ou dégâts</li>
                                    </ul>
                                </div>

                                <!-- Détail de la caution par article -->
                                @if($orderLocation->items->where('deposit_amount', '>', 0)->count() > 0)
                                    <h6 class="text-muted mb-3">Détail par article :</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Article</th>
                                                    <th class="text-center">Caution</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($orderLocation->items->where('deposit_amount', '>', 0) as $item)
                                                    <tr>
                                                        <td>{{ $item->product_name }}</td>
                                                        <td class="text-center">
                                                            <span class="badge bg-warning text-dark">
                                                                {{ number_format($item->deposit_amount, 2) }}€
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif

                                <!-- Statut de la caution -->
                                <div class="mt-3">
                                    @php
                                        $totalPenalties = ($orderLocation->late_fee ?? 0) + ($orderLocation->damage_fee ?? 0);
                                        $depositRefund = $orderLocation->deposit_amount - $totalPenalties;
                                    @endphp

                                    @if($orderLocation->status === 'completed' && $orderLocation->deposit_refunded_at)
                                        <!-- Caution remboursée -->
                                        <div class="alert alert-success">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-1">
                                                        <i class="fas fa-check-circle me-2"></i>
                                                        Caution remboursée
                                                    </h6>
                                                    <small>Remboursée le {{ $orderLocation->deposit_refunded_at->format('d/m/Y à H:i') }}</small>
                                                </div>
                                                <span class="badge bg-success fs-6">
                                                    {{ number_format($orderLocation->deposit_refund_amount ?? $depositRefund, 2) }}€
                                                </span>
                                            </div>
                                            @if($orderLocation->refund_notes)
                                                <hr class="my-2">
                                                <small><strong>Note :</strong> {{ $orderLocation->refund_notes }}</small>
                                            @endif
                                        </div>

                                        @if($totalPenalties > 0)
                                            <div class="alert alert-warning">
                                                <h6 class="mb-2">
                                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                                    Déductions appliquées
                                                </h6>
                                                @if($orderLocation->late_fee > 0)
                                                    <div class="d-flex justify-content-between">
                                                        <span>Frais de retard :</span>
                                                        <span class="text-danger">-{{ number_format($orderLocation->late_fee, 2) }}€</span>
                                                    </div>
                                                @endif
                                                @if($orderLocation->damage_fee > 0)
                                                    <div class="d-flex justify-content-between">
                                                        <span>Frais de dégâts :</span>
                                                        <span class="text-danger">-{{ number_format($orderLocation->damage_fee, 2) }}€</span>
                                                    </div>
                                                @endif
                                                <hr class="my-2">
                                                <div class="d-flex justify-content-between">
                                                    <strong>Montant remboursé :</strong>
                                                    <strong class="text-success">{{ number_format($depositRefund, 2) }}€</strong>
                                                </div>
                                            </div>
                                        @endif

                                    @elseif($orderLocation->status === 'pending_inspection' || $orderLocation->status === 'completed')
                                        <!-- En attente de traitement -->
                                        <div class="alert alert-warning">
                                            <h6 class="mb-2">
                                                <i class="fas fa-hourglass-half me-2"></i>
                                                Traitement en cours
                                            </h6>
                                            <p class="mb-2">Votre caution est en cours de traitement suite à l'inspection du matériel.</p>
                                            
                                            @if($totalPenalties > 0)
                                                <div class="bg-light p-3 rounded">
                                                    <h6 class="text-muted mb-2">Aperçu des déductions potentielles :</h6>
                                                    @if($orderLocation->late_fee > 0)
                                                        <div class="d-flex justify-content-between">
                                                            <span>Frais de retard :</span>
                                                            <span class="text-danger">{{ number_format($orderLocation->late_fee, 2) }}€</span>
                                                        </div>
                                                    @endif
                                                    @if($orderLocation->damage_fee > 0)
                                                        <div class="d-flex justify-content-between">
                                                            <span>Frais de dégâts :</span>
                                                            <span class="text-danger">{{ number_format($orderLocation->damage_fee, 2) }}€</span>
                                                        </div>
                                                    @endif
                                                    <hr class="my-2">
                                                    <div class="d-flex justify-content-between">
                                                        <strong>Remboursement estimé :</strong>
                                                        <strong class="text-{{ $depositRefund > 0 ? 'success' : 'danger' }}">
                                                            {{ number_format(max(0, $depositRefund), 2) }}€
                                                        </strong>
                                                    </div>
                                                    @if($depositRefund <= 0)
                                                        <small class="text-muted">
                                                            Les frais dépassent le montant de la caution.
                                                        </small>
                                                    @endif
                                                </div>
                                            @else
                                                <div class="bg-light p-3 rounded">
                                                    <div class="d-flex justify-content-between">
                                                        <strong>Remboursement prévu :</strong>
                                                        <strong class="text-success">{{ number_format($orderLocation->deposit_amount, 2) }}€</strong>
                                                    </div>
                                                    <small class="text-muted">Aucune déduction détectée à ce stade</small>
                                                </div>
                                            @endif

                                            <div class="mt-2">
                                                <small class="text-muted">
                                                    <i class="fas fa-clock me-1"></i>
                                                    Le remboursement sera effectué dans les 2-3 jours ouvrés après validation.
                                                </small>
                                            </div>
                                        </div>

                                    @elseif($orderLocation->status === 'active' && $orderLocation->is_overdue)
                                        <!-- Location en retard -->
                                        <div class="alert alert-danger">
                                            <h6 class="mb-2">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                Location en retard
                                            </h6>
                                            <p class="mb-2">Votre location est en retard de <strong>{{ $orderLocation->days_late }} jour(s)</strong>.</p>
                                            
                                            @if($orderLocation->late_fee > 0)
                                                <div class="bg-light p-3 rounded">
                                                    <div class="d-flex justify-content-between">
                                                        <span>Frais de retard actuels :</span>
                                                        <span class="text-danger">{{ number_format($orderLocation->late_fee, 2) }}€</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <span>Caution restante :</span>
                                                        <span class="text-{{ ($orderLocation->deposit_amount - $orderLocation->late_fee) > 0 ? 'warning' : 'danger' }}">
                                                            {{ number_format(max(0, $orderLocation->deposit_amount - $orderLocation->late_fee), 2) }}€
                                                        </span>
                                                    </div>
                                                </div>
                                                <small class="text-muted mt-2 d-block">
                                                    Les frais de retard augmentent chaque jour. Clôturez votre location dès que possible.
                                                </small>
                                            @endif
                                        </div>

                                    @else
                                        <!-- Statut normal -->
                                        <div class="alert alert-light">
                                            <h6 class="mb-2">
                                                <i class="fas fa-shield-alt me-2"></i>
                                                Caution sécurisée
                                            </h6>
                                            <p class="mb-0">
                                                Votre caution de <strong>{{ number_format($orderLocation->deposit_amount, 2) }}€</strong> 
                                                est sécurisée et vous sera remboursée après le retour du matériel en bon état.
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Résumé financier -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-calculator me-2"></i>
                                Résumé financier
                            </h5>
                        </div>
                        <div class="card-body">
                            <!-- Location -->
                            <h6 class="text-muted border-bottom pb-2 mb-3">Coûts de location</h6>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Sous-total location :</span>
                                <strong>{{ number_format($orderLocation->items->sum('subtotal'), 2) }}€</strong>
                            </div>
                            
                            <!-- Caution -->
                            @if($orderLocation->deposit_amount > 0)
                                <h6 class="text-muted border-bottom pb-2 mb-3 mt-3">Caution (remboursable)</h6>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Caution bloquée :</span>
                                    <strong class="text-warning">{{ number_format($orderLocation->deposit_amount, 2) }}€</strong>
                                </div>
                                
                                @php
                                    $totalPenalties = ($orderLocation->late_fee ?? 0) + ($orderLocation->damage_fee ?? 0);
                                    $depositRefundEstimate = $orderLocation->deposit_amount - $totalPenalties;
                                @endphp
                                
                                @if($totalPenalties > 0)
                                    <div class="ms-3">
                                        @if($orderLocation->late_fee > 0)
                                            <div class="d-flex justify-content-between mb-1">
                                                <small class="text-danger">- Frais de retard :</small>
                                                <small class="text-danger">{{ number_format($orderLocation->late_fee, 2) }}€</small>
                                            </div>
                                        @endif
                                        @if($orderLocation->damage_fee > 0)
                                            <div class="d-flex justify-content-between mb-1">
                                                <small class="text-danger">- Frais de dégâts :</small>
                                                <small class="text-danger">{{ number_format($orderLocation->damage_fee, 2) }}€</small>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="d-flex justify-content-between mb-2 border-top pt-2">
                                        <span class="fw-bold">Remboursement {{ $orderLocation->deposit_refunded_at ? 'effectué' : 'estimé' }} :</span>
                                        <strong class="text-{{ $depositRefundEstimate > 0 ? 'success' : 'danger' }}">
                                            {{ number_format(max(0, $orderLocation->deposit_refund_amount ?? $depositRefundEstimate), 2) }}€
                                        </strong>
                                    </div>
                                    @if($depositRefundEstimate <= 0 && !$orderLocation->deposit_refunded_at)
                                        <small class="text-muted d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>
                                            Les frais dépassent la caution
                                        </small>
                                    @endif
                                @else
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="fw-bold">Remboursement {{ $orderLocation->deposit_refunded_at ? 'effectué' : 'prévu' }} :</span>
                                        <strong class="text-success">
                                            {{ number_format($orderLocation->deposit_refund_amount ?? $orderLocation->deposit_amount, 2) }}€
                                        </strong>
                                    </div>
                                @endif
                            @endif
                            
                            <!-- Pénalités additionnelles -->
                            @if($orderLocation->late_fee > 0 || $orderLocation->damage_fee > 0)
                                <h6 class="text-muted border-bottom pb-2 mb-3 mt-3">Frais additionnels</h6>
                                @if($orderLocation->late_fee > 0)
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Frais de retard :</span>
                                        <strong class="text-danger">{{ number_format($orderLocation->late_fee, 2) }}€</strong>
                                    </div>
                                @endif
                                @if($orderLocation->damage_fee > 0)
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Frais de dégâts :</span>
                                        <strong class="text-danger">{{ number_format($orderLocation->damage_fee, 2) }}€</strong>
                                    </div>
                                @endif
                            @endif
                            
                            <!-- Total -->
                            <hr class="my-3">
                            <div class="d-flex justify-content-between mb-3">
                                <span><strong>Total facturé :</strong></span>
                                <strong class="text-primary h5">{{ number_format($orderLocation->total_amount, 2) }}€</strong>
                            </div>
                            
                            @if($orderLocation->paid_amount > 0)
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Montant payé :</span>
                                    <strong class="text-success">{{ number_format($orderLocation->paid_amount, 2) }}€</strong>
                                </div>
                                @if($orderLocation->remaining_amount > 0)
                                    <div class="d-flex justify-content-between mb-3">
                                        <span><strong>Reste à payer :</strong></span>
                                        <strong class="text-warning">{{ number_format($orderLocation->remaining_amount, 2) }}€</strong>
                                    </div>
                                @endif
                            @endif
                            
                            <!-- Actions -->
                            <div class="d-grid gap-2">
                                @if($orderLocation->can_be_closed_by_client)
                                    <button type="button" class="btn btn-warning btn-lg" onclick="closeLocation({{ $orderLocation->id }})">
                                        <i class="fas fa-check-circle me-2"></i>
                                        Clôturer la location
                                    </button>
                                    <div class="alert alert-warning mt-2">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        <strong>Action requise !</strong><br>
                                        Vous devez clôturer cette location aujourd'hui pour confirmer la fin de votre utilisation.
                                    </div>
                                @endif
                                
                                @if($orderLocation->can_be_cancelled_by_client)
                                    <button type="button" class="btn btn-outline-danger" onclick="cancelOrder({{ $orderLocation->id }})">
                                        <i class="fas fa-times me-1"></i>
                                        Annuler la location
                                    </button>
                                @endif
                                
                                @if($orderLocation->is_ready_for_admin_inspection)
                                    <div class="alert alert-info mt-2">
                                        <i class="fas fa-hourglass-half me-2"></i>
                                        <strong>Location clôturée</strong><br>
                                        En attente d'inspection par notre équipe pour finaliser le retour et traiter le remboursement de la caution.
                                        <div class="mt-2">
                                            <small class="text-muted">
                                                <i class="fas fa-clock me-1"></i>
                                                Inspection et remboursement sous 2-3 jours ouvrés
                                            </small>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Alertes importantes -->
                            @if($orderLocation->status === 'pending')
                                <div class="alert alert-warning mt-3 py-2">
                                    <i class="fas fa-clock me-2"></i>
                                    <small>En attente de confirmation par l'équipe</small>
                                </div>
                            @elseif($orderLocation->status === 'confirmed')
                                <div class="alert alert-info mt-3 py-2">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <small>Récupération possible dès le {{ $orderLocation->rental_start_date->format('d/m/Y') }}</small>
                                </div>
                            @elseif($orderLocation->status === 'active')
                                <div class="alert alert-success mt-3 py-2">
                                    <i class="fas fa-calendar-check me-2"></i>
                                    <small>Retour prévu le {{ $orderLocation->rental_end_date->format('d/m/Y') }}</small>
                                </div>
                            @elseif($orderLocation->is_overdue)
                                <div class="alert alert-danger mt-3 py-2">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <small>Retour en retard ! Veuillez nous contacter.</small>
                                </div>
                            @elseif($orderLocation->status === 'completed')
                                <div class="alert alert-secondary mt-3 py-2">
                                    <i class="fas fa-check-circle me-2"></i>
                                    <small>Location terminée avec succès</small>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Contact -->
                    <div class="card mt-4">
                        <div class="card-header bg-secondary text-white">
                            <h6 class="mb-0">
                                <i class="fas fa-headset me-2"></i>
                                Besoin d'aide ?
                            </h6>
                        </div>
                        <div class="card-body">
                            <p class="small mb-3">
                                Pour toute question concernant votre location, contactez-nous :
                            </p>
                            <div class="d-grid gap-2">
                                <a href="tel:+123456789" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-phone me-1"></i>
                                    Appeler
                                </a>
                                <a href="mailto:contact@farmshop.com" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-envelope me-1"></i>
                                    Email
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
let orderToCancel = {{ $orderLocation->id }};

function closeLocation(orderId) {
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
                            <strong>Qu'est-ce que cela signifie ?</strong><br>
                            Vous confirmez avoir terminé d'utiliser le matériel loué et qu'il est prêt à être récupéré par notre équipe.
                        </div>
                        
                        <div class="alert alert-info">
                            <h6 class="mb-2">
                                <i class="fas fa-clipboard-check me-2"></i>
                                Prochaines étapes :
                            </h6>
                            <ol class="mb-0">
                                <li>Notre équipe récupérera le matériel et effectuera une inspection</li>
                                <li>Votre caution sera remboursée après validation de l'état du matériel</li>
                                <li>En cas de dégâts ou retard, des frais pourront être déduits de la caution</li>
                                <li>Le remboursement sera effectué sous 2-3 jours ouvrés</li>
                            </ol>
                        </div>
                        
                        <div class="mb-3">
                            <label for="closeNotes" class="form-label">Notes sur l'état du matériel (optionnel)</label>
                            <textarea class="form-control" id="closeNotes" rows="3" placeholder="Signalez tout problème observé, dégât constaté, ou information utile pour l'inspection..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="button" class="btn btn-warning" onclick="confirmCloseLocation(${orderId})">
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

function confirmCloseLocation(orderId) {
    const notes = document.getElementById('closeNotes').value;
    
    // Créer un formulaire pour la soumission
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/commandes-location/${orderId}/cloturer`;
    
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
    const modal = new bootstrap.Modal(document.getElementById('cancelModal'));
    modal.show();
}

function confirmCancel() {
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
