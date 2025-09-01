@extends('layouts.admin')

@section('title', __('app.admin.categories.category_details') . ': ' . $category->name . ' - FarmShop Admin')
@section('page-title', __('app.admin.categories.category_details'))

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header avec navigation -->
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.categories.index') }}" 
               class="text-gray-600 hover:text-gray-900 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $category->name }}</h1>
                <p class="text-gray-600">{{ __('app.admin.categories.details_and_stats') }}</p>
            </div>
        </div>
        
        <div class="flex space-x-3">
            <a href="{{ route('admin.categories.edit', $category) }}" 
               class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                <span>{{ __('app.admin.categories.edit') }}</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informations principales -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Détails de la catégorie -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('app.admin.categories.general_info') }}</h3>
                </div>
                <div class="px-6 py-4 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.admin.categories.name') }}</label>
                            <p class="text-sm text-gray-900">{{ $category->name }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.admin.categories.slug') }}</label>
                            <p class="text-sm text-gray-900 font-mono">{{ $category->slug }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.admin.categories.type') }}</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $category->food_type === 'alimentaire' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ $category->food_type ? ucfirst($category->food_type) : __('app.admin.categories.undefined') }}
                            </span>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.admin.categories.status') }}</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $category->is_active ? __('app.admin.categories.active') : __('app.admin.categories.inactive') }}
                            </span>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.admin.categories.returnable') }}</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $category->is_returnable ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $category->is_returnable ? __('app.admin.categories.yes') : __('app.admin.categories.no') }}
                            </span>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.admin.categories.created_on') }}</label>
                            <p class="text-sm text-gray-900">{{ $category->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>
                    
                    @if($category->description)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.admin.categories.description') }}</label>
                        <p class="text-sm text-gray-900">{{ $category->description }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Produits de la catégorie -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">
                        {{ __('app.admin.categories.associated_products') }}
                        <span class="ml-2 bg-gray-100 text-gray-800 text-sm px-2 py-1 rounded-full">
                            {{ $category->products->count() }}
                        </span>
                    </h3>
                    <a href="{{ route('admin.products.create', ['category' => $category->id]) }}" 
                       class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        {{ __('app.admin.categories.add_product') }}
                    </a>
                </div>
                <div class="px-6 py-4">
                    @if($category->products->count() > 0)
                        <div class="space-y-3">
                            @foreach($category->products as $product)
                            <div class="flex items-center justify-between p-3 border rounded-lg hover:bg-gray-50 transition-colors cursor-pointer"
                                 onclick="window.location.href='{{ route('admin.products.show', $product) }}'">
                                <div class="flex items-center space-x-3">
                                    @if($product->main_image)
                                        <img src="{{ asset('storage/' . $product->main_image) }}" 
                                             alt="{{ $product->name }}" 
                                             class="w-10 h-10 rounded object-cover hover:scale-105 transition-transform">
                                    @else
                                        <div class="w-10 h-10 bg-gray-200 rounded flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-medium text-gray-900 hover:text-blue-600 transition-colors">{{ $product->name }}</p>
                                        <p class="text-sm text-gray-500">{{ number_format($product->price, 2) }}€</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2" onclick="event.stopPropagation()">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                        {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $product->is_active ? __('app.admin.categories.active') : __('app.admin.categories.inactive') }}
                                    </span>
                                    <a href="{{ route('admin.products.show', $product) }}" 
                                       class="text-blue-600 hover:text-blue-800">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('app.admin.categories.no_products') }}</h3>
                            <p class="mt-1 text-sm text-gray-500">{{ __('app.admin.categories.no_products_message') }}</p>
                            <div class="mt-6">
                                <a href="{{ route('admin.products.create', ['category' => $category->id]) }}" 
                                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    {{ __('app.admin.categories.add_first_product') }}
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar avec statistiques -->
        <div class="space-y-6">
            <!-- Statistiques -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('app.admin.categories.statistics') }}</h3>
                </div>
                <div class="px-6 py-4 space-y-4">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">{{ __('app.admin.categories.total_products') }}</span>
                        <span class="text-sm font-medium text-gray-900">{{ $category->products->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">{{ __('app.admin.categories.active_products') }}</span>
                        <span class="text-sm font-medium text-gray-900">{{ $category->products->where('is_active', true)->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">{{ __('app.admin.categories.inactive_products') }}</span>
                        <span class="text-sm font-medium text-gray-900">{{ $category->products->where('is_active', false)->count() }}</span>
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('app.admin.categories.quick_actions') }}</h3>
                </div>
                <div class="px-6 py-4 space-y-3">
                    <a href="{{ route('admin.categories.edit', $category) }}" 
                       class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        {{ __('app.admin.categories.edit_category_action') }}
                    </a>
                    
                    <a href="{{ route('admin.products.create', ['category' => $category->id]) }}" 
                       class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        {{ __('app.admin.categories.add_product_action') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
