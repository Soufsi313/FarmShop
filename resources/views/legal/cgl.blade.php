@extends('layouts.app')

@section('title', 'Conditions Générales de Location - FarmShop')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-lg rounded-lg p-8">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">Conditions Générales de Location</h1>
                <p class="text-gray-600">Applicables aux locations de matériel sur la plateforme FarmShop</p>
                <p class="text-sm text-gray-500 mt-2">Version en vigueur depuis le {{ date('d/m/Y') }}</p>
            </div>

            <!-- Objet et champ d'application -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4 border-b-2 border-orange-500 pb-2">
                    Article 1 - Objet et champ d'application
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg">
                    <p class="text-gray-700 mb-4">
                        Les présentes Conditions Générales de Location (CGL) régissent exclusivement les contrats 
                        de location de matériel agricole proposés sur farmshop.be, exploité par FarmShop SPRL.
                    </p>
                    <div class="bg-orange-50 border-l-4 border-orange-400 p-4">
                        <p class="text-orange-800">
                            <strong>Important:</strong> Pour les achats de produits, consultez nos 
                            <a href="{{ route('legal.cgv') }}" class="text-orange-600 hover:text-orange-700 underline">
                                Conditions Générales de Vente
                            </a>.
                        </p>
                    </div>
                    <p class="text-gray-700 mt-4">
                        Ces CGL s'appliquent à tous les contrats de location conclus entre FarmShop (bailleur) 
                        et le client (locataire), qu'il soit professionnel ou particulier.
                    </p>
                </div>
            </section>

            <!-- Formation du contrat -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4 border-b-2 border-orange-500 pb-2">
                    Article 2 - Formation du contrat de location
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg space-y-4">
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">2.1 Processus de réservation</h3>
                        <ol class="text-gray-700 space-y-1 list-decimal list-inside">
                            <li>Sélection du matériel et période de location</li>
                            <li>Vérification disponibilité en temps réel</li>
                            <li>Identification complète du locataire</li>
                            <li>Vérification documents requis (permis, assurance)</li>
                            <li>Validation du devis et conditions spécifiques</li>
                            <li>Paiement caution et premier loyer</li>
                            <li>Signature électronique du contrat</li>
                        </ol>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">2.2 Conditions d'éligibilité</h3>
                        <ul class="text-gray-700 space-y-2">
                            <li>• Âge minimum: 18 ans révolus</li>
                            <li>• Justificatifs d'identité et domicile obligatoires</li>
                            <li>• Permis de conduire valide pour matériel roulant</li>
                            <li>• Assurance responsabilité civile en cours de validité</li>
                            <li>• Capacité juridique de contracter</li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">2.3 Documents contractuels</h3>
                        <p class="text-gray-700">
                            Le contrat de location comprend: ces CGL, le devis accepté, l'état des lieux d'entrée, 
                            les notices d'utilisation et consignes de sécurité spécifiques au matériel loué.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Durée et tarification -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4 border-b-2 border-orange-500 pb-2">
                    Article 3 - Durée et tarification
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg space-y-4">
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">3.1 Périodes de location</h3>
                        <ul class="text-gray-700 space-y-2">
                            <li>• Location à la journée: période de 24h (9h-9h)</li>
                            <li>• Location hebdomadaire: 7 jours calendaires</li>
                            <li>• Location mensuelle: 30 jours calendaires</li>
                            <li>• Location saisonnière: tarifs dégressifs selon durée</li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">3.2 Tarifs de location</h3>
                        <div class="bg-white p-4 rounded border">
                            <p class="text-gray-700 mb-2">Tous les tarifs sont exprimés TTC et comprennent:</p>
                            <ul class="text-gray-700 space-y-1 ml-4">
                                <li>• La mise à disposition du matériel</li>
                                <li>• L'entretien préventif et maintenance de base</li>
                                <li>• L'assistance technique pendant les heures ouvrables</li>
                                <li>• L'assurance matériel (hors franchise utilisateur)</li>
                            </ul>
                        </div>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">3.3 Prolongation et renouvellement</h3>
                        <p class="text-gray-700">
                            Toute prolongation doit être demandée au moins 48h avant l'échéance. 
                            Le renouvellement automatique peut être prévu pour les locations longue durée, 
                            avec préavis de résiliation de 30 jours.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Caution et paiement -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4 border-b-2 border-orange-500 pb-2">
                    Article 4 - Caution et modalités de paiement
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg space-y-4">
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">4.1 Dépôt de garantie</h3>
                        <p class="text-gray-700 mb-2">
                            Une caution est exigée pour toute location, d'un montant équivalent à:
                        </p>
                        <ul class="text-gray-700 space-y-1">
                            <li>• Outillage manuel: 50-200€</li>
                            <li>• Matériel motorisé léger: 300-800€</li>
                            <li>• Tracteurs et gros matériel: 1000-5000€</li>
                            <li>• Matériel spécialisé: selon évaluation</li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">4.2 Modalités de paiement</h3>
                        <div class="bg-white p-4 rounded border">
                            <p class="text-gray-700 mb-2"><strong>À la prise en charge:</strong></p>
                            <ul class="text-gray-700 space-y-1 ml-4">
                                <li>• Dépôt de garantie (autorisation CB ou espèces)</li>
                                <li>• Premier loyer ou totalité selon durée</li>
                            </ul>
                            <p class="text-gray-700 mb-2 mt-4"><strong>Locations longue durée:</strong></p>
                            <ul class="text-gray-700 space-y-1 ml-4">
                                <li>• Facturation mensuelle à terme échu</li>
                                <li>• Prélèvement automatique possible</li>
                            </ul>
                        </div>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">4.3 Restitution de la caution</h3>
                        <p class="text-gray-700">
                            La caution est restituée dans les 15 jours suivant la restitution du matériel, 
                            déduction faite des éventuels dommages, retards ou frais supplémentaires.
                        </p>
                    </div>
                </div>
            </section>

            <!-- État du matériel -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4 border-b-2 border-orange-500 pb-2">
                    Article 5 - État du matériel et remise
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg space-y-4">
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">5.1 État des lieux d'entrée</h3>
                        <p class="text-gray-700">
                            Un état des lieux contradictoire est établi lors de la remise du matériel. 
                            Le locataire doit signaler immédiatement tout défaut ou dommage apparent. 
                            L'absence de réserve vaut acceptation de l'état du matériel.
                        </p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">5.2 Formation et prise en main</h3>
                        <p class="text-gray-700">
                            FarmShop fournit une formation gratuite pour l'utilisation du matériel complexe. 
                            Cette formation est obligatoire et conditionne la remise du matériel. 
                            Un certificat de formation peut être délivré.
                        </p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">5.3 Documentation fournie</h3>
                        <ul class="text-gray-700 space-y-2">
                            <li>• Manuel d'utilisation et consignes de sécurité</li>
                            <li>• Carnet d'entretien et relevé horaire/kilométrique</li>
                            <li>• Coordonnées assistance technique 24h/24</li>
                            <li>• Procédure d'urgence et contact dépannage</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- Obligations du locataire -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4 border-b-2 border-orange-500 pb-2">
                    Article 6 - Obligations du locataire
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg space-y-4">
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">6.1 Utilisation conforme</h3>
                        <ul class="text-gray-700 space-y-2">
                            <li>• Utiliser le matériel selon sa destination et les instructions</li>
                            <li>• Respecter les charges et capacités maximales</li>
                            <li>• Ne pas modifier ou réparer sans autorisation</li>
                            <li>• Maintenir en bon état et nettoyer après usage</li>
                            <li>• Signaler immédiatement tout dysfonctionnement</li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">6.2 Entretien courant</h3>
                        <p class="text-gray-700">
                            Le locataire assure l'entretien quotidien: vérifications huile, eau, pneumatiques, 
                            nettoyage, ravitaillement carburant. Les carnets d'entretien doivent être tenus à jour.
                        </p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">6.3 Interdictions</h3>
                        <div class="bg-red-50 border-l-4 border-red-400 p-4">
                            <ul class="text-red-800 space-y-1">
                                <li>• Sous-location ou prêt à des tiers</li>
                                <li>• Usage commercial non autorisé</li>
                                <li>• Transport de matières dangereuses</li>
                                <li>• Utilisation par personnel non habilité</li>
                                <li>• Sortie du territoire sans autorisation</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Assurance et responsabilité -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4 border-b-2 border-orange-500 pb-2">
                    Article 7 - Assurance et responsabilité
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg space-y-4">
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">7.1 Assurance obligatoire du locataire</h3>
                        <p class="text-gray-700 mb-2">
                            Le locataire doit disposer d'une assurance responsabilité civile couvrant:
                        </p>
                        <ul class="text-gray-700 space-y-1">
                            <li>• Les dommages causés aux tiers</li>
                            <li>• Les dommages causés au matériel loué</li>
                            <li>• La responsabilité civile professionnelle (usage pro)</li>
                            <li>• Couverture minimale: 1.250.000€</li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">7.2 Franchise et déductibles</h3>
                        <div class="bg-white p-4 rounded border">
                            <p class="text-gray-700 mb-2">En cas de sinistre, franchise à charge du locataire:</p>
                            <ul class="text-gray-700 space-y-1">
                                <li>• Matériel &lt; 1000€: 150€</li>
                                <li>• Matériel 1000-5000€: 300€</li>
                                <li>• Matériel &gt; 5000€: 500€</li>
                                <li>• Vol ou destruction: 20% valeur de remplacement</li>
                            </ul>
                        </div>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">7.3 Responsabilité du locataire</h3>
                        <p class="text-gray-700">
                            Le locataire est responsable de tous dommages, vols, destructions pendant la durée 
                            de location, sauf vice propre du matériel ou défaut d'entretien imputable à FarmShop.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Restitution -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4 border-b-2 border-orange-500 pb-2">
                    Article 8 - Conditions de restitution
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg space-y-4">
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">8.1 Lieu et horaires de restitution</h3>
                        <p class="text-gray-700">
                            Restitution dans nos locaux aux horaires convenus ou sur site (supplément transport). 
                            Hors horaires d'ouverture: procédure de dépôt sécurisé disponible.
                        </p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">8.2 État des lieux de sortie</h3>
                        <p class="text-gray-700">
                            Contrôle contradictoire de l'état du matériel, relevé des compteurs, 
                            vérification de l'entretien effectué. Tout dommage non signalé sera facturé.
                        </p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">8.3 Pénalités de retard</h3>
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                            <p class="text-yellow-800 mb-2">
                                <strong>Retard de restitution:</strong>
                            </p>
                            <ul class="text-yellow-800 space-y-1">
                                <li>• Moins de 2h: tolérance</li>
                                <li>• 2h à 24h: 20% du tarif journalier</li>
                                <li>• Plus de 24h: tarif journalier majoré de 50%</li>
                                <li>• Plus de 72h: considéré comme vol (dépôt de plainte)</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Résiliation -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4 border-b-2 border-orange-500 pb-2">
                    Article 9 - Résiliation du contrat
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg space-y-4">
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">9.1 Résiliation par le locataire</h3>
                        <p class="text-gray-700">
                            Résiliation anticipée possible avec préavis de 48h minimum. 
                            Pas de remboursement pour les périodes payées d'avance sauf cas de force majeure.
                        </p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">9.2 Résiliation par FarmShop</h3>
                        <p class="text-gray-700 mb-2">
                            FarmShop peut résilier immédiatement en cas de:
                        </p>
                        <ul class="text-gray-700 space-y-1">
                            <li>• Non-paiement des loyers</li>
                            <li>• Usage non conforme ou dangereux</li>
                            <li>• Sous-location non autorisée</li>
                            <li>• Dégradation volontaire du matériel</li>
                            <li>• Non-respect des obligations contractuelles</li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">9.3 Droit de rétractation</h3>
                        <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
                            <p class="text-blue-800">
                                <strong>Important:</strong> Pour les prestations de services commencées avec votre accord exprès, 
                                le droit de rétractation de 14 jours ne s'applique pas. Un formulaire de renonciation 
                                au droit de rétractation est signé avant livraison.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Litiges -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4 border-b-2 border-orange-500 pb-2">
                    Article 10 - Résolution des litiges
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg space-y-4">
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">10.1 Médiation</h3>
                        <p class="text-gray-700">
                            En cas de litige, une solution amiable est recherchée prioritairement. 
                            Médiation possible via les services compétents.
                            <a href="{{ route('legal.mediation') }}" class="text-orange-600 hover:text-orange-700 underline ml-1">
                                → Procédure de médiation
                            </a>
                        </p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">10.2 Droit applicable</h3>
                        <p class="text-gray-700">
                            Contrats soumis au droit belge et aux dispositions du Code civil relatives au contrat de bail. 
                            Tribunaux de Bruxelles compétents sauf pour les consommateurs.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Entrée en vigueur -->
            <section class="text-center border-t pt-6">
                <p class="text-sm text-gray-500">
                    CGL en vigueur depuis le {{ date('d/m/Y') }}<br>
                    Conformes au Code civil belge (articles 1708 et suivants)
                </p>
            </section>
        </div>
    </div>
</div>
@endsection
