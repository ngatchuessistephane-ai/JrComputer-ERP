<?php

namespace App\Providers;

use App\Models\Module2\PurchaseOrder;
use App\Observers\PurchaseOrderObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
         // Enregistrer l'observateur pour PurchaseOrder
        PurchaseOrder::observe(PurchaseOrderObserver::class);
    }
    
}
