@extends('layouts.admin')

@section('title', 'Détails de la Catégorie - Dashboard Admin')
@section('page-title', 'Détails de la Catégorie de Location')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="flex justify-between items-start">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ $rentalCategory->name }}</h2>
            <p class="text-gray-600">{{ $rentalCategory->slug }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.rental-categories.edit', $rentalCategory) }}" 
               class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition-colors">
                Modifier
            </a>
            <a href="{{ route('admin.rental-categories.index') }}" 
               class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg transition-colors">
                Retour à la liste
            </a>
        </div>
    </div>

    <!-- Messages de succès/erreur -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Colonne principale -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informations générales -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informations générales</h3>
                
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nom</dt>
                        <dd class="text-sm text-gray-900">{{ $rentalCategory->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Slug</dt>
                        <dd class="text-sm text-gray-900">{{ $rentalCategory->slug }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Ordre d'affichage</dt>
                        <dd class="text-sm text-gray-900">{{ $rentalCategory->display_order ?? 0 }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Icône</dt>
                        <dd class="text-sm text-gray-900">
                            @if($rentalCategory->icon)
                                <div class="flex items-center space-x-2">
                                    <i class="{{ $rentalCategory->icon }} text-indigo-600"></i>
                                    <span>{{ $rentalCategory->icon }}</span>
                                </div>
                            @else
                                <span class="text-gray-400 italic">Aucune icône</span>
                            @endif
                        </dd>
                    </div>
                </dl>

                @if($rentalCategory->description)
                <div class="mt-6">
                    <dt class="text-sm font-medium text-gray-500 mb-2">Description</dt>
                    <dd class="text-sm text-gray-900 whitespace-pre-wrap">{{ $rentalCategory->description }}</dd>
                </div>
                @endif
            </div>

            <!-- SEO -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Référencement SEO</h3>
                
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Titre SEO (meta title)</dt>
                        <dd class="text-sm text-gray-900 mt-1">
                            {{ $rentalCategory->meta_title ?: 'Non défini' }}
                        </dd>
                        @if($rentalCategory->meta_title)
                            <div class="mt-1 text-xs text-gray-500">
                                {{ strlen($rentalCategory->meta_title) }} caractères
                                @if(strlen($rentalCategory->meta_title) > 60)
                                    <span class="text-orange-600">(⚠️ Trop long)</span>
                                @elseif(strlen($rentalCategory->meta_title) < 30)
                                    <span class="text-yellow-600">(⚠️ Trop court)</span>
                                @else
                                    <span class="text-green-600">(✅ Optimal)</span>
                                @endif
                            </div>
                        @endif
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Description SEO (meta description)</dt>
                        <dd class="text-sm text-gray-900 mt-1">
                            {{ $rentalCategory->meta_description ?: 'Non définie' }}
                        </dd>
                        @if($rentalCategory->meta_description)
                            <div class="mt-1 text-xs text-gray-500">
                                {{ strlen($rentalCategory->meta_description) }} caractères
                                @if(strlen($rentalCategory->meta_description) > 160)
                                    <span class="text-orange-600">(⚠️ Trop long)</span>
                                @elseif(strlen($rentalCategory->meta_description) < 120)
                                    <span class="text-yellow-600">(⚠️ Trop court)</span>
                                @else
                                    <span class="text-green-600">(✅ Optimal)</span>
                                @endif
                            </div>
                        @endif
                    </div>
                </dl>

                <!-- Aperçu Google -->
                @if($rentalCategory->meta_title || $rentalCategory->meta_description)
                <div class="mt-6 p-4 border border-gray-200 rounded-lg bg-gray-50">
                    <h4 class="text-sm font-medium text-gray-900 mb-2">Aperçu dans Google</h4>
                    <div class="space-y-1">
                        <div class="text-blue-600 text-lg font-medium">
                            {{ $rentalCategory->meta_title ?: $rentalCategory->name }}
                        </div>
                        <div class="text-green-600 text-sm">
                            {{ url('/') }}/locations/categories/{{ $rentalCategory->slug }}
                        </div>
                        <div class="text-gray-600 text-sm">
                            {{ $rentalCategory->meta_description ?: Str::limit($rentalCategory->description, 160) }}
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Statistiques -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Statistiques</h3>
                
                <dl class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Produits associés</dt>
                        <dd class="text-2xl font-bold text-gray-900">
                            {{ \App\Models\Product::where('rental_category_id', $rentalCategory->id)->count() }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Produits actifs</dt>
                        <dd class="text-2xl font-bold text-green-600">
                            {{ \App\Models\Product::where('rental_category_id', $rentalCategory->id)->where('is_active', true)->count() }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Produits inactifs</dt>
                        <dd class="text-2xl font-bold text-red-600">
                            {{ \App\Models\Product::where('rental_category_id', $rentalCategory->id)->where('is_active', false)->count() }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Colonne latérale -->
        <div class="space-y-6">
            <!-- Statut et visibilité -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Statut</h3>
                
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Statut</span>
                        @if($rentalCategory->is_active)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                ✅ Actif
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                ❌ Inactif
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Actions</h3>
                
                <div class="space-y-3">
                    <a href="{{ route('admin.rental-categories.edit', $rentalCategory) }}" 
                       class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition-colors text-center block">
                        Modifier la catégorie
                    </a>
                    
                    <button type="button" 
                            onclick="confirmDelete({{ $rentalCategory->id }})"
                            class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        Supprimer la catégorie
                    </button>
                </div>
            </div>

            <!-- Informations système -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informations système</h3>
                
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Créée le</dt>
                        <dd class="text-sm text-gray-900">{{ $rentalCategory->created_at->format('d/m/Y à H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Modifiée le</dt>
                        <dd class="text-sm text-gray-900">{{ $rentalCategory->updated_at->format('d/m/Y à H:i') }}</dd>
                    </div>
                    @if($rentalCategory->deleted_at)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Supprimée le</dt>
                        <dd class="text-sm text-red-600">{{ $rentalCategory->deleted_at->format('d/m/Y à H:i') }}</dd>
                    </div>
                    @endif
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(categoryId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette catégorie de location ? Cette action est irréversible.')) {
        // Créer et soumettre un formulaire de suppression
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/rental-categories/${categoryId}`;
        
        // CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        // Method spoofing pour DELETE
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);
        
        // Ajouter au DOM et soumettre
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
