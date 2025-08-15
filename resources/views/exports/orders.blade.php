<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export des commandes</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 11px;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #28a745;
            padding-bottom: 20px;
        }
        
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #28a745;
            margin-bottom: 10px;
        }
        
        .export-title {
            font-size: 18px;
            font-weight: bold;
            margin: 10px 0;
        }
        
        .export-info {
            color: #666;
            margin-bottom: 20px;
        }
        
        .orders-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .orders-table th,
        .orders-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 10px;
        }
        
        .orders-table th {
            background-color: #28a745;
            color: white;
            font-weight: bold;
        }
        
        .orders-table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .status-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status-confirmed {
            background-color: #cff4fc;
            color: #055160;
        }
        
        .status-preparing {
            background-color: #e2e3e5;
            color: #383d41;
        }
        
        .status-shipped {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        
        .status-delivered {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .summary {
            margin: 20px 0;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <!-- En-tête -->
    <div class="header">
        <div class="company-name">FarmShop</div>
        <div class="export-title">Export des commandes</div>
        <div class="export-info">
            Généré le {{ now()->format('d/m/Y à H:i') }} | 
            Total: {{ $orders->count() }} commande(s)
        </div>
    </div>
    
    <!-- Résumé -->
    <div class="summary">
        <h3 style="margin-top: 0; color: #28a745;">Résumé</h3>
        <div class="summary-row">
            <span><strong>Nombre total de commandes:</strong></span>
            <span>{{ $orders->count() }}</span>
        </div>
        <div class="summary-row">
            <span><strong>Chiffre d'affaires total:</strong></span>
            <span>{{ number_format($orders->where('payment_status', 'paid')->sum('total_amount'), 2, ',', ' ') }} €</span>
        </div>
        <div class="summary-row">
            <span><strong>Panier moyen:</strong></span>
            <span>{{ number_format($orders->where('payment_status', 'paid')->avg('total_amount'), 2, ',', ' ') }} €</span>
        </div>
        <div class="summary-row">
            <span><strong>Commandes payées:</strong></span>
            <span>{{ $orders->where('payment_status', 'paid')->count() }}</span>
        </div>
        <div class="summary-row">
            <span><strong>Commandes livrées:</strong></span>
            <span>{{ $orders->where('status', 'delivered')->count() }}</span>
        </div>
    </div>
    
    <!-- Tableau des commandes -->
    <table class="orders-table">
        <thead>
            <tr>
                <th style="width: 12%;">N° Commande</th>
                <th style="width: 20%;">Client</th>
                <th style="width: 15%;">{{ __("app.forms.email") }}</th>
                <th style="width: 8%;">Statut</th>
                <th style="width: 8%;">{{ __("app.ecommerce.payment") }}</th>
                <th style="width: 10%;" class="text-right">Montant</th>
                <th style="width: 12%;">Date commande</th>
                <th style="width: 15%;">{{ __("app.nav.products") }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td>{{ $order->order_number }}</td>
                <td>{{ $order->user->name }}</td>
                <td>{{ $order->user->email }}</td>
                <td>
                    <span class="status-badge status-{{ $order->status }}">
                        {{ $order->status_label }}
                    </span>
                </td>
                <td>
                    <span class="status-badge status-{{ $order->payment_status }}">
                        {{ $order->payment_status_label }}
                    </span>
                </td>
                <td class="text-right">{{ number_format($order->total_amount, 2, ',', ' ') }} €</td>
                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    @foreach($order->items->take(3) as $item)
                        {{ $item->product->name }}
                        @if($item->quantity > 1)
                            (x{{ $item->quantity }})
                        @endif
                        @if(!$loop->last)
                            <br>
                        @endif
                    @endforeach
                    @if($order->items->count() > 3)
                        <br>... et {{ $order->items->count() - 3 }} autre(s)
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <!-- Statistiques par statut -->
    <div class="summary">
        <h3 style="margin-top: 0; color: #28a745;">Répartition par statut</h3>
        @php
            $statusCounts = $orders->groupBy('status')->map->count();
        @endphp
        @foreach($statusCounts as $status => $count)
        <div class="summary-row">
            <span>{{ ucfirst($status) }}:</span>
            <span>{{ $count }} commande(s) ({{ round(($count / $orders->count()) * 100, 1) }}%)</span>
        </div>
        @endforeach
    </div>
    
    <!-- Statistiques par mode de paiement -->
    <div class="summary">
        <h3 style="margin-top: 0; color: #28a745;">Répartition par mode de paiement</h3>
        @php
            $paymentCounts = $orders->groupBy('payment_method')->map->count();
        @endphp
        @foreach($paymentCounts as $method => $count)
        <div class="summary-row">
            <span>{{ ucfirst($method) }}:</span>
            <span>{{ $count }} commande(s) ({{ round(($count / $orders->count()) * 100, 1) }}%)</span>
        </div>
        @endforeach
    </div>
    
    <!-- Pied de page -->
    <div class="footer">
        <p><strong>FarmShop - E-commerce de produits agricoles</strong></p>
        <p>123 Avenue des Champs, 75001 Paris, France | Tél: +33 1 23 45 67 89 | Email: contact@farmshop.fr</p>
        <p>Document généré automatiquement le {{ now()->format('d/m/Y à H:i') }}</p>
    </div>
</body>
</html>
