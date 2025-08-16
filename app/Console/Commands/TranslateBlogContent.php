<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\BlogPost;
use App\Models\BlogCategory;

class TranslateBlogContent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blog:translate {--force : Force overwrite existing translations}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Translate all blog posts and categories to English and Dutch';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting blog content translation...');
        
        $force = $this->option('force');
        
        // Translate categories first
        $this->translateCategories($force);
        
        // Then translate posts
        $this->translatePosts($force);
        
        $this->info('Blog translation completed successfully!');
    }
    
    private function translateCategories($force = false)
    {
        $this->info('Translating blog categories...');
        
        $categories = BlogCategory::all();
        $progressBar = $this->output->createProgressBar($categories->count());
        
        foreach ($categories as $category) {
            // Check if translations already exist
            $existingEN = DB::table('blog_category_translations')
                ->where('blog_category_id', $category->id)
                ->where('locale', 'en')
                ->exists();
                
            $existingNL = DB::table('blog_category_translations')
                ->where('blog_category_id', $category->id)
                ->where('locale', 'nl')
                ->exists();
            
            // English translation
            if (!$existingEN || $force) {
                $englishName = $this->getEnglishCategoryName($category->name);
                
                DB::table('blog_category_translations')->updateOrInsert(
                    [
                        'blog_category_id' => $category->id,
                        'locale' => 'en'
                    ],
                    [
                        'name' => $englishName,
                        'slug' => \Str::slug($englishName),
                        'description' => $this->translateText($category->description, 'en'),
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                );
            }
            
            // Dutch translation
            if (!$existingNL || $force) {
                $dutchName = $this->getDutchCategoryName($category->name);
                
                DB::table('blog_category_translations')->updateOrInsert(
                    [
                        'blog_category_id' => $category->id,
                        'locale' => 'nl'
                    ],
                    [
                        'name' => $dutchName,
                        'slug' => \Str::slug($dutchName),
                        'description' => $this->translateText($category->description, 'nl'),
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                );
            }
            
            $progressBar->advance();
        }
        
        $progressBar->finish();
        $this->newLine();
        $this->info('Categories translation completed!');
    }
    
    private function translatePosts($force = false)
    {
        $this->info('Translating blog posts...');
        
        $posts = BlogPost::all();
        $progressBar = $this->output->createProgressBar($posts->count());
        
        foreach ($posts as $post) {
            // Check if translations already exist
            $existingEN = DB::table('blog_post_translations')
                ->where('blog_post_id', $post->id)
                ->where('locale', 'en')
                ->exists();
                
            $existingNL = DB::table('blog_post_translations')
                ->where('blog_post_id', $post->id)
                ->where('locale', 'nl')
                ->exists();
            
            // English translation
            if (!$existingEN || $force) {
                DB::table('blog_post_translations')->updateOrInsert(
                    [
                        'blog_post_id' => $post->id,
                        'locale' => 'en'
                    ],
                    [
                        'title' => $this->translateText($post->title, 'en'),
                        'slug' => \Str::slug($this->translateText($post->title, 'en')),
                        'excerpt' => $this->translateText($post->excerpt, 'en'),
                        'content' => $this->translateText($post->content, 'en'),
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                );
            }
            
            // Dutch translation
            if (!$existingNL || $force) {
                DB::table('blog_post_translations')->updateOrInsert(
                    [
                        'blog_post_id' => $post->id,
                        'locale' => 'nl'
                    ],
                    [
                        'title' => $this->translateText($post->title, 'nl'),
                        'slug' => \Str::slug($this->translateText($post->title, 'nl')),
                        'excerpt' => $this->translateText($post->excerpt, 'nl'),
                        'content' => $this->translateText($post->content, 'nl'),
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                );
            }
            
            $progressBar->advance();
        }
        
        $progressBar->finish();
        $this->newLine();
        $this->info('Posts translation completed!');
    }
    
    private function getEnglishCategoryName($frenchName)
    {
        $translations = [
            'Actualités' => 'News',
            'Conseils' => 'Tips',
            'Guides' => 'Guides',
            'Produits' => 'Products',
            'Location' => 'Rental',
            'Agriculture' => 'Agriculture',
            'Élevage' => 'Livestock',
            'Jardinage' => 'Gardening',
            'Matériel' => 'Equipment',
            'Techniques' => 'Techniques'
        ];
        
        return $translations[$frenchName] ?? $frenchName;
    }
    
    private function getDutchCategoryName($frenchName)
    {
        $translations = [
            'Actualités' => 'Nieuws',
            'Conseils' => 'Tips',
            'Guides' => 'Gidsen',
            'Produits' => 'Producten',
            'Location' => 'Verhuur',
            'Agriculture' => 'Landbouw',
            'Élevage' => 'Veeteelt',
            'Jardinage' => 'Tuinieren',
            'Matériel' => 'Materiaal',
            'Techniques' => 'Technieken'
        ];
        
        return $translations[$frenchName] ?? $frenchName;
    }
    
    private function translateText($text, $locale)
    {
        if (empty($text)) {
            return $text;
        }
        
        // Pour cette implémentation basique, on fait une traduction simple
        // Dans un vrai projet, vous utiliseriez un service de traduction comme Google Translate API
        
        if ($locale === 'en') {
            return $this->basicFrenchToEnglish($text);
        } elseif ($locale === 'nl') {
            return $this->basicFrenchToDutch($text);
        }
        
        return $text;
    }
    
    private function basicFrenchToEnglish($text)
    {
        $translations = [
            // Mots courants
            'et' => 'and',
            'le' => 'the',
            'la' => 'the',
            'les' => 'the',
            'un' => 'a',
            'une' => 'a',
            'du' => 'of the',
            'de' => 'of',
            'des' => 'of the',
            'pour' => 'for',
            'avec' => 'with',
            'dans' => 'in',
            'sur' => 'on',
            'par' => 'by',
            'ce' => 'this',
            'cette' => 'this',
            'ces' => 'these',
            'qui' => 'which',
            'que' => 'that',
            'comment' => 'how',
            'quand' => 'when',
            'où' => 'where',
            'pourquoi' => 'why',
            
            // Agriculture/élevage
            'agriculture' => 'agriculture',
            'élevage' => 'livestock farming',
            'ferme' => 'farm',
            'agriculteur' => 'farmer',
            'éleveur' => 'breeder',
            'animal' => 'animal',
            'animaux' => 'animals',
            'vache' => 'cow',
            'vaches' => 'cows',
            'porc' => 'pig',
            'porcs' => 'pigs',
            'mouton' => 'sheep',
            'moutons' => 'sheep',
            'cheval' => 'horse',
            'chevaux' => 'horses',
            'tracteur' => 'tractor',
            'matériel' => 'equipment',
            'outil' => 'tool',
            'outils' => 'tools',
            'semence' => 'seed',
            'semences' => 'seeds',
            'récolte' => 'harvest',
            'culture' => 'crop',
            'cultures' => 'crops',
            'champ' => 'field',
            'champs' => 'fields',
            'terre' => 'soil',
            'sol' => 'soil',
            'engrais' => 'fertilizer',
            'pesticide' => 'pesticide',
            'irrigation' => 'irrigation',
            'serre' => 'greenhouse',
            'serres' => 'greenhouses',
            'jardin' => 'garden',
            'jardinage' => 'gardening',
            'plante' => 'plant',
            'plantes' => 'plants',
            'fleur' => 'flower',
            'fleurs' => 'flowers',
            'légume' => 'vegetable',
            'légumes' => 'vegetables',
            'fruit' => 'fruit',
            'fruits' => 'fruits',
            
            // Actions
            'acheter' => 'buy',
            'vendre' => 'sell',
            'louer' => 'rent',
            'utiliser' => 'use',
            'choisir' => 'choose',
            'sélectionner' => 'select',
            'commander' => 'order',
            'livrer' => 'deliver',
            'installer' => 'install',
            'réparer' => 'repair',
            'entretenir' => 'maintain',
            'nettoyer' => 'clean',
            'stocker' => 'store',
            'transporter' => 'transport',
        ];
        
        foreach ($translations as $french => $english) {
            $text = str_ireplace($french, $english, $text);
        }
        
        return $text;
    }
    
    private function basicFrenchToDutch($text)
    {
        $translations = [
            // Mots courants
            'et' => 'en',
            'le' => 'de',
            'la' => 'de',
            'les' => 'de',
            'un' => 'een',
            'une' => 'een',
            'du' => 'van de',
            'de' => 'van',
            'des' => 'van de',
            'pour' => 'voor',
            'avec' => 'met',
            'dans' => 'in',
            'sur' => 'op',
            'par' => 'door',
            'ce' => 'dit',
            'cette' => 'deze',
            'ces' => 'deze',
            'qui' => 'die',
            'que' => 'dat',
            'comment' => 'hoe',
            'quand' => 'wanneer',
            'où' => 'waar',
            'pourquoi' => 'waarom',
            
            // Agriculture/élevage
            'agriculture' => 'landbouw',
            'élevage' => 'veeteelt',
            'ferme' => 'boerderij',
            'agriculteur' => 'boer',
            'éleveur' => 'veehouder',
            'animal' => 'dier',
            'animaux' => 'dieren',
            'vache' => 'koe',
            'vaches' => 'koeien',
            'porc' => 'varken',
            'porcs' => 'varkens',
            'mouton' => 'schaap',
            'moutons' => 'schapen',
            'cheval' => 'paard',
            'chevaux' => 'paarden',
            'tracteur' => 'tractor',
            'matériel' => 'materiaal',
            'outil' => 'gereedschap',
            'outils' => 'gereedschappen',
            'semence' => 'zaad',
            'semences' => 'zaden',
            'récolte' => 'oogst',
            'culture' => 'gewas',
            'cultures' => 'gewassen',
            'champ' => 'veld',
            'champs' => 'velden',
            'terre' => 'grond',
            'sol' => 'bodem',
            'engrais' => 'meststof',
            'pesticide' => 'pesticide',
            'irrigation' => 'irrigatie',
            'serre' => 'kas',
            'serres' => 'kassen',
            'jardin' => 'tuin',
            'jardinage' => 'tuinieren',
            'plante' => 'plant',
            'plantes' => 'planten',
            'fleur' => 'bloem',
            'fleurs' => 'bloemen',
            'légume' => 'groente',
            'légumes' => 'groenten',
            'fruit' => 'fruit',
            'fruits' => 'vruchten',
            
            // Actions
            'acheter' => 'kopen',
            'vendre' => 'verkopen',
            'louer' => 'huren',
            'utiliser' => 'gebruiken',
            'choisir' => 'kiezen',
            'sélectionner' => 'selecteren',
            'commander' => 'bestellen',
            'livrer' => 'leveren',
            'installer' => 'installeren',
            'réparer' => 'repareren',
            'entretenir' => 'onderhouden',
            'nettoyer' => 'schoonmaken',
            'stocker' => 'opslaan',
            'transporter' => 'transporteren',
        ];
        
        foreach ($translations as $french => $dutch) {
            $text = str_ireplace($french, $dutch, $text);
        }
        
        return $text;
    }
}
