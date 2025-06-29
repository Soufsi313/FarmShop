<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'FarmShop') }} - Votre marketplace agricole</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
</head>
<body class="font-sans antialiased">
    <div id="app">
        <!-- Navigation Header -->
        @include('components.navigation')

        <!-- Page Content -->
        <main>
            <!-- Hero Section -->
            <section class="hero-section text-white">
                <div class="container">
                    <div class="row justify-content-center text-center">
                        <div class="col-lg-10">
                            <div class="hero-content animate-fade-in">
                                <h1 class="hero-title">
                                    Bienvenue sur <span class="text-warning">FarmShop</span>
                                </h1>
                                <p class="hero-subtitle">
                                    Votre marketplace agricole de confiance - Achat, location et expertise au service de votre réussite
                                </p>
                                <div class="d-flex flex-column flex-md-row gap-3 justify-content-center mt-4">
                                    <a href="#" class="btn btn-primary-custom btn-custom">
                                        <i class="fas fa-shopping-cart me-2"></i>
                                        Découvrir nos produits
                                    </a>
                                    <a href="#" class="btn btn-outline-custom btn-custom">
                                        <i class="fas fa-calendar-alt me-2"></i>
                                        Explorer les locations
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Features Section -->
            <section class="feature-section">
                <div class="container">
                    <div class="row text-center mb-5">
                        <div class="col-lg-8 mx-auto">
                            <h2 class="display-5 fw-bold mb-4">Pourquoi choisir FarmShop ?</h2>
                            <p class="lead text-muted">
                                Une plateforme complète pour tous vos besoins en matériel agricole
                            </p>
                        </div>
                    </div>
                    
                    <div class="row g-4">
                        <!-- Feature 1 -->
                        <div class="col-md-4 text-center">
                            <div class="feature-icon">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <h4 class="feature-title">Achat sécurisé</h4>
                            <p class="feature-description">
                                Achetez du matériel agricole neuf et d'occasion avec garantie et livraison rapide dans toute la France
                            </p>
                        </div>

                        <!-- Feature 2 -->
                        <div class="col-md-4 text-center">
                            <div class="feature-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <h4 class="feature-title">Location flexible</h4>
                            <p class="feature-description">
                                Louez du matériel pour vos besoins ponctuels avec des tarifs compétitifs et une disponibilité maximale
                            </p>
                        </div>

                        <!-- Feature 3 -->
                        <div class="col-md-4 text-center">
                            <div class="feature-icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <h4 class="feature-title">Qualité garantie</h4>
                            <p class="feature-description">
                                Tous nos équipements sont vérifiés et certifiés par nos experts pour une utilisation en toute sérénité
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Categories Section -->
            <section class="categories-section">
                <div class="container">
                    <div class="row text-center mb-5">
                        <div class="col-lg-8 mx-auto">
                            <h2 class="display-5 fw-bold mb-4">Nos catégories populaires</h2>
                            <p class="lead text-muted">
                                Trouvez le matériel adapté à vos activités agricoles
                            </p>
                        </div>
                    </div>
                    
                    <div class="row g-4">
                        <!-- Catégorie 1 -->
                        <div class="col-sm-6 col-lg-3">
                            <div class="category-card card-hover">
                                <div class="category-icon">
                                    <i class="fas fa-tractor"></i>
                                </div>
                                <h5 class="category-title">Tracteurs</h5>
                                <p class="category-description">
                                    Tracteurs agricoles neufs et d'occasion de toutes puissances
                                </p>
                                <a href="#" class="btn btn-primary-custom btn-sm mt-3">
                                    Voir plus <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Catégorie 2 -->
                        <div class="col-sm-6 col-lg-3">
                            <div class="category-card card-hover">
                                <div class="category-icon">
                                    <i class="fas fa-tools"></i>
                                </div>
                                <h5 class="category-title">Outils</h5>
                                <p class="category-description">
                                    Outillage et équipements divers pour tous types de travaux
                                </p>
                                <a href="#" class="btn btn-primary-custom btn-sm mt-3">
                                    Voir plus <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Catégorie 3 -->
                        <div class="col-sm-6 col-lg-3">
                            <div class="category-card card-hover">
                                <div class="category-icon">
                                    <i class="fas fa-seedling"></i>
                                </div>
                                <h5 class="category-title">Semences</h5>
                                <p class="category-description">
                                    Graines et plants de qualité certifiée pour vos cultures
                                </p>
                                <a href="#" class="btn btn-primary-custom btn-sm mt-3">
                                    Voir plus <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Catégorie 4 -->
                        <div class="col-sm-6 col-lg-3">
                            <div class="category-card card-hover">
                                <div class="category-icon">
                                    <i class="fas fa-truck"></i>
                                </div>
                                <h5 class="category-title">Transport</h5>
                                <p class="category-description">
                                    Véhicules et remorques agricoles pour vos déplacements
                                </p>
                                <a href="#" class="btn btn-primary-custom btn-sm mt-3">
                                    Voir plus <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Statistics Section -->
            <section class="py-5 bg-white">
                <div class="container">
                    <div class="row text-center">
                        <div class="col-md-3 mb-4">
                            <div class="h2 fw-bold text-primary mb-1">
                                <i class="fas fa-users me-2"></i>5000+
                            </div>
                            <p class="text-muted mb-0">Clients satisfaits</p>
                        </div>
                        <div class="col-md-3 mb-4">
                            <div class="h2 fw-bold text-success mb-1">
                                <i class="fas fa-box me-2"></i>2500+
                            </div>
                            <p class="text-muted mb-0">Produits disponibles</p>
                        </div>
                        <div class="col-md-3 mb-4">
                            <div class="h2 fw-bold text-warning mb-1">
                                <i class="fas fa-calendar me-2"></i>1200+
                            </div>
                            <p class="text-muted mb-0">Locations réalisées</p>
                        </div>
                        <div class="col-md-3 mb-4">
                            <div class="h2 fw-bold text-danger mb-1">
                                <i class="fas fa-star me-2"></i>4.8/5
                            </div>
                            <p class="text-muted mb-0">Note moyenne</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Testimonials Section -->
            <section class="py-5" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">
                <div class="container">
                    <div class="row text-center mb-5">
                        <div class="col-lg-8 mx-auto">
                            <h2 class="display-5 fw-bold mb-4">Ce que disent nos clients</h2>
                            <p class="lead text-muted">
                                Des témoignages authentiques de nos utilisateurs
                            </p>
                        </div>
                    </div>
                    
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body p-4">
                                    <div class="text-warning mb-3">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <p class="card-text">"Excellent service ! J'ai trouvé exactement le tracteur que je cherchais à un prix très compétitif. Livraison rapide et professionnelle."</p>
                                    <div class="d-flex align-items-center mt-3">
                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                            <i class="fas fa-user text-white"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">Jean Dupont</h6>
                                            <small class="text-muted">Agriculteur, Normandie</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body p-4">
                                    <div class="text-warning mb-3">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <p class="card-text">"La location de matériel est très pratique pour mes besoins ponctuels. Interface claire et processus simple."</p>
                                    <div class="d-flex align-items-center mt-3">
                                        <div class="bg-success rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                            <i class="fas fa-user text-white"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">Marie Martin</h6>
                                            <small class="text-muted">Maraîchère, Provence</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body p-4">
                                    <div class="text-warning mb-3">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <p class="card-text">"Support client réactif et professionnel. Je recommande vivement cette plateforme à tous les agriculteurs."</p>
                                    <div class="d-flex align-items-center mt-3">
                                        <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                            <i class="fas fa-user text-white"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">Pierre Legrand</h6>
                                            <small class="text-muted">Éleveur, Bretagne</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- CTA Section -->
            <section class="cta-section">
                <div class="container text-center">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <h2 class="cta-title">Prêt à commencer ?</h2>
                            <p class="cta-description">
                                Rejoignez des milliers d'agriculteurs qui font confiance à FarmShop pour leurs besoins en matériel agricole
                            </p>
                            <div class="d-flex flex-column flex-md-row gap-3 justify-content-center">
                                <a href="#" class="btn btn-outline-custom btn-custom">
                                    <i class="fas fa-user-plus me-2"></i>
                                    Créer un compte gratuit
                                </a>
                                <a href="#" class="btn btn-primary-custom btn-custom" style="background: white; color: var(--primary-color);">
                                    <i class="fas fa-phone me-2"></i>
                                    Nous contacter
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <!-- Footer -->
        @include('components.footer')
    </div>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript pour les animations -->
    <script>
        // Animation au scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observer tous les éléments avec la classe animate-fade-in
        document.addEventListener('DOMContentLoaded', function() {
            const animatedElements = document.querySelectorAll('.feature-icon, .category-card, .card');
            animatedElements.forEach((el, index) => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(30px)';
                el.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
                observer.observe(el);
            });
        });
    </script>
</body>
</html>
