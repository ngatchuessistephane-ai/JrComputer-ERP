<?php

namespace App\Models\Module1;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Models\User;
use App\Events\LowStockAlert;
use App\Notifications\LowStockNotification;
use App\Services\ActivityLoggerService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use LogsActivity;

    protected $table = 'products';
    protected $fillable = [
        'name', 'reference', 'serial_number', 'description',
        'purchase_price', 'selling_price', 'quantity',
        'alert_threshold', 'category', 'supplier'
    ];

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    public function isLowStock(): bool
    {
        return $this->quantity <= $this->alert_threshold;
    }

    /**
     * ✅ VÉRIFICATION DIRECTE - Force l'envoi des notifications
     */
    public function forceSendLowStockAlert(): void
    {
        Log::info('🔔 FORCE: Envoi alerte stock bas pour ' . $this->name);
        
        try {
            // 1. Événement broadcast (WebSocket)
            event(new LowStockAlert($this));
            Log::info('✅ Événement LowStockAlert broadcasté');

            // 2. Récupérer les admins et managers
            $users = User::whereHas('roles', function ($q) {
                $q->whereIn('name', ['admin', 'manager']);
            })->get();
            
            Log::info('👥 Utilisateurs trouvés: ' . $users->count());

            // 3. Données de la notification
            $data = [
                'type' => 'low_stock',
                'title' => "Stock bas - {$this->name}",
                'message' => "Stock bas : {$this->name} (plus que {$this->quantity} unités, seuil: {$this->alert_threshold})",
                'product_id' => $this->id,
                'product_name' => $this->name,
                'current_quantity' => $this->quantity,
                'alert_threshold' => $this->alert_threshold,
                'reference' => $this->reference,
                'action_url' => route('module1.products.index'),
                'action_links' => [
                    ['label' => 'Voir le stock', 'url' => route('module1.products.index')],
                    ['label' => 'Commander', 'url' => route('module2.purchase-orders.index')],
                ],
                'priority' => $this->quantity <= 3 ? 'high' : 'normal',
            ];

            // 4. Envoyer les notifications à chaque utilisateur
            foreach ($users as $user) {
                try {
                    // Vérifier si une notification existe déjà
                    $existing = $user->notifications()
                        ->where('data->type', 'low_stock')
                        ->where('data->product_id', $this->id)
                        ->whereNull('read_at')
                        ->first();
                    
                    if (!$existing) {
                        $user->notify(new LowStockNotification($this, $data));
                        Log::info('✅ Notification envoyée à ' . $user->email);
                    } else {
                        Log::info('⏭️ Notification déjà existante pour ' . $user->email);
                    }
                } catch (\Exception $e) {
                    Log::error('❌ Erreur envoi à ' . $user->email . ': ' . $e->getMessage());
                }
            }

            // 5. Enregistrer dans system_activities
            try {
                $activityLogger = app(ActivityLoggerService::class);
                $activityLogger->log(
                    type: 'low_stock',
                    action: 'alert',
                    entityType: 'product',
                    entityId: $this->id,
                    data: [
                        'product_name' => $this->name,
                        'reference' => $this->reference,
                        'current_quantity' => $this->quantity,
                        'alert_threshold' => $this->alert_threshold,
                        'category' => $this->category,
                        'supplier' => $this->supplier,
                        'title' => "Stock bas - {$this->name}",
                        'message' => "Quantité : plus que {$this->quantity} unités, seuil: {$this->alert_threshold}",
                        'action_url' => route('module1.products.index'),
                    ],
                    priority: $this->quantity <= 3 ? 'high' : 'normal',
                    actionLinks: [
                        ['label' => 'Voir le stock', 'url' => route('module1.products.index')],
                        ['label' => 'Commander', 'url' => route('module2.purchase-orders.index')],
                    ]
                );
                Log::info('✅ Activité enregistrée dans system_activities');
            } catch (\Exception $e) {
                Log::error('❌ Erreur system_activities: ' . $e->getMessage());
            }

        } catch (\Exception $e) {
            Log::error('❌ Erreur générale: ' . $e->getMessage());
        }
    }

    /**
     * Boot du modèle
     */
    protected static function booted()
    {
        // Après mise à jour
        static::updated(function ($product) {
            Log::info('🔄 Product updated: ' . $product->name . ' - Qty: ' . $product->quantity);
            
            if ($product->wasChanged('quantity') && $product->isLowStock()) {
                Log::info('🔔 Stock bas détecté pour ' . $product->name);
                $product->forceSendLowStockAlert();
            }
        });

        // À la création
        static::created(function ($product) {
            Log::info('🆕 Product created: ' . $product->name . ' - Qty: ' . $product->quantity);
            if ($product->isLowStock()) {
                $product->forceSendLowStockAlert();
            }
        });
    }

    /**
     * Vérification manuelle de tous les produits
     */
    public static function checkAllLowStock(): void
    {
        $products = self::whereRaw('quantity <= alert_threshold')->get();
        Log::info('🔍 Vérification globale stock bas: ' . $products->count() . ' produits trouvés');
        
        foreach ($products as $product) {
            $product->forceSendLowStockAlert();
        }
    }
}