@extends('layouts.admin')

@section('title', 'Détails du Retour - ' . $orderLocation->order_number)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Entête -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <a href="{{ route('admin.rental-returns.index') }}" class="text-purple-600 hover:text-purple-700 flex items-center mb-4">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Retour à la liste des retours
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Détails du Retour {{ $orderLocation->order_number }}</h1>
                <p class="text-gray-600 mt-2">Gestion du retour et de l'inspection</p>
            </div>
            
            <div class="flex gap-3">
                @if($orderLocation->status === 'completed')
                <form action="{{ route('admin.rental-returns.mark-returned', $orderLocation) }}" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition duration-200 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/>
                        </svg>
                        Confirmer le retour
                    </button>
                </form>
                @endif
                
                @if($orderLocation->status === 'closed')
                <form action="{{ route('admin.rental-returns.start-inspection', $orderLocation) }}" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition duration-200 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2"/>
                        </svg>
                        Démarrer l'inspection
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Colonne principale -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Informations générales -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Informations Générales</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="font-medium text-gray-700 mb-2">Client</h3>
                        <p class="text-gray-900">{{ $orderLocation->user->name }}</p>
                        <p class="text-gray-600">{{ $orderLocation->user->email }}</p>
                    </div>
                    
                    <div>
                        <h3 class="font-medium text-gray-700 mb-2">Période de location</h3>
                        <p class="text-gray-900">Du {{ $orderLocation->start_date->format('d/m/Y') }} au {{ $orderLocation->end_date->format('d/m/Y') }}</p>
                        <p class="text-gray-600">{{ $orderLocation->rental_days }} jour(s)</p>
                    </div>
                    
                    <div>
                        <h3 class="font-medium text-gray-700 mb-2">Statut actuel</h3>
                        @php
                            $statusColors = [
                                'completed' => 'bg-yellow-100 text-yellow-800',
                                'closed' => 'bg-blue-100 text-blue-800',
                                'inspecting' => 'bg-purple-100 text-purple-800',
                                'finished' => 'bg-green-100 text-green-800'
                            ];
                            $statusLabels = [
                                'completed' => 'Terminé (Attente retour)',
                                'closed' => 'Retourné (Attente inspection)',
                                'inspecting' => 'En cours d\'inspection',
                                'finished' => 'Inspection terminée'
                            ];
                        @endphp
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $statusColors[$orderLocation->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $statusLabels[$orderLocation->status] ?? $orderLocation->status }}
                        </span>
                    </div>
                    
                    <div>
                        <h3 class="font-medium text-gray-700 mb-2">Date de retour effectif</h3>
                        @if($orderLocation->actual_return_date)
                            <p class="text-green-600 font-medium">{{ $orderLocation->actual_return_date->format('d/m/Y à H:i') }}</p>
                        @else
                            <p class="text-gray-400">Pas encore retourné</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Produits loués -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Produits de la Location</h2>
                
                <!-- Frais et Pénalités -->
                @if(($orderLocation->status === 'finished' && ($orderLocation->late_fees > 0 || $orderLocation->damage_cost > 0)) || ($orderLocation->status === 'inspecting' && ($orderLocation->late_days > 0 || $orderLocation->damage_cost > 0)))
                <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <h3 class="text-lg font-semibold text-yellow-800 mb-3">⚠️ Frais et Pénalités</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @if(($orderLocation->status === 'finished' && $orderLocation->late_fees > 0) || ($orderLocation->status === 'inspecting' && $orderLocation->late_days > 0))
                        <div class="bg-orange-50 p-3 rounded border border-orange-200">
                            <div class="text-sm font-medium text-orange-800">Frais de retard</div>
                            <div class="text-xl font-bold text-orange-900">{{ abs($orderLocation->late_days) }} jour{{ abs($orderLocation->late_days) > 1 ? 's' : '' }}</div>
                            <div class="text-lg font-semibold text-orange-700" id="summary_late_fees_display">
                                @if($orderLocation->status === 'finished')
                                    {{ number_format($orderLocation->late_fees, 2) }}€
                                @else
                                    {{ number_format($orderLocation->late_fees ?? (abs($orderLocation->late_days) * 10), 2) }}€
                                @endif
                            </div>
                        </div>
                        @endif
                        
                        @if($orderLocation->damage_cost > 0)
                        <div class="bg-red-50 p-3 rounded border border-red-200">
                            <div class="text-sm font-medium text-red-800">Frais de dégâts</div>
                            <div class="text-xl font-bold text-red-900">Dommages</div>
                            <div class="text-lg font-semibold text-red-700" id="summary_damage_costs_display">{{ number_format($orderLocation->damage_cost, 2) }}€</div>
                        </div>
                        @endif
                        
                        <div class="bg-gray-50 p-3 rounded border border-gray-200">
                            <div class="text-sm font-medium text-gray-800">Total des pénalités</div>
                            <div class="text-xl font-bold text-gray-900">Calculé</div>
                            <div class="text-lg font-semibold text-gray-700" id="summary_total_penalties_display">
                                @if($orderLocation->status === 'finished')
                                    {{ number_format($orderLocation->penalty_amount ?? 0, 2) }}€
                                @else
                                    {{ number_format(($orderLocation->late_fees ?? (abs($orderLocation->late_days) * 10)) + ($orderLocation->damage_cost ?? 0), 2) }}€
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                
                @if($orderLocation->status === 'finished')
                <!-- Résultats de l'inspection terminée -->
                <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-green-800 mb-4">✅ Inspection Terminée</h3>
                    
                    <!-- Résumé financier final -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-white p-4 rounded border">
                            <div class="text-sm font-medium text-gray-600">Frais de Retard</div>
                            <div class="text-xl font-bold text-orange-600">{{ number_format($orderLocation->late_fees ?? 0, 2) }}€</div>
                        </div>
                        <div class="bg-white p-4 rounded border">
                            <div class="text-sm font-medium text-gray-600">Frais de Dégâts</div>
                            <div class="text-xl font-bold text-red-600">{{ number_format($orderLocation->damage_cost ?? 0, 2) }}€</div>
                        </div>
                        <div class="bg-white p-4 rounded border">
                            <div class="text-sm font-medium text-gray-600">Total Pénalités</div>
                            <div class="text-xl font-bold text-gray-800">{{ number_format($orderLocation->penalty_amount ?? 0, 2) }}€</div>
                        </div>
                        <div class="bg-white p-4 rounded border">
                            <div class="text-sm font-medium text-gray-600">Remboursement</div>
                            <div class="text-xl font-bold text-green-600">{{ number_format($orderLocation->deposit_refund ?? 0, 2) }}€</div>
                        </div>
                    </div>
                    
                    <!-- Détail des produits inspectés -->
                    <div class="space-y-4">
                        <h4 class="font-medium text-gray-900">Détail de l'inspection :</h4>
                        @foreach($orderLocation->orderItemLocations as $item)
                        <div class="bg-white border rounded-lg p-4">
                            <div class="flex items-start space-x-4">
                                @if($item->product && $item->product->main_image)
                                <img src="{{ asset('storage/' . $item->product->main_image) }}" alt="{{ $item->product_name }}" class="w-12 h-12 object-cover rounded">
                                @else
                                <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
                                    <span class="text-xs text-gray-500">IMG</span>
                                </div>
                                @endif
                                
                                <div class="flex-1">
                                    <h5 class="font-medium text-gray-900">{{ $item->product_name }}</h5>
                                    <div class="text-sm text-gray-600 mt-1">
                                        <span>Quantité: {{ $item->quantity }}</span> • 
                                        <span>État: 
                                            @if($item->condition_at_return === 'excellent')
                                                <span class="text-green-600">Excellent</span>
                                            @elseif($item->condition_at_return === 'good')
                                                <span class="text-blue-600">Bon</span>
                                            @elseif($item->condition_at_return === 'poor')
                                                <span class="text-red-600">Mauvais</span>
                                            @else
                                                <span class="text-gray-500">Non spécifié</span>
                                            @endif
                                        </span>
                                        @if($item->item_damage_cost > 0)
                                        • <span class="text-red-600 font-medium">Dégâts: {{ number_format($item->item_damage_cost, 2) }}€</span>
                                        @endif
                                    </div>
                                    @if($item->item_inspection_notes)
                                    <div class="text-sm text-gray-700 mt-2 bg-gray-50 p-2 rounded">
                                        <strong>Notes:</strong> {{ $item->item_inspection_notes }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    @if($orderLocation->inspection_notes)
                    <div class="mt-4 bg-white border rounded-lg p-4">
                        <h5 class="font-medium text-gray-900 mb-2">Notes générales d'inspection:</h5>
                        <p class="text-gray-700">{{ $orderLocation->inspection_notes }}</p>
                    </div>
                    @endif
                </div>
                @endif
                
                @if($orderLocation->status === 'inspecting')
                <!-- Formulaire d'inspection -->
                <form action="{{ route('admin.rental-returns.finish-inspection', $orderLocation) }}" method="POST" id="inspectionForm">
                    @csrf
                    @method('PATCH')
                    
                    <div class="space-y-6">
                        @foreach($orderLocation->orderItemLocations as $item)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-start space-x-4">
                                @if($item->product && $item->product->main_image)
                                <img src="{{ asset('storage/' . $item->product->main_image) }}" alt="{{ $item->product_name }}" class="w-16 h-16 object-cover rounded">
                                @else
                                <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                @endif
                                
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900">{{ $item->product_name }}</h4>
                                    <p class="text-sm text-gray-600">Quantité: {{ $item->quantity }}</p>
                                    <p class="text-sm text-gray-600">Dépôt par item: {{ number_format($item->deposit_per_item, 2) }}€</p>
                                    
                                    <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">État au retour</label>
                                            <select name="items[{{ $item->id }}][condition_at_return]" class="w-full border border-gray-300 rounded px-3 py-2" required>
                                                <option value="">Sélectionner...</option>
                                                <option value="excellent" {{ $item->condition_at_return === 'excellent' ? 'selected' : '' }}>Excellent</option>
                                                <option value="good" {{ $item->condition_at_return === 'good' ? 'selected' : '' }}>Bon</option>
                                                <option value="poor" {{ $item->condition_at_return === 'poor' ? 'selected' : '' }}>Mauvais</option>
                                            </select>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Coût des dégâts (€)</label>
                                            <input type="number" 
                                                   name="items[{{ $item->id }}][item_damage_cost]" 
                                                   value="{{ $item->item_damage_cost ?? 0 }}"
                                                   step="0.01" 
                                                   min="0" 
                                                   max="999999.99"
                                                   class="w-full border border-gray-300 rounded px-3 py-2 item-damage-cost"
                                                   placeholder="0.00"
                                                   oninput="updatePenaltiesDisplay()"
                                                   onchange="updatePenaltiesDisplay()">>
                                        </div>
                                        
                                        <div class="md:col-span-1">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Notes d'inspection</label>
                                            <textarea name="items[{{ $item->id }}][item_inspection_notes]" 
                                                      rows="2" 
                                                      class="w-full border border-gray-300 rounded px-3 py-2"
                                                      placeholder="Notes sur l'état du produit...">{{ $item->item_inspection_notes }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        
                        <!-- Section Frais généraux -->
                        <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                            <h4 class="font-medium text-gray-900 mb-4">💰 Frais et Pénalités</h4>
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Frais de retard (€)
                                        @if($orderLocation->late_days > 0)
                                        <small class="text-orange-600">({{ $orderLocation->late_days }} jour{{ $orderLocation->late_days > 1 ? 's' : '' }} × 10€ = {{ $orderLocation->late_days * 10 }}€ suggéré)</small>
                                        @endif
                                    </label>
                                    <input type="number" 
                                           id="late_fees_input"
                                           name="late_fees" 
                                           value="{{ $orderLocation->late_fees ?? ($orderLocation->late_days * 10) }}"
                                           step="0.01" 
                                           min="0" 
                                           max="999999.99"
                                           class="w-full border border-gray-300 rounded px-3 py-2"
                                           placeholder="0.00"
                                           oninput="updatePenaltiesDisplay()"
                                           onchange="updatePenaltiesDisplay()">>
                                    <small class="text-gray-500">Vous pouvez modifier le montant des frais de retard selon les circonstances</small>
                                </div>
                                

                            </div>
                        </div>
                        
                        <div class="border-t pt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Notes générales d'inspection</label>
                            <textarea name="general_notes" 
                                      rows="4" 
                                      class="w-full border border-gray-300 rounded px-3 py-2"
                                      placeholder="Notes générales sur l'inspection...">{{ $orderLocation->inspection_notes }}</textarea>
                        </div>
                        
                        <div class="flex justify-end gap-4">
                            <button type="button" onclick="history.back()" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition duration-200">
                                Annuler
                            </button>
                            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition duration-200">
                                Terminer l'inspection
                            </button>
                        </div>
                    </div>
                </form>
                @endif
            </div>
        </div>

        <!-- Colonne latérale -->
        <div class="space-y-6">
            <!-- Résumé financier -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Résumé Financier</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Sous-total location:</span>
                        <span class="font-medium">{{ number_format($orderLocation->subtotal, 2) }}€</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Dépôt de caution:</span>
                        <span class="font-medium">{{ number_format($orderLocation->deposit_amount, 2) }}€</span>
                    </div>
                    
                    @if($orderLocation->penalty_amount > 0)
                    <div class="flex justify-between text-red-600">
                        <span>Pénalités/Dégâts:</span>
                        <span class="font-medium">-{{ number_format($orderLocation->penalty_amount, 2) }}€</span>
                    </div>
                    @endif
                    
                    @if($orderLocation->deposit_refund !== null)
                    <div class="border-t pt-3">
                        <div class="flex justify-between text-green-600">
                            <span class="font-medium">Remboursement caution:</span>
                            <span class="font-bold">{{ number_format($orderLocation->deposit_refund, 2) }}€</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Timeline de l'inspection -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Timeline</h3>
                
                <div class="space-y-4">
                    <div class="flex items-start space-x-3">
                        <div class="w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Location créée</p>
                            <p class="text-xs text-gray-500">{{ $orderLocation->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>
                    
                    @if($orderLocation->started_at)
                    <div class="flex items-start space-x-3">
                        <div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Location démarrée</p>
                            <p class="text-xs text-gray-500">{{ $orderLocation->started_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>
                    @endif
                    
                    @if($orderLocation->completed_at)
                    <div class="flex items-start space-x-3">
                        <div class="w-2 h-2 bg-yellow-500 rounded-full mt-2"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Location terminée</p>
                            <p class="text-xs text-gray-500">{{ $orderLocation->completed_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>
                    @endif
                    
                    @if($orderLocation->actual_return_date)
                    <div class="flex items-start space-x-3">
                        <div class="w-2 h-2 bg-purple-500 rounded-full mt-2"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Matériel retourné</p>
                            <p class="text-xs text-gray-500">{{ $orderLocation->actual_return_date->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>
                    @endif
                    
                    @if($orderLocation->inspection_started_at)
                    <div class="flex items-start space-x-3">
                        <div class="w-2 h-2 bg-indigo-500 rounded-full mt-2"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Inspection démarrée</p>
                            <p class="text-xs text-gray-500">{{ $orderLocation->inspection_started_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>
                    @endif
                    
                    @if($orderLocation->inspection_finished_at)
                    <div class="flex items-start space-x-3">
                        <div class="w-2 h-2 bg-green-600 rounded-full mt-2"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Inspection terminée</p>
                            <p class="text-xs text-gray-500">{{ $orderLocation->inspection_finished_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            @if($orderLocation->inspection_notes)
            <!-- Notes d'inspection -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Notes d'Inspection</h3>
                <p class="text-gray-700 text-sm">{{ $orderLocation->inspection_notes }}</p>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
function updatePenaltiesDisplay() {
    // Récupérer les frais de retard
    const lateFees = parseFloat(document.getElementById('late_fees_input')?.value || 0);
    
    // Calculer la somme des coûts de dégâts par produit
    let itemDamageCosts = 0;
    const itemDamageInputs = document.querySelectorAll('.item-damage-cost');
    itemDamageInputs.forEach(input => {
        itemDamageCosts += parseFloat(input.value || 0);
    });
    
    // Total des pénalités
    const totalPenalties = lateFees + itemDamageCosts;
    
    // Mettre à jour l'affichage
    const lateFeesDisplay = document.getElementById('late_fees_display');
    if (lateFeesDisplay) {
        lateFeesDisplay.textContent = lateFees.toFixed(2) + '€';
    }
    
    const damageDisplay = document.getElementById('damage_costs_display');
    if (damageDisplay) {
        damageDisplay.textContent = itemDamageCosts.toFixed(2) + '€';
    }
    
    const totalDisplay = document.getElementById('total_penalties_display');
    if (totalDisplay) {
        totalDisplay.textContent = totalPenalties.toFixed(2) + '€';
    }
}

// Initialiser l'affichage au chargement de la page SEULEMENT en mode inspection
document.addEventListener('DOMContentLoaded', function() {
    @if($orderLocation->status === 'inspecting')
    updatePenaltiesDisplay();
    @endif
});
</script>

@endsection
