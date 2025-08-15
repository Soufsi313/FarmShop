@extends('layouts.app')

@section('title', __('app.pages.test_title'))

@section('content')
<div class="container mx-auto px-6 py-12">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-farm-green-800 mb-4">
                🌍 {{ __('app.pages.test_title') }}
            </h1>
            <p class="text-xl text-gray-600">
                {{ __('app.pages.test_subtitle') }}
            </p>
        </div>

        <!-- Language Info -->
        <div class="bg-farm-orange-50 rounded-lg p-6 mb-8">
            <h2 class="text-2xl font-semibold text-farm-orange-800 mb-4">
                {{ __('app.pages.current_language') }}
            </h2>
            <div class="grid md:grid-cols-3 gap-4">
                <div class="text-center">
                    <div class="text-3xl mb-2">{{ get_current_locale_config()['flag'] }}</div>
                    <div class="font-semibold">{{ get_current_locale_config()['name'] }}</div>
                    <div class="text-sm text-gray-600">{{ app()->getLocale() }}</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl mb-2">🕐</div>
                    <div class="font-semibold">{{ __('app.time.today') }}</div>
                    <div class="text-sm text-gray-600">{{ now()->format('d/m/Y') }}</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl mb-2">✅</div>
                    <div class="font-semibold">{{ __('app.status.active') }}</div>
                    <div class="text-sm text-gray-600">{{ __('app.messages.operation_successful') }}</div>
                </div>
            </div>
        </div>

        <!-- Navigation Test -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">
                {{ __('app.nav.home') }} - {{ __('app.pages.navigation_test') }}
            </h3>
            <div class="grid md:grid-cols-4 gap-4">
                <a href="#" class="block p-4 bg-farm-green-50 rounded-lg text-center hover:bg-farm-green-100">
                    <div class="text-2xl mb-2">🏠</div>
                    <div>{{ __('app.nav.home') }}</div>
                </a>
                <a href="#" class="block p-4 bg-farm-green-50 rounded-lg text-center hover:bg-farm-green-100">
                    <div class="text-2xl mb-2">🛒</div>
                    <div>{{ __('app.nav.products') }}</div>
                </a>
                <a href="#" class="block p-4 bg-farm-green-50 rounded-lg text-center hover:bg-farm-green-100">
                    <div class="text-2xl mb-2">📅</div>
                    <div>{{ __('app.nav.rentals') }}</div>
                </a>
                <a href="#" class="block p-4 bg-farm-green-50 rounded-lg text-center hover:bg-farm-green-100">
                    <div class="text-2xl mb-2">📝</div>
                    <div>{{ __('app.nav.blog') }}</div>
                </a>
            </div>
        </div>

        <!-- Buttons Test -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">
                {{ __('app.pages.buttons_test') }}
            </h3>
            <div class="space-y-4">
                <div class="flex flex-wrap gap-3">
                    <button class="bg-farm-green-600 text-white px-4 py-2 rounded hover:bg-farm-green-700">
                        {{ __('app.buttons.add') }}
                    </button>
                    <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        {{ __('app.buttons.edit') }}
                    </button>
                    <button class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                        {{ __('app.buttons.delete') }}
                    </button>
                    <button class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                        {{ __('app.buttons.cancel') }}
                    </button>
                </div>
                <div class="flex flex-wrap gap-3">
                    <button class="bg-farm-orange-600 text-white px-4 py-2 rounded hover:bg-farm-orange-700">
                        {{ __('app.ecommerce.add_to_cart') }}
                    </button>
                    <button class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
                        {{ __('app.buttons.view_details') }}
                    </button>
                    <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                        {{ __('app.ecommerce.checkout') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- E-commerce Test -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">
                {{ __('app.pages.ecommerce_test') }}
            </h3>
            <div class="grid md:grid-cols-2 gap-6">
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span>{{ __('app.ecommerce.price') }}:</span>
                        <span class="font-semibold">€29.99</span>
                    </div>
                    <div class="flex justify-between">
                        <span>{{ __('app.ecommerce.quantity') }}:</span>
                        <span>3</span>
                    </div>
                    <div class="flex justify-between">
                        <span>{{ __('app.ecommerce.tax') }}:</span>
                        <span>€6.30</span>
                    </div>
                    <div class="flex justify-between font-bold text-lg border-t pt-2">
                        <span>{{ __('app.ecommerce.total') }}:</span>
                        <span>€96.27</span>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                        <span>{{ __('app.ecommerce.in_stock') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 bg-blue-500 rounded-full"></span>
                        <span>{{ __('app.ecommerce.free_shipping') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 bg-purple-500 rounded-full"></span>
                        <span>{{ __('app.ecommerce.warranty') }} 2 {{ __('app.time.years') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Test -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">
                {{ __('app.pages.status_test') }}
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center p-3 bg-yellow-50 rounded">
                    <div class="text-yellow-600 font-semibold">{{ __('app.status.pending') }}</div>
                </div>
                <div class="text-center p-3 bg-green-50 rounded">
                    <div class="text-green-600 font-semibold">{{ __('app.status.confirmed') }}</div>
                </div>
                <div class="text-center p-3 bg-blue-50 rounded">
                    <div class="text-blue-600 font-semibold">{{ __('app.status.shipped') }}</div>
                </div>
                <div class="text-center p-3 bg-purple-50 rounded">
                    <div class="text-purple-600 font-semibold">{{ __('app.status.delivered') }}</div>
                </div>
            </div>
        </div>

        <!-- Messages Test -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">
                {{ __('app.pages.messages_test') }}
            </h3>
            <div class="space-y-3">
                <div class="p-3 bg-green-50 border-l-4 border-green-500 text-green-700">
                    {{ __('app.messages.operation_successful') }}
                </div>
                <div class="p-3 bg-blue-50 border-l-4 border-blue-500 text-blue-700">
                    {{ __('app.messages.data_saved') }}
                </div>
                <div class="p-3 bg-yellow-50 border-l-4 border-yellow-500 text-yellow-700">
                    {{ __('app.messages.please_wait') }}
                </div>
                <div class="p-3 bg-red-50 border-l-4 border-red-500 text-red-700">
                    {{ __('app.messages.operation_error') }}
                </div>
            </div>
        </div>

        <!-- Language Switcher Test -->
        <div class="text-center mt-12">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">
                {{ __('app.pages.switch_language') }}
            </h3>
            <div class="flex justify-center space-x-4">
                <a href="{{ route('locale.change', 'fr') }}" 
                   class="flex items-center space-x-2 px-4 py-2 bg-farm-orange-100 rounded-lg hover:bg-farm-orange-200 {{ app()->getLocale() === 'fr' ? 'ring-2 ring-farm-orange-500' : '' }}">
                    <span class="text-xl">🇫🇷</span>
                    <span>Français</span>
                </a>
                <a href="{{ route('locale.change', 'en') }}" 
                   class="flex items-center space-x-2 px-4 py-2 bg-farm-orange-100 rounded-lg hover:bg-farm-orange-200 {{ app()->getLocale() === 'en' ? 'ring-2 ring-farm-orange-500' : '' }}">
                    <span class="text-xl">🇬🇧</span>
                    <span>English</span>
                </a>
                <a href="{{ route('locale.change', 'nl') }}" 
                   class="flex items-center space-x-2 px-4 py-2 bg-farm-orange-100 rounded-lg hover:bg-farm-orange-200 {{ app()->getLocale() === 'nl' ? 'ring-2 ring-farm-orange-500' : '' }}">
                    <span class="text-xl">🇳🇱</span>
                    <span>Nederlands</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
