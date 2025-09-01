@extends('layouts.app')

@section('title', __('app.rental_confirmation.page_title') . ' - FarmShop')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-emerald-100 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header de succès -->
        <div class="text-center mb-8">
            <div class="mx-auto w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mb-6">
                <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ __('app.rental_confirmation.payment_successful') }}</h1>
            <p class="text-lg text-gray-600">{{ __('app.rental_confirmation.rental_confirmed') }}</p>
        </div>

        <!-- Carte principale -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- En-tête de la carte -->
            <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-8 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold">{{ __('app.rental_confirmation.order_number', ['number' => $orderLocation->order_number]) }}</h2>
                        <p class="text-green-100 mt-1">{{ __('app.rental_confirmation.paid_on', ['date' => now()->format('d/m/Y à H:i')]) }}</p>
                    </div>
                    <div class="text-right">
                        <div class="text-3xl font-bold">{{ number_format($orderLocation->total_amount, 2) }}€</div>
                        <div class="text-green-100">{{ __('app.rental_confirmation.total_amount') }}</div>
                    </div>
                </div>
            </div>

            <!-- Contenu de la carte -->
            <div class="p-6">
                <!-- Statut -->
                <div class="flex items-center justify-center mb-6">
                    <span class="bg-green-100 text-green-800 px-4 py-2 rounded-full text-sm font-medium">
                        {{ __('app.rental_confirmation.payment_confirmed') }}
                    </span>
                </div>

                <!-- Informations de la location -->
                <div class="grid md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">{{ __('app.rental_confirmation.rental_period') }}</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-gray-600">{{ __('app.rental_confirmation.start_date') }}</span>
                                <span class="font-medium">{{ \Carbon\Carbon::parse($orderLocation->start_date)->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-gray-600">{{ __('app.rental_confirmation.end_date') }}</span>
                                <span class="font-medium">{{ \Carbon\Carbon::parse($orderLocation->end_date)->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex items-center justify-between border-t pt-2">
                                <span class="text-gray-600">{{ __('app.rental_confirmation.duration') }}</span>
                                <span class="font-medium">{{ \Carbon\Carbon::parse($orderLocation->start_date)->diffInDays(\Carbon\Carbon::parse($orderLocation->end_date)) + 1 }} {{ __('app.rental_confirmation.days') }}</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">{{ __('app.rental_confirmation.payment_details') }}</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-gray-600">{{ __('app.rental_confirmation.rental_subtotal') }}</span>
                                <span class="font-medium">{{ number_format($orderLocation->subtotal ?? 0, 2) }}€</span>
                            </div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-gray-600">{{ __('app.rental_confirmation.vat_rate') }}</span>
                                <span class="font-medium">{{ number_format($orderLocation->tax_amount ?? 0, 2) }}€</span>
                            </div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-gray-600">{{ __('app.rental_confirmation.deposit_amount') }}</span>
                                <span class="font-medium">{{ number_format($orderLocation->deposit_amount ?? 0, 2) }}€</span>
                            </div>
                            @if($orderLocation->delivery_fee > 0)
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-gray-600">{{ __('app.rental_confirmation.delivery_fee') }}</span>
                                <span class="font-medium">{{ number_format($orderLocation->delivery_fee, 2) }}€</span>
                            </div>
                            @endif
                            <div class="flex items-center justify-between border-t pt-2">
                                <span class="text-gray-600 font-medium">{{ __('app.rental_confirmation.total') }}</span>
                                <span class="font-bold text-green-600">{{ number_format($orderLocation->total_amount, 2) }}€</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Articles loués -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">{{ __('app.rental_confirmation.rented_items') }}</h3>
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
                                    <div class="text-sm text-gray-600">
                                        {{ __('app.rental_confirmation.quantity_label') }}: {{ $item->quantity }} | 
                                        {{ $item->rental_days }} {{ $item->rental_days > 1 ? __('app.rental_confirmation.days') : __('app.rental_confirmation.day') }}
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-medium">{{ number_format($item->daily_rate ?? 0, 2) }}€{{ __('app.rental_confirmation.per_day') }}</div>
                                <div class="text-sm text-gray-600">{{ number_format($item->subtotal ?? 0, 2) }}€ {{ __('app.rental_confirmation.total_price') }}</div>
                                <div class="text-xs text-gray-500">{{ __('app.rental_confirmation.deposit_label') }}: {{ number_format($item->total_deposit ?? 0, 2) }}€</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Prochaines étapes -->
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h3 class="text-lg font-semibold text-blue-900 mb-2">{{ __('app.rental_confirmation.next_steps_title') }}</h3>
                    <ul class="text-blue-800 space-y-1">
                        <li>{{ __('app.rental_confirmation.next_step_email') }}</li>
                        <li>{{ __('app.rental_confirmation.next_step_delivery', ['date' => \Carbon\Carbon::parse($orderLocation->start_date)->format('d/m/Y')]) }}</li>
                        <li>{{ __('app.rental_confirmation.next_step_follow') }}</li>
                        <li>{{ __('app.rental_confirmation.next_step_contact') }}</li>
                    </ul>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row justify-center space-y-3 sm:space-y-0 sm:space-x-4">
                    <a href="{{ route('rental-orders.show', $orderLocation) }}" 
                       class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                        {{ __('app.rental_confirmation.view_my_rental') }}
                    </a>
                    <a href="{{ route('rental-orders.index') }}" 
                       class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                        {{ __('app.rental_confirmation.my_rentals') }}
                    </a>
                    <a href="{{ route('home') }}" 
                       class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                        {{ __('app.rental_confirmation.back_home') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Informations de contact -->
        <div class="text-center mt-8">
            <p class="text-gray-600">
                {{ __('app.rental_confirmation.contact_question') }} 
                <a href="tel:+33123456789" class="text-green-600 hover:text-green-700 font-medium">01 23 45 67 89</a>
                {{ __('app.rental_confirmation.or_email') }} 
                <a href="mailto:support@farmshop.com" class="text-green-600 hover:text-green-700 font-medium">support@farmshop.com</a>
            </p>
        </div>
    </div>
</div>
@endsection
