@extends('layouts.admin')

@section('title', __('rental_returns.title') . ' - ' . $orderLocation->order_number)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <!-- Bouton Retour - Amélioré: Plus grand et plus visible -->
                <a href="{{ route('admin.rental-returns.index') }}" class="inline-flex items-center text-purple-600 hover:text-purple-700 bg-purple-50 hover:bg-purple-100 px-4 py-2 rounded-lg transition-all duration-200 mb-4">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    {{ __('rental_returns.back_to_returns') }}
                </a>
                <h1 class="text-3xl font-bold text-gray-900">{{ __('rental_returns.page_title', ['number' => $orderLocation->order_number]) }}</h1>
                <p class="text-gray-600 mt-2">{{ __('rental_returns.subtitle') }}</p>
            </div>
            
            <div class="flex gap-3">
                @if($orderLocation->status === 'completed')
                <form action="{{ route('admin.rental-returns.mark-returned', $orderLocation) }}" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <!-- Bouton Confirmer retour - Amélioré: Plus grand et plus visible -->
                    <button type="submit" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition duration-200 flex items-center shadow-lg hover:shadow-xl transform hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/>
                        </svg>
                        {{ __('rental_returns.confirm_return') }}
                    </button>
                </form>
                @endif
                
                @if($orderLocation->status === 'closed')
                <form action="{{ route('admin.rental-returns.start-inspection', $orderLocation) }}" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <!-- Bouton Commencer inspection - Amélioré: Plus grand et plus visible -->
                    <button type="submit" class="bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700 transition duration-200 flex items-center shadow-lg hover:shadow-xl transform hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        {{ __('rental_returns.start_inspection') }}
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main column -->
        <div class="lg:col-span-2 space-y-8">
            <!-- General information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">{{ __('rental_returns.rental_information') }}</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="font-medium text-gray-700 mb-2">{{ __('rental_returns.customer_name') }}</h3>
                        <p class="text-gray-900">{{ $orderLocation->user->name }}</p>
                        <p class="text-gray-600">{{ $orderLocation->user->email }}</p>
                    </div>
                    
                    <div>
                        <h3 class="font-medium text-gray-700 mb-2">{{ __('rental_returns.rental_period') }}</h3>
                        <p class="text-gray-900">{{ __('rental_returns.from') }} {{ $orderLocation->start_date->format('d/m/Y') }} {{ __('rental_returns.to') }} {{ $orderLocation->end_date->format('d/m/Y') }}</p>
                        <p class="text-gray-600">{{ $orderLocation->rental_days }} {{ __('rental_returns.days') }}</p>
                    </div>
                    
                    <div>
                        <h3 class="font-medium text-gray-700 mb-2">{{ __('rental_returns.rental_status') }}</h3>
                        @php
                            $statusColors = [
                                'completed' => 'bg-yellow-100 text-yellow-800',
                                'closed' => 'bg-blue-100 text-blue-800',
                                'inspecting' => 'bg-purple-100 text-purple-800',
                                'finished' => 'bg-green-100 text-green-800'
                            ];
                            $statusLabels = [
                                'completed' => __('rental_returns.status_completed') . ' (' . __('rental_returns.return_pending') . ')',
                                'closed' => __('rental_returns.return_returned') . ' (' . __('rental_returns.inspection_required') . ')',
                                'inspecting' => __('rental_returns.status_inspecting'),
                                'finished' => __('rental_returns.inspection_completed')
                            ];
                        @endphp
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $statusColors[$orderLocation->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $statusLabels[$orderLocation->status] ?? $orderLocation->status }}
                        </span>
                    </div>
                    
                    <div>
                        <h3 class="font-medium text-gray-700 mb-2">{{ __('rental_returns.actual_return_date') }}</h3>
                        @if($orderLocation->actual_return_date)
                            <p class="text-green-600 font-medium">{{ $orderLocation->actual_return_date->format('d/m/Y à H:i') }}</p>
                        @else
                            <p class="text-gray-400">{{ __('rental_returns.not_returned_yet') }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Produits loués -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">{{ __('rental_returns.rental_products') }}</h2>
                
                <!-- Frais et Pénalités -->
                @if($orderLocation->status !== 'finished' && (($orderLocation->status === 'inspecting' && ($orderLocation->late_days > 0 || $orderLocation->damage_cost > 0))))
                <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <h3 class="text-lg font-semibold text-yellow-800 mb-3">⚠️ {{ __('rental_returns.fees_and_penalties') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @if($orderLocation->status === 'inspecting' && $orderLocation->late_days > 0)
                        <div class="bg-orange-50 p-3 rounded border border-orange-200">
                            <div class="text-sm font-medium text-orange-800">{{ __('rental_returns.late_fees') }}</div>
                            <div class="text-xl font-bold text-orange-900">{{ abs($orderLocation->late_days) }} {{ __('rental_returns.days') }}</div>
                            <div class="text-lg font-semibold text-orange-700" id="summary_late_fees_display">
                                @if($orderLocation->status === 'finished')
                                    {{ number_format($orderLocation->late_fees, 2) }}€
                                @else
                                    {{ number_format($orderLocation->late_fees ?? (abs($orderLocation->late_days) * 10), 2) }}€
                                @endif
                            </div>
                        </div>
                        @endif
                        
                        @if($orderLocation->damage_cost > 0)
                        <div class="bg-red-50 p-3 rounded border border-red-200">
                            <div class="text-sm font-medium text-red-800">{{ __('rental_returns.damage_fees') }}</div>
                            <div class="text-xl font-bold text-red-900">{{ __('rental_returns.damages') }}</div>
                            <div class="text-lg font-semibold text-red-700" id="summary_damage_costs_display">{{ number_format($orderLocation->damage_cost, 2) }}€</div>
                        </div>
                        @endif
                        
                        <div class="bg-gray-50 p-3 rounded border border-gray-200">
                            <div class="text-sm font-medium text-gray-800">{{ __('rental_returns.total_penalties') }}</div>
                            <div class="text-xl font-bold text-gray-900">{{ __('rental_returns.calculated') }}</div>
                            <div class="text-lg font-semibold text-gray-700" id="summary_total_penalties_display">
                                @if($orderLocation->status === 'finished')
                                    {{ number_format((($orderLocation->late_fees ?? 0) * ($orderLocation->late_days > 0 ? 1 : 0)) + ($orderLocation->damage_cost ?? 0), 2) }}€
                                @else
                                    {{ number_format(($orderLocation->late_fees ?? (abs($orderLocation->late_days) * 10)) + ($orderLocation->damage_cost ?? 0), 2) }}€
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                
                @if($orderLocation->status === 'finished')
                <!-- Résultats de l'inspection terminée -->
                <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-green-800 mb-4">✅ {{ __('rental_returns.inspection_finished') }}</h3>
                    
                    <!-- Résumé financier final -->
                    <div class="grid grid-cols-1 gap-4 mb-6" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
                        @if($orderLocation->late_fees > 0 && $orderLocation->late_days > 0)
                        <div class="bg-white p-4 rounded border">
                            <div class="text-sm font-medium text-gray-600">{{ __('rental_returns.late_fees_label') }}</div>
                            <div class="text-xl font-bold text-orange-600">{{ number_format($orderLocation->late_fees ?? 0, 2) }}€</div>
                        </div>
                        @endif
                        @if($orderLocation->damage_cost > 0)
                        <div class="bg-white p-4 rounded border">
                            <div class="text-sm font-medium text-gray-600">{{ __('rental_returns.damage_fees_label') }}</div>
                            <div class="text-xl font-bold text-red-600">{{ number_format($orderLocation->damage_cost ?? 0, 2) }}€</div>
                        </div>
                        @endif
                        @if((($orderLocation->late_fees ?? 0) > 0 && $orderLocation->late_days > 0) || ($orderLocation->damage_cost ?? 0) > 0)
                        <div class="bg-white p-4 rounded border">
                            <div class="text-sm font-medium text-gray-600">{{ __('rental_returns.total_penalties_label') }}</div>
                            <div class="text-xl font-bold text-gray-800">{{ number_format((($orderLocation->late_fees ?? 0) * ($orderLocation->late_days > 0 ? 1 : 0)) + ($orderLocation->damage_cost ?? 0), 2) }}€</div>
                        </div>
                        @endif
                        <div class="bg-white p-4 rounded border">
                            <div class="text-sm font-medium text-gray-600">{{ __('rental_returns.refund_label') }}</div>
                            <div class="text-xl font-bold text-green-600">{{ number_format(max(0, ($orderLocation->deposit_amount ?? 0) - ((($orderLocation->late_fees ?? 0) * ($orderLocation->late_days > 0 ? 1 : 0)) + ($orderLocation->damage_cost ?? 0))), 2) }}€</div>
                        </div>
                    </div>
                    
                    <!-- Détail des produits inspectés -->
                    <div class="space-y-4">
                        <h4 class="font-medium text-gray-900">{{ __('rental_returns.inspection_detail') }} :</h4>
                        @foreach($orderLocation->orderItemLocations as $item)
                        <div class="bg-white border rounded-lg p-4">
                            <div class="flex items-start space-x-4">
                                @if($item->product && $item->product->main_image)
                                <img src="{{ asset('storage/' . $item->product->main_image) }}" alt="{{ $item->product ? $item->product->getTranslation('name', app()->getLocale()) : $item->product_name }}" class="w-12 h-12 object-cover rounded">
                                @else
                                <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
                                    <span class="text-xs text-gray-500">IMG</span>
                                </div>
                                @endif
                                
                                <div class="flex-1">
                                    <h5 class="font-medium text-gray-900">{{ $item->product ? $item->product->getTranslation('name', app()->getLocale()) : $item->product_name }}</h5>
                                    <div class="text-sm text-gray-600 mt-1">
                                        <span>{{ __('rental_returns.quantity') }}: {{ $item->quantity }}</span> • 
                                        <span>{{ __('rental_returns.condition') }}: 
                                            @if($item->condition_at_return === 'excellent')
                                                <span class="text-green-600">{{ __('rental_returns.excellent') }}</span>
                                            @elseif($item->condition_at_return === 'good')
                                                <span class="text-blue-600">{{ __('rental_returns.good') }}</span>
                                            @elseif($item->condition_at_return === 'poor')
                                                <span class="text-red-600">{{ __('rental_returns.poor') }}</span>
                                            @else
                                                <span class="text-gray-500">{{ __('rental_returns.not_specified') }}</span>
                                            @endif
                                        </span>
                                        @if($item->item_damage_cost > 0)
                                        • <span class="text-red-600 font-medium">{{ __('rental_returns.damages') }}: {{ number_format($item->item_damage_cost, 2) }}€</span>
                                        @endif
                                    </div>
                                    @if($item->item_inspection_notes)
                                    <div class="text-sm text-gray-700 mt-2 bg-gray-50 p-2 rounded">
                                        <strong>{{ __('rental_returns.notes') }}:</strong> {{ $item->item_inspection_notes }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    @if($orderLocation->inspection_notes)
                    <div class="mt-4 bg-white border rounded-lg p-4">
                        <h5 class="font-medium text-gray-900 mb-2">{{ __('rental_returns.general_inspection_notes_label') }}:</h5>
                        <p class="text-gray-700">{{ $orderLocation->inspection_notes }}</p>
                    </div>
                    @endif
                    
                    @if($orderLocation->damage_photos && count($orderLocation->damage_photos) > 0)
                    <div class="mt-4 bg-white border rounded-lg p-4">
                        <h5 class="font-medium text-gray-900 mb-3">{{ __('rental_returns.damage_photos') }}:</h5>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            @foreach($orderLocation->damage_photos as $index => $photoPath)
                            <div class="relative group">
                                <img src="{{ asset('storage/' . $photoPath) }}" 
                                     alt="Photo dommage {{ $index + 1 }}" 
                                     class="w-full h-24 object-cover rounded-lg border-2 border-gray-200 cursor-pointer hover:border-blue-300 transition-colors"
                                     onclick="openImageModal('{{ asset('storage/' . $photoPath) }}', 'Photo dommage {{ $index + 1 }}')"">
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-all rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                    </svg>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <p class="text-xs text-gray-500 mt-2">{{ __('rental_returns.click_to_enlarge') }}</p>
                    </div>
                    @endif
                </div>
                @endif
                
                @if($orderLocation->status === 'inspecting')
                <!-- Formulaire d'inspection -->
                <form action="{{ route('admin.rental-returns.finish-inspection', $orderLocation) }}" method="POST" id="inspectionForm" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    
                    <div class="space-y-6">
                        @foreach($orderLocation->orderItemLocations as $item)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-start space-x-4">
                                @if($item->product && $item->product->main_image)
                                <img src="{{ asset('storage/' . $item->product->main_image) }}" alt="{{ $item->product ? $item->product->getTranslation('name', app()->getLocale()) : $item->product_name }}" class="w-16 h-16 object-cover rounded">
                                @else
                                <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                @endif
                                
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900">{{ $item->product ? $item->product->getTranslation('name', app()->getLocale()) : $item->product_name }}</h4>
                                    <p class="text-sm text-gray-600">{{ __('rental_returns.quantity') }}: {{ $item->quantity }}</p>
                                    <p class="text-sm text-gray-600">{{ __('rental_returns.deposit_amount') }} par item: {{ number_format($item->deposit_per_item, 2) }}€</p>
                                    
                                    <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('rental_returns.condition_at_return') }}</label>
                                            <select name="items[{{ $item->id }}][condition_at_return]" class="w-full border border-gray-300 rounded px-3 py-2" required>
                                                <option value="">{{ __('rental_returns.select_condition') }}</option>
                                                <option value="excellent" {{ $item->condition_at_return === 'excellent' ? 'selected' : '' }}>{{ __('rental_returns.excellent') }}</option>
                                                <option value="good" {{ $item->condition_at_return === 'good' ? 'selected' : '' }}>{{ __('rental_returns.good') }}</option>
                                                <option value="poor" {{ $item->condition_at_return === 'poor' ? 'selected' : '' }}>{{ __('rental_returns.poor') }}</option>
                                            </select>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('rental_returns.damage_status') }}</label>
                                            <div class="flex items-center space-x-4">
                                                <label class="flex items-center">
                                                    <input type="radio" 
                                                           name="items[{{ $item->id }}][has_damages]" 
                                                           value="0"
                                                           {{ (!isset($item->has_damages) || !$item->has_damages) ? 'checked' : '' }}
                                                           class="mr-2 text-green-600 focus:ring-green-500">
                                                    <span class="text-green-700">{{ __('rental_returns.no_damage') }}</span>
                                                </label>
                                                <label class="flex items-center">
                                                    <input type="radio" 
                                                           name="items[{{ $item->id }}][has_damages]" 
                                                           value="1"
                                                           {{ (isset($item->has_damages) && $item->has_damages) ? 'checked' : '' }}
                                                           class="mr-2 text-red-600 focus:ring-red-500">
                                                    <span class="text-red-700">{{ __('rental_returns.has_damage') }}</span>
                                                </label>
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1">
                                                {{ __('rental_returns.damage_auto_calculation_note') }}
                                            </div>
                                        </div>
                                        
                                        <div class="md:col-span-1">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('rental_returns.inspection_notes_field') }}</label>
                                            <textarea name="items[{{ $item->id }}][item_inspection_notes]" 
                                                      rows="2" 
                                                      class="w-full border border-gray-300 rounded px-3 py-2"
                                                      placeholder="{{ __('rental_returns.product_condition_notes') }}">{{ $item->item_inspection_notes }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        
                        <!-- Section Frais généraux -->
                        <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                            <h4 class="font-medium text-gray-900 mb-4">💰 {{ __('rental_returns.fees_and_penalties') }}</h4>
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        {{ __('rental_returns.late_fees') }}
                                        @if($orderLocation->late_days > 0)
                                        <small class="text-orange-600">({{ $orderLocation->late_days }} jour{{ $orderLocation->late_days > 1 ? 's' : '' }} × 10€ = {{ $orderLocation->late_days * 10 }}€ suggéré)</small>
                                        @endif
                                    </label>
                                    <input type="number" 
                                           id="late_fees_input"
                                           name="late_fees" 
                                           value="{{ $orderLocation->late_fees ?? ($orderLocation->late_days * 10) }}"
                                           step="0.01" 
                                           min="0" 
                                           max="999999.99"
                                           class="w-full border border-gray-300 rounded px-3 py-2"
                                           placeholder="0.00"
                                           oninput="updatePenaltiesDisplay()"
                                           onchange="updatePenaltiesDisplay()">>
                                    <small class="text-gray-500">{{ __('rental_returns.late_fees_note') }}</small>
                                </div>
                                

                            </div>
                        </div>
                        
                        <div class="border-t pt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('rental_returns.general_inspection_notes') }}</label>
                            <textarea name="general_notes" 
                                      rows="4" 
                                      class="w-full border border-gray-300 rounded px-3 py-2"
                                      placeholder="{{ __('rental_returns.general_inspection_notes') }}...">{{ $orderLocation->inspection_notes }}</textarea>
                        </div>
                        
                        <!-- Section Photos de dommages -->
                        <div class="border-t pt-4" id="damage-photos-section" style="display: none;">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('rental_returns.damage_photos') }}
                                <span class="text-sm text-gray-500 font-normal">({{ __('rental_returns.upload_damage_photos') }})</span>
                            </label>
                            <div class="space-y-3">
                                <div class="flex items-center justify-center w-full">
                                    <label for="damage-photos-input" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                            </svg>
                                            <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">{{ __('rental_returns.add_damage_photos') }}</span></p>
                                            <p class="text-xs text-gray-500">PNG, JPG, JPEG (MAX. 5MB chacune)</p>
                                        </div>
                                        <input id="damage-photos-input" type="file" name="damage_photos[]" multiple accept="image/*" class="hidden" onchange="previewDamagePhotos(event)">
                                    </label>
                                </div>
                                <div id="damage-photos-preview" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4"></div>
                                <p class="text-xs text-gray-500">{{ __('rental_returns.damage_photos_note') }}</p>
                            </div>
                        </div>
                        
                        <div class="flex justify-end gap-4">
                            <button type="button" onclick="history.back()" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition duration-200">
                                {{ __('rental_returns.cancel') }}
                            </button>
                            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition duration-200">
                                {{ __('rental_returns.complete_inspection') }}
                            </button>
                        </div>
                    </div>
                </form>
                @endif
            </div>
        </div>

        <!-- Colonne latérale -->
        <div class="space-y-6">
            <!-- Résumé financier -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('rental_returns.financial_summary') }}</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">{{ __('rental_returns.rental_subtotal') }}:</span>
                        <span class="font-medium">{{ number_format($orderLocation->subtotal, 2) }}€</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">{{ __('rental_returns.deposit_caution') }}:</span>
                        <span class="font-medium">{{ number_format($orderLocation->deposit_amount, 2) }}€</span>
                    </div>
                    
                    @php
                        $calculatedPenalties = (($orderLocation->late_fees ?? 0) * ($orderLocation->late_days > 0 ? 1 : 0)) + ($orderLocation->damage_cost ?? 0);
                    @endphp
                    @if($calculatedPenalties > 0)
                    <div class="flex justify-between text-red-600">
                        <span>Pénalités/Dégâts:</span>
                        <span class="font-medium">-{{ number_format($calculatedPenalties, 2) }}€</span>
                    </div>
                    @endif
                    
                    @if($orderLocation->deposit_refund !== null)
                    <div class="border-t pt-3">
                        <div class="flex justify-between text-green-600">
                            <span class="font-medium">Caution libérée:</span>
                            <span class="font-bold">{{ number_format($orderLocation->deposit_refund, 2) }}€</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Timeline de l'inspection -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('rental_returns.timeline') }}</h3>
                
                <div class="space-y-4">
                    <div class="flex items-start space-x-3">
                        <div class="w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ __('rental_returns.rental_created') }}</p>
                            <p class="text-xs text-gray-500">{{ $orderLocation->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>
                    
                    @if($orderLocation->started_at)
                    <div class="flex items-start space-x-3">
                        <div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Location démarrée</p>
                            <p class="text-xs text-gray-500">{{ $orderLocation->started_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>
                    @endif
                    
                    @if($orderLocation->completed_at)
                    <div class="flex items-start space-x-3">
                        <div class="w-2 h-2 bg-yellow-500 rounded-full mt-2"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Location terminée</p>
                            <p class="text-xs text-gray-500">{{ $orderLocation->completed_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>
                    @endif
                    
                    @if($orderLocation->actual_return_date)
                    <div class="flex items-start space-x-3">
                        <div class="w-2 h-2 bg-purple-500 rounded-full mt-2"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ __('rental_returns.equipment_returned') }}</p>
                            <p class="text-xs text-gray-500">{{ $orderLocation->actual_return_date->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>
                    @endif
                    
                    @if($orderLocation->inspection_started_at)
                    <div class="flex items-start space-x-3">
                        <div class="w-2 h-2 bg-indigo-500 rounded-full mt-2"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Inspection démarrée</p>
                            <p class="text-xs text-gray-500">{{ $orderLocation->inspection_started_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>
                    @endif
                    
                    @if($orderLocation->inspection_finished_at)
                    <div class="flex items-start space-x-3">
                        <div class="w-2 h-2 bg-green-600 rounded-full mt-2"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Inspection terminée</p>
                            <p class="text-xs text-gray-500">{{ $orderLocation->inspection_finished_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            @if($orderLocation->inspection_notes)
            <!-- Notes d'inspection -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Notes d'Inspection</h3>
                <p class="text-gray-700 text-sm">{{ $orderLocation->inspection_notes }}</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modale pour agrandir les images -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 hidden">
    <div class="relative max-w-4xl max-h-screen p-4">
        <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white hover:text-gray-300 z-10">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <img id="modalImage" src="" alt="" class="max-w-full max-h-full object-contain rounded-lg">
        <p id="modalCaption" class="text-white text-center mt-2"></p>
    </div>
</div>

<script>
function updatePenaltiesDisplay() {
    // Récupérer les frais de retard
    const lateFees = parseFloat(document.getElementById('late_fees_input')?.value || 0);
    
    // Calculer la somme des coûts de dégâts par produit
    let itemDamageCosts = 0;
    const itemDamageInputs = document.querySelectorAll('.item-damage-cost');
    itemDamageInputs.forEach(input => {
        itemDamageCosts += parseFloat(input.value || 0);
    });
    
    // Total des pénalités
    const totalPenalties = lateFees + itemDamageCosts;
    
    // Mettre à jour l'affichage
    const lateFeesDisplay = document.getElementById('late_fees_display');
    if (lateFeesDisplay) {
        lateFeesDisplay.textContent = lateFees.toFixed(2) + '€';
    }
    
    const damageDisplay = document.getElementById('damage_costs_display');
    if (damageDisplay) {
        damageDisplay.textContent = itemDamageCosts.toFixed(2) + '€';
    }
    
    const totalDisplay = document.getElementById('total_penalties_display');
    if (totalDisplay) {
        totalDisplay.textContent = totalPenalties.toFixed(2) + '€';
    }
}

// Gestion des photos de dommages
function toggleDamagePhotosSection() {
    const damageRadios = document.querySelectorAll('input[type="radio"][name*="[has_damages]"]');
    const photosSection = document.getElementById('damage-photos-section');
    
    let hasDamages = false;
    damageRadios.forEach(radio => {
        if (radio.checked && radio.value === '1') {
            hasDamages = true;
        }
    });
    
    if (photosSection) {
        photosSection.style.display = hasDamages ? 'block' : 'none';
    }
}

function previewDamagePhotos(event) {
    const files = event.target.files;
    const previewContainer = document.getElementById('damage-photos-preview');
    
    // Vider la prévisualisation existante
    previewContainer.innerHTML = '';
    
    if (files.length === 0) return;
    
    Array.from(files).forEach((file, index) => {
        if (!file.type.startsWith('image/')) return;
        
        // Vérifier la taille du fichier (5MB max)
        if (file.size > 5 * 1024 * 1024) {
            alert(`Le fichier ${file.name} est trop volumineux (max 5MB)`);
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            const photoContainer = document.createElement('div');
            photoContainer.className = 'relative group';
            photoContainer.innerHTML = `
                <img src="${e.target.result}" alt="Photo dommage ${index + 1}" 
                     class="w-full h-24 object-cover rounded-lg border-2 border-gray-200">
                <button type="button" 
                        onclick="removeDamagePhoto(this, ${index})"
                        class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            `;
            previewContainer.appendChild(photoContainer);
        };
        reader.readAsDataURL(file);
    });
}

function removeDamagePhoto(button, index) {
    const photoContainer = button.parentElement;
    photoContainer.remove();
    
    // Mettre à jour l'input file pour retirer ce fichier
    const fileInput = document.getElementById('damage-photos-input');
    const dt = new DataTransfer();
    const files = Array.from(fileInput.files);
    
    files.forEach((file, i) => {
        if (i !== index) {
            dt.items.add(file);
        }
    });
    
    fileInput.files = dt.files;
}

// Gestion de la modale d'image
function openImageModal(imageSrc, caption) {
    const modal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    const modalCaption = document.getElementById('modalCaption');
    
    modalImage.src = imageSrc;
    modalCaption.textContent = caption;
    modal.classList.remove('hidden');
    
    // Fermer avec Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeImageModal();
        }
    });
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.classList.add('hidden');
}

// Fermer la modale en cliquant en dehors de l'image
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('imageModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeImageModal();
            }
        });
    }
});

// Initialiser l'affichage au chargement de la page SEULEMENT en mode inspection
document.addEventListener('DOMContentLoaded', function() {
    @if($orderLocation->status === 'inspecting')
    updatePenaltiesDisplay();
    
    // Ajouter des écouteurs d'événements pour les boutons radio de dommages
    const damageRadios = document.querySelectorAll('input[type="radio"][name*="[has_damages]"]');
    damageRadios.forEach(radio => {
        radio.addEventListener('change', toggleDamagePhotosSection);
    });
    
    // Initialiser l'affichage des photos
    toggleDamagePhotosSection();
    @endif
});
</script>

@endsection
