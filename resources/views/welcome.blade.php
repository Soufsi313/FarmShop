@extends('layouts.app')

@section('title', 'FarmShop - Votre marketplace agricole')

@section('content')
<div x-data="farmShopHome">
    <!-- Hero Section -->
    <section class="hero-bg bg-gradient-to-br from-white via-farm-green-50 to-farm-orange-100 py-20">
        <div class="container mx-auto px-6 text-center">
            <h1 class="text-5xl md:text-6xl font-bold text-farm-green-800 mb-6">
                🌾 Bienvenue chez FarmShop
            </h1>
            <p class="text-xl md:text-2xl text-farm-orange-700 mb-8 max-w-3xl mx-auto">
                Votre marketplace agricole de confiance pour acheter et louer du matériel agricole de qualité
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('products.index') }}" class="bg-farm-green-600 hover:bg-farm-green-700 text-white px-8 py-4 rounded-lg text-lg font-semibold transition-colors shadow-lg hover:shadow-xl">
                    🛒 Découvrir nos produits
                </a>
                <a href="{{ route('rentals.index') }}" class="border-2 border-farm-orange-500 text-farm-orange-600 hover:bg-farm-orange-500 hover:text-white px-8 py-4 rounded-lg text-lg font-semibold transition-colors">
                    📅 Louer du matériel
                </a>
            </div>
            
            @guest
            <!-- Raccourci connexion/inscription pour visiteurs -->
            <div class="mt-8 p-4 bg-white/80 backdrop-blur-sm rounded-lg max-w-md mx-auto">
                <p class="text-farm-green-700 font-medium mb-3">👤 Nouveau client ?</p>
                <div class="flex gap-3">
                    <a href="{{ route('register') }}" class="flex-1 bg-farm-orange-500 hover:bg-farm-orange-600 text-white px-4 py-2 rounded-lg text-center font-medium transition-colors">
                        Créer un compte
                    </a>
                    <a href="{{ route('login') }}" class="flex-1 border border-farm-green-500 text-farm-green-700 hover:bg-farm-green-500 hover:text-white px-4 py-2 rounded-lg text-center font-medium transition-colors">
                        Se connecter
                    </a>
                </div>
            </div>
            @endguest
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center text-farm-green-800 mb-12">
                Pourquoi choisir FarmShop ?
            </h2>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center p-6 rounded-lg shadow-md border border-farm-green-200 hover:border-farm-orange-300 transition-colors">
                    <div class="text-5xl mb-4">🚜</div>
                    <h3 class="text-2xl font-semibold text-farm-green-700 mb-4">Matériel de Qualité</h3>
                    <p class="text-farm-orange-600">
                        Tous nos équipements sont rigoureusement sélectionnés et entretenus pour garantir performance et fiabilité.
                    </p>
                </div>
                <div class="text-center p-6 rounded-lg shadow-md border border-farm-orange-200 hover:border-farm-green-300 transition-colors">
                    <div class="text-5xl mb-4">⚡</div>
                    <h3 class="text-2xl font-semibold text-farm-orange-700 mb-4">Disponibilité Rapide</h3>
                    <p class="text-farm-green-600">
                        Achat immédiat ou location flexible selon vos besoins. Livraison rapide dans toute la région.
                    </p>
                </div>
                <div class="text-center p-6 rounded-lg shadow-md border border-farm-green-200 hover:border-farm-orange-300 transition-colors">
                    <div class="text-5xl mb-4">🛡️</div>
                    <h3 class="text-2xl font-semibold text-farm-green-700 mb-4">Paiement Sécurisé</h3>
                    <p class="text-farm-orange-600">
                        Transactions sécurisées avec Stripe. Vos données personnelles et bancaires sont protégées.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-16 bg-gradient-to-r from-farm-green-50 via-white to-farm-orange-50">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center text-farm-green-800 mb-12">
                Nos Catégories
            </h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow cursor-pointer" @click="navigateToCategory('tracteurs')">
                    <div class="h-48 bg-gradient-to-br from-farm-green-200 to-farm-green-300 flex items-center justify-center">
                        <span class="text-6xl">🚜</span>
                    </div>
                    <div class="p-4">
                        <h3 class="text-xl font-semibold text-farm-green-700">Tracteurs</h3>
                        <p class="text-farm-orange-600 text-sm">Puissants et fiables</p>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow cursor-pointer" @click="navigateToCategory('outils')">
                    <div class="h-48 bg-gradient-to-br from-farm-orange-200 to-farm-orange-300 flex items-center justify-center">
                        <span class="text-6xl">🔧</span>
                    </div>
                    <div class="p-4">
                        <h3 class="text-xl font-semibold text-farm-orange-700">Outils</h3>
                        <p class="text-farm-green-600 text-sm">Précision et durabilité</p>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow cursor-pointer" @click="navigateToCategory('equipements')">
                    <div class="h-48 bg-gradient-to-br from-farm-green-200 to-farm-orange-200 flex items-center justify-center">
                        <span class="text-6xl">⚙️</span>
                    </div>
                    <div class="p-4">
                        <h3 class="text-xl font-semibold text-farm-green-700">Équipements</h3>
                        <p class="text-farm-orange-600 text-sm">Solutions complètes</p>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow cursor-pointer" @click="navigateToCategory('accessoires')">
                    <div class="h-48 bg-gradient-to-br from-farm-orange-200 to-farm-green-200 flex items-center justify-center">
                        <span class="text-6xl">🔩</span>
                    </div>
                    <div class="p-4">
                        <h3 class="text-xl font-semibold text-farm-orange-700">Accessoires</h3>
                        <p class="text-farm-green-600 text-sm">Pièces et compléments</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-gradient-to-r from-farm-green-600 to-farm-orange-600 text-white">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-4xl font-bold mb-6">
                Prêt à moderniser votre exploitation ? 🚀
            </h2>
            <p class="text-xl mb-8 max-w-2xl mx-auto">
                Rejoignez des milliers d'agriculteurs qui font confiance à FarmShop pour leurs équipements agricoles.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/register" class="bg-white text-farm-green-600 hover:bg-gray-100 px-8 py-4 rounded-lg text-lg font-semibold transition-colors">
                    📝 Créer un compte
                </a>
                <a href="{{ route('products.index') }}" class="border-2 border-white text-white hover:bg-white hover:text-farm-orange-600 px-8 py-4 rounded-lg text-lg font-semibold transition-colors">
                    🔍 Explorer le catalogue
                </a>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="text-4xl font-bold text-farm-green-600 mb-2">500+</div>
                    <p class="text-farm-orange-600">Équipements disponibles</p>
                </div>
                <div>
                    <div class="text-4xl font-bold text-farm-orange-600 mb-2">1200+</div>
                    <p class="text-farm-green-600">Clients satisfaits</p>
                </div>
                <div>
                    <div class="text-4xl font-bold text-farm-green-600 mb-2">98%</div>
                    <p class="text-farm-orange-600">Taux de satisfaction</p>
                </div>
                <div>
                    <div class="text-4xl font-bold text-farm-orange-600 mb-2">24h</div>
                    <p class="text-farm-green-600">Support client</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-16 bg-gradient-to-r from-farm-orange-50 via-white to-farm-green-50">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center text-farm-green-800 mb-12">
                Ce que disent nos clients
            </h2>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-farm-green-500">
                    <div class="text-farm-orange-400 text-xl mb-4">⭐⭐⭐⭐⭐</div>
                    <p class="text-gray-600 mb-4">
                        "Excellent service ! J'ai trouvé exactement le tracteur qu'il me fallait. Livraison rapide et matériel en parfait état."
                    </p>
                    <div class="font-semibold text-farm-green-700">- Pierre Martin, Agriculteur</div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-farm-orange-500">
                    <div class="text-farm-orange-400 text-xl mb-4">⭐⭐⭐⭐⭐</div>
                    <p class="text-gray-600 mb-4">
                        "La location m'a permis d'essayer avant d'acheter. Très pratique pour les gros équipements !"
                    </p>
                    <div class="font-semibold text-farm-orange-700">- Marie Dubois, Exploitante</div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-farm-green-500">
                    <div class="text-farm-orange-400 text-xl mb-4">⭐⭐⭐⭐⭐</div>
                    <p class="text-gray-600 mb-4">
                        "Support client réactif et professionnel. Je recommande FarmShop à tous mes collègues."
                    </p>
                    <div class="font-semibold text-farm-green-700">- Jean Lefebvre, GAEC</div>
                </div>
            </div>
        </div>
    </section>

    @guest
    <!-- Section Inscription CTA -->
    <section class="py-16 bg-gradient-to-br from-farm-orange-50 to-farm-green-50">
        <div class="container mx-auto px-6 text-center">
            <div class="max-w-3xl mx-auto">
                <h2 class="text-4xl font-bold text-farm-green-800 mb-6">
                    🚀 Rejoignez la communauté FarmShop
                </h2>
                <p class="text-xl text-farm-orange-700 mb-8">
                    Créez votre compte gratuit et profitez de tous nos services : achats, locations, wishlist et bien plus encore !
                </p>
                
                <div class="grid md:grid-cols-3 gap-6 mb-8">
                    <div class="p-4 bg-white rounded-lg shadow-md">
                        <div class="text-3xl mb-3">💝</div>
                        <h3 class="font-semibold text-farm-green-700 mb-2">Offres exclusives</h3>
                        <p class="text-gray-600 text-sm">Accédez à des prix préférentiels et des promotions réservées aux membres</p>
                    </div>
                    <div class="p-4 bg-white rounded-lg shadow-md">
                        <div class="text-3xl mb-3">📋</div>
                        <h3 class="font-semibold text-farm-green-700 mb-2">Suivi des commandes</h3>
                        <p class="text-gray-600 text-sm">Gérez facilement vos achats et locations depuis votre espace personnel</p>
                    </div>
                    <div class="p-4 bg-white rounded-lg shadow-md">
                        <div class="text-3xl mb-3">❤️</div>
                        <h3 class="font-semibold text-farm-green-700 mb-2">Liste de souhaits</h3>
                        <p class="text-gray-600 text-sm">Sauvegardez vos produits favoris et recevez des alertes de disponibilité</p>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('register') }}" class="bg-farm-green-600 hover:bg-farm-green-700 text-white px-8 py-4 rounded-lg text-lg font-semibold transition-colors shadow-lg hover:shadow-xl">
                        🔥 Créer mon compte gratuit
                    </a>
                    <a href="{{ route('login') }}" class="border-2 border-farm-orange-500 text-farm-orange-600 hover:bg-farm-orange-500 hover:text-white px-8 py-4 rounded-lg text-lg font-semibold transition-colors">
                        J'ai déjà un compte
                    </a>
                </div>
                
                <p class="text-sm text-gray-600 mt-4">
                    ⚡ Inscription rapide • 🔒 100% sécurisé • 📧 Pas de spam
                </p>
            </div>
        </div>
    </section>
    @endguest

    <!-- Newsletter Section -->
    <section class="py-16 bg-gradient-to-r from-farm-green-700 to-farm-orange-700 text-white">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold mb-4">
                📧 Restez informé des nouveautés
            </h2>
            <p class="text-lg mb-8">
                Recevez en avant-première nos nouveaux produits et nos offres exclusives
            </p>
            <div class="max-w-md mx-auto flex gap-4">
                <input type="email" placeholder="Votre adresse email" class="flex-1 px-4 py-3 rounded-lg text-gray-800 focus:outline-none focus:ring-2 focus:ring-farm-orange-400">
                <button class="bg-farm-orange-500 hover:bg-farm-orange-600 px-6 py-3 rounded-lg font-semibold transition-colors">
                    S'abonner
                </button>
            </div>
        </div>
    </section>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('farmShopHome', () => ({
        navigateToCategory(category) {
            window.location.href = `/products?category=${category}`;
        }
    }))
})
</script>
@endsection
