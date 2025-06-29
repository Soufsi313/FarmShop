@extends('admin.layout')

@section('title', 'Détails de la Catégorie')

@section('content')
<div class="admin-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-layer-group me-2"></i>{{ $category->name }}</h1>
            <p class="text-muted mb-0">Détails de la catégorie et produits associés</p>
        </div>
        <div>
            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-primary me-2">
                <i class="fas fa-edit me-2"></i>Modifier
            </a>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Retour à la liste
            </a>
        </div>
    </div>
</div>

<div class="row">
    <!-- Informations de la catégorie -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="fas fa-info-circle me-2"></i>Informations</h5>
            </div>
            <div class="card-body">
                @if($category->image)
                    <div class="text-center mb-3">
                        <img src="{{ asset('storage/categories/' . $category->image) }}" 
                             alt="{{ $category->name }}" 
                             class="img-fluid rounded"
                             style="max-width: 100%; max-height: 200px; object-fit: cover;">
                    </div>
                @endif

                <table class="table table-sm">
                    <tr>
                        <td><strong>Nom :</strong></td>
                        <td>{{ $category->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Slug :</strong></td>
                        <td><code>{{ $category->slug }}</code></td>
                    </tr>
                    <tr>
                        <td><strong>Type :</strong></td>
                        <td>
                            <span class="badge bg-{{ $category->type === 'purchase' ? 'primary' : ($category->type === 'rental' ? 'warning' : 'success') }}">
                                {{ ucfirst($category->type) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Type alimentaire :</strong></td>
                        <td>
                            @if($category->food_type === 'non_food')
                                <span class="badge bg-secondary">Non alimentaire</span>
                            @elseif($category->food_type === 'perishable')
                                <span class="badge bg-warning">Périssable</span>
                            @else
                                <span class="badge bg-info">Non périssable</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Statut :</strong></td>
                        <td>
                            @if($category->is_active)
                                <span class="badge bg-success">Actif</span>
                            @else
                                <span class="badge bg-secondary">Inactif</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Ordre :</strong></td>
                        <td><span class="badge bg-light text-dark">{{ $category->sort_order }}</span></td>
                    </tr>
                    <tr>
                        <td><strong>Retours :</strong></td>
                        <td>
                            @if($category->allows_returns)
                                <span class="text-success"><i class="fas fa-check"></i> Autorisés</span>
                            @else
                                <span class="text-danger"><i class="fas fa-times"></i> Non autorisés</span>
                            @endif
                        </td>
                    </tr>
                </table>

                @if($category->description)
                    <div class="mt-3">
                        <strong>Description :</strong>
                        <p class="mt-2">{{ $category->description }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Statistiques -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="fas fa-chart-bar me-2"></i>Statistiques</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-12 mb-3">
                        <h3 class="text-primary mb-0">{{ $category->products->count() }}</h3>
                        <small class="text-muted">Produits dans cette catégorie</small>
                    </div>
                </div>
                
                @if($category->products->count() > 0)
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h5 class="text-success mb-0">{{ $category->products->where('is_active', true)->count() }}</h5>
                                <small class="text-muted">Actifs</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h5 class="text-secondary mb-0">{{ $category->products->where('is_active', false)->count() }}</h5>
                            <small class="text-muted">Inactifs</small>
                        </div>
                    </div>
                @endif

                <hr>
                <div class="small text-muted">
                    <div><strong>Créée le :</strong> {{ $category->created_at->format('d/m/Y à H:i') }}</div>
                    <div><strong>Modifiée le :</strong> {{ $category->updated_at->format('d/m/Y à H:i') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Produits de la catégorie -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0"><i class="fas fa-box me-2"></i>Produits ({{ $category->products->count() }})</h5>
                @if(Gate::allows('manage products'))
                    <a href="{{ route('admin.products.create', ['category_id' => $category->id]) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1"></i>Ajouter un produit
                    </a>
                @endif
            </div>
            <div class="card-body">
                @if($category->products->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Image</th>
                                    <th>Nom</th>
                                    <th>Prix</th>
                                    <th>Stock</th>
                                    <th>Statut</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($category->products as $product)
                                <tr>
                                    <td>
                                        @if($product->image)
                                            <img src="{{ asset('storage/products/' . $product->image) }}" 
                                                 alt="{{ $product->name }}" 
                                                 class="rounded" 
                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $product->name }}</strong>
                                            @if($product->description)
                                                <br><small class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($product->price)
                                            <span class="fw-bold">{{ number_format($product->price, 2) }}€</span>
                                        @endif
                                        @if($product->rental_price)
                                            <br><small class="text-muted">{{ number_format($product->rental_price, 2) }}€/jour</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($product->manage_stock)
                                            <span class="badge bg-{{ $product->stock_quantity > 10 ? 'success' : ($product->stock_quantity > 0 ? 'warning' : 'danger') }}">
                                                {{ $product->stock_quantity }}
                                            </span>
                                        @else
                                            <span class="text-muted">Non géré</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($product->is_active)
                                            <span class="badge bg-success">Actif</span>
                                        @else
                                            <span class="badge bg-secondary">Inactif</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group">
                                            @can('view products')
                                                <a href="{{ route('admin.products.show', $product) }}" 
                                                   class="btn btn-sm btn-outline-info" 
                                                   title="Voir">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @endcan
                                            @can('manage products')
                                                <a href="{{ route('admin.products.edit', $product) }}" 
                                                   class="btn btn-sm btn-outline-primary" 
                                                   title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-box fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Aucun produit dans cette catégorie</h5>
                        <p class="text-muted">Cette catégorie ne contient encore aucun produit.</p>
                        @can('manage products')
                            <a href="{{ route('admin.products.create', ['category_id' => $category->id]) }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Ajouter le premier produit
                            </a>
                        @endcan
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Actions rapides -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="fas fa-bolt me-2"></i>Actions rapides</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        @can('manage categories')
                            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-outline-primary w-100 mb-2">
                                <i class="fas fa-edit me-2"></i>Modifier la catégorie
                            </a>
                        @endcan
                    </div>
                    <div class="col-md-3">
                        @can('manage products')
                            <a href="{{ route('admin.products.create', ['category_id' => $category->id]) }}" class="btn btn-outline-success w-100 mb-2">
                                <i class="fas fa-plus me-2"></i>Ajouter un produit
                            </a>
                        @endcan
                    </div>
                    <div class="col-md-3">
                        @can('view products')
                            <a href="{{ route('admin.products.index', ['category_id' => $category->id]) }}" class="btn btn-outline-info w-100 mb-2">
                                <i class="fas fa-filter me-2"></i>Voir tous les produits
                            </a>
                        @endcan
                    </div>
                    <div class="col-md-3">
                        @can('manage categories')
                            <button type="button" 
                                    class="btn btn-outline-danger w-100 mb-2" 
                                    onclick="confirmDelete()">
                                <i class="fas fa-trash me-2"></i>Supprimer la catégorie
                            </button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
@can('manage categories')
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer la catégorie <strong>{{ $category->name }}</strong> ?</p>
                @if($category->products->count() > 0)
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Attention :</strong> Cette catégorie contient {{ $category->products->count() }} produit(s). 
                        Vous devez d'abord supprimer ou déplacer ces produits vers une autre catégorie.
                    </div>
                @else
                    <p class="text-warning"><i class="fas fa-exclamation-triangle me-2"></i>Cette action est irréversible.</p>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                @if($category->products->count() == 0)
                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Supprimer</button>
                    </form>
                @else
                    <button type="button" class="btn btn-danger" disabled>
                        Impossible de supprimer
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
@endcan

@push('scripts')
<script>
function confirmDelete() {
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endpush
@endsection
