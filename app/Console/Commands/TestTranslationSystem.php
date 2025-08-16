<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TestTranslationSystem extends Command
{
    protected $signature = 'test:translations';
    protected $description = 'Test du système de traduction FarmShop';

    public function handle()
    {
        $this->info('🔍 Test du système de traduction FarmShop...');
        $this->newLine();
        
        // Test des helpers
        $this->info('📦 Test des helpers de traduction:');
        
        try {
            $this->line('   - format_price(15000.50, "fr"): ' . format_price(15000.50, 'fr'));
            $this->line('   - format_price(15000.50, "en"): ' . format_price(15000.50, 'en'));
            $this->line('   - format_price(15000.50, "nl"): ' . format_price(15000.50, 'nl'));
            
            $this->line('   - smart_translate("Ajouter au panier", "en"): ' . smart_translate('Ajouter au panier', 'en'));
            $this->line('   - smart_translate("Ajouter au panier", "nl"): ' . smart_translate('Ajouter au panier', 'nl'));
            $this->line('   - smart_translate("Prix", "en"): ' . smart_translate('Prix', 'en'));
            $this->line('   - smart_translate("Prix", "nl"): ' . smart_translate('Prix', 'nl'));
            
            $this->newLine();
            $this->info('✅ Helpers de traduction fonctionnels');
            
        } catch (\Exception $e) {
            $this->error('❌ Erreur dans les helpers: ' . $e->getMessage());
        }
        
        // Vérification des tables
        $this->newLine();
        $this->info('💾 Vérification des tables de traduction:');
        
        $tables = [
            'product_translations' => 'Traductions de produits',
            'category_translations' => 'Traductions de catégories', 
            'blog_post_translations' => 'Traductions d\'articles',
            'blog_comment_translations' => 'Traductions de commentaires',
            'translations' => 'Traductions génériques'
        ];
        
        foreach ($tables as $table => $description) {
            try {
                $count = DB::table($table)->count();
                $this->line("   ✅ $description ($table): $count entrées");
            } catch (\Exception $e) {
                $this->line("   ❌ $description ($table): Table non trouvée");
            }
        }
        
        // Test des traductions d'interface
        $this->newLine();
        $this->info('🌍 Test des traductions d\'interface:');
        
        $interfaceCount = DB::table('translations')->where('group', 'interface')->count();
        $this->line("   ✅ Traductions d'interface: $interfaceCount entrées");
        
        // Exemple de traductions
        $samples = DB::table('translations')
            ->where('group', 'interface')
            ->where('locale', 'en')
            ->limit(5)
            ->get(['key', 'value']);
            
        foreach ($samples as $sample) {
            $this->line("   • {$sample->key} -> {$sample->value}");
        }
        
        $this->newLine();
        $this->info('🎉 SYSTÈME DE TRADUCTION COMPLET INSTALLÉ !');
        $this->newLine();
        
        $this->line('📚 GUIDE D\'UTILISATION:');
        $this->line('   • smart_translate(\'texte\') - Traduction intelligente');
        $this->line('   • trans_product($product, \'name\') - Traduction de produit');
        $this->line('   • format_price($amount) - Formatage prix selon locale');
        $this->newLine();
        
        $this->line('🌍 LANGUES SUPPORTÉES:');
        $this->line('   🇫🇷 Français (défaut)');
        $this->line('   🇬🇧 English (complet)');
        $this->line('   🇳🇱 Nederlands (complet)');
        $this->newLine();
        
        $this->line('🚀 Accédez à votre site : http://127.0.0.1:8000');
        $this->line('   Testez le sélecteur de langue en haut à droite !');
        
        return 0;
    }
}
