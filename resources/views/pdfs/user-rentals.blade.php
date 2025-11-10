<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Historique Locations - {{ $user->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #27ae60;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .rental {
            border: 1px solid #ddd;
            margin-bottom: 20px;
            padding: 15px;
        }
        .rental-header {
            background-color: #f8f9fa;
            padding: 10px;
            margin: -15px -15px 15px -15px;
            border-bottom: 1px solid #ddd;
        }
        .rental-info {
            display: flex;
            margin-bottom: 10px;
        }
        .rental-label {
            font-weight: bold;
            width: 150px;
        }
        .inspections {
            margin-top: 15px;
            padding: 10px;
            background-color: #f8f9fa;
        }
        .inspection {
            margin-bottom: 10px;
            padding: 8px;
            border-left: 3px solid #27ae60;
            background-color: white;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Historique des Locations</h1>
        <h2>{{ $user->name }}</h2>
        <p>Exporté le {{ now()->format('d/m/Y à H:i:s') }}</p>
    </div>

    @forelse($rentals as $rental)
        <div class="rental">
            <div class="rental-header">
                <h3>Location #{{ $rental->id }}</h3>
            </div>
            
            <div class="rental-info">
                <div class="rental-label">Produit(s) :</div>
                <div>
                    @if($rental->orderItemLocations && $rental->orderItemLocations->count() > 0)
                        @foreach($rental->orderItemLocations as $item)
                            {{ $item->product_name ?? 'Produit supprimé' }}
                            @if(!$loop->last), @endif
                        @endforeach
                    @else
                        Aucun produit
                    @endif
                </div>
            </div>
            <div class="rental-info">
                <div class="rental-label">Date de début :</div>
                <div>{{ $rental->start_date ? $rental->start_date->format('d/m/Y') : 'Non définie' }}</div>
            </div>
            <div class="rental-info">
                <div class="rental-label">Date de fin :</div>
                <div>{{ $rental->end_date ? $rental->end_date->format('d/m/Y') : 'Non définie' }}</div>
            </div>
            <div class="rental-info">
                <div class="rental-label">Statut :</div>
                <div>{{ $rental->status }}</div>
            </div>
            <div class="rental-info">
                <div class="rental-label">Prix total :</div>
                <div>{{ number_format($rental->total_amount, 2) }} €</div>
            </div>
            <div class="rental-info">
                <div class="rental-label">Caution :</div>
                <div>{{ number_format($rental->deposit_amount, 2) }} €</div>
            </div>

            @if($rental->inspection_notes)
                <div class="rental-info">
                    <div class="rental-label">Notes d'inspection :</div>
                    <div>{{ $rental->inspection_notes }}</div>
                </div>
            @endif
        </div>
    @empty
        <p>Aucune location trouvée.</p>
    @endforelse

    <div class="footer" style="margin-top: 40px; text-align: center; font-size: 10px; color: #666;">
        <p>Document généré automatiquement par FarmShop</p>
        <p>Conformément au Règlement Général sur la Protection des Données (RGPD)</p>
    </div>
</body>
</html>
