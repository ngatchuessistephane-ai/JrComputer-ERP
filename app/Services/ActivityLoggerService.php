<?php

namespace App\Services;

use App\Models\SystemActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class ActivityLoggerService
{
    protected int $cacheTtl = 600; // Réduit à 10 minutes (au lieu de 3600)
    
    /**
     * Enregistre une activité dans le système
     */
    public function log(
        string $type,
        string $action,
        string $entityType,
        ?int $entityId = null,
        array $data = [],
        string $priority = 'normal',
        array $actionLinks = []
    ): ?SystemActivity {
        try {
            $user = Auth::user();
            $request = request();
            
            $activity = SystemActivity::create([
                'type' => $type,
                'action' => $action,
                'entity_type' => $entityType,
                'entity_id' => $entityId,
                'data' => array_merge($data, [
                    'user_agent' => $request->userAgent(),
                    'ip_address' => $request->ip(),
                    'url' => $request->fullUrl(),
                ]),
                'user_id' => $user?->id,
                'user_name' => $user?->name,
                'priority' => $priority,
                'action_links' => $actionLinks,
                'is_read' => false,
            ]);
            
            // Invalider le cache des activités récentes
            $this->invalidateRecentActivitiesCache();
            
            return $activity;
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'enregistrement de l\'activité: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Récupère les activités récentes (avec cache optimisé)
     */
    public function getRecentActivities(int $limit = 50, array $filters = []): \Illuminate\Database\Eloquent\Collection
    {
        // Cache key unique basé sur les filtres
        $cacheKey = 'recent_activities_' . md5(json_encode($filters) . '_limit_' . $limit);
        
        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($limit, $filters) {
            $query = SystemActivity::query()
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->select([
                    'id', 'type', 'action', 'entity_type', 'entity_id',
                    'data', 'user_id', 'user_name', 'priority', 
                    'action_links', 'is_read', 'read_at', 'created_at'
                ]); // Sélectionne uniquement les colonnes nécessaires
            
            if (isset($filters['type']) && !empty($filters['type'])) {
                $query->where('type', $filters['type']);
            }
            if (isset($filters['priority']) && !empty($filters['priority'])) {
                $query->where('priority', $filters['priority']);
            }
            if (isset($filters['unread_only']) && $filters['unread_only'] === true) {
                $query->where('is_read', false);
            }
            if (isset($filters['user_id']) && !empty($filters['user_id'])) {
                $query->where('user_id', $filters['user_id']);
            }
            
            return $query->get();
        });
    }

    /**
     * Récupère le nombre de notifications non lues (avec cache)
     */
    public function getUnreadCount(): int
    {
        $cacheKey = 'unread_activities_count_' . (Auth::id() ?? 'guest');
        
        return Cache::remember($cacheKey, 30, function () {
            return SystemActivity::where('is_read', false)->count();
        });
    }

    /**
     * Marque toutes les notifications comme lues
     */
    public function markAllAsRead(): int
    {
        $count = SystemActivity::where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);
        
        $this->invalidateCache();
        
        return $count;
    }

    /**
     * Invalide tous les caches liés aux activités
     */
    public function invalidateCache(): void
    {
        Cache::forget('unread_activities_count_' . (Auth::id() ?? 'guest'));
        $this->invalidateRecentActivitiesCache();
    }
    
    /**
     * Invalide le cache des activités récentes (supprime tous les patterns)
     */
    private function invalidateRecentActivitiesCache(): void
    {
        // Note: En production, utilisez Redis avec un pattern matching
        // Solution simple : flush partiel via un tag ou préfixe commun
        Cache::flush(); // Temporaire - à optimiser avec des tags Redis
    }
}