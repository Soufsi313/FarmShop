@extends('admin.layout')

@section('title', 'Récupération - ' . $orderLocation->order_number)

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-hand-holding text-primary me-2"></i>
                Récupération du matériel
            </h1>
            <p class="text-muted mb-0">Commande #{{ $orderLocation->order_number }} - {{ $orderLocation->user->name }}</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.locations.show', $orderLocation) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Retour
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Formulaire d'inspection -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Inspection du matériel avant remise</h5>
                </div>
                <div class="card-body">
                    <form id="pickupForm" method="POST" action="{{ route('admin.locations.pickup', $orderLocation) }}">
                        @csrf
                        
                        <!-- Notes générales -->
                        <div class="mb-4">
                            <label for="pickup_notes" class="form-label">Notes générales de récupération</label>
                            <textarea class="form-control" id="pickup_notes" name="pickup_notes" rows="3" 
                                      placeholder="Notes sur la récupération (optionnel)"></textarea>
                        </div>

                        <!-- Inspection de chaque produit -->
                        <h6 class="mb-3">Inspection des produits</h6>
                        
                        @foreach($orderLocation->items as $index => $item)
                        <div class="border rounded p-3 mb-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center mb-3">
                                        @if($item->product && $item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}" 
                                                 alt="{{ $item->product_name }}" 
                                                 class="me-3" width="60" height="60" 
                                                 style="object-fit: cover; border-radius: 5px;">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center me-3" 
                                                 style="width: 60px; height: 60px; border-radius: 5px;">
                                                <i class="fas fa-tools text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <h6 class="mb-1">{{ $item->product_name }}</h6>
                                            <small class="text-muted">{{ $item->duration_days }} jours - {{ number_format($item->rental_price_per_day, 2) }}€/jour</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <!-- Champs cachés pour l'ID -->
                                    <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">
                                    
                                    <!-- État du produit -->
                                    <div class="mb-3">
                                        <label class="form-label">État du produit <span class="text-danger">*</span></label>
                                        <select name="items[{{ $index }}][condition]" class="form-select" required>
                                            <option value="excellent">✅ Excellent - Comme neuf</option>
                                            <option value="good" selected>✔️ Bon - Légères traces d'usure</option>
                                            <option value="fair">⚠️ Correct - Quelques défauts visibles</option>
                                            <option value="poor">❌ Mauvais - Défauts importants</option>
                                        </select>
                                    </div>
                                    
                                    <!-- Notes spécifiques -->
                                    <div class="mb-2">
                                        <label class="form-label">Notes spécifiques</label>
                                        <textarea class="form-control" name="items[{{ $index }}][notes]" rows="2" 
                                                  placeholder="Défauts, usure, accessoires manquants..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        <!-- Boutons d'action -->
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-secondary" onclick="history.back()">
                                <i class="fas fa-times me-1"></i> Annuler
                            </button>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-check me-1"></i> Confirmer la récupération
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Informations de la commande -->
        <div class="col-md-4">
            <!-- Informations client -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Informations client</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ $orderLocation->user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($orderLocation->user->name) . '&color=7F9CF5&background=EBF4FF' }}" 
                             alt="{{ $orderLocation->user->name }}" 
                             class="rounded-circle me-3" width="50" height="50">
                        <div>
                            <h6 class="mb-0">{{ $orderLocation->user->name }}</h6>
                            <small class="text-muted">{{ $orderLocation->user->email }}</small>
                        </div>
                    </div>
                    <p class="mb-1"><strong>ID Client :</strong> #{{ $orderLocation->user->id }}</p>
                    <p class="mb-0"><strong>Téléphone :</strong> {{ $orderLocation->user->phone ?? 'Non renseigné' }}</p>
                </div>
            </div>

            <!-- Détails de la location -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Détails de la location</h6>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Numéro :</strong> {{ $orderLocation->order_number }}</p>
                    <p class="mb-2"><strong>Période :</strong></p>
                    <ul class="mb-3">
                        <li>Du {{ $orderLocation->rental_start_date->format('d/m/Y') }}</li>
                        <li>Au {{ $orderLocation->rental_end_date->format('d/m/Y') }}</li>
                        <li><strong>{{ $orderLocation->rental_start_date->diffInDays($orderLocation->rental_end_date) + 1 }} jour(s)</strong></li>
                    </ul>
                    <p class="mb-2"><strong>Total :</strong> {{ number_format($orderLocation->total_amount, 2) }} €</p>
                    <p class="mb-0"><strong>Caution :</strong> {{ number_format($orderLocation->deposit_amount, 2) }} €</p>
                </div>
            </div>

            <!-- Instructions -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Instructions</h6>
                </div>
                <div class="card-body">
                    <ol class="mb-0">
                        <li class="mb-2">Vérifiez l'identité du client</li>
                        <li class="mb-2">Inspectez chaque produit</li>
                        <li class="mb-2">Documentez l'état initial</li>
                        <li class="mb-2">Expliquez les conditions de retour</li>
                        <li class="mb-0">Remettez le matériel au client</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('pickupForm').addEventListener('submit', function(e) {
    // Confirmer l'action
    if (!confirm('Êtes-vous sûr de vouloir marquer cette location comme récupérée ?')) {
        e.preventDefault();
        return;
    }
    
    // Laisser le formulaire se soumettre normalement
});
</script>
@endsection
