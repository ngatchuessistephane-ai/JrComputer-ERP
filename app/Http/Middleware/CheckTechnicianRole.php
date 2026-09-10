<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckTechnicianRole
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        if (!$user->hasRole('technicien_sav')) {
            abort(403, '⛔ Accès non autorisé. Cette section est réservée aux techniciens SAV.');
        }
        
        return $next($request);
    }
}