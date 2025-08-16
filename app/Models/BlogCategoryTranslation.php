<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogCategoryTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'blog_category_id',
        'locale',
        'name',
        'description',
        'meta_title',
        'meta_description',
    ];

    /**
     * Relation vers la catégorie de blog originale
     */
    public function blogCategory()
    {
        return $this->belongsTo(BlogCategory::class);
    }
}
