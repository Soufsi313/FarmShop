@extends('layouts.app')

@section('title', 'FarmShop - Votre marketplace agricole')

@section('content')
<div x-data="farmShopHome">
    <!-- Hero Section -->
    <section class="hero-bg bg-gradient-to-br from-green-50 to-amber-50 py-20">
        <div class="container mx-auto px-6 text-center">
            <h1 class="text-5xl md:text-6xl font-bold text-green-800 mb-6">
                🌾 Bienvenue chez FarmShop
            </h1>
            <p class="text-xl md:text-2xl text-amber-700 mb-8 max-w-3xl mx-auto">
                Votre marketplace agricole de confiance pour acheter et louer du matériel agricole de qualité
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('products.index') }}" class="btn-primary bg-green-600 hover:bg-green-700 text-white px-8 py-4 rounded-lg text-lg font-semibold transition-colors">
                    🛒 Découvrir nos produits
                </a>
                <a href="{{ route('rentals.index') }}" class="btn-secondary border-2 border-amber-600 text-amber-600 hover:bg-amber-600 hover:text-white px-8 py-4 rounded-lg text-lg font-semibold transition-colors">
                    📅 Louer du matériel
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center text-green-800 mb-12">
                Pourquoi choisir FarmShop ?
            </h2>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center p-6 rounded-lg shadow-md border border-green-100">
                    <div class="text-5xl mb-4">🚜</div>
                    <h3 class="text-2xl font-semibold text-green-700 mb-4">Matériel de Qualité</h3>
                    <p class="text-amber-600">
                        Tous nos équipements sont rigoureusement sélectionnés et entretenus pour garantir performance et fiabilité.
                    </p>
                </div>
                <div class="text-center p-6 rounded-lg shadow-md border border-green-100">
                    <div class="text-5xl mb-4">⚡</div>
                    <h3 class="text-2xl font-semibold text-green-700 mb-4">Disponibilité Rapide</h3>
                    <p class="text-amber-600">
                        Achat immédiat ou location flexible selon vos besoins. Livraison rapide dans toute la région.
                    </p>
                </div>
                <div class="text-center p-6 rounded-lg shadow-md border border-green-100">
                    <div class="text-5xl mb-4">🛡️</div>
                    <h3 class="text-2xl font-semibold text-green-700 mb-4">Paiement Sécurisé</h3>
                    <p class="text-farm-brown-600">
                        Transactions sécurisées avec Stripe. Vos données personnelles et bancaires sont protégées.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-16 bg-farm-green-50">
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
                        <p class="text-farm-brown-600 text-sm">Puissants et fiables</p>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow cursor-pointer" @click="navigateToCategory('outils')">
                    <div class="h-48 bg-gradient-to-br from-farm-brown-200 to-farm-brown-300 flex items-center justify-center">
                        <span class="text-6xl">🔧</span>
                    </div>
                    <div class="p-4">
                        <h3 class="text-xl font-semibold text-farm-green-700">Outils</h3>
                        <p class="text-farm-brown-600 text-sm">Précision et durabilité</p>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow cursor-pointer" @click="navigateToCategory('equipements')">
                    <div class="h-48 bg-gradient-to-br from-farm-green-200 to-farm-brown-200 flex items-center justify-center">
                        <span class="text-6xl">⚙️</span>
                    </div>
                    <div class="p-4">
                        <h3 class="text-xl font-semibold text-farm-green-700">Équipements</h3>
                        <p class="text-farm-brown-600 text-sm">Solutions complètes</p>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow cursor-pointer" @click="navigateToCategory('accessoires')">
                    <div class="h-48 bg-gradient-to-br from-farm-brown-200 to-farm-green-200 flex items-center justify-center">
                        <span class="text-6xl">🔩</span>
                    </div>
                    <div class="p-4">
                        <h3 class="text-xl font-semibold text-farm-green-700">Accessoires</h3>
                        <p class="text-farm-brown-600 text-sm">Pièces et compléments</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-farm-green-600 text-white">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-4xl font-bold mb-6">
                Prêt à moderniser votre exploitation ?
            </h2>
            <p class="text-xl mb-8 max-w-2xl mx-auto">
                Rejoignez les centaines d'agriculteurs qui nous font confiance pour équiper leurs exploitations.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                @guest
                    <a href="/register" class="bg-white text-farm-green-600 hover:bg-gray-100 px-8 py-4 rounded-lg text-lg font-semibold transition-colors">
                        Créer un compte
                    </a>
                    <a href="/login" class="border-2 border-white text-white hover:bg-white hover:text-farm-green-600 px-8 py-4 rounded-lg text-lg font-semibold transition-colors">
                        Se connecter
                    </a>
                @else
                    <a href="{{ route('products.index') }}" class="bg-white text-farm-green-600 hover:bg-gray-100 px-8 py-4 rounded-lg text-lg font-semibold transition-colors">
                        Commencer mes achats
                    </a>
                @endguest
            </div>
        </div>
    </section>

    <!-- Legal & Trust Section -->
    <section class="py-12 bg-gray-50 border-t">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8 text-center">
                <div>
                    <h3 class="font-semibold text-farm-green-700 mb-3">🏛️ Conformité AFSCA</h3>
                    <p class="text-sm text-farm-brown-600 mb-2">
                        Agréé par l'Agence Fédérale pour la Sécurité de la Chaîne Alimentaire
                    </p>
                    <a href="https://www.afsca.be" target="_blank" rel="noopener" class="text-farm-green-600 hover:text-farm-green-700 text-sm underline">
                        Vérifier notre agrément
                    </a>
                </div>
                <div>
                    <h3 class="font-semibold text-farm-green-700 mb-3">🔒 Protection des données</h3>
                    <p class="text-sm text-farm-brown-600 mb-2">
                        Conformité RGPD et protection de votre vie privée
                    </p>
                    <a href="{{ route('privacy') }}" class="text-farm-green-600 hover:text-farm-green-700 text-sm underline">
                        Politique de confidentialité
                    </a>
                </div>
                <div>
                    <h3 class="font-semibold text-farm-green-700 mb-3">↩️ Retours & Échanges</h3>
                    <p class="text-sm text-farm-brown-600 mb-2">
                        Politique de retour flexible pour votre tranquillité
                    </p>
                    <a href="{{ route('returns') }}" class="text-farm-green-600 hover:text-farm-green-700 text-sm underline">
                        Conditions de retour
                    </a>
                </div>
                <div>
                    <h3 class="font-semibold text-farm-green-700 mb-3">⚖️ Vos droits RGPD</h3>
                    <p class="text-sm text-farm-brown-600 mb-2">
                        Exercez vos droits sur vos données personnelles
                    </p>
                    <a href="{{ route('gdpr-rights') }}" class="text-farm-green-600 hover:text-farm-green-700 text-sm underline">
                        Gérer mes données
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('farmShopHome', () => ({
        navigateToCategory(category) {
            // Navigation vers les produits filtrés par catégorie
            window.location.href = `/products?category=${category}`;
        },

        init() {
            // Animation d'apparition au scroll
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-fade-in');
                    }
                });
            });

            // Observer les sections pour les animations
            document.querySelectorAll('section').forEach(section => {
                observer.observe(section);
            });
        }
    }));
});
</script>

<style>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.6s ease-out;
}

.hero-bg {
    background-image: 
        url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23059669' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='4'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}
</style>
@endsection

