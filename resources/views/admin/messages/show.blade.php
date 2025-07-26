@extends('layouts.admin')

@section('title', 'Détails du message')

@section('page-title', 'Détails du message')

@section('content')
@php
    // Décoder les métadonnées JSON une seule fois au début
    $metadata = is_string($message->metadata) ? json_decode($message->metadata, true) : $message->metadata;
    $metadata = $metadata ?? [];
@endphp

<div class="container mx-auto px-4 py-8">
    <!-- Bouton retour -->
    <div class="mb-6">
        <a href="{{ route('admin.messages.index') }}" 
           class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors duration-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Retour aux messages
        </a>
    </div>

    <!-- Carte détaillée du message -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- En-tête du message -->
        <div class="border-b border-gray-200 px-8 py-6 bg-gradient-to-r from-blue-50 to-indigo-50">
            <div class="flex justify-between items-start">
                <div class="flex items-center space-x-6">
                    @if($message->sender)
                        @php
                            // Vérifier si c'est Mr Clank (utilisateur système)
                            $isSystemMessage = $message->sender->email === 'system@farmshop.local' || 
                                             ($metadata && isset($metadata['system_sender']) && $metadata['system_sender']);
                        @endphp
                        
                        @if($isSystemMessage)
                            <!-- Avatar pour Mr Clank (système) -->
                            <div class="h-16 w-16 rounded-full bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white font-bold text-xl shadow-lg">
                                🤖
                            </div>
                            <div>
                                <div class="flex items-center space-x-3 mb-2">
                                    <h2 class="text-2xl font-bold text-gray-900">{{ $message->sender->name }}</h2>
                                    <span class="px-3 py-1 bg-purple-100 text-purple-700 text-sm rounded-full font-medium">
                                        Système Automatique
                                    </span>
                                </div>
                                <p class="text-gray-600 mb-1">🔔 Message automatique du système</p>
                                <p class="text-sm text-gray-500">Généré le {{ $message->created_at->format('d/m/Y à H:i') }}</p>
                            </div>
                        @else
                            <!-- Avatar de l'utilisateur connecté normal -->
                            <div class="h-16 w-16 rounded-full bg-gradient-to-br from-green-500 to-blue-600 flex items-center justify-center text-white font-bold text-xl shadow-lg">
                                {{ substr($message->sender->name ?: $message->sender->username, 0, 1) }}
                            </div>
                            <div>
                                <div class="flex items-center space-x-3 mb-2">
                                    <h2 class="text-2xl font-bold text-gray-900">
                                        {{ $message->sender->name ?: $message->sender->username }}
                                    </h2>
                                    <span class="px-3 py-1 bg-green-100 text-green-700 text-sm rounded-full font-medium">
                                        Utilisateur inscrit
                                    </span>
                                </div>
                                <p class="text-gray-600 mb-1">{{ $message->sender->email }}</p>
                                <p class="text-sm text-gray-500">Membre depuis le {{ $message->sender->created_at->format('d/m/Y') }}</p>
                            </div>
                        @endif
                    @elseif($metadata && isset($metadata['sender_name']))
                        <!-- Avatar pour les contacts non-inscrits -->
                        <div class="h-16 w-16 rounded-full bg-gradient-to-br from-orange-500 to-red-500 flex items-center justify-center text-white font-bold text-xl shadow-lg">
                            {{ substr($metadata['sender_name'], 0, 1) }}
                        </div>
                        <div>
                            <div class="flex items-center space-x-3 mb-2">
                                <h2 class="text-2xl font-bold text-gray-900">{{ $metadata['sender_name'] }}</h2>
                                <span class="px-3 py-1 bg-orange-100 text-orange-700 text-sm rounded-full font-medium">
                                    Visiteur
                                </span>
                                @if(isset($metadata['migrated_from_contacts']) && $metadata['migrated_from_contacts'])
                                    <span class="px-3 py-1 bg-purple-100 text-purple-700 text-sm rounded-full font-medium">
                                        Migré depuis contacts
                                    </span>
                                @endif
                            </div>
                            @if(isset($metadata['sender_email']))
                                <p class="text-gray-600 mb-1">📧 {{ $metadata['sender_email'] }}</p>
                            @endif
                            @if(isset($metadata['sender_phone']))
                                <p class="text-gray-600 mb-1">📞 {{ $metadata['sender_phone'] }}</p>
                            @endif
                        </div>
                    @else
                        <!-- Avatar pour les utilisateurs inconnus -->
                        <div class="h-16 w-16 rounded-full bg-gray-400 flex items-center justify-center text-white font-bold text-xl shadow-lg">
                            ?
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Utilisateur inconnu</h2>
                            <span class="px-3 py-1 bg-gray-100 text-gray-600 text-sm rounded-full font-medium">
                                Anonyme
                            </span>
                        </div>
                    @endif
                </div>
                
                <!-- Badges de statut -->
                <div class="flex flex-col items-end space-y-3">
                    <div class="flex items-center space-x-2">
                        @if(!$message->read_at)
                            <span class="px-4 py-2 bg-green-100 text-green-800 text-sm rounded-full font-medium animate-pulse">
                                ● Nouveau message
                            </span>
                        @else
                            <span class="px-4 py-2 bg-gray-100 text-gray-600 text-sm rounded-full font-medium">
                                ✓ Lu le {{ $message->read_at->format('d/m/Y à H:i') }}
                            </span>
                        @endif
                        
                        @if($message->is_important)
                            <span class="px-4 py-2 bg-red-100 text-red-800 text-sm rounded-full font-medium">
                                ⚠️ Important
                            </span>
                        @endif
                    </div>
                    
                    @if($message->priority && $message->priority !== 'normal')
                        <span class="px-4 py-2 text-sm rounded-full font-medium
                            {{ $message->priority === 'urgent' ? 'bg-red-500 text-white' : 
                               ($message->priority === 'high' ? 'bg-orange-500 text-white' : 
                               'bg-blue-500 text-white') }}">
                            @if($message->priority === 'urgent')
                                🚨 Priorité Urgente
                            @elseif($message->priority === 'high')
                                ⚡ Priorité Haute
                            @else
                                📌 Priorité {{ ucfirst($message->priority) }}
                            @endif
                        </span>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Informations du message -->
        <div class="px-8 py-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Date d'envoi</h3>
                    <p class="text-lg font-semibold text-gray-900">
                        {{ $message->created_at->format('d/m/Y à H:i') }}
                    </p>
                    <p class="text-sm text-gray-500">
                        {{ $message->created_at->diffForHumans() }}
                    </p>
                </div>
                
                @if($metadata && isset($metadata['contact_reason']))
                    <div class="bg-blue-50 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Motif du contact</h3>
                        <p class="text-lg font-semibold text-blue-900">
                            {{ ucfirst($metadata['contact_reason']) }}
                        </p>
                    </div>
                @endif
                
                <div class="bg-purple-50 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Type de message</h3>
                    <p class="text-lg font-semibold text-purple-900">
                        {{ $message->type === 'contact' ? 'Message de contact' : 'Message système' }}
                    </p>
                    @if($metadata && isset($metadata['original_contact_id']))
                        <p class="text-sm text-purple-600">
                            Réf: #{{ $metadata['original_contact_id'] }}
                        </p>
                    @endif
                </div>
            </div>
            
            <!-- Sujet et contenu -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-6">{{ $message->subject }}</h1>
                
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Contenu du message</h3>
                    <div class="prose max-w-none text-gray-700 leading-relaxed">
                        {!! nl2br(e($message->content)) !!}
                    </div>
                </div>
            </div>
            
            <!-- Métadonnées supplémentaires -->
            @if($metadata && is_array($metadata) && count($metadata) > 0)
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Informations supplémentaires</h3>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($metadata as $key => $value)
                                @if($key !== 'contact_reason' && $key !== 'sender_name' && $key !== 'sender_email' && $key !== 'sender_phone')
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">{{ ucfirst(str_replace('_', ' ', $key)) }}</dt>
                                        <dd class="text-sm text-gray-900">{{ is_bool($value) ? ($value ? 'Oui' : 'Non') : $value }}</dd>
                                    </div>
                                @endif
                            @endforeach
                        </dl>
                    </div>
                </div>
            @endif
            
            <!-- Actions -->
            <div class="border-t border-gray-200 pt-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        @if(!$message->read_at)
                            <button onclick="markAsRead({{ $message->id }})" 
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Marquer comme lu
                            </button>
                        @endif
                        
                        @if($message->type === 'contact' && !($metadata['admin_responded'] ?? false))
                            <button onclick="openResponseModal({{ $message->id }})" 
                                    class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                </svg>
                                Répondre au message
                            </button>
                        @endif
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <button onclick="archiveMessage({{ $message->id }})" 
                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8l14 M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                            </svg>
                            Archiver
                        </button>
                        
                        <button onclick="deleteMessage({{ $message->id }})" 
                                class="bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Supprimer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modale de réponse -->
