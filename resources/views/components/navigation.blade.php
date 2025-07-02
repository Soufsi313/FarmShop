<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm border-bottom" style="display: block !important; position: relative !important; z-index: 1000 !important; background-color: white !important;">
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
                    <!-- Panier d'achat -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cart.index') }}">
                            <i class="fas fa-shopping-cart me-1"></i> Panier d'achat
                            <span class="badge bg-success ms-1" id="cart-count">0</span>
                        </a>
                    </li>

                    <!-- Panier de location -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cart-location.index') }}">
                            <i class="fas fa-calendar-alt me-1"></i> Panier location
                            <span class="badge bg-info ms-1" id="rental-cart-count">0</span>
                        </a>
                    </li>

                    <!-- Wishlist -->
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-heart me-1"></i> Wishlist
                        </a>
                    </li>

                    <!-- Mes commandes -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('orders.user.index') }}">
                            <i class="fas fa-box me-1"></i> Mes commandes
                        </a>
                    </li>

                    <!-- Mes locations -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('order-locations.index') }}">
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

@auth
<script>
// Mettre à jour le compteur du panier au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    updateCartCount();
    updateRentalCartCount();
});

function updateCartCount() {
    fetch('/api/cart/count', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        credentials: 'same-origin'
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        const cartCountElement = document.getElementById('cart-count');
        if (cartCountElement && data.success && data.count !== undefined) {
            cartCountElement.textContent = data.count;
            cartCountElement.style.display = data.count > 0 ? 'inline' : 'none';
        }
    })
    .catch(error => {
        console.error('Erreur lors de la récupération du compteur panier:', error);
        // Masquer le badge en cas d'erreur
        const cartCountElement = document.getElementById('cart-count');
        if (cartCountElement) {
            cartCountElement.style.display = 'none';
        }
    });
}

function updateRentalCartCount() {
    fetch('/panier-location/api/count', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        credentials: 'same-origin'
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        const rentalCartCountElement = document.getElementById('rental-cart-count');
        if (rentalCartCountElement && data.success && data.count !== undefined) {
            rentalCartCountElement.textContent = data.count;
            rentalCartCountElement.style.display = data.count > 0 ? 'inline' : 'none';
        }
    })
    .catch(error => {
        console.error('Erreur lors de la récupération du compteur panier location:', error);
        // Masquer le badge en cas d'erreur
        const rentalCartCountElement = document.getElementById('rental-cart-count');
        if (rentalCartCountElement) {
            rentalCartCountElement.style.display = 'none';
        }
    });
}

// Fonctions globales pour mettre à jour les paniers depuis d'autres pages
window.updateCartCount = updateCartCount;
window.updateRentalCartCount = updateRentalCartCount;

// Mettre à jour les compteurs au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    updateCartCount();
    updateRentalCartCount();
});
</script>
@endauth
