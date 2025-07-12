@extends('layouts.app')

@section('title', 'Conditions de Retour                                                           <h3 class="text-xl font-semibold text-farm-green-700 mb-4">
                                Support (si besoin)
                            </h3>
                            <p class="mb-2">En cas de problème, contactez-nous :</p>
                            <ul class="text-sm">
                                <li>Email : s.mef2703@gmail.com</li>
                                <li>Téléphone : +32 486 39 02 07</li>
                                <li>Horaires : Lun-Sam 8h-17h</li>
                            </ul>     <h3 class="text-xl font-semibold text-farm-green-700 mb-4">
                                Procédure en ligne
                            </h3>         <div class="bg-white border-2 border-farm-green-200 rounded-lg p-6">
                            <h3 class="text-xl font-semibold text-farm-green-700 mb-4">
                                🖥️ Procédure en ligne
                            </h3>
                            <p class="mb-2">Pour retourner un produit :</p>
                            <ul class="text-sm">
                                <li>• Rendez-vous sur votre page "Mes achats"</li>
                                <li>• Suivez les procédures indiquées</li>
                                <li>• Sélectionnez les produits à retourner</li>
                                <li>• Confirmez votre demande</li>
                            </ul>
                        </div>               <h3 class="text-xl font-semibold text-farm-green-700 mb-4">
                                🖥️ Procédure en ligne
                            </h3>                       <h3 class="text-xl font-semibold text-farm-green-700 mb-4">
                                🖥️ Procédure en ligne
                            </h3>                       <h3 class="text-xl font-semibold text-farm-green-700 mb-4">
                                🖥️ Procédure en ligne
                            </h3>FarmShop')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="container mx-auto px-6 max-w-4xl">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-4xl font-bold text-farm-green-800 mb-8">
                ↩️ Conditions de Retour et d'Échange
            </h1>
            
            <div class="prose prose-lg max-w-none">
                <p class="text-gray-600 mb-6">
                    <strong>Dernière mise à jour :</strong> {{ now()->format('d/m/Y') }}
                </p>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-farm-green-700 mb-4">1. Politique générale</h2>
                    <p class="mb-4">
                        Chez FarmShop, votre satisfaction est notre priorité. Nous offrons une politique de retour 
                        flexible pour vous assurer une expérience d'achat sereine.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-farm-green-700 mb-4">2. Délais de retour</h2>
                    <div class="bg-farm-green-50 border-l-4 border-farm-green-400 p-4 mb-4">
                        <h3 class="text-lg font-semibold text-farm-green-800 mb-2">📦 Achats de matériel</h3>
                        <ul class="list-disc pl-6">
                            <li><strong>Matériel neuf :</strong> 14 jours à compter de la réception</li>
                        </ul>
                    </div>
                    <div class="bg-farm-brown-50 border-l-4 border-farm-brown-400 p-4 mb-4">
                        <h3 class="text-lg font-semibold text-farm-brown-800 mb-2">📅 Locations</h3>
                        <ul class="list-disc pl-6">
                            <li><strong>Annulation :</strong> Jusqu'à 24h avant le début de location</li>
                            <li><strong>Retour anticipé :</strong> Aucun remboursement (matériel peut être retourné mais non remboursé)</li>
                        </ul>
                    </div>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-farm-green-700 mb-4">3. Conditions du matériel</h2>
                    <p class="mb-4">Pour être accepté en retour, le matériel doit :</p>
                    <ul class="list-disc pl-6 mb-4">
                        <li>✅ Être dans son état d'origine</li>
                        <li>✅ Être complet avec tous ses accessoires</li>
                        <li>✅ Être accompagné de sa facture d'achat</li>
                        <li>✅ Ne pas avoir été endommagé par mauvais usage</li>
                        <li>✅ Être dans son emballage d'origine (si possible)</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-farm-green-700 mb-4">4. Procédure de retour</h2>
                    
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                        <h3 class="text-lg font-semibold text-yellow-800 mb-2">Important : Produits alimentaires</h3>
                        <p class="text-yellow-700">
                            Seuls les produits non alimentaires sont retournables. Si votre commande contient des produits alimentaires et non alimentaires, seuls les produits non alimentaires pourront être retournés et vous serez remboursé partiellement.
                        </p>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-white border-2 border-farm-green-200 rounded-lg p-6">
                            <h3 class="text-xl font-semibold text-farm-green-700 mb-4">
                                �️ Étape 1 : Procédure en ligne
                            </h3>
                            <p class="mb-2">Pour retourner un produit :</p>
                            <ul class="text-sm">
                                <li>• Rendez-vous sur votre page "Mes achats"</li>
                                <li>• Suivez les procédures indiquées</li>
                                <li>• Sélectionnez les produits à retourner</li>
                                <li>• Confirmez votre demande</li>
                            </ul>
                        </div>
                        <div class="bg-white border-2 border-farm-green-200 rounded-lg p-6">
                            <h3 class="text-xl font-semibold text-farm-green-700 mb-4">
                                � Support (si besoin)
                            </h3>
                            <p class="mb-2">En cas de problème, contactez-nous :</p>
                            <ul class="text-sm">
                                <li>📧 Email : s.mef2703@gmail.com</li>
                                <li>📞 Téléphone : +32 486 39 02 07</li>
                                <li>🕒 Lun-Sam 8h-17h</li>
                            </ul>
                        </div>
                    </div>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-farm-green-700 mb-4">5. Remboursements</h2>
                    
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                        <h3 class="text-lg font-semibold text-green-800 mb-2">💰 Délais de remboursement</h3>
                        <ul class="list-disc pl-6 text-green-700">
                            <li><strong>Après réception :</strong> 3-5 jours ouvrés</li>
                            <li><strong>Sur votre compte :</strong> Selon votre banque (2-10 jours)</li>
                            <li><strong>Frais de retour :</strong> Remboursés si défaut de notre part</li>
                        </ul>
                    </div>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-farm-green-700 mb-4">6. Échanges</h2>
                    <p class="mb-4">
                        Les échanges sont possibles dans les mêmes conditions que les retours. 
                        Si la différence de prix est en votre faveur, nous procédons au remboursement. 
                        Si elle est en notre faveur, nous vous facturerons la différence.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-farm-green-700 mb-4">7. Cas particuliers</h2>
                    
                    <div class="space-y-4">
                        <div class="border-l-4 border-red-400 bg-red-50 p-4">
                            <h3 class="font-semibold text-red-800 mb-2">🔧 Matériel défectueux</h3>
                            <p class="text-red-700">
                                Retour gratuit avec remboursement intégral ou remplacement. 
                                Garantie constructeur applicable.
                            </p>
                        </div>
                        
                        <div class="border-l-4 border-blue-400 bg-blue-50 p-4">
                            <h3 class="font-semibold text-blue-800 mb-2">📦 Erreur de livraison</h3>
                            <p class="text-blue-700">
                                Si nous avons livré le mauvais produit, nous organisons l'échange 
                                gratuitement dans les 48h.
                            </p>
                        </div>
                    </div>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-farm-green-700 mb-4">8. Contact</h2>
                    <div class="bg-farm-green-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-farm-green-800 mb-4">
                            Notre équipe est là pour vous aider
                        </h3>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <p class="font-medium">Service Retours</p>
                                <p>Email : s.mef2703@gmail.com</p>
                                <p>Téléphone : +32 486 39 02 07</p>
                            </div>
                            <div>
                                <p class="font-medium">Horaires</p>
                                <p>Lundi - Samedi : 8h00 - 17h00</p>
                            </div>
                        </div>
                    </div>
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
