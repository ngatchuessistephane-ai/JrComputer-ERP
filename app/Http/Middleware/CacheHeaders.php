<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CacheHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Cache uniquement pour les assets statiques (images, CSS, JS compilés)
        if ($request->is('build/*') || 
            $request->is('images/*') || 
            $request->is('css/*') || 
            $request->is('js/*') ||
            $request->is('*.css') ||
            $request->is('*.js') ||
            $request->is('*.jpg') ||
            $request->is('*.png') ||
            $request->is('*.svg') ||
            $request->is('*.woff') ||
            $request->is('*.woff2')) {
            
            $response->header('Cache-Control', 'public, max-age=86400, immutable');
            $response->header('Pragma', 'public');
        } 
        // Pour les pages dynamiques authentifiées
        elseif ($request->user()) {
            $response->header('Cache-Control', 'no-cache, no-store, must-revalidate');
            $response->header('Pragma', 'no-cache');
            $response->header('Expires', '0');
        }
        // Pour les pages publiques
        else {
            $response->header('Cache-Control', 'public, max-age=3600');
            $response->header('Pragma', 'public');
        }

        return $response;
    }
}