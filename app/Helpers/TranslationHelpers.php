<?php

if (!function_exists('trans_db')) {
    /**
     * Obtient la traduction d'un élément de base de données
     */
    function trans_db($table, $id, $field, $locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        
        $translation = DB::table($table . '_translations')
            ->where($table . '_id', $id)
            ->where('locale', $locale)
            ->value($field);
            
        return $translation ?: trans("fallback.{$field}");
    }
}

if (!function_exists('trans_product')) {
    /**
     * Traduit un produit en utilisant les fichiers de langue Laravel
     */
    function trans_product($product, $field = 'name', $locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        
        if ($locale === 'fr') {
            return $product->{$field};
        }
        
        // Utiliser les fichiers de langue Laravel pour les traductions
        if ($field === 'name') {
            // Essayer d'abord app.slug (nouvelle structure)
            $translation = __('app.' . $product->slug, [], $locale);
            if ($translation !== 'app.' . $product->slug) {
                return $translation;
            }
            
            // Fallback vers l'ancienne structure app.product_names.slug
            $translation = __('app.product_names.' . $product->slug, [], $locale);
            if ($translation !== 'app.product_names.' . $product->slug) {
                return $translation;
            }
        } elseif ($field === 'description') {
            $translation = __('app.product_descriptions.' . $product->slug, [], $locale);
            if ($translation !== 'app.product_descriptions.' . $product->slug) {
                return $translation;
            }
        }
        
        // Fallback vers l'ancienne méthode base de données si pas de traduction dans les fichiers
        $translation = DB::table('product_translations')
            ->where('product_id', $product->id)
            ->where('locale', $locale)
            ->value($field);
            
        return $translation ?: $product->{$field};
    }
}

if (!function_exists('trans_category')) {
    /**
     * Traduit une catégorie
     */
    function trans_category($category, $field = 'name', $locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        
        if ($locale === 'fr') {
            return $category->{$field};
        }
        
        $translation = DB::table('category_translations')
            ->where('category_id', $category->id)
            ->where('locale', $locale)
            ->value($field);
            
        return $translation ?: $category->{$field};
    }
}

if (!function_exists('trans_interface')) {
    /**
     * Traduit les éléments d'interface
     */
    function trans_interface($key, $locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        
        $translation = DB::table('translations')
            ->where('group', 'interface')
            ->where('key', $key)
            ->where('locale', $locale)
            ->value('value');
            
        return $translation ?: __("app.interface.{$key}");
    }
}

if (!function_exists('format_price')) {
    /**
     * Formate un prix selon la locale
     */
    function format_price($price, $locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        
        switch ($locale) {
            case 'en':
                return '€' . number_format($price, 2, '.', ',');
            case 'nl':
                return '€ ' . number_format($price, 2, ',', '.');
            default:
                return number_format($price, 2, ',', ' ') . ' €';
        }
    }
}

if (!function_exists('smart_translate')) {
    /**
     * Traduction intelligente qui détecte le type de contenu
     */
    function smart_translate($content, $context = null, $locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        
        if ($locale === 'fr') {
            return $content;
        }
        
        // Dictionnaire de traductions courantes
        $commonTranslations = [
            'en' => [
                'Ajouter au panier' => 'Add to cart',
                'Voir le produit' => 'View product',
                'En stock' => 'In stock',
                'Rupture de stock' => 'Out of stock',
                'Livraison gratuite' => 'Free delivery',
                'Retour gratuit' => 'Free return',
                'Prix par jour' => 'Price per day',
                'Disponible' => 'Available',
                'Non disponible' => 'Unavailable',
            ],
            'nl' => [
                'Ajouter au panier' => 'Toevoegen aan winkelwagen',
                'Voir le produit' => 'Product bekijken',
                'En stock' => 'Op voorraad',
                'Rupture de stock' => 'Niet op voorraad',
                'Livraison gratuite' => 'Gratis levering',
                'Retour gratuit' => 'Gratis retour',
                'Prix par jour' => 'Prijs per dag',
                'Disponible' => 'Beschikbaar',
                'Non disponible' => 'Niet beschikbaar',
            ],
        ];
        
        return $commonTranslations[$locale][$content] ?? $content;
    }
}