@extends('layouts.admin')

@section('title', __('settings.page_title') . ' - Dashboard Admin')
@section('page-title', __('settings.page_title'))

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-xl font-semibold text-gray-900">{{ __('settings.site_settings') }}</h2>
        <p class="text-gray-600">{{ __('settings.page_description') }}</p>
    </div>

    <!-- Paramètres généraux -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">{{ __('settings.general_information.title') }}</h3>
        </div>
        <div class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">{{ __('settings.general_information.site_name') }}</label>
                <input type="text" value="FarmShop" placeholder="{{ __('settings.general_information.site_name_placeholder') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">{{ __('settings.general_information.description') }}</label>
                <textarea rows="3" placeholder="{{ __('settings.general_information.description_placeholder') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ __('settings.general_information.description_placeholder') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">{{ __('settings.general_information.contact_email') }}</label>
                <input type="email" value="contact@farmshop.be" placeholder="{{ __('settings.general_information.contact_email_placeholder') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>
    </div>

    <!-- Paramètres de paiement -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">{{ __('settings.payment_configuration.title') }}</h3>
        </div>
        <div class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">{{ __('settings.payment_configuration.public_key') }}</label>
                <input type="text" placeholder="{{ __('settings.payment_configuration.public_key_placeholder') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">{{ __('settings.payment_configuration.secret_key') }}</label>
                <input type="password" placeholder="{{ __('settings.payment_configuration.secret_key_placeholder') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>
    </div>

    <!-- Boutons d'action -->
    <div class="flex justify-end space-x-3">
        <button class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
            {{ __('settings.actions.cancel') }}
        </button>
        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
            {{ __('settings.actions.save') }}
        </button>
    </div>
</div>
@endsection
