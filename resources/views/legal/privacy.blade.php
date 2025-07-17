@extends('layouts.app')

@section('title', 'Politique de Confidentialité - FarmShop')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-lg rounded-lg p-8">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">Politique de Confidentialité</h1>
                <p class="text-gray-600">Protection de vos données personnelles</p>
                <p class="text-sm text-gray-500 mt-2">Conforme au RGPD - Version du {{ date('d/m/Y') }}</p>
            </div>

            <!-- Introduction -->
            <section class="mb-8">
                <div class="bg-blue-50 border-l-4 border-blue-400 p-6 rounded-r-lg">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-medium text-blue-800 mb-2">Votre vie privée nous tient à cœur</h3>
                            <p class="text-blue-700">
                                FarmShop SPRL s'engage à protéger vos données personnelles conformément au Règlement Général 
                                sur la Protection des Données (RGPD) et à la législation belge. Cette politique détaille 
                                comment nous collectons, utilisons et protégeons vos informations.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Responsable du traitement -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4 border-b-2 border-blue-500 pb-2">
                    1. Responsable du traitement des données
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg">
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-2">Identité du responsable</h3>
                            <p class="text-gray-700">
                                <strong>FarmShop SPRL</strong><br>
                                Rue de l'Agriculture, 123<br>
                                1000 Bruxelles, Belgique<br>
                                BCE: 0123.456.789<br>
                                TVA: BE0123456789
                            </p>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-2">Contact DPO</h3>
                            <p class="text-gray-700">
                                <strong>Délégué à la Protection des Données</strong><br>
                                Email: dpo@farmshop.be<br>
                                Téléphone: +32 2 123 45 67<br>
                                Courrier: DPO FarmShop,<br>
                                Rue de l'Agriculture, 123<br>
                                1000 Bruxelles
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Types de données collectées -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4 border-b-2 border-blue-500 pb-2">
                    2. Données personnelles collectées
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg space-y-6">
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-3">2.1 Données d'identification</h3>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div class="bg-white p-4 rounded border">
                                <h4 class="font-medium text-gray-800 mb-2">Particuliers</h4>
                                <ul class="text-gray-700 space-y-1 text-sm">
                                    <li>• Nom et prénom</li>
                                    <li>• Adresse email</li>
                                    <li>• Numéro de téléphone</li>
                                    <li>• Adresse postale</li>
                                    <li>• Date de naissance</li>
                                </ul>
                            </div>
                            <div class="bg-white p-4 rounded border">
                                <h4 class="font-medium text-gray-800 mb-2">Professionnels</h4>
                                <ul class="text-gray-700 space-y-1 text-sm">
                                    <li>• Dénomination sociale</li>
                                    <li>• Numéro d'entreprise (BCE)</li>
                                    <li>• Numéro de TVA</li>
                                    <li>• Secteur d'activité</li>
                                    <li>• Taille d'exploitation</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-800 mb-3">2.2 Données de navigation et techniques</h3>
                        <div class="bg-white p-4 rounded border">
                            <ul class="text-gray-700 space-y-1">
                                <li>• Adresse IP et données de géolocalisation</li>
                                <li>• Informations sur l'appareil et le navigateur</li>
                                <li>• Pages visitées et temps de navigation</li>
                                <li>• Historique des achats et recherches</li>
                                <li>• Cookies et technologies similaires</li>
                                <li>• Données de performance du site</li>
                            </ul>
                        </div>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-800 mb-3">2.3 Données financières et commerciales</h3>
                        <div class="bg-white p-4 rounded border">
                            <ul class="text-gray-700 space-y-1">
                                <li>• Informations de paiement (cryptées)</li>
                                <li>• Historique des commandes et factures</li>
                                <li>• Préférences produits et wishlist</li>
                                <li>• Avis et évaluations produits</li>
                                <li>• Communications client (emails, chat)</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Finalités du traitement -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4 border-b-2 border-blue-500 pb-2">
                    3. Finalités et bases légales du traitement
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg space-y-4">
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="border-b px-4 py-3 text-left text-sm font-semibold text-gray-800">Finalité</th>
                                    <th class="border-b px-4 py-3 text-left text-sm font-semibold text-gray-800">Base légale RGPD</th>
                                    <th class="border-b px-4 py-3 text-left text-sm font-semibold text-gray-800">Données concernées</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm text-gray-700">
                                <tr>
                                    <td class="border-b px-4 py-3">Gestion des comptes clients</td>
                                    <td class="border-b px-4 py-3">Exécution du contrat (Art. 6.1.b)</td>
                                    <td class="border-b px-4 py-3">Identification, contact</td>
                                </tr>
                                <tr>
                                    <td class="border-b px-4 py-3">Traitement des commandes</td>
                                    <td class="border-b px-4 py-3">Exécution du contrat (Art. 6.1.b)</td>
                                    <td class="border-b px-4 py-3">Toutes données client</td>
                                </tr>
                                <tr>
                                    <td class="border-b px-4 py-3">Paiements et facturation</td>
                                    <td class="border-b px-4 py-3">Obligation légale (Art. 6.1.c)</td>
                                    <td class="border-b px-4 py-3">Financières, comptables</td>
                                </tr>
                                <tr>
                                    <td class="border-b px-4 py-3">Service client et SAV</td>
                                    <td class="border-b px-4 py-3">Intérêt légitime (Art. 6.1.f)</td>
                                    <td class="border-b px-4 py-3">Contact, historique</td>
                                </tr>
                                <tr>
                                    <td class="border-b px-4 py-3">Marketing personnalisé</td>
                                    <td class="border-b px-4 py-3">Consentement (Art. 6.1.a)</td>
                                    <td class="border-b px-4 py-3">Préférences, navigation</td>
                                </tr>
                                <tr>
                                    <td class="border-b px-4 py-3">Amélioration du site</td>
                                    <td class="border-b px-4 py-3">Intérêt légitime (Art. 6.1.f)</td>
                                    <td class="border-b px-4 py-3">Navigation, techniques</td>
                                </tr>
                                <tr>
                                    <td class="border-b px-4 py-3">Lutte contre la fraude</td>
                                    <td class="border-b px-4 py-3">Obligation légale (Art. 6.1.c)</td>
                                    <td class="border-b px-4 py-3">Identification, paiement</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-farm-green-700 mb-4">4. Base légale</h2>
                    <p class="mb-4">
                        Le traitement de vos données personnelles est basé sur :
                    </p>
                    <ul class="list-disc pl-6 mb-4">
                        <li><strong>L'exécution d'un contrat :</strong> pour le traitement des commandes</li>
                        <li><strong>L'intérêt légitime :</strong> pour l'amélioration de nos services</li>
                        <li><strong>Le consentement :</strong> pour la newsletter et les cookies non essentiels</li>
                        <li><strong>L'obligation légale :</strong> pour la comptabilité et la conformité</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-farm-green-700 mb-4">5. Partage des données</h2>
                    <p class="mb-4">Nous partageons vos données uniquement avec :</p>
                    <ul class="list-disc pl-6 mb-4">
                        <li><strong>Stripe :</strong> pour le traitement des paiements</li>
                        <li><strong>Transporteurs :</strong> pour la livraison de vos commandes</li>
                        <li><strong>Autorités compétentes :</strong> si requis par la loi</li>
                    </ul>
                    <p class="mb-4">
                        Nous ne vendons jamais vos données à des tiers.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-farm-green-700 mb-4">6. Conservation des données</h2>
                    <p class="mb-4">Nous conservons vos données :</p>
                    <ul class="list-disc pl-6 mb-4">
                        <li><strong>Données de compte :</strong> tant que votre compte est actif</li>
                        <li><strong>Données de commande :</strong> 10 ans (obligation comptable)</li>
                        <li><strong>Données marketing :</strong> jusqu'à désabonnement</li>
                        <li><strong>Cookies :</strong> 13 mois maximum</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-farm-green-700 mb-4">7. Vos droits RGPD</h2>
                    <p class="mb-4">Vous disposez des droits suivants :</p>
                    <ul class="list-disc pl-6 mb-4">
                        <li><strong>Droit d'accès :</strong> obtenir une copie de vos données</li>
                        <li><strong>Droit de rectification :</strong> corriger vos données</li>
                        <li><strong>Droit à l'effacement :</strong> supprimer vos données</li>
                        <li><strong>Droit à la portabilité :</strong> récupérer vos données</li>
                        <li><strong>Droit d'opposition :</strong> vous opposer au traitement</li>
                        <li><strong>Droit de limitation :</strong> limiter le traitement</li>
                    </ul>
                    <p class="mb-4">
                        Pour exercer vos droits, contactez-nous à s.mef2703@gmail.com ou
                        <a href="{{ route('gdpr-rights') }}" class="text-farm-green-600 hover:text-farm-green-700 underline">
                            utilisez notre formulaire RGPD
                        </a>.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-farm-green-700 mb-4">8. Sécurité</h2>
                    <p class="mb-4">
                        Nous mettons en place des mesures techniques et organisationnelles appropriées pour 
                        protéger vos données contre la perte, l'utilisation abusive, l'accès non autorisé, 
                        la divulgation, l'altération ou la destruction.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-farm-green-700 mb-4">9. Cookies</h2>
                    <p class="mb-4">
                        Notre site utilise des cookies essentiels et des cookies d'analyse. 
                        Vous pouvez gérer vos préférences via notre bandeau de cookies.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-farm-green-700 mb-4">10. Contact et réclamations</h2>
                    <p class="mb-4">
                        Pour toute question relative à cette politique de confidentialité :
                    </p>
                    <ul class="list-disc pl-6 mb-4">
                        <li>Email : s.mef2703@gmail.com</li>
                        <li>Courrier : FarmShop SPRL, [Adresse complète]</li>
                    </ul>
                    <p class="mb-4">
                        Vous avez également le droit de déposer une plainte auprès de l'Autorité de protection des données (APD).
                    </p>
                </section>
            </div>

            <div class="mt-8 pt-8 border-t border-gray-200">
                <a href="{{ url('/') }}" class="inline-flex items-center text-farm-green-600 hover:text-farm-green-700">
                    ← Retour à l'accueil
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
