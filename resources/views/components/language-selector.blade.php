<!-- Sélecteur de langue Alpine.js -->
<div x-data="{
    isOpen: false,
    currentLocale: '{{ app()->getLocale() }}',
    locales: @js(get_all_locales()),
    loading: false,
    
    async changeLanguage(locale) {
        if (this.loading || locale === this.currentLocale) return;
        
        this.loading = true;
        
        try {
            // Appel AJAX pour changer la langue
            const response = await fetch('{{ route('locale.change', ['locale' => 'PLACEHOLDER']) }}'.replace('PLACEHOLDER', locale), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ locale: locale })
            });
            
            if (response.ok) {
                const data = await response.json();
                // Rediriger vers l'URL localisée
                if (data.redirect_url) {
                    window.location.href = data.redirect_url;
                } else {
                    // Fallback: construire l'URL localisée
                    const currentPath = window.location.pathname;
                    let newPath;
                    
                    // Si on est déjà sur une URL localisée, remplacer la langue
                    if (currentPath.match(/^\/(fr|en|nl)(\/|$)/)) {
                        newPath = currentPath.replace(/^\/(fr|en|nl)/, '/' + locale);
                    } 
                    // Si on est sur l'URL racine
                    else if (currentPath === '/') {
                        newPath = '/' + locale;
                    }
                    // Sinon, ajouter le préfixe de langue
                    else {
                        newPath = '/' + locale + currentPath;
                    }
                    
                    window.location.href = newPath;
                }
            } else {
                console.error('Erreur lors du changement de langue');
            }
        } catch (error) {
            console.error('Erreur AJAX:', error);
        } finally {
            this.loading = false;
            this.isOpen = false;
        }
    },
    
    getCurrentLocaleConfig() {
        return this.locales[this.currentLocale] || this.locales['fr'];
    }
}" class="relative" @click.away="isOpen = false">
    
    <!-- Bouton principal -->
    <button type="button" 
            @click="isOpen = !isOpen"
            :disabled="loading"
            class="flex items-center space-x-2 px-3 py-2 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 transition-all duration-200 shadow-sm hover:shadow-md disabled:opacity-50 disabled:cursor-not-allowed">
        
        <!-- Loading spinner -->
        <div x-show="loading" class="animate-spin h-4 w-4 border-2 border-farm-orange-500 border-t-transparent rounded-full"></div>
        
        <!-- Flag et code langue -->
        <template x-if="!loading">
            <div class="flex items-center space-x-2">
                <span class="text-lg" x-text="getCurrentLocaleConfig().flag"></span>
                <span class="text-sm font-medium text-gray-700 hidden sm:block" x-text="currentLocale.toUpperCase()"></span>
            </div>
        </template>
        
        <!-- Chevron -->
        <svg class="w-4 h-4 text-gray-500 transform transition-transform duration-200"
             :class="{ 'rotate-180': isOpen }"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    <!-- Menu déroulant Alpine.js -->
    <div x-show="isOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-50 origin-top-right">
        
        <template x-for="(config, code) in locales" :key="code">
            <button @click="changeLanguage(code)"
                    :disabled="loading"
                    :class="{
                        'bg-farm-orange-50 border-l-4 border-farm-orange-500': currentLocale === code,
                        'hover:bg-gray-50': currentLocale !== code
                    }"
                    class="w-full flex items-center space-x-3 px-4 py-3 transition-colors duration-150 disabled:opacity-50 disabled:cursor-not-allowed">
                <span class="text-xl" x-text="config.flag"></span>
                <div class="flex-1 text-left">
                    <div class="text-sm font-medium text-gray-900" x-text="config.name"></div>
                    <div class="text-xs text-gray-500 uppercase" x-text="code"></div>
                </div>
                <template x-if="currentLocale === code">
                    <svg class="w-4 h-4 text-farm-orange-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </template>
            </button>
        </template>
    </div>
</div>


