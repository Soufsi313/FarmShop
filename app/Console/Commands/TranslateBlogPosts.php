<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BlogPost;
use Illuminate\Support\Str;

class TranslateBlogPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blog:translate-all {--force : Force retranslation of already translated posts} {--batch=25 : Number of posts to translate per batch} {--offset=0 : Starting offset for batch processing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Translate all blog posts to English and Dutch';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting translation of blog posts...');
        
        $batchSize = (int) $this->option('batch');
        $offset = (int) $this->option('offset');
        
        $query = BlogPost::query();
        
        if (!$this->option('force')) {
            $query->where(function($q) {
                $q->whereNull('title_en')
                  ->orWhereNull('title_nl')
                  ->orWhereNull('content_en')
                  ->orWhereNull('content_nl');
            });
        }
        
        $totalPosts = $query->count();
        $posts = $query->skip($offset)->take($batchSize)->get();
        $processedCount = $posts->count();
        
        if ($processedCount === 0) {
            $this->info('No posts need translation in this batch. Use --force to retranslate all posts.');
            return;
        }
        
        $this->info("Processing batch: {$processedCount} posts (offset: {$offset}, total available: {$totalPosts})");
        
        $progressBar = $this->output->createProgressBar($processedCount);
        $progressBar->start();
        
        $successCount = 0;
        $errorCount = 0;
        
        foreach ($posts as $post) {
            try {
                $this->translatePost($post);
                $successCount++;
                $progressBar->advance();
            } catch (\Exception $e) {
                $errorCount++;
                $this->error("\nError translating post {$post->id}: " . $e->getMessage());
                $progressBar->advance();
            }
        }
        
        $progressBar->finish();
        $this->info("\nBatch translation completed!");
        $this->info("Success: {$successCount}, Errors: {$errorCount}");
        
        $nextOffset = $offset + $batchSize;
        if ($nextOffset < $totalPosts && !$this->option('force')) {
            $this->info("To continue with next batch, run:");
            $this->info("php artisan blog:translate-all --offset={$nextOffset} --batch={$batchSize}");
        } elseif ($nextOffset < BlogPost::count() && $this->option('force')) {
            $this->info("To continue with next batch, run:");
            $this->info("php artisan blog:translate-all --force --offset={$nextOffset} --batch={$batchSize}");
        } else {
            $this->info("All posts have been processed!");
        }
    }
    
    private function translatePost(BlogPost $post)
    {
        // English translations
        if (!$post->title_en || $this->option('force')) {
            $post->title_en = $this->translateText($post->title, 'en');
            $post->slug_en = Str::slug($post->title_en);
        }
        
        if (!$post->excerpt_en || $this->option('force')) {
            $post->excerpt_en = $this->translateText($post->excerpt, 'en');
        }
        
        if (!$post->content_en || $this->option('force')) {
            $post->content_en = $this->translateHtmlContent($post->content, 'en');
        }
        
        if (!$post->meta_title_en || $this->option('force')) {
            $post->meta_title_en = $this->translateText($post->meta_title ?? $post->title, 'en');
        }
        
        if (!$post->meta_description_en || $this->option('force')) {
            $post->meta_description_en = $this->translateText($post->meta_description ?? $post->excerpt, 'en');
        }
        
        // Dutch translations
        if (!$post->title_nl || $this->option('force')) {
            $post->title_nl = $this->translateText($post->title, 'nl');
            $post->slug_nl = Str::slug($post->title_nl);
        }
        
        if (!$post->excerpt_nl || $this->option('force')) {
            $post->excerpt_nl = $this->translateText($post->excerpt, 'nl');
        }
        
        if (!$post->content_nl || $this->option('force')) {
            $post->content_nl = $this->translateHtmlContent($post->content, 'nl');
        }
        
        if (!$post->meta_title_nl || $this->option('force')) {
            $post->meta_title_nl = $this->translateText($post->meta_title ?? $post->title, 'nl');
        }
        
        if (!$post->meta_description_nl || $this->option('force')) {
            $post->meta_description_nl = $this->translateText($post->meta_description ?? $post->excerpt, 'nl');
        }
        
        $post->save();
    }
    
    private function translateText(string $text, string $targetLang): string
    {
        // Load translation dictionary
        $dictionary = include resource_path('lang/translations_dictionary.php');
        $dictKey = 'fr_to_' . $targetLang;
        
        if (!isset($dictionary[$dictKey])) {
            return $this->generateBasicTranslation($text, $targetLang);
        }
        
        $translations = $dictionary[$dictKey];
        $translated = $text;
        
        // Sort by length (longest first) to handle compound terms better
        uksort($translations, function($a, $b) {
            return strlen($b) - strlen($a);
        });
        
        // Apply translations with word boundaries
        foreach ($translations as $french => $target) {
            // Case-insensitive replacement with word boundaries
            $pattern = '/\b' . preg_quote($french, '/') . '\b/ui';
            $translated = preg_replace($pattern, $target, $translated);
        }
        
        // If no significant change occurred, provide basic fallback
        if (strtolower($translated) === strtolower($text)) {
            $translated = $this->generateBasicTranslation($text, $targetLang);
        }
        
        return $translated;
    }
    
    private function translateHtmlContent(string $content, string $targetLang): string
    {
        // Simple approach: extract text content and translate, keeping HTML structure
        $dom = new \DOMDocument('1.0', 'UTF-8');
        libxml_use_internal_errors(true);
        
        // Add proper HTML wrapper to avoid issues
        $htmlContent = '<!DOCTYPE html><html><head><meta charset="UTF-8"></head><body>' . $content . '</body></html>';
        @$dom->loadHTML($htmlContent);
        
        $xpath = new \DOMXPath($dom);
        $textNodes = $xpath->query('//text()[normalize-space()]');
        
        foreach ($textNodes as $node) {
            $trimmedValue = trim($node->nodeValue);
            if (!empty($trimmedValue)) {
                $node->nodeValue = $this->translateText($trimmedValue, $targetLang);
            }
        }
        
        // Extract only the body content
        $body = $dom->getElementsByTagName('body')->item(0);
        $result = '';
        foreach ($body->childNodes as $child) {
            $result .= $dom->saveHTML($child);
        }
        
        return $result;
    }
    
    private function generateBasicTranslation(string $text, string $targetLang): string
    {
        // Basic fallback for untranslated content
        $prefixes = [
            'en' => '[EN] ',
            'nl' => '[NL] '
        ];
        
        return $prefixes[$targetLang] . $text;
    }
}
