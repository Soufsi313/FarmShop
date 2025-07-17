@extends('layouts.app')

@section('title', 'Conditions Générales de Vente - FarmShop')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-lg rounded-lg p-8">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">Conditions Générales de Vente</h1>
                <p class="text-gray-600">Applicables aux achats sur la plateforme FarmShop</p>
                <p class="text-sm text-gray-500 mt-2">Version en vigueur depuis le {{ date('d/m/Y') }}</p>
            </div>

            <!-- Objet et champ d'application -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4 border-b-2 border-green-500 pb-2">
                    Article 1 - Objet et champ d'application
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg">
                    <p class="text-gray-700 mb-4">
                        Les présentes Conditions Générales de Vente (CGV) régissent exclusivement les ventes 
                        de produits effectuées sur le site internet farmshop.be, exploité par FarmShop SPRL.
                    </p>
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
                        <p class="text-blue-800">
                            <strong>Important:</strong> Pour les locations de matériel, consultez nos 
                            <a href="{{ route('legal.cgl') }}" class="text-blue-600 hover:text-blue-700 underline">
                                Conditions Générales de Location
                            </a>.
                        </p>
                    </div>
                    <p class="text-gray-700 mt-4">
                        L'acceptation de ces CGV est matérialisée par le processus de commande en ligne. 
                        Elles prévalent sur toutes autres conditions générales ou particulières non expressément agréées par FarmShop.
                    </p>
                </div>
            </section>

            <!-- Produits et prix -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4 border-b-2 border-green-500 pb-2">
                    Article 2 - Produits et prix
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg space-y-4">
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">2.1 Catalogue produits</h3>
                        <p class="text-gray-700">
                            Les produits proposés sont ceux figurant sur le site au jour de la consultation par l'acheteur 
                            et dans la limite des stocks disponibles. Les photographies et descriptions n'ont pas de valeur contractuelle.
                        </p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">2.2 Prix</h3>
                        <ul class="text-gray-700 space-y-2">
                            <li>• Les prix sont exprimés en euros, toutes taxes comprises (TTC)</li>
                            <li>• La TVA applicable est la TVA belge en vigueur au jour de la commande</li>
                            <li>• Les prix peuvent être modifiés à tout moment mais sont garantis pour les commandes validées</li>
                            <li>• Les frais de livraison sont indiqués avant validation de la commande</li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">2.3 Disponibilité</h3>
                        <p class="text-gray-700">
                            En cas d'indisponibilité d'un produit après passation de la commande, FarmShop s'engage 
                            à en informer le client dans les plus brefs délais et à proposer un produit de substitution 
                            ou un remboursement intégral.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Commande -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4 border-b-2 border-green-500 pb-2">
                    Article 3 - Processus de commande
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg space-y-4">
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">3.1 Formation du contrat</h3>
                        <p class="text-gray-700">
                            Le contrat de vente est conclu dès validation du paiement par FarmShop. 
                            Un accusé de réception est envoyé par email confirmant les détails de la commande.
                        </p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">3.2 Étapes de commande</h3>
                        <ol class="text-gray-700 space-y-1 list-decimal list-inside">
                            <li>Sélection des produits et ajout au panier</li>
                            <li>Vérification du contenu et modification possible</li>
                            <li>Identification ou création de compte client</li>
                            <li>Choix de l'adresse de livraison</li>
                            <li>Sélection du mode de livraison et paiement</li>
                            <li>Vérification finale et validation de la commande</li>
                            <li>Paiement sécurisé via Stripe</li>
                        </ol>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">3.3 Conservation des commandes</h3>
                        <p class="text-gray-700">
                            FarmShop conserve les bons de commandes sur support informatique et les tient 
                            à disposition du client. L'historique des commandes est accessible dans l'espace client.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Paiement -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4 border-b-2 border-green-500 pb-2">
                    Article 4 - Modalités de paiement
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg space-y-4">
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">4.1 Moyens de paiement acceptés</h3>
                        <ul class="text-gray-700 space-y-2">
                            <li>• Cartes bancaires Visa, Mastercard, American Express</li>
                            <li>• Virement bancaire (pour commandes professionnelles &gt; 500€)</li>
                            <li>• Bancontact/Mister Cash</li>
                            <li>• PayPal (via Stripe)</li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">4.2 Sécurisation des paiements</h3>
                        <p class="text-gray-700">
                            Les paiements sont sécurisés par Stripe, prestataire certifié PCI DSS Level 1. 
                            FarmShop ne conserve aucune donnée bancaire sur ses serveurs.
                        </p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">4.3 Facturation</h3>
                        <p class="text-gray-700">
                            La facture est disponible en téléchargement dans l'espace client et envoyée par email. 
                            Pour les clients professionnels assujettis à la TVA, la facture mentionne le numéro de TVA.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Livraison -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4 border-b-2 border-green-500 pb-2">
                    Article 5 - Livraison
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg space-y-4">
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">5.1 Zone de livraison</h3>
                        <p class="text-gray-700">
                            Livraisons en Belgique, Luxembourg et Nord de la France. 
                            Conditions spéciales pour le matériel lourd (contactez-nous).
                        </p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">5.2 Délais de livraison</h3>
                        <ul class="text-gray-700 space-y-2">
                            <li>• Produits en stock: 2-5 jours ouvrés</li>
                            <li>• Matériel sur commande: 7-14 jours ouvrés</li>
                            <li>• Gros matériel: délai convenu au cas par cas</li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">5.3 Transfert de propriété et des risques</h3>
                        <p class="text-gray-700">
                            Le transfert de propriété et des risques s'opère dès livraison des produits à l'adresse indiquée, 
                            matérialisée par la signature du bon de livraison ou remise en main propre.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Droit de rétractation -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4 border-b-2 border-green-500 pb-2">
                    Article 6 - Droit de rétractation
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg space-y-4">
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
                        <p class="text-blue-800">
                            <strong>Conformément à l'article VI.47 du Code de droit économique belge</strong>
                        </p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">6.1 Délai de rétractation</h3>
                        <p class="text-gray-700">
                            Vous disposez d'un délai de <strong>14 jours calendaires</strong> à compter de la réception 
                            des produits pour exercer votre droit de rétractation sans avoir à justifier de motifs 
                            ni à payer de pénalités.
                        </p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">6.2 Modalités de rétractation</h3>
                        <p class="text-gray-700">
                            Pour exercer ce droit, contactez-nous par email à returns@farmshop.be 
                            ou utilisez le formulaire de rétractation disponible 
                            <a href="{{ route('legal.returns') }}" class="text-green-600 hover:text-green-700 underline">ici</a>.
                        </p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">6.3 Exceptions au droit de rétractation</h3>
                        <ul class="text-gray-700 space-y-2">
                            <li>• Produits périssables ou rapidement détériorables</li>
                            <li>• Produits confectionnés selon spécifications du client</li>
                            <li>• Semences et plants descellés pour raisons d'hygiène</li>
                            <li>• Produits sur mesure ou personnalisés</li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">6.4 Remboursement</h3>
                        <p class="text-gray-700">
                            En cas de rétractation, FarmShop rembourse la totalité des sommes versées, 
                            y compris les frais de livraison initiaux, dans un délai de 14 jours suivant 
                            la réception du produit retourné. Les frais de retour restent à votre charge.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Garanties -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4 border-b-2 border-green-500 pb-2">
                    Article 7 - Garanties
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg space-y-4">
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">7.1 Garantie légale de conformité</h3>
                        <p class="text-gray-700">
                            Durée: 2 ans pour les produits neufs, 1 an pour les produits d'occasion 
                            (Articles 1649bis et suivants du Code civil belge).
                        </p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">7.2 Garantie contre les vices cachés</h3>
                        <p class="text-gray-700">
                            Protection contre les défauts cachés rendant le produit impropre à l'usage 
                            ou diminuant son usage de manière importante.
                        </p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">7.3 Garanties commerciales</h3>
                        <ul class="text-gray-700 space-y-2">
                            <li>• Extension garantie constructeur disponible</li>
                            <li>• Service après-vente et pièces détachées</li>
                            <li>• Formation gratuite matériel complexe</li>
                        </ul>
                        <p class="text-gray-700 mt-2">
                            <a href="{{ route('legal.warranties') }}" class="text-green-600 hover:text-green-700 underline">
                                → Détails complets des garanties
                            </a>
                        </p>
                    </div>
                </div>
            </section>

            <!-- Responsabilité -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4 border-b-2 border-green-500 pb-2">
                    Article 8 - Responsabilité
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg space-y-4">
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">8.1 Responsabilité FarmShop</h3>
                        <p class="text-gray-700">
                            FarmShop s'engage à livrer des produits conformes aux descriptions et aux normes en vigueur. 
                            Notre responsabilité est couverte par une assurance professionnelle de 1.250.000€.
                        </p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">8.2 Limitation de responsabilité</h3>
                        <p class="text-gray-700">
                            La responsabilité de FarmShop ne peut être engagée pour des dommages indirects, 
                            pertes d'exploitation ou préjudices immatériels résultant de l'utilisation des produits.
                        </p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">8.3 Obligation de l'acheteur</h3>
                        <p class="text-gray-700">
                            L'acheteur s'engage à utiliser les produits conformément à leur destination et 
                            aux instructions du fabricant. Il doit vérifier la conformité des produits à la livraison.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Protection des données -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4 border-b-2 border-green-500 pb-2">
                    Article 9 - Protection des données personnelles
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg">
                    <p class="text-gray-700 mb-4">
                        FarmShop traite vos données personnelles conformément au RGPD et à la législation belge. 
                        Les données collectées sont nécessaires à la gestion de votre commande et de la relation commerciale.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('legal.privacy') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition-colors">
                            Politique de confidentialité
                        </a>
                        <a href="{{ route('legal.gdpr-rights') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">
                            Vos droits RGPD
                        </a>
                    </div>
                </div>
            </section>

            <!-- Litiges -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4 border-b-2 border-green-500 pb-2">
                    Article 10 - Résolution des litiges
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg space-y-4">
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">10.1 Médiation</h3>
                        <p class="text-gray-700">
                            En cas de litige, nous privilégions la résolution amiable. Les consommateurs peuvent 
                            recourir gratuitement à la médiation.
                            <a href="{{ route('legal.mediation') }}" class="text-green-600 hover:text-green-700 underline ml-1">
                                → Procédure de médiation
                            </a>
                        </p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">10.2 Droit applicable et juridiction</h3>
                        <p class="text-gray-700">
                            Les présentes CGV sont soumises au droit belge. En cas de litige, les tribunaux de Bruxelles 
                            sont seuls compétents, sauf pour les consommateurs qui peuvent saisir les tribunaux de leur domicile.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Entrée en vigueur -->
            <section class="text-center border-t pt-6">
                <p class="text-sm text-gray-500">
                    CGV en vigueur depuis le {{ date('d/m/Y') }}<br>
                    Conformes au Code de droit économique belge (Livre VI et XII)
                </p>
            </section>
        </div>
    </div>
</div>
@endsection
