@extends('layouts.admin')

@section('title', 'Gestion des utilisateurs - Dashboard Admin')
@section('page-title', 'Gestion des utilisateurs')

@section('content')
<div class="space-y-6">
    <!-- En-tête avec bouton d'action -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-xl font-semibold text-gray-900">Liste des utilisateurs</h2>
            <p class="text-gray-600">Gérez les comptes utilisateurs de la plateforme</p>
        </div>
        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
            Ajouter un utilisateur
        </button>
    </div>

    <!-- Filtres et recherche -->
    <div class="bg-white rounded-lg shadow p-4">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap gap-4">
            <!-- Recherche -->
            <div class="flex-1 min-w-64">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Rechercher par nom, username ou email..."
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <!-- Filtre par rôle -->
            <div>
                <select name="role" class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Tous les rôles</option>
                    <option value="Admin" {{ request('role') === 'Admin' ? 'selected' : '' }}>Admin</option>
                    <option value="User" {{ request('role') === 'User' ? 'selected' : '' }}>User</option>
                </select>
            </div>
            
            <!-- Tri -->
            <div>
                <select name="sort" class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="created_at" {{ $sortBy === 'created_at' ? 'selected' : '' }}>Date d'inscription</option>
                    <option value="name" {{ $sortBy === 'name' ? 'selected' : '' }}>Nom</option>
                    <option value="username" {{ $sortBy === 'username' ? 'selected' : '' }}>Username</option>
                    <option value="email" {{ $sortBy === 'email' ? 'selected' : '' }}>Email</option>
                    <option value="role" {{ $sortBy === 'role' ? 'selected' : '' }}>Rôle</option>
                    <option value="updated_at" {{ $sortBy === 'updated_at' ? 'selected' : '' }}>Dernière modification</option>
                </select>
            </div>
            
            <!-- Ordre -->
            <div>
                <select name="order" class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="desc" {{ $sortOrder === 'desc' ? 'selected' : '' }}>Décroissant</option>
                    <option value="asc" {{ $sortOrder === 'asc' ? 'selected' : '' }}>Croissant</option>
                </select>
            </div>
            
            <!-- Boutons -->
            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                    Filtrer
                </button>
                <a href="{{ route('admin.users.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md text-sm font-medium transition-colors">
                    Réinitialiser
                </a>
            </div>
        </form>
    </div>

    <!-- Résultats -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-700">
                    {{ $users->count() }} utilisateur(s) sur {{ $users->total() }}
                </span>
                <span class="text-sm text-gray-500">
                    Page {{ $users->currentPage() }} sur {{ $users->lastPage() }}
                </span>
            </div>
        </div>
        
        <!-- Tableau des utilisateurs -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utilisateur</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rôle</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Newsletter</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Inscription</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-blue-600 flex items-center justify-center text-white font-medium text-sm">
                                        {{ substr($user->name ?: $user->username, 0, 1) }}
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">{{ $user->name ?: 'N/A' }}</p>
                                        <p class="text-sm text-gray-500">{{ $user->username }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    @if($user->role === 'Admin') bg-red-100 text-red-800 
                                    @else bg-green-100 text-green-800 @endif">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    @if($user->newsletter_subscribed) bg-blue-100 text-blue-800 
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ $user->newsletter_subscribed ? 'Abonné' : 'Non abonné' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $user->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <!-- Bouton Voir -->
                                    <a href="{{ route('admin.users.show', $user) }}" 
                                       class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-1 rounded-lg text-xs font-medium transition-colors">
                                        Voir
                                    </a>
                                    
                                    <!-- Bouton Modifier -->
                                    <a href="{{ route('admin.users.edit', $user) }}" 
                                       class="bg-yellow-100 hover:bg-yellow-200 text-yellow-700 px-3 py-1 rounded-lg text-xs font-medium transition-colors">
                                        Modifier
                                    </a>
                                    
                                    <!-- Bouton Supprimer -->
                                    @if($user->id !== auth()->id())
                                        <button type="button" 
                                                onclick="confirmDelete({{ $user->id }})"
                                                class="bg-red-100 hover:bg-red-200 text-red-700 px-3 py-1 rounded-lg text-xs font-medium transition-colors">
                                            Supprimer
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun utilisateur trouvé</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    @if(request()->hasAny(['search', 'role']))
                                        Essayez de modifier vos critères de recherche.
                                    @else
                                        Commencez par créer votre premier utilisateur.
                                    @endif
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($users->hasPages())
        <div class="bg-white px-4 py-3 border border-gray-200 rounded-lg">
            {{ $users->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(userId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.')) {
        // Créer et soumettre un formulaire de suppression
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/users/${userId}`;
        
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
