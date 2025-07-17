@extends('layouts.app')

@section('title', 'Conditions Générales d\'Utilisation - FarmShop')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-lg rounded-lg p-8">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">Conditions Générales d'Utilisation</h1>
                <p class="text-gray-600">Règles d'utilisation de la plateforme FarmShop</p>
                <p class="text-sm text-gray-500 mt-2">Version en vigueur depuis le {{ date('d/m/Y') }}</p>
            </div>

            <!-- Objet et acceptation -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4 border-b-2 border-blue-500 pb-2">
                    Article 1 - Objet et acceptation
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg">
                    <p class="text-gray-700 mb-4">
                        Les présentes Conditions Générales d'Utilisation (CGU) régissent l'accès et l'utilisation 
                        du site internet farmshop.be et de ses services associés, exploités par FarmShop SPRL.
                    </p>
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
                        <p class="text-blue-800">
                            <strong>Acceptation des CGU:</strong> L'utilisation du site implique l'acceptation pleine 
                            et entière des présentes CGU. Si vous n'acceptez pas ces conditions, 
                            veuillez ne pas utiliser le site.
                        </p>
                    </div>
                    <p class="text-gray-700 mt-4">
                        FarmShop se réserve le droit de modifier les CGU à tout moment. 
                        Les modifications entrent en vigueur dès leur publication sur le site.
                    </p>
                </div>
            </section>

            <!-- Description des services -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4 border-b-2 border-blue-500 pb-2">
                    Article 2 - Description des services
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg space-y-4">
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">2.1 Services proposés</h3>
                        <ul class="text-gray-700 space-y-2">
                            <li>• Plateforme de commerce électronique spécialisée en matériel agricole</li>
                            <li>• Vente en ligne de produits agricoles, outils et équipements</li>
                            <li>• Service de location de matériel agricole</li>
                            <li>• Conseils techniques et assistance client</li>
                            <li>• Blog informatif sur les pratiques agricoles</li>
                            <li>• Espace client personnalisé et suivi commandes</li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">2.2 Accessibilité du service</h3>
                        <p class="text-gray-700">
                            FarmShop s'efforce d'assurer un accès au site 24h/24 et 7j/7, sous réserve 
                            des opérations de maintenance programmées. Aucune garantie de disponibilité absolue n'est donnée.
                        </p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">2.3 Évolution des services</h3>
                        <p class="text-gray-700">
                            FarmShop se réserve le droit de faire évoluer, modifier ou suspendre tout ou partie 
                            des services sans préavis, notamment pour des raisons de maintenance ou d'amélioration.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Inscription et compte utilisateur -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4 border-b-2 border-blue-500 pb-2">
                    Article 3 - Inscription et compte utilisateur
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg space-y-4">
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">3.1 Création de compte</h3>
                        <p class="text-gray-700 mb-2">
                            La création d'un compte utilisateur est obligatoire pour effectuer des achats ou locations. 
                            L'inscription est gratuite et requiert:
                        </p>
                        <ul class="text-gray-700 space-y-1">
                            <li>• Informations personnelles complètes et exactes</li>
                            <li>• Adresse email valide et vérifiée</li>
                            <li>• Mot de passe sécurisé respectant nos critères</li>
                            <li>• Acceptation des CGU et politique de confidentialité</li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">3.2 Responsabilité du compte</h3>
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                            <p class="text-yellow-800">
                                <strong>Sécurité:</strong> Vous êtes responsable de la confidentialité de vos identifiants 
                                et de toutes les activités effectuées avec votre compte. 
                                Signalez immédiatement tout usage non autorisé.
                            </p>
                        </div>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">3.3 Comptes professionnels</h3>
                        <p class="text-gray-700">
                            Les comptes professionnels bénéficient de conditions spécifiques (tarifs, paiement, facturation). 
                            Justificatifs d'activité professionnelle requis pour validation.
                        </p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">3.4 Suspension et résiliation</h3>
                        <p class="text-gray-700">
                            FarmShop peut suspendre ou supprimer un compte en cas de non-respect des CGU, 
                            d'activité frauduleuse ou de demande de l'utilisateur. 
                            Les données sont conservées selon notre politique de rétention.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Utilisation du site -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4 border-b-2 border-blue-500 pb-2">
                    Article 4 - Règles d'utilisation du site
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg space-y-4">
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">4.1 Utilisation licite</h3>
                        <p class="text-gray-700 mb-2">
                            Vous vous engagez à utiliser le site de manière licite et conforme aux présentes CGU. 
                            Il est notamment interdit de:
                        </p>
                        <ul class="text-gray-700 space-y-1">
                            <li>• Porter atteinte aux droits de propriété intellectuelle</li>
                            <li>• Diffuser des contenus illicites, offensants ou diffamatoires</li>
                            <li>• Utiliser des robots, scripts ou moyens automatisés</li>
                            <li>• Tenter d'accéder aux systèmes informatiques</li>
                            <li>• Perturber le fonctionnement du site</li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">4.2 Contenus utilisateurs</h3>
                        <p class="text-gray-700">
                            Les avis, commentaires et contenus publiés par les utilisateurs sont de leur seule responsabilité. 
                            FarmShop se réserve le droit de modérer, modifier ou supprimer tout contenu inapproprié.
                        </p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">4.3 Avis et évaluations</h3>
                        <div class="bg-white p-4 rounded border">
                            <p class="text-gray-700 mb-2">
                                Les avis produits doivent être authentiques et basés sur une expérience réelle. 
                                Sont prohibés:
                            </p>
                            <ul class="text-gray-700 space-y-1">
                                <li>• Les faux avis et avis rémunérés</li>
                                <li>• Les contenus hors sujet ou promotionnels</li>
                                <li>• Les attaques personnelles et propos discriminatoires</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Propriété intellectuelle -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4 border-b-2 border-blue-500 pb-2">
                    Article 5 - Propriété intellectuelle
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg space-y-4">
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">5.1 Droits de FarmShop</h3>
                        <p class="text-gray-700">
                            L'ensemble du site (design, textes, images, logos, structure, fonctionnalités) 
                            est protégé par les droits de propriété intellectuelle. 
                            Toute reproduction ou utilisation non autorisée est interdite.
                        </p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">5.2 Licence d'utilisation</h3>
                        <p class="text-gray-700">
                            FarmShop vous accorde une licence non exclusive, révocable et non transférable 
                            d'utilisation du site pour vos besoins personnels ou professionnels légitimes.
                        </p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">5.3 Contenus tiers</h3>
                        <p class="text-gray-700">
                            Les marques, logos et contenus de tiers présents sur le site restent la propriété 
                            de leurs détenteurs respectifs. Leur présence ne confère aucun droit d'usage.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Protection des données -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4 border-b-2 border-blue-500 pb-2">
                    Article 6 - Protection des données personnelles
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg space-y-4">
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">6.1 Traitement des données</h3>
                        <p class="text-gray-700">
                            FarmShop traite vos données personnelles conformément au RGPD et à sa politique de confidentialité. 
                            Les données collectées sont nécessaires au fonctionnement des services.
                        </p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">6.2 Vos droits</h3>
                        <p class="text-gray-700">
                            Vous disposez des droits d'accès, rectification, effacement, portabilité et opposition 
                            sur vos données personnelles, exerceables via votre espace client ou par email.
                        </p>
                        <div class="flex flex-wrap gap-4 mt-4">
                            <a href="{{ route('legal.privacy') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">
                                Politique de confidentialité
                            </a>
                            <a href="{{ route('legal.gdpr-rights') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition-colors">
                                Exercer vos droits
                            </a>
                        </div>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">6.3 Cookies</h3>
                        <p class="text-gray-700">
                            Le site utilise des cookies pour améliorer votre expérience. 
                            Vous pouvez gérer vos préférences via notre gestionnaire de cookies.
                            <a href="{{ route('legal.cookies') }}" class="text-blue-600 hover:text-blue-700 underline ml-1">
                                → Politique des cookies
                            </a>
                        </p>
                    </div>
                </div>
            </section>

            <!-- Responsabilité -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4 border-b-2 border-blue-500 pb-2">
                    Article 7 - Responsabilité et garanties
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg space-y-4">
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">7.1 Limitation de responsabilité</h3>
                        <p class="text-gray-700">
                            FarmShop met tout en œuvre pour assurer la sécurité et le bon fonctionnement du site, 
                            mais ne peut garantir une disponibilité absolue ni l'absence d'erreurs. 
                            La responsabilité est limitée aux dommages directs.
                        </p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">7.2 Contenus tiers</h3>
                        <p class="text-gray-700">
                            FarmShop n'est pas responsable des contenus, avis ou informations publiés par les utilisateurs 
                            ou accessibles via des liens externes. La vérification de ces contenus relève de votre responsabilité.
                        </p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">7.3 Force majeure</h3>
                        <p class="text-gray-700">
                            FarmShop ne peut être tenu responsable des retards ou manquements dus à des événements 
                            de force majeure (catastrophes naturelles, grèves, pandémies, défaillances techniques majeures).
                        </p>
                    </div>
                </div>
            </section>

            <!-- Signalement et modération -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4 border-b-2 border-blue-500 pb-2">
                    Article 8 - Signalement et modération
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg space-y-4">
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">8.1 Signalement de contenus</h3>
                        <p class="text-gray-700">
                            Pour signaler un contenu inapproprié, illicite ou portant atteinte à vos droits, 
                            contactez-nous à moderation@farmshop.be avec les éléments suivants:
                        </p>
                        <ul class="text-gray-700 space-y-1 mt-2">
                            <li>• URL exacte du contenu concerné</li>
                            <li>• Nature du problème signalé</li>
                            <li>• Vos coordonnées complètes</li>
                            <li>• Justificatifs le cas échéant</li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">8.2 Procédure de traitement</h3>
                        <p class="text-gray-700">
                            Tout signalement est examiné dans les 48h ouvrables. 
                            Des mesures appropriées sont prises si le signalement est fondé 
                            (suppression, modification, suspension de compte).
                        </p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">8.3 Recours</h3>
                        <p class="text-gray-700">
                            En cas de désaccord avec une décision de modération, 
                            vous pouvez introduire un recours motivé dans les 15 jours 
                            à recours@farmshop.be.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Résiliation -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4 border-b-2 border-blue-500 pb-2">
                    Article 9 - Résiliation et sanctions
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg space-y-4">
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">9.1 Résiliation par l'utilisateur</h3>
                        <p class="text-gray-700">
                            Vous pouvez résilier votre compte à tout moment via votre espace client 
                            ou en contactant le service client. La résiliation n'affecte pas les commandes en cours.
                        </p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">9.2 Sanctions et exclusion</h3>
                        <p class="text-gray-700 mb-2">
                            En cas de non-respect des CGU, FarmShop peut prendre les mesures suivantes:
                        </p>
                        <ul class="text-gray-700 space-y-1">
                            <li>• Avertissement et mise en demeure</li>
                            <li>• Suspension temporaire du compte</li>
                            <li>• Suppression de contenus inappropriés</li>
                            <li>• Résiliation définitive du compte</li>
                            <li>• Poursuites judiciaires si nécessaire</li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">9.3 Conservation des données</h3>
                        <p class="text-gray-700">
                            Après résiliation, les données sont conservées selon les durées légales 
                            pour les besoins comptables, fiscaux et de lutte contre la fraude.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Droit applicable -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4 border-b-2 border-blue-500 pb-2">
                    Article 10 - Droit applicable et juridictions
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg space-y-4">
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">10.1 Droit applicable</h3>
                        <p class="text-gray-700">
                            Les présentes CGU sont régies par le droit belge. 
                            En cas de conflit avec d'autres versions linguistiques, la version française prévaut.
                        </p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">10.2 Résolution amiable</h3>
                        <p class="text-gray-700">
                            En cas de litige, nous privilégions la recherche d'une solution amiable. 
                            Contactez notre service client à contact@farmshop.be.
                        </p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">10.3 Juridiction compétente</h3>
                        <p class="text-gray-700">
                            À défaut d'accord amiable, les tribunaux de Bruxelles sont seuls compétents, 
                            sauf pour les consommateurs qui peuvent saisir les tribunaux de leur domicile.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Contact et assistance -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4 border-b-2 border-blue-500 pb-2">
                    Article 11 - Contact et assistance
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg">
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-2">Service client</h3>
                            <p class="text-gray-700">
                                Email: contact@farmshop.be<br>
                                Téléphone: +32 2 123 45 67<br>
                                Horaires: 9h-18h (lun-ven)
                            </p>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-2">Questions juridiques</h3>
                            <p class="text-gray-700">
                                Email: legal@farmshop.be<br>
                                Pour toute question relative aux CGU, 
                                données personnelles ou signalements
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Entrée en vigueur -->
            <section class="text-center border-t pt-6">
                <p class="text-sm text-gray-500">
                    CGU en vigueur depuis le {{ date('d/m/Y') }}<br>
                    Conformes au Code de droit économique belge et au RGPD
                </p>
            </section>
        </div>
    </div>
</div>
@endsection
