@extends('layouts.app')

@section('title', 'Compte supprimé avec succès')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-lg w-full space-y-8">
        <div class="text-center">
            <div class="mx-auto h-20 w-20 bg-green-100 rounded-full flex items-center justify-center mb-6">
                <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h2 class="text-3xl font-extrabold text-gray-900 mb-2">Compte supprimé avec succès</h2>
            <p class="text-sm text-gray-600">Votre compte a été définitivement supprimé de nos serveurs</p>
        </div>

        <div class="bg-white shadow-2xl rounded-2xl p-8 border border-gray-100 space-y-6">
            <!-- Confirmation de suppression -->
            <div class="text-center">
                <div class="mx-auto h-12 w-12 bg-green-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Suppression terminée</h3>
                <p class="text-gray-600">Toutes vos données ont été définitivement supprimées de nos systèmes</p>
            </div>

            <!-- Téléchargement GDPR -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-semibold text-blue-800 mb-2">📁 Archive GDPR téléchargée</h4>
                        <p class="text-sm text-blue-700 mb-3">Conformément au RGPD, nous avons généré une archive contenant toutes vos données.</p>
                        
                        <div class="text-xs text-blue-600 space-y-1">
                            <div class="flex items-center">
                                <span class="w-1.5 h-1.5 bg-blue-400 rounded-full mr-2"></span>
                                Informations de profil (PDF)
                            </div>
                            <div class="flex items-center">
                                <span class="w-1.5 h-1.5 bg-blue-400 rounded-full mr-2"></span>
                                Historique des commandes (PDF)
                            </div>
                            <div class="flex items-center">
                                <span class="w-1.5 h-1.5 bg-blue-400 rounded-full mr-2"></span>
                                Historique des locations (PDF)
                            </div>
                            <div class="flex items-center">
                                <span class="w-1.5 h-1.5 bg-blue-400 rounded-full mr-2"></span>
                                Messages et communications (PDF)
                            </div>
                            <div class="flex items-center">
                                <span class="w-1.5 h-1.5 bg-blue-400 rounded-full mr-2"></span>
                                Préférences de navigation (PDF)
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Message de remerciement -->
            <div class="text-center bg-gray-50 rounded-xl p-6">
                <div class="mx-auto h-10 w-10 bg-yellow-100 rounded-full flex items-center justify-center mb-3">
                    <svg class="h-5 w-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <h4 class="text-lg font-semibold text-gray-900 mb-2">Merci d'avoir utilisé FarmShop</h4>
                <p class="text-sm text-gray-600">Nous espérons vous revoir bientôt. Au revoir !</p>
            </div>

            <!-- Actions -->
            <div class="pt-4">
                <a href="{{ route('home') }}" 
                   class="w-full inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-gradient-to-r from-green-600 to-emerald-700 hover:from-green-700 hover:to-emerald-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Retour à l'accueil
                </a>
            </div>

            <!-- Auto-déconnexion -->
            <div class="text-center bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                <p class="text-xs text-yellow-800">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Déconnexion automatique dans <span id="countdown">5</span> secondes...
                </p>
            </div>
        </div>
    </div>
</div>

@if(isset($zipFileName))
<script>
// Déclencher le téléchargement automatique du ZIP
document.addEventListener('DOMContentLoaded', function() {
    const link = document.createElement('a');
    link.href = '{{ asset('storage/gdpr/') }}/' + '{{ $zipFileName }}';
    link.download = '{{ $zipFileName }}';
    link.style.display = 'none';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
});
</script>
@endif

<script>
// Countdown et auto-déconnexion après 5 secondes
let countdown = 5;
const countdownElement = document.getElementById('countdown');

const timer = setInterval(function() {
    countdown--;
    if (countdownElement) {
        countdownElement.textContent = countdown;
    }
    
    if (countdown <= 0) {
        clearInterval(timer);
        // Créer un formulaire POST pour la déconnexion
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route('logout') }}";
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = "{{ csrf_token() }}";
        
        form.appendChild(csrfToken);
        document.body.appendChild(form);
        form.submit();
    }
}, 1000);

// Animation d'entrée
document.addEventListener('DOMContentLoaded', function() {
    const elements = document.querySelectorAll('.max-w-lg > *');
    elements.forEach((el, index) => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        setTimeout(() => {
            el.style.transition = 'all 0.6s ease-out';
            el.style.opacity = '1';
            el.style.transform = 'translateY(0)';
        }, index * 150);
    });
});
</script>
@endsection