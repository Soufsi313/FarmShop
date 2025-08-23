<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;

class QueueWorkerService
{
    /**
     * Vérifier si un worker de queue est actif
     */
    public static function isWorkerRunning(): bool
    {
        try {
            // Vérifier si un processus php artisan queue:work est en cours
            if (PHP_OS_FAMILY === 'Windows') {
                $result = shell_exec('tasklist /FI "IMAGENAME eq php.exe" /FO CSV 2>NUL | findstr /C:"queue:work"');
                return !empty(trim($result));
            } else {
                $result = shell_exec('ps aux | grep "queue:work" | grep -v grep');
                return !empty(trim($result));
            }
        } catch (\Exception $e) {
            Log::warning('Impossible de vérifier le statut du worker', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Démarrer automatiquement un worker si aucun n'est actif
     */
    public static function ensureWorkerIsRunning(): bool
    {
        // Vérifier si déjà en cours
        if (self::isWorkerRunning()) {
            Log::info('Worker de queue déjà actif');
            return true;
        }

        try {
            Log::info('🚀 Démarrage automatique du worker de queue');
            
            if (PHP_OS_FAMILY === 'Windows') {
                // Windows - démarrer en arrière-plan avec timeout de 5h
                $command = 'start /B php artisan queue:work --daemon --tries=3 --timeout=18000 --sleep=1 > nul 2>&1';
                shell_exec($command);
                
                // Attendre un peu et vérifier
                sleep(2);
                $isRunning = self::isWorkerRunning();
                
                if ($isRunning) {
                    Log::info('✅ Worker de queue démarré automatiquement');
                } else {
                    Log::warning('⚠️ Échec du démarrage automatique du worker');
                }
                
                return $isRunning;
            } else {
                // Linux/Mac - timeout de 5h
                $command = 'nohup php artisan queue:work --daemon --tries=3 --timeout=18000 --sleep=1 > /dev/null 2>&1 &';
                shell_exec($command);
                
                sleep(2);
                return self::isWorkerRunning();
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors du démarrage automatique du worker', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Obtenir le nombre de jobs en attente
     */
    public static function getPendingJobsCount(): int
    {
        try {
            return \DB::table('jobs')->count();
        } catch (\Exception $e) {
            Log::warning('Impossible de compter les jobs en attente', ['error' => $e->getMessage()]);
            return 0;
        }
    }
}
