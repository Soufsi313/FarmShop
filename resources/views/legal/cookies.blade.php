@extends('layouts.app')

@section('title', 'Politique de Cookies - FarmShop')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-lg rounded-lg p-8">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">Politique de Cookies</h1>
                <p class="text-gray-600">Gestion des cookies et technologies de suivi</p>
                <p class="text-sm text-gray-500 mt-2">Conforme à la directive ePrivacy - Version du {{ date('d/m/Y') }}</p>
            </div>

            <!-- Introduction -->
            <section class="mb-8">
                <div class="bg-blue-50 border-l-4 border-blue-400 p-6 rounded-r-lg">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-medium text-blue-800 mb-2">Qu'est-ce qu'un cookie ?</h3>
                            <p class="text-blue-700">
                                Un cookie est un petit fichier texte stocké sur votre appareil lorsque vous visitez notre site. 
                                Il nous permet d'améliorer votre expérience de navigation, de mémoriser vos préférences 
                                et d'analyser l'utilisation de notre site.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Types de cookies -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6 border-b-2 border-blue-500 pb-2">
                    Types de cookies utilisés
                </h2>
                <div class="space-y-6">
                    <!-- Cookies essentiels -->
                    <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="flex items-center justify-between mb-3">
                                    <h3 class="text-lg font-semibold text-green-800">Cookies essentiels</h3>
                                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                                        Toujours actifs
                                    </span>
                                </div>
                                <p class="text-green-700 mb-4">
                                    Ces cookies sont indispensables au fonctionnement du site et ne peuvent pas être désactivés. 
                                    Ils permettent la navigation de base, la sécurité et l'accessibilité.
                                </p>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full bg-white border border-green-200 rounded">
                                        <thead class="bg-green-100">
                                            <tr>
                                                <th class="border-b px-4 py-2 text-left text-sm font-semibold text-green-800">{{ __("app.forms.name") }}</th>
                                                <th class="border-b px-4 py-2 text-left text-sm font-semibold text-green-800">Finalité</th>
                                                <th class="border-b px-4 py-2 text-left text-sm font-semibold text-green-800">Durée</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-sm text-green-700">
                                            <tr>
                                                <td class="border-b px-4 py-2 font-mono">XSRF-TOKEN</td>
                                                <td class="border-b px-4 py-2">Protection contre les attaques CSRF</td>
                                                <td class="border-b px-4 py-2">Session</td>
                                            </tr>
                                            <tr>
                                                <td class="border-b px-4 py-2 font-mono">farmshop_session</td>
                                                <td class="border-b px-4 py-2">Maintien de la session utilisateur</td>
                                                <td class="border-b px-4 py-2">2 heures</td>
                                            </tr>
                                            <tr>
                                                <td class="border-b px-4 py-2 font-mono">shopping_cart</td>
                                                <td class="border-b px-4 py-2">Mémorisation du panier d'achat</td>
                                                <td class="border-b px-4 py-2">30 jours</td>
                                            </tr>
                                            <tr>
                                                <td class="border-b px-4 py-2 font-mono">auth_token</td>
                                                <td class="border-b px-4 py-2">Authentification utilisateur</td>
                                                <td class="border-b px-4 py-2">15 jours</td>
                                            </tr>
                                            <tr>
                                                <td class="border-b px-4 py-2 font-mono">cookie_consent</td>
                                                <td class="border-b px-4 py-2">Mémorisation des préférences cookies</td>
                                                <td class="border-b px-4 py-2">1 an</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cookies fonctionnels -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="flex items-center justify-between mb-3">
                                    <h3 class="text-lg font-semibold text-blue-800">Cookies fonctionnels</h3>
                                    <label class="flex items-center cursor-pointer">
                                        <input type="checkbox" class="sr-only" id="functional-cookies" checked>
                                        <div class="relative">
                                            <div class="block bg-blue-600 w-14 h-8 rounded-full"></div>
                                            <div class="dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition"></div>
                                        </div>
                                        <span class="ml-3 text-blue-700 font-medium">Activé</span>
                                    </label>
                                </div>
                                <p class="text-blue-700 mb-4">
                                    Ces cookies améliorent votre expérience en mémorisant vos préférences et choix. 
                                    Ils ne sont pas strictement nécessaires mais rendent votre navigation plus agréable.
                                </p>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full bg-white border border-blue-200 rounded">
                                        <thead class="bg-blue-100">
                                            <tr>
                                                <th class="border-b px-4 py-2 text-left text-sm font-semibold text-blue-800">{{ __("app.forms.name") }}</th>
                                                <th class="border-b px-4 py-2 text-left text-sm font-semibold text-blue-800">Finalité</th>
                                                <th class="border-b px-4 py-2 text-left text-sm font-semibold text-blue-800">Durée</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-sm text-blue-700">
                                            <tr>
                                                <td class="border-b px-4 py-2 font-mono">user_preferences</td>
                                                <td class="border-b px-4 py-2">Langue, devise, région d'affichage</td>
                                                <td class="border-b px-4 py-2">1 an</td>
                                            </tr>
                                            <tr>
                                                <td class="border-b px-4 py-2 font-mono">theme_mode</td>
                                                <td class="border-b px-4 py-2">Mode sombre/clair</td>
                                                <td class="border-b px-4 py-2">1 an</td>
                                            </tr>
                                            <tr>
                                                <td class="border-b px-4 py-2 font-mono">wishlist</td>
                                                <td class="border-b px-4 py-2">Liste de souhaits produits</td>
                                                <td class="border-b px-4 py-2">6 mois</td>
                                            </tr>
                                            <tr>
                                                <td class="border-b px-4 py-2 font-mono">recently_viewed</td>
                                                <td class="border-b px-4 py-2">Produits récemment consultés</td>
                                                <td class="border-b px-4 py-2">30 jours</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cookies analytiques -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="flex items-center justify-between mb-3">
                                    <h3 class="text-lg font-semibold text-yellow-800">Cookies analytiques</h3>
                                    <label class="flex items-center cursor-pointer">
                                        <input type="checkbox" class="sr-only" id="analytics-cookies">
                                        <div class="relative">
                                            <div class="block bg-gray-600 w-14 h-8 rounded-full"></div>
                                            <div class="dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition"></div>
                                        </div>
                                        <span class="ml-3 text-yellow-700 font-medium">Désactivé</span>
                                    </label>
                                </div>
                                <p class="text-yellow-700 mb-4">
                                    Ces cookies nous aident à comprendre comment vous utilisez notre site pour l'améliorer. 
                                    Les données sont anonymisées et ne permettent pas de vous identifier.
                                </p>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full bg-white border border-yellow-200 rounded">
                                        <thead class="bg-yellow-100">
                                            <tr>
                                                <th class="border-b px-4 py-2 text-left text-sm font-semibold text-yellow-800">Service</th>
                                                <th class="border-b px-4 py-2 text-left text-sm font-semibold text-yellow-800">Finalité</th>
                                                <th class="border-b px-4 py-2 text-left text-sm font-semibold text-yellow-800">Durée</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-sm text-yellow-700">
                                            <tr>
                                                <td class="border-b px-4 py-2">Google Analytics</td>
                                                <td class="border-b px-4 py-2">Analyse du trafic et comportement utilisateur</td>
                                                <td class="border-b px-4 py-2">2 ans</td>
                                            </tr>
                                            <tr>
                                                <td class="border-b px-4 py-2">Hotjar</td>
                                                <td class="border-b px-4 py-2">Heatmaps et enregistrements de session</td>
                                                <td class="border-b px-4 py-2">1 an</td>
                                            </tr>
                                            <tr>
                                                <td class="border-b px-4 py-2">Internal Analytics</td>
                                                <td class="border-b px-4 py-2">Statistiques internes de performance</td>
                                                <td class="border-b px-4 py-2">6 mois</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cookies marketing -->
                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                </svg>
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="flex items-center justify-between mb-3">
                                    <h3 class="text-lg font-semibold text-purple-800">Cookies marketing</h3>
                                    <label class="flex items-center cursor-pointer">
                                        <input type="checkbox" class="sr-only" id="marketing-cookies">
                                        <div class="relative">
                                            <div class="block bg-gray-600 w-14 h-8 rounded-full"></div>
                                            <div class="dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition"></div>
                                        </div>
                                        <span class="ml-3 text-purple-700 font-medium">Désactivé</span>
                                    </label>
                                </div>
                                <p class="text-purple-700 mb-4">
                                    Ces cookies permettent de personnaliser les publicités et de mesurer l'efficacité 
                                    de nos campagnes marketing. Ils peuvent créer un profil de vos intérêts.
                                </p>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full bg-white border border-purple-200 rounded">
                                        <thead class="bg-purple-100">
                                            <tr>
                                                <th class="border-b px-4 py-2 text-left text-sm font-semibold text-purple-800">Service</th>
                                                <th class="border-b px-4 py-2 text-left text-sm font-semibold text-purple-800">Finalité</th>
                                                <th class="border-b px-4 py-2 text-left text-sm font-semibold text-purple-800">Durée</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-sm text-purple-700">
                                            <tr>
                                                <td class="border-b px-4 py-2">Google Ads</td>
                                                <td class="border-b px-4 py-2">Publicités ciblées et remarketing</td>
                                                <td class="border-b px-4 py-2">90 jours</td>
                                            </tr>
                                            <tr>
                                                <td class="border-b px-4 py-2">Facebook Pixel</td>
                                                <td class="border-b px-4 py-2">Publicités sociales et audiences</td>
                                                <td class="border-b px-4 py-2">90 jours</td>
                                            </tr>
                                            <tr>
                                                <td class="border-b px-4 py-2">Mailchimp</td>
                                                <td class="border-b px-4 py-2">Personnalisation des newsletters</td>
                                                <td class="border-b px-4 py-2">2 ans</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Gestion des préférences -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6 border-b-2 border-blue-500 pb-2">
                    Gestion de vos préférences
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg">
                    <div class="space-y-6">
                        <!-- Actions rapides -->
                        <div class="grid md:grid-cols-3 gap-4">
                            <button class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors font-medium">
                                ✓ Accepter tous les cookies
                            </button>
                            <button class="bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 transition-colors font-medium">
                                ✗ Refuser tous les cookies non-essentiels
                            </button>
                            <button class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                                ⚙️ Personnaliser mes préférences
                            </button>
                        </div>

                        <!-- Préférences détaillées -->
                        <div class="bg-white p-6 rounded-lg border" id="detailed-preferences" style="display: none;">
                            <h3 class="font-semibold text-gray-800 mb-4">Préférences détaillées</h3>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded">
                                    <div>
                                        <h4 class="font-medium text-gray-800">Cookies essentiels</h4>
                                        <p class="text-sm text-gray-600">Nécessaires au fonctionnement du site</p>
                                    </div>
                                    <span class="bg-gray-400 text-white px-3 py-1 rounded text-sm">Toujours activé</span>
                                </div>
                                
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded">
                                    <div>
                                        <h4 class="font-medium text-gray-800">Cookies fonctionnels</h4>
                                        <p class="text-sm text-gray-600">Préférences et expérience personnalisée</p>
                                    </div>
                                    <label class="flex items-center cursor-pointer">
                                        <input type="checkbox" class="sr-only">
                                        <div class="relative">
                                            <div class="block bg-blue-600 w-14 h-8 rounded-full"></div>
                                            <div class="dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition"></div>
                                        </div>
                                    </label>
                                </div>

                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded">
                                    <div>
                                        <h4 class="font-medium text-gray-800">Cookies analytiques</h4>
                                        <p class="text-sm text-gray-600">Analyse et amélioration du site</p>
                                    </div>
                                    <label class="flex items-center cursor-pointer">
                                        <input type="checkbox" class="sr-only">
                                        <div class="relative">
                                            <div class="block bg-gray-400 w-14 h-8 rounded-full"></div>
                                            <div class="dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition"></div>
                                        </div>
                                    </label>
                                </div>

                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded">
                                    <div>
                                        <h4 class="font-medium text-gray-800">Cookies marketing</h4>
                                        <p class="text-sm text-gray-600">Publicités personnalisées</p>
                                    </div>
                                    <label class="flex items-center cursor-pointer">
                                        <input type="checkbox" class="sr-only">
                                        <div class="relative">
                                            <div class="block bg-gray-400 w-14 h-8 rounded-full"></div>
                                            <div class="dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition"></div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="mt-6 text-center">
                                <button class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition-colors">
                                    Sauvegarder mes préférences
                                </button>
                            </div>
                        </div>

                        <!-- Informations complémentaires -->
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                            <h4 class="font-medium text-yellow-800 mb-2">Gestion via votre navigateur</h4>
                            <p class="text-yellow-700 text-sm">
                                Vous pouvez également gérer les cookies directement dans les paramètres de votre navigateur. 
                                Notez que bloquer certains cookies peut affecter le fonctionnement du site.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Cookies tiers -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6 border-b-2 border-blue-500 pb-2">
                    Services tiers et cookies
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg space-y-4">
                    <p class="text-gray-700">
                        Notre site utilise des services tiers qui peuvent déposer leurs propres cookies. 
                        Ces services ont leurs propres politiques de confidentialité:
                    </p>
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="bg-white p-4 rounded border">
                            <h3 class="font-semibold text-gray-800 mb-2">Services Google</h3>
                            <ul class="text-gray-700 space-y-1 text-sm">
                                <li>• Google Analytics</li>
                                <li>• Google Ads</li>
                                <li>• Google Maps</li>
                                <li>• reCAPTCHA</li>
                            </ul>
                            <a href="https://policies.google.com/privacy" 
                               class="text-blue-600 hover:text-blue-700 text-sm" 
                               target="_blank" rel="noopener">
                                → Politique de confidentialité Google
                            </a>
                        </div>

                        <div class="bg-white p-4 rounded border">
                            <h3 class="font-semibold text-gray-800 mb-2">Réseaux sociaux</h3>
                            <ul class="text-gray-700 space-y-1 text-sm">
                                <li>• Facebook Pixel</li>
                                <li>• Widgets de partage</li>
                                <li>• Boutons "J'aime"</li>
                                <li>• Intégrations Instagram</li>
                            </ul>
                            <a href="https://www.facebook.com/privacy/explanation" 
                               class="text-blue-600 hover:text-blue-700 text-sm" 
                               target="_blank" rel="noopener">
                                → Politique Meta/Facebook
                            </a>
                        </div>

                        <div class="bg-white p-4 rounded border">
                            <h3 class="font-semibold text-gray-800 mb-2">Paiement et sécurité</h3>
                            <ul class="text-gray-700 space-y-1 text-sm">
                                <li>• Stripe (paiements)</li>
                                <li>• PayPal</li>
                                <li>• Systèmes de détection fraude</li>
                                <li>• Certificats SSL</li>
                            </ul>
                            <a href="https://stripe.com/privacy" 
                               class="text-blue-600 hover:text-blue-700 text-sm" 
                               target="_blank" rel="noopener">
                                → Politique Stripe
                            </a>
                        </div>

                        <div class="bg-white p-4 rounded border">
                            <h3 class="font-semibold text-gray-800 mb-2">Communication</h3>
                            <ul class="text-gray-700 space-y-1 text-sm">
                                <li>• Mailchimp (newsletters)</li>
                                <li>• Zendesk (support)</li>
                                <li>• Chat en ligne</li>
                                <li>• Outils de feedback</li>
                            </ul>
                            <a href="https://mailchimp.com/legal/privacy/" 
                               class="text-blue-600 hover:text-blue-700 text-sm" 
                               target="_blank" rel="noopener">
                               → Politique Mailchimp
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Vos droits -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6 border-b-2 border-blue-500 pb-2">
                    Vos droits concernant les cookies
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg">
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-3">Droit de refus</h3>
                            <p class="text-gray-700 text-sm mb-3">
                                Vous pouvez refuser l'utilisation de cookies non-essentiels à tout moment. 
                                Ce choix n'affectera pas l'accès au site.
                            </p>
                            <ul class="text-gray-700 space-y-1 text-sm">
                                <li>• Refus global ou sélectif</li>
                                <li>• Modification à tout moment</li>
                                <li>• Conservation de vos préférences</li>
                            </ul>
                        </div>

                        <div>
                            <h3 class="font-semibold text-gray-800 mb-3">Contrôle technique</h3>
                            <p class="text-gray-700 text-sm mb-3">
                                Vous gardez le contrôle technique via votre navigateur pour 
                                bloquer ou supprimer les cookies.
                            </p>
                            <ul class="text-gray-700 space-y-1 text-sm">
                                <li>• Paramètres de confidentialité</li>
                                <li>• Mode navigation privée</li>
                                <li>• Extensions de blocage</li>
                            </ul>
                        </div>

                        <div>
                            <h3 class="font-semibold text-gray-800 mb-3">Information transparente</h3>
                            <p class="text-gray-700 text-sm mb-3">
                                Nous vous informons clairement sur l'utilisation des cookies 
                                et leurs finalités avant leur dépôt.
                            </p>
                            <ul class="text-gray-700 space-y-1 text-sm">
                                <li>• Bannière de consentement</li>
                                <li>• Détails par catégorie</li>
                                <li>• Mise à jour des politiques</li>
                            </ul>
                        </div>

                        <div>
                            <h3 class="font-semibold text-gray-800 mb-3">Exercice des droits</h3>
                            <p class="text-gray-700 text-sm mb-3">
                                Pour toute question sur les cookies ou exercer vos droits, 
                                contactez notre DPO.
                            </p>
                            <ul class="text-gray-700 space-y-1 text-sm">
                                <li>• Email: dpo@farmshop.be</li>
                                <li>• Téléphone: +32 2 123 45 67</li>
                                <li>• Réponse sous 30 jours</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Modifications -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6 border-b-2 border-blue-500 pb-2">
                    Modifications de cette politique
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg">
                    <p class="text-gray-700 mb-4">
                        Cette politique de cookies peut être modifiée pour refléter les évolutions 
                        de nos services, de la réglementation ou des technologies utilisées.
                    </p>
                    
                    <div class="bg-white p-4 rounded border">
                        <h3 class="font-semibold text-gray-800 mb-2">Notification des changements</h3>
                        <ul class="text-gray-700 space-y-1 text-sm">
                            <li>• Publication sur le site avec date d'entrée en vigueur</li>
                            <li>• Notification par bannière pour changements majeurs</li>
                            <li>• Demande de nouveau consentement si nécessaire</li>
                            <li>• Historique des versions disponible</li>
                        </ul>
                    </div>

                    <div class="mt-4 text-center">
                        <p class="text-sm text-gray-500">
                            Dernière mise à jour: {{ date('d/m/Y') }}
                        </p>
                    </div>
                </div>
            </section>

            <!-- Contact -->
            <section class="bg-blue-50 border-l-4 border-blue-400 p-6 rounded-r-lg">
                <h3 class="text-lg font-semibold text-blue-800 mb-3">Questions sur les cookies ?</h3>
                <div class="grid md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-blue-700 mb-2">
                            <strong>Délégué à la Protection des Données</strong>
                        </p>
                        <p class="text-blue-600">
                            Email: dpo@farmshop.be<br>
                            Téléphone: +32 2 123 45 67<br>
                            Spécialiste cookies et confidentialité
                        </p>
                    </div>
                    <div>
                        <p class="text-blue-700 mb-2">
                            <strong>Support Technique</strong>
                        </p>
                        <p class="text-blue-600">
                            Email: support@farmshop.be<br>
                            Pour problèmes techniques liés aux cookies<br>
                            Assistance navigation et préférences
                        </p>
                    </div>
                </div>
            </section>

            <!-- Footer réglementaire -->
            <section class="text-center border-t pt-6 mt-8">
                <p class="text-sm text-gray-500">
                    Politique conforme à la directive ePrivacy (2002/58/CE) et au RGPD (UE 2016/679)<br>
                    Loi belge du 13 juin 2005 relative aux communications électroniques
                </p>
            </section>
        </div>
    </div>
</div>

<script>
// Toggle détaillé des préférences
document.addEventListener('DOMContentLoaded', function() {
    const personalizeBtn = document.querySelector('button[contains("Personnaliser")]');
    const detailedPrefs = document.getElementById('detailed-preferences');
    
    if (personalizeBtn && detailedPrefs) {
        personalizeBtn.addEventListener('click', function() {
            detailedPrefs.style.display = detailedPrefs.style.display === 'none' ? 'block' : 'none';
        });
    }
});
</script>
@endsection
