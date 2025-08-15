<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Données personnelles - {{ $user->name }}</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            font-size: 12px; 
            line-height: 1.4;
            color: #333;
            margin: 20px;
        }
        .header { 
            text-align: center; 
            margin-bottom: 30px; 
            border-bottom: 2px solid #28a745;
            padding-bottom: 20px;
        }
        .header h1 { 
            color: #28a745; 
            margin: 0;
            font-size: 24px;
        }
        .section { 
            margin-bottom: 25px; 
            page-break-inside: avoid;
        }
        .section h2 { 
            color: #28a745; 
            border-bottom: 1px solid #ddd; 
            padding-bottom: 5px;
            font-size: 16px;
            margin-bottom: 15px;
        }
        .info-grid { 
            display: grid; 
            grid-template-columns: 1fr 2fr; 
            gap: 10px; 
        }
        .info-label { 
            font-weight: bold; 
            color: #555;
        }
        .info-value { 
            color: #333;
        }
        .table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px;
        }
        .table th, .table td { 
            border: 1px solid #ddd; 
            padding: 8px; 
            text-align: left;
        }
        .table th { 
            background-color: #f8f9fa; 
            font-weight: bold;
        }
        .footer { 
            margin-top: 40px; 
            text-align: center; 
            font-size: 10px; 
            color: #666; 
            border-top: 1px solid #ddd; 
            padding-top: 15px;
        }
        .status-badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .status-active { background-color: #d4edda; color: #155724; }
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-completed { background-color: #d1ecf1; color: #0c5460; }
        .status-cancelled { background-color: #f8d7da; color: #721c24; }
        .empty-state { 
            text-align: center; 
            color: #666; 
            font-style: italic; 
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📊 Archive complète des données personnelles</h1>
        <p>Utilisateur: <strong>{{ $user->name }}</strong> ({{ $user->email }})</p>
        <p>Généré le: {{ now()->format('d/m/Y à H:i:s') }}</p>
    </div>

    <!-- Informations personnelles -->
    <div class="section">
        <h2>👤 Informations personnelles</h2>
        <div class="info-grid">
            <span class="info-label">Nom d'utilisateur:</span>
            <span class="info-value">{{ $user->username }}</span>
            
            <span class="info-label">Nom complet:</span>
            <span class="info-value">{{ $user->name }}</span>
            
            <span class="info-label">Email:</span>
            <span class="info-value">{{ $user->email }}</span>
            
            <span class="info-label">Téléphone:</span>
            <span class="info-value">{{ $user->phone ?? 'Non renseigné' }}</span>
            
            <span class="info-label">Rôle:</span>
            <span class="info-value">{{ $user->role }}</span>
            
            <span class="info-label">Newsletter:</span>
            <span class="info-value">{{ $user->newsletter_subscribed ? '✅ Abonné' : '❌ Non abonné' }}</span>
            
            <span class="info-label">Date de création:</span>
            <span class="info-value">{{ $user->created_at->format('d/m/Y à H:i') }}</span>
            
            <span class="info-label">Dernière modification:</span>
            <span class="info-value">{{ $user->updated_at->format('d/m/Y à H:i') }}</span>
        </div>
    </div>

    <!-- Adresse -->
    @if($user->hasCompleteAddress())
    <div class="section">
        <h2>🏠 Adresse de livraison</h2>
        <div class="info-grid">
            <span class="info-label">Adresse:</span>
            <span class="info-value">{{ $user->address }}</span>
            
            @if($user->address_line_2)
            <span class="info-label">Complément:</span>
            <span class="info-value">{{ $user->address_line_2 }}</span>
            @endif
            
            <span class="info-label">Ville:</span>
            <span class="info-value">{{ $user->city }}</span>
            
            <span class="info-label">Code postal:</span>
            <span class="info-value">{{ $user->postal_code }}</span>
            
            <span class="info-label">Pays:</span>
            <span class="info-value">{{ $user->country }}</span>
        </div>
    </div>
    @endif

    <!-- Commandes d'achat -->
    @if($orders->count() > 0)
    <div class="section">
        <h2>🛒 Commandes d'achat ({{ $orders->count() }})</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>N° Commande</th>
                    <th>{{ __("app.time.date") }}</th>
                    <th>Statut</th>
                    <th>{{ __("app.ecommerce.total") }}</th>
                    <th>{{ __("app.ecommerce.payment") }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td>{{ $order->order_number }}</td>
                    <td>{{ $order->created_at->format('d/m/Y') }}</td>
                    <td><span class="status-badge status-{{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
                    <td>{{ number_format($order->total_amount, 2) }}€</td>
                    <td>{{ ucfirst($order->payment_status) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Commandes de location -->
    @if($rentalOrders->count() > 0)
    <div class="section">
        <h2>🏗️ Commandes de location ({{ $rentalOrders->count() }})</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>N° Commande</th>
                    <th>Période</th>
                    <th>Statut</th>
                    <th>{{ __("app.ecommerce.total") }}</th>
                    <th>Caution</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rentalOrders as $rental)
                <tr>
                    <td>{{ $rental->order_number }}</td>
                    <td>{{ $rental->start_date->format('d/m/Y') }} - {{ $rental->end_date->format('d/m/Y') }}</td>
                    <td><span class="status-badge status-{{ $rental->status }}">{{ ucfirst($rental->status) }}</span></td>
                    <td>{{ number_format($rental->total_amount, 2) }}€</td>
                    <td>{{ number_format($rental->deposit_amount, 2) }}€</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Produits favoris -->
    @if($likedProducts->count() > 0)
    <div class="section">
        <h2>❤️ Produits favoris ({{ $likedProducts->count() }})</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>{{ __("app.ecommerce.product") }}</th>
                    <th>{{ __("app.ecommerce.category") }}</th>
                    <th>{{ __("app.ecommerce.price") }}</th>
                    <th>Date d'ajout</th>
                </tr>
            </thead>
            <tbody>
                @foreach($likedProducts as $like)
                <tr>
                    <td>{{ $like->product->name }}</td>
                    <td>{{ $like->product->category->name ?? 'N/A' }}</td>
                    <td>{{ number_format($like->product->price, 2) }}€</td>
                    <td>{{ $like->created_at->format('d/m/Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Liste de souhaits -->
    @if($wishlistItems->count() > 0)
    <div class="section">
        <h2>⭐ Liste de souhaits ({{ $wishlistItems->count() }})</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>{{ __("app.ecommerce.product") }}</th>
                    <th>{{ __("app.ecommerce.category") }}</th>
                    <th>{{ __("app.ecommerce.price") }}</th>
                    <th>Date d'ajout</th>
                </tr>
            </thead>
            <tbody>
                @foreach($wishlistItems as $wishlist)
                <tr>
                    <td>{{ $wishlist->product->name }}</td>
                    <td>{{ $wishlist->product->category->name ?? 'N/A' }}</td>
                    <td>{{ number_format($wishlist->product->price, 2) }}€</td>
                    <td>{{ $wishlist->created_at->format('d/m/Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Abonnement newsletter -->
    @if($newsletterSubscription)
    <div class="section">
        <h2>📧 Abonnement newsletter</h2>
        <div class="info-grid">
            <span class="info-label">Statut:</span>
            <span class="info-value">{{ $newsletterSubscription->is_subscribed ? '✅ Abonné' : '❌ Désabonné' }}</span>
            
            @if($newsletterSubscription->subscribed_at)
            <span class="info-label">Date d'abonnement:</span>
            <span class="info-value">{{ $newsletterSubscription->subscribed_at->format('d/m/Y à H:i') }}</span>
            @endif
            
            @if($newsletterSubscription->unsubscribed_at)
            <span class="info-label">Date de désabonnement:</span>
            <span class="info-value">{{ $newsletterSubscription->unsubscribed_at->format('d/m/Y à H:i') }}</span>
            @endif
            
            <span class="info-label">Source:</span>
            <span class="info-value">{{ ucfirst($newsletterSubscription->source ?? 'Manuel') }}</span>
        </div>
    </div>
    @endif

    <!-- Paniers actifs -->
    @if($activeCarts->count() > 0)
    <div class="section">
        <h2>🛒 Paniers actifs ({{ $activeCarts->count() }})</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Articles</th>
                    <th>{{ __("app.ecommerce.total") }}</th>
                    <th>Dernière modification</th>
                </tr>
            </thead>
            <tbody>
                @foreach($activeCarts as $cart)
                <tr>
                    <td>Achat</td>
                    <td>{{ $cart->total_items ?? 0 }}</td>
                    <td>{{ number_format($cart->total ?? 0, 2) }}€</td>
                    <td>{{ $cart->updated_at->format('d/m/Y à H:i') }}</td>
                </tr>
                @endforeach
                @foreach($activeCartLocations as $cartLocation)
                <tr>
                    <td>{{ __("app.ecommerce.rental") }}</td>
                    <td>{{ $cartLocation->total_items ?? 0 }}</td>
                    <td>{{ number_format($cartLocation->total_with_tax ?? 0, 2) }}€</td>
                    <td>{{ $cartLocation->updated_at->format('d/m/Y à H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Statistiques -->
    <div class="section">
        <h2>📈 Statistiques d'activité</h2>
        <div class="info-grid">
            <span class="info-label">Total commandes d'achat:</span>
            <span class="info-value">{{ $orders->count() }}</span>
            
            <span class="info-label">Total commandes de location:</span>
            <span class="info-value">{{ $rentalOrders->count() }}</span>
            
            <span class="info-label">Montant total dépensé:</span>
            <span class="info-value">{{ number_format($orders->sum('total_amount'), 2) }}€</span>
            
            <span class="info-label">Produits favoris:</span>
            <span class="info-value">{{ $likedProducts->count() }}</span>
            
            <span class="info-label">Liste de souhaits:</span>
            <span class="info-value">{{ $wishlistItems->count() }}</span>
            
            <span class="info-label">Première connexion:</span>
            <span class="info-value">{{ $user->created_at->format('d/m/Y') }}</span>
        </div>
    </div>

    <div class="footer">
        <p><strong>FarmShop</strong> - Archive complète des données personnelles</p>
        <p>Document généré le {{ now()->format('d/m/Y à H:i:s') }} | Conforme RGPD</p>
        <p>Ces données vous appartiennent et peuvent être modifiées ou supprimées à tout moment depuis votre profil.</p>
    </div>
</body>
</html>
