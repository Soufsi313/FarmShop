<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - FarmShop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .admin-sidebar {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            min-height: 100vh;
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            z-index: 1000;
        }
        
        .admin-main {
            background-color: #f8f9fa;
            min-height: 100vh;
            margin-left: 250px;
            padding: 20px;
        }
        
        .admin-sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 12px 20px;
            border-radius: 8px;
            margin: 2px 10px;
            transition: all 0.3s ease;
        }
        
        .admin-sidebar .nav-link:hover,
        .admin-sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(5px);
        }
        
        .admin-header {
            background: white;
            padding: 15px 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
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
        
        .logo-section {
            padding: 30px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }
        
        .logo-section h4 {
            margin: 0;
            font-weight: bold;
        }
        
        .user-info {
            padding: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: auto;
        }
        
        @media (max-width: 768px) {
            .admin-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .admin-sidebar.show {
                transform: translateX(0);
            }
            
            .admin-main {
                margin-left: 0;
            }
        }
        
        .sidebar-toggle {
            display: none;
        }
        
        @media (max-width: 768px) {
            .sidebar-toggle {
                display: block;
                position: fixed;
                top: 20px;
                left: 20px;
                z-index: 1001;
                background: #2c3e50;
                color: white;
                border: none;
                padding: 10px;
                border-radius: 5px;
            }
        }
    </style>
</head>
<body>
    <!-- Mobile toggle button -->
    <button class="sidebar-toggle btn" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <div class="admin-sidebar" id="adminSidebar">
        <div class="d-flex flex-column h-100">
            <!-- Logo -->
            <div class="logo-section">
                <h4><i class="fas fa-leaf me-2"></i>FarmShop</h4>
                <small>Panel d'administration</small>
            </div>
            
            <!-- Navigation -->
            <nav class="nav flex-column flex-grow-1 pt-4">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                </a>
                
                <a class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                    <i class="fas fa-users me-2"></i>Utilisateurs
                </a>
                
                <a class="nav-link {{ request()->routeIs('admin.products*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
                    <i class="fas fa-box me-2"></i>Produits
                </a>
                
                <a class="nav-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                    <i class="fas fa-layer-group me-2"></i>Catégories
                </a>
                
                <a class="nav-link {{ request()->routeIs('admin.orders*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
                    <i class="fas fa-shopping-cart me-2"></i>Commandes
                </a>
                
                <a class="nav-link {{ request()->routeIs('admin.messages*') ? 'active' : '' }}" href="{{ route('admin.messages.index') }}">
                    <i class="fas fa-envelope me-2"></i>Messages
                    @php
                        $unreadCount = \App\Models\AdminMessage::where('status', 'pending')->count();
                    @endphp
                    @if($unreadCount > 0)
                        <span class="badge bg-warning ms-2">{{ $unreadCount }}</span>
                    @endif
                </a>
                
                <hr class="my-3" style="border-color: rgba(255, 255, 255, 0.2);">
                
                <a class="nav-link" href="#">
                    <i class="fas fa-chart-line me-2"></i>Statistiques
                </a>
                
                <a class="nav-link" href="#">
                    <i class="fas fa-cog me-2"></i>Paramètres
                </a>
                
                <hr class="my-3" style="border-color: rgba(255, 255, 255, 0.2);">
                
                <a class="nav-link" href="{{ route('welcome') }}" target="_blank">
                    <i class="fas fa-external-link-alt me-2"></i>Voir le site
                </a>
            </nav>
            
            <!-- User info -->
            <div class="user-info">
                <div class="d-flex align-items-center mb-3">
                    @if(auth()->user()->profile_photo_path)
                        <img src="{{ asset('storage/'.auth()->user()->profile_photo_path) }}" alt="Avatar" class="rounded-circle me-3" width="40" height="40">
                    @else
                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                            <span class="text-dark fw-bold">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                        </div>
                    @endif
                    <div class="flex-grow-1">
                        <div class="fw-bold">{{ auth()->user()->name }}</div>
                        <small class="opacity-75">Administrateur</small>
                    </div>
                </div>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm w-100">
                        <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Main content -->
    <div class="admin-main">
        <!-- Header -->
        <div class="admin-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">@yield('title', 'Dashboard')</h1>
                    <small class="text-muted">{{ now()->format('l d F Y') }}</small>
                </div>
                <div class="d-flex align-items-center">
                    <span class="badge bg-success me-3">
                        <i class="fas fa-circle me-1"></i>En ligne
                    </span>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-bell"></i>
                            <span class="badge bg-danger">3</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><h6 class="dropdown-header">Notifications</h6></li>
                            <li><a class="dropdown-item" href="#">Nouvelle commande #000123</a></li>
                            <li><a class="dropdown-item" href="#">Produit en rupture de stock</a></li>
                            <li><a class="dropdown-item" href="#">Nouvel utilisateur inscrit</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-center" href="#">Voir toutes</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Page content -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @yield('content')
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            sidebar.classList.toggle('show');
        }
        
        // Fermer la sidebar sur mobile quand on clique en dehors
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('adminSidebar');
            const toggle = document.querySelector('.sidebar-toggle');
            
            if (window.innerWidth <= 768 && 
                !sidebar.contains(event.target) && 
                !toggle.contains(event.target) && 
                sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
            }
        });
    </script>
    
    @yield('scripts')
</body>
</html>
