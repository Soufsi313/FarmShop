<footer class="bg-dark text-white">
    <div class="container py-5">
        <div class="row">
            <!-- Logo et description -->
            <div class="col-lg-6 col-md-6 mb-4">
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-seedling fs-2 text-success me-3"></i>
                    <span class="fs-2 fw-bold">FarmShop</span>
                </div>
                <p class="text-light mb-4" style="max-width: 400px;">
                    Votre marketplace agricole de confiance pour acheter et louer du matériel agricole de qualité. 
                    Rejoignez des milliers d'agriculteurs satisfaits.
                </p>
                <div class="d-flex gap-3">
                    <a href="#" class="text-secondary text-decoration-none fs-4 hover-success">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="text-secondary text-decoration-none fs-4 hover-success">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="text-secondary text-decoration-none fs-4 hover-success">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="text-secondary text-decoration-none fs-4 hover-success">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                </div>
            </div>

            <!-- Liens rapides -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="fw-bold mb-3">Liens rapides</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="{{ route('products.index') }}" class="text-light text-decoration-none hover-success">
                            <i class="fas fa-shopping-bag me-2"></i>Tous les produits
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('products.index', ['product_type' => 'rental']) }}" class="text-light text-decoration-none hover-success">
                            <i class="fas fa-calendar-alt me-2"></i>Locations
                        </a>
                    </li>
                    @auth
                        <li class="mb-2">
                            <a href="#" class="text-light text-decoration-none hover-success">
                                <i class="fas fa-box me-2"></i>Mes commandes
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-light text-decoration-none hover-success">
                                <i class="fas fa-calendar-check me-2"></i>Mes locations
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>

            <!-- Support -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="fw-bold mb-3">Support</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="#" class="text-light text-decoration-none hover-success">
                            <i class="fas fa-envelope me-2"></i>Contact
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-light text-decoration-none hover-success">
                            <i class="fas fa-question-circle me-2"></i>FAQ
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-light text-decoration-none hover-success">
                            <i class="fas fa-truck me-2"></i>Livraison
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-light text-decoration-none hover-success">
                            <i class="fas fa-undo me-2"></i>Retours
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('cookies.policy') }}" class="text-light text-decoration-none hover-success">
                            <i class="fas fa-cookie-bite me-2"></i>Politique cookies
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Ligne de séparation -->
        <hr class="border-secondary my-4">
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <small class="text-secondary">
                    © {{ date('Y') }} FarmShop. Tous droits réservés.
                </small>
            </div>
            <div class="col-md-6 text-center text-md-end mt-3 mt-md-0">
                <div class="d-flex justify-content-center justify-content-md-end gap-3">
                    <a href="#" class="text-secondary text-decoration-none small hover-success">
                        Mentions légales
                    </a>
                    <a href="#" class="text-secondary text-decoration-none small hover-success">
                        Politique de confidentialité
                    </a>
                    <a href="#" class="text-secondary text-decoration-none small hover-success">
                        CGV
                    </a>
                    <a href="#" onclick="window.reopenCookiePreferences?.()" class="text-secondary text-decoration-none small hover-success">
                        <i class="fas fa-cookie-bite me-1"></i>
                        Gérer les cookies
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
.hover-success:hover {
    color: var(--bs-success) !important;
    transition: color 0.3s ease;
}
</style>
