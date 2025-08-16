@extends('layouts.app')

@section('title', __('app.contact.page_title') . ' - FarmShop')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-orange-100 via-green-50 to-orange-100 py-12">
    <div class="max-w-4xl mx-auto px-6">
        <!-- En-tête -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-green-800 mb-4">
                📞 {{ __('app.contact.page_title') }}
            </h1>
            <p class="text-xl text-orange-600 max-w-2xl mx-auto">
                {{ __('app.contact.page_subtitle') }}
            </p>
        </div>

        <div class="grid lg:grid-cols-2 gap-12">
            <!-- Formulaire de contact -->
            <div class="bg-white rounded-xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-green-700 mb-6">
                    ✉️ {{ __('app.contact.form_title') }}
                </h2>
                
                <form id="contactForm" action="/api/contact" method="POST">
                    @csrf
                    
                    <!-- Informations personnelles -->
                    <div class="grid md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('app.contact.full_name_required') }}
                            </label>
                            <input type="text" id="name" name="name" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('app.contact.email_required') }}
                            </label>
                            <input type="email" id="email" name="email" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('app.contact.phone') }}
                        </label>
                        <input type="tel" id="phone" name="phone"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <!-- Motif et priorité -->
                    <div class="grid md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('app.contact.reason_required') }}
                            </label>
                            <select id="reason" name="reason" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="">{{ __('app.contact.select_reason') }}</option>
                                <option value="mon_profil">{{ __('app.contact.reason_profile') }}</option>
                                <option value="mes_achats">{{ __('app.contact.reason_purchases') }}</option>
                                <option value="mes_locations">{{ __('app.contact.reason_rentals') }}</option>
                                <option value="mes_donnees">{{ __('app.contact.reason_data') }}</option>
                                <option value="support_technique">{{ __('app.contact.reason_technical') }}</option>
                                <option value="partenariat">{{ __('app.contact.reason_partnership') }}</option>
                                <option value="autre">{{ __('app.contact.reason_other') }}</option>
                            </select>
                        </div>
                        <div>
                            <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('app.contact.priority') }}
                            </label>
                            <select id="priority" name="priority"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="normal">{{ __('app.contact.priority_normal') }}</option>
                                <option value="low">{{ __('app.contact.priority_low') }}</option>
                                <option value="high">{{ __('app.contact.priority_high') }}</option>
                                <option value="urgent">{{ __('app.contact.priority_urgent') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('app.contact.subject_required') }}
                        </label>
                        <input type="text" id="subject" name="subject" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                               placeholder="{{ __('app.contact.subject_placeholder') }}">
                    </div>

                    <div class="mb-6">
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('app.contact.message_required') }}
                        </label>
                        <textarea id="message" name="message" rows="6" required
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                  placeholder="{{ __('app.contact.message_placeholder') }}"></textarea>
                        <div class="text-sm text-gray-500 mt-1">
                            <span id="charCount">0</span>/2000 {{ __('app.contact.character_count') }}
                        </div>
                    </div>

                    <!-- Messages d'état -->
                    <div id="alertContainer" class="mb-6 hidden"></div>

                    <button type="submit" id="submitBtn"
                            class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-6 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        <span id="submitText">📤 {{ __('app.contact.send_message') }}</span>
                        <span id="loadingText" class="hidden">⏳ {{ __('app.contact.sending') }}</span>
                    </button>
                </form>
            </div>

            <!-- Informations de contact -->
            <div class="space-y-8">
                <!-- Coordonnées -->
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <h3 class="text-xl font-bold text-green-700 mb-6">
                        📍 {{ __('app.contact.coordinates_title') }}
                    </h3>
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <div class="text-orange-500 text-xl">🏢</div>
                            <div>
                                <h4 class="font-medium text-gray-900">{{ __('app.contact.address') }}</h4>
                                <p class="text-gray-600">{!! __('app.contact.address_value') !!}</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="text-orange-500 text-xl">📞</div>
                            <div>
                                <h4 class="font-medium text-gray-900">{{ __('app.contact.phone') }}</h4>
                                <p class="text-gray-600">{{ __('app.contact.phone_value') }}</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="text-orange-500 text-xl">✉️</div>
                            <div>
                                <h4 class="font-medium text-gray-900">{{ __('app.contact.email') }}</h4>
                                <p class="text-gray-600">{{ __('app.contact.email_value') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Horaires -->
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <h3 class="text-xl font-bold text-green-700 mb-6">
                        🕒 {{ __('app.contact.opening_hours_title') }}
                    </h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ __('app.contact.monday_friday') }}</span>
                            <span class="font-medium">{{ __('app.contact.hours_weekdays') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ __('app.contact.saturday') }}</span>
                            <span class="font-medium">{{ __('app.contact.hours_saturday') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ __('app.contact.sunday') }}</span>
                            <span class="text-red-500">{{ __('app.contact.closed') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Temps de réponse -->
                <div class="bg-gradient-to-r from-green-500 to-orange-500 rounded-xl shadow-lg p-8 text-white">
                    <h3 class="text-xl font-bold mb-4">
                        ⚡ {{ __('app.contact.response_time_title') }}
                    </h3>
                    <p class="mb-4">
                        {!! __('app.contact.response_time_text') !!}
                    </p>
                    <p class="text-sm opacity-90">
                        {{ __('app.contact.urgent_note') }}
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
                showAlert('error', '❌ {{ __('app.contact.error_title') }}', result.message || '{{ __('app.contact.form_error') }}');
            }
        } catch (error) {
            showAlert('error', '❌ {{ __('app.contact.connection_error_title') }}', '{{ __('app.contact.connection_error') }}');
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
