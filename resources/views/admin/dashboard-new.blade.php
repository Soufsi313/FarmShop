@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
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
            <div class="stat-icon blogs">
                <i class="fas fa-layer-group"></i>
            </div>
            <h3>{{ $stats['categories_count'] }}</h3>
            <p class="text-muted mb-0">Catégories</p>
        </div>
    </div>
</div>

<!-- Activité récente -->
<div class="row">
    <div class="col-md-6">
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
    
    <div class="col-md-6">
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
.stat-icon.blogs { background: linear-gradient(45deg, #6f42c1, #5a2d91); }
</style>
@endsection
