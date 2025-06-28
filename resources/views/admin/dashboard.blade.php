<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin - FarmShop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .admin-sidebar {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            min-height: 100vh;
            color: white;
        }
        
        .admin-main {
            background-color: #f8f9fa;
            min-height: 100vh;
        }
        
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 30px;
            color: white;
        }
        
        .stat-icon.users { background: linear-gradient(45deg, #007bff, #0056b3); }
        .stat-icon.products { background: linear-gradient(45deg, #28a745, #1e7e34); }
        .stat-icon.orders { background: linear-gradient(45deg, #ffc107, #e0a800); }
        .stat-icon.blogs { background: linear-gradient(45deg, #6f42c1, #5a2d91); }
        
        .admin-nav-link {
            color: rgba(255,255,255,0.8);
            padding: 15px 20px;
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }
        
        .admin-nav-link:hover {
            color: white;
            background-color: rgba(255,255,255,0.1);
            text-decoration: none;
        }
        
        .admin-nav-link i {
            margin-right: 10px;
            width: 20px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar Admin -->
            <div class="col-md-3 col-lg-2 admin-sidebar p-0">
                <div class="p-4">
                    <h4 class="text-center mb-4">
                        <i class="fas fa-shield-alt me-2"></i>
                        Admin Panel
                    </h4>
                    
                    <div class="text-center mb-4">
                        <img src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&color=ffffff&background=007bff' }}" 
                             alt="{{ Auth::user()->name }}" class="rounded-circle" width="60" height="60">
                        <div class="mt-2">
                            <small>{{ Auth::user()->name }}</small><br>
                            <small class="text-warning">{{ Auth::user()->getRoleNames()->implode(', ') }}</small>
                        </div>
                    </div>
                </div>
                
                <nav>
                    <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'bg-primary' : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        Tableau de bord
                    </a>
                    <a href="{{ route('admin.users') }}" class="admin-nav-link {{ request()->routeIs('admin.users') ? 'bg-primary' : '' }}">
                        <i class="fas fa-users"></i>
                        Utilisateurs
                    </a>
                    <a href="#" class="admin-nav-link">
                        <i class="fas fa-box"></i>
                        Produits
                    </a>
                    <a href="#" class="admin-nav-link">
                        <i class="fas fa-shopping-cart"></i>
                        Commandes
                    </a>
                    <a href="#" class="admin-nav-link">
                        <i class="fas fa-blog"></i>
                        Blog
                    </a>
                    <a href="#" class="admin-nav-link">
                        <i class="fas fa-chart-bar"></i>
                        Analytics
                    </a>
                    
                    <hr class="my-4" style="border-color: rgba(255,255,255,0.2);">
                    
                    <a href="{{ route('dashboard') }}" class="admin-nav-link">
                        <i class="fas fa-arrow-left"></i>
                        Retour au site
                    </a>
                </nav>
            </div>
            
            <!-- Contenu principal -->
            <div class="col-md-9 col-lg-10 admin-main p-0">
                <div class="p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1>Tableau de bord administrateur</h1>
                        <div class="text-muted">
                            <i class="fas fa-calendar me-2"></i>
                            {{ now()->format('d/m/Y H:i') }}
                        </div>
                    </div>
                    
                    <!-- Statistiques -->
                    <div class="row mb-5">
                        <div class="col-md-3 mb-4">
                            <div class="stat-card">
                                <div class="stat-icon users">
                                    <i class="fas fa-users"></i>
                                </div>
                                <h3>{{ $stats['users_count'] }}</h3>
                                <p class="text-muted mb-0">Utilisateurs</p>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4">
                            <div class="stat-card">
                                <div class="stat-icon products">
                                    <i class="fas fa-box"></i>
                                </div>
                                <h3>{{ $stats['products_count'] }}</h3>
                                <p class="text-muted mb-0">Produits</p>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4">
                            <div class="stat-card">
                                <div class="stat-icon orders">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <h3>{{ $stats['orders_count'] }}</h3>
                                <p class="text-muted mb-0">Commandes</p>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4">
                            <div class="stat-card">
                                <div class="stat-icon blogs">
                                    <i class="fas fa-blog"></i>
                                </div>
                                <h3>{{ $stats['blogs_count'] }}</h3>
                                <p class="text-muted mb-0">Articles blog</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Activité récente -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">
                                        <i class="fas fa-users me-2"></i>
                                        Utilisateurs récents
                                    </h5>
                                </div>
                                <div class="card-body">
                                    @if($stats['recent_users']->count() > 0)
                                        @foreach($stats['recent_users'] as $user)
                                            <div class="d-flex align-items-center mb-3">
                                                <img src="{{ $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&size=40' }}" 
                                                     alt="{{ $user->name }}" class="rounded-circle me-3" width="40" height="40">
                                                <div>
                                                    <strong>{{ $user->name }}</strong><br>
                                                    <small class="text-muted">{{ $user->email }}</small><br>
                                                    <small class="text-success">{{ $user->created_at->diffForHumans() }}</small>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-muted">Aucun utilisateur récent</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0">
                                        <i class="fas fa-shopping-cart me-2"></i>
                                        Commandes récentes
                                    </h5>
                                </div>
                                <div class="card-body">
                                    @if($stats['recent_orders']->count() > 0)
                                        @foreach($stats['recent_orders'] as $order)
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <div>
                                                    <strong>Commande #{{ $order->id }}</strong><br>
                                                    <small class="text-muted">{{ $order->user->name ?? 'N/A' }}</small><br>
                                                    <small class="text-success">{{ $order->created_at->diffForHumans() }}</small>
                                                </div>
                                                <span class="badge bg-primary">{{ $order->status ?? 'En attente' }}</span>
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-muted">Aucune commande récente</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
