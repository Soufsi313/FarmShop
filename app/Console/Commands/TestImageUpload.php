<?php

namespace App\Console\Commands;

use App\Traits\HandlesImageUploads;
use Illuminate\Console\Command;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class TestImageUpload extends Command
{
    use HandlesImageUploads;

    protected $signature = 'test:image-upload';
    protected $description = 'Tester le système d\'upload d\'images unifié';

    public function handle()
    {
        $this->info('=== TEST SYSTÈME UPLOAD UNIFIÉ ===');

        // 1. Test détection environnement
        $this->info("\n1. Détection environnement:");
        $isRailway = $this->shouldUseRailwayStorage();
        $this->line("Utilise Railway Storage: " . ($isRailway ? 'OUI' : 'NON'));

        // 2. Test configuration disks
        $this->info("\n2. Configuration filesystem:");
        try {
            $publicDisk = Storage::disk('public');
            $this->line("✅ Disk 'public' configuré");
            
            $railwayDisk = Storage::disk('railway');
            $this->line("✅ Disk 'railway' configuré");
            $this->line("Railway root: " . config('filesystems.disks.railway.root'));
            $this->line("Railway URL: " . config('filesystems.disks.railway.url'));
        } catch (\Exception $e) {
            $this->error("❌ Erreur configuration: " . $e->getMessage());
        }

        // 3. Test écriture dans disks
        $this->info("\n3. Test écriture:");
        
        // Test Railway disk
        try {
            $railwayDisk = Storage::disk('railway');
            $testPath = 'test/test-railway.txt';
            $testContent = 'Test Railway - ' . date('Y-m-d H:i:s');
            
            if ($railwayDisk->put($testPath, $testContent)) {
                $this->line("✅ Écriture Railway disk réussie");
                $url = $railwayDisk->url($testPath);
                $this->line("URL générée: $url");
                
                // Vérifier le fichier physique
                $physicalPath = public_path('images/' . $testPath);
                if (file_exists($physicalPath)) {
                    $this->line("✅ Fichier physique créé: $physicalPath");
                } else {
                    $this->error("❌ Fichier physique manquant: $physicalPath");
                }
                
                // Nettoyer
                $railwayDisk->delete($testPath);
                $this->line("🧹 Fichier test supprimé");
            } else {
                $this->error("❌ Écriture Railway disk échouée");
            }
        } catch (\Exception $e) {
            $this->error("❌ Erreur Railway disk: " . $e->getMessage());
        }

        // Test public disk
        try {
            $publicDisk = Storage::disk('public');
            $testPath = 'test/test-public.txt';
            $testContent = 'Test Public - ' . date('Y-m-d H:i:s');
            
            if ($publicDisk->put($testPath, $testContent)) {
                $this->line("✅ Écriture public disk réussie");
                $url = $publicDisk->url($testPath);
                $this->line("URL générée: $url");
                
                // Nettoyer
                $publicDisk->delete($testPath);
                $this->line("🧹 Fichier test supprimé");
            } else {
                $this->error("❌ Écriture public disk échouée");
            }
        } catch (\Exception $e) {
            $this->error("❌ Erreur public disk: " . $e->getMessage());
        }

        // 4. Test des dossiers images
        $this->info("\n4. Structure dossiers images:");
        $directories = [
            'products',
            'products/gallery', 
            'products/additional',
            'special-offers',
            'blog/articles'
        ];

        foreach ($directories as $dir) {
            $publicPath = public_path('images/' . $dir);
            $exists = is_dir($publicPath) ? '✅' : '❌';
            $writable = is_dir($publicPath) && is_writable($publicPath) ? 'WRITABLE' : 'NOT WRITABLE';
            $this->line("$exists images/$dir - $writable");
        }

        // 5. Test simulation upload
        $this->info("\n5. Simulation upload:");
        $this->line("ℹ️ Pour tester un vrai upload, utilisez l'interface admin");

        $this->info("\n=== FIN TEST ===");
    }
}
