<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SystemActivity;
use App\Services\ActivityLoggerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ActivityController extends Controller
{
    protected ActivityLoggerService $activityLogger;

    public function __construct(ActivityLoggerService $activityLogger)
    {
        $this->activityLogger = $activityLogger;
    }

    /**
     * Récupère la liste des activités (notifications Laravel + system_activities)
     */
    public function index(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['success' => true, 'activities' => [], 'unread_count' => 0]);
        }

        $activities = collect();

        // 1. Notifications Laravel
        $notifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get()
            ->map(function ($notification) {
                $data = $notification->data;
                $type = $data['type'] ?? 'general';

                return [
                    'id'          => 'notif_' . $notification->id,
                    'type'        => $type,
                    'title'       => $this->buildTitle($type, $data),
                    'message'     => $this->buildMessage($type, $data),
                    'message_html'=> $this->buildMessageHtml($type, $data),
                    'data'        => $data,
                    'is_read'     => $notification->read_at !== null,
                    'created_at'  => $notification->created_at->toIso8601String(),
                    'time_ago'    => $notification->created_at->diffForHumans(),
                    'action_url'  => $data['action_url'] ?? '#',
                    'action_links'=> $data['action_links'] ?? [],
                    'badge'       => $this->getBadge($type, $data),
                    'badge_color' => $this->getBadgeColor($type, $data),
                    'icon'        => $this->getIcon($type),
                    'icon_color'  => $this->getIconColor($type, $data),
                    'priority'    => $this->getPriority($type, $data),
                    'category'    => $this->getCategory($type),
                    'source'      => 'notification',
                ];
            });

        $activities = $activities->merge($notifications);

        // 2. System Activities
        $systemActivities = SystemActivity::where('user_id', $user->id)
            ->orWhereNull('user_id')
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get()
            ->map(function ($activity) {
                $data = $activity->data ?? [];
                $type = $activity->type ?? 'general';

                return [
                    'id'          => 'sys_' . $activity->id,
                    'type'        => $type,
                    'title'       => $this->buildTitle($type, $data),
                    'message'     => $this->buildMessage($type, $data),
                    'message_html'=> $this->buildMessageHtml($type, $data),
                    'data'        => $data,
                    'is_read'     => (bool) $activity->is_read,
                    'created_at'  => $activity->created_at->toIso8601String(),
                    'time_ago'    => $activity->created_at->diffForHumans(),
                    'action_url'  => $data['action_url'] ?? '#',
                    'action_links'=> $activity->action_links ?? [],
                    'badge'       => $this->getBadge($type, $data),
                    'badge_color' => $this->getBadgeColor($type, $data),
                    'icon'        => $this->getIcon($type),
                    'icon_color'  => $this->getIconColor($type, $data),
                    'priority'    => $this->getPriority($type, $data),
                    'category'    => $this->getCategory($type),
                    'source'      => 'system',
                ];
            });

        $activities = $activities->merge($systemActivities);

        // ✅ DÉDUPLICATION
        $activities = $this->deduplicateActivities($activities);

        // ✅ TRI
        $activities = $activities->sortByDesc('created_at')->values();

        // ✅ RECALCUL DU COMPTEUR
        $unreadCount = $activities->filter(function ($item) {
            return !$item['is_read'];
        })->count();

        return response()->json([
            'success'      => true,
            'activities'   => $activities->values(),
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * ✅ DÉDUPLICATION INTELLIGENTE
     */
    private function deduplicateActivities($activities): \Illuminate\Support\Collection
    {
        $seen = [];
        $unique = [];

        foreach ($activities as $item) {
            $date = new \DateTime($item['created_at']);
            $date->setTime($date->format('H'), $date->format('i'), 0);
            $key = $item['type'] . '|' . $item['message'] . '|' . $date->format('Y-m-d H:i');

            if (!in_array($key, $seen)) {
                $seen[] = $key;
                $unique[] = $item;
            }
        }

        return collect($unique);
    }

    /**
     * Marque une activité comme lue
     */
    public function markAsRead(Request $request, string $id): \Illuminate\Http\JsonResponse
    {
        if (str_starts_with($id, 'notif_')) {
            $realId = substr($id, 6);
            $user = $request->user();
            if ($user) {
                $notification = $user->notifications()->where('id', $realId)->first();
                if ($notification && !$notification->read_at) {
                    $notification->markAsRead();
                }
            }
        } else {
            $realId = (int) str_replace('sys_', '', $id);
            $activity = SystemActivity::find($realId);
            if ($activity && !$activity->is_read) {
                $activity->update(['is_read' => true, 'read_at' => now()]);
            }
        }

        Cache::flush();
        $this->activityLogger->invalidateCache();

        return response()->json(['success' => true]);
    }

    /**
     * Marque toutes les activités comme lues
     */
    public function markAllAsRead(Request $request): \Illuminate\Http\JsonResponse
    {
        SystemActivity::where('is_read', false)->update(['is_read' => true, 'read_at' => now()]);

        $user = $request->user();
        if ($user) {
            $user->unreadNotifications->markAsRead();
        }

        $this->activityLogger->markAllAsRead();
        Cache::flush();

        return response()->json([
            'success' => true,
            'message' => 'Toutes les notifications ont été marquées comme lues'
        ]);
    }

    /**
     * Supprime plusieurs notifications sélectionnées
     */
    public function destroyMultiple(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'Aucun ID fourni']);
        }

        $user = $request->user();
        $deleted = 0;

        foreach ($ids as $id) {
            if (str_starts_with($id, 'notif_')) {
                $realId = substr($id, 6);
                $notification = $user->notifications()->where('id', $realId)->first();
                if ($notification) {
                    $notification->delete();
                    $deleted++;
                }
            } else {
                $realId = (int) str_replace('sys_', '', $id);
                $activity = SystemActivity::where('id', $realId)
                    ->where(function($q) use ($user) {
                        $q->where('user_id', $user->id)->orWhereNull('user_id');
                    })->first();
                if ($activity) {
                    $activity->delete();
                    $deleted++;
                }
            }
        }

        return response()->json(['success' => true, 'deleted' => $deleted]);
    }

    // ============================================================
    // MÉTHODES DE FORMATAGE — VERSION PROFESSIONNELLE
    // ============================================================

    /**
     * Construction du titre
     */
    private function buildTitle(string $type, array $data): string
    {
        return match ($type) {
            'low_stock'       => $this->buildLowStockTitle($data),
            'critical_part'   => $this->buildCriticalPartTitle($data),
            'ticket_assigned' => ' Nouveau ticket SAV',
            'ticket_closed'   => ' Ticket clôturé',
            'urgent', 'ticket_urgent' => ' URGENCE',
            'invoice_created' => ' Nouvelle facture',
            'invoice_paid'    => ' Paiement reçu',
            default           => $data['title'] ?? ' Notification',
        };
    }

    /**
     * Titre court - Stock bas
     */
    private function buildLowStockTitle(array $data): string
    {
        $productName = $data['product_name'] ?? 'Produit';
        $quantity = $data['current_quantity'] ?? 0;
        $threshold = $data['alert_threshold'] ?? 5;

        if ($quantity <= 0) {
            return " RUPTURE - {$productName}";
        } elseif ($quantity <= $threshold) {
            return " STOCK BAS - {$productName}";
        }
        
        return " MàJ stock - {$productName}";
    }

    /**
     * Titre court - Pièces critiques
     */
    private function buildCriticalPartTitle(array $data): string
    {
        $partName = $data['part_name'] ?? 'Pièce';
        $stock = $data['current_stock'] ?? 0;
        $minStock = $data['min_stock_alert'] ?? 5;

        if ($stock <= 0) {
            return " RUPTURE - {$partName}";
        } elseif ($stock <= $minStock) {
            return " STOCK BAS - {$partName}";
        }

        return " MàJ stock - {$partName}";
    }

    /**
     * Construction du message
     */
    private function buildMessage(string $type, array $data): string
    {
        return match ($type) {
            'low_stock'       => $this->buildLowStockMessage($data),
            'critical_part'   => $this->buildCriticalPartMessage($data),
            'ticket_closed'   => sprintf(
                'Ticket %s clôturé - %s',
                $data['ticket_number'] ?? 'N/A',
                $data['device_model'] ?? 'Appareil'
            ),
            'ticket_assigned' => sprintf(
                'Ticket %s - %s',
                $data['ticket_number'] ?? 'Nouveau',
                $data['device_model'] ?? 'Appareil'
            ),
            'urgent', 'ticket_urgent' => sprintf(
                'Ticket %s - Intervention immédiate',
                $data['ticket_number'] ?? 'Nouveau'
            ),
            'invoice_created' => sprintf(
                'Facture %s - %s FCFA',
                $data['invoice_reference'] ?? 'Nouvelle',
                number_format($data['total'] ?? 0, 0, ',', ' ')
            ),
            'invoice_paid' => sprintf(
                'Paiement facture %s - %s FCFA',
                $data['invoice_reference'] ?? 'N/A',
                number_format($data['amount'] ?? 0, 0, ',', ' ')
            ),
            default => $data['message'] ?? 'Nouvelle notification',
        };
    }

    /**
     * Message court - Stock bas
     */
    private function buildLowStockMessage(array $data): string
    {
        $quantity = $data['current_quantity'] ?? 0;
        $threshold = $data['alert_threshold'] ?? 5;

        if ($quantity <= 0) {
            return "Plus d'unités disponibles.";
        } elseif ($quantity <= $threshold) {
            return "{$quantity} unité(s) restante(s) (seuil: {$threshold}).";
        }

        return "Stock mis à jour : {$quantity} unités.";
    }

    /**
     * Message court - Pièces critiques
     */
    private function buildCriticalPartMessage(array $data): string
    {
        $stock = $data['current_stock'] ?? 0;
        $minStock = $data['min_stock_alert'] ?? 5;

        if ($stock <= 0) {
            return "Plus d'unités disponibles.";
        } elseif ($stock <= $minStock) {
            return "{$stock} unité(s) restante(s) (seuil: {$minStock}).";
        }

        return "Stock mis à jour : {$stock} unités.";
    }

    /**
     * Message HTML enrichi
     */
    private function buildMessageHtml(string $type, array $data): string
    {
        $plain = $this->buildMessage($type, $data);
        
        // Mettre en évidence les nombres
        $html = preg_replace('/\b(\d+)\s+unité[s]?\b/', '<strong>$1</strong> unités', $plain);
        
        // Mettre en évidence les mots-clés importants
        $keywords = ['RUPTURE', 'STOCK BAS', 'Commande urgente', 'seuil'];
        $pattern = '/\b(' . implode('|', array_map('preg_quote', $keywords)) . ')\b/';
        $html = preg_replace($pattern, '<strong>$1</strong>', $html);
        
        return $html;
    }

    // ============================================================
    // MÉTADONNÉES
    // ============================================================

    /**
     * Priorité dynamique
     */
    private function getPriority(string $type, array $data): string
    {
        if ($type === 'critical_part' || $type === 'low_stock') {
            $stock = $data['current_stock'] ?? $data['current_quantity'] ?? 0;
            $threshold = $data['min_stock_alert'] ?? $data['alert_threshold'] ?? 5;
            
            if ($stock <= 0) return 'critical';
            if ($stock <= $threshold) return 'high';
            return 'normal';
        }

        return match ($type) {
            'urgent', 'ticket_urgent' => 'critical',
            'ticket_assigned' => 'normal',
            'ticket_closed', 'invoice_paid' => 'low',
            default => 'normal',
        };
    }

    /**
     * Catégorie
     */
    private function getCategory(string $type): string
    {
        return match ($type) {
            'ticket_assigned', 'ticket_closed', 'urgent', 'ticket_urgent' => 'tickets',
            'low_stock', 'critical_part' => 'alertes',
            'invoice_created', 'invoice_paid' => 'commandes',
            default => 'other',
        };
    }

    /**
     * Icône
     */
    private function getIcon(string $type): string
    {
        return match ($type) {
            'low_stock'       => 'bi-exclamation-triangle-fill',
            'critical_part'   => 'bi-puzzle-fill',
            'urgent', 'ticket_urgent' => 'bi-alarm-fill',
            'ticket_assigned' => 'bi-person-plus-fill',
            'ticket_closed'   => 'bi-check-circle-fill',
            'invoice_created' => 'bi-file-earmark-text-fill',
            'invoice_paid'    => 'bi-cash-stack',
            default           => 'bi-bell-fill',
        };
    }

    /**
     * Couleur de l'icône (dynamique)
     */
    private function getIconColor(string $type, array $data): string
    {
        if ($type === 'critical_part' || $type === 'low_stock') {
            $stock = $data['current_stock'] ?? $data['current_quantity'] ?? 0;
            $threshold = $data['min_stock_alert'] ?? $data['alert_threshold'] ?? 5;
            
            if ($stock <= 0) return '#dc2626';
            if ($stock <= $threshold) return '#f07d00';
            return '#10b981';
        }

        return match ($type) {
            'urgent', 'ticket_urgent' => '#dc2626',
            'ticket_assigned' => '#3b82f6',
            'ticket_closed'   => '#10b981',
            'invoice_created' => '#0891b2',
            'invoice_paid'    => '#16a34a',
            default           => '#94a89e',
        };
    }

    /**
     * Badge (dynamique)
     */
    private function getBadge(string $type, array $data): ?string
    {
        if ($type === 'critical_part' || $type === 'low_stock') {
            $stock = $data['current_stock'] ?? $data['current_quantity'] ?? 0;
            $threshold = $data['min_stock_alert'] ?? $data['alert_threshold'] ?? 5;
            
            if ($stock <= 0) return 'URGENT';
            if ($stock <= $threshold) return 'ALERTE';
            return 'INFO';
        }

        return match ($type) {
            'urgent', 'ticket_urgent' => 'URGENT',
            'ticket_assigned' => 'NOUVEAU',
            'ticket_closed' => 'TERMINÉ',
            'invoice_paid' => 'PAYÉ',
            default => null,
        };
    }

    /**
     * Classe CSS du badge (dynamique)
     */
    private function getBadgeColor(string $type, array $data): ?string
    {
        if ($type === 'critical_part' || $type === 'low_stock') {
            $stock = $data['current_stock'] ?? $data['current_quantity'] ?? 0;
            $threshold = $data['min_stock_alert'] ?? $data['alert_threshold'] ?? 5;
            
            if ($stock <= 0) return 'np-tag-red';
            if ($stock <= $threshold) return 'np-tag-amber';
            return 'np-tag-green';
        }

        return match ($type) {
            'urgent', 'ticket_urgent' => 'np-tag-red',
            'ticket_assigned' => 'np-tag-blue',
            'ticket_closed', 'invoice_paid' => 'np-tag-green',
            default => null,
        };
    }
}