<div id="responseModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-3/4 max-w-2xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Répondre au message</h3>
            
            <form id="responseForm">
                @csrf
                <input type="hidden" id="messageId" name="message_id" value="{{ $message->id }}">
                
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
function markAsRead(messageId) {
    fetch(`/admin/messages/${messageId}/mark-read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Erreur lors du marquage comme lu');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur de connexion');
    });
}

function archiveMessage(messageId) {
    if (confirm('Êtes-vous sûr de vouloir archiver ce message ?')) {
        fetch(`/admin/messages/${messageId}/archive`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Message archivé avec succès');
                window.location.href = '/admin/messages';
            } else {
                alert('Erreur lors de l\'archivage');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur de connexion');
        });
    }
}

function deleteMessage(messageId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer définitivement ce message ?')) {
        fetch(`/admin/messages/${messageId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Message supprimé avec succès');
                window.location.href = '/admin/messages';
            } else {
                alert('Erreur lors de la suppression');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur de connexion');
        });
    }
}

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
    
    // Validation côté client
    if (!response || response.trim().length < 10) {
        alert('La réponse doit contenir au moins 10 caractères.');
        return;
    }
    
    if (response.length > 5000) {
        alert('La réponse ne peut pas dépasser 5000 caractères.');
        return;
    }
    
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
            let errorMessage = 'Erreur: ' + data.message;
            if (data.errors) {
                errorMessage += '\n\nDétails:';
                for (let field in data.errors) {
                    errorMessage += '\n- ' + data.errors[field].join(', ');
                }
            }
            alert(errorMessage);
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
