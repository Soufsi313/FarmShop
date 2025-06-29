@extends('admin.layout')

@section('title', 'Créer une Catégorie')

@section('content')
<div class="admin-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-plus me-2"></i>Créer une Catégorie</h1>
            <p class="text-muted mb-0">Ajoutez une nouvelle catégorie de produits</p>
        </div>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Informations de base -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Nom de la catégorie <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="slug" class="form-label">Slug (URL)</label>
                            <input type="text" 
                                   class="form-control @error('slug') is-invalid @enderror" 
                                   id="slug" 
                                   name="slug" 
                                   value="{{ old('slug') }}" 
                                   placeholder="Généré automatiquement si vide">
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Laissez vide pour génération automatique à partir du nom</small>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="3" 
                                  placeholder="Description de la catégorie...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Type de catégorie -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="type" class="form-label">Type de catégorie <span class="text-danger">*</span></label>
                            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                <option value="">Sélectionnez un type</option>
                                <option value="purchase" {{ old('type') == 'purchase' ? 'selected' : '' }}>Achat</option>
                                <option value="rental" {{ old('type') == 'rental' ? 'selected' : '' }}>Location</option>
                                <option value="both" {{ old('type') == 'both' ? 'selected' : '' }}>Achat et Location</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="food_type" class="form-label">Type alimentaire</label>
                            <select class="form-select @error('food_type') is-invalid @enderror" id="food_type" name="food_type">
                                <option value="non_food" {{ old('food_type') == 'non_food' ? 'selected' : '' }}>Non alimentaire</option>
                                <option value="perishable" {{ old('food_type') == 'perishable' ? 'selected' : '' }}>Périssable</option>
                                <option value="non_perishable" {{ old('food_type') == 'non_perishable' ? 'selected' : '' }}>Non périssable</option>
                            </select>
                            @error('food_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Image -->
                    <div class="mb-4">
                        <label for="image" class="form-label">Image de la catégorie</label>
                        <input type="file" 
                               class="form-control @error('image') is-invalid @enderror" 
                               id="image" 
                               name="image" 
                               accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Formats acceptés : JPG, PNG, GIF. Taille maximale : 2MB</small>
                        
                        <!-- Prévisualisation -->
                        <div id="imagePreview" class="mt-2" style="display: none;">
                            <img id="previewImg" src="" alt="Prévisualisation" class="img-thumbnail" style="max-width: 200px;">
                        </div>
                    </div>

                    <!-- Options -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label for="sort_order" class="form-label">Ordre d'affichage</label>
                            <input type="number" 
                                   class="form-control @error('sort_order') is-invalid @enderror" 
                                   id="sort_order" 
                                   name="sort_order" 
                                   value="{{ old('sort_order', 0) }}" 
                                   min="0">
                            @error('sort_order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <div class="form-check mt-4">
                                <input class="form-check-input @error('allows_returns') is-invalid @enderror" 
                                       type="checkbox" 
                                       id="allows_returns" 
                                       name="allows_returns" 
                                       value="1" 
                                       {{ old('allows_returns') ? 'checked' : '' }}>
                                <label class="form-check-label" for="allows_returns">
                                    Autorise les retours
                                </label>
                                @error('allows_returns')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-check mt-4">
                                <input class="form-check-input @error('is_active') is-invalid @enderror" 
                                       type="checkbox" 
                                       id="is_active" 
                                       name="is_active" 
                                       value="1" 
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Catégorie active
                                </label>
                                @error('is_active')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Boutons -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Annuler
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Créer la catégorie
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Aide -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="fas fa-info-circle me-2"></i>Aide</h5>
            </div>
            <div class="card-body">
                <h6>Types de catégories :</h6>
                <ul class="list-unstyled">
                    <li><strong>Achat :</strong> Produits à vendre uniquement</li>
                    <li><strong>Location :</strong> Produits à louer uniquement</li>
                    <li><strong>Achat et Location :</strong> Produits disponibles dans les deux modes</li>
                </ul>

                <h6 class="mt-3">Types alimentaires :</h6>
                <ul class="list-unstyled">
                    <li><strong>Non alimentaire :</strong> Équipements, outils, etc.</li>
                    <li><strong>Périssable :</strong> Fruits, légumes, produits frais</li>
                    <li><strong>Non périssable :</strong> Conserves, produits secs</li>
                </ul>

                <h6 class="mt-3">Bonnes pratiques :</h6>
                <ul class="list-unstyled">
                    <li>• Utilisez des noms clairs et descriptifs</li>
                    <li>• Ajoutez une image représentative</li>
                    <li>• Organisez avec l'ordre d'affichage</li>
                    <li>• Rédigez une description utile</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Génération automatique du slug
document.getElementById('name').addEventListener('input', function() {
    const name = this.value;
    const slug = name.toLowerCase()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .trim('-');
    
    if (document.getElementById('slug').value === '') {
        document.getElementById('slug').value = slug;
    }
});

// Prévisualisation de l'image
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imagePreview').style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        document.getElementById('imagePreview').style.display = 'none';
    }
});
</script>
@endpush
@endsection
