<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestCartTranslations extends Command
{
    protected $signature = 'test:cart-translations';
    protected $description = 'Teste les traductions du panier de location';

    public function handle()
    {
        $this->info('🧪 Test des traductions du panier de location');
        $this->line(str_repeat('=', 60));
        $this->line('');

        $keys = [
            'app.messages.item_removed',
            'app.messages.quantity_updated',
            'app.messages.remove_error',
            'app.messages.confirm_remove_item',
            'app.messages.update_error',
            'app.messages.quantity_update_error',
            'app.messages.cart_cleared',
            'app.messages.clear_cart_error',
            'app.messages.dates_updated',
            'app.messages.dates_update_error'
        ];

        $languages = ['fr', 'en', 'nl'];

        foreach ($languages as $lang) {
            $this->info("🌐 Langue : " . strtoupper($lang));
            $this->line(str_repeat('-', 40));
            
            app()->setLocale($lang);
            
            foreach ($keys as $key) {
                $translation = __($key);
                $status = ($translation !== $key) ? '✅' : '❌';
                $this->line("{$status} {$key}: {$translation}");
            }
            
            $this->line('');
        }

        $this->info('🏁 Test terminé !');
        
        // Remettre la langue par défaut
        app()->setLocale('fr');
        
        return 0;
    }
}
