@extends('layouts.app')

@section('title', 'Demande de Données Personnelles - FarmShop')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-lg rounded-lg p-8">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">Demande de Données Personnelles</h1>
                <p class="text-gray-600">Accédez à vos informations personnelles en toute transparence</p>
                <p class="text-sm text-gray-500 mt-2">Conforme au RGPD - Article 15 (Droit d'accès)</p>
            </div>

            <!-- Introduction -->
            <section class="mb-8">
                <div class="bg-blue-50 border-l-4 border-blue-400 p-6 rounded-r-lg">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-medium text-blue-800 mb-2">Votre droit d'accès</h3>
                            <p class="text-blue-700">
                                Vous avez le droit d'obtenir une copie de toutes vos données personnelles que nous détenons, 
                                ainsi que des informations détaillées sur leur traitement. Cette demande est entièrement gratuite.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Informations incluses -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6 border-b-2 border-blue-500 pb-2">
                    Informations incluses dans votre export
                </h2>
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="font-semibold text-gray-800 mb-4 flex items-center">
                            <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Données d'identification
                        </h3>
                        <ul class="text-gray-700 space-y-2 text-sm">
                            <li>• Nom, prénom et coordonnées</li>
                            <li>• Informations de compte (email, téléphone)</li>
                            <li>• Adresses de livraison et facturation</li>
                            <li>• Date de création et dernière connexion</li>
                            <li>• Statut du compte (actif, suspendu, etc.)</li>
                            <li>• Préférences de communication</li>
                        </ul>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="font-semibold text-gray-800 mb-4 flex items-center">
                            <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Activité commerciale
                        </h3>
                        <ul class="text-gray-700 space-y-2 text-sm">
                            <li>• Historique complet des commandes</li>
                            <li>• Contrats de location actifs et passés</li>
                            <li>• Factures et moyens de paiement utilisés</li>
                            <li>• Avis et évaluations produits</li>
                            <li>• Liste de souhaits et favoris</li>
                            <li>• Communications avec le service client</li>
                        </ul>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="font-semibold text-gray-800 mb-4 flex items-center">
                            <svg class="h-5 w-5 text-purple-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Données techniques
                        </h3>
                        <ul class="text-gray-700 space-y-2 text-sm">
                            <li>• Logs de connexion et navigation</li>
                            <li>• Préférences de cookies</li>
                            <li>• Données d'analyse anonymisées</li>
                            <li>• Historique des recherches</li>
                            <li>• Paramètres de compte et notifications</li>
                            <li>• Données de géolocalisation (si autorisée)</li>
                        </ul>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="font-semibold text-gray-800 mb-4 flex items-center">
                            <svg class="h-5 w-5 text-orange-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Métadonnées de traitement
                        </h3>
                        <ul class="text-gray-700 space-y-2 text-sm">
                            <li>• Finalités de traitement de chaque donnée</li>
                            <li>• Bases légales invoquées (RGPD)</li>
                            <li>• Durées de conservation prévues</li>
                            <li>• Destinataires des données (internes/externes)</li>
                            <li>• Transferts vers pays tiers (le cas échéant)</li>
                            <li>• Historique des consentements</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- Formulaire de demande -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6 border-b-2 border-blue-500 pb-2">
                    Formulaire de demande d'accès
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg">
                    <form action="#" method="POST" class="space-y-6" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Identification du demandeur -->
                        <div class="bg-white p-6 rounded-lg border">
                            <h3 class="font-semibold text-gray-800 mb-4">1. Identification du demandeur</h3>
                            
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nom <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="nom" name="nom" required 
                                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                           placeholder="Votre nom de famille">
                                </div>
                                <div>
                                    <label for="prenom" class="block text-sm font-medium text-gray-700 mb-2">
                                        Prénom <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="prenom" name="prenom" required 
                                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                           placeholder="Votre prénom">
                                </div>
                            </div>

                            <div class="mt-4">
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Adresse email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" id="email" name="email" required 
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       placeholder="email@example.com">
                                <p class="text-sm text-gray-500 mt-1">
                                    Utilisez l'adresse email associée à votre compte FarmShop
                                </p>
                            </div>

                            <div class="mt-4">
                                <label for="date_naissance" class="block text-sm font-medium text-gray-700 mb-2">
                                    Date de naissance <span class="text-red-500">*</span>
                                </label>
                                <input type="date" id="date_naissance" name="date_naissance" required 
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <p class="text-sm text-gray-500 mt-1">
                                    Nécessaire pour vérifier votre identité
                                </p>
                            </div>
                        </div>

                        <!-- Type de demande -->
                        <div class="bg-white p-6 rounded-lg border">
                            <h3 class="font-semibold text-gray-800 mb-4">2. Type de demande</h3>
                            
                            <div class="space-y-4">
                                <div class="flex items-start">
                                    <input type="radio" id="acces_complet" name="type_demande" value="acces_complet" 
                                           class="mt-1 h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500" checked>
                                    <label for="acces_complet" class="ml-3">
                                        <span class="font-medium text-gray-800">Accès complet à mes données</span>
                                        <p class="text-sm text-gray-600">
                                            Export de toutes vos données personnelles détenues par FarmShop
                                        </p>
                                    </label>
                                </div>
                                
                                <div class="flex items-start">
                                    <input type="radio" id="acces_partiel" name="type_demande" value="acces_partiel" 
                                           class="mt-1 h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                    <label for="acces_partiel" class="ml-3">
                                        <span class="font-medium text-gray-800">Accès partiel / données spécifiques</span>
                                        <p class="text-sm text-gray-600">
                                            Export de catégories spécifiques de données (précisez dans le champ détails)
                                        </p>
                                    </label>
                                </div>
                                
                                <div class="flex items-start">
                                    <input type="radio" id="info_traitement" name="type_demande" value="info_traitement" 
                                           class="mt-1 h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                    <label for="info_traitement" class="ml-3">
                                        <span class="font-medium text-gray-800">Informations sur le traitement uniquement</span>
                                        <p class="text-sm text-gray-600">
                                            Finalités, bases légales, destinataires sans export des données
                                        </p>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Format souhaité -->
                        <div class="bg-white p-6 rounded-lg border">
                            <h3 class="font-semibold text-gray-800 mb-4">3. Format de l'export (optionnel)</h3>
                            
                            <div class="grid md:grid-cols-3 gap-4">
                                <div class="flex items-center">
                                    <input type="checkbox" id="format_pdf" name="formats[]" value="pdf" 
                                           class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" checked>
                                    <label for="format_pdf" class="ml-2 text-sm text-gray-700">
                                        PDF (lisible)
                                    </label>
                                </div>
                                
                                <div class="flex items-center">
                                    <input type="checkbox" id="format_csv" name="formats[]" value="csv" 
                                           class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="format_csv" class="ml-2 text-sm text-gray-700">
                                        CSV (tableur)
                                    </label>
                                </div>
                                
                                <div class="flex items-center">
                                    <input type="checkbox" id="format_json" name="formats[]" value="json" 
                                           class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="format_json" class="ml-2 text-sm text-gray-700">
                                        JSON (technique)
                                    </label>
                                </div>
                            </div>
                            
                            <p class="text-sm text-gray-500 mt-2">
                                Si aucun format n'est sélectionné, nous vous fournirons un export PDF par défaut.
                            </p>
                        </div>

                        <!-- Détails complémentaires -->
                        <div class="bg-white p-6 rounded-lg border">
                            <h3 class="font-semibold text-gray-800 mb-4">4. Détails complémentaires (optionnel)</h3>
                            
                            <div>
                                <label for="details" class="block text-sm font-medium text-gray-700 mb-2">
                                    Précisions sur votre demande
                                </label>
                                <textarea id="details" name="details" rows="4" 
                                          class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                          placeholder="Si vous souhaitez accéder à des données spécifiques, des périodes particulières, ou avez des questions spéciales, précisez-les ici..."></textarea>
                            </div>

                            <div class="mt-4">
                                <label for="periode" class="block text-sm font-medium text-gray-700 mb-2">
                                    Période concernée (optionnel)
                                </label>
                                <div class="grid md:grid-cols-2 gap-4">
                                    <input type="date" id="date_debut" name="date_debut" 
                                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                           placeholder="Date de début">
                                    <input type="date" id="date_fin" name="date_fin" 
                                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                           placeholder="Date de fin">
                                </div>
                                <p class="text-sm text-gray-500 mt-1">
                                    Laissez vide pour obtenir toutes les données sans restriction de période
                                </p>
                            </div>
                        </div>

                        <!-- Justificatif d'identité -->
                        <div class="bg-white p-6 rounded-lg border">
                            <h3 class="font-semibold text-gray-800 mb-4">5. Justificatif d'identité</h3>
                            
                            <div>
                                <label for="justificatif" class="block text-sm font-medium text-gray-700 mb-2">
                                    Document d'identité <span class="text-red-500">*</span>
                                </label>
                                <input type="file" id="justificatif" name="justificatif" required
                                       accept=".pdf,.jpg,.jpeg,.png" 
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <div class="mt-2 text-sm text-gray-500">
                                    <p class="mb-1"><strong>Documents acceptés :</strong></p>
                                    <ul class="list-disc list-inside space-y-1">
                                        <li>Carte d'identité belge (recto-verso)</li>
                                        <li>Passeport (page photo)</li>
                                        <li>Permis de conduire européen</li>
                                        <li>Carte de séjour (pour résidents UE)</li>
                                    </ul>
                                    <p class="mt-2"><strong>Format :</strong> PDF, JPG, PNG (max 10MB)</p>
                                </div>
                            </div>

                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mt-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-yellow-700">
                                            <strong>Sécurité :</strong> Votre document d'identité sera utilisé uniquement 
                                            pour vérifier votre identité et sera supprimé après traitement de votre demande 
                                            (maximum 60 jours).
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Mode de livraison -->
                        <div class="bg-white p-6 rounded-lg border">
                            <h3 class="font-semibold text-gray-800 mb-4">6. Mode de transmission</h3>
                            
                            <div class="space-y-4">
                                <div class="flex items-start">
                                    <input type="radio" id="livraison_email" name="mode_livraison" value="email" 
                                           class="mt-1 h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500" checked>
                                    <label for="livraison_email" class="ml-3">
                                        <span class="font-medium text-gray-800">Email sécurisé (recommandé)</span>
                                        <p class="text-sm text-gray-600">
                                            Lien de téléchargement chiffré envoyé à votre adresse email, 
                                            valable 7 jours
                                        </p>
                                    </label>
                                </div>
                                
                                <div class="flex items-start">
                                    <input type="radio" id="livraison_courrier" name="mode_livraison" value="courrier" 
                                           class="mt-1 h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                    <label for="livraison_courrier" class="ml-3">
                                        <span class="font-medium text-gray-800">Courrier postal</span>
                                        <p class="text-sm text-gray-600">
                                            Export sur support physique (USB) envoyé par courrier recommandé 
                                            (délai supplémentaire de 5-7 jours)
                                        </p>
                                    </label>
                                </div>
                                
                                <div class="flex items-start">
                                    <input type="radio" id="livraison_retrait" name="mode_livraison" value="retrait" 
                                           class="mt-1 h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                    <label for="livraison_retrait" class="ml-3">
                                        <span class="font-medium text-gray-800">Retrait en personne</span>
                                        <p class="text-sm text-gray-600">
                                            Retrait au siège social sur rendez-vous avec pièce d'identité 
                                            (Rue de l'Agriculture 123, 1000 Bruxelles)
                                        </p>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Consentements -->
                        <div class="bg-white p-6 rounded-lg border">
                            <h3 class="font-semibold text-gray-800 mb-4">7. Consentements et déclarations</h3>
                            
                            <div class="space-y-4">
                                <div class="flex items-start">
                                    <input type="checkbox" id="identite_confirmee" name="consentements[]" value="identite" required
                                           class="mt-1 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="identite_confirmee" class="ml-3 text-sm text-gray-700">
                                        Je certifie être le titulaire des données personnelles concernées par cette demande 
                                        et fournir un document d'identité authentique.
                                        <span class="text-red-500">*</span>
                                    </label>
                                </div>
                                
                                <div class="flex items-start">
                                    <input type="checkbox" id="usage_personnel" name="consentements[]" value="usage" required
                                           class="mt-1 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="usage_personnel" class="ml-3 text-sm text-gray-700">
                                        Je m'engage à utiliser ces données à des fins personnelles uniquement 
                                        et à ne pas les diffuser à des tiers.
                                        <span class="text-red-500">*</span>
                                    </label>
                                </div>
                                
                                <div class="flex items-start">
                                    <input type="checkbox" id="politique_lue" name="consentements[]" value="politique" required
                                           class="mt-1 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="politique_lue" class="ml-3 text-sm text-gray-700">
                                        J'ai lu et j'accepte la 
                                        <a href="{{ route('legal.privacy') }}" class="text-blue-600 hover:text-blue-700 underline" target="_blank">
                                            politique de confidentialité
                                        </a> 
                                        et les conditions de traitement de cette demande.
                                        <span class="text-red-500">*</span>
                                    </label>
                                </div>
                                
                                <div class="flex items-start">
                                    <input type="checkbox" id="notifications" name="consentements[]" value="notifications"
                                           class="mt-1 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="notifications" class="ml-3 text-sm text-gray-700">
                                        J'accepte de recevoir des notifications par email concernant le traitement 
                                        de ma demande (recommandé).
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Bouton de soumission -->
                        <div class="text-center">
                            <button type="submit" 
                                    class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition-colors font-medium inline-flex items-center">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Envoyer ma demande d'accès
                            </button>
                        </div>
                    </form>
                </div>
            </section>

            <!-- Informations pratiques -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6 border-b-2 border-blue-500 pb-2">
                    Informations pratiques
                </h2>
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="font-semibold text-gray-800 mb-3 flex items-center">
                            <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Délais de traitement
                        </h3>
                        <ul class="text-gray-700 space-y-2 text-sm">
                            <li>• <strong>Accusé de réception :</strong> Sous 48h ouvrables</li>
                            <li>• <strong>Vérification d'identité :</strong> 3-5 jours ouvrables</li>
                            <li>• <strong>Préparation de l'export :</strong> 5-15 jours ouvrables</li>
                            <li>• <strong>Délai maximum légal :</strong> 30 jours calendaires</li>
                            <li>• <strong>Extension possible :</strong> +60 jours si complexe</li>
                        </ul>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="font-semibold text-gray-800 mb-3 flex items-center">
                            <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Garanties et sécurité
                        </h3>
                        <ul class="text-gray-700 space-y-2 text-sm">
                            <li>• <strong>Gratuit :</strong> Première demande entièrement gratuite</li>
                            <li>• <strong>Sécurisé :</strong> Transmission chiffrée et authentifiée</li>
                            <li>• <strong>Complet :</strong> Toutes vos données sans exception</li>
                            <li>• <strong>Lisible :</strong> Format PDF accessible et compréhensible</li>
                            <li>• <strong>Confidentiel :</strong> Accès limité à votre personne uniquement</li>
                        </ul>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="font-semibold text-gray-800 mb-3 flex items-center">
                            <svg class="h-5 w-5 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Cas particuliers
                        </h3>
                        <ul class="text-gray-700 space-y-2 text-sm">
                            <li>• <strong>Demandes répétées :</strong> Frais possibles après 3 demandes/an</li>
                            <li>• <strong>Données tierces :</strong> Anonymisation des références</li>
                            <li>• <strong>Données sensibles :</strong> Transmission renforcée</li>
                            <li>• <strong>Comptes fermés :</strong> Données archivées incluses</li>
                            <li>• <strong>Mineurs :</strong> Autorisation parentale requise</li>
                        </ul>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="font-semibold text-gray-800 mb-3 flex items-center">
                            <svg class="h-5 w-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            Limitations légales
                        </h3>
                        <ul class="text-gray-700 space-y-2 text-sm">
                            <li>• <strong>Obligations fiscales :</strong> Conservation 7-10 ans</li>
                            <li>• <strong>Procédures judiciaires :</strong> Données bloquées</li>
                            <li>• <strong>Sécurité/fraude :</strong> Logs de sécurité limités</li>
                            <li>• <strong>Données anonymes :</strong> Non incluses dans l'export</li>
                            <li>• <strong>Secrets d'affaires :</strong> Algorithmes exclus</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- Autres droits RGPD -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6 border-b-2 border-blue-500 pb-2">
                    Autres droits RGPD disponibles
                </h2>
                <div class="bg-gray-50 p-6 rounded-lg">
                    <p class="text-gray-700 mb-4">
                        En plus du droit d'accès, vous disposez d'autres droits concernant vos données personnelles :
                    </p>
                    
                    <div class="grid md:grid-cols-2 gap-4">
                        <a href="{{ route('legal.gdpr-rights') }}" 
                           class="bg-white p-4 rounded border hover:border-blue-300 transition-colors block">
                            <h4 class="font-medium text-gray-800 mb-2">Exercer tous mes droits RGPD</h4>
                            <p class="text-sm text-gray-600">
                                Rectification, effacement, portabilité, opposition, limitation du traitement
                            </p>
                        </a>
                        
                        <a href="{{ route('legal.privacy') }}" 
                           class="bg-white p-4 rounded border hover:border-blue-300 transition-colors block">
                            <h4 class="font-medium text-gray-800 mb-2">{{ __("app.legal.privacy_policy") }}</h4>
                            <p class="text-sm text-gray-600">
                                Comprendre comment nous traitons vos données personnelles
                            </p>
                        </a>
                        
                        <a href="{{ route('legal.cookies') }}" 
                           class="bg-white p-4 rounded border hover:border-blue-300 transition-colors block">
                            <h4 class="font-medium text-gray-800 mb-2">Gestion des cookies</h4>
                            <p class="text-sm text-gray-600">
                                Contrôler les cookies et technologies de suivi
                            </p>
                        </a>
                        
                        <div class="bg-white p-4 rounded border">
                            <h4 class="font-medium text-gray-800 mb-2">Retrait du consentement</h4>
                            <p class="text-sm text-gray-600">
                                Retirez votre consentement pour les newsletters et marketing
                            </p>
                            <button class="text-blue-600 hover:text-blue-700 text-sm mt-1">
                                → Se désabonner
                            </button>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Contact et assistance -->
            <section class="bg-blue-50 border-l-4 border-blue-400 p-6 rounded-r-lg">
                <h3 class="text-lg font-semibold text-blue-800 mb-3">Besoin d'aide avec votre demande ?</h3>
                <div class="grid md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-blue-700 mb-2">
                            <strong>Délégué à la Protection des Données (DPO)</strong>
                        </p>
                        <p class="text-blue-600">
                            Email: dpo@farmshop.be<br>
                            Téléphone: +32 2 123 45 67<br>
                            Horaires: 9h-17h (lun-ven)<br>
                            Spécialiste RGPD et données personnelles
                        </p>
                    </div>
                    <div>
                        <p class="text-blue-700 mb-2">
                            <strong>Service Client</strong>
                        </p>
                        <p class="text-blue-600">
                            Email: contact@farmshop.be<br>
                            Téléphone: +32 2 123 45 67<br>
                            Chat en ligne: Disponible 9h-18h<br>
                            Assistance générale et technique
                        </p>
                    </div>
                </div>
                
                <div class="mt-4 p-3 bg-blue-100 rounded">
                    <p class="text-blue-800 text-sm">
                        <strong>💡 Conseil :</strong> Pour un traitement plus rapide, assurez-vous que l'adresse email 
                        utilisée dans ce formulaire correspond exactement à celle de votre compte FarmShop.
                    </p>
                </div>
            </section>

            <!-- Footer légal -->
            <section class="text-center border-t pt-6 mt-8">
                <p class="text-sm text-gray-500">
                    Demande traitée conformément à l'Article 15 du RGPD (UE 2016/679)<br>
                    et à la loi belge du 30 juillet 2018 relative à la protection des données personnelles
                </p>
            </section>
        </div>
    </div>
</div>

<script>
// Gestion dynamique du formulaire
document.addEventListener('DOMContentLoaded', function() {
    // Mise à jour du format selon le type de demande
    const typeRadios = document.querySelectorAll('input[name="type_demande"]');
    const formatCheckboxes = document.querySelectorAll('input[name="formats[]"]');
    
    typeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'info_traitement') {
                // Pour les infos traitement, seul PDF est pertinent
                formatCheckboxes.forEach(cb => {
                    if (cb.value === 'pdf') {
                        cb.checked = true;
                        cb.disabled = false;
                    } else {
                        cb.checked = false;
                        cb.disabled = true;
                    }
                });
            } else {
                // Réactiver tous les formats
                formatCheckboxes.forEach(cb => cb.disabled = false);
            }
        });
    });

    // Validation de la taille du fichier
    const fileInput = document.getElementById('justificatif');
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file && file.size > 10 * 1024 * 1024) { // 10MB
                alert('Le fichier est trop volumineux. Taille maximum: 10MB');
                this.value = '';
            }
        });
    }

    // Gestion des dates (pas de date future)
    const dateInputs = document.querySelectorAll('input[type="date"]');
    const today = new Date().toISOString().split('T')[0];
    dateInputs.forEach(input => {
        if (input.id !== 'date_fin') {
            input.max = today;
        }
    });
});
</script>
@endsection
