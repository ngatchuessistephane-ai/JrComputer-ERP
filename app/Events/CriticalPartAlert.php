<?php

namespace App\Events;

use App\Models\Module5\SparePart;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CriticalPartAlert implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $part;
    public $isOutOfStock;
    public $isLowStock;

    public function __construct(SparePart $part)
    {
        $this->part = $part;
        $this->isOutOfStock = $part->quantity_in_stock <= 0;
        $this->isLowStock = $part->quantity_in_stock > 0 && $part->quantity_in_stock <= $part->min_stock_alert;
    }

    public function broadcastOn()
    {
        return [
            new Channel('private-admin'),
            new Channel('private-manager'),
            new Channel('public-technicians'),
        ];
    }

    public function broadcastAs()
    {
        return 'critical.part';
    }

    public function broadcastWith()
    {
        $stock = $this->part->quantity_in_stock;
        $minStock = $this->part->min_stock_alert ?? 5;

        // ✅ Message plus spécifique selon le niveau de stock
        if ($this->isOutOfStock) {
            $title = ' RUPTURE TOTALE - ' . $this->part->name;
            $message = " URGENCE : {$this->part->name} (Réf: {$this->part->reference}) est épuisée ! Stock : 0 unité. Commande immédiate nécessaire.";
            $severity = 'critical';
        } elseif ($this->isLowStock) {
            $title = ' STOCK BAS - ' . $this->part->name;
            $message = " Attention : {$this->part->name} (Réf: {$this->part->reference}) est en stock très bas : {$stock} unité(s) restante(s) pour un seuil de {$minStock}.";
            $severity = 'warning';
        } else {
            $title = ' Mise à jour - ' . $this->part->name;
            $message = "La pièce {$this->part->name} (Réf: {$this->part->reference}) a été mise à jour. Stock actuel : {$stock} unités.";
            $severity = 'info';
        }

        return [
            'type'          => 'critical_part',
            'severity'      => $severity,
            'title'         => $title,
            'message'       => $message,
            'part_id'       => $this->part->id,
            'part_name'     => $this->part->name,
            'reference'     => $this->part->reference,
            'current_stock' => $stock,
            'min_stock_alert' => $minStock,
            'is_out_of_stock' => $this->isOutOfStock,
            'is_low_stock'  => $this->isLowStock,
            'timestamp'     => now()->toIso8601String(),
            'action_url'    => route('module5.parts.index'),
            'badge'         => $this->isOutOfStock ? 'URGENT' : 'ALERTE',
            'badge_color'   => $this->isOutOfStock ? 'np-tag-red' : 'np-tag-amber',
        ];
    }
}