<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Utilisateurs - FarmShop Admin</title>
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
        
        .admin-nav-link {
            color: rgba(255,255,255,0.8);
            padding: 15px 20px;
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }
        
        .admin-nav-link:hover, .admin-nav-link.bg-primary {
            color: white;
            background-color: rgba(255,255,255,0.1);
            text-decoration: none;
        }
        
        .admin-nav-link i {
            margin-right: 10px;
            width: 20px;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            object-fit: cover;
        }
        
        .role-badge {
            font-size: 0.8em;
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
                    <a href="{{ route('admin.dashboard') }}" class="admin-nav-link">
                        <i class="fas fa-tachometer-alt"></i>
                        Tableau de bord
                    </a>
                    <a href="{{ route('admin.users') }}" class="admin-nav-link bg-primary">
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
                        <h1>
                            <i class="fas fa-users me-2"></i>
                            Gestion des utilisateurs
                        </h1>
                        <div class="text-muted">
                            {{ $users->total() }} utilisateurs au total
                        </div>
                    </div>
                    
                    @if(session('success'))
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    <!-- Filtres et recherche -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <form method="GET" class="row g-3">
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="search" 
                                           placeholder="Rechercher par nom ou email..." 
                                           value="{{ request('search') }}">
                                </div>
                                <div class="col-md-3">
                                    <select name="role" class="form-select">
                                        <option value="">Tous les rôles</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                                                {{ ucfirst($role->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select name="verified" class="form-select">
                                        <option value="">Tous</option>
                                        <option value="1" {{ request('verified') == '1' ? 'selected' : '' }}>Email vérifié</option>
                                        <option value="0" {{ request('verified') == '0' ? 'selected' : '' }}>Email non vérifié</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i> Filtrer
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Liste des utilisateurs -->
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Utilisateur</th>
                                            <th>Email</th>
                                            <th>Username</th>
                                            <th>Rôles</th>
                                            <th>Vérifié</th>
                                            <th>Inscription</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users as $user)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&size=40' }}" 
                                                             alt="{{ $user->name }}" class="rounded-circle user-avatar me-3">
                                                        <div>
                                                            <strong>{{ $user->name }}</strong>
                                                            @if($user->is_newsletter_subscribed)
                                                                <br><small class="text-success">
                                                                    <i class="fas fa-envelope me-1"></i>Newsletter
                                                                </small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $user->email }}</td>
                                                <td>
                                                    <code>{{ $user->username }}</code>
                                                </td>
                                                <td>
                                                    @foreach($user->roles as $role)
                                                        <span class="badge bg-{{ $role->name === 'admin' ? 'danger' : 'primary' }} role-badge me-1">
                                                            {{ ucfirst($role->name) }}
                                                        </span>
                                                    @endforeach
                                                    @if($user->roles->isEmpty())
                                                        <span class="text-muted">Aucun rôle</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($user->email_verified_at)
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-check me-1"></i>Vérifié
                                                        </span>
                                                        <br><small class="text-muted">{{ $user->email_verified_at->format('d/m/Y') }}</small>
                                                    @else
                                                        <span class="badge bg-warning">
                                                            <i class="fas fa-clock me-1"></i>En attente
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $user->created_at->format('d/m/Y') }}<br>
                                                    <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <!-- Assigner rôle admin -->
                                                        @if(!$user->hasRole('admin'))
                                                            <form method="POST" action="{{ route('admin.assign-role', $user) }}" class="d-inline">
                                                                @csrf
                                                                <input type="hidden" name="role" value="admin">
                                                                <button type="submit" class="btn btn-sm btn-danger" 
                                                                        onclick="return confirm('Assigner le rôle admin à cet utilisateur ?')">
                                                                    <i class="fas fa-crown"></i>
                                                                </button>
                                                            </form>
                                                        @else
                                                            <form method="POST" action="{{ route('admin.remove-role', $user) }}" class="d-inline">
                                                                @csrf
                                                                <input type="hidden" name="role" value="admin">
                                                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                                        onclick="return confirm('Retirer le rôle admin à cet utilisateur ?')">
                                                                    <i class="fas fa-crown"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                        
                                                        <!-- Voir profil -->
                                                        <a href="#" class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Pagination -->
                            @if($users->hasPages())
                                <div class="d-flex justify-content-center mt-4">
                                    {{ $users->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
