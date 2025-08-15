<?php

/**
 * Script d'aide à la traduction automatique
 * Scanne les fichiers Blade et identifie les textes à traduire
 */

require_once __DIR__ . '/vendor/autoload.php';

class TranslationScanner
{
    private $viewsPath;
    private $langPath;
    private $excludePatterns = [
        '/admin\/.*\.blade\.php$/', // Exclure les vues admin
    ];
    
    // Textes à ignorer (variables, routes, etc.)
    private $ignorePatterns = [
        '/^\$.*/', // Variables PHP
        '/^route\(.*\)/', // Routes
        '/^asset\(.*\)/', // Assets
        '/^auth\(\).*/', // Auth functions
        '/^config\(.*\)/', // Config
        '/^.*\-\>.*/', // Méthodes d'objets
        '/^\d+$/', // Nombres purs
        '/^[a-zA-Z0-9_-]+\.(jpg|jpeg|png|gif|svg|css|js)$/', // Fichiers
    ];
    
    public function __construct()
    {
        $this->viewsPath = __DIR__ . '/resources/views';
        $this->langPath = __DIR__ . '/lang';
    }
    
    public function scanViews()
    {
        echo "🔍 Scan des fichiers Blade pour les traductions...\n";
        echo str_repeat("=", 60) . "\n";
        
        $files = $this->getBladeFiles($this->viewsPath);
        $allTexts = [];
        
        foreach ($files as $file) {
            if ($this->shouldExcludeFile($file)) {
                echo "⏭️  Ignoré: " . $this->getRelativePath($file) . "\n";
                continue;
            }
            
            echo "📄 Scan: " . $this->getRelativePath($file) . "\n";
            $texts = $this->extractTexts($file);
            
            if (!empty($texts)) {
                $allTexts[$file] = $texts;
                echo "   ✅ " . count($texts) . " textes trouvés\n";
            } else {
                echo "   ℹ️  Aucun texte à traduire\n";
            }
        }
        
        echo "\n" . str_repeat("=", 60) . "\n";
        echo "📊 Résumé du scan:\n";
        echo "   Fichiers scannés: " . count($files) . "\n";
        echo "   Fichiers avec du contenu: " . count($allTexts) . "\n";
        echo "   Total de textes: " . array_sum(array_map('count', $allTexts)) . "\n";
        
        return $allTexts;
    }
    
    private function getBladeFiles($directory)
    {
        $files = [];
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory)
        );
        
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php' && 
                strpos($file->getFilename(), '.blade.') !== false) {
                $files[] = $file->getPathname();
            }
        }
        
        return $files;
    }
    
    private function shouldExcludeFile($file)
    {
        $relativePath = $this->getRelativePath($file);
        
        foreach ($this->excludePatterns as $pattern) {
            if (preg_match($pattern, $relativePath)) {
                return true;
            }
        }
        
        return false;
    }
    
    private function getRelativePath($file)
    {
        return str_replace($this->viewsPath . '/', '', $file);
    }
    
    private function extractTexts($file)
    {
        $content = file_get_contents($file);
        $texts = [];
        
        // Patterns pour extraire les textes
        $patterns = [
            // Texte entre guillemets simples
            "/'([^'\\\\]*(\\\\.[^'\\\\]*)*)'/",
            // Texte entre guillemets doubles (plus complexe pour éviter les variables)
            '/"([^"\\\\]*(\\\\.[^"\\\\]*)*)(?=">|"\s*[,\)\]\}]|"$)/m',
            // Texte dans les balises HTML
            '/>([^<]+)</m',
            // Attributs title, alt, placeholder
            '/(?:title|alt|placeholder)="([^"]+)"/i',
        ];
        
        foreach ($patterns as $pattern) {
            preg_match_all($pattern, $content, $matches);
            
            foreach ($matches[1] as $match) {
                $text = trim($match);
                
                if ($this->isValidText($text)) {
                    $texts[] = $text;
                }
            }
        }
        
        return array_unique($texts);
    }
    
    private function isValidText($text)
    {
        // Ignorer les textes vides ou trop courts
        if (strlen($text) < 2) {
            return false;
        }
        
        // Ignorer les textes contenant uniquement des caractères spéciaux
        if (!preg_match('/[a-zA-ZÀ-ÿ]/', $text)) {
            return false;
        }
        
        // Appliquer les patterns d'exclusion
        foreach ($this->ignorePatterns as $pattern) {
            if (preg_match($pattern, $text)) {
                return false;
            }
        }
        
        // Ignorer les directives Blade
        if (strpos($text, '@') === 0 || strpos($text, '{{') !== false) {
            return false;
        }
        
        return true;
    }
    
    public function generateTranslationKeys($texts)
    {
        echo "\n🗝️  Génération des clés de traduction...\n";
        echo str_repeat("=", 60) . "\n";
        
        $keys = [];
        $sections = [
            'nav' => ['navigation', 'menu', 'accueil', 'produits', 'contact'],
            'forms' => ['nom', 'email', 'mot de passe', 'adresse', 'téléphone'],
            'buttons' => ['envoyer', 'annuler', 'confirmer', 'suivant', 'précédent'],
            'messages' => ['succès', 'erreur', 'attention', 'information'],
            'ecommerce' => ['prix', 'quantité', 'panier', 'commander', 'total'],
        ];
        
        foreach ($texts as $file => $fileTexts) {
            foreach ($fileTexts as $text) {
                $section = $this->categorizeText($text, $sections);
                $key = $this->generateKey($text);
                
                if (!isset($keys[$section])) {
                    $keys[$section] = [];
                }
                
                $keys[$section][$key] = [
                    'fr' => $text,
                    'en' => '', // À traduire
                    'nl' => '', // À traduire
                    'files' => []
                ];
                
                if (!in_array($file, $keys[$section][$key]['files'])) {
                    $keys[$section][$key]['files'][] = $this->getRelativePath($file);
                }
            }
        }
        
        return $keys;
    }
    
    private function categorizeText($text, $sections)
    {
        $text_lower = strtolower($text);
        
        foreach ($sections as $section => $keywords) {
            foreach ($keywords as $keyword) {
                if (strpos($text_lower, $keyword) !== false) {
                    return $section;
                }
            }
        }
        
        return 'general';
    }
    
    private function generateKey($text)
    {
        // Créer une clé basée sur le texte
        $key = strtolower($text);
        $key = preg_replace('/[^a-z0-9\s]/', '', $key);
        $key = preg_replace('/\s+/', '_', trim($key));
        $key = substr($key, 0, 50); // Limiter la longueur
        
        return $key;
    }
}

// Exécution du script
if (php_sapi_name() === 'cli') {
    $scanner = new TranslationScanner();
    $texts = $scanner->scanViews();
    $keys = $scanner->generateTranslationKeys($texts);
    
    // Afficher un échantillon des clés générées
    echo "\n📋 Échantillon des clés générées:\n";
    echo str_repeat("=", 60) . "\n";
    
    foreach (array_slice($keys, 0, 3) as $section => $sectionKeys) {
        echo "\n[{$section}]\n";
        foreach (array_slice($sectionKeys, 0, 5) as $key => $data) {
            echo "  {$key} => \"{$data['fr']}\"\n";
            echo "    Fichiers: " . implode(', ', $data['files']) . "\n";
        }
        if (count($sectionKeys) > 5) {
            echo "  ... et " . (count($sectionKeys) - 5) . " autres\n";
        }
    }
    
    echo "\n✅ Scan terminé ! Clés prêtes pour la traduction.\n";
}
