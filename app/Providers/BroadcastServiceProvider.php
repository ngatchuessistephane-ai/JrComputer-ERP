<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Routes broadcasting avec middleware auth:sanctum
        Broadcast::routes(['middleware' => ['auth:sanctum']]);
        
        require base_path('routes/channels.php');
    }
}