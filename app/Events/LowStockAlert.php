<?php

namespace App\Events;

use App\Models\Module1\Product;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class LowStockAlert implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Product $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
        Log::info('🔔 LowStockAlert créé pour: ' . $product->name);
    }

    public function broadcastOn()
    {
        return [
            new Channel('private-admin'),
            new Channel('private-manager'),
        ];
    }

    public function broadcastAs()
    {
        return 'low.stock';
    }

    public function broadcastWith()
    {
        return [
            'type' => 'low_stock',
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'reference' => $this->product->reference,
            'current_quantity' => (int) $this->product->quantity,
            'alert_threshold' => (int) $this->product->alert_threshold,
            'title' => "Stock bas - {$this->product->name}",
            'message' => "Quantité : plus que {$this->product->quantity} unités, seuil: {$this->product->alert_threshold}",
            'action_url' => route('module1.products.index'),
            'action_links' => [
                ['label' => 'Voir le stock', 'url' => route('module1.products.index')],
                ['label' => "<p style='color:green, font-size:7px'>Commander</p>", 'url' => route('module2.purchase-orders.index')],
            ],
            'priority' => $this->product->quantity <= 3 ? 'high' : 'normal',
            'created_at' => now()->toIso8601String(),
        ];
    }
}