@extends('layouts.app')

@section('title', 'Assurances et Couvertures - FarmShop')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-lg rounded-lg p-8">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">Assurances et Couvertures</h1>
                <p class="text-gray-600">Protection complète pour vos achats et locations</p>
                <p class="text-sm text-gray-500 mt-2">Conformément aux exigences légales belges et européennes</p>
            </div>

            <!-- Introduction -->
            <section class="mb-8">
                <div class="bg-blue-50 border-l-4 border-blue-400 p-6 rounded-r-lg">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-medium text-blue-800 mb-2">Votre protection avant tout</h3>
                            <p class="text-blue-700">
                                FarmShop souscrit toutes les assurances obligatoires et recommandées pour garantir 
                                votre sécurité et protéger vos intérêts. Découvrez nos couvertures et les assurances 
                                complémentaires disponibles pour vos achats et locations.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Assurances obligatoires FarmShop -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6 border-b-2 border-blue-500 pb-2">
                    Assurances obligatoires de FarmShop
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg space-y-6">
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-4">Responsabilité civile professionnelle</h3>
                        <div class="bg-white p-6 rounded border">
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <h4 class="font-medium text-gray-800 mb-3">Couverture</h4>
                                    <ul class="text-gray-700 text-sm space-y-1">
                                        <li>• <strong>Assureur :</strong> Ethias Assurances SA</li>
                                        <li>• <strong>Police n° :</strong> RC-PRO-2024-789456</li>
                                        <li>• <strong>Montant :</strong> 2.500.000 € par sinistre</li>
                                        <li>• <strong>Franchise :</strong> 250 € par sinistre</li>
                                        <li>• <strong>Validité :</strong> 01/01/2024 au 31/12/2024</li>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-800 mb-3">Protection</h4>
                                        <ul class="text-gray-700 text-sm space-y-1">
                                            <li>• Dommages corporels causés aux tiers</li>
                                            <li>• Dommages matériels aux biens clients</li>
                                            <li>• Préjudices économiques consécutifs</li>
                                            <li>• Défaut de conseil et d'information</li>
                                            <li>• Faute professionnelle dans la vente</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-800 mb-4">Responsabilité civile produits</h3>
                        <div class="bg-white p-6 rounded border">
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <h4 class="font-medium text-gray-800 mb-3">Couverture</h4>
                                    <ul class="text-gray-700 text-sm space-y-1">
                                        <li>• <strong>Assureur :</strong> AXA Belgium SA</li>
                                        <li>• <strong>Police n° :</strong> RC-PROD-2024-654321</li>
                                        <li>• <strong>Montant :</strong> 5.000.000 € par année</li>
                                        <li>• <strong>Franchise :</strong> 500 € par sinistre</li>
                                        <li>• <strong>Validité :</strong> 01/01/2024 au 31/12/2024</li>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-800 mb-3">Protection</h4>
                                        <ul class="text-gray-700 text-sm space-y-1">
                                            <li>• Défauts de fabrication (responsabilité distributeur)</li>
                                            <li>• Vices cachés non détectables</li>
                                            <li>• Information et notice insuffisantes</li>
                                            <li>• Dommages causés par les produits vendus</li>
                                            <li>• Rappel de produits défectueux</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-800 mb-4">Assurance matériel et stocks</h3>
                        <div class="bg-white p-6 rounded border">
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <h4 class="font-medium text-gray-800 mb-3">Couverture</h4>
                                    <ul class="text-gray-700 text-sm space-y-1">
                                        <li>• <strong>Assureur :</strong> Belfius Insurance SA</li>
                                        <li>• <strong>Police n° :</strong> MAT-STOCK-2024-987654</li>
                                        <li>• <strong>Montant :</strong> 1.500.000 € (stocks + matériel)</li>
                                        <li>• <strong>Franchise :</strong> 1.000 € par sinistre</li>
                                        <li>• <strong>Validité :</strong> 01/01/2024 au 31/12/2024</li>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-800 mb-3">Risques couverts</h4>
                                        <ul class="text-gray-700 text-sm space-y-1">
                                            <li>• Incendie, explosion, foudre</li>
                                            <li>• Dégâts des eaux, inondation</li>
                                            <li>• Vol et vandalisme</li>
                                            <li>• Bris de machines et équipements</li>
                                            <li>• Événements climatiques exceptionnels</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-800 mb-4">Assurance transport</h3>
                        <div class="bg-white p-6 rounded border">
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <h4 class="font-medium text-gray-800 mb-3">Couverture</h4>
                                    <ul class="text-gray-700 text-sm space-y-1">
                                        <li>• <strong>Assureur :</strong> KBC Assurances SA</li>
                                        <li>• <strong>Police n° :</strong> TRANSP-2024-456789</li>
                                        <li>• <strong>Montant :</strong> 250.000 € par expédition</li>
                                        <li>• <strong>Franchise :</strong> 150 € par sinistre</li>
                                        <li>• <strong>Validité :</strong> 01/01/2024 au 31/12/2024</li>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-800 mb-3">Protection</h4>
                                        <ul class="text-gray-700 text-sm space-y-1">
                                            <li>• Dommages pendant le transport</li>
                                            <li>• Vol de marchandises en transit</li>
                                            <li>• Avaries particulières et communes</li>
                                            <li>• Retards de livraison (selon conditions)</li>
                                            <li>• Frais de sauvetage et conservation</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Assurances location -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6 border-b-2 border-blue-500 pb-2">
                    Assurances spécifiques location
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg space-y-6">
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-4">Responsabilité civile location</h3>
                        <div class="bg-white p-6 rounded border">
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <h4 class="font-medium text-gray-800 mb-3">Protection du locataire</h4>
                                    <ul class="text-gray-700 text-sm space-y-1">
                                        <li>• Dommages causés à des tiers par le matériel loué</li>
                                        <li>• Accidents liés à l'utilisation normale</li>
                                        <li>• Défaillance technique imprévisible</li>
                                        <li>• Couverture : 1.000.000 € par sinistre</li>
                                        <li>• Franchise locataire : 200 €</li>
                                    </ul>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-800 mb-3">Protection du matériel</h4>
                                    <ul class="text-gray-700 text-sm space-y-1">
                                        <li>• Vol du matériel pendant la location</li>
                                        <li>• Dommages accidentels au matériel</li>
                                        <li>• Bris et détérioration imprévisible</li>
                                        <li>• Vandalisme et actes de malveillance</li>
                                        <li>• Franchise selon valeur du matériel</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-800 mb-4">Assurance dommages matériel de location</h3>
                        <div class="bg-white p-6 rounded border">
                            <div class="space-y-4">
                                <p class="text-gray-700">
                                    Chaque matériel en location est couvert contre les risques suivants :
                                </p>
                                <div class="grid md:grid-cols-3 gap-4">
                                    <div class="bg-green-50 p-4 rounded">
                                        <h4 class="font-medium text-green-800 mb-2">Risques couverts</h4>
                                        <ul class="text-green-700 text-sm space-y-1">
                                            <li>• Vol qualifié avec effraction</li>
                                            <li>• Incendie et explosion</li>
                                            <li>• Catastrophes naturelles</li>
                                            <li>• Bris accidentel</li>
                                            <li>• Dommages électriques</li>
                                        </ul>
                                    </div>
                                    <div class="bg-yellow-50 p-4 rounded">
                                        <h4 class="font-medium text-yellow-800 mb-2">Conditions</h4>
                                        <ul class="text-yellow-700 text-sm space-y-1">
                                            <li>• Utilisation conforme</li>
                                            <li>• Respect des consignes</li>
                                            <li>• Déclaration sous 48h</li>
                                            <li>• Dépôt de plainte si vol</li>
                                            <li>• Expertise contradictoire</li>
                                        </ul>
                                    </div>
                                    <div class="bg-red-50 p-4 rounded">
                                        <h4 class="font-medium text-red-800 mb-2">Exclusions</h4>
                                        <ul class="text-red-700 text-sm space-y-1">
                                            <li>• Mauvaise utilisation</li>
                                            <li>• Négligence grave</li>
                                            <li>• Usure normale</li>
                                            <li>• Vol simple (sans effraction)</li>
                                            <li>• Dommages esthétiques</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Assurances optionnelles pour clients -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6 border-b-2 border-blue-500 pb-2">
                    Assurances optionnelles pour clients
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg space-y-6">
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                        <h4 class="font-medium text-blue-800 mb-2">Protection renforcée</h4>
                        <p class="text-blue-700 text-sm">
                            FarmShop vous propose des assurances complémentaires pour une protection optimale 
                            de vos achats et locations. Ces assurances sont optionnelles et peuvent être souscrites 
                            au moment de votre commande.
                        </p>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-800 mb-4">Extension de garantie</h3>
                        <div class="bg-white p-6 rounded border">
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <h4 class="font-medium text-gray-800 mb-3">Couverture étendue</h4>
                                    <ul class="text-gray-700 text-sm space-y-1">
                                        <li>• Prolongation de garantie jusqu'à 5 ans</li>
                                        <li>• Pièces et main d'œuvre incluses</li>
                                        <li>• Intervention sur site (selon matériel)</li>
                                        <li>• Prêt de matériel de remplacement</li>
                                        <li>• Maintenance préventive incluse</li>
                                    </ul>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-800 mb-3">Tarification</h4>
                                    <ul class="text-gray-700 text-sm space-y-1">
                                        <li>• +1 an : 8% du prix d'achat</li>
                                        <li>• +2 ans : 12% du prix d'achat</li>
                                        <li>• +3 ans : 18% du prix d'achat</li>
                                        <li>• +5 ans : 25% du prix d'achat</li>
                                        <li>• Souscription dans les 30 jours</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-800 mb-4">Assurance vol et dommages accidentels</h3>
                        <div class="bg-white p-6 rounded border">
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <h4 class="font-medium text-gray-800 mb-3">Protection</h4>
                                    <ul class="text-gray-700 text-sm space-y-1">
                                        <li>• Vol sur l'exploitation ou en déplacement</li>
                                        <li>• Dommages accidentels non couverts</li>
                                        <li>• Vandalisme et actes de malveillance</li>
                                        <li>• Catastrophes naturelles renforcées</li>
                                        <li>• Remboursement à valeur à neuf (2 ans)</li>
                                    </ul>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-800 mb-3">Conditions</h4>
                                    <ul class="text-gray-700 text-sm space-y-1">
                                        <li>• Prime : 3-5% du prix selon matériel</li>
                                        <li>• Franchise : 5% avec minimum 100 €</li>
                                        <li>• Durée : 1 à 3 ans renouvelable</li>
                                        <li>• Déclaration sous 48h obligatoire</li>
                                        <li>• Mesures de sécurité à respecter</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-800 mb-4">Assurance perte d'exploitation</h3>
                        <div class="bg-white p-6 rounded border">
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <h4 class="font-medium text-gray-800 mb-3">Indemnisation</h4>
                                    <ul class="text-gray-700 text-sm space-y-1">
                                        <li>• Perte de revenus due à l'immobilisation</li>
                                        <li>• Frais supplémentaires de location</li>
                                        <li>• Retard de récolte ou de travaux</li>
                                        <li>• Frais de main d'œuvre supplémentaire</li>
                                        <li>• Plafond selon chiffre d'affaires</li>
                                    </ul>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-800 mb-3">Modalités</h4>
                                    <ul class="text-gray-700 text-sm space-y-1">
                                        <li>• Prime : 2-4% du chiffre d'affaires déclaré</li>
                                        <li>• Franchise temporelle : 48-72h</li>
                                        <li>• Durée d'indemnisation : jusqu'à 6 mois</li>
                                        <li>• Justificatifs comptables requis</li>
                                        <li>• Expertise économique possible</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Procédures de sinistre -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6 border-b-2 border-blue-500 pb-2">
                    Procédures en cas de sinistre
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg space-y-6">
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-4">Étapes à suivre</h3>
                        <div class="space-y-4">
                            <div class="bg-white p-4 rounded border-l-4 border-red-400">
                                <div class="flex items-center mb-2">
                                    <div class="bg-red-100 rounded-full w-8 h-8 flex items-center justify-center mr-3">
                                        <span class="text-red-600 font-bold text-sm">1</span>
                                    </div>
                                    <h4 class="font-medium text-gray-800">Déclaration immédiate (dans les 48h)</h4>
                                </div>
                                <div class="ml-11 text-gray-700 text-sm">
                                    <p>Contactez immédiatement :</p>
                                    <ul class="list-disc list-inside mt-1">
                                        <li><strong>FarmShop :</strong> +32 2 123 45 69 (urgence sinistres)</li>
                                        <li><strong>Email :</strong> sinistre@farmshop.be</li>
                                        <li><strong>Police :</strong> En cas de vol ou vandalisme</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="bg-white p-4 rounded border-l-4 border-yellow-400">
                                <div class="flex items-center mb-2">
                                    <div class="bg-yellow-100 rounded-full w-8 h-8 flex items-center justify-center mr-3">
                                        <span class="text-yellow-600 font-bold text-sm">2</span>
                                    </div>
                                    <h4 class="font-medium text-gray-800">Conservation des preuves</h4>
                                </div>
                                <div class="ml-11 text-gray-700 text-sm">
                                    <ul class="list-disc list-inside">
                                        <li>Photographiez les dommages sous tous les angles</li>
                                        <li>Conservez les pièces détériorées si possible</li>
                                        <li>Rassemblez tous documents (factures, contrats, notices)</li>
                                        <li>Notez les circonstances exactes du sinistre</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="bg-white p-4 rounded border-l-4 border-blue-400">
                                <div class="flex items-center mb-2">
                                    <div class="bg-blue-100 rounded-full w-8 h-8 flex items-center justify-center mr-3">
                                        <span class="text-blue-600 font-bold text-sm">3</span>
                                    </div>
                                    <h4 class="font-medium text-gray-800">Expertise et évaluation</h4>
                                </div>
                                <div class="ml-11 text-gray-700 text-sm">
                                    <ul class="list-disc list-inside">
                                        <li>Un expert sera mandaté par l'assureur dans les 5 jours</li>
                                        <li>Vous pouvez faire appel à un expert de votre choix</li>
                                        <li>Expertise contradictoire en cas de désaccord</li>
                                        <li>Rapport d'expertise sous 15 jours</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="bg-white p-4 rounded border-l-4 border-green-400">
                                <div class="flex items-center mb-2">
                                    <div class="bg-green-100 rounded-full w-8 h-8 flex items-center justify-center mr-3">
                                        <span class="text-green-600 font-bold text-sm">4</span>
                                    </div>
                                    <h4 class="font-medium text-gray-800">Indemnisation</h4>
                                </div>
                                <div class="ml-11 text-gray-700 text-sm">
                                    <ul class="list-disc list-inside">
                                        <li>Proposition d'indemnisation dans les 30 jours</li>
                                        <li>Paiement sous 15 jours après accord</li>
                                        <li>Possibilité de réparation in natura</li>
                                        <li>Remplacement par matériel équivalent si accord</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Formulaire de déclaration -->
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-4">Formulaire de déclaration de sinistre</h3>
                        <div class="bg-white p-6 rounded border">
                            <form action="#" method="POST" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="nom_declarant" class="block text-sm font-medium text-gray-700 mb-1">
                                            Nom du déclarant <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" id="nom_declarant" name="nom_declarant" required 
                                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label for="numero_police" class="block text-sm font-medium text-gray-700 mb-1">
                                            Numéro de police/contrat <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" id="numero_police" name="numero_police" required 
                                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                </div>

                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="date_sinistre" class="block text-sm font-medium text-gray-700 mb-1">
                                            Date du sinistre <span class="text-red-500">*</span>
                                        </label>
                                        <input type="datetime-local" id="date_sinistre" name="date_sinistre" required 
                                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label for="lieu_sinistre" class="block text-sm font-medium text-gray-700 mb-1">
                                            Lieu du sinistre <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" id="lieu_sinistre" name="lieu_sinistre" required 
                                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                </div>

                                <div>
                                    <label for="type_sinistre" class="block text-sm font-medium text-gray-700 mb-1">
                                        Type de sinistre <span class="text-red-500">*</span>
                                    </label>
                                    <select id="type_sinistre" name="type_sinistre" required 
                                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">Sélectionnez le type de sinistre</option>
                                        <option value="vol">Vol ou tentative de vol</option>
                                        <option value="vandalisme">Vandalisme</option>
                                        <option value="incendie">Incendie/Explosion</option>
                                        <option value="degats_eaux">Dégâts des eaux</option>
                                        <option value="bris_accidentel">Bris accidentel</option>
                                        <option value="dommage_electrique">Dommage électrique</option>
                                        <option value="catastrophe_naturelle">Catastrophe naturelle</option>
                                        <option value="responsabilite_civile">Responsabilité civile</option>
                                        <option value="defaut_produit">Défaut de produit</option>
                                        <option value="autre">Autre</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="materiel_concerne" class="block text-sm font-medium text-gray-700 mb-1">
                                        Matériel/produit concerné <span class="text-red-500">*</span>
                                    </label>
                                    <textarea id="materiel_concerne" name="materiel_concerne" rows="2" required 
                                              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                              placeholder="Marque, modèle, référence, numéro de série..."></textarea>
                                </div>

                                <div>
                                    <label for="circonstances" class="block text-sm font-medium text-gray-700 mb-1">
                                        Circonstances du sinistre <span class="text-red-500">*</span>
                                    </label>
                                    <textarea id="circonstances" name="circonstances" rows="4" required 
                                              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                              placeholder="Décrivez précisément ce qui s'est passé, les conditions météo, l'utilisation en cours..."></textarea>
                                </div>

                                <div>
                                    <label for="estimation_dommages" class="block text-sm font-medium text-gray-700 mb-1">
                                        Estimation des dommages (€)
                                    </label>
                                    <input type="number" id="estimation_dommages" name="estimation_dommages" step="0.01" min="0" 
                                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label for="police_prevenue" class="block text-sm font-medium text-gray-700 mb-1">
                                        Police prévenue (en cas de vol/vandalisme)
                                    </label>
                                    <div class="flex items-center space-x-4">
                                        <label class="flex items-center">
                                            <input type="radio" name="police_prevenue" value="oui" 
                                                   class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                            <span class="ml-2 text-sm text-gray-700">{{ __("app.common.yes") }}</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="police_prevenue" value="non" 
                                                   class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                            <span class="ml-2 text-sm text-gray-700">{{ __("app.common.no") }}</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="police_prevenue" value="non_applicable" 
                                                   class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                            <span class="ml-2 text-sm text-gray-700">Non applicable</span>
                                        </label>
                                    </div>
                                </div>

                                <div>
                                    <label for="numero_pv" class="block text-sm font-medium text-gray-700 mb-1">
                                        Numéro de PV/procès-verbal
                                    </label>
                                    <input type="text" id="numero_pv" name="numero_pv" 
                                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label for="temoins" class="block text-sm font-medium text-gray-700 mb-1">
                                        Témoins (nom, adresse, téléphone)
                                    </label>
                                    <textarea id="temoins" name="temoins" rows="2" 
                                              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                                </div>

                                <div>
                                    <label for="photos_documents" class="block text-sm font-medium text-gray-700 mb-1">
                                        Photos et documents
                                    </label>
                                    <input type="file" id="photos_documents" name="photos_documents[]" multiple 
                                           accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <p class="text-sm text-gray-500 mt-1">
                                        Photos des dommages, factures, PV police, devis réparation... (10 Mo max par fichier)
                                    </p>
                                </div>

                                <div class="flex items-start">
                                    <input type="checkbox" id="declaration_veracite" name="declaration_veracite" required 
                                           class="mt-1 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="declaration_veracite" class="ml-3 text-sm text-gray-700">
                                        Je déclare sur l'honneur que les informations fournies sont exactes et complètes. 
                                        Je suis conscient(e) que toute fausse déclaration peut entraîner la nullité du contrat 
                                        et des poursuites pour fraude à l'assurance.
                                        <span class="text-red-500">*</span>
                                    </label>
                                </div>

                                <div class="text-center">
                                    <button type="submit" 
                                            class="bg-red-600 text-white px-8 py-3 rounded-lg hover:bg-red-700 transition-colors font-medium">
                                        Déclarer le sinistre
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="mt-4 bg-orange-50 border-l-4 border-orange-400 p-4">
                            <h4 class="font-medium text-orange-800 mb-2">Traitement de votre déclaration</h4>
                            <p class="text-orange-700 text-sm">
                                Votre déclaration sera traitée dans les 24h ouvrables. Vous recevrez un accusé de réception 
                                avec le numéro de dossier et les coordonnées de votre gestionnaire sinistre. 
                                L'expert sera contacté dans les 48h pour les dossiers nécessitant une expertise.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Contacts assurances -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6 border-b-2 border-blue-500 pb-2">
                    Contacts assurances
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg">
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="bg-white p-6 rounded border">
                            <h3 class="font-semibold text-gray-800 mb-4">Service sinistres FarmShop</h3>
                            <div class="text-gray-700 text-sm space-y-2">
                                <p><strong>Responsable :</strong> Pierre Mercier</p>
                                <p><strong>Email :</strong> 
                                    <a href="mailto:sinistre@farmshop.be" class="text-blue-600 hover:text-blue-700">
                                        sinistre@farmshop.be
                                    </a>
                                </p>
                                <p><strong>Urgence 24h/24 :</strong> +32 2 123 45 69</p>
                                <p><strong>Bureau :</strong> +32 2 123 45 67</p>
                                <p><strong>Horaires :</strong> 8h-18h (lun-ven), urgences weekend</p>
                                <p><strong>Fax :</strong> +32 2 123 45 99</p>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded border">
                            <h3 class="font-semibold text-gray-800 mb-4">Contacts assureurs directs</h3>
                            <div class="text-gray-700 text-sm space-y-3">
                                <div>
                                    <p><strong>Ethias :</strong> +32 4 220 31 11</p>
                                    <p class="text-xs">RC Professionnelle</p>
                                </div>
                                <div>
                                    <p><strong>AXA Belgium :</strong> +32 2 678 61 11</p>
                                    <p class="text-xs">RC Produits</p>
                                </div>
                                <div>
                                    <p><strong>Belfius Insurance :</strong> +32 2 222 11 11</p>
                                    <p class="text-xs">Matériel et stocks</p>
                                </div>
                                <div>
                                    <p><strong>KBC :</strong> +32 78 152 152</p>
                                    <p class="text-xs">Transport</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 bg-green-50 border-l-4 border-green-400 p-4">
                        <h4 class="font-medium text-green-800 mb-2">Nos engagements</h4>
                        <div class="text-green-700 text-sm space-y-2">
                            <p>• <strong>Réactivité :</strong> Prise en charge de votre déclaration sous 24h</p>
                            <p>• <strong>Accompagnement :</strong> Suivi personnalisé tout au long de la procédure</p>
                            <p>• <strong>Transparence :</strong> Information régulière sur l'avancement de votre dossier</p>
                            <p>• <strong>Proximité :</strong> Interlocuteur unique dédié à votre dossier</p>
                            <p>• <strong>Efficacité :</strong> Recherche de la solution la plus rapide et équitable</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Références légales -->
            <section class="text-center border-t pt-6">
                <h3 class="font-semibold text-gray-800 mb-3">Références légales</h3>
                <div class="text-sm text-gray-600 space-y-1">
                    <p><strong>Assurances obligatoires :</strong> Loi du 25 juin 1992 sur le contrat d'assurance terrestre</p>
                    <p><strong>Responsabilité civile :</strong> Articles 1382-1386 du Code civil belge</p>
                    <p><strong>Responsabilité produits :</strong> Loi du 25 février 1991 relative à la responsabilité du fait des produits défectueux</p>
                    <p><strong>Protection consommateurs :</strong> Code de droit économique belge</p>
                </div>
                <p class="text-sm text-gray-500 mt-4">
                    Les polices d'assurance sont disponibles sur demande. Cette page est fournie à titre informatif.
                </p>
            </section>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validation des dates de sinistre
    const dateSinistre = document.getElementById('date_sinistre');
    if (dateSinistre) {
        const maintenant = new Date();
        dateSinistre.max = maintenant.toISOString().slice(0, 16);
        
        // Limite à 2 ans dans le passé
        const deuxAnsAvant = new Date(maintenant.getTime() - (2 * 365 * 24 * 60 * 60 * 1000));
        dateSinistre.min = deuxAnsAvant.toISOString().slice(0, 16);
    }

    // Gestion conditionnelle du numéro PV
    const typeSinistre = document.getElementById('type_sinistre');
    const numeroPV = document.getElementById('numero_pv');
    const policePrevenueRadios = document.querySelectorAll('input[name="police_prevenue"]');
    
    function gererChampsPV() {
        const type = typeSinistre.value;
        const needsPV = ['vol', 'vandalisme'].includes(type);
        
        if (needsPV) {
            numeroPV.closest('div').style.display = 'block';
            document.querySelector('label[for="police_prevenue"]').parentElement.style.display = 'block';
        } else {
            numeroPV.closest('div').style.display = 'none';
            document.querySelector('label[for="police_prevenue"]').parentElement.style.display = 'none';
        }
    }

    if (typeSinistre) {
        typeSinistre.addEventListener('change', gererChampsPV);
        gererChampsPV(); // Initialisation
    }

    // Validation taille fichiers
    const inputFichiers = document.getElementById('photos_documents');
    if (inputFichiers) {
        inputFichiers.addEventListener('change', function() {
            const fichiers = this.files;
            const tailleLimite = 10 * 1024 * 1024; // 10 Mo
            
            for (let i = 0; i < fichiers.length; i++) {
                if (fichiers[i].size > tailleLimite) {
                    alert(`Le fichier "${fichiers[i].name}" dépasse la limite de 10 Mo.`);
                    this.value = '';
                    break;
                }
            }
        });
    }
});
</script>
@endsection
