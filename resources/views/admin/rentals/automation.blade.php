@extends('layouts.admin')

@section('title', 'Automatisation des Locations')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Automatisation des Locations</h1>
            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                Système Actif
            </span>
        </div>

        <!-- Informations sur l'automatisation -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-blue-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold text-blue-800 mb-2">📅 Planification</h3>
                <ul class="text-blue-700 space-y-1">
                    <li>• Exécution automatique: 3 fois par jour</li>
                    <li>• Horaires: 09:00, 15:00, 21:00</li>
                    <li>• Logs: storage/logs/rental-automation.log</li>
                </ul>
            </div>

            <div class="bg-green-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold text-green-800 mb-2">⚡ Actions Automatiques</h3>
                <ul class="text-green-700 space-y-1">
                    <li>• Rappels 7 jours avant fin</li>
                    <li>• Détection des retards</li>
                    <li>• Calcul des amendes</li>
                    <li>• Notifications email</li>
                </ul>
            </div>
        </div>

        <!-- Exécution manuelle -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold text-yellow-800 mb-4">🔧 Exécution Manuelle</h2>
            <p class="text-yellow-700 mb-4">
                Vous pouvez déclencher manuellement l'automatisation pour traiter immédiatement 
                les locations en attente.
            </p>
            
            <div class="flex space-x-4">
                <button 
                    id="runAutomation" 
                    class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded transition duration-200"
                >
                    🚀 Exécuter Maintenant
                </button>
                
                <button 
                    id="runDryRun" 
                    class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition duration-200"
                >
                    👁️ Simulation (Dry Run)
                </button>
            </div>
        </div>

        <!-- Zone de résultats -->
        <div id="results" class="hidden">
            <div class="bg-gray-50 border rounded-lg p-4">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">📋 Résultats</h3>
                <div id="output" class="bg-white p-3 rounded border font-mono text-sm max-h-64 overflow-y-auto">
                    <!-- Les résultats apparaîtront ici -->
                </div>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="mt-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">📊 Statistiques</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white border rounded-lg p-4">
                    <div class="text-sm text-gray-600">Locations Actives</div>
                    <div class="text-2xl font-bold text-blue-600" id="activeRentals">-</div>
                </div>
                
                <div class="bg-white border rounded-lg p-4">
                    <div class="text-sm text-gray-600">En Retard</div>
                    <div class="text-2xl font-bold text-red-600" id="overdueRentals">-</div>
                </div>
                
                <div class="bg-white border rounded-lg p-4">
                    <div class="text-sm text-gray-600">Rappels Envoyés</div>
                    <div class="text-2xl font-bold text-yellow-600" id="remindersSent">-</div>
                </div>
                
                <div class="bg-white border rounded-lg p-4">
                    <div class="text-sm text-gray-600">Amendes Totales</div>
                    <div class="text-2xl font-bold text-purple-600" id="totalPenalties">-</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const runButton = document.getElementById('runAutomation');
    const dryRunButton = document.getElementById('runDryRun');
    const resultsDiv = document.getElementById('results');
    const outputDiv = document.getElementById('output');

    function executeAutomation(isDryRun = false) {
        const button = isDryRun ? dryRunButton : runButton;
        const originalText = button.textContent;
        
        button.disabled = true;
        button.textContent = '⏳ Exécution...';
        button.classList.add('opacity-50', 'cursor-not-allowed');

        const url = isDryRun ? '/api/admin/rentals/automation/dry-run' : '/api/admin/rentals/automation/run';
        
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            resultsDiv.classList.remove('hidden');
            
            if (data.success) {
                outputDiv.innerHTML = `<div class="text-green-600 font-semibold">✅ ${data.message}</div>`;
                if (data.output) {
                    outputDiv.innerHTML += `<pre class="mt-2 text-gray-700">${data.output}</pre>`;
                }
                loadStatistics(); // Recharger les statistiques
            } else {
                outputDiv.innerHTML = `<div class="text-red-600 font-semibold">❌ Erreur: ${data.message}</div>`;
            }
        })
        .catch(error => {
            resultsDiv.classList.remove('hidden');
            outputDiv.innerHTML = `<div class="text-red-600 font-semibold">❌ Erreur réseau: ${error.message}</div>`;
        })
        .finally(() => {
            button.disabled = false;
            button.textContent = originalText;
            button.classList.remove('opacity-50', 'cursor-not-allowed');
        });
    }

    function loadStatistics() {
        fetch('/api/admin/rentals/dashboard')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const stats = data.data.statistics;
                document.getElementById('activeRentals').textContent = stats.active_rentals || 0;
                document.getElementById('overdueRentals').textContent = stats.overdue_rentals || 0;
                document.getElementById('remindersSent').textContent = stats.needs_attention?.pending_returns || 0;
                document.getElementById('totalPenalties').textContent = (stats.total_penalties || 0) + '€';
            }
        })
        .catch(error => console.error('Erreur lors du chargement des statistiques:', error));
    }

    runButton.addEventListener('click', () => executeAutomation(false));
    dryRunButton.addEventListener('click', () => executeAutomation(true));

    // Charger les statistiques au chargement de la page
    loadStatistics();
});
</script>
@endsection
