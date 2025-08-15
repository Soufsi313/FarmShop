<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BlogPost;

class ScanPartialTranslations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blog:scan-translations {--limit=10 : Number of examples to show}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan all blog posts for partial translations and identify untranslated words';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Scanning all blog posts for partial translations...');
        
        $limit = $this->option('limit');
        $posts = BlogPost::all(['id', 'title', 'title_en', 'title_nl']);
        
        $partialTranslations = [
            'en' => [],
            'nl' => []
        ];
        
        $frenchWords = [];
        
        foreach ($posts as $post) {
            // Check English translations
            if ($post->title_en) {
                $untranslatedWords = $this->findUntranslatedWords($post->title, $post->title_en);
                if (!empty($untranslatedWords)) {
                    $partialTranslations['en'][] = [
                        'id' => $post->id,
                        'original' => $post->title,
                        'translated' => $post->title_en,
                        'untranslated_words' => $untranslatedWords
                    ];
                    $frenchWords = array_merge($frenchWords, $untranslatedWords);
                }
            }
            
            // Check Dutch translations
            if ($post->title_nl) {
                $untranslatedWords = $this->findUntranslatedWords($post->title, $post->title_nl);
                if (!empty($untranslatedWords)) {
                    $partialTranslations['nl'][] = [
                        'id' => $post->id,
                        'original' => $post->title,
                        'translated' => $post->title_nl,
                        'untranslated_words' => $untranslatedWords
                    ];
                    $frenchWords = array_merge($frenchWords, $untranslatedWords);
                }
            }
        }
        
        // Display results
        $this->displayResults($partialTranslations, $limit);
        
        // Show most common untranslated words
        $this->showCommonUntranslatedWords($frenchWords);
    }
    
    private function findUntranslatedWords($original, $translated)
    {
        // Clean and normalize text
        $originalWords = $this->extractWords($original);
        $translatedWords = $this->extractWords($translated);
        
        $untranslated = [];
        
        foreach ($originalWords as $word) {
            // Skip very short words, numbers, and common punctuation
            if (strlen($word) < 3 || is_numeric($word)) {
                continue;
            }
            
            // Check if the French word appears in the translation (likely untranslated)
            if (in_array(strtolower($word), array_map('strtolower', $translatedWords))) {
                $untranslated[] = $word;
            }
        }
        
        return array_unique($untranslated);
    }
    
    private function extractWords($text)
    {
        // Remove HTML tags, punctuation, and extract words
        $text = strip_tags($text);
        $text = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $text);
        $words = preg_split('/\s+/', trim($text));
        
        return array_filter($words, function($word) {
            return !empty(trim($word));
        });
    }
    
    private function displayResults($partialTranslations, $limit)
    {
        $this->info("\n=== ENGLISH TRANSLATION ISSUES ===");
        $count = 0;
        foreach ($partialTranslations['en'] as $item) {
            if ($count >= $limit) break;
            $this->line("ID {$item['id']}: {$item['original']}");
            $this->line("EN: {$item['translated']}");
            $this->error("Untranslated words: " . implode(', ', $item['untranslated_words']));
            $this->line('');
            $count++;
        }
        
        $this->info("\n=== DUTCH TRANSLATION ISSUES ===");
        $count = 0;
        foreach ($partialTranslations['nl'] as $item) {
            if ($count >= $limit) break;
            $this->line("ID {$item['id']}: {$item['original']}");
            $this->line("NL: {$item['translated']}");
            $this->error("Untranslated words: " . implode(', ', $item['untranslated_words']));
            $this->line('');
            $count++;
        }
        
        $this->info("Total articles with partial EN translations: " . count($partialTranslations['en']));
        $this->info("Total articles with partial NL translations: " . count($partialTranslations['nl']));
    }
    
    private function showCommonUntranslatedWords($frenchWords)
    {
        if (empty($frenchWords)) {
            $this->info("\nNo untranslated words found!");
            return;
        }
        
        $wordCounts = array_count_values(array_map('strtolower', $frenchWords));
        arsort($wordCounts);
        
        $this->info("\n=== MOST COMMON UNTRANSLATED WORDS ===");
        $count = 0;
        foreach ($wordCounts as $word => $frequency) {
            if ($count >= 20) break; // Show top 20
            $this->line("{$word} (appears {$frequency} times)");
            $count++;
        }
    }
}
