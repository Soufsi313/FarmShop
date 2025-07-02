@extends('admin.layout')

@section('title', 'Détails de l\'offre spéciale')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-eye text-info me-2"></i>
                Détails de l'offre spéciale
            </h1>
            <p class="text-muted mb-0">{{ $specialOffer->name }}</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.special-offers.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Retour à la liste
            </a>
            <a href="{{ route('admin.special-offers.edit', $specialOffer) }}" class="btn btn-outline-primary">
                <i class="fas fa-edit me-1"></i> Modifier
            </a>
            @if($specialOffer->is_active)
                <form action="{{ route('admin.special-offers.deactivate', $specialOffer) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-outline-warning" onclick="return confirm('Désactiver cette offre ?')">
                        <i class="fas fa-pause me-1"></i> Désactiver
                    </button>
                </form>
            @else
                <form action="{{ route('admin.special-offers.activate', $specialOffer) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-outline-success">
                        <i class="fas fa-play me-1"></i> Activer
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="row">
        <!-- Informations principales -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Informations générales
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Nom de l'offre :</strong><br>
                                <span class="text-muted">{{ $specialOffer->name }}</span>
                            </div>
                            
                            <div class="mb-3">
                                <strong>Produit concerné :</strong><br>
                                <div class="d-flex align-items-center">
                                    @if($specialOffer->product->image)
                                        <img src="{{ Storage::url($specialOffer->product->image) }}" 
                                             alt="{{ $specialOffer->product->name }}" 
                                             class="rounded me-2" 
                                             style="width: 40px; height: 40px; object-fit: cover;">
                                    @endif
                                    <div>
                                        <span class="text-muted">{{ $specialOffer->product->name }}</span><br>
                                        <small class="text-success">Prix : {{ number_format($specialOffer->product->price, 2) }}€</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <strong>Description :</strong><br>
                                <span class="text-muted">{{ $specialOffer->description ?: 'Aucune description' }}</span>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Quantité minimale :</strong><br>
                                <span class="badge bg-primary fs-6">{{ $specialOffer->min_quantity }} unité(s)</span>
                            </div>
                            
                            <div class="mb-3">
                                <strong>Remise :</strong><br>
                                <span class="badge bg-success fs-6">{{ $specialOffer->discount_percentage }}%</span>
                            </div>
                            
                            <div class="mb-3">
                                <strong>Statut :</strong><br>
                                @if($specialOffer->isActive())
                                    <span class="badge bg-success fs-6">🟢 Active</span>
                                @elseif($specialOffer->isScheduled())
                                    <span class="badge bg-primary fs-6">🔵 Programmée</span>
                                @elseif($specialOffer->isExpired())
                                    <span class="badge bg-danger fs-6">🔴 Expirée</span>
                                @else
                                    <span class="badge bg-secondary fs-6">⚫ Inactive</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Période de validité -->
            <div class="card mt-3">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar me-2"></i>
                        Période de validité
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Date de début :</strong><br>
                                <i class="fas fa-play text-success me-1"></i>
                                {{ $specialOffer->start_date ? $specialOffer->start_date->format('d/m/Y à H:i') : 'Non définie' }}
                                @if($specialOffer->start_date)
                                    <br><small class="text-muted">{{ $specialOffer->start_date->diffForHumans() }}</small>
                                @endif
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Date de fin :</strong><br>
                                <i class="fas fa-stop text-danger me-1"></i>
                                {{ $specialOffer->end_date ? $specialOffer->end_date->format('d/m/Y à H:i') : 'Non définie' }}
                                @if($specialOffer->end_date)
                                    <br><small class="text-muted">{{ $specialOffer->end_date->diffForHumans() }}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    @if($specialOffer->start_date && $specialOffer->end_date)
                        <div class="progress mt-3">
                            @php
                                $now = now();
                                $start = $specialOffer->start_date;
                                $end = $specialOffer->end_date;
                                $total = $end->diffInSeconds($start);
                                $elapsed = $now->diffInSeconds($start);
                                $percentage = $total > 0 ? min(100, max(0, ($elapsed / $total) * 100)) : 0;
                            @endphp
                            <div class="progress-bar {{ $percentage >= 100 ? 'bg-danger' : ($percentage > 0 ? 'bg-warning' : 'bg-primary') }}" 
                                 role="progressbar" 
                                 style="width: {{ $percentage }}%">
                                {{ round($percentage) }}%
                            </div>
                        </div>
                        <small class="text-muted">Durée totale : {{ $specialOffer->start_date->diffForHumans($specialOffer->end_date, true) }}</small>
                    @endif
                </div>
            </div>

            <!-- Simulation d'économies -->
            <div class="card mt-3">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-calculator me-2"></i>
                        Simulation d'économies
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Quantité</th>
                                    <th>Prix unitaire</th>
                                    <th>Total sans remise</th>
                                    <th>Total avec remise</th>
                                    <th>Économie</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $price = $specialOffer->product->price;
                                    $quantities = [$specialOffer->min_quantity, $specialOffer->min_quantity + 5, $specialOffer->min_quantity + 10];
                                @endphp
                                @foreach($quantities as $qty)
                                    @php
                                        $discountData = $specialOffer->calculateDiscount($qty, $price);
                                        $totalWithoutDiscount = $discountData['original_total'];
                                        $discountAmount = $discountData['discount_amount'];
                                        $totalWithDiscount = $discountData['final_total'];
                                    @endphp
                                    <tr class="{{ $qty == $specialOffer->min_quantity ? 'table-warning' : '' }}">
                                        <td>
                                            <strong>{{ $qty }}</strong>
                                            @if($qty == $specialOffer->min_quantity)
                                                <small class="text-success">(min)</small>
                                            @endif
                                        </td>
                                        <td>{{ number_format($price, 2) }}€</td>
                                        <td>{{ number_format($totalWithoutDiscount, 2) }}€</td>
                                        <td>
                                            @if($discountAmount > 0)
                                                <span class="text-success">{{ number_format($totalWithDiscount, 2) }}€</span>
                                            @else
                                                {{ number_format($totalWithoutDiscount, 2) }}€
                                            @endif
                                        </td>
                                        <td>
                                            @if($discountAmount > 0)
                                                <span class="badge bg-success">-{{ number_format($discountAmount, 2) }}€</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Actions rapides -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-bolt me-2"></i>
                        Actions rapides
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.special-offers.edit', $specialOffer) }}" class="btn btn-outline-primary">
                            <i class="fas fa-edit me-1"></i> Modifier l'offre
                        </a>
                        
                        @if($specialOffer->is_active)
                            <form action="{{ route('admin.special-offers.deactivate', $specialOffer) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-outline-warning w-100" onclick="return confirm('Désactiver cette offre ?')">
                                    <i class="fas fa-pause me-1"></i> Désactiver
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.special-offers.activate', $specialOffer) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-outline-success w-100">
                                    <i class="fas fa-play me-1"></i> Activer
                                </button>
                            </form>
                        @endif
                        
                        <a href="{{ route('products.show', $specialOffer->product) }}" class="btn btn-outline-info" target="_blank">
                            <i class="fas fa-box me-1"></i> Voir le produit (public)
                        </a>
                        
                        <form action="{{ route('admin.special-offers.destroy', $specialOffer) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100" onclick="return confirm('Supprimer définitivement cette offre ?')">
                                <i class="fas fa-trash me-1"></i> Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Historique -->
            <div class="card mt-3">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-history me-2"></i>
                        Historique
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <strong>Créée :</strong><br>
                        <small class="text-muted">{{ $specialOffer->created_at->format('d/m/Y à H:i') }}</small><br>
                        <small class="text-muted">{{ $specialOffer->created_at->diffForHumans() }}</small>
                    </div>
                    
                    @if($specialOffer->updated_at != $specialOffer->created_at)
                        <div class="mb-2">
                            <strong>Dernière modification :</strong><br>
                            <small class="text-muted">{{ $specialOffer->updated_at->format('d/m/Y à H:i') }}</small><br>
                            <small class="text-muted">{{ $specialOffer->updated_at->diffForHumans() }}</small>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Conseils -->
            <div class="card mt-3">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-lightbulb me-2"></i>
                        Conseils
                    </h6>
                </div>
                <div class="card-body">
                    <small class="text-muted">
                        <strong>💡 Optimisation :</strong><br>
                        • Analysez les ventes pour ajuster la quantité minimale<br>
                        • Testez différents pourcentages de remise<br>
                        • Vérifiez la rentabilité de l'offre<br><br>
                        
                        <strong>⚠️ Attention :</strong><br>
                        • Une offre active s'applique automatiquement<br>
                        • Vérifiez qu'il n'y a pas de conflit avec d'autres offres
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
