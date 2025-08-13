<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait HandlesImageUploads
{
    /**
     * Upload une image en fonction de l'environnement
     * 
     * @param UploadedFile $file
     * @param string $directory
     * @param string|null $filename
     * @return string|null
     */
    public function uploadImage(UploadedFile $file, $directory = 'uploads', $filename = null)
    {
        try {
            // Valider le fichier
            if (!$file->isValid()) {
                \Log::error('Fichier upload invalide', ['file' => $file->getClientOriginalName()]);
                return null;
            }

            // Générer un nom de fichier si non fourni
            if (!$filename) {
                $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            }

            // Sur Railway ou si on détecte un problème avec storage/app/public
            if ($this->shouldUseRailwayStorage()) {
                return $this->uploadToRailwayStorage($file, $directory, $filename);
            }

            // Utilisation standard de Laravel Storage
            return $this->uploadToLaravelStorage($file, $directory, $filename);

        } catch (\Exception $e) {
            \Log::error('Erreur upload image', [
                'error' => $e->getMessage(),
                'file' => $file->getClientOriginalName(),
                'directory' => $directory
            ]);
            return null;
        }
    }

    /**
     * Détermine si on doit utiliser le stockage Railway
     */
    protected function shouldUseRailwayStorage()
    {
        // Détecter Railway ou forcer via env
        return env('USE_RAILWAY_STORAGE', false) || 
               isset($_ENV['RAILWAY_ENVIRONMENT']) ||
               !is_writable(storage_path('app/public'));
    }

    /**
     * Upload vers Railway (public/images)
     */
    protected function uploadToRailwayStorage(UploadedFile $file, $directory, $filename)
    {
        $disk = Storage::disk('railway');
        $path = $directory . '/' . $filename;
        
        if ($disk->put($path, file_get_contents($file->getPathname()))) {
            \Log::info('Image uploadée vers Railway', ['path' => $path]);
            return '/images/' . $path;
        }
        
        return null;
    }

    /**
     * Upload vers Laravel Storage standard
     */
    protected function uploadToLaravelStorage(UploadedFile $file, $directory, $filename)
    {
        $path = $file->storeAs($directory, $filename, 'public');
        
        if ($path) {
            \Log::info('Image uploadée vers Laravel Storage', ['path' => $path]);
            return 'storage/' . $path;
        }
        
        return null;
    }

    /**
     * Supprime une image
     */
    public function deleteImage($imagePath)
    {
        if (!$imagePath) return true;

        try {
            // Railway storage
            if (str_starts_with($imagePath, '/images/')) {
                $path = str_replace('/images/', '', $imagePath);
                return Storage::disk('railway')->delete($path);
            }
            
            // Laravel storage
            if (str_starts_with($imagePath, 'storage/')) {
                $path = str_replace('storage/', '', $imagePath);
                return Storage::disk('public')->delete($path);
            }

            return true;

        } catch (\Exception $e) {
            \Log::error('Erreur suppression image', ['path' => $imagePath, 'error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Retourne l'URL complète d'une image
     */
    public function getImageUrl($imagePath)
    {
        if (!$imagePath) return null;

        if (str_starts_with($imagePath, 'http')) {
            return $imagePath;
        }

        return url($imagePath);
    }
}
