<footer class="bg-gray-800 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Logo et description -->
            <div class="col-span-1 md:col-span-2">
                <div class="flex items-center mb-4">
                    <i class="fas fa-seedling text-2xl text-green-400 mr-2"></i>
                    <span class="text-2xl font-bold">FarmShop</span>
                </div>
                <p class="text-gray-300 mb-4 max-w-md">
                    Votre marketplace agricole de confiance pour acheter et louer du matériel agricole de qualité. 
                    Rejoignez des milliers d'agriculteurs satisfaits.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-green-400 transition-colors duration-200">
                        <i class="fab fa-facebook-f text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-green-400 transition-colors duration-200">
                        <i class="fab fa-twitter text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-green-400 transition-colors duration-200">
                        <i class="fab fa-instagram text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-green-400 transition-colors duration-200">
                        <i class="fab fa-linkedin-in text-xl"></i>
                    </a>
                </div>
            </div>

            <!-- Liens rapides -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Liens rapides</h3>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('products.index') }}" class="text-gray-300 hover:text-green-400 transition-colors duration-200">
                            Tous les produits
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('products.index', ['type' => 'rental']) }}" class="text-gray-300 hover:text-green-400 transition-colors duration-200">
                            Locations
                        </a>
                    </li>
                    @auth
                        <li>
                            <a href="#" class="text-gray-300 hover:text-green-400 transition-colors duration-200">
                                Mes commandes
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-gray-300 hover:text-green-400 transition-colors duration-200">
                                Mes locations
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>

            <!-- Support -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Support</h3>
                <ul class="space-y-2">
                    <li>
                        <a href="#" class="text-gray-300 hover:text-green-400 transition-colors duration-200">
                            Contact
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-gray-300 hover:text-green-400 transition-colors duration-200">
                            FAQ
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-gray-300 hover:text-green-400 transition-colors duration-200">
                            Livraison
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-gray-300 hover:text-green-400 transition-colors duration-200">
                            Retours
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('cookies.policy') }}" class="text-gray-300 hover:text-green-400 transition-colors duration-200">
                            Politique cookies
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Ligne de séparation -->
        <div class="border-t border-gray-700 mt-8 pt-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="text-gray-400 text-sm">
                    © {{ date('Y') }} FarmShop. Tous droits réservés.
                </div>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="#" class="text-gray-400 hover:text-green-400 text-sm transition-colors duration-200">
                        Mentions légales
                    </a>
                    <a href="#" class="text-gray-400 hover:text-green-400 text-sm transition-colors duration-200">
                        Politique de confidentialité
                    </a>
                    <a href="#" class="text-gray-400 hover:text-green-400 text-sm transition-colors duration-200">
                        CGV
                    </a>
                    <a href="#" onclick="window.reopenCookiePreferences?.()" class="text-gray-400 hover:text-green-400 text-sm transition-colors duration-200">
                        <i class="fas fa-cookie-bite me-1"></i>
                        Gérer les cookies
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>
