<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'FarmShop') }} - Votre marketplace agricole</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

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
<body class="font-sans antialiased bg-gray-50">
    <div id="app">
        <!-- Navigation Header -->
        @include('components.navigation')

        <!-- Page Content -->
        <main>
            <!-- Hero Section -->
            <section class="hero-section text-white">
                <div class="container">
                    <div class="row justify-content-center text-center">
                        <div class="col-lg-8">
                            <h1 class="display-3 fw-bold mb-4">
                                Bienvenue sur <span class="text-warning">FarmShop</span>
                            </h1>
                            <p class="lead mb-5">
                                Votre marketplace agricole pour acheter et louer du matériel agricole de qualité
                            </p>
                            <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                                <a href="{{ route('products.index') }}" 
                                   class="btn btn-light btn-lg">
                                    <i class="fas fa-shopping-bag me-2"></i>
                                    Découvrir nos produits
                                </a>
                                <a href="{{ route('products.index', ['type' => 'rental']) }}" 
                                   class="btn btn-outline-light btn-lg">
                                    <i class="fas fa-calendar-alt me-2"></i>
                                    Voir les locations
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Features Section -->
            <section class="py-5 bg-light">
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
                            <div class="feature-icon bg-success bg-opacity-10">
                                <i class="fas fa-shopping-cart fa-2x text-success"></i>
                            </div>
                            <h4 class="fw-bold mb-3">Achat sécurisé</h4>
                            <p class="text-muted">
                                Achetez du matériel agricole neuf et d'occasion avec garantie et livraison rapide
                            </p>
                        </div>

                        <!-- Feature 2 -->
                        <div class="col-md-4 text-center">
                            <div class="feature-icon bg-primary bg-opacity-10">
                                <i class="fas fa-calendar-alt fa-2x text-primary"></i>
                            </div>
                            <h4 class="fw-bold mb-3">Location flexible</h4>
                            <p class="text-muted">
                                Louez du matériel pour vos besoins ponctuels avec des tarifs compétitifs
                            </p>
                        </div>

                        <!-- Feature 3 -->
                        <div class="col-md-4 text-center">
                            <div class="feature-icon bg-warning bg-opacity-10">
                                <i class="fas fa-shield-alt fa-2x text-warning"></i>
                            </div>
                            <h4 class="fw-bold mb-3">Qualité garantie</h4>
                            <p class="text-muted">
                                Tous nos équipements sont vérifiés et certifiés par nos experts
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Categories Section -->
            <section class="py-5">
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
                            <div class="card h-100 border-0 shadow-sm card-hover">
                                <div class="card-body text-center p-4">
                                    <div class="mb-4 p-4 bg-success bg-opacity-10 rounded-circle d-inline-block">
                                        <i class="fas fa-tractor fa-3x text-success"></i>
                                    </div>
                                    <h5 class="card-title fw-bold">Tracteurs</h5>
                                    <p class="card-text text-muted">
                                        Tracteurs agricoles neufs et d'occasion
                                    </p>
                                    <a href="#" class="btn btn-outline-success">
                                        Voir plus <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Catégorie 2 -->
                        <div class="col-sm-6 col-lg-3">
                            <div class="card h-100 border-0 shadow-sm card-hover">
                                <div class="card-body text-center p-4">
                                    <div class="mb-4 p-4 bg-primary bg-opacity-10 rounded-circle d-inline-block">
                                        <i class="fas fa-tools fa-3x text-primary"></i>
                                    </div>
                                    <h5 class="card-title fw-bold">Outils</h5>
                                    <p class="card-text text-muted">
                                        Outillage et équipements divers
                                    </p>
                                    <a href="#" class="btn btn-outline-primary">
                                        Voir plus <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Catégorie 3 -->
                        <div class="col-sm-6 col-lg-3">
                            <div class="card h-100 border-0 shadow-sm card-hover">
                                <div class="card-body text-center p-4">
                                    <div class="mb-4 p-4 bg-warning bg-opacity-10 rounded-circle d-inline-block">
                                        <i class="fas fa-seedling fa-3x text-warning"></i>
                                    </div>
                                    <h5 class="card-title fw-bold">Semences</h5>
                                    <p class="card-text text-muted">
                                        Graines et plants de qualité
                                    </p>
                                    <a href="#" class="btn btn-outline-warning">
                                        Voir plus <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Catégorie 4 -->
                        <div class="col-sm-6 col-lg-3">
                            <div class="card h-100 border-0 shadow-sm card-hover">
                                <div class="card-body text-center p-4">
                                    <div class="mb-4 p-4 bg-danger bg-opacity-10 rounded-circle d-inline-block">
                                        <i class="fas fa-truck fa-3x text-danger"></i>
                                    </div>
                                    <h5 class="card-title fw-bold">Transport</h5>
                                    <p class="card-text text-muted">
                                        Véhicules et remorques agricoles
                                    </p>
                                    <a href="#" class="btn btn-outline-danger">
                                        Voir plus <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Call to Action -->
            <section class="py-5 bg-success text-white">
                <div class="container text-center">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <h2 class="display-5 fw-bold mb-4">Prêt à démarrer ?</h2>
                            <p class="lead mb-4">
                                Rejoignez des milliers d'agriculteurs qui font confiance à FarmShop
                            </p>
                            @guest
                                <a href="{{ route('register') }}" 
                                   class="btn btn-light btn-lg">
                                    <i class="fas fa-user-plus me-2"></i>
                                    Créer mon compte gratuitement
                                </a>
                            @else
                                <a href="{{ route('products.index') }}" 
                                   class="btn btn-light btn-lg">
                                    <i class="fas fa-shopping-bag me-2"></i>
                                    Découvrir nos produits
                                </a>
                            @endguest
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <!-- Footer -->
        @include('components.footer')
    </div>
</body>
</html>
