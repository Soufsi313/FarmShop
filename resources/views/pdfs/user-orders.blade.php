<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Historique Commandes - {{ $user->name }}</title>
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
        .order {
            border: 1px solid #ddd;
            margin-bottom: 20px;
            padding: 15px;
        }
        .order-header {
            background-color: #f8f9fa;
            padding: 10px;
            margin: -15px -15px 15px -15px;
            border-bottom: 1px solid #ddd;
        }
        .order-info {
            display: flex;
            margin-bottom: 10px;
        }
        .order-label {
            font-weight: bold;
            width: 120px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .items-table th,
        .items-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .items-table th {
            background-color: #f8f9fa;
        }
        .total {
            text-align: right;
            font-weight: bold;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Historique des Commandes</h1>
        <h2>{{ $user->name }}</h2>
        <p>Exporté le {{ now()->format('d/m/Y à H:i:s') }}</p>
    </div>

    @forelse($orders as $order)
        <div class="order">
            <div class="order-header">
                <h3>Commande #{{ $order->id }}</h3>
            </div>
            
            <div class="order-info">
                <div class="order-label">Date :</div>
                <div>{{ $order->created_at->format('d/m/Y à H:i:s') }}</div>
            </div>
            <div class="order-info">
                <div class="order-label">Statut :</div>
                <div>{{ $order->status }}</div>
            </div>
            <div class="order-info">
                <div class="order-label">Total :</div>
                <div>{{ number_format($order->total_amount, 2) }} €</div>
            </div>

            @if($order->items->count() > 0)
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>{{ __("app.ecommerce.product") }}</th>
                            <th>{{ __("app.ecommerce.quantity") }}</th>
                            <th>Prix unitaire</th>
                            <th>{{ __("app.ecommerce.total") }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->product->name ?? 'Produit supprimé' }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->unit_price, 2) }} €</td>
                                <td>{{ number_format($item->total_price, 2) }} €</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    @empty
        <p>Aucune commande trouvée.</p>
    @endforelse

    <div class="footer" style="margin-top: 40px; text-align: center; font-size: 10px; color: #666;">
        <p>Document généré automatiquement par FarmShop</p>
        <p>Conformément au Règlement Général sur la Protection des Données (RGPD)</p>
    </div>
</body>
</html>
