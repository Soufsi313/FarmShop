@extends('layouts.app')

@section('title', 'Médiation et Résolution des Litiges - FarmShop')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-lg rounded-lg p-8">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">Médiation et Résolution des Litiges</h1>
                <p class="text-gray-600">Solutions amiables pour résoudre vos différends</p>
                <p class="text-sm text-gray-500 mt-2">Conforme aux Articles XVII.17 et suivants du Code de droit économique belge</p>
            </div>

            <!-- Introduction -->
            <section class="mb-8">
                <div class="bg-blue-50 border-l-4 border-blue-400 p-6 rounded-r-lg">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM9 9a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-medium text-blue-800 mb-2">Résolution amiable privilégiée</h3>
                            <p class="text-blue-700">
                                FarmShop s'engage à résoudre tous les litiges de manière amiable avant tout recours judiciaire. 
                                Nous mettons à votre disposition plusieurs niveaux de médiation pour trouver une solution 
                                équitable et rapide à vos préoccupations.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Processus de résolution -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6 border-b-2 border-blue-500 pb-2">
                    Processus de résolution des litiges
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg">
                    <div class="space-y-6">
                        <!-- Étape 1 -->
                        <div class="bg-white p-6 rounded-lg border-l-4 border-green-400">
                            <div class="flex items-center mb-4">
                                <div class="bg-green-100 rounded-full w-10 h-10 flex items-center justify-center mr-4">
                                    <span class="text-green-600 font-bold">1</span>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">Contact direct avec FarmShop</h3>
                            </div>
                            <div class="ml-14">
                                <p class="text-gray-700 mb-3">
                                    <strong>Première étape obligatoire :</strong> Contactez notre service client pour exposer votre problème.
                                </p>
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <h4 class="font-medium text-gray-800 mb-2">Moyens de contact</h4>
                                        <ul class="text-gray-700 text-sm space-y-1">
                                            <li>• <strong>Email :</strong> litige@farmshop.be</li>
                                            <li>• <strong>Téléphone :</strong> +32 2 123 45 67</li>
                                            <li>• <strong>Courrier :</strong> Service Litiges</li>
                                            <li>• <strong>Formulaire en ligne :</strong> Espace client</li>
                                        </ul>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-800 mb-2">Délai de traitement</h4>
                                        <ul class="text-gray-700 text-sm space-y-1">
                                            <li>• <strong>Accusé de réception :</strong> 48h</li>
                                            <li>• <strong>Première réponse :</strong> 5 jours ouvrables</li>
                                            <li>• <strong>Résolution :</strong> 15 jours maximum</li>
                                            <li>• <strong>Cas complexes :</strong> 30 jours</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Étape 2 -->
                        <div class="bg-white p-6 rounded-lg border-l-4 border-yellow-400">
                            <div class="flex items-center mb-4">
                                <div class="bg-yellow-100 rounded-full w-10 h-10 flex items-center justify-center mr-4">
                                    <span class="text-yellow-600 font-bold">2</span>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">Médiation interne</h3>
                            </div>
                            <div class="ml-14">
                                <p class="text-gray-700 mb-3">
                                    Si la première étape n'aboutit pas, votre dossier est transmis à notre médiateur interne.
                                </p>
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <h4 class="font-medium text-gray-800 mb-2">Processus</h4>
                                        <ul class="text-gray-700 text-sm space-y-1">
                                            <li>• Analyse impartiale du dossier</li>
                                            <li>• Audition des parties si nécessaire</li>
                                            <li>• Recherche de solution équitable</li>
                                            <li>• Proposition de résolution</li>
                                        </ul>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-800 mb-2">Avantages</h4>
                                        <ul class="text-gray-700 text-sm space-y-1">
                                            <li>• Gratuit et confidentiel</li>
                                            <li>• Rapide (10-15 jours)</li>
                                            <li>• Connaissance du secteur</li>
                                            <li>• Solution personnalisée</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Étape 3 -->
                        <div class="bg-white p-6 rounded-lg border-l-4 border-blue-400">
                            <div class="flex items-center mb-4">
                                <div class="bg-blue-100 rounded-full w-10 h-10 flex items-center justify-center mr-4">
                                    <span class="text-blue-600 font-bold">3</span>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">Médiation externe</h3>
                            </div>
                            <div class="ml-14">
                                <p class="text-gray-700 mb-3">
                                    Recours à un médiateur externe agréé pour une résolution impartiale.
                                </p>
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <h4 class="font-medium text-gray-800 mb-2">Organismes partenaires</h4>
                                        <ul class="text-gray-700 text-sm space-y-1">
                                            <li>• Service de Médiation pour le Consommateur</li>
                                            <li>• Chambre de Commerce et d'Industrie</li>
                                            <li>• Centre Belge d'Arbitrage et de Médiation</li>
                                            <li>• Médiateurs sectoriels agricoles</li>
                                        </ul>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-800 mb-2">Conditions</h4>
                                        <ul class="text-gray-700 text-sm space-y-1">
                                            <li>• Échec de la médiation interne</li>
                                            <li>• Accord des deux parties</li>
                                            <li>• Frais partagés (si applicable)</li>
                                            <li>• Durée : 30-60 jours</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Étape 4 -->
                        <div class="bg-white p-6 rounded-lg border-l-4 border-red-400">
                            <div class="flex items-center mb-4">
                                <div class="bg-red-100 rounded-full w-10 h-10 flex items-center justify-center mr-4">
                                    <span class="text-red-600 font-bold">4</span>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">Recours judiciaire</h3>
                            </div>
                            <div class="ml-14">
                                <p class="text-gray-700 mb-3">
                                    En dernier recours, si aucune solution amiable n'a pu être trouvée.
                                </p>
                                <div class="bg-red-50 p-4 rounded">
                                    <p class="text-red-700 text-sm">
                                        <strong>Important :</strong> Avant tout recours judiciaire, la tentative de médiation 
                                        est fortement recommandée et peut être exigée par certaines juridictions.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Formulaire de saisine médiation -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6 border-b-2 border-blue-500 pb-2">
                    Demande de médiation
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg">
                    <div class="bg-white p-6 rounded border">
                        <h3 class="font-semibold text-gray-800 mb-4">Formulaire de saisine du médiateur</h3>
                        <form action="#" method="POST" class="space-y-4">
                            @csrf
                            
                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <label for="nom_demandeur" class="block text-sm font-medium text-gray-700 mb-1">
                                        Nom complet du demandeur <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="nom_demandeur" name="nom_demandeur" required 
                                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label for="qualite" class="block text-sm font-medium text-gray-700 mb-1">
                                        Qualité <span class="text-red-500">*</span>
                                    </label>
                                    <select id="qualite" name="qualite" required 
                                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">Sélectionnez votre qualité</option>
                                        <option value="particulier">Particulier/Consommateur</option>
                                        <option value="agriculteur">Agriculteur</option>
                                        <option value="entreprise">{{ __("app.forms.company") }}<//option>
                                        <option value="association">Association</option>
                                        <option value="collectivite">Collectivité</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <label for="email_demandeur" class="block text-sm font-medium text-gray-700 mb-1">
                                        Email <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" id="email_demandeur" name="email_demandeur" required 
                                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label for="telephone_demandeur" class="block text-sm font-medium text-gray-700 mb-1">
                                        Téléphone <span class="text-red-500">*</span>
                                    </label>
                                    <input type="tel" id="telephone_demandeur" name="telephone_demandeur" required 
                                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>

                            <div>
                                <label for="adresse_demandeur" class="block text-sm font-medium text-gray-700 mb-1">
                                    Adresse complète <span class="text-red-500">*</span>
                                </label>
                                <textarea id="adresse_demandeur" name="adresse_demandeur" rows="2" required 
                                          class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                            </div>

                            <div class="border-t pt-6">
                                <h4 class="font-medium text-gray-800 mb-4">Informations sur le litige</h4>
                                
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="numero_commande_litige" class="block text-sm font-medium text-gray-700 mb-1">
                                            Numéro de commande/contrat
                                        </label>
                                        <input type="text" id="numero_commande_litige" name="numero_commande_litige" 
                                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                               placeholder="FS-2025-XXXXX">
                                    </div>
                                    <div>
                                        <label for="date_litige" class="block text-sm font-medium text-gray-700 mb-1">
                                            Date du litige <span class="text-red-500">*</span>
                                        </label>
                                        <input type="date" id="date_litige" name="date_litige" required 
                                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                </div>

                                <div>
                                    <label for="type_litige" class="block text-sm font-medium text-gray-700 mb-1">
                                        Type de litige <span class="text-red-500">*</span>
                                    </label>
                                    <select id="type_litige" name="type_litige" required 
                                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">Sélectionnez le type de litige</option>
                                        <option value="livraison">Problème de livraison</option>
                                        <option value="conformite">Non-conformité du produit</option>
                                        <option value="garantie">Problème de garantie</option>
                                        <option value="facturation">Erreur de facturation</option>
                                        <option value="retractation">Refus de rétractation</option>
                                        <option value="remboursement">Problème de remboursement</option>
                                        <option value="service_client">Service client défaillant</option>
                                        <option value="installation">Problème d'installation</option>
                                        <option value="location">Litige de location</option>
                                        <option value="autre">Autre</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="description_litige" class="block text-sm font-medium text-gray-700 mb-1">
                                        Description détaillée du litige <span class="text-red-500">*</span>
                                    </label>
                                    <textarea id="description_litige" name="description_litige" rows="5" required 
                                              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                              placeholder="Décrivez précisément les faits, les dates importantes, les échanges avec FarmShop, vos demandes..."></textarea>
                                </div>

                                <div>
                                    <label for="montant_litige" class="block text-sm font-medium text-gray-700 mb-1">
                                        Montant en litige (€)
                                    </label>
                                    <input type="number" id="montant_litige" name="montant_litige" step="0.01" min="0" 
                                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label for="solution_souhaitee" class="block text-sm font-medium text-gray-700 mb-1">
                                        Solution souhaitée <span class="text-red-500">*</span>
                                    </label>
                                    <textarea id="solution_souhaitee" name="solution_souhaitee" rows="3" required 
                                              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                              placeholder="Quelle solution souhaitez-vous ? (remboursement, remplacement, réparation, indemnisation...)"></textarea>
                                </div>
                            </div>

                            <div class="border-t pt-6">
                                <h4 class="font-medium text-gray-800 mb-4">Démarches préalables</h4>
                                
                                <div>
                                    <label for="demarches_effectuees" class="block text-sm font-medium text-gray-700 mb-1">
                                        Démarches déjà effectuées <span class="text-red-500">*</span>
                                    </label>
                                    <div class="space-y-2">
                                        <label class="flex items-center">
                                            <input type="checkbox" name="demarches[]" value="contact_service_client" 
                                                   class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                            <span class="ml-3 text-sm text-gray-700">Contact avec le service client</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" name="demarches[]" value="reclamation_ecrite" 
                                                   class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                            <span class="ml-3 text-sm text-gray-700">Réclamation écrite</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" name="demarches[]" value="mediation_interne" 
                                                   class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                            <span class="ml-3 text-sm text-gray-700">Médiation interne FarmShop</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" name="demarches[]" value="mise_en_demeure" 
                                                   class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                            <span class="ml-3 text-sm text-gray-700">Mise en demeure</span>
                                        </label>
                                    </div>
                                </div>

                                <div>
                                    <label for="pieces_jointes" class="block text-sm font-medium text-gray-700 mb-1">
                                        Pièces justificatives
                                    </label>
                                    <input type="file" id="pieces_jointes" name="pieces_jointes[]" multiple 
                                           accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <p class="text-sm text-gray-500 mt-1">
                                        Formats acceptés : PDF, DOC, DOCX, JPG, PNG (5 Mo max par fichier)
                                    </p>
                                </div>
                            </div>

                            <div class="border-t pt-6">
                                <h4 class="font-medium text-gray-800 mb-4">Type de médiation souhaité</h4>
                                
                                <div class="space-y-3">
                                    <label class="flex items-start">
                                        <input type="radio" name="type_mediation" value="interne" 
                                               class="mt-1 h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                        <div class="ml-3">
                                            <span class="text-sm font-medium text-gray-700">Médiation interne FarmShop</span>
                                            <p class="text-sm text-gray-600">Gratuite, rapide, expertise sectorielle</p>
                                        </div>
                                    </label>
                                    <label class="flex items-start">
                                        <input type="radio" name="type_mediation" value="externe_consommateur" 
                                               class="mt-1 h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                        <div class="ml-3">
                                            <span class="text-sm font-medium text-gray-700">Service de Médiation pour le Consommateur</span>
                                            <p class="text-sm text-gray-600">Médiateur officiel, gratuit pour les consommateurs</p>
                                        </div>
                                    </label>
                                    <label class="flex items-start">
                                        <input type="radio" name="type_mediation" value="externe_professionnel" 
                                               class="mt-1 h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                        <div class="ml-3">
                                            <span class="text-sm font-medium text-gray-700">Médiation professionnelle</span>
                                            <p class="text-sm text-gray-600">Centre Belge d'Arbitrage et de Médiation (frais partagés)</p>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <input type="checkbox" id="acceptation_mediation" name="acceptation_mediation" required 
                                       class="mt-1 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <label for="acceptation_mediation" class="ml-3 text-sm text-gray-700">
                                    Je demande officiellement l'ouverture d'une procédure de médiation et j'accepte 
                                    de participer de bonne foi à cette démarche amiable. Je comprends que la médiation 
                                    est un processus volontaire et confidentiel.
                                    <span class="text-red-500">*</span>
                                </label>
                            </div>

                            <div class="text-center">
                                <button type="submit" 
                                        class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                                    Déposer ma demande de médiation
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="mt-6 bg-yellow-50 border-l-4 border-yellow-400 p-4">
                        <h4 class="font-medium text-yellow-800 mb-2">Délai de traitement</h4>
                        <p class="text-yellow-700 text-sm">
                            Votre demande sera étudiée dans les 5 jours ouvrables. Nous vous confirmerons la recevabilité 
                            de votre demande et vous indiquerons les prochaines étapes de la procédure de médiation.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Médiateurs partenaires -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6 border-b-2 border-blue-500 pb-2">
                    Médiateurs et organismes partenaires
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg">
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="bg-white p-6 rounded border">
                            <h3 class="font-semibold text-gray-800 mb-4">Service de Médiation pour le Consommateur</h3>
                            <div class="text-gray-700 text-sm space-y-2">
                                <p><strong>Compétence :</strong> Litiges entre consommateurs et entreprises</p>
                                <p><strong>Coût :</strong> Gratuit pour les consommateurs</p>
                                <p><strong>Délai :</strong> 90 jours maximum</p>
                                <p><strong>Site web :</strong> 
                                    <a href="https://mediationconsommateur.be" target="_blank" class="text-blue-600 hover:text-blue-700">
                                        mediationconsommateur.be
                                    </a>
                                </p>
                                <p><strong>Contact :</strong> +32 2 702 52 00</p>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded border">
                            <h3 class="font-semibold text-gray-800 mb-4">Centre Belge d'Arbitrage et de Médiation</h3>
                            <div class="text-gray-700 text-sm space-y-2">
                                <p><strong>Compétence :</strong> Tous types de litiges commerciaux</p>
                                <p><strong>Coût :</strong> Frais selon barème (partagés)</p>
                                <p><strong>Délai :</strong> 60 jours en moyenne</p>
                                <p><strong>Site web :</strong> 
                                    <a href="https://www.cepani.be" target="_blank" class="text-blue-600 hover:text-blue-700">
                                        www.cepani.be
                                    </a>
                                </p>
                                <p><strong>Contact :</strong> +32 2 515 08 35</p>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded border">
                            <h3 class="font-semibold text-gray-800 mb-4">Chambre de Commerce et d'Industrie</h3>
                            <div class="text-gray-700 text-sm space-y-2">
                                <p><strong>Compétence :</strong> Litiges commerciaux sectoriels</p>
                                <p><strong>Coût :</strong> Tarifs préférentiels membres</p>
                                <p><strong>Délai :</strong> 45-60 jours</p>
                                <p><strong>Site web :</strong> 
                                    <a href="https://www.cci.be" target="_blank" class="text-blue-600 hover:text-blue-700">
                                        www.cci.be
                                    </a>
                                </p>
                                <p><strong>Contact :</strong> +32 2 500 02 11</p>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded border">
                            <h3 class="font-semibold text-gray-800 mb-4">Médiateurs Sectoriels Agricoles</h3>
                            <div class="text-gray-700 text-sm space-y-2">
                                <p><strong>Compétence :</strong> Litiges spécifiques au secteur agricole</p>
                                <p><strong>Coût :</strong> Variable selon organisme</p>
                                <p><strong>Délai :</strong> 30-45 jours</p>
                                <p><strong>Spécialités :</strong> Matériel agricole, semences, phytosanitaires</p>
                                <p><strong>Contact :</strong> Via FarmShop</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Plateforme européenne ODR -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6 border-b-2 border-blue-500 pb-2">
                    Plateforme européenne de résolution en ligne
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg">
                    <div class="bg-white p-6 rounded border">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mr-4">
                                <svg class="h-12 w-12 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800 mb-3">Plateforme ODR (Online Dispute Resolution)</h3>
                                <p class="text-gray-700 mb-4">
                                    Conformément au Règlement UE n°524/2013, les consommateurs de l'Union européenne 
                                    peuvent utiliser la plateforme de résolution en ligne des litiges de la Commission européenne.
                                </p>
                                
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <h4 class="font-medium text-gray-800 mb-2">Conditions d'utilisation</h4>
                                        <ul class="text-gray-700 text-sm space-y-1">
                                            <li>• Contrat conclu en ligne</li>
                                            <li>• Vendeur établi dans l'UE</li>
                                            <li>• Consommateur résidant dans l'UE</li>
                                            <li>• Litiges contractuels uniquement</li>
                                        </ul>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-800 mb-2">Processus</h4>
                                        <ul class="text-gray-700 text-sm space-y-1">
                                            <li>• Dépôt de plainte en ligne</li>
                                            <li>• Sélection d'un organisme RLL</li>
                                            <li>• Médiation/arbitrage selon choix</li>
                                            <li>• Résolution dans les 90 jours</li>
                                        </ul>
                                    </div>
                                </div>
                                
                                <div class="mt-4 text-center">
                                    <a href="https://ec.europa.eu/consumers/odr" target="_blank" 
                                       class="inline-flex items-center bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                        Accéder à la plateforme ODR
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Juridictions compétentes -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6 border-b-2 border-blue-500 pb-2">
                    Juridictions compétentes
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg">
                    <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                        <h4 class="font-medium text-red-800 mb-2">Recours judiciaire en dernier ressort</h4>
                        <p class="text-red-700 text-sm">
                            Si toutes les tentatives de résolution amiable échouent, les juridictions belges 
                            sont compétentes selon les règles ci-dessous.
                        </p>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="bg-white p-6 rounded border">
                            <h3 class="font-semibold text-gray-800 mb-4">Consommateurs</h3>
                            <div class="text-gray-700 text-sm space-y-3">
                                <div>
                                    <h4 class="font-medium text-gray-800 mb-1">Justice de Paix</h4>
                                    <p>Litiges jusqu'à 5.000 € (art. 590 C.jud.)</p>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-800 mb-1">Tribunal de Première Instance</h4>
                                    <p>Litiges de 5.000 € à 75.000 €</p>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-800 mb-1">Compétence territoriale</h4>
                                    <p>Au choix du consommateur :</p>
                                    <ul class="list-disc list-inside mt-1 text-xs">
                                        <li>Domicile du consommateur</li>
                                        <li>Siège social de FarmShop</li>
                                        <li>Lieu d'exécution du contrat</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded border">
                            <h3 class="font-semibold text-gray-800 mb-4">Professionnels</h3>
                            <div class="text-gray-700 text-sm space-y-3">
                                <div>
                                    <h4 class="font-medium text-gray-800 mb-1">Tribunal de Commerce</h4>
                                    <p>Litiges commerciaux entre professionnels</p>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-800 mb-1">Justice de Paix</h4>
                                    <p>Litiges jusqu'à 5.000 € (même entre pros)</p>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-800 mb-1">Compétence territoriale</h4>
                                    <p>Selon les CGV ou à défaut :</p>
                                    <ul class="list-disc list-inside mt-1 text-xs">
                                        <li>Siège social du défendeur</li>
                                        <li>Lieu d'exécution du contrat</li>
                                        <li>Lieu de formation du contrat</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 bg-blue-50 border-l-4 border-blue-400 p-4">
                        <h4 class="font-medium text-blue-800 mb-2">Droit applicable</h4>
                        <div class="text-blue-700 text-sm space-y-1">
                            <p>• <strong>Loi applicable :</strong> Droit belge (domicile de FarmShop SPRL)</p>
                            <p>• <strong>Conventions internationales :</strong> Convention de Vienne (vente internationale de marchandises)</p>
                            <p>• <strong>Règlements européens :</strong> Rome I et II, Bruxelles I bis</p>
                            <p>• <strong>Protection consommateurs :</strong> Lois impératives du pays de résidence du consommateur</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Contacts et informations -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6 border-b-2 border-blue-500 pb-2">
                    Contacts et assistance
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg">
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="bg-white p-6 rounded border">
                            <h3 class="font-semibold text-gray-800 mb-4">Service Litiges FarmShop</h3>
                            <div class="text-gray-700 text-sm space-y-2">
                                <p><strong>Responsable :</strong> Marie Dubois, Médiatrice interne</p>
                                <p><strong>Email :</strong> 
                                    <a href="mailto:litige@farmshop.be" class="text-blue-600 hover:text-blue-700">
                                        litige@farmshop.be
                                    </a>
                                </p>
                                <p><strong>Téléphone :</strong> +32 2 123 45 68</p>
                                <p><strong>Horaires :</strong> 9h-17h (lundi au vendredi)</p>
                                <p><strong>Adresse :</strong><br>
                                    FarmShop SPRL - Service Litiges<br>
                                    Rue de l'Agriculture, 123<br>
                                    1000 Bruxelles, Belgique
                                </p>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded border">
                            <h3 class="font-semibold text-gray-800 mb-4">Assistance juridique</h3>
                            <div class="text-gray-700 text-sm space-y-2">
                                <p><strong>Bureau d'aide juridique :</strong> Gratuit sous conditions de revenus</p>
                                <p><strong>Assurance protection juridique :</strong> Vérifiez votre contrat</p>
                                <p><strong>Avocat spécialisé :</strong> Droit de la consommation/commercial</p>
                                <p><strong>Organisations de consommateurs :</strong></p>
                                <ul class="list-disc list-inside text-xs mt-1">
                                    <li>Test Achats : <a href="https://testachats.be" class="text-blue-600">testachats.be</a></li>
                                    <li>CRIOC : <a href="https://crioc.be" class="text-blue-600">crioc.be</a></li>
                                    <li>Association de consommateurs : Aide et conseils</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 bg-green-50 border-l-4 border-green-400 p-4">
                        <h4 class="font-medium text-green-800 mb-2">Nos engagements</h4>
                        <div class="text-green-700 text-sm space-y-2">
                            <p>• <strong>Réponse rapide :</strong> Accusé de réception sous 48h, première réponse sous 5 jours</p>
                            <p>• <strong>Dialogue constructif :</strong> Recherche systématique de solutions amiables</p>
                            <p>• <strong>Transparence :</strong> Information claire sur le processus et les délais</p>
                            <p>• <strong>Équité :</strong> Traitement impartial de tous les dossiers</p>
                            <p>• <strong>Suivi :</strong> Accompagnement jusqu'à la résolution complète</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Références légales -->
            <section class="text-center border-t pt-6">
                <h3 class="font-semibold text-gray-800 mb-3">Références légales</h3>
                <div class="text-sm text-gray-600 space-y-1">
                    <p><strong>Médiation :</strong> Articles XVII.17 à XVII.43 du Code de droit économique belge</p>
                    <p><strong>Directive ADR :</strong> Directive 2013/11/UE relative au règlement extrajudiciaire des litiges</p>
                    <p><strong>Règlement ODR :</strong> Règlement UE 524/2013 relatif au règlement en ligne des litiges</p>
                    <p><strong>Compétence judiciaire :</strong> Code judiciaire belge et Règlement Bruxelles I bis</p>
                </div>
                <p class="text-sm text-gray-500 mt-4">
                    Cette page d'information n'a pas de valeur contractuelle et complète nos conditions générales.
                </p>
            </section>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-remplissage des champs si données disponibles
    const numeroCommande = document.getElementById('numero_commande_litige');
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('commande') && numeroCommande) {
        numeroCommande.value = urlParams.get('commande');
    }

    // Validation du formulaire
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const demarches = document.querySelectorAll('input[name="demarches[]"]:checked');
            if (demarches.length === 0) {
                e.preventDefault();
                alert('Veuillez indiquer quelles démarches vous avez déjà effectuées.');
                return false;
            }
        });
    }

    // Calcul automatique de la date de litige si récente
    const dateLitige = document.getElementById('date_litige');
    if (dateLitige) {
        const aujourd_hui = new Date();
        const il_y_a_30_jours = new Date(aujourd_hui.getTime() - (30 * 24 * 60 * 60 * 1000));
        dateLitige.max = aujourd_hui.toISOString().split('T')[0];
        dateLitige.min = il_y_a_30_jours.toISOString().split('T')[0];
    }
});
</script>
@endsection
