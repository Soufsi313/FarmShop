<?php

if (!function_exists('imageUrl')) {
    /**
     * Helper pour générer l'URL correcte d'une image selon l'environnement
     */
    function imageUrl($imagePath) {
        if (!$imagePath) {
            return null;
        }

        // Si c'est déjà une URL complète
        if (str_starts_with($imagePath, 'http')) {
            return $imagePath;
        }

        // Si l'image commence par /images/ (Railway)
        if (str_starts_with($imagePath, '/images/')) {
            return url($imagePath);
        }

        // Si l'image commence par storage/ (Laravel standard)  
        if (str_starts_with($imagePath, 'storage/')) {
            return url($imagePath);
        }

        // Legacy: si c'est juste le nom de fichier, essayer de deviner
        if (!str_contains($imagePath, '/')) {
            // Sur Railway, essayer /images/ d'abord
            if (env('USE_RAILWAY_STORAGE', false) || !is_writable(storage_path('app/public'))) {
                return url('/images/products/' . $imagePath);
            }
            // Sinon storage/
            return url('storage/' . $imagePath);
        }

        // Fallback: retourner tel quel avec url()
        return url($imagePath);
    }
}

if (!function_exists('imageUrlFromStorage')) {
    /**
     * Helper pour convertir les anciens chemins storage/ vers le bon format
     */
    function imageUrlFromStorage($imagePath) {
        if (!$imagePath) return null;

        // Nouveau format (/images/ ou storage/)
        if (str_starts_with($imagePath, '/images/') || str_starts_with($imagePath, 'storage/')) {
            return imageUrl($imagePath);
        }

        // Ancien format direct, ajouter storage/
        return imageUrl('storage/' . $imagePath);
    }
}
