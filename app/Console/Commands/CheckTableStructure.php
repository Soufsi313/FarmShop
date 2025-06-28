<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CheckTableStructure extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:table {table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vérifier la structure d\'une table';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $table = $this->argument('table');
        
        $this->info("Structure de la table '{$table}':");
        $this->info(str_repeat('=', 50));
        
        if (!Schema::hasTable($table)) {
            $this->error("❌ La table '{$table}' n'existe pas !");
            return 1;
        }
        
        try {
            $columns = Schema::getColumnListing($table);
            
            $this->info("✅ Colonnes trouvées (" . count($columns) . "):");
            foreach ($columns as $column) {
                $this->info("   - {$column}");
            }
            
            // Vérifier si c'est la table memberships
            if ($table === 'memberships') {
                $this->info("\n🔍 Vérification spéciale pour memberships:");
                $hasTeamId = in_array('team_id', $columns);
                $this->info("   team_id présent : " . ($hasTeamId ? '✅ Oui' : '❌ Non'));
                
                if (!$hasTeamId) {
                    $this->warn("\n⚠️  PROBLÈME DÉTECTÉ :");
                    $this->warn("   La colonne 'team_id' est manquante dans la table 'memberships'");
                    $this->warn("   Cela cause l'erreur dans navigation-menu.blade.php");
                }
            }
            
        } catch (\Exception $e) {
            $this->error("❌ Erreur lors de la vérification : " . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}
