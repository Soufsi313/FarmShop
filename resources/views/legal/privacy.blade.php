@extends('layouts.app')

@section('title', 'Politique de Confidentialité - FarmShop')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="container mx-auto px-6 max-w-4xl">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-4xl font-bold text-farm-green-800 mb-8">
                🔒 Politique de Confidentialité
            </h1>
            
            <div class="prose prose-lg max-w-none">
                <p class="text-gray-600 mb-6">
                    <strong>Dernière mise à jour :</strong> {{ now()->format('d/m/Y') }}
                </p>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-farm-green-700 mb-4">1. Responsable du traitement</h2>
                    <p class="mb-4">
                        FarmShop SPRL<br>
                        Adresse : [Adresse complète]<br>
                        Email : s.mef2703@gmail.com<br>
                        Téléphone : [Numéro de téléphone]
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-farm-green-700 mb-4">2. Données collectées</h2>
                    <p class="mb-4">Nous collectons les données suivantes :</p>
                    <ul class="list-disc pl-6 mb-4">
                        <li><strong>Données d'identification :</strong> nom, prénom, adresse email</li>
                        <li><strong>Données de contact :</strong> adresse postale, numéro de téléphone</li>
                        <li><strong>Données de paiement :</strong> informations de facturation (traitées par Stripe)</li>
                        <li><strong>Données de navigation :</strong> cookies, adresse IP, données de session</li>
                        <li><strong>Données de commande :</strong> historique des achats et locations</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-farm-green-700 mb-4">3. Finalités du traitement</h2>
                    <p class="mb-4">Vos données sont utilisées pour :</p>
                    <ul class="list-disc pl-6 mb-4">
                        <li>Traitement et suivi de vos commandes</li>
                        <li>Gestion des locations de matériel</li>
                        <li>Service client et support technique</li>
                        <li>Amélioration de nos services</li>
                        <li>Respect de nos obligations légales</li>
                        <li>Newsletter (avec votre consentement)</li>
                    </ul>
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
