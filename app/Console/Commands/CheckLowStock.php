<?php

namespace App\Console\Commands;

use App\Models\Module1\Product;
use Illuminate\Console\Command;

class CheckLowStock extends Command
{
    protected $signature = 'stock:check';
    protected $description = 'Vérifie et envoie les alertes de stock bas';

    public function handle()
    {
        $this->info('🔍 Vérification des stocks...');
        Product::checkAllLowStock();
        $this->info('✅ Vérification terminée.');
        return 0;
    }
}