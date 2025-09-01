@extends('layouts.app')

@section('title', __('app.payment_cancel.title'))

@section('content')
<div class="min-h-screen bg-gradient-to-br from-red-50 to-orange-100 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header d'annulation -->
        <div class="text-center mb-8">
            <div class="mx-auto w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mb-6">
                <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-2">😔 {{ __('app.payment_cancel.payment_cancelled') }}</h1>
            <p class="text-lg text-gray-600">{{ __('app.payment_cancel.payment_not_completed') }}</p>
        </div>

        <!-- Carte principale -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- En-tête de la carte -->
            <div class="bg-gradient-to-r from-red-600 to-orange-600 px-6 py-8 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold">{{ __('app.payment_cancel.order_number', ['number' => $orderLocation->order_number]) }}</h2>
                        <p class="text-red-100 mt-1">{{ __('app.payment_cancel.cancelled_on', ['date' => now()->format('d/m/Y à H:i')]) }}</p>
                    </div>
                    <div class="text-right">
                        <div class="text-3xl font-bold">{{ number_format($orderLocation->total_amount, 2) }}€</div>
                        <div class="text-red-100">{{ __('app.payment_cancel.amount_not_charged') }}</div>
                    </div>
                </div>
            </div>

            <!-- Contenu de la carte -->
            <div class="p-6">
                <!-- Statut -->
                <div class="flex items-center justify-center mb-6">
                    <span class="bg-red-100 text-red-800 px-4 py-2 rounded-full text-sm font-medium">
                        ❌ {{ __('app.payment_cancel.payment_cancelled_status') }}
                    </span>
                </div>

                <!-- Message d'information -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">
                                {{ __('app.payment_cancel.what_happened_title') }}
                            </h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>{{ __('app.payment_cancel.what_happened_description') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informations de la location -->
                <div class="grid md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">📅 {{ __('app.payment_cancel.rental_period_title') }}</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-gray-600">{{ __('app.payment_cancel.start_label') }}</span>
                                <span class="font-medium">{{ \Carbon\Carbon::parse($orderLocation->start_date)->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-gray-600">{{ __('app.payment_cancel.end_label') }}</span>
                                <span class="font-medium">{{ \Carbon\Carbon::parse($orderLocation->end_date)->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex items-center justify-between border-t pt-2">
                                <span class="text-gray-600">{{ __('app.payment_cancel.duration_label') }}</span>
                                <span class="font-medium">{{ \Carbon\Carbon::parse($orderLocation->start_date)->diffInDays(\Carbon\Carbon::parse($orderLocation->end_date)) + 1 }} {{ __('app.checkout_rental.rental_days') }}</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">💰 {{ __('app.payment_cancel.amount_details_title') }}</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-gray-600">{{ __("app.ecommerce.subtotal") }}</span>
                                <span class="font-medium">{{ number_format($orderLocation->subtotal_amount, 2) }}€</span>
                            </div>
                            @if($orderLocation->delivery_fee > 0)
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-gray-600">{{ __("app.ecommerce.delivery") }}</span>
                                <span class="font-medium">{{ number_format($orderLocation->delivery_fee, 2) }}€</span>
                            </div>
                            @endif
                            <div class="flex items-center justify-between border-t pt-2">
                                <span class="text-gray-600 font-medium">{{ __("app.ecommerce.total") }}</span>
                                <span class="font-bold text-red-600">{{ number_format($orderLocation->total_amount, 2) }}€</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Articles de la commande -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">📦 {{ __('app.payment_cancel.order_items_title') }}</h3>
                    <div class="space-y-3">
                        @foreach($orderLocation->items as $item)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                @if($item->product && $item->product->main_image)
                                    <img src="{{ asset('storage/' . $item->product->main_image) }}" 
                                         alt="{{ $item->product_name }}" 
                                         class="w-12 h-12 object-cover rounded">
                                @else
                                    <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
                                        <span class="text-gray-400 text-xs">📦</span>
                                    </div>
                                @endif
                                <div>
                                    <div class="font-medium text-gray-900">{{ $item->product_name }}</div>
                                    <div class="text-sm text-gray-600">{{ __('app.payment_cancel.quantity_text') }} {{ $item->quantity }}</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-medium">{{ number_format($item->unit_price, 2) }}{{ __('app.payment_cancel.per_day_text') }}</div>
                                <div class="text-sm text-gray-600">{{ number_format($item->total_price, 2) }}{{ __('app.payment_cancel.total_text') }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Options disponibles -->
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h3 class="text-lg font-semibold text-blue-900 mb-2">🔄 {{ __('app.payment_cancel.available_options_title') }}</h3>
                    <ul class="text-blue-800 space-y-1">
                        <li>💳 {{ __('app.payment_cancel.option_retry_payment') }}</li>
                        <li>📞 {{ __('app.payment_cancel.option_contact_support') }}</li>
                        <li>📧 {{ __('app.payment_cancel.option_pay_later') }}</li>
                        <li>🛒 {{ __('app.payment_cancel.option_modify_order') }}</li>
                    </ul>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row justify-center space-y-3 sm:space-y-0 sm:space-x-4">
                    <a href="{{ route('payment.stripe-rental', $orderLocation) }}" 
                       class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                        💳 {{ __('app.payment_cancel.retry_payment_button') }}
                    </a>
                    <a href="{{ route('rental-orders.show', $orderLocation) }}" 
                       class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                        📋 {{ __('app.payment_cancel.view_order_button') }}
                    </a>
                    <a href="{{ route('cart-location.index') }}" 
                       class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                        🛒 {{ __('app.payment_cancel.back_to_cart_button') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Aide et contact -->
        <div class="text-center mt-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">💬 {{ __('app.payment_cancel.need_help_title') }}</h3>
                <p class="text-gray-600 mb-4">
                    {{ __('app.payment_cancel.need_help_description') }}
                </p>
                <div class="flex flex-col sm:flex-row justify-center space-y-2 sm:space-y-0 sm:space-x-6">
                    <a href="tel:+33123456789" class="text-green-600 hover:text-green-700 font-medium">
                        📞 {{ __('app.payment_cancel.phone_number') }}
                    </a>
                    <a href="mailto:support@farmshop.com" class="text-green-600 hover:text-green-700 font-medium">
                        📧 {{ __('app.payment_cancel.email_support') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
