@extends('layouts.admin')

@section('title', 'Gestion des Signalements')
@section('page-title', 'Gestion des Signalements')

@section('content')
<!-- Messages flash -->
@if(session('success'))
    <div class="rounded-md bg-green-50 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="rounded-md bg-red-50 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
            </div>
        </div>
    </div>
@endif

<div class="space-y-6">
    <!-- En-tête avec actions -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Gestion des Signalements</h1>
                    <p class="mt-1 text-sm text-gray-600">
                        Examinez et traitez les signalements de commentaires
                    </p>
                </div>
                <div class="mt-4 flex space-x-3 sm:mt-0">
                    <a href="{{ request()->fullUrl() }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Actualiser
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total signalements</dt>
                            <dd class="text-2xl font-semibold text-gray-900">{{ $stats['total_reports'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">En attente</dt>
                            <dd class="text-2xl font-semibold text-gray-900">{{ $stats['pending_reports'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Résolus</dt>
                            <dd class="text-2xl font-semibold text-gray-900">{{ $stats['resolved_reports'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Rejetés</dt>
                            <dd class="text-2xl font-semibold text-gray-900">{{ $stats['dismissed_reports'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des signalements -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Signalements</h3>
        </div>
        <div>
            @if($reports->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Signalement</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Commentaire</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($reports as $report)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ ucfirst(str_replace('_', ' ', $report->reason)) }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    par {{ $report->reporter->name ?? 'Utilisateur supprimé' }}
                                                </div>
                                                <div class="text-xs text-gray-400">
                                                    {{ $report->created_at->format('d/m/Y H:i') }}
                                                </div>
                                                @if($report->description)
                                                    <div class="text-xs text-gray-500 mt-1 max-w-xs truncate">
                                                        "{{ $report->description }}"
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($report->comment)
                                            <div class="text-sm text-gray-900">
                                                <div class="max-w-xs">
                                                    <p class="line-clamp-3">{{ $report->comment->content }}</p>
                                                </div>
                                                <div class="text-xs text-gray-500 mt-1">
                                                    par {{ $report->comment->user->name ?? 'Utilisateur supprimé' }}
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-sm text-gray-400 italic">Commentaire supprimé</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @switch($report->status)
                                            @case('pending')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    ⏳ En attente
                                                </span>
                                                @break
                                            @case('resolved')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    ✅ Résolu
                                                </span>
                                                @break
                                            @case('dismissed')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    ❌ Rejeté
                                                </span>
                                                @break
                                        @endswitch
                                        
                                        @if($report->action_taken && $report->action_taken !== 'none')
                                            <div class="text-xs text-gray-500 mt-1">
                                                @switch($report->action_taken)
                                                    @case('comment_deleted')
                                                        🗑️ Commentaire supprimé
                                                        @break
                                                    @case('comment_hidden')
                                                        ⚠️ Commentaire masqué
                                                        @break
                                                    @case('warning_sent')
                                                        📧 Avertissement envoyé
                                                        @break
                                                @endswitch
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        @if($report->status === 'pending')
                                            <button onclick="document.getElementById('process-modal-{{ $report->id }}').classList.remove('hidden')"
                                                    class="text-orange-600 hover:text-orange-900 mr-3">
                                                🎯 Traiter
                                            </button>
                                        @endif
                                        <button onclick="document.getElementById('detail-modal-{{ $report->id }}').classList.remove('hidden')" 
                                                class="text-blue-600 hover:text-blue-900">
                                            👁️ Détails
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $reports->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun signalement</h3>
                    <p class="mt-1 text-sm text-gray-500">Il n'y a aucun signalement à traiter pour le moment.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modals pour traiter chaque signalement -->
@foreach($reports as $report)
@if($report->status === 'pending')
<div id="process-modal-{{ $report->id }}" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-lg w-full m-4 max-h-[90vh] overflow-y-auto">
        <h3 class="text-lg font-semibold mb-4">Traiter le signalement #{{ $report->id }}</h3>
        
        <div class="mb-4 p-3 bg-gray-50 rounded">
            <strong>Commentaire signalé :</strong><br>
            <em class="block mt-1">"{{ $report->comment->content ?? 'Commentaire supprimé' }}"</em><br>
            <small class="text-gray-600">Par {{ $report->comment->user->name ?? 'Utilisateur supprimé' }}</small>
        </div>
        
        <div class="mb-4 p-3 bg-red-50 rounded">
            <strong>Raison :</strong> {{ ucfirst(str_replace('_', ' ', $report->reason)) }}<br>
            @if($report->description)
                <strong>Description :</strong><br>
                <em class="block mt-1">"{{ $report->description }}"</em><br>
            @endif
            <small class="text-gray-600">Signalé par {{ $report->reporter->name ?? 'Utilisateur supprimé' }}</small>
        </div>

        <div class="space-y-3">
            <!-- Modérer (approuver le signalement et masquer le commentaire) -->
            <button onclick="processReport({{ $report->id }}, 'moderate')" 
                    class="w-full bg-orange-500 text-white px-4 py-3 rounded text-sm hover:bg-orange-600 flex items-center justify-center">
                🛡️ Modérer le commentaire
                <span class="ml-2 text-xs opacity-75">(masquer mais conserver)</span>
            </button>

            <!-- Supprimer -->
            <button onclick="if(confirm('Êtes-vous sûr de vouloir supprimer définitivement ce commentaire ?')) processReport({{ $report->id }}, 'delete')" 
                    class="w-full bg-red-500 text-white px-4 py-3 rounded text-sm hover:bg-red-600 flex items-center justify-center">
                🗑️ Supprimer définitivement
                <span class="ml-2 text-xs opacity-75">(irréversible)</span>
            </button>

            <!-- Rejeter le signalement -->
            <button onclick="processReport({{ $report->id }}, 'dismiss')" 
                    class="w-full bg-gray-500 text-white px-4 py-3 rounded text-sm hover:bg-gray-600 flex items-center justify-center">
                ❌ Rejeter le signalement
                <span class="ml-2 text-xs opacity-75">(conserver le commentaire)</span>
            </button>

            <!-- Fermer -->
            <button onclick="document.getElementById('process-modal-{{ $report->id }}').classList.add('hidden')"
                    class="w-full bg-gray-300 text-gray-700 px-4 py-3 rounded text-sm hover:bg-gray-400">
                Annuler
            </button>
        </div>
    </div>
</div>
@endif

<!-- Modal de détails pour chaque signalement -->
<div id="detail-modal-{{ $report->id }}" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-2xl w-full m-4 max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Détails du signalement #{{ $report->id }}</h3>
            <button onclick="document.getElementById('detail-modal-{{ $report->id }}').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <div class="space-y-6">
            <!-- Info signalement -->
            <div>
                <h4 class="font-medium text-gray-900 mb-2">Informations du signalement</h4>
                <div class="bg-gray-50 p-4 rounded">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-sm font-medium text-gray-500">Raison :</span>
                            <p class="text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $report->reason)) }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Statut :</span>
                            <p class="text-sm text-gray-900">
                                @switch($report->status)
                                    @case('pending') En attente @break
                                    @case('resolved') Résolu @break  
                                    @case('dismissed') Rejeté @break
                                @endswitch
                            </p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Date :</span>
                            <p class="text-sm text-gray-900">{{ $report->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Signalé par :</span>
                            <p class="text-sm text-gray-900">{{ $report->reporter->name ?? 'Utilisateur supprimé' }}</p>
                        </div>
                    </div>
                    @if($report->description)
                        <div class="mt-4">
                            <span class="text-sm font-medium text-gray-500">Description :</span>
                            <p class="text-sm text-gray-900 mt-1">"{{ $report->description }}"</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Commentaire signalé -->
            <div>
                <h4 class="font-medium text-gray-900 mb-2">Commentaire signalé</h4>
                <div class="bg-blue-50 p-4 rounded">
                    @if($report->comment)
                        <p class="text-sm text-gray-900 whitespace-pre-wrap">{{ $report->comment->content }}</p>
                        <div class="mt-2 text-xs text-gray-600">
                            Par {{ $report->comment->user->name ?? 'Utilisateur supprimé' }} • 
                            Publié le {{ $report->comment->created_at->format('d/m/Y à H:i') }}
                        </div>
                    @else
                        <p class="text-sm text-gray-500 italic">Commentaire supprimé</p>
                    @endif
                </div>
            </div>

            @if($report->admin_notes)
                <!-- Notes admin -->
                <div>
                    <h4 class="font-medium text-gray-900 mb-2">Notes administratives</h4>
                    <div class="bg-green-50 p-4 rounded">
                        <p class="text-sm text-gray-900 whitespace-pre-wrap">{{ $report->admin_notes }}</p>
                    </div>
                </div>
            @endif
        </div>
        
        <div class="mt-6 flex justify-end">
            <button onclick="document.getElementById('detail-modal-{{ $report->id }}').classList.add('hidden')"
                    class="bg-gray-300 text-gray-700 px-4 py-2 rounded text-sm hover:bg-gray-400">
                Fermer
            </button>
        </div>
    </div>
</div>
@endforeach
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configuration CSRF pour toutes les requêtes AJAX
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Configuration globale pour fetch
    window.fetchWithCSRF = function(url, options = {}) {
        const defaultOptions = {
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            }
        };
        
        // Merger les options
        const mergedOptions = {
            ...defaultOptions,
            ...options,
            headers: {
                ...defaultOptions.headers,
                ...options.headers
            }
        };
        
        return fetch(url, mergedOptions);
    };
});

// Fonction pour traiter un signalement via AJAX
function processReport(reportId, action) {
    const url = `/admin/blog-comment-reports/${reportId}`;
    
    // Afficher un indicateur de chargement
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<svg class="animate-spin h-4 w-4 mr-2" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Traitement...';
    button.disabled = true;
    
    window.fetchWithCSRF(url, {
        method: 'PUT',
        body: JSON.stringify({
            action: action
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showToast(data.message || 'Action effectuée avec succès', 'success');
            // Recharger la page après un court délai
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            throw new Error(data.message || 'Erreur lors du traitement');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showToast('Erreur lors du traitement: ' + error.message, 'error');
        // Restaurer le bouton
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

// Fonction pour afficher les détails d'un signalement
function viewReportDetails(reportId) {
    // Implémenter selon vos besoins
    document.getElementById('detail-modal-' + reportId).classList.remove('hidden');
}

// Fonction pour afficher des notifications toast
function showToast(message, type = 'info') {
    // Créer un élément toast s'il n'existe pas
    let toast = document.getElementById('toast');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'toast';
        toast.className = 'fixed top-4 right-4 z-50 hidden';
        document.body.appendChild(toast);
    }
    
    const icons = {
        'success': '✅',
        'error': '❌',
        'info': 'ℹ️'
    };
    
    const colors = {
        'success': 'bg-green-100 border-green-500 text-green-700',
        'error': 'bg-red-100 border-red-500 text-red-700',
        'info': 'bg-blue-100 border-blue-500 text-blue-700'
    };
    
    toast.innerHTML = `
        <div class="border-l-4 p-4 rounded shadow-lg max-w-sm ${colors[type] || colors.info}">
            <div class="flex items-center">
                <span class="mr-2 text-lg">${icons[type] || icons.info}</span>
                <p class="text-sm font-medium">${message}</p>
                <button onclick="hideToast()" class="ml-auto text-lg hover:opacity-70">&times;</button>
            </div>
        </div>
    `;
    
    toast.classList.remove('hidden');
    
    // Auto-hide après 5 secondes
    setTimeout(() => {
        hideToast();
    }, 5000);
}

function hideToast() {
    const toast = document.getElementById('toast');
    if (toast) {
        toast.classList.add('hidden');
    }
}
</script>
