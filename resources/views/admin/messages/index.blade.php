@extends('layouts.admin')

@section('title', 'Messages')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Messages reçus</h1>
        <div class="text-sm text-gray-600">
            Total: {{ $messages->total() }} messages
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white rounded-lg border border-gray-200 p-4 mb-6">
        <form method="GET" action="{{ route('admin.messages.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Filtre par auteur -->
                <div>
                    <label for="author" class="block text-sm font-medium text-gray-700 mb-1">Auteur</label>
                    <input type="text" id="author" name="author" value="{{ request('author') }}" 
                           placeholder="Nom ou email..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Filtre par raison -->
                <div>
                    <label for="reason" class="block text-sm font-medium text-gray-700 mb-1">Motif</label>
                    <select id="reason" name="reason" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Tous les motifs</option>
                        <option value="question" {{ request('reason') == 'question' ? 'selected' : '' }}>Question</option>
                        <option value="support" {{ request('reason') == 'support' ? 'selected' : '' }}>Support</option>
                        <option value="commande" {{ request('reason') == 'commande' ? 'selected' : '' }}>Commande</option>
                        <option value="autre" {{ request('reason') == 'autre' ? 'selected' : '' }}>Autre</option>
                    </select>
                </div>

                <!-- Filtre par priorité -->
                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">Priorité</label>
                    <select id="priority" name="priority" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Toutes priorités</option>
                        <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                        <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>Haute</option>
                        <option value="normal" {{ request('priority') == 'normal' ? 'selected' : '' }}>Normale</option>
                        <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Basse</option>
                    </select>
                </div>

                <!-- Statut -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                    <select id="status" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Tous statuts</option>
                        <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>Nouveau</option>
                        <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Lu</option>
                        <option value="responded" {{ request('status') == 'responded' ? 'selected' : '' }}>Répondu</option>
                        <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archivé</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Date de début -->
                <div>
                    <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">Date de début</label>
                    <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Date de fin -->
                <div>
                    <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">Date de fin</label>
                    <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Recherche -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Recherche</label>
                    <input type="text" id="search" name="search" value="{{ request('search') }}" 
                           placeholder="Rechercher dans le contenu..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.messages.index') }}" 
                   class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md">
                    Réinitialiser
                </a>
                <button type="submit" 
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md">
                    Filtrer
                </button>
            </div>
        </form>
    </div>

    <!-- Liste compacte des messages -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        @if($messages->count() > 0)
            @foreach($messages as $message)
                <div class="border-b border-gray-100 hover:bg-gray-50 transition-colors duration-150">
                    <div class="p-4">
                        <!-- Ligne principale desktop -->
                        <div class="hidden md:flex items-center justify-between">
                            <!-- Informations de l'auteur (compactes) -->
                            <div class="flex items-center space-x-3 flex-1">
                                @if($message->sender)
                                    <div class="h-8 w-8 rounded-full bg-gradient-to-br from-green-500 to-blue-600 flex items-center justify-center text-white font-bold text-sm">
                                        {{ substr($message->sender->name ?: $message->sender->username, 0, 1) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center space-x-2">
                                            <h4 class="font-medium text-gray-900 text-sm truncate">
                                                {{ $message->sender->name ?: $message->sender->username }}
                                            </h4>
                                            <span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs rounded-full">
                                                Inscrit
                                            </span>
                                        </div>
                                        <p class="text-xs text-gray-600 truncate">{{ $message->sender->email }}</p>
                                    </div>
                                @elseif($message->metadata && isset($message->metadata['sender_name']))
                                    <div class="h-8 w-8 rounded-full bg-gradient-to-br from-orange-500 to-red-500 flex items-center justify-center text-white font-bold text-sm">
                                        {{ substr($message->metadata['sender_name'], 0, 1) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center space-x-2">
                                            <h4 class="font-medium text-gray-900 text-sm truncate">{{ $message->metadata['sender_name'] }}</h4>
                                            <span class="px-2 py-0.5 bg-orange-100 text-orange-700 text-xs rounded-full">
                                                Visiteur
                                            </span>
                                            @if(isset($message->metadata['migrated_from_contacts']))
                                                <span class="px-2 py-0.5 bg-purple-100 text-purple-700 text-xs rounded-full">
                                                    Migré
                                                </span>
                                            @endif
                                        </div>
                                        @if(isset($message->metadata['sender_email']))
                                            <p class="text-xs text-gray-600 truncate">{{ $message->metadata['sender_email'] }}</p>
                                        @endif
                                    </div>
                                @else
                                    <div class="h-8 w-8 rounded-full bg-gray-400 flex items-center justify-center text-white font-bold text-sm">
                                        ?
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-medium text-gray-900 text-sm">Utilisateur inconnu</h4>
                                        <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-xs rounded-full">
                                            Anonyme
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <!-- Sujet et contenu (aperçu) -->
                            <div class="flex-2 px-4 min-w-0">
                                <h3 class="font-medium text-gray-900 text-sm mb-1 truncate">{{ $message->subject }}</h3>
                                <p class="text-xs text-gray-600" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ Str::limit($message->content, 100) }}</p>
                            </div>

                            <!-- Métadonnées et actions -->
                            <div class="flex items-center space-x-4">
                                <!-- Date et motif -->
                                <div class="text-right">
                                    <div class="text-xs text-gray-500">{{ $message->created_at->format('d/m/Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $message->created_at->format('H:i') }}</div>
                                    @if($message->metadata && isset($message->metadata['contact_reason']))
                                        <div class="text-xs text-blue-600 font-medium">
                                            {{ ucfirst($message->metadata['contact_reason']) }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Badges de statut -->
                                <div class="flex flex-col items-end space-y-1">
                                    @if(!$message->read_at)
                                        <span class="px-2 py-0.5 bg-green-100 text-green-800 text-xs rounded-full">
                                            Nouveau
                                        </span>
                                    @endif
                                    
                                    @if($message->is_important)
                                        <span class="px-2 py-0.5 bg-red-100 text-red-800 text-xs rounded-full">
                                            Important
                                        </span>
                                    @endif

                                    @if($message->priority && $message->priority !== 'normal')
                                        <span class="px-2 py-0.5 text-xs rounded-full text-white
                                            {{ $message->priority === 'urgent' ? 'bg-red-500' : 
                                               ($message->priority === 'high' ? 'bg-orange-500' : 'bg-blue-500') }}">
                                            {{ ucfirst($message->priority) }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Actions -->
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.messages.show', $message) }}" 
                                       class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs font-medium transition-colors">
                                        Voir
                                    </a>
                                    
                                    @if($message->type === 'contact' && !($message->metadata['admin_responded'] ?? false))
                                        <button onclick="openResponseModal({{ $message->id }})" 
                                                class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs font-medium transition-colors">
                                            Répondre
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Version mobile -->
                        <div class="md:hidden">
                            <div class="flex items-start space-x-3 mb-3">
                                @if($message->sender)
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-green-500 to-blue-600 flex items-center justify-center text-white font-bold text-sm">
                                        {{ substr($message->sender->name ?: $message->sender->username, 0, 1) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center space-x-2 mb-1">
                                            <h4 class="font-medium text-gray-900 text-sm">
                                                {{ $message->sender->name ?: $message->sender->username }}
                                            </h4>
                                            <span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs rounded-full">
                                                Inscrit
                                            </span>
                                        </div>
                                        <p class="text-xs text-gray-600">{{ $message->sender->email }}</p>
                                    </div>
                                @elseif($message->metadata && isset($message->metadata['sender_name']))
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-orange-500 to-red-500 flex items-center justify-center text-white font-bold text-sm">
                                        {{ substr($message->metadata['sender_name'], 0, 1) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center space-x-2 mb-1">
                                            <h4 class="font-medium text-gray-900 text-sm">{{ $message->metadata['sender_name'] }}</h4>
                                            <span class="px-2 py-0.5 bg-orange-100 text-orange-700 text-xs rounded-full">
                                                Visiteur
                                            </span>
                                        </div>
                                        @if(isset($message->metadata['sender_email']))
                                            <p class="text-xs text-gray-600">{{ $message->metadata['sender_email'] }}</p>
                                        @endif
                                    </div>
                                @else
                                    <div class="h-10 w-10 rounded-full bg-gray-400 flex items-center justify-center text-white font-bold text-sm">
                                        ?
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-medium text-gray-900 text-sm">Utilisateur inconnu</h4>
                                        <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-xs rounded-full">
                                            Anonyme
                                        </span>
                                    </div>
                                @endif
                                
                                <div class="flex items-center space-x-1">
                                    @if(!$message->read_at)
                                        <span class="px-2 py-0.5 bg-green-100 text-green-800 text-xs rounded-full">
                                            Nouveau
                                        </span>
                                    @endif
                                    @if($message->is_important)
                                        <span class="px-2 py-0.5 bg-red-100 text-red-800 text-xs rounded-full">
                                            Important
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <h3 class="font-medium text-gray-900 text-sm mb-1">{{ $message->subject }}</h3>
                                <p class="text-xs text-gray-600">{{ Str::limit($message->content, 120) }}</p>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div class="text-xs text-gray-500">
                                    {{ $message->created_at->format('d/m/Y à H:i') }}
                                    @if($message->metadata && isset($message->metadata['contact_reason']))
                                        • {{ ucfirst($message->metadata['contact_reason']) }}
                                    @endif
                                </div>
                                
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.messages.show', $message) }}" 
                                       class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs font-medium">
                                        Voir
                                    </a>
                                    @if($message->type === 'contact' && !($message->metadata['admin_responded'] ?? false))
                                        <button onclick="openResponseModal({{ $message->id }})" 
                                                class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs font-medium">
                                            Répondre
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="p-8 text-center text-gray-500">
                <div class="text-4xl mb-4">📭</div>
                <h3 class="text-lg font-medium mb-2">Aucun message trouvé</h3>
                <p class="text-sm">Aucun message ne correspond aux critères de recherche.</p>
            </div>
        @endif
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $messages->links() }}
    </div>
</div>

<!-- Modale de réponse -->
<div id="responseModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-3/4 max-w-2xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Répondre au message</h3>
            
            <form id="responseForm">
                @csrf
                <input type="hidden" id="messageId" name="message_id">
                
                <div class="mb-4">
                    <label for="response" class="block text-sm font-medium text-gray-700 mb-2">
                        Votre réponse <span class="text-red-500">*</span>
                    </label>
                    <textarea id="response" name="response" rows="6" required 
                              placeholder="Rédigez votre réponse..."
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeResponseModal()" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md">
                        Annuler
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-md">
                        Envoyer la réponse
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openResponseModal(messageId) {
    document.getElementById('messageId').value = messageId;
    document.getElementById('responseModal').classList.remove('hidden');
}

function closeResponseModal() {
    document.getElementById('responseModal').classList.add('hidden');
    document.getElementById('responseForm').reset();
}

// Soumettre la réponse
document.getElementById('responseForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const messageId = document.getElementById('messageId').value;
    const response = document.getElementById('response').value;
    
    try {
        const result = await fetch(`/admin/messages/${messageId}/respond`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ response: response })
        });
        
        const data = await result.json();
        
        if (data.success) {
            alert('Réponse envoyée avec succès !');
            closeResponseModal();
            location.reload();
        } else {
            alert('Erreur: ' + data.message);
        }
    } catch (error) {
        alert('Erreur lors de l\'envoi de la réponse');
        console.error(error);
    }
});

// Fermer la modale en cliquant à l'extérieur
document.getElementById('responseModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeResponseModal();
    }
});
</script>
@endsection
