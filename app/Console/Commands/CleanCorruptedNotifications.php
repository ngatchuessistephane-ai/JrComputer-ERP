<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CleanCorruptedNotifications extends Command
{
    protected $signature = 'notifications:clean-corrupted';
    protected $description = 'Nettoie les notifications corrompues ou orphelines';

    public function handle(): int
    {
        $this->info('🧹 Nettoyage des notifications...');
        
        // Supprimer les notifications sans type valide
        $count = DB::table('notifications')
            ->whereNull('type')
            ->orWhere('type', '')
            ->orWhere('type', 'like', '%\\%')
            ->delete();
        
        if ($count > 0) {
            $this->info("🗑️ $count notification(s) corrompue(s) supprimée(s).");
            Log::channel('daily')->info("Notifications corrompues supprimées : $count");
        } else {
            $this->info('✅ Aucune notification corrompue trouvée.');
        }
        
        return 0;
    }
}