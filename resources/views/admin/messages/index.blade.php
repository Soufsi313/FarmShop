@extends('layouts.admin')

@section('title', 'Messages')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Messages reçus</h1>
        <div class="text-sm text-gray-600">
            Total: {{ $messages->total() }} messages
        </div>
    </div>

    <div class="grid gap-6">
        @foreach($messages as $message)
            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow duration-200">
                <!-- En-tête du message avec informations de l'auteur -->
                <div class="border-b border-gray-100 px-6 py-4 bg-gray-50">
                    <div class="flex justify-between items-start">
                        <div class="flex items-center space-x-4 flex-1">
                            @if($message->sender)
                                <!-- Avatar de l'utilisateur connecté -->
                                <div class="h-12 w-12 rounded-full bg-gradient-to-br from-green-500 to-blue-600 flex items-center justify-center text-white font-bold text-lg shadow-md">
                                    {{ substr($message->sender->name ?: $message->sender->username, 0, 1) }}
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2 mb-1">
                                        <h4 class="font-semibold text-gray-900 text-lg">
                                            {{ $message->sender->name ?: $message->sender->username }}
                                        </h4>
                                        <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full font-medium">
                                            Utilisateur inscrit
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-1">{{ $message->sender->email }}</p>
                                    <div class="flex items-center space-x-4 text-xs text-gray-500">
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ $message->created_at->format('d/m/Y à H:i') }}
                                        </span>
                                        @if($message->metadata && isset($message->metadata['contact_reason']))
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v14l-4-4z"/>
                                                </svg>
                                                Motif: {{ ucfirst($message->metadata['contact_reason']) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @elseif($message->metadata && isset($message->metadata['sender_name']))
                                <!-- Avatar pour les contacts non-inscrits -->
                                <div class="h-12 w-12 rounded-full bg-gradient-to-br from-orange-500 to-red-500 flex items-center justify-center text-white font-bold text-lg shadow-md">
                                    {{ substr($message->metadata['sender_name'], 0, 1) }}
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2 mb-1">
                                        <h4 class="font-semibold text-gray-900 text-lg">{{ $message->metadata['sender_name'] }}</h4>
                                        <span class="px-2 py-1 bg-orange-100 text-orange-700 text-xs rounded-full font-medium">
                                            Visiteur
                                        </span>
                                        @if(isset($message->metadata['migrated_from_contacts']) && $message->metadata['migrated_from_contacts'])
                                            <span class="px-2 py-1 bg-purple-100 text-purple-700 text-xs rounded-full font-medium">
                                                Migré
                                            </span>
                                        @endif
                                    </div>
                                    @if(isset($message->metadata['sender_email']))
                                        <p class="text-sm text-gray-600 mb-1">{{ $message->metadata['sender_email'] }}</p>
                                    @endif
                                    @if(isset($message->metadata['sender_phone']))
                                        <p class="text-sm text-gray-600 mb-1">📞 {{ $message->metadata['sender_phone'] }}</p>
                                    @endif
                                    <div class="flex items-center space-x-4 text-xs text-gray-500">
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ $message->created_at->format('d/m/Y à H:i') }}
                                        </span>
                                        @if(isset($message->metadata['contact_reason']))
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v14l-4-4z"/>
                                                </svg>
                                                Motif: {{ ucfirst($message->metadata['contact_reason']) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <!-- Avatar pour les utilisateurs inconnus -->
                                <div class="h-12 w-12 rounded-full bg-gray-400 flex items-center justify-center text-white font-bold text-lg shadow-md">
                                    ?
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2 mb-1">
                                        <h4 class="font-semibold text-gray-900 text-lg">Utilisateur inconnu</h4>
                                        <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-full font-medium">
                                            Anonyme
                                        </span>
                                    </div>
                                    <div class="flex items-center space-x-4 text-xs text-gray-500">
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ $message->created_at->format('d/m/Y à H:i') }}
                                        </span>
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Badges de statut et priorité -->
                        <div class="flex flex-col items-end space-y-2">
                            <div class="flex items-center space-x-2">
                                @if(!$message->read_at)
                                    <span class="px-3 py-1 bg-green-100 text-green-800 text-sm rounded-full font-medium animate-pulse">
                                        ● Nouveau
                                    </span>
                                @endif
                                
                                @if($message->is_important)
                                    <span class="px-3 py-1 bg-red-100 text-red-800 text-sm rounded-full font-medium">
                                        ⚠️ Important
                                    </span>
                                @endif
                            </div>
                            
                            @if($message->priority && $message->priority !== 'normal')
                                <span class="px-3 py-1 text-sm rounded-full font-medium
                                    {{ $message->priority === 'urgent' ? 'bg-red-500 text-white' : 
                                       ($message->priority === 'high' ? 'bg-orange-500 text-white' : 
                                       'bg-blue-500 text-white') }}">
                                    @if($message->priority === 'urgent')
                                        🚨 Urgent
                                    @elseif($message->priority === 'high')
                                        ⚡ Haute
                                    @else
                                        📌 {{ ucfirst($message->priority) }}
                                    @endif
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Contenu du message -->
                <div class="px-6 py-4">
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ $message->subject }}</h3>
                    
                    <div class="text-gray-700 mb-4 leading-relaxed">
                        {!! nl2br(e($message->content)) !!}
                    </div>
                    
                    <!-- Actions -->
                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                        <div class="flex items-center space-x-3">
                            <span class="text-sm text-gray-500">
                                Message {{ $message->type === 'contact' ? 'de contact' : 'système' }}
                            </span>
                            @if($message->metadata && isset($message->metadata['original_contact_id']))
                                <span class="text-xs text-purple-600 bg-purple-50 px-2 py-1 rounded">
                                    Réf: #{{ $message->metadata['original_contact_id'] }}
                                </span>
                            @endif
                        </div>
                        
                        <div class="flex space-x-3">
                            <a href="{{ route('admin.messages.show', $message) }}" 
                               class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Voir détails
                            </a>
                            
                            @if($message->type === 'contact' && !($message->metadata['admin_responded'] ?? false))
                                <button onclick="openResponseModal({{ $message->id }})" 
                                        class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                    </svg>
                                    Répondre
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
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
