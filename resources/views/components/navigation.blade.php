<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm border-bottom">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand text-success fw-bold" href="{{ route('dashboard') }}">
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
                    <a class="nav-link" href="{{ route('dashboard') }}">
                        <i class="fas fa-home me-1"></i> Accueil
                    </a>
                </li>

                <!-- Produits -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-shopping-bag me-1"></i> Produits
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('products.index') }}">
                            <i class="fas fa-shopping-cart me-2 text-success"></i> Achat
                        </a></li>
                        <li><a class="dropdown-item" href="{{ route('products.index', ['type' => 'rental']) }}">
                            <i class="fas fa-calendar-alt me-2 text-primary"></i> Locations
                        </a></li>
                    </ul>
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
                            <a class="nav-link text-danger" href="#">
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
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                            <img src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&color=7F9CF5&background=EBF4FF' }}" 
                                 alt="{{ Auth::user()->name }}" class="rounded-circle me-2" width="30" height="30">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('profile.show') }}">
                                <i class="fas fa-user me-2"></i> Mon profil
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt me-2"></i> Déconnexion
                                    </button>
                                </form>
                            </li>
                        </ul>
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
