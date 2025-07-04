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
        
        .nav-section-title {
            padding: 8px 16px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
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
                
                <!-- Section Offres Spéciales -->
                <div class="nav-section-title mt-3 mb-2">
                    <small class="text-muted fw-bold">OFFRES SPÉCIALES</small>
                </div>
                
                <a class="nav-link {{ request()->routeIs('admin.special-offers.index') ? 'active' : '' }}" href="{{ route('admin.special-offers.index') }}">
                    <i class="fas fa-percent me-2"></i>Toutes les offres
                    @php
                        $activeOffersCount = \App\Models\SpecialOffer::available()->count();
                    @endphp
                    @if($activeOffersCount > 0)
                        <span class="badge bg-success ms-2">{{ $activeOffersCount }}</span>
                    @endif
                </a>
                
                <a class="nav-link {{ request()->routeIs('admin.special-offers.create') ? 'active' : '' }}" href="{{ route('admin.special-offers.create') }}">
                    <i class="fas fa-plus me-2"></i>Créer une offre
                </a>
                
                <!-- Section Locations -->
                <div class="nav-section-title mt-3 mb-2">
                    <small class="text-muted fw-bold">LOCATIONS</small>
                </div>
                
                <a class="nav-link {{ request()->routeIs('admin.locations.dashboard') ? 'active' : '' }}" href="{{ route('admin.locations.dashboard') }}">
                    <i class="fas fa-chart-line me-2"></i>Tableau de bord
                </a>
                
                <a class="nav-link {{ request()->routeIs('admin.locations.index') ? 'active' : '' }}" href="{{ route('admin.locations.index') }}">
                    <i class="fas fa-calendar-alt me-2"></i>Toutes les locations
                </a>
                
                <!-- Section Automatisation -->
                <div class="nav-section-title mt-3 mb-2">
                    <small class="text-muted fw-bold">AUTOMATISATION</small>
                </div>
                
                <a class="nav-link {{ request()->routeIs('admin.orders.automation*') ? 'active' : '' }}" href="{{ route('admin.orders.automation') }}">
                    <i class="fas fa-robot me-2"></i>Automatisation
                </a>
                
                <a class="nav-link {{ request()->routeIs('admin.orders.cancellation*') ? 'active' : '' }}" href="{{ route('admin.orders.cancellation') }}">
                    <i class="fas fa-ban me-2"></i>Annulations & Retours
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
                
                <a class="nav-link {{ request()->routeIs('admin.notifications*') ? 'active' : '' }}" href="{{ route('admin.notifications.index') }}">
                    <i class="fas fa-bell me-2"></i>Notifications
                    <span class="badge bg-danger ms-2 d-none" id="sidebarNotificationBadge">0</span>
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
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" id="notificationDropdown">
                            <i class="fas fa-bell"></i>
                            <span class="badge bg-danger d-none" id="notificationBadge">0</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" style="width: 320px;" id="notificationDropdownMenu">
                            <li>
                                <h6 class="dropdown-header d-flex justify-content-between align-items-center">
                                    Notifications
                                    <button class="btn btn-sm btn-outline-primary" id="markAllReadBtn">
                                        <i class="fas fa-check-double"></i>
                                    </button>
                                </h6>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <div id="notificationDropdownList" style="max-height: 300px; overflow-y: auto;">
                                <li class="text-center py-3">
                                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                                        <span class="visually-hidden">Chargement...</span>
                                    </div>
                                </li>
                            </div>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-center" href="{{ route('admin.notifications.index') }}">
                                    <i class="fas fa-list me-1"></i>Voir toutes les notifications
                                </a>
                            </li>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    
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
        
        // Système de notifications
        $(document).ready(function() {
            // Charger les notifications au démarrage
            loadHeaderNotifications();
            
            // Recharger les notifications toutes les 60 secondes
            setInterval(loadHeaderNotifications, 60000);
            
            // Marquer toutes les notifications comme lues depuis le header
            $('#markAllReadBtn').click(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('admin.notifications.mark-all-read') }}",
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            loadHeaderNotifications();
                            if (typeof toastr !== 'undefined') {
                                toastr.success('Toutes les notifications ont été marquées comme lues');
                            }
                        }
                    },
                    error: function() {
                        if (typeof toastr !== 'undefined') {
                            toastr.error('Erreur lors de la mise à jour');
                        }
                    }
                });
            });
        });
        
        function loadHeaderNotifications() {
            $.ajax({
                url: "{{ route('admin.notifications.index') }}",
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        updateHeaderNotifications(response.notifications, response.count);
                    }
                },
                error: function() {
                    console.error('Erreur lors du chargement des notifications');
                }
            });
        }
        
        function updateHeaderNotifications(notifications, count) {
            // Mise à jour du badge
            if (count > 0) {
                $('#notificationBadge').text(count).removeClass('d-none');
                $('#sidebarNotificationBadge').text(count).removeClass('d-none');
            } else {
                $('#notificationBadge').addClass('d-none');
                $('#sidebarNotificationBadge').addClass('d-none');
            }
            
            // Mise à jour de la liste dans le dropdown
            let html = '';
            if (notifications.length === 0) {
                html = '<li class="text-center py-3 text-muted">Aucune notification</li>';
            } else {
                notifications.slice(0, 5).forEach(function(notification) {
                    let icon = getHeaderNotificationIcon(notification.type);
                    let title = getHeaderNotificationTitle(notification.type, notification.data);
                    let description = getHeaderNotificationDescription(notification.type, notification.data);
                    
                    html += `
                        <li>
                            <a class="dropdown-item py-2" href="#" onclick="markNotificationAsRead('${notification.id}')">
                                <div class="d-flex">
                                    <div class="text-primary me-2">
                                        <i class="${icon}"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold small">${title}</div>
                                        <div class="text-muted small">${description.substring(0, 50)}...</div>
                                        <div class="text-muted" style="font-size: 0.7em;">${notification.created_at}</div>
                                    </div>
                                </div>
                            </a>
                        </li>
                    `;
                });
            }
            
            $('#notificationDropdownList').html(html);
        }
        
        function markNotificationAsRead(id) {
            $.ajax({
                url: `/admin/notifications/${id}/read`,
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        loadHeaderNotifications();
                    }
                }
            });
        }
        
        function getHeaderNotificationIcon(type) {
            const icons = {
                'App\\Notifications\\ProductLowStock': 'fas fa-exclamation-triangle',
                'App\\Notifications\\ProductOutOfStock': 'fas fa-times-circle',
                'App\\Notifications\\OrderStatusChanged': 'fas fa-shopping-cart',
                'App\\Notifications\\RentalOverdue': 'fas fa-clock',
                'default': 'fas fa-bell'
            };
            
            return icons[type] || icons['default'];
        }
        
        function getHeaderNotificationTitle(type, data) {
            const titles = {
                'App\\Notifications\\ProductLowStock': 'Stock faible',
                'App\\Notifications\\ProductOutOfStock': 'Rupture de stock',
                'App\\Notifications\\OrderStatusChanged': 'Commande mise à jour',
                'App\\Notifications\\RentalOverdue': 'Location en retard',
                'default': 'Notification'
            };
            
            return titles[type] || titles['default'];
        }
        
        function getHeaderNotificationDescription(type, data) {
            switch(type) {
                case 'App\\Notifications\\ProductLowStock':
                    return `${data.product_name} (${data.quantity} restant)`;
                case 'App\\Notifications\\ProductOutOfStock':
                    return `${data.product_name} en rupture`;
                case 'App\\Notifications\\OrderStatusChanged':
                    return `Commande ${data.order_number}`;
                case 'App\\Notifications\\RentalOverdue':
                    return `Location ${data.rental_number}`;
                default:
                    return 'Nouvelle notification';
            }
        }
    </script>
    
    @yield('scripts')
</body>
</html>
