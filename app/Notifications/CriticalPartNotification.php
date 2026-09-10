<?php

namespace App\Notifications;

use App\Models\Module5\SparePart;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CriticalPartNotification extends Notification
{
    use Queueable;

    protected $part;

    public function __construct(SparePart $part)
    {
        $this->part = $part;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toArray($notifiable)
    {
        $stock = $this->part->quantity_in_stock;
        $minStock = $this->part->min_stock_alert ?? 5;
        $isOutOfStock = $stock <= 0;
        $isLowStock = $stock > 0 && $stock <= $minStock;

        // ✅ Titre plus précis selon le niveau de stock
        if ($isOutOfStock) {
            $title = ' RUPTURE DE STOCK !';
            $badge = 'URGENT';
            $body = "La pièce {$this->part->name} (Réf: {$this->part->reference}) est en RUPTURE TOTALE. Aucune unité disponible. Commande immédiate requise !";
            $priority = 'critical';
        } elseif ($isLowStock) {
            $title = ' STOCK CRITIQUEMENT BAS';
            $badge = 'ALERTE';
            $body = "La pièce {$this->part->name} (Réf: {$this->part->reference}) est en STOCK TRÈS BAS : seulement {$stock} unité(s) restante(s) pour un seuil minimum de {$minStock}.";
            $priority = 'high';
        } else {
            $title = ' Mise à jour stock pièce';
            $badge = 'INFO';
            $body = "La pièce {$this->part->name} (Réf: {$this->part->reference}) a été mise à jour. Stock actuel : {$stock} unités.";
            $priority = 'normal';
        }

        return [
            'type'         => 'critical_part',
            'category'     => 'alertes',
            'priority'     => $priority,
            'icon'         => $isOutOfStock ? 'bi-exclamation-triangle-fill' : 'bi-puzzle-fill',
            'badge'        => $badge,
            'badge_color'  => $isOutOfStock ? 'np-tag-red' : 'np-tag-amber',
            'title'        => $title,
            'message'      => $body,
            'message_html' => $this->buildMessageHtml($body),
            'data'         => [
                'part_id' => $this->part->id,
                'part_name' => $this->part->name,
                'reference' => $this->part->reference,
                'current_stock' => $stock,
                'min_stock_alert' => $minStock,
                'is_out_of_stock' => $isOutOfStock,
                'is_low_stock' => $isLowStock,
            ],
            'action_url'   => route('module5.parts.index'),
            'action_links' => [
                [
                    'label' => $isOutOfStock ? ' Commander en urgence' : ' Voir les pièces',
                    'url' => route('module5.parts.index'),
                ]
            ],
            'time_ago'     => now()->diffForHumans(),
        ];
    }

    /**
     * ✅ Construire un message HTML enrichi
     */
    private function buildMessageHtml(string $message): string
    {
        // Mettre en évidence les mots-clés
        $keywords = ['RUPTURE TOTALE', 'STOCK CRITIQUEMENT BAS', 'Commande immédiate', 'Réf:'];
        $html = str_replace($keywords, '<strong>$0</strong>', $message);

        // Mettre en évidence les nombres
        $html = preg_replace('/\b(\d+)\s+(unité[s]?|unités)\b/', '<strong>$1</strong> $2', $html);

        return $html;
    }
}