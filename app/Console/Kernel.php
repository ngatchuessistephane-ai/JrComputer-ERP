<?php

namespace App\Console;

use App\Models\Module1\Product;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Les commandes Artisan enregistrées.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\CheckLowStock::class,
        \App\Console\Commands\CleanCorruptedNotifications::class,
    ];

    /**
     * Définit les tâches planifiées.
     */
    protected function schedule(Schedule $schedule): void
    {
        // ✅ Vérification quotidienne des stocks bas à 08:00
        $schedule->call(function () {
            Log::channel('daily')->info('🔍 Début vérification stocks bas...');
            
            $lowStockProducts = Product::whereRaw('quantity <= alert_threshold')->get();
            
            if ($lowStockProducts->isEmpty()) {
                Log::channel('daily')->info('✅ Aucun produit en stock bas.');
                return;
            }
            
            Log::channel('daily')->warning('⚠️ ' . $lowStockProducts->count() . ' produit(s) en stock bas détecté(s).');
            
            foreach ($lowStockProducts as $product) {
                // ✅ Déclencher la notification via la méthode dédiée
                $product->forceSendLowStockAlert();
                
                Log::channel('daily')->warning('📦 Stock bas : ' . $product->name . ' (Réf: ' . $product->reference . ') quantité : ' . $product->quantity);
            }
            
        })->dailyAt('08:00')->name('check-low-stock');

        // ✅ Vérification toutes les heures (pour les cas urgents)
        $schedule->call(function () {
            // Vérifier les produits avec stock = 0 (urgent)
            $urgentProducts = Product::where('quantity', 0)->get();
            foreach ($urgentProducts as $product) {
                Log::channel('daily')->critical('🚨 STOCK ÉPUISÉ : ' . $product->name . ' (Réf: ' . $product->reference . ')');
                $product->forceSendLowStockAlert();
            }
        })->hourly()->name('check-zero-stock');

        // ✅ Nettoyage des notifications corrompues (quotidien)
        $schedule->command('notifications:clean-corrupted')->daily()->name('clean-corrupted-notifications');
    }

    /**
     * Enregistre les commandes.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}