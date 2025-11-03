@extends('layouts.admin')

@section('title', 'Détails de la newsletter - Dashboard Admin')
@section('page-title', 'Détails de la newsletter')

@push('styles')
<style>
    /* Amélioration de la lisibilité sur petits écrans */
    @media (max-width: 1024px) {
        .container {
            max-width: 100%;
        }
    }
    
    /* Éviter les débordements de texte */
    .line-clamp-1 {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    /* Améliorer les boutons sur mobile */
    @media (max-width: 640px) {
        button, a.inline-flex {
            font-size: 0.875rem;
            padding: 0.5rem 0.75rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-2 sm:px-4 py-4 sm:py-8">
    <!-- En-tête -->
    <div class="mb-8">
        <div class="bg-gradient-to-r from-purple-600 to-blue-600 rounded-xl p-4 md:p-6 text-white shadow-lg">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex-1">
                    <h1 class="text-xl md:text-2xl lg:text-3xl font-bold flex items-center">
                        <svg class="w-6 h-6 md:w-8 md:h-8 mr-2 md:mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="line-clamp-2">{{ $newsletter->title }}</span>
                    </h1>
                    <p class="mt-2 text-purple-100 text-sm md:text-base line-clamp-1">
                        {{ $newsletter->subject }}
                    </p>
                </div>
                <div class="flex flex-wrap gap-2 lg:flex-shrink-0">
                    <a href="{{ route('admin.newsletters.index') }}" 
                       class="inline-flex items-center justify-center bg-white/20 hover:bg-white/30 text-white px-3 md:px-4 py-2 rounded-lg transition-colors text-sm whitespace-nowrap">
                        ← Retour
                    </a>
                    <a href="{{ route('admin.newsletters.preview', $newsletter) }}" 
                       target="_blank"
                       class="inline-flex items-center justify-center bg-blue-500/90 hover:bg-blue-600 text-white px-3 md:px-4 py-2 rounded-lg transition-colors text-sm whitespace-nowrap">
                        👁️ Aperçu
                    </a>
                    @if($newsletter->status != 'sent')
                    <a href="{{ route('admin.newsletters.edit', $newsletter) }}" 
                       class="inline-flex items-center justify-center bg-white/20 hover:bg-white/30 text-white px-3 md:px-4 py-2 rounded-lg transition-colors text-sm whitespace-nowrap">
                        ✏️ Modifier
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Statut et statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Statut -->
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Statut</h3>
            <div class="flex items-center justify-between">
                @if($newsletter->status == 'draft')
                    <span class="bg-yellow-100 text-yellow-800 px-4 py-2 rounded-full text-sm font-medium">
                        Brouillon
                    </span>
                @elseif($newsletter->status == 'scheduled')
                    <span class="bg-blue-100 text-blue-800 px-4 py-2 rounded-full text-sm font-medium">
                        Programmée
                    </span>
                @elseif($newsletter->status == 'sent')
                    <span class="bg-green-100 text-green-800 px-4 py-2 rounded-full text-sm font-medium">
                        Envoyée
                    </span>
                @endif
            </div>
            
            @if($newsletter->sent_at)
                <div class="mt-4 text-sm text-gray-600">
                    <strong>Envoyée le:</strong><br>
                    {{ $newsletter->sent_at->format('d/m/Y à H:i') }}
                </div>
            @elseif($newsletter->scheduled_at)
                <div class="mt-4 text-sm text-gray-600">
                    <strong>Programmée pour:</strong><br>
                    {{ $newsletter->scheduled_at->format('d/m/Y à H:i') }}
                </div>
            @endif
        </div>

        <!-- Statistiques d'envoi -->
        @if($newsletter->status == 'sent')
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistiques d'envoi</h3>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Envoyés:</span>
                    <span class="text-sm font-medium">{{ $newsletter->newsletterSends ? $newsletter->newsletterSends->count() : 0 }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Succès:</span>
                    <span class="text-sm font-medium text-green-600">
                        {{ $newsletter->newsletterSends ? $newsletter->newsletterSends->where('status', 'sent')->count() : 0 }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Erreurs:</span>
                    <span class="text-sm font-medium text-red-600">
                        {{ $newsletter->newsletterSends ? $newsletter->newsletterSends->where('status', 'failed')->count() : 0 }}
                    </span>
                </div>
            </div>
        </div>
        @endif

        <!-- Dates -->
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informations</h3>
            <div class="space-y-2 text-sm">
                <div>
                    <span class="text-gray-600">Créée le:</span><br>
                    <span class="font-medium">{{ $newsletter->created_at->format('d/m/Y à H:i') }}</span>
                </div>
                <div>
                    <span class="text-gray-600">Modifiée le:</span><br>
                    <span class="font-medium">{{ $newsletter->updated_at->format('d/m/Y à H:i') }}</span>
                </div>
                @if($newsletter->tags)
                <div>
                    <span class="text-gray-600">Tags:</span><br>
                    <div class="flex flex-wrap gap-1 mt-1">
                        @foreach($newsletter->tags as $tag)
                            <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">{{ $tag }}</span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Contenu -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Informations détaillées -->
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Informations détaillées</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Titre</label>
                    <div class="text-gray-900">{{ $newsletter->title }}</div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sujet de l'email</label>
                    <div class="text-gray-900">{{ $newsletter->subject }}</div>
                </div>
                
                @if($newsletter->excerpt)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Extrait</label>
                    <div class="text-gray-900">{{ $newsletter->excerpt }}</div>
                </div>
                @endif
                
                @if($newsletter->featured_image)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Image de couverture</label>
                    <img src="{{ asset('storage/' . $newsletter->featured_image) }}" 
                         alt="Image de couverture" 
                         class="w-full max-w-sm h-32 object-cover rounded border">
                </div>
                @endif
            </div>
        </div>

        <!-- Aperçu du contenu -->
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Aperçu du contenu</h3>
            
            <div class="border rounded-lg p-4 max-h-96 overflow-y-auto bg-gray-50">
                {!! $newsletter->content !!}
            </div>
            
            <div class="mt-4 flex flex-wrap gap-2">
                <button onclick="previewNewsletter()" 
                        class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white px-3 md:px-4 py-2 rounded-lg text-sm transition-colors whitespace-nowrap">
                    👁️ Aperçu complet
                </button>
                
                @if($newsletter->status != 'sent')
                <button onclick="sendTestEmail()" 
                        class="inline-flex items-center justify-center bg-green-600 hover:bg-green-700 text-white px-3 md:px-4 py-2 rounded-lg text-sm transition-colors whitespace-nowrap">
                    Envoyer un test
                </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Historique des envois -->
    @if($newsletter->status == 'sent' && $newsletter->newsletterSends->count() > 0)
    <div class="mt-8 bg-white rounded-lg border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Historique des envois</h3>
        
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Destinataire
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Statut
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Envoyé le
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Erreur
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($newsletter->newsletterSends->take(50) as $send)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $send->email }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($send->status == 'sent')
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Envoyé
                                </span>
                            @elseif($send->status == 'failed')
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    Échec
                                </span>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    En attente
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $send->sent_at ? $send->sent_at->format('d/m/Y H:i') : '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-red-600">
                            {{ $send->error_message ?? '-' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if($newsletter->newsletterSends->count() > 50)
        <div class="mt-4 text-sm text-gray-500">
            Affichage de 50 envois sur {{ $newsletter->newsletterSends->count() }} au total.
        </div>
        @endif
    </div>
    @endif

    <!-- Actions -->
    <div class="mt-8 flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">
        <div class="flex flex-wrap gap-2 md:gap-3">
            @if($newsletter->status != 'sent')
                <a href="{{ route('admin.newsletters.edit', $newsletter) }}" 
                   class="inline-flex items-center justify-center bg-purple-600 hover:bg-purple-700 text-white px-4 md:px-6 py-2 md:py-3 rounded-lg transition-colors text-sm md:text-base whitespace-nowrap">
                    ✏️ Modifier
                </a>
                
                @if($newsletter->status == 'draft')
                <form method="POST" action="{{ route('admin.newsletters.update', $newsletter) }}" class="inline">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="action" value="send_now">
                    <button type="submit" 
                            class="inline-flex items-center justify-center bg-green-600 hover:bg-green-700 text-white px-4 md:px-6 py-2 md:py-3 rounded-lg transition-colors text-sm md:text-base whitespace-nowrap"
                            onclick="return confirm('Êtes-vous sûr de vouloir envoyer cette newsletter à TOUS les abonnés ?')">
                        🚀 Envoyer maintenant
                    </button>
                </form>
                
                <form method="POST" action="{{ route('admin.newsletters.send-to-me', $newsletter) }}" class="inline">
                    @csrf
                    <button type="submit" 
                            class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white px-4 md:px-6 py-2 md:py-3 rounded-lg transition-colors text-sm md:text-base whitespace-nowrap"
                            onclick="return confirm('Envoyer cette newsletter uniquement à votre adresse ?')">
                        📧 Test
                    </button>
                </form>
                @elseif($newsletter->status == 'scheduled')
                <form method="POST" action="{{ route('admin.newsletters.send', $newsletter) }}" class="inline">
                    @csrf
                    <button type="submit" 
                            class="inline-flex items-center justify-center bg-orange-600 hover:bg-orange-700 text-white px-4 md:px-6 py-2 md:py-3 rounded-lg transition-colors text-sm md:text-base whitespace-nowrap"
                            onclick="return confirm('Forcer l\'envoi immédiat de cette newsletter programmée ?')">
                        ⚡ Forcer l'envoi
                    </button>
                </form>
                @endif
            @else
                <!-- Newsletter déjà envoyée - Option de renvoi -->
                <form method="POST" action="{{ route('admin.newsletters.resend', $newsletter) }}" class="inline">
                    @csrf
                    <button type="submit" 
                            class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white px-4 md:px-6 py-2 md:py-3 rounded-lg transition-colors text-sm md:text-base whitespace-nowrap"
                            onclick="return confirm('⚠️ Renvoyer cette newsletter à TOUS les abonnés actuels ?\n\nCela créera de nouveaux envois pour tous les abonnés actifs.')">
                        🔄 Renvoyer
                    </button>
                </form>
            @endif
        </div>
        
        <form method="POST" action="{{ route('admin.newsletters.destroy', $newsletter) }}" class="inline w-full lg:w-auto">
            @csrf
            @method('DELETE')
            <button type="submit" 
                    class="inline-flex items-center justify-center w-full lg:w-auto bg-red-600 hover:bg-red-700 text-white px-4 md:px-6 py-2 md:py-3 rounded-lg transition-colors text-sm md:text-base whitespace-nowrap"
                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette newsletter ? Cette action est irréversible.')">
                🗑️ Supprimer
            </button>
        </form>
    </div>
</div>

<!-- Modal pour aperçu -->
<div id="previewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-4xl w-full max-h-screen overflow-hidden">
            <div class="flex justify-between items-center p-6 border-b">
                <h3 class="text-lg font-semibold">Aperçu de la newsletter</h3>
                <button onclick="closePreview()" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="p-6 overflow-y-auto max-h-96">
                <iframe id="previewFrame" class="w-full h-96 border-0"></iframe>
            </div>
        </div>
    </div>
</div>

<script>
function previewNewsletter() {
    const modal = document.getElementById('previewModal');
    const frame = document.getElementById('previewFrame');
    
    // Créer le contenu HTML complet pour l'aperçu
    const content = `
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>{{ $newsletter->title }}</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                .header { border-bottom: 1px solid #eee; padding-bottom: 20px; margin-bottom: 20px; }
                .content { line-height: 1.6; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>{{ $newsletter->title }}</h1>
                <p><strong>Sujet:</strong> {{ $newsletter->subject }}</p>
                @if($newsletter->excerpt)
                <p><em>{{ $newsletter->excerpt }}</em></p>
                @endif
            </div>
            <div class="content">
                {!! $newsletter->content !!}
            </div>
        </body>
        </html>
    `;
    
    frame.srcdoc = content;
    modal.classList.remove('hidden');
}

function closePreview() {
    document.getElementById('previewModal').classList.add('hidden');
}

function sendTestEmail() {
    const email = prompt('Adresse email pour le test:');
    if (email) {
        fetch('{{ route("admin.newsletters.test", $newsletter) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ email: email })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Email de test envoyé avec succès !');
            } else {
                alert('Erreur lors de l\'envoi: ' + data.message);
            }
        })
        .catch(error => {
            alert('Erreur lors de l\'envoi du test');
        });
    }
}
</script>
@endsection
