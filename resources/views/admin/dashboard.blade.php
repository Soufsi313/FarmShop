@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
<!-- Alertes pour les offres qui expirent -->
@if($stats['expiring_offers_count'] > 0)
<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <div class="d-flex align-items-center">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <div>
            <strong>Attention !</strong> 
            {{ $stats['expiring_offers_count'] }} offre(s) spéciale(s) vont expirer dans les 7 prochains jours.
            <a href="{{ route('admin.special-offers.index') }}?status=expiring" class="alert-link">Voir les offres</a>
        </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- Statistiques -->
<div class="row mb-4">
    <div class="col-md-3 mb-4">
        <div class="stat-card">
            <div class="stat-icon users">
                <i class="fas fa-users"></i>
            </div>
            <h3>{{ $stats['users_count'] }}</h3>
            <p class="text-muted mb-0">Utilisateurs</p>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="stat-card">
            <div class="stat-icon products">
                <i class="fas fa-box"></i>
            </div>
            <h3>{{ $stats['products_count'] }}</h3>
            <p class="text-muted mb-0">Produits</p>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="stat-card">
            <div class="stat-icon orders">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <h3>{{ $stats['orders_count'] }}</h3>
            <p class="text-muted mb-0">Commandes</p>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="stat-card">
            <div class="stat-icon special-offers">
                <i class="fas fa-percent"></i>
            </div>
            <h3>{{ $stats['active_special_offers_count'] }}/{{ $stats['special_offers_count'] }}</h3>
            <p class="text-muted mb-0">Offres spéciales</p>
            <small class="text-success">{{ $stats['active_special_offers_count'] }} active(s)</small>
        </div>
    </div>
</div>

