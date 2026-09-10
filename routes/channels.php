<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

// Canal privé pour les admins et managers
Broadcast::channel('admin', function ($user) {
    return $user->hasRole('admin') || $user->hasRole('manager');
});

// Canal privé pour un utilisateur spécifique
Broadcast::channel('user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Canal public pour les tickets urgents
Broadcast::channel('public-tickets', function ($user = null) {
    return true;
});

// Canal public pour les techniciens
Broadcast::channel('public-technicians', function ($user = null) {
    return $user && $user->hasRole('technicien_sav');
});