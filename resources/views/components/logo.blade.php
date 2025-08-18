@props(['size' => 'md', 'linkTo' => '/', 'showText' => true])

@php
    $sizes = [
        'sm' => ['circle' => 'w-8 h-8', 'border' => 'border-2', 'sun' => 'w-1.5 h-1.5', 'hills' => 'h-3', 'plant' => 'h-2', 'badge' => 'w-3 h-3', 'text' => 'text-lg'],
        'md' => ['circle' => 'w-16 h-16', 'border' => 'border-4', 'sun' => 'w-3 h-3', 'hills' => 'h-6', 'plant' => 'h-4', 'badge' => 'w-6 h-6', 'text' => 'text-3xl'],
        'lg' => ['circle' => 'w-24 h-24', 'border' => 'border-6', 'sun' => 'w-4 h-4', 'hills' => 'h-9', 'plant' => 'h-6', 'badge' => 'w-8 h-8', 'text' => 'text-4xl'],
        'xl' => ['circle' => 'w-32 h-32', 'border' => 'border-8', 'sun' => 'w-6 h-6', 'hills' => 'h-12', 'plant' => 'h-8', 'badge' => 'w-12 h-12', 'text' => 'text-6xl']
    ];
    
    $sizeClasses = $sizes[$size] ?? $sizes['md'];
@endphp

<a href="{{ $linkTo }}" class="flex items-center group hover:opacity-90 transition-opacity" title="{{ __('app.logo.alt') }}">
    <div class="relative mr-3">
        <!-- Cercle principal avec paysage agricole -->
        <div class="{{ $sizeClasses['circle'] }} {{ $sizeClasses['border'] }} border-farm-green-500 rounded-full bg-gradient-to-br from-farm-green-100 to-farm-green-200 flex items-center justify-center relative overflow-hidden shadow-lg group-hover:shadow-xl transition-shadow">
            
            <!-- Soleil avec rayons -->
            <div class="absolute top-2 right-2 {{ $sizeClasses['sun'] }} bg-yellow-400 rounded-full shadow-sm">
                @if($size === 'lg' || $size === 'xl')
                <!-- Rayons du soleil pour les grandes tailles -->
                <div class="absolute -top-1 left-1/2 transform -translate-x-1/2 w-0.5 h-2 bg-yellow-400"></div>
                <div class="absolute -right-1 top-1/2 transform -translate-y-1/2 w-2 h-0.5 bg-yellow-400"></div>
                <div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-0.5 h-2 bg-yellow-400"></div>
                <div class="absolute -left-1 top-1/2 transform -translate-y-1/2 w-2 h-0.5 bg-yellow-400"></div>
                @endif
            </div>
            
            <!-- Collines en plusieurs couches pour créer la profondeur -->
            <div class="absolute bottom-0 left-0 w-full {{ $sizeClasses['hills'] }} bg-gradient-to-t from-farm-green-500 to-farm-green-400 rounded-b-full opacity-70"></div>
            <div class="absolute bottom-0 right-0 w-3/5 h-4 bg-gradient-to-t from-farm-green-600 to-farm-green-500 rounded-bl-full opacity-90"></div>
            @if($size === 'lg' || $size === 'xl')
            <div class="absolute bottom-0 left-4 w-2/5 h-3 bg-gradient-to-t from-farm-green-700 to-farm-green-600 rounded-bl-full opacity-60"></div>
            @endif
            
            <!-- Plantes/épis de blé -->
            <div class="absolute bottom-2 left-1/2 transform -translate-x-1/2">
                <div class="w-0.5 {{ $sizeClasses['plant'] }} bg-yellow-800 shadow-sm"></div>
                <div class="absolute -top-1 -left-1 w-3 h-2 bg-yellow-400 rounded-full transform rotate-12 shadow-sm"></div>
            </div>
            
            @if($size === 'md' || $size === 'lg' || $size === 'xl')
            <!-- Plantes supplémentaires pour les tailles moyennes et grandes -->
            <div class="absolute bottom-1.5 left-1/4">
                <div class="w-0.5 h-3 bg-yellow-800"></div>
                <div class="absolute -top-1 -left-1 w-2 h-1.5 bg-yellow-400 rounded-full transform rotate-45"></div>
            </div>
            <div class="absolute bottom-1.5 right-1/4">
                <div class="w-0.5 h-2.5 bg-yellow-800"></div>
                <div class="absolute -top-1 -left-1 w-2 h-1.5 bg-yellow-400 rounded-full transform -rotate-12"></div>
            </div>
            @endif
        </div>
        
        <!-- Indicateur shopping/e-commerce -->
        <div class="absolute top-0 -right-1 {{ $sizeClasses['badge'] }} bg-orange-500 rounded-full border-2 border-white flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
            <div class="w-2 h-2 bg-white rounded-sm"></div>
            @if($size === 'lg' || $size === 'xl')
            <!-- Point de notification pour les grandes tailles -->
            <div class="absolute -top-1 -right-1 w-2 h-2 bg-red-500 rounded-full"></div>
            @endif
        </div>
    </div>
    
    @if($showText)
    <!-- Texte du logo adapté selon la langue -->
    <div class="flex flex-col">
        <div class="{{ $sizeClasses['text'] }} font-bold tracking-tight">
            <span class="text-farm-green-500 drop-shadow-sm">Farm</span><span class="text-orange-500 drop-shadow-sm">Shop</span>
        </div>
        @if($size === 'lg' || $size === 'xl')
        <div class="text-sm text-gray-600 tracking-wide uppercase font-medium">
            {{ __('app.logo.subtitle') }}
        </div>
        @endif
    </div>
    @endif
</a>
