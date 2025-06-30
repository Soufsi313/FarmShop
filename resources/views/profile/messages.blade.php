@extends('layouts.public')

@section('title', 'Mes Messages')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-emerald-100 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-sm border border-green-100 mb-8 overflow-hidden">
            <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-8 py-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-white">Mes Messages</h1>
                        <p class="text-green-100 mt-2">Consultez vos échanges avec l'administration</p>
                    </div>
                    <a href="{{ route('profile.show') }}" 
                       class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 text-white rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Retour au profil
                    </a>
                </div>
            </div>
        </div>

        <!-- Messages -->
        @if($messages->count() > 0)
            <div class="space-y-4">
                @foreach($messages as $message)
                    <div class="bg-white rounded-xl shadow-sm border border-green-100 overflow-hidden hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $message->subject }}</h3>
                                        
                                        <!-- Badge statut -->
                                        @if($message->status === 'pending')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                En attente
                                            </span>
                                        @elseif($message->status === 'in_progress')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                En cours
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Résolu
                                            </span>
                                        @endif

                                        <!-- Badge non lu -->
                                        @if($message->replies->count() > 0 && !$message->is_read_by_user)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Nouvelle réponse
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <p class="text-gray-600 mb-3 line-clamp-2">{{ Str::limit($message->message, 150) }}</p>
                                    
                                    <div class="flex items-center text-sm text-gray-500 space-x-4">
                                        <span>{{ $message->created_at->format('d/m/Y à H:i') }}</span>
                                        @if($message->replies->count() > 0)
                                            <span>{{ $message->replies->count() }} réponse(s)</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="ml-4">
                                    <a href="{{ route('user.messages.show', $message) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                        </svg>
                                        Voir
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Pagination -->
                <div class="bg-white rounded-xl shadow-sm border border-green-100 p-6">
                    {{ $messages->links() }}
                </div>
            </div>
        @else
            <!-- Aucun message -->
            <div class="bg-white rounded-2xl shadow-sm border border-green-100 p-12 text-center">
                <div class="max-w-md mx-auto">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun message</h3>
                    <p class="text-gray-600 mb-6">Vous n'avez pas encore envoyé de message à l'administration.</p>
                    <a href="{{ route('profile.show') }}" 
                       class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        Envoyer un message
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
