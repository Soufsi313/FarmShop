@extends('layouts.admin')

@section('title', 'Catégories de Location - Dashboard Admin')
@section('page-title', 'Catégories de Location')

@section('content')
<div class="space-y-6">
    <!-- En-tête avec recherche et filtres -->
    <div class="flex justify-between items-start">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Catégories de Location</h2>
            <p class="text-gray-600">Gérez les catégories pour vos produits de location</p>
        </div>
        <a href="{{ route('admin.rental-categories.create') }}" 
           class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
            Nouvelle catégorie
        </a>
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

    <!-- Formulaire de recherche et filtres -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" action="{{ route('admin.rental-categories.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Recherche -->
            <div class="md:col-span-2">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Rechercher</label>
                <input type="text" 
                       id="search" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Nom, description..."
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <!-- Statut -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                <select id="status" 
                        name="status" 
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Tous les statuts</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Actif</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactif</option>
                </select>
            </div>

            <!-- Boutons -->
            <div class="flex space-x-2">
                <button type="submit" 
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors flex-1">
                    Filtrer
                </button>
                <a href="{{ route('admin.rental-categories.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md text-sm font-medium transition-colors text-center">
                    Reset
                </a>
            </div>
        </form>

        <!-- Résultats -->
        <div class="mt-4 text-sm text-gray-600">
            <span class="font-medium">{{ $rentalCategories->total() }}</span> catégorie(s) trouvée(s)
            @if(request()->hasAny(['search', 'status']))
                sur <span class="font-medium">{{ \App\Models\RentalCategory::count() }}</span> au total
            @endif
            @if($rentalCategories->hasPages())
                <span class="ml-4">
                    Page {{ $rentalCategories->currentPage() }} sur {{ $rentalCategories->lastPage() }}
                </span>
            @endif
        </div>
    </div>

    <!-- Tableau des catégories -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catégorie</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SEO</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ordre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($rentalCategories as $category)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($category->icon)
                                        <div class="h-8 w-8 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-600 mr-3">
                                            <i class="{{ $category->icon }}"></i>
                                        </div>
                                    @else
                                        <div class="h-8 w-8 rounded-lg bg-gray-100 flex items-center justify-center text-gray-600 mr-3">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $category->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $category->slug }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-900 max-w-xs truncate">
                                    {{ $category->description ?: 'Aucune description' }}
                                </p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm">
                                    @if($category->meta_title)
                                        <p class="text-gray-900 font-medium">{{ Str::limit($category->meta_title, 30) }}</p>
                                    @endif
                                    @if($category->meta_description)
                                        <p class="text-gray-500">{{ Str::limit($category->meta_description, 40) }}</p>
                                    @else
                                        <p class="text-gray-400 italic">Pas de méta-description</p>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $category->display_order }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($category->is_active)
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Actif
                                    </span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        Inactif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <!-- Bouton Voir -->
                                    <a href="{{ route('admin.rental-categories.show', $category) }}" 
                                       class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-1 rounded-lg text-xs font-medium transition-colors">
                                        Voir
                                    </a>
                                    
                                    <!-- Bouton Modifier -->
                                    <a href="{{ route('admin.rental-categories.edit', $category) }}" 
                                       class="bg-yellow-100 hover:bg-yellow-200 text-yellow-700 px-3 py-1 rounded-lg text-xs font-medium transition-colors">
                                        Modifier
                                    </a>
                                    
                                    <!-- Bouton Supprimer -->
                                    <button type="button" 
                                            onclick="confirmDelete({{ $category->id }})"
                                            class="bg-red-100 hover:bg-red-200 text-red-700 px-3 py-1 rounded-lg text-xs font-medium transition-colors">
                                        Supprimer
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune catégorie trouvée</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    @if(request()->hasAny(['search', 'status']))
                                        Essayez de modifier vos critères de recherche.
                                    @else
                                        Commencez par créer votre première catégorie de location.
                                    @endif
                                </p>
                                @if(!request()->hasAny(['search', 'status']))
                                    <a href="{{ route('admin.rental-categories.create') }}" 
                                       class="mt-3 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200">
                                        Créer une catégorie
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($rentalCategories->hasPages())
        <div class="bg-white px-4 py-3 border border-gray-200 rounded-lg">
            {{ $rentalCategories->appends(request()->query())->links() }}
        </div>
    @endif
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
