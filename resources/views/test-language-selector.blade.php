<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Sélecteur de Langue Alpine.js</title>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">🌍 Test du Sélecteur de Langue Alpine.js</h1>
        
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">État actuel</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <h3 class="font-medium text-blue-800">Locale actuelle</h3>
                    <p class="text-blue-600 text-lg">{{ app()->getLocale() }}</p>
                </div>
                <div class="bg-green-50 p-4 rounded-lg">
                    <h3 class="font-medium text-green-800">Configuration</h3>
                    <p class="text-green-600">{{ get_current_locale_config()['name'] }}</p>
                    <p class="text-2xl">{{ get_current_locale_config()['flag'] }}</p>
                </div>
                <div class="bg-orange-50 p-4 rounded-lg">
                    <h3 class="font-medium text-orange-800">Session</h3>
                    <p class="text-orange-600">{{ session('locale', 'Non définie') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Sélecteur de langue</h2>
            <div class="flex justify-center">
                @include('components.language-selector')
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Test des traductions</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="font-medium text-gray-700 mb-2">Navigation</h3>
                    <ul class="space-y-1 text-sm">
                        <li><strong>Accueil:</strong> {{ __('app.nav.home') }}</li>
                        <li><strong>Produits:</strong> {{ __('app.nav.products') }}</li>
                        <li><strong>Locations:</strong> {{ __('app.nav.rentals') }}</li>
                        <li><strong>Contact:</strong> {{ __('app.nav.contact') }}</li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-medium text-gray-700 mb-2">Welcome</h3>
                    <ul class="space-y-1 text-sm">
                        <li><strong>Titre:</strong> {{ __('app.welcome.hero_title') }}</li>
                        <li><strong>Bouton Shop:</strong> {{ __('app.welcome.shop_now') }}</li>
                        <li><strong>Bouton Rent:</strong> {{ __('app.welcome.rent_equipment') }}</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="mt-8 text-center">
            <p class="text-gray-600">
                Cliquez sur le sélecteur de langue ci-dessus pour tester le changement avec Alpine.js + AJAX
            </p>
            <p class="text-sm text-gray-500 mt-2">
                ✅ Alpine.js &nbsp; ✅ AJAX &nbsp; ✅ Laravel &nbsp; ✅ Blade
            </p>
        </div>
    </div>

    <!-- Console de débogage Alpine.js -->
    <div x-data="{
        showDebug: false,
        logs: []
    }" class="fixed bottom-4 right-4">
        <button @click="showDebug = !showDebug" 
                class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-blue-700">
            🐛 Debug
        </button>
        
        <div x-show="showDebug" 
             x-transition
             class="absolute bottom-12 right-0 w-80 bg-black text-green-400 p-4 rounded-lg shadow-xl font-mono text-xs">
            <div class="mb-2">
                <strong>Alpine.js Debug Console</strong>
                <button @click="showDebug = false" class="float-right text-red-400">✕</button>
            </div>
            <div class="border-t border-gray-600 pt-2">
                <p>Locale: {{ app()->getLocale() }}</p>
                <p>Available: {{ implode(', ', array_keys(get_all_locales())) }}</p>
                <p>CSRF: {{ csrf_token() }}</p>
            </div>
        </div>
    </div>
</body>
</html>