<!-- Section Offres Spéciales mise en avant -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card border-warning">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">
                    <i class="fas fa-percent me-2"></i>
                    Gestion des Offres Spéciales
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="me-3">
                                <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="fas fa-percent text-white fa-2x"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="mb-1">{{ $stats['active_special_offers_count'] }} offres actives</h6>
                                <p class="text-muted mb-0">sur {{ $stats['special_offers_count'] }} offres totales</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 text-end">
                        <div class="btn-group" role="group">
                            <a href="{{ route('admin.special-offers.index') }}" class="btn btn-warning">
                                <i class="fas fa-list me-1"></i>
                                Gérer les offres
                            </a>
                            <a href="{{ route('admin.special-offers.create') }}" class="btn btn-outline-warning">
                                <i class="fas fa-plus me-1"></i>
                                Créer une offre
                            </a>
                        </div>
                    </div>
                </div>
                
                @if($stats['recent_special_offers']->count() > 0)
                    <hr>
                    <h6 class="text-muted mb-3">Dernières offres créées :</h6>
                    <div class="row">
                        @foreach($stats['recent_special_offers']->take(3) as $offer)
                            <div class="col-md-4">
                                <div class="card border-left-warning">
                                    <div class="card-body py-2">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h6 class="mb-1">{{ $offer->name }}</h6>
                                                <small class="text-muted">{{ Str::limit($offer->product->name ?? 'Produit supprimé', 20) }}</small>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge bg-warning">{{ $offer->discount_percentage }}%</span>
                                                @if($offer->status === 'active')
                                                    <br><small class="text-success">🟢 Actif</small>
                                                @elseif($offer->status === 'scheduled')
                                                    <br><small class="text-info">🔵 Programmé</small>
                                                @elseif($offer->status === 'expired')
                                                    <br><small class="text-danger">🔴 Expiré</small>
                                                @else
                                                    <br><small class="text-secondary">⚫ Inactif</small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Menu d'actions rapides -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">
                    <i class="fas fa-bolt me-2"></i>
                    Actions rapides
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2 mb-3">
                        <a href="{{ route('admin.locations.index') }}" class="btn btn-outline-warning w-100">
                            <i class="fas fa-handshake mb-2"></i><br>
                            Locations
                        </a>
                    </div>
                    <div class="col-md-2 mb-3">
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-info w-100">
                            <i class="fas fa-box mb-2"></i><br>
                            Produits
                        </a>
                    </div>
                    <div class="col-md-2 mb-3">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-users mb-2"></i><br>
                            Utilisateurs
                        </a>
                    </div>
                    <div class="col-md-2 mb-3">
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-dark w-100">
                            <i class="fas fa-shopping-cart mb-2"></i><br>
                            Commandes
                        </a>
                    </div>
                    <div class="col-md-2 mb-3">
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-tags mb-2"></i><br>
                            Catégories
                        </a>
                    </div>
                    <div class="col-md-2 mb-3">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-success w-100">
                            <i class="fas fa-chart-line mb-2"></i><br>
                            Analytics
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Activité récente -->
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-users me-2"></i>
                    Utilisateurs récents
                </h5>
            </div>
            <div class="card-body">
                @if($stats['recent_users']->count() > 0)
                    @foreach($stats['recent_users'] as $user)
                        <div class="d-flex align-items-center mb-3">
                            @if($user->profile_photo_path)
                                <img src="{{ asset('storage/'.$user->profile_photo_path) }}" alt="{{ $user->name }}" class="rounded-circle me-3" width="40" height="40">
                            @else
                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    <span class="text-white fw-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                </div>
                            @endif
                            <div>
                                <strong>{{ $user->name }}</strong><br>
                                <small class="text-muted">{{ $user->email }}</small><br>
                                <small class="text-success">{{ $user->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">Aucun utilisateur récent</p>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="fas fa-shopping-cart me-2"></i>
                    Commandes récentes
                </h5>
            </div>
            <div class="card-body">
                @if($stats['recent_orders']->count() > 0)
                    @foreach($stats['recent_orders'] as $order)
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <strong>Commande #{{ $order->id }}</strong><br>
                                <small class="text-muted">{{ $order->user->name ?? 'N/A' }}</small><br>
                                <small class="text-success">{{ $order->created_at->diffForHumans() }}</small>
                            </div>
                            <span class="badge bg-primary">{{ $order->status ?? 'En attente' }}</span>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">Aucune commande récente</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">
                    <i class="fas fa-percent me-2"></i>
                    Offres spéciales récentes
                </h5>
            </div>
            <div class="card-body">
                @if($stats['recent_special_offers']->count() > 0)
                    @foreach($stats['recent_special_offers'] as $offer)
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <strong>{{ $offer->name }}</strong><br>
                                <small class="text-muted">{{ $offer->product->name ?? 'Produit supprimé' }}</small><br>
                                <small class="text-info">{{ $offer->discount_percentage }}% de remise</small><br>
                                <small class="text-success">{{ $offer->created_at->diffForHumans() }}</small>
                            </div>
                            <div class="text-end">
                                @if($offer->status === 'active')
                                    <span class="badge bg-success">Actif</span>
                                @elseif($offer->status === 'scheduled')
                                    <span class="badge bg-info">Programmé</span>
                                @elseif($offer->status === 'expired')
                                    <span class="badge bg-danger">Expiré</span>
                                @else
                                    <span class="badge bg-secondary">Inactif</span>
                                @endif
                                <br>
                                <small class="text-muted">Min: {{ $offer->min_quantity }}</small>
                            </div>
                        </div>
                    @endforeach
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.special-offers.index') }}" class="btn btn-sm btn-outline-warning">
                            Voir toutes les offres
                        </a>
                    </div>
                @else
                    <p class="text-muted mb-3">Aucune offre spéciale créée</p>
                    <div class="text-center">
                        <a href="{{ route('admin.special-offers.create') }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-plus me-1"></i>
                            Créer une offre
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.stat-card {
    background: white;
    border-radius: 15px;
    padding: 30px;
    text-align: center;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    font-size: 30px;
    color: white;
}

.stat-icon.users { background: linear-gradient(45deg, #007bff, #0056b3); }
.stat-icon.products { background: linear-gradient(45deg, #28a745, #1e7e34); }
.stat-icon.orders { background: linear-gradient(45deg, #ffc107, #e0a800); }
.stat-icon.special-offers { background: linear-gradient(45deg, #fd7e14, #e55b13); }
.stat-icon.blogs { background: linear-gradient(45deg, #6f42c1, #5a2d91); }

.border-left-warning {
    border-left: 4px solid #ffc107 !important;
}

.card.border-warning {
    border: 2px solid #ffc107 !important;
}

.btn-group .btn {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.btn-group .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.card-body.py-2 {
    padding: 0.5rem 1rem !important;
}
</style>
@endsection
