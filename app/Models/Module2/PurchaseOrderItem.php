<?php

namespace App\Models\Module2;

use App\Models\Module1\Product;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class PurchaseOrderItem extends Model
{
    use LogsActivity;

    protected $table = 'purchase_order_items';
    
    protected $fillable = [
        'purchase_order_id', 
        'product_id', 
        'quantity_ordered',
        'quantity_received', 
        'unit_price', 
        'total'
    ];

    protected $casts = [
        'quantity_ordered' => 'integer',
        'quantity_received' => 'integer',
        'unit_price' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    /**
     * Relation avec le bon de commande parent
     */
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    /**
     * Relation avec le produit
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Calculer automatiquement le total avant sauvegarde
     */
    public static function boot()
    {
        parent::boot();

        static::saving(function ($item) {
            $item->total = $item->quantity_ordered * $item->unit_price;
        });
    }

    /**
     * Récupérer la quantité restante à recevoir
     */
    public function getRemainingQuantityAttribute()
    {
        return $this->quantity_ordered - ($this->quantity_received ?? 0);
    }

    /**
     * Vérifier si l'article est complètement reçu
     */
    public function isFullyReceived(): bool
    {
        return ($this->quantity_received ?? 0) >= $this->quantity_ordered;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }
}