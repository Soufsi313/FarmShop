@extends('admin.layout')

@section('title', 'Gestion des offres spéciales')

@section('content')
<div class="container-fluid">
    <!-- En-tête avec bouton d'ajout -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-tags text-warning me-2"></i>
                Gestion des offres spéciales
            </h1>
            <p class="text-muted mb-0">Créez et gérez les offres par quantité pour vos produits</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.special-offers.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Créer une offre
            </a>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.special-offers.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="status" class="form-label">Statut</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">Tous les statuts</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>🟢 Actives</option>
                        <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>🔵 Programmées</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>🔴 Expirées</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>⚫ Inactives</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="product_id" class="form-label">Produit</label>
                    <select name="product_id" id="product_id" class="form-select">
                        <option value="">Tous les produits</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-outline-primary me-2">
                        <i class="fas fa-search me-1"></i> Filtrer
                    </button>
                    <a href="{{ route('admin.special-offers.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-success">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Offres actives</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $offers->where('status', 'active')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-info">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Programmées</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $offers->where('status', 'scheduled')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-warning">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Expirées</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $offers->where('status', 'expired')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-secondary">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Inactives</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $offers->where('status', 'inactive')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-pause-circle fa-2x text-secondary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des offres -->
    <div class="card">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">
                Liste des offres spéciales ({{ $offers->total() }} au total)
            </h6>
        </div>
        <div class="card-body">
            @if($offers->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Nom de l'offre</th>
                                <th>Produit</th>
                                <th>Quantité min.</th>
                                <th>Remise</th>
                                <th>Période de validité</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($offers as $offer)
                                <tr>
                                    <td>
                                        <strong>{{ $offer->name }}</strong>
                                        @if($offer->description)
                                            <br><small class="text-muted">{{ Str::limit($offer->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('products.show', $offer->product->id) }}" class="text-decoration-none" target="_blank">
                                            {{ $offer->product->name }}
                                            <i class="fas fa-external-link-alt ms-1 text-muted" style="font-size: 0.8em;"></i>
                                        </a>
                                        <br><small class="text-muted">{{ number_format($offer->product->price, 2) }}€</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info text-white">
                                            {{ $offer->min_quantity }} articles
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success text-white fs-6">
                                            -{{ $offer->discount_percentage }}%
                                        </span>
                                    </td>
                                    <td>
                                        <small>
                                            Du {{ $offer->start_date->format('d/m/Y') }}<br>
                                            au {{ $offer->end_date->format('d/m/Y') }}
                                        </small>
                                        @if($offer->status === 'active' && $offer->days_remaining <= 3)
                                            <br><span class="badge bg-warning text-dark">
                                                {{ $offer->days_remaining }} jour(s) restant(s)
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @switch($offer->status)
                                            @case('active')
                                                <span class="badge bg-success">🟢 Active</span>
                                                @break
                                            @case('scheduled')
                                                <span class="badge bg-info">🔵 Programmée</span>
                                                @break
                                            @case('expired')
                                                <span class="badge bg-warning">🔴 Expirée</span>
                                                @break
                                            @case('inactive')
                                                <span class="badge bg-secondary">⚫ Inactive</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.special-offers.show', $offer) }}" class="btn btn-sm btn-outline-info" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.special-offers.edit', $offer) }}" class="btn btn-sm btn-outline-primary" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('admin.special-offers.toggle', $offer) }}" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-outline-{{ $offer->is_active ? 'warning' : 'success' }}" 
                                                        title="{{ $offer->is_active ? 'Désactiver' : 'Activer' }}">
                                                    <i class="fas fa-{{ $offer->is_active ? 'pause' : 'play' }}"></i>
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.special-offers.destroy', $offer) }}" class="d-inline" 
                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette offre ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="mt-3">
                    {{ $offers->withQueryString()->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Aucune offre spéciale trouvée</h5>
                    <p class="text-muted">Commencez par créer votre première offre spéciale !</p>
                    <a href="{{ route('admin.special-offers.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Créer une offre
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
