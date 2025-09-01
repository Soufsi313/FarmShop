@extends('layouts.app')

@section('title', __('app.checkout_rental.title') . ' - FarmShop')

@section('content')
<div class="min-h-screen bg-gray-50 py-8" x-data="checkoutRentalData()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- En-tête -->
        <div class="mb-8">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-9 9a1 1 0 101.414 1.414L9 4.414V17a1 1 0 102 0V4.414l7.293 7.293a1 1 0 001.414-1.414l-9-9z"></path>
                            </svg>
                            {{ __('app.checkout_rental.breadcrumb_home') }}
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 111.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <a href="{{ route('cart-location.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">
                                {{ __('app.checkout_rental.breadcrumb_cart') }}
                            </a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 111.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ __('app.checkout_rental.breadcrumb_checkout') }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
            
            <h1 class="text-3xl font-bold text-gray-900 mb-2 mt-4">{{ __('app.checkout_rental.page_title') }}</h1>
            <p class="text-gray-600">{{ __('app.checkout_rental.page_description') }}</p>
        </div>

        @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-800">{{ session('error') }}</p>
                </div>
            </div>
        </div>
        @endif

        @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">{{ __('app.checkout_rental.validation_errors') }}</h3>
                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <form method="POST" action="{{ route('checkout-rental.create-order') }}" class="grid lg:grid-cols-3 gap-8">
            @csrf
            <input type="hidden" name="cart_location_id" value="{{ $cartLocation->id }}">
            
            <!-- Colonnes de gauche: Formulaires -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Période de location -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('app.checkout_rental.rental_period_title') }}</h2>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.checkout_rental.start_date_label') }}</label>
                            <input type="date" 
                                   id="start_date" 
                                   name="start_date" 
                                   value="{{ old('start_date', $cartLocation->default_start_date ? $cartLocation->default_start_date->format('Y-m-d') : now()->addDay()->format('Y-m-d')) }}"
                                   x-model="rentalDates.start_date"
                                   :min="minStartDate"
                                   required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                        </div>
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.checkout_rental.end_date_label') }}</label>
                            <input type="date" 
                                   id="end_date" 
                                   name="end_date" 
                                   value="{{ old('end_date', $cartLocation->default_end_date ? $cartLocation->default_end_date->format('Y-m-d') : now()->addDays(8)->format('Y-m-d')) }}"
                                   x-model="rentalDates.end_date"
                                   :min="rentalDates.start_date"
                                   required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                        </div>
                    </div>
                    
                    <div class="mt-4 p-4 bg-purple-50 rounded-lg">
                        <p class="text-sm text-purple-800">
                            <span class="font-medium">{{ __('app.checkout_rental.rental_duration') }}</span> 
                            <span x-text="calculateRentalDays()"></span> {{ __('app.checkout_rental.rental_days') }}
                        </p>
                    </div>
                </div>

                <!-- Adresses de récupération et retour -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('app.checkout_rental.addresses_title') }}</h2>
                    
                    <div class="space-y-6">
                        <!-- Adresse de récupération -->
                        <div>
                            <h3 class="font-medium text-gray-900 mb-3">{{ __('app.checkout_rental.pickup_address_title') }}</h3>
                            <textarea name="pickup_address" 
                                      required
                                      rows="3"
                                      placeholder="{{ __('app.checkout_rental.pickup_address_placeholder') }}"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">{{ old('pickup_address', auth()->user()->address . ', ' . auth()->user()->postal_code . ' ' . auth()->user()->city) }}</textarea>
                        </div>
                        
                        <!-- Adresse de retour -->
                        <div>
                            <h3 class="font-medium text-gray-900 mb-3">{{ __('app.checkout_rental.return_address_title') }}</h3>
                            <div class="flex items-center space-x-2 mb-3">
                                <input type="checkbox" 
                                       id="same_address"
                                       x-model="sameAsPickup"
                                       @change="toggleSameAddress()"
                                       class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                                <label for="same_address" class="text-sm text-gray-600">{{ __('app.checkout_rental.same_address_label') }}</label>
                            </div>
                            <textarea name="return_address" 
                                      required
                                      rows="3"
                                      x-bind:readonly="sameAsPickup"
                                      placeholder="{{ __('app.checkout_rental.return_address_placeholder') }}"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
                                      x-bind:class="sameAsPickup ? 'bg-gray-100 text-gray-500' : ''">{{ old('return_address', auth()->user()->address . ', ' . auth()->user()->postal_code . ' ' . auth()->user()->city) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Notes additionnelles -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('app.checkout_rental.additional_notes_title') }}</h2>
                    <textarea name="notes" 
                              rows="4"
                              placeholder="{{ __('app.checkout_rental.additional_notes_placeholder') }}"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">{{ old('notes') }}</textarea>
                </div>
            </div>
            
            <!-- Colonne de droite: Récapitulatif -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-6 sticky top-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('app.checkout_rental.order_summary_title') }}</h2>
                    
                    <!-- Liste des produits -->
                    <div class="space-y-4 mb-6">
                        @foreach($cartLocation->items as $item)
                        <div class="flex space-x-3 pb-4 border-b border-gray-200 last:border-b-0">
                            <img src="{{ $item->product->image_url }}" 
                                 alt="{{ $item->product_name }}" 
                                 class="w-16 h-16 object-cover rounded-lg">
                            
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900">{{ $item->product_name }}</h4>
                                <p class="text-sm text-gray-600">{{ __('app.checkout_rental.quantity_label') }} {{ $item->quantity }}</p>
                                <p class="text-sm text-gray-600">{{ number_format($item->product->rental_price_per_day, 2) }}{{ __('app.checkout_rental.per_day') }}</p>
                                <p class="text-sm font-medium text-purple-600">
                                    {{ __('app.checkout_rental.period_label') }} {{ $item->start_date->format('d/m/Y') }} - {{ $item->end_date->format('d/m/Y') }}
                                    ({{ $item->duration_days }} {{ __('app.checkout_rental.duration_days') }})
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Total -->
                    <div class="space-y-2 pt-4 border-t border-gray-200">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">{{ __('app.checkout_rental.subtotal_rental') }}</span>
                            <span>{{ number_format($summary['total_amount'], 2) }}€</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">{{ __('app.checkout_rental.total_deposit') }}</span>
                            <span>{{ number_format($summary['total_deposit'], 2) }}€</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">{{ __('app.checkout_rental.vat_label') }}</span>
                            <span>{{ number_format($summary['total_tva'], 2) }}€</span>
                        </div>
                        <div class="flex justify-between text-lg font-semibold pt-2 border-t border-gray-200">
                            <span>{{ __('app.checkout_rental.total_to_pay') }}</span>
                            <span>{{ number_format($summary['total_with_tax'], 2) }}€</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">
                            {{ __('app.checkout_rental.deposit_info') }}
                        </p>
                    </div>
                    
                    <!-- Bouton de validation -->
                    <button type="submit" 
                            class="w-full mt-6 bg-purple-600 text-white py-3 px-4 rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 font-medium">
                        {{ __('app.checkout_rental.confirm_rental_button') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('checkoutRentalData', () => ({
        // Données de location - utiliser les vraies dates du panier
        rentalDates: {
            start_date: '{{ $cartLocation->items->first() ? $cartLocation->items->first()->start_date->format("Y-m-d") : now()->addDay()->format("Y-m-d") }}',
            end_date: '{{ $cartLocation->items->first() ? $cartLocation->items->first()->end_date->format("Y-m-d") : now()->addDays(8)->format("Y-m-d") }}'
        },
        sameAsPickup: true,
        
        // Computed
        get minStartDate() {
            return '{{ now()->addDay()->format("Y-m-d") }}';
        },
        
        // Méthodes
        calculateRentalDays() {
            if (!this.rentalDates.start_date || !this.rentalDates.end_date) {
                return 0;
            }
            
            const start = new Date(this.rentalDates.start_date);
            const end = new Date(this.rentalDates.end_date);
            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1; // +1 car inclusif
            
            return diffDays > 0 ? diffDays : 0;
        },
        
        toggleSameAddress() {
            const pickupAddress = document.querySelector('textarea[name="pickup_address"]');
            const returnAddress = document.querySelector('textarea[name="return_address"]');
            
            if (this.sameAsPickup && pickupAddress && returnAddress) {
                returnAddress.value = pickupAddress.value;
            }
        },
        
        // Initialisation
        init() {
            // Synchroniser les adresses si nécessaire
            this.$watch('sameAsPickup', () => {
                this.toggleSameAddress();
            });
            
            // Initialiser les valeurs avec les vraies dates du panier
            this.rentalDates.start_date = '{{ $cartLocation->default_start_date ? $cartLocation->default_start_date->format("Y-m-d") : now()->addDay()->format("Y-m-d") }}';
            this.rentalDates.end_date = '{{ $cartLocation->default_end_date ? $cartLocation->default_end_date->format("Y-m-d") : now()->addDays(8)->format("Y-m-d") }}';
            
            // Synchroniser automatiquement lors de la frappe dans pickup_address
            setTimeout(() => {
                const pickupAddress = document.querySelector('textarea[name="pickup_address"]');
                if (pickupAddress) {
                    pickupAddress.addEventListener('input', () => {
                        if (this.sameAsPickup) {
                            this.toggleSameAddress();
                        }
                    });
                }
                
                // Initialiser la synchronisation si la checkbox est cochée par défaut
                if (this.sameAsPickup) {
                    this.toggleSameAddress();
                }
            }, 100);
            
            // Debug: Ajouter un event listener sur le formulaire
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', (e) => {
                    // Synchroniser les adresses juste avant soumission
                    if (this.sameAsPickup) {
                        this.toggleSameAddress();
                    }
                    
                    console.log('Formulaire soumis');
                    console.log('Start date:', document.querySelector('[name="start_date"]').value);
                    console.log('End date:', document.querySelector('[name="end_date"]').value);
                    console.log('Pickup address:', document.querySelector('[name="pickup_address"]').value);
                    console.log('Return address:', document.querySelector('[name="return_address"]').value);
                    console.log('Same as pickup:', this.sameAsPickup);
                });
            }
        }
    }));
});
</script>
@endpush
@endsection
