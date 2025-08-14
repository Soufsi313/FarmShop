@extends('layouts.app')

@section('title', 'Suppression de compte demandée')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-orange-50 via-red-50 to-pink-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <div class="mx-auto h-16 w-16 bg-orange-100 rounded-full flex items-center justify-center mb-6">
                <svg class="h-8 w-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <h2 class="text-3xl font-extrabold text-gray-900 mb-2">Email de confirmation envoyé</h2>
            <p class="text-sm text-gray-600">Dernière étape avant la suppression de votre compte</p>
        </div>

        <div class="bg-white shadow-2xl rounded-2xl p-8 border border-gray-100">
            <!-- Email envoyé -->
            <div class="text-center mb-6">
                <div class="mx-auto h-12 w-12 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Vérifiez votre boîte email</h3>
                <p class="text-sm text-gray-600 mb-1">Un lien de confirmation a été envoyé à :</p>
                <p class="text-sm font-medium text-blue-600 bg-blue-50 px-3 py-1 rounded-lg inline-block">
                    {{ auth()->user()->email }}
                </p>
            </div>

            <!-- Instructions -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-4 mb-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium text-blue-800">Instructions</h4>
                        <p class="text-sm text-blue-700 mt-1">Cliquez sur le lien dans l'email pour finaliser la suppression de votre compte.</p>
                    </div>
                </div>
            </div>

            <!-- Avertissements importants -->
            <div class="bg-gradient-to-r from-red-50 to-orange-50 border border-red-200 rounded-xl p-4 mb-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium text-red-800 mb-2">⚠️ Attention</h4>
                        <ul class="text-sm text-red-700 space-y-1">
                            <li class="flex items-center">
                                <span class="w-1.5 h-1.5 bg-red-400 rounded-full mr-2"></span>
                                Le lien expire dans <strong>60 minutes</strong>
                            </li>
                            <li class="flex items-center">
                                <span class="w-1.5 h-1.5 bg-red-400 rounded-full mr-2"></span>
                                Cette action est <strong>irréversible</strong>
                            </li>
                            <li class="flex items-center">
                                <span class="w-1.5 h-1.5 bg-red-400 rounded-full mr-2"></span>
                                Toutes vos données seront supprimées
                            </li>
                            <li class="flex items-center">
                                <span class="w-1.5 h-1.5 bg-green-400 rounded-full mr-2"></span>
                                Vous recevrez vos données (GDPR)
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('users.profile') }}" 
                   class="flex-1 inline-flex items-center justify-center px-4 py-3 border border-gray-300 text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Retour au profil
                </a>
                
                <a href="{{ route('home') }}" 
                   class="flex-1 inline-flex items-center justify-center px-4 py-3 border border-transparent text-sm font-medium rounded-xl text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-lg hover:shadow-xl">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Accueil
                </a>
            </div>
        </div>

        <!-- Aide -->
        <div class="text-center">
            <p class="text-xs text-gray-500">
                Vous n'avez pas reçu l'email ? Vérifiez votre dossier spam ou 
                <a href="{{ route('contact') }}" class="text-blue-600 hover:text-blue-800 underline">contactez-nous</a>
            </p>
        </div>
    </div>
</div>

<!-- Styles additionnels pour l'animation -->
<style>
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fadeInUp {
    animation: fadeInUp 0.6s ease-out;
}
</style>

<script>
// Animation d'entrée
document.addEventListener('DOMContentLoaded', function() {
    const elements = document.querySelectorAll('.max-w-md > *');
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