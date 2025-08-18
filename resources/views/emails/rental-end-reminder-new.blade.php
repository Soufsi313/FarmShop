<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        @if($hoursRemaining <= 3)
            Fin de location dans {{ $hoursRemaining }}h
        @elseif($hoursRemaining <= 12)
            Fin de location aujourd'hui
        @else
            Fin de location demain
        @endif
    </title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'farm-green': '#16a34a',
                        'farm-orange': '#ea580c',
                        'farm-yellow': '#fbbf24'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 font-sans">
    <div class="max-w-2xl mx-auto bg-white shadow-lg rounded-lg overflow-hidden">
        
        <!-- Header avec gradient -->
        <div class="bg-gradient-to-r from-farm-orange to-orange-600 px-8 py-6 text-white">
            <div class="flex items-center space-x-3">
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold">
                        @if($hoursRemaining <= 3)
                            ⚡ Fin imminente
                        @elseif($hoursRemaining <= 12)
                            🕛 Fin aujourd'hui
                        @else
                            ⏰ Rappel important
                        @endif
                    </h1>
                    <p class="text-orange-100">
                        @if($hoursRemaining <= 3)
                            Votre location se termine dans {{ $hoursRemaining }}h
                        @elseif($hoursRemaining <= 12)
                            Votre location se termine aujourd'hui
                        @else
                            Votre location se termine demain
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Contenu principal -->
        <div class="px-8 py-6">
            
            <!-- Salutation -->
            <div class="mb-6">
                <p class="text-lg text-gray-700">Bonjour <span class="font-semibold text-farm-green">{{ $user->name }}</span>,</p>
            </div>

            <!-- Alerte principale -->
            <div class="
                @if($hoursRemaining <= 3)
                    bg-red-50 border-l-4 border-red-500
                @elseif($hoursRemaining <= 12)
                    bg-orange-50 border-l-4 border-orange-500
                @else
                    bg-yellow-50 border-l-4 border-yellow-500
                @endif
                p-6 rounded-lg mb-6">
                
                <div class="flex items-start space-x-4">
                    <div class="
                        @if($hoursRemaining <= 3)
                            bg-red-500
                        @elseif($hoursRemaining <= 12)
                            bg-orange-500
                        @else
                            bg-yellow-500
                        @endif
                        text-white rounded-full p-2 flex-shrink-0">
                        @if($hoursRemaining <= 3)
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        @else
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                        @endif
                    </div>
                    <div class="flex-1">
                        <h2 class="text-xl font-bold
                            @if($hoursRemaining <= 3)
                                text-red-800
                            @elseif($hoursRemaining <= 12)
                                text-orange-800
                            @else
                                text-yellow-800
                            @endif
                            mb-2">
                            @if($hoursRemaining <= 3)
                                Fin de location imminente !
                            @elseif($hoursRemaining <= 12)
                                Fin de location aujourd'hui !
                            @else
                                Fin de location demain !
                            @endif
                        </h2>
                        <p class="
                            @if($hoursRemaining <= 3)
                                text-red-700
                            @elseif($hoursRemaining <= 12)
                                text-orange-700
                            @else
                                text-yellow-700
                            @endif
                            mb-4">
                            Votre location <strong>#{{ $orderLocation->order_number }}</strong> se termine 
                            @if($hoursRemaining <= 3)
                                dans <strong>{{ $hoursRemaining }} heures</strong>.
                            @elseif($hoursRemaining <= 12)
                                aujourd'hui.
                            @else
                                demain.
                            @endif
                        </p>
                        
                        <!-- Compteur temps restant -->
                        <div class="inline-flex items-center space-x-2 bg-white px-4 py-2 rounded-lg shadow-sm border">
                            <svg class="w-5 h-5 text-farm-orange" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="font-semibold text-gray-900">{{ $endTomorrow }}</span>
                            <span class="text-sm text-gray-600">(dans {{ $hoursRemaining }}h)</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations de commande -->
            <div class="bg-farm-green bg-opacity-5 border border-farm-green border-opacity-20 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-farm-green mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2v1a1 1 0 002 0V3a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 3a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                    </svg>
                    Détails de votre location
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Numéro de commande :</span>
                        <span class="font-semibold text-gray-900">#{{ $orderLocation->order_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Période :</span>
                        <span class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Durée totale :</span>
                        <span class="font-semibold text-gray-900">{{ $totalDays }} jour{{ $totalDays > 1 ? 's' : '' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Statut :</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            En cours
                        </span>
                    </div>
                </div>
            </div>

            <!-- Matériel à retourner -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-farm-orange" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"></path>
                        <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1V8a1 1 0 00-1-1h-3z"></path>
                    </svg>
                    Matériel à retourner
                </h3>
                
                @if(count($items) > 0)
                    <div class="space-y-3">
                        @foreach($items as $item)
                            <div class="flex items-center p-4 bg-gray-50 rounded-lg border">
                                <div class="w-10 h-10 bg-farm-green bg-opacity-10 rounded-lg flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-farm-green" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 2L3 7v11a1 1 0 001 1h3a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h3a1 1 0 001-1V7l-7-5z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900">{{ $item->product_name ?? 'Matériel agricole' }}</h4>
                                    <p class="text-sm text-gray-600">Quantité : {{ $item->quantity ?? 1 }}x</p>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                        À retourner
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-4 bg-gray-50 rounded-lg border">
                        <p class="text-gray-700">Matériel agricole loué - À retourner en bon état</p>
                    </div>
                @endif
            </div>

            <!-- Checklist de retour -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-blue-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    Liste de vérification avant retour
                </h3>
                
                <div class="space-y-3">
                    <div class="flex items-start space-x-3">
                        <div class="bg-blue-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm font-bold flex-shrink-0 mt-0.5">1</div>
                        <div>
                            <h4 class="font-semibold text-blue-900">Nettoyage complet</h4>
                            <p class="text-sm text-blue-700">Nettoyez soigneusement tous les équipements</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="bg-blue-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm font-bold flex-shrink-0 mt-0.5">2</div>
                        <div>
                            <h4 class="font-semibold text-blue-900">Vérification de l'état</h4>
                            <p class="text-sm text-blue-700">Inspectez le matériel pour détecter d'éventuels dommages</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="bg-blue-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm font-bold flex-shrink-0 mt-0.5">3</div>
                        <div>
                            <h4 class="font-semibold text-blue-900">Accessoires complets</h4>
                            <p class="text-sm text-blue-700">Vérifiez que tous les accessoires sont présents</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="bg-blue-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm font-bold flex-shrink-0 mt-0.5">4</div>
                        <div>
                            <h4 class="font-semibold text-blue-900">Photos recommandées</h4>
                            <p class="text-sm text-blue-700">Prenez des photos de l'état du matériel (recommandé)</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bouton d'action -->
            <div class="text-center mb-6">
                <a href="{{ config('app.frontend_url') }}/rental-orders/{{ $orderLocation->id }}" 
                   class="inline-flex items-center px-8 py-3 border border-transparent rounded-lg shadow-lg text-base font-medium text-white bg-gradient-to-r from-farm-green to-green-600 hover:from-green-600 hover:to-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 transform hover:scale-105">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                    </svg>
                    Suivre ma location
                </a>
            </div>

            <!-- Informations de contact -->
            <div class="bg-gray-100 rounded-lg p-6">
                <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-farm-orange" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                    </svg>
                    Besoin d'aide ?
                </h4>
                <p class="text-gray-700 text-sm mb-2">Notre équipe est disponible pour vous accompagner dans le processus de retour.</p>
                <div class="flex flex-wrap gap-4 text-sm">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                        </svg>
                        support@farmshop.com
                    </div>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                        </svg>
                        +33 1 23 45 67 89
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="bg-farm-green px-8 py-6 text-white">
            <div class="text-center">
                <h3 class="text-lg font-semibold mb-2">🌾 FarmShop - Location Matériel Agricole</h3>
                <p class="text-green-100 text-sm">Merci de votre confiance. Bon retour avec votre matériel !</p>
                <p class="text-green-200 text-xs mt-3 opacity-75">
                    Cet email de rappel a été envoyé automatiquement selon la durée de votre location.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
