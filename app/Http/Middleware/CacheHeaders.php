<?php

namespace App\Http\Middleware;

use Closure;

class CacheHeaders
{
   public function handle($request, Closure $next)
{
    $response = $next($request);

    // Cache uniquement pour les assets statiques non authentifiés
    if ($request->is('build/*') || $request->is('images/*')) {
        $response->header('Cache-Control', 'public, max-age=86400');
    } else {
        $response->header('Cache-Control', 'no-store, private');
    }

    return $response;
}
}