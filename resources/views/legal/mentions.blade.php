@extends('layouts.app')

@section('title', 'Mentions légales - FarmShop')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-lg rounded-lg p-8">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ __("app.legal.legal_notices") }}</h1>
                <p class="text-gray-600">Informations légales obligatoires selon le Code de droit économique belge</p>
            </div>

            <!-- Identification de l'entreprise -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4 border-b-2 border-green-500 pb-2">
                    1. Identification de l'entreprise
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg">
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-2">Dénomination sociale</h3>
                            <p class="text-gray-600">FarmShop SPRL</p>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-2">Forme juridique</h3>
                            <p class="text-gray-600">Société Privée à Responsabilité Limitée</p>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-2">Numéro d'entreprise BCE</h3>
                            <p class="text-gray-600">0XXX.XXX.XXX</p>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-2">Numéro TVA</h3>
                            <p class="text-gray-600">BE 0XXX.XXX.XXX</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Adresse et contact -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4 border-b-2 border-green-500 pb-2">
                    2. Coordonnées et siège social
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg">
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-2">Siège social</h3>
                            <p class="text-gray-600">
                                Avenue de la ferme 123<br>
                                1000 Bruxelles<br>
                                Belgique
                            </p>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-2">{{ __("app.nav.contact") }}</h3>
                            <p class="text-gray-600">
                                Téléphone: +32 2 123 45 67<br>
                                Email: contact@farmshop.be<br>
                                Heures d'ouverture: 9h-18h (Lu-Ve)
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Direction et gérance -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4 border-b-2 border-green-500 pb-2">
                    3. Direction et responsabilités
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg">
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-2">Gérant</h3>
                            <p class="text-gray-600">MEFTAH Soufiane</p>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-2">Directeur de publication</h3>
                            <p class="text-gray-600">MEFTAH Soufiane</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Activités et autorisations -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4 border-b-2 border-green-500 pb-2">
                    4. Activités et autorisations
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg">
                    <div class="space-y-4">
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-2">Objet social</h3>
                            <p class="text-gray-600">
                                Commerce électronique spécialisé dans la vente et location de matériel agricole, 
                                outils de jardinage, semences et produits phytosanitaires destinés exclusivement 
                                aux particuliers.
                            </p>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-2">Autorisations sectorielles</h3>
                            <ul class="text-gray-600 space-y-1">
                                <li>• Déclaration AFSCA pour commercialisation produits alimentaires aux particuliers</li>
                                <li>• Agrément vente produits phytopharmaceutiques usage non professionnel (en cours)</li>
                                <li>• Autorisation commerce matériel agricole destiné aux particuliers</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Hébergement et technique -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4 border-b-2 border-green-500 pb-2">
                    5. Hébergement et prestataires techniques
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg">
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-2">Hébergeur du site</h3>
                            <p class="text-gray-600">
                                OVH SAS<br>
                                2 rue Kellermann<br>
                                59100 Roubaix, France<br>
                                Tél: +33 9 72 10 10 07
                            </p>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-2">Paiements sécurisés</h3>
                            <p class="text-gray-600">
                                Stripe Payments Europe, Ltd.<br>
                                1 Grand Canal Street Lower<br>
                                Dublin 2, Irlande<br>
                                Licence établissement de paiement
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Assurances -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4 border-b-2 border-green-500 pb-2">
                    6. Assurances et garanties
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg">
                    <div class="space-y-4">
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-2">Responsabilité civile professionnelle</h3>
                            <p class="text-gray-600">
                                Assureur: [Nom de l'assureur]<br>
                                Police n°: XXX.XXX.XXX<br>
                                Couverture: 1.250.000 € par sinistre<br>
                                Activités couvertes: Vente, location, conseil matériel agricole
                            </p>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-2">Cyber-assurance</h3>
                            <p class="text-gray-600">
                                Protection données personnelles et cyber-risques selon RGPD<br>
                                <a href="{{ route('legal.insurance') }}" class="text-green-600 hover:text-green-700">
                                    → Voir détails couverture complète
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Propriété intellectuelle -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4 border-b-2 border-green-500 pb-2">
                    7. Propriété intellectuelle et droits d'auteur
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg">
                    <div class="space-y-4">
                        <p class="text-gray-600">
                            L'ensemble du contenu du site FarmShop (textes, images, graphismes, logo, vidéos, 
                            architecture, etc.) est protégé par les droits de propriété intellectuelle selon 
                            le Code de droit économique belge - Livre XI.
                        </p>
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-2">Droits réservés</h3>
                            <p class="text-gray-600">
                                © 2024-2025 FarmShop SPRL. Tous droits réservés.<br>
                                Toute reproduction, même partielle, est interdite sans autorisation expresse.
                            </p>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-2">Signalement de violation</h3>
                            <p class="text-gray-600">
                                Pour signaler une violation des droits d'auteur:<br>
                                Email: legal@farmshop.be
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Médiation et juridiction -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4 border-b-2 border-green-500 pb-2">
                    8. Médiation et juridiction compétente
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg">
                    <div class="space-y-4">
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-2">Résolution amiable des litiges</h3>
                            <p class="text-gray-600">
                                En cas de litige, nous privilégions la résolution amiable. 
                                Les consommateurs peuvent faire appel gratuitement à un médiateur agréé.
                                <br><br>
                                <a href="{{ route('legal.mediation') }}" class="text-green-600 hover:text-green-700">
                                    → Procédure de médiation détaillée
                                </a>
                            </p>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-2">Juridiction compétente</h3>
                            <p class="text-gray-600">
                                Droit applicable: Droit belge<br>
                                Tribunaux compétents: Tribunaux de Bruxelles<br>
                                Exception: Pour les consommateurs, compétence du tribunal du domicile du consommateur
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Conformité RGPD -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4 border-b-2 border-green-500 pb-2">
                    9. Protection des données personnelles
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg">
                    <div class="space-y-4">
                        <p class="text-gray-600">
                            FarmShop respecte strictement le Règlement Général sur la Protection des Données (RGPD) 
                            et la législation belge en matière de protection de la vie privée.
                        </p>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <h3 class="font-semibold text-gray-800 mb-2">Responsable du traitement</h3>
                                <p class="text-gray-600">FarmShop SPRL</p>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800 mb-2">Délégué à la protection des données</h3>
                                <p class="text-gray-600">dpo@farmshop.be</p>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-4 mt-4">
                            <a href="{{ route('legal.privacy') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition-colors">
                                Politique de confidentialité
                            </a>
                            <a href="{{ route('legal.gdpr-rights') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">
                                Vos droits RGPD
                            </a>
                            <a href="{{ route('legal.cookies') }}" class="bg-orange-600 text-white px-4 py-2 rounded hover:bg-orange-700 transition-colors">
                                Politique des cookies
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Date de mise à jour -->
            <section class="text-center border-t pt-6">
                <p class="text-sm text-gray-500">
                    Mentions légales mises à jour le {{ date('d/m/Y') }}<br>
                    Conformes au Code de droit économique belge et au RGPD
                </p>
            </section>
        </div>
    </div>
</div>
@endsection
