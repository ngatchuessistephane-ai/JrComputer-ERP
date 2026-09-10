<?php

namespace App\Notifications;

use App\Models\Module1\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class LowStockNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Product $product;
    protected array $data;

    public function __construct(Product $product, array $data = [])
    {
        $this->product = $product;
        $this->data = $data;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable)
    {
        // Utiliser les données passées ou les construire
        if (!empty($this->data)) {
            return $this->data;
        }
        
        return [
            'type' => 'low_stock',
            'title' => "Stock bas - {$this->product->name}",
            'message' => "Quantité : plus que {$this->product->quantity} unités, seuil: {$this->product->alert_threshold}",
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'current_quantity' => $this->product->quantity,
            'alert_threshold' => $this->product->alert_threshold,
            'action_url' => route('module1.products.index'),
            'action_links' => [
                ['label' => 'Voir le stock', 'url' => route('module1.products.index')],
                ['label' => 'Commander', 'url' => route('module2.purchase-orders.index')],
            ],
            'priority' => $this->product->quantity <= 3 ? 'high' : 'normal',
        ];
    }

    public function toBroadcast($notifiable)
    {
        return [
            'data' => $this->toDatabase($notifiable),
        ];
    }
}