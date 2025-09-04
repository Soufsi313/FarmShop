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

        <!-- Section Google Maps -->
        <div class="mt-16">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-green-800 mb-4">
                    🗺️ {{ __('app.contact.find_us_title') }}
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    {{ __('app.contact.location_description') }}
                </p>
            </div>

            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
                <!-- Enhanced Map Container -->
                <div class="relative">
                    <!-- Map Header -->
                    <div class="bg-gradient-to-r from-green-600 to-orange-500 p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                                    <span class="text-2xl">📍</span>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold">{{ __('app.contact.farmshop_brussels') }}</h3>
                                    <p class="text-white/90">{{ __('app.contact.brussels_location') }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-white/80">{{ __('app.contact.distance_city_center') }}</p>
                                <p class="text-lg font-bold">{{ __('app.contact.walking_distance') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Google Maps Container -->
                    <div class="relative h-96 lg:h-[500px] bg-gray-100">
                        <iframe 
                            id="googleMap"
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2519.2775186965783!2d4.351710315735!3d50.84656007952755!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47c3c47fb2381c1d%3A0xa82a3d1b0e3a9c4!2s1000%20Bruxelles%2C%20Belgique!5e0!3m2!1sfr!2sbe!4v1694175600000!5m2!1sfr!2sbe"
                            width="100%" 
                            height="100%" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade"
                            class="w-full h-full">
                        </iframe>

                        <!-- Map Overlay with Info -->
                        <div class="absolute top-4 left-4 bg-white/95 backdrop-blur-sm rounded-xl shadow-xl p-4 max-w-xs">
                            <div class="flex items-start space-x-3">
                                <div class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center flex-shrink-0">
                                    <span class="text-white text-lg">🏪</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800">{{ __('app.contact.farmshop_name') }}</h4>
                                    <p class="text-sm text-gray-600">{{ __('app.contact.city_center_brussels') }}</p>
                                    <p class="text-xs text-green-600 font-medium mt-1">📍 {{ __('app.contact.approximate_position') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions Overlay -->
                        <div class="absolute bottom-4 right-4 flex space-x-2">
                            <button onclick="openInGoogleMaps()" class="bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-full shadow-lg transition-all duration-200 hover:scale-110">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            <button onclick="getDirections()" class="bg-green-600 hover:bg-green-700 text-white p-3 rounded-full shadow-lg transition-all duration-200 hover:scale-110">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Map Info Footer -->
                    <div class="bg-gray-50 px-6 py-4">
                        <div class="grid md:grid-cols-3 gap-4 text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <span class="text-blue-600 text-lg">🚇</span>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">{{ __('app.contact.metro_transport') }}</p>
                                    <p class="text-xs text-gray-600">{{ __('app.contact.central_station') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-center space-x-2">
                                <span class="text-green-600 text-lg">🚌</span>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">{{ __('app.contact.bus_transport') }}</p>
                                    <p class="text-xs text-gray-600">{{ __('app.contact.bus_lines') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-center space-x-2">
                                <span class="text-orange-600 text-lg">🚗</span>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">{{ __('app.contact.parking_info') }}</p>
                                    <p class="text-xs text-gray-600">{{ __('app.contact.parking_distance') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Map JavaScript Functions -->
<script>
function openInGoogleMaps() {
    const url = 'https://www.google.com/maps/search/1000+Bruxelles,+Belgique/@50.8465600,4.3517103,17z';
    window.open(url, '_blank');
}

function getDirections() {
    const url = 'https://www.google.com/maps/dir//1000+Bruxelles,+Belgique/@50.8465600,4.3517103,17z';
    window.open(url, '_blank');
}

// Map loading animation
document.addEventListener('DOMContentLoaded', function() {
    const mapFrame = document.getElementById('googleMap');
    
    mapFrame.addEventListener('load', function() {
        // Add a subtle fade-in effect when map loads
        this.style.opacity = '0';
        this.style.transition = 'opacity 0.5s ease-in-out';
        setTimeout(() => {
            this.style.opacity = '1';
        }, 100);
    });
});
</script>

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
