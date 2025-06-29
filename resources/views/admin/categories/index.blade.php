@extends('admin.layout')

@section('title', 'Gestion des Catégories')

@section('content')
<div class="admin-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-layer-group me-2"></i>Gestion des Catégories</h1>
            <p class="text-muted mb-0">Gérez les catégories de produits de votre boutique</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Nouvelle Catégorie
        </a>
    </div>
</div>

<!-- Filtres -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.categories.index') }}" class="row g-3">
            <div class="col-md-4">
                <label for="search" class="form-label">Rechercher</label>
                <input type="text" class="form-control" id="search" name="search" 
                       value="{{ request('search') }}" placeholder="Nom ou description...">
            </div>
            <div class="col-md-3">
                <label for="is_active" class="form-label">Statut</label>
                <select class="form-select" id="is_active" name="is_active">
                    <option value="">Tous</option>
                    <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Actif</option>
                    <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Inactif</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="sort_by" class="form-label">Trier par</label>
                <select class="form-select" id="sort_by" name="sort_by">
                    <option value="sort_order" {{ request('sort_by') == 'sort_order' ? 'selected' : '' }}>Ordre</option>
                    <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Nom</option>
                    <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date de création</option>
                    <option value="products_count" {{ request('sort_by') == 'products_count' ? 'selected' : '' }}>Nombre de produits</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div>
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fas fa-search me-1"></i>Filtrer
                    </button>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary ms-1">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Messages de succès/erreur -->
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- Liste des catégories -->
<div class="card">
    <div class="card-body">
        @if($categories->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Image</th>
                            <th>
                                <a href="{{ route('admin.categories.index', array_merge(request()->all(), ['sort_by' => 'name', 'sort_direction' => request('sort_direction') == 'asc' ? 'desc' : 'asc'])) }}" 
                                   class="text-decoration-none text-dark">
                                    Nom
                                    @if(request('sort_by') == 'name')
                                        <i class="fas fa-sort-{{ request('sort_direction') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                    @endif
                                </a>
                            </th>
                            <th>Type</th>
                            <th>
                                <a href="{{ route('admin.categories.index', array_merge(request()->all(), ['sort_by' => 'products_count', 'sort_direction' => request('sort_direction') == 'asc' ? 'desc' : 'asc'])) }}" 
                                   class="text-decoration-none text-dark">
                                    Produits
                                    @if(request('sort_by') == 'products_count')
                                        <i class="fas fa-sort-{{ request('sort_direction') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                    @endif
                                </a>
                            </th>
                            <th>Statut</th>
                            <th>
                                <a href="{{ route('admin.categories.index', array_merge(request()->all(), ['sort_by' => 'sort_order', 'sort_direction' => request('sort_direction') == 'asc' ? 'desc' : 'asc'])) }}" 
                                   class="text-decoration-none text-dark">
                                    Ordre
                                    @if(request('sort_by') == 'sort_order')
                                        <i class="fas fa-sort-{{ request('sort_direction') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                    @endif
                                </a>
                            </th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                        <tr>
                            <td>
                                @if($category->image)
                                    <img src="{{ asset('storage/categories/' . $category->image) }}" 
                                         alt="{{ $category->name }}" 
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
                                    <strong>{{ $category->name }}</strong>
                                    @if($category->description)
                                        <br><small class="text-muted">{{ Str::limit($category->description, 50) }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-{{ $category->type === 'purchase' ? 'primary' : ($category->type === 'rental' ? 'warning' : 'success') }}">
                                    {{ ucfirst($category->type) }}
                                </span>
                                @if($category->food_type && $category->food_type !== 'non_food')
                                    <br><small class="text-muted">{{ ucfirst(str_replace('_', ' ', $category->food_type)) }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $category->products_count }}</span>
                            </td>
                            <td>
                                @if($category->is_active)
                                    <span class="badge bg-success">Actif</span>
                                @else
                                    <span class="badge bg-secondary">Inactif</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">{{ $category->sort_order }}</span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <a href="{{ route('admin.categories.show', $category) }}" 
                                       class="btn btn-sm btn-outline-info" 
                                       title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.categories.edit', $category) }}" 
                                       class="btn btn-sm btn-outline-primary" 
                                       title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger" 
                                            title="Supprimer"
                                            onclick="confirmDelete({{ $category->id }}, '{{ $category->name }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Affichage de {{ $categories->firstItem() }} à {{ $categories->lastItem() }} 
                    sur {{ $categories->total() }} catégories
                </div>
                {{ $categories->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-layer-group fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Aucune catégorie trouvée</h5>
                <p class="text-muted">
                    @if(request()->hasAny(['search', 'is_active']))
                        Aucune catégorie ne correspond à vos critères de recherche.
                    @else
                        Commencez par créer votre première catégorie.
                    @endif
                </p>
                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Créer une catégorie
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer la catégorie <strong id="categoryName"></strong> ?</p>
                <p class="text-warning"><i class="fas fa-exclamation-triangle me-2"></i>Cette action est irréversible.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(categoryId, categoryName) {
    document.getElementById('categoryName').textContent = categoryName;
    document.getElementById('deleteForm').action = `/admin/categories/${categoryId}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endpush
@endsection
