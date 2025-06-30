@extends('admin.layout')

@section('title', 'Gestion des Produits')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-box me-2"></i>Gestion des Produits</h2>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addProductModal">
        <i class="fas fa-plus me-2"></i>Ajouter un produit
    </button>
</div>

<!-- Filtres -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="text" class="form-control" name="search" placeholder="Rechercher par nom, description..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select class="form-select" name="category_id">
                    <option value="">Toutes les catégories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select" name="product_type">
                    <option value="">Tous les types</option>
                    <option value="purchase" {{ request('product_type') == 'purchase' ? 'selected' : '' }}>Achat</option>
                    <option value="rental" {{ request('product_type') == 'rental' ? 'selected' : '' }}>Location</option>
                    <option value="both" {{ request('product_type') == 'both' ? 'selected' : '' }}>Achat + Location</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select" name="status">
                    <option value="">Tous les statuts</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actif</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactif</option>
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

<!-- Table des produits -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Nom</th>
                        <th>Catégorie</th>
                        <th>Type</th>
                        <th>Prix</th>
                        <th>Stock</th>
                        <th>Seuil critique</th>
                        <th>Statut</th>
                        <th>Créé le</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>
                            @if($product->image)
                                <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" class="rounded" width="50" height="50" style="object-fit: cover;">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
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
                            @if($product->category)
                                <span class="badge bg-info">{{ $product->category->name }}</span>
                            @else
                                <span class="text-muted">Aucune</span>
                            @endif
                        </td>
                        <td>
                            @if($product->is_rentable && $product->price > 0)
                                <span class="badge bg-warning">
                                    <i class="fas fa-star me-1"></i>Achat + Location
                                </span>
                            @elseif($product->is_rentable)
                                <span class="badge bg-info">
                                    <i class="fas fa-calendar-alt me-1"></i>Location
                                </span>
                            @else
                                <span class="badge bg-success">
                                    <i class="fas fa-shopping-cart me-1"></i>Achat
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($product->is_rentable && $product->rental_price_per_day > 0)
                                @if($product->price > 0)
                                    <!-- Produit mixte achat + location -->
                                    <div>
                                        <strong class="text-success">{{ number_format($product->price, 2) }} €</strong>
                                        <small class="text-muted">/{{ $product->unit_symbol }}</small>
                                    </div>
                                    <div class="mt-1">
                                        <strong class="text-info">{{ number_format($product->rental_price_per_day, 2) }} €</strong>
                                        <small class="text-muted">/jour</small>
                                    </div>
                                @else
                                    <!-- Produit location uniquement -->
                                    <div>
                                        <strong class="text-info">{{ number_format($product->rental_price_per_day, 2) }} €</strong>
                                        <small class="text-muted">/jour</small>
                                    </div>
                                    @if($product->deposit_amount > 0)
                                        <div class="mt-1">
                                            <small class="text-warning">Caution: {{ number_format($product->deposit_amount, 2) }} €</small>
                                        </div>
                                    @endif
                                @endif
                            @else
                                <!-- Produit achat uniquement -->
                                <div>
                                    <strong class="text-success">{{ number_format($product->price, 2) }} €</strong>
                                    <small class="text-muted">/{{ $product->unit_symbol ?? 'unité' }}</small>
                                </div>
                                @if($product->original_price && $product->original_price > $product->price)
                                    <div class="mt-1">
                                        <small class="text-decoration-line-through text-muted">{{ number_format($product->original_price, 2) }} €</small>
                                    </div>
                                @endif
                            @endif
                        </td>
                        <td>
                            @if($product->quantity !== null)
                                <span class="badge bg-{{ $product->quantity > $product->critical_stock_threshold ? 'success' : ($product->quantity > 0 ? 'warning' : 'danger') }}">
                                    {{ $product->quantity }}
                                </span>
                            @else
                                <span class="text-muted">Illimité</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $product->quantity <= $product->critical_stock_threshold ? 'danger' : 'secondary' }}">
                                {{ $product->critical_stock_threshold }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $product->is_active ? 'success' : 'secondary' }}">
                                {{ $product->is_active ? 'Actif' : 'Inactif' }}
                            </span>
                        </td>
                        <td>{{ $product->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editProductModal{{ $product->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-info" target="_blank">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteProduct({{ $product->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4">
                            <i class="fas fa-box fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Aucun produit trouvé</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $products->links() }}
    </div>
</div>

<!-- Modal Ajouter Produit -->
<div class="modal fade" id="addProductModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter un produit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nom du produit</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Catégorie</label>
                                <select class="form-select" name="category_id">
                                    <option value="">Sélectionner une catégorie</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Prix (€)</label>
                                <input type="number" class="form-control" name="price" step="0.01" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Stock</label>
                                <input type="number" class="form-control" name="quantity" placeholder="Quantité en stock" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Seuil critique</label>
                                <input type="number" class="form-control" name="critical_stock_threshold" value="10" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Unité</label>
                                <select class="form-select" name="unit_symbol" required>
                                    <option value="kg">Kilogramme (kg)</option>
                                    <option value="piece">À la pièce</option>
                                    <option value="liter">Litre</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Image</label>
                        <input type="file" class="form-control" name="image" accept="image/*">
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="is_active" value="1" checked>
                            <label class="form-check-label">Produit actif</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">Créer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modals d'édition des produits -->
@foreach($products as $product)
<div class="modal fade" id="editProductModal{{ $product->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Modifier le produit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nom du produit</label>
                                <input type="text" class="form-control" name="name" value="{{ $product->name }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Catégorie</label>
                                <select class="form-select" name="category_id" required>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3">{{ $product->description }}</textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Prix d'achat (€) <small class="text-muted">(0 si location uniquement)</small></label>
                                <input type="number" class="form-control" name="price" step="0.01" value="{{ $product->price }}" min="0">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Stock</label>
                                <input type="number" class="form-control" name="quantity" value="{{ $product->quantity }}" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Seuil critique</label>
                                <input type="number" class="form-control" name="critical_stock_threshold" value="{{ $product->critical_stock_threshold }}" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Unité</label>
                                <select class="form-select" name="unit_symbol" required>
                                    <option value="kg" {{ $product->unit_symbol == 'kg' ? 'selected' : '' }}>Kilogramme (kg)</option>
                                    <option value="piece" {{ $product->unit_symbol == 'piece' ? 'selected' : '' }}>À la pièce</option>
                                    <option value="liter" {{ $product->unit_symbol == 'liter' ? 'selected' : '' }}>Litre</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Section Location -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="fas fa-calendar-alt me-2"></i>Paramètres de location
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="is_rentable" value="1" 
                                           {{ $product->is_rentable ? 'checked' : '' }} id="is_rentable_{{ $product->id }}">
                                    <label class="form-check-label" for="is_rentable_{{ $product->id }}">
                                        Disponible en location
                                    </label>
                                </div>
                            </div>
                            
                            <div class="row rental-fields" style="display: {{ $product->is_rentable ? 'block' : 'none' }};">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Prix/jour (€)</label>
                                        <input type="number" class="form-control" name="rental_price_per_day" 
                                               step="0.01" value="{{ $product->rental_price_per_day }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Caution (€)</label>
                                        <input type="number" class="form-control" name="deposit_amount" 
                                               step="0.01" value="{{ $product->deposit_amount }}">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label">Min jours</label>
                                        <input type="number" class="form-control" name="min_rental_days" 
                                               value="{{ $product->min_rental_days ?? 1 }}">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label">Max jours</label>
                                        <input type="number" class="form-control" name="max_rental_days" 
                                               value="{{ $product->max_rental_days ?? 30 }}">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Conditions de location</label>
                                        <textarea class="form-control" name="rental_conditions" rows="2" 
                                                  placeholder="Ex: Nettoyer après usage, vérifier l'huile...">{{ $product->rental_conditions }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Image</label>
                        <input type="file" class="form-control" name="image" accept="image/*">
                        @if($product->main_image)
                            <small class="text-muted">Image actuelle : {{ $product->main_image }}</small>
                        @endif
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }}>
                            <label class="form-check-label">Produit actif</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Modifier</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<script>
function deleteProduct(productId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')) {
        fetch(`/admin/products/${productId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }).then(() => {
            location.reload();
        });
    }
}

// Gestion de l'affichage des champs de location
document.addEventListener('DOMContentLoaded', function() {
    // Pour chaque modal
    document.querySelectorAll('[id^="is_rentable_"]').forEach(checkbox => {
        const productId = checkbox.id.replace('is_rentable_', '');
        const rentalFields = checkbox.closest('.modal').querySelector('.rental-fields');
        
        // Événement change sur la checkbox
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                rentalFields.style.display = 'block';
            } else {
                rentalFields.style.display = 'none';
            }
        });
    });
});
</script>
@endsection
