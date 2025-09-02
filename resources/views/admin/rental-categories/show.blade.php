@extends('layouts.admin')

@section('title', __('rental_categories.show.title') . ' - Dashboard Admin')
@section('page-title', __('rental_categories.show.page_title'))

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="flex justify-between items-start">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ $rentalCategory->translated_name }}</h2>
            <p class="text-gray-600">{{ $rentalCategory->slug }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.rental-categories.edit', $rentalCategory) }}" 
               class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition-colors">
                {{ __('rental_categories.show.edit_button') }}
            </a>
            <a href="{{ route('admin.rental-categories.index') }}" 
               class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg transition-colors">
                {{ __('rental_categories.show.back_button') }}
            </a>
        </div>
    </div>

    <!-- Messages de succès/erreur -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Colonne principale -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informations générales -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('rental_categories.show.general_info') }}</h3>
                
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('rental_categories.show.name_label') }}</dt>
                        <dd class="text-sm text-gray-900">{{ $rentalCategory->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('rental_categories.show.translated_name_label') }}</dt>
                        <dd class="text-sm text-gray-900">{{ $rentalCategory->translated_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('rental_categories.show.slug_label') }}</dt>
                        <dd class="text-sm text-gray-900">{{ $rentalCategory->slug }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('rental_categories.show.display_order_label') }}</dt>
                        <dd class="text-sm text-gray-900">{{ $rentalCategory->display_order ?? 0 }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('rental_categories.show.icon_label') }}</dt>
                        <dd class="text-sm text-gray-900">
                            @if($rentalCategory->icon)
                                <div class="flex items-center space-x-2">
                                    <div class="text-2xl">{{ $rentalCategory->icon }}</div>
                                    <span>{{ $rentalCategory->icon }}</span>
                                </div>
                            @else
                                <span class="text-gray-400 italic">{{ __('rental_categories.show.no_icon') }}</span>
                            @endif
                        </dd>
                    </div>
                </dl>

                @if($rentalCategory->description)
                <div class="mt-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 mb-2">{{ __('rental_categories.show.description_label') }}</dt>
                            <dd class="text-sm text-gray-900 whitespace-pre-wrap">{{ $rentalCategory->description }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 mb-2">{{ __('rental_categories.show.translated_description_label') }}</dt>
                            <dd class="text-sm text-gray-900 whitespace-pre-wrap">{{ $rentalCategory->translated_description }}</dd>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- SEO -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('rental_categories.show.seo_section') }}</h3>
                
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('rental_categories.show.meta_title_label') }}</dt>
                        <dd class="text-sm text-gray-900 mt-1">
                            {{ $rentalCategory->meta_title ?: __('rental_categories.show.not_defined') }}
                        </dd>
                        @if($rentalCategory->meta_title)
                            <div class="mt-1 text-xs text-gray-500">
                                {{ strlen($rentalCategory->meta_title) }} {{ __('rental_categories.show.characters') }}
                                @if(strlen($rentalCategory->meta_title) > 60)
                                    <span class="text-orange-600">{{ __('rental_categories.show.too_long') }}</span>
                                @elseif(strlen($rentalCategory->meta_title) < 30)
                                    <span class="text-yellow-600">{{ __('rental_categories.show.too_short') }}</span>
                                @else
                                    <span class="text-green-600">{{ __('rental_categories.show.optimal') }}</span>
                                @endif
                            </div>
                        @endif
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('rental_categories.show.meta_description_label') }}</dt>
                        <dd class="text-sm text-gray-900 mt-1">
                            {{ $rentalCategory->meta_description ?: __('rental_categories.show.not_defined') }}
                        </dd>
                        @if($rentalCategory->meta_description)
                            <div class="mt-1 text-xs text-gray-500">
                                {{ strlen($rentalCategory->meta_description) }} {{ __('rental_categories.show.characters') }}
                                @if(strlen($rentalCategory->meta_description) > 160)
                                    <span class="text-orange-600">{{ __('rental_categories.show.too_long') }}</span>
                                @elseif(strlen($rentalCategory->meta_description) < 120)
                                    <span class="text-yellow-600">{{ __('rental_categories.show.too_short') }}</span>
                                @else
                                    <span class="text-green-600">{{ __('rental_categories.show.optimal') }}</span>
                                @endif
                            </div>
                        @endif
                    </div>
                </dl>

                <!-- Aperçu Google -->
                @if($rentalCategory->meta_title || $rentalCategory->meta_description)
                <div class="mt-6 p-4 border border-gray-200 rounded-lg bg-gray-50">
                    <h4 class="text-sm font-medium text-gray-900 mb-2">{{ __('rental_categories.show.google_preview') }}</h4>
                    <div class="space-y-1">
                        <div class="text-blue-600 text-lg font-medium">
                            {{ $rentalCategory->meta_title ?: $rentalCategory->translated_name }}
                        </div>
                        <div class="text-green-600 text-sm">
                            {{ url('/') }}/locations/categories/{{ $rentalCategory->slug }}
                        </div>
                        <div class="text-gray-600 text-sm">
                            {{ $rentalCategory->meta_description ?: Str::limit($rentalCategory->translated_description, 160) }}
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Statistiques -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('rental_categories.show.statistics_section') }}</h3>
                
                <dl class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('rental_categories.show.associated_products') }}</dt>
                        <dd class="text-2xl font-bold text-gray-900">
                            {{ \App\Models\Product::where('rental_category_id', $rentalCategory->id)->count() }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('rental_categories.show.active_products') }}</dt>
                        <dd class="text-2xl font-bold text-green-600">
                            {{ \App\Models\Product::where('rental_category_id', $rentalCategory->id)->where('is_active', true)->count() }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('rental_categories.show.inactive_products') }}</dt>
                        <dd class="text-2xl font-bold text-red-600">
                            {{ \App\Models\Product::where('rental_category_id', $rentalCategory->id)->where('is_active', false)->count() }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Colonne latérale -->
        <div class="space-y-6">
            <!-- Statut et visibilité -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('rental_categories.show.sidebar_section') }}</h3>
                
                <dl class="space-y-3">
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-gray-600">{{ __('rental_categories.show.status_label') }}</dt>
                        <dd>
                            @if($rentalCategory->is_active)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    ✅ {{ __('rental_categories.status.active') }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    ❌ {{ __('rental_categories.status.inactive') }}
                                </span>
                            @endif
                        </dd>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-gray-600">{{ __('rental_categories.show.creation_date_label') }}</dt>
                        <dd class="text-sm text-gray-900">{{ $rentalCategory->created_at->format('d/m/Y') }}</dd>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-gray-600">{{ __('rental_categories.show.update_date_label') }}</dt>
                        <dd class="text-sm text-gray-900">{{ $rentalCategory->updated_at->format('d/m/Y H:i') }}</dd>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-gray-600">{{ __('rental_categories.show.products_count_label') }}</dt>
                        <dd class="text-sm text-gray-900">{{ __('rental_categories.show.products_count_value', ['count' => $rentalCategory->rental_products_count ?? 0]) }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Actions rapides -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('rental_categories.show.quick_actions') }}</h3>
                
                <div class="space-y-3">
                    <a href="{{ route('admin.rental-categories.edit', $rentalCategory) }}" 
                       class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition-colors text-center block">
                        {{ __('rental_categories.actions.edit') }}
                    </a>
                    
                    @if($rentalCategory->is_active)
                        <button type="button" 
                                onclick="toggleStatus({{ $rentalCategory->id }}, false)"
                                class="w-full bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                            {{ __('rental_categories.show.deactivate_button') }}
                        </button>
                    @else
                        <button type="button" 
                                onclick="toggleStatus({{ $rentalCategory->id }}, true)"
                                class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                            {{ __('rental_categories.show.activate_button') }}
                        </button>
                    @endif
                    
                    <button type="button" 
                            onclick="confirmDelete({{ $rentalCategory->id }})"
                            class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        {{ __('rental_categories.show.delete_button') }}
                    </button>
                    
                    @if(($rentalCategory->rental_products_count ?? 0) > 0)
                        <a href="{{ route('admin.rental-products.index', ['category' => $rentalCategory->id]) }}" 
                           class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors text-center block">
                            {{ __('rental_categories.show.view_products') }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
                </div>
            </div>

            <!-- Informations système -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('rental_categories.show.system_info') }}</h3>
                
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('rental_categories.show.created_on') }}</dt>
                        <dd class="text-sm text-gray-900">{{ $rentalCategory->created_at->format(__('app.date_format.datetime')) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('rental_categories.show.modified_on') }}</dt>
                        <dd class="text-sm text-gray-900">{{ $rentalCategory->updated_at->format(__('app.date_format.datetime')) }}</dd>
                    </div>
                    @if($rentalCategory->deleted_at)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('rental_categories.show.deleted_on') }}</dt>
                        <dd class="text-sm text-red-600">{{ $rentalCategory->deleted_at->format(__('app.date_format.datetime')) }}</dd>
                    </div>
                    @endif
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(categoryId) {
    if (confirm('{{ __("rental_categories.modal.delete_message") }}')) {
        // Créer et soumettre un formulaire de suppression
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/rental-categories/${categoryId}`;
        
        // CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        // Method spoofing pour DELETE
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);
        
        // Ajouter au DOM et soumettre
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
