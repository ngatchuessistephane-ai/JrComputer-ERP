<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckDashboardAccess
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        
        // Si l'utilisateur n'est pas connecté, laisser passer (l'auth middleware gérera)
        if (!$user) {
            return $next($request);
        }
        
        // Vérifier si l'utilisateur peut voir les analytics
        if (!$user->can('view analytics')) {
            // Rediriger vers product-index pour vendeur et technicien
            return redirect()->route('module1.products.index')
                ->with('warning', 'Vous n\'avez pas accès au tableau de bord principal. Voici la gestion des produits.');
        }
        
        return $next($request);
    }
}