<?php

namespace App\Traits;

trait Translatable
{
    /**
     * Obtient la traduction d'un champ
     */
    public function translate($field, $locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        
        if ($locale === 'fr') {
            return $this->{$field};
        }
        
        $translationTable = strtolower(class_basename($this)) . '_translations';
        $foreignKey = strtolower(class_basename($this)) . '_id';
        
        $translation = \DB::table($translationTable)
            ->where($foreignKey, $this->id)
            ->where('locale', $locale)
            ->value($field);
            
        return $translation ?: $this->{$field};
    }

    /**
     * Obtient le nom traduit
     */
    public function getTranslatedNameAttribute()
    {
        return $this->translate('name');
    }

    /**
     * Obtient la description traduite
     */
    public function getTranslatedDescriptionAttribute()
    {
        return $this->translate('description');
    }
}