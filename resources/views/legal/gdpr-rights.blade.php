@extends('layouts.app')

@section('title', 'Vos Droits RGPD - FarmShop')

@section('content')
<div class="min-h-screen bg-gray-50 py-12" x-data="gdprRights">
    <div class="container mx-auto px-6 max-w-4xl">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-4xl font-bold text-farm-green-800 mb-8">
                ⚖️ Vos Droits RGPD
            </h1>
            
            <div class="mb-8">
                <p class="text-lg text-gray-600 mb-6">
                    Conformément au Règlement Général sur la Protection des Données (RGPD), 
                    vous disposez de plusieurs droits concernant vos données personnelles. 
                    Utilisez les formulaires ci-dessous pour exercer vos droits.
                </p>
            </div>

            <!-- Onglets -->
            <div class="border-b border-gray-200 mb-8">
                <nav class="-mb-px flex space-x-8">
                    <button @click="activeTab = 'access'" 
                            :class="activeTab === 'access' ? 'border-farm-green-500 text-farm-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                        📋 Droit d'accès
                    </button>
                    <button @click="activeTab = 'rectification'" 
                            :class="activeTab === 'rectification' ? 'border-farm-green-500 text-farm-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                        ✏️ Rectification
                    </button>
                    <button @click="activeTab = 'erasure'" 
                            :class="activeTab === 'erasure' ? 'border-farm-green-500 text-farm-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                        🗑️ Effacement
                    </button>
                    <button @click="activeTab = 'portability'" 
                            :class="activeTab === 'portability' ? 'border-farm-green-500 text-farm-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                        📦 Portabilité
                    </button>
                    <button @click="activeTab = 'opposition'" 
                            :class="activeTab === 'opposition' ? 'border-farm-green-500 text-farm-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                        🚫 Opposition
                    </button>
                </nav>
            </div>

            <!-- Contenu des onglets -->
            
            <!-- Droit d'accès -->
            <div x-show="activeTab === 'access'" class="space-y-6">
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
                    <h2 class="text-xl font-semibold text-blue-800 mb-2">
                        📋 Droit d'accès à vos données
                    </h2>
                    <p class="text-blue-700">
                        Vous avez le droit d'obtenir une copie de toutes les données personnelles 
                        que nous détenons sur vous.
                    </p>
                </div>
                
                <form @submit.prevent="submitAccessRequest" class="space-y-4">
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Nom complet *
                            </label>
                            <input type="text" x-model="forms.access.name" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-farm-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Email *
                            </label>
                            <input type="email" x-model="forms.access.email" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-farm-green-500">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Informations supplémentaires pour vérification
                        </label>
                        <textarea x-model="forms.access.details" rows="3"
                                  placeholder="Ex: Numéro de commande, date de dernière commande, adresse..."
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-farm-green-500"></textarea>
                    </div>
                    
                    <button type="submit" 
                            class="bg-farm-green-600 hover:bg-farm-green-700 text-white px-6 py-2 rounded-md font-medium">
                        Demander l'accès à mes données
                    </button>
                </form>
            </div>

            <!-- Droit de rectification -->
            <div x-show="activeTab === 'rectification'" class="space-y-6">
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                    <h2 class="text-xl font-semibold text-yellow-800 mb-2">
                        ✏️ Droit de rectification
                    </h2>
                    <p class="text-yellow-700">
                        Vous avez le droit de faire corriger des données inexactes ou de compléter 
                        des données incomplètes.
                    </p>
                </div>
                
                <form @submit.prevent="submitRectificationRequest" class="space-y-4">
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Nom complet *
                            </label>
                            <input type="text" x-model="forms.rectification.name" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-farm-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Email *
                            </label>
                            <input type="email" x-model="forms.rectification.email" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-farm-green-500">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Données à corriger *
                        </label>
                        <textarea x-model="forms.rectification.incorrect_data" rows="3" required
                                  placeholder="Décrivez les données incorrectes..."
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-farm-green-500"></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Corrections souhaitées *
                        </label>
                        <textarea x-model="forms.rectification.correct_data" rows="3" required
                                  placeholder="Indiquez les corrections à apporter..."
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-farm-green-500"></textarea>
                    </div>
                    
                    <button type="submit" 
                            class="bg-farm-green-600 hover:bg-farm-green-700 text-white px-6 py-2 rounded-md font-medium">
                        Demander une rectification
                    </button>
                </form>
            </div>

            <!-- Droit à l'effacement -->
            <div x-show="activeTab === 'erasure'" class="space-y-6">
                <div class="bg-red-50 border-l-4 border-red-400 p-4">
                    <h2 class="text-xl font-semibold text-red-800 mb-2">
                        🗑️ Droit à l'effacement
                    </h2>
                    <p class="text-red-700">
                        Vous avez le droit de demander la suppression de vos données personnelles 
                        dans certaines circonstances.
                    </p>
                </div>
                
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                    <h3 class="font-semibold text-amber-800 mb-2">⚠️ Important</h3>
                    <p class="text-amber-700 text-sm">
                        Certaines données peuvent être conservées pour des raisons légales 
                        (ex: facturation, obligations comptables). Nous vous informerons 
                        des données qui ne peuvent pas être supprimées.
                    </p>
                </div>
                
                <form @submit.prevent="submitErasureRequest" class="space-y-4">
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Nom complet *
                            </label>
                            <input type="text" x-model="forms.erasure.name" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-farm-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Email *
                            </label>
                            <input type="email" x-model="forms.erasure.email" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-farm-green-500">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Motif de la demande *
                        </label>
                        <select x-model="forms.erasure.reason" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-farm-green-500">
                            <option value="">Sélectionnez un motif</option>
                            <option value="no_longer_necessary">Les données ne sont plus nécessaires</option>
                            <option value="withdraw_consent">Je retire mon consentement</option>
                            <option value="unlawful_processing">Traitement illicite</option>
                            <option value="legal_obligation">Obligation légale d'effacement</option>
                            <option value="other">Autre motif</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Détails supplémentaires
                        </label>
                        <textarea x-model="forms.erasure.details" rows="3"
                                  placeholder="Précisez votre demande..."
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-farm-green-500"></textarea>
                    </div>
                    
                    <button type="submit" 
                            class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-md font-medium">
                        Demander l'effacement de mes données
                    </button>
                </form>
            </div>

            <!-- Droit à la portabilité -->
            <div x-show="activeTab === 'portability'" class="space-y-6">
                <div class="bg-purple-50 border-l-4 border-purple-400 p-4">
                    <h2 class="text-xl font-semibold text-purple-800 mb-2">
                        📦 Droit à la portabilité
                    </h2>
                    <p class="text-purple-700">
                        Vous avez le droit de recevoir vos données dans un format structuré 
                        et de les transmettre à un autre responsable de traitement.
                    </p>
                </div>
                
                <form @submit.prevent="submitPortabilityRequest" class="space-y-4">
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Nom complet *
                            </label>
                            <input type="text" x-model="forms.portability.name" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-farm-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Email *
                            </label>
                            <input type="email" x-model="forms.portability.email" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-farm-green-500">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Format souhaité
                        </label>
                        <select x-model="forms.portability.format"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-farm-green-500">
                            <option value="json">JSON</option>
                            <option value="csv">CSV</option>
                            <option value="xml">XML</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Données spécifiques souhaitées
                        </label>
                        <textarea x-model="forms.portability.specific_data" rows="3"
                                  placeholder="Précisez quelles données vous souhaitez exporter..."
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-farm-green-500"></textarea>
                    </div>
                    
                    <button type="submit" 
                            class="bg-farm-green-600 hover:bg-farm-green-700 text-white px-6 py-2 rounded-md font-medium">
                        Demander la portabilité de mes données
                    </button>
                </form>
            </div>

            <!-- Droit d'opposition -->
            <div x-show="activeTab === 'opposition'" class="space-y-6">
                <div class="bg-orange-50 border-l-4 border-orange-400 p-4">
                    <h2 class="text-xl font-semibold text-orange-800 mb-2">
                        🚫 Droit d'opposition
                    </h2>
                    <p class="text-orange-700">
                        Vous avez le droit de vous opposer au traitement de vos données personnelles 
                        dans certaines situations.
                    </p>
                </div>
                
                <form @submit.prevent="submitOppositionRequest" class="space-y-4">
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Nom complet *
                            </label>
                            <input type="text" x-model="forms.opposition.name" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-farm-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Email *
                            </label>
                            <input type="email" x-model="forms.opposition.email" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-farm-green-500">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Type de traitement concerné *
                        </label>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" x-model="forms.opposition.types" value="marketing"
                                       class="mr-2 text-farm-green-600 focus:ring-farm-green-500">
                                Marketing et communication commerciale
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" x-model="forms.opposition.types" value="profiling"
                                       class="mr-2 text-farm-green-600 focus:ring-farm-green-500">
                                Profilage et analyse comportementale
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" x-model="forms.opposition.types" value="analytics"
                                       class="mr-2 text-farm-green-600 focus:ring-farm-green-500">
                                Cookies d'analyse et de performance
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" x-model="forms.opposition.types" value="other"
                                       class="mr-2 text-farm-green-600 focus:ring-farm-green-500">
                                Autre (précisez ci-dessous)
                            </label>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Motif de l'opposition
                        </label>
                        <textarea x-model="forms.opposition.reason" rows="3"
                                  placeholder="Expliquez les raisons de votre opposition..."
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-farm-green-500"></textarea>
                    </div>
                    
                    <button type="submit" 
                            class="bg-farm-green-600 hover:bg-farm-green-700 text-white px-6 py-2 rounded-md font-medium">
                        M'opposer au traitement
                    </button>
                </form>
            </div>

            <!-- Informations générales -->
            <div class="mt-12 pt-8 border-t border-gray-200">
                <h2 class="text-2xl font-semibold text-farm-green-700 mb-4">
                    ℹ️ Informations importantes
                </h2>
                
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="bg-farm-green-50 rounded-lg p-6">
                        <h3 class="font-semibold text-farm-green-800 mb-3">⏱️ Délais de traitement</h3>
                        <ul class="text-sm text-farm-green-700 space-y-1">
                            <li>• Accusé de réception : 48h</li>
                            <li>• Traitement de la demande : 30 jours</li>
                            <li>• Extension possible : +60 jours si complexe</li>
                        </ul>
                    </div>
                    
                    <div class="bg-farm-brown-50 rounded-lg p-6">
                        <h3 class="font-semibold text-farm-brown-800 mb-3">🔐 Vérification d'identité</h3>
                        <p class="text-sm text-farm-brown-700">
                            Pour votre sécurité, nous pouvons vous demander des informations 
                            supplémentaires pour vérifier votre identité avant de traiter votre demande.
                        </p>
                    </div>
                </div>
                
                <div class="mt-6 bg-gray-50 rounded-lg p-6">
                    <h3 class="font-semibold text-gray-800 mb-3">📞 Contact direct</h3>
                    <p class="text-gray-700 mb-2">
                        Pour toute question sur vos droits RGPD ou si vous préférez nous contacter directement :
                    </p>
                    <ul class="text-sm text-gray-600">
                        <li>📧 Email : privacy@farmshop.be</li>
                        <li>📞 Téléphone : [Numéro de téléphone]</li>
                        <li>📮 Courrier : FarmShop SPRL, [Adresse complète]</li>
                    </ul>
                </div>
            </div>

            <div class="mt-8 pt-8 border-t border-gray-200">
                <a href="{{ url('/') }}" class="inline-flex items-center text-farm-green-600 hover:text-farm-green-700">
                    ← Retour à l'accueil
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('gdprRights', () => ({
        activeTab: 'access',
        
        forms: {
            access: { name: '', email: '', details: '' },
            rectification: { name: '', email: '', incorrect_data: '', correct_data: '' },
            erasure: { name: '', email: '', reason: '', details: '' },
            portability: { name: '', email: '', format: 'json', specific_data: '' },
            opposition: { name: '', email: '', types: [], reason: '' }
        },
        
        async submitAccessRequest() {
            await this.submitForm('access', this.forms.access);
        },
        
        async submitRectificationRequest() {
            await this.submitForm('rectification', this.forms.rectification);
        },
        
        async submitErasureRequest() {
            await this.submitForm('erasure', this.forms.erasure);
        },
        
        async submitPortabilityRequest() {
            await this.submitForm('portability', this.forms.portability);
        },
        
        async submitOppositionRequest() {
            await this.submitForm('opposition', this.forms.opposition);
        },
        
        async submitForm(type, data) {
            try {
                // Ici, vous pouvez ajouter l'appel AJAX vers votre backend
                alert(`Demande de ${type} envoyée avec succès ! Vous recevrez un accusé de réception par email.`);
                
                // Réinitialiser le formulaire
                this.forms[type] = type === 'opposition' 
                    ? { name: '', email: '', types: [], reason: '' }
                    : Object.fromEntries(Object.keys(this.forms[type]).map(key => [key, key === 'format' ? 'json' : '']));
                    
            } catch (error) {
                alert('Erreur lors de l\'envoi de la demande. Veuillez réessayer ou nous contacter directement.');
            }
        }
    }));
});
</script>
@endsection
