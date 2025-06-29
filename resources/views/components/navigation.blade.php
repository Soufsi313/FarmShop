<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm border-bottom">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand text-success fw-bold" href="{{ route('welcome') }}">
            <i class="fas fa-seedling me-2"></i>
            FarmShop
        </a>

        <!-- Mobile toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navigation -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <!-- Accueil -->
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('welcome') }}">
                        <i class="fas fa-home me-1"></i> Accueil
                    </a>
                </li>

                <!-- Produits -->
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('products.index') }}">
                        <i class="fas fa-shopping-bag me-1"></i> Produits
                    </a>
                </li>

                @auth
                    <!-- Panier -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-shopping-cart me-1"></i> Panier
                            <span class="badge bg-success ms-1">3</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">
                                <i class="fas fa-shopping-cart me-2 text-success"></i> Panier Achat
                            </a></li>
                            <li><a class="dropdown-item" href="#">
                                <i class="fas fa-calendar-alt me-2 text-primary"></i> Panier Location
                            </a></li>
                        </ul>
                    </li>

                    <!-- Wishlist -->
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-heart me-1"></i> Wishlist
                        </a>
                    </li>

                    <!-- Mes commandes -->
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-box me-1"></i> Mes commandes
                        </a>
                    </li>

                    <!-- Mes locations -->
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-calendar-check me-1"></i> Mes locations
                        </a>
                    </li>

                    @if(Auth::user()->hasRole('admin') || Auth::user()->can('access admin panel'))
                        <!-- Panel Admin -->
                        <li class="nav-item">
                            <a class="nav-link text-danger" href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-cogs me-1"></i> Panel Admin
                            </a>
                        </li>
                    @endif
                @endauth

                <!-- Blog -->
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-blog me-1"></i> Blog
                    </a>
                </li>

                <!-- Contact -->
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-envelope me-1"></i> Contact
                    </a>
                </li>
            </ul>

            <!-- Menu utilisateur -->
            <ul class="navbar-nav">
                @auth
                    <!-- Informations utilisateur -->
                    <li class="nav-item d-flex align-items-center me-3">
                        <img src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&color=7F9CF5&background=EBF4FF' }}" 
                             alt="{{ Auth::user()->name }}" class="rounded-circle me-2" width="30" height="30">
                        <span class="text-dark">{{ Auth::user()->name }}</span>
                    </li>
                    
                    <!-- Lien Mon profil -->
                    <li class="nav-item me-2">
                        <a href="{{ route('profile.show') }}" class="btn btn-outline-primary">
                            <i class="fas fa-user me-1"></i> Mon profil
                        </a>
                    </li>
                    
                    <!-- Bouton déconnexion direct -->
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="fas fa-sign-out-alt me-1"></i> Déconnexion
                            </button>
                        </form>
                    </li>
                @else
                    <li class="nav-item me-2">
                        <a href="{{ route('login') }}" class="btn btn-outline-success">
                            <i class="fas fa-sign-in-alt me-1"></i> Connexion
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('register') }}" class="btn btn-success">
                            <i class="fas fa-user-plus me-1"></i> S'inscrire
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
