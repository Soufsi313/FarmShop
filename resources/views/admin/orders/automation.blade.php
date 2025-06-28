@extends('layouts.app')

@section('title', 'Automatisation des commandes')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- En-tête -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Automatisation des commandes</h1>
            <p class="text-gray-600">Gérez l'automatisation des statuts de commandes et exécutez les tâches manuellement.</p>
        </div>

        <!-- Messages de succès/erreur -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Commandes en attente</h3>
                        <p class="text-sm text-gray-600">{{ App\Models\Order::where('status', 'pending')->count() }} commandes</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-100 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Confirmées</h3>
                        <p class="text-sm text-gray-600">{{ App\Models\Order::where('status', 'confirmed')->count() }} commandes</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Expédiées</h3>
                        <p class="text-sm text-gray-600">{{ App\Models\Order::where('status', 'shipped')->count() }} commandes</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions d'automatisation -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Actions d'automatisation</h2>
            </div>
            
            <div class="p-6">
                <div class="space-y-6">
                    <!-- Automatisation manuelle -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Exécuter l'automatisation maintenant</h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    Déclenche immédiatement la mise à jour automatique des statuts de toutes les commandes éligibles.
                                </p>
                                <div class="mt-2 text-sm text-gray-500">
                                    <ul class="list-disc list-inside space-y-1">
                                        <li>Confirme automatiquement les commandes en attente depuis plus de 24h</li>
                                        <li>Expédie les commandes confirmées depuis plus de 24h</li>
                                        <li>Livre les commandes expédiées depuis plus de 3 jours</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="ml-4">
                                <form action="{{ route('admin.orders.automation.run') }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                            onclick="return confirm('Êtes-vous sûr de vouloir exécuter l\'automatisation maintenant ?')">
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                        Exécuter maintenant
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Informations sur la planification -->
                    <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                        <div class="flex items-start">
                            <div class="p-2 bg-blue-100 rounded-lg mr-4">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Automatisation planifiée</h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    L'automatisation des statuts s'exécute automatiquement <strong>tous les jours à 6h00</strong> via le cron de Laravel.
                                </p>
                                <div class="mt-3 text-sm text-gray-500">
                                    <p><strong>Commande cron :</strong> <code class="bg-white px-2 py-1 rounded">php artisan orders:automate-statuses</code></p>
                                    <p class="mt-1"><strong>Planification :</strong> <code class="bg-white px-2 py-1 rounded">$schedule->command('orders:automate-statuses')->dailyAt('06:00')</code></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liens de navigation -->
        <div class="mt-8 flex space-x-4">
            <a href="{{ route('admin.orders.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200">
                ← Retour aux commandes
            </a>
            <a href="{{ route('admin.orders.statistics') }}" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200">
                Voir les statistiques
            </a>
        </div>
    </div>
</div>

<script>
// Fonction pour actualiser les statistiques via AJAX (optionnel)
function refreshStats() {
    // Ici on pourrait ajouter du JavaScript pour actualiser les stats en temps réel
    location.reload();
}

// Auto-refresh toutes les 30 secondes (optionnel)
// setInterval(refreshStats, 30000);
</script>
@endsection
