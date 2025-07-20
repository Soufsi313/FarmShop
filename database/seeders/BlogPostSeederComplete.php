<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\User;

class BlogPostSeederComplete extends Seeder
{
    public function run()
    {
        // Supprimer les articles existants
        BlogPost::withTrashed()->forceDelete();

        // Récupérer les catégories et l'utilisateur admin
        $categories = BlogCategory::all()->keyBy('name');
        $admin = User::where('role', 'Admin')->first();

        if (!$admin) {
            $admin = User::first();
        }

        if (!$admin) {
            $this->command->error('Aucun utilisateur trouvé. Veuillez créer un utilisateur d\'abord.');
            return;
        }

        // Exécuter tous les seeders pour avoir 100 articles
        $this->call([
            BlogPostSeeder::class,
            BlogPostSeederPart2::class,
            BlogPostSeederPart3::class,
            BlogPostSeederPart4::class
        ]);

        $this->command->info("✅ 100 articles de blog créés avec succès dans toutes les catégories !");
        $this->command->info("📊 Répartition par catégorie :");
        
        // Afficher le nombre d'articles par catégorie
        $counts = BlogPost::join('blog_categories', 'blog_posts.blog_category_id', '=', 'blog_categories.id')
                    ->selectRaw('blog_categories.name, COUNT(*) as count')
                    ->groupBy('blog_categories.name')
                    ->get();
        
        foreach ($counts as $count) {
            $this->command->info("   {$count->name}: {$count->count} articles");
        }
    }
}
