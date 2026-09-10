<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;

class BroadcastAuthController extends Controller
{
    public function authenticate(Request $request)
    {
        // Vérifier si l'utilisateur est authentifié via Sanctum
        if ($request->user()) {
            Broadcast::channel($request->channel_name, function ($user) {
                return true;
            });
            
            return Broadcast::auth($request);
        }
        
        return response()->json(['message' => 'Unauthenticated'], 403);
    }
}