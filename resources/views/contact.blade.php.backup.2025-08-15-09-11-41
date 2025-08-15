@extends('layouts.app')

@section('title', 'Contact - FarmShop')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-orange-100 via-green-50 to-orange-100 py-12">
    <div class="max-w-4xl mx-auto px-6">
        <!-- En-tête -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-green-800 mb-4">
                📞 Contactez-nous
            </h1>
            <p class="text-xl text-orange-600 max-w-2xl mx-auto">
                Une question ? Un projet ? Notre équipe est là pour vous accompagner dans tous vos besoins agricoles.
            </p>
        </div>

        <div class="grid lg:grid-cols-2 gap-12">
            <!-- Formulaire de contact -->
            <div class="bg-white rounded-xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-green-700 mb-6">
                    ✉️ Envoyez-nous un message
                </h2>
                
                <form id="contactForm" action="/api/contact" method="POST">
                    @csrf
                    
                    <!-- Informations personnelles -->
                    <div class="grid md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nom complet *
                            </label>
                            <input type="text" id="name" name="name" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email *
                            </label>
                            <input type="email" id="email" name="email" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            Téléphone
                        </label>
                        <input type="tel" id="phone" name="phone"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <!-- Motif et priorité -->
                    <div class="grid md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">
                                Motif de contact *
                            </label>
                            <select id="reason" name="reason" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="">-- Sélectionnez un motif --</option>
                                <option value="mon_profil">Mon profil</option>
                                <option value="mes_achats">Mes achats</option>
                                <option value="mes_locations">Mes locations</option>
                                <option value="mes_donnees">Mes données personnelles</option>
                                <option value="support_technique">Support technique</option>
                                <option value="partenariat">Partenariat</option>
                                <option value="autre">Autre demande</option>
                            </select>
                        </div>
                        <div>
                            <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                                Priorité
                            </label>
                            <select id="priority" name="priority"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="normal">Normale</option>
                                <option value="low">Faible</option>
                                <option value="high">Élevée</option>
                                <option value="urgent">Urgente</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                            Sujet *
                        </label>
                        <input type="text" id="subject" name="subject" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                               placeholder="Résumez votre demande en quelques mots">
                    </div>

                    <div class="mb-6">
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                            Message *
                        </label>
                        <textarea id="message" name="message" rows="6" required
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                  placeholder="Décrivez votre demande en détail (minimum 10 caractères)"></textarea>
                        <div class="text-sm text-gray-500 mt-1">
                            <span id="charCount">0</span>/2000 caractères
                        </div>
                    </div>

                    <!-- Messages d'état -->
                    <div id="alertContainer" class="mb-6 hidden"></div>

                    <button type="submit" id="submitBtn"
                            class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-6 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        <span id="submitText">📤 Envoyer le message</span>
                        <span id="loadingText" class="hidden">⏳ Envoi en cours...</span>
                    </button>
                </form>
            </div>

            <!-- Informations de contact -->
            <div class="space-y-8">
                <!-- Coordonnées -->
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <h3 class="text-xl font-bold text-green-700 mb-6">
                        📍 Nos coordonnées
                    </h3>
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <div class="text-orange-500 text-xl">🏢</div>
                            <div>
                                <h4 class="font-medium text-gray-900">Adresse</h4>
                                <p class="text-gray-600">Avenue de la ferme 123<br>1000 Bruxelles, Belgique</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="text-orange-500 text-xl">📞</div>
                            <div>
                                <h4 class="font-medium text-gray-900">Téléphone</h4>
                                <p class="text-gray-600">+32 2 123 45 67</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="text-orange-500 text-xl">✉️</div>
                            <div>
                                <h4 class="font-medium text-gray-900">Email</h4>
                                <p class="text-gray-600">s.mef2703@gmail.com</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Horaires -->
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <h3 class="text-xl font-bold text-green-700 mb-6">
                        🕒 Horaires d'ouverture
                    </h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Lundi - Vendredi</span>
                            <span class="font-medium">8h00 - 18h00</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Samedi</span>
                            <span class="font-medium">9h00 - 12h00</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Dimanche</span>
                            <span class="text-red-500">Fermé</span>
                        </div>
                    </div>
                </div>

                <!-- Temps de réponse -->
                <div class="bg-gradient-to-r from-green-500 to-orange-500 rounded-xl shadow-lg p-8 text-white">
                    <h3 class="text-xl font-bold mb-4">
                        ⚡ Temps de réponse
                    </h3>
                    <p class="mb-4">
                        Nous nous engageons à vous répondre dans les <strong>24 à 48 heures</strong> suivant la réception de votre message.
                    </p>
                    <p class="text-sm opacity-90">
                        Pour les demandes urgentes, n'hésitez pas à nous appeler directement.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contactForm');
    const messageTextarea = document.getElementById('message');
    const charCount = document.getElementById('charCount');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const loadingText = document.getElementById('loadingText');
    const alertContainer = document.getElementById('alertContainer');

    // Compteur de caractères
    messageTextarea.addEventListener('input', function() {
        const count = this.value.length;
        charCount.textContent = count;
        
        if (count > 2000) {
            charCount.classList.add('text-red-500');
            this.value = this.value.substring(0, 2000);
        } else {
            charCount.classList.remove('text-red-500');
        }
    });

    // Soumission du formulaire
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Désactiver le bouton et afficher loading
        submitBtn.disabled = true;
        submitText.classList.add('hidden');
        loadingText.classList.remove('hidden');
        
        // Collecter les données
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());
        
        try {
            const response = await fetch('/api/contact', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (result.success) {
                // Stocker les données de confirmation dans localStorage
                localStorage.setItem('contactConfirmation', JSON.stringify({
                    reference: result.data.reference,
                    estimatedResponseTime: result.data.estimated_response_time,
                    submittedAt: new Date().toISOString()
                }));
                
                // Rediriger vers la page de confirmation
                window.location.href = '/contact/confirmation';
            } else {
                showAlert('error', '❌ Erreur', result.message || 'Une erreur est survenue');
            }
        } catch (error) {
            showAlert('error', '❌ Erreur de connexion', 'Impossible d\'envoyer le message. Veuillez réessayer.');
        } finally {
            // Réactiver le bouton
            submitBtn.disabled = false;
            submitText.classList.remove('hidden');
            loadingText.classList.add('hidden');
        }
    });

    function showAlert(type, title, message) {
        const bgColor = type === 'success' ? 'bg-green-100 border-green-500 text-green-700' : 'bg-red-100 border-red-500 text-red-700';
        
        alertContainer.innerHTML = `
            <div class="border-l-4 p-4 ${bgColor}">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <div class="text-lg">${type === 'success' ? '✅' : '❌'}</div>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium">${title}</h3>
                        <p class="mt-1 text-sm">${message}</p>
                    </div>
                </div>
            </div>
        `;
        
        alertContainer.classList.remove('hidden');
        
        // Auto-hide après 10 secondes pour les messages de succès
        if (type === 'success') {
            setTimeout(() => {
                alertContainer.classList.add('hidden');
            }, 10000);
        }
    }
});
</script>
@endsection
