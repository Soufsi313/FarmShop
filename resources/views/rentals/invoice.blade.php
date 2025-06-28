<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture Location {{ $rental->rental_number }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 15px;
        }
        
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #4CAF50;
            margin-bottom: 5px;
        }
        
        .invoice-title {
            font-size: 18px;
            color: #666;
            margin-top: 10px;
        }
        
        .invoice-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        
        .invoice-details, .client-info {
            width: 48%;
        }
        
        .invoice-details h3, .client-info h3 {
            color: #4CAF50;
            font-size: 14px;
            margin-bottom: 10px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        
        .rental-period {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .rental-period h3 {
            color: #4CAF50;
            margin-bottom: 10px;
        }
        
        .period-dates {
            font-size: 14px;
            font-weight: bold;
        }
        
        .duration {
            color: #666;
            margin-top: 5px;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .items-table th {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }
        
        .items-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #ddd;
        }
        
        .items-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .text-right {
            text-align: right;
        }
        
        .totals {
            margin-top: 20px;
            float: right;
            width: 300px;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            border-bottom: 1px solid #ddd;
        }
        
        .total-row.final {
            font-weight: bold;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            margin-top: 10px;
        }
        
        .penalties-section {
            margin-top: 30px;
            clear: both;
        }
        
        .penalties-section h3 {
            color: #dc3545;
            border-bottom: 2px solid #dc3545;
            padding-bottom: 5px;
        }
        
        .status-badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-pending { background-color: #ffc107; color: #856404; }
        .status-confirmed { background-color: #17a2b8; color: white; }
        .status-active { background-color: #28a745; color: white; }
        .status-completed { background-color: #6c757d; color: white; }
        .status-overdue { background-color: #dc3545; color: white; }
        .status-cancelled { background-color: #343a40; color: white; }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        
        .notes {
            margin-top: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-left: 4px solid #4CAF50;
        }
        
        .notes h4 {
            margin-top: 0;
            color: #4CAF50;
        }
        
        @media print {
            body { margin: 0; padding: 15px; }
            .page-break { page-break-after: always; }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">FarmShop</div>
        <div>E-commerce Agricole</div>
        <div class="invoice-title">FACTURE DE LOCATION</div>
    </div>

    <div class="invoice-info">
        <div class="invoice-details">
            <h3>Détails de la facture</h3>
            <p><strong>Numéro:</strong> {{ $rental->rental_number }}</p>
            <p><strong>Date d'émission:</strong> {{ $rental->created_at->format('d/m/Y') }}</p>
            <p><strong>Statut:</strong> 
                <span class="status-badge status-{{ $rental->status }}">
                    {{ \App\Models\Rental::getAllStatuses()[$rental->status] }}
                </span>
            </p>
            @if($rental->actual_return_date)
                <p><strong>Date de retour:</strong> {{ $rental->actual_return_date->format('d/m/Y') }}</p>
            @endif
        </div>

        <div class="client-info">
            <h3>Informations client</h3>
            <p><strong>{{ $rental->user->name }}</strong></p>
            <p>{{ $rental->user->email }}</p>
            @if($rental->billing_address)
                <p>{{ $rental->billing_address['street'] ?? '' }}</p>
                <p>{{ $rental->billing_address['postal_code'] ?? '' }} {{ $rental->billing_address['city'] ?? '' }}</p>
                <p>{{ $rental->billing_address['country'] ?? '' }}</p>
            @endif
        </div>
    </div>

    <div class="rental-period">
        <h3>Période de location</h3>
        <div class="period-dates">
            Du {{ $rental->start_date->format('d/m/Y') }} au {{ $rental->end_date->format('d/m/Y') }}
        </div>
        <div class="duration">
            Durée: {{ $rental->duration_in_days }} jour(s)
        </div>
    </div>

    <h3>Articles loués</h3>
    <table class="items-table">
        <thead>
            <tr>
                <th>Produit</th>
                <th>Quantité</th>
                <th>Prix/jour</th>
                <th>Durée</th>
                <th>Sous-total</th>
                <th>Caution</th>
                <th>État</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rental->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->rental_price_per_day, 2) }}€</td>
                    <td>{{ $rental->duration_in_days }} jour(s)</td>
                    <td class="text-right">{{ number_format($item->total_rental_amount, 2) }}€</td>
                    <td class="text-right">{{ number_format($item->total_deposit_amount, 2) }}€</td>
                    <td>
                        <span class="status-badge status-{{ str_replace('_', '-', $item->return_status) }}">
                            {{ \App\Models\RentalItem::getReturnStatuses()[$item->return_status] }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <div class="total-row">
            <span>Sous-total location:</span>
            <span>{{ number_format($rental->total_rental_amount, 2) }}€</span>
        </div>
        <div class="total-row">
            <span>Caution totale:</span>
            <span>{{ number_format($rental->total_deposit_amount, 2) }}€</span>
        </div>
        @if($rental->penalty_amount > 0)
            <div class="total-row">
                <span>Amendes:</span>
                <span>{{ number_format($rental->penalty_amount, 2) }}€</span>
            </div>
        @endif
        @if($rental->refund_amount > 0)
            <div class="total-row">
                <span>Remboursement:</span>
                <span>-{{ number_format($rental->refund_amount, 2) }}€</span>
            </div>
        @endif
        <div class="total-row final">
            <span>TOTAL:</span>
            <span>{{ number_format($rental->total_amount, 2) }}€</span>
        </div>
    </div>

    @if($rental->penalties->count() > 0)
        <div class="penalties-section">
            <h3>Détail des amendes</h3>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Raison</th>
                        <th>Montant</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rental->penalties as $penalty)
                        <tr>
                            <td>{{ $penalty->formatted_type }}</td>
                            <td>{{ $penalty->reason }}</td>
                            <td class="text-right">{{ number_format($penalty->amount, 2) }}€</td>
                            <td>{{ $penalty->formatted_payment_status }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    @if($rental->notes || $rental->return_notes)
        <div class="notes">
            @if($rental->notes)
                <h4>Notes de location</h4>
                <p>{{ $rental->notes }}</p>
            @endif
            
            @if($rental->return_notes)
                <h4>Notes de retour</h4>
                <p>{{ $rental->return_notes }}</p>
            @endif
        </div>
    @endif

    <div class="footer">
        <p>FarmShop - E-commerce Agricole</p>
        <p>Cette facture a été générée automatiquement le {{ now()->format('d/m/Y à H:i') }}</p>
        <p>Pour toute question, contactez notre service client.</p>
    </div>
</body>
</html>
