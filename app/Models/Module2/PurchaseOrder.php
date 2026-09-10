<?php

namespace App\Models\Module2;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class PurchaseOrder extends Model
{
    use LogsActivity;

    protected $table = 'purchase_orders';
    
    protected $fillable = [
        'reference', 
        'supplier_id', 
        'order_date', 
        'expected_delivery_date',
        'status', 
        'subtotal', 
        'discount', 
        'tax', 
        'total', 
        'notes', 
        'created_by'
    ];

    protected $casts = [
        'order_date' => 'date',
        'expected_delivery_date' => 'date',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    /**
     * Statuts possibles pour un bon de commande
     */
    const STATUS_DRAFT = 'draft';
    const STATUS_SENT = 'sent';
    const STATUS_PARTIALLY_RECEIVED = 'partially_received';
    const STATUS_RECEIVED = 'received';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Liste des statuts pour les sélecteurs
     */
    public static $statuses = [
        self::STATUS_DRAFT => 'Brouillon',
        self::STATUS_SENT => 'Envoyé',
        self::STATUS_PARTIALLY_RECEIVED => 'Partiellement reçu',
        self::STATUS_RECEIVED => 'Reçu',
        self::STATUS_CANCELLED => 'Annulé',
    ];

    /**
     * Relation avec le fournisseur
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Relation avec les lignes du bon de commande
     */
    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    /**
     * Relation avec l'utilisateur qui a créé le bon de commande
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Configuration de l'activity log
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * Calcule le total du bon de commande à partir des items
     */
    public function calculateTotal(): void
    {
        $subtotal = $this->items->sum(function ($item) {
            return $item->quantity * $item->unit_price;
        });

        $this->subtotal = $subtotal;
        $this->total = $subtotal - $this->discount + $this->tax;
        
        $this->saveQuietly();
    }

    /**
     * Vérifie si le bon de commande peut être réceptionné
     */
    public function canBeReceived(): bool
    {
        return in_array($this->status, [self::STATUS_SENT, self::STATUS_PARTIALLY_RECEIVED]);
    }

    /**
     * Marque le bon de commande comme réceptionné
     */
    public function markAsReceived(): void
    {
        if ($this->canBeReceived()) {
            $this->status = self::STATUS_RECEIVED;
            $this->save();
            
            // Mettre à jour le total acheté du fournisseur
            $this->updateSupplierTotalPurchased();
        }
    }

    /**
     * Marque le bon de commande comme partiellement réceptionné
     */
    public function markAsPartiallyReceived(): void
    {
        if ($this->status === self::STATUS_SENT) {
            $this->status = self::STATUS_PARTIALLY_RECEIVED;
            $this->save();
        }
    }

    /**
     * Met à jour le total acheté du fournisseur
     */
    public function updateSupplierTotalPurchased(): void
    {
        if ($this->supplier) {
            // Calculer le total des commandes reçues pour ce fournisseur
            $totalPurchased = self::where('supplier_id', $this->supplier_id)
                ->where('status', self::STATUS_RECEIVED)
                ->sum('total');
            
            // Mettre à jour le fournisseur
            $this->supplier->update([
                'total_purchased' => $totalPurchased
            ]);
        }
    }

    /**
     * Accesseur pour le montant total formaté
     */
    public function getFormattedTotalAttribute(): string
    {
        return number_format($this->total, 0, ',', ' ') . ' FCFA';
    }

    /**
     * Accesseur pour le sous-total formaté
     */
    public function getFormattedSubtotalAttribute(): string
    {
        return number_format($this->subtotal, 0, ',', ' ') . ' FCFA';
    }

    /**
     * Scope pour les commandes en attente
     */
    public function scopePending($query)
    {
        return $query->whereIn('status', [self::STATUS_SENT, self::STATUS_PARTIALLY_RECEIVED]);
    }

    /**
     * Scope pour les commandes reçues
     */
    public function scopeReceived($query)
    {
        return $query->where('status', self::STATUS_RECEIVED);
    }

    /**
     * Boot du modèle
     */
    protected static function boot()
    {
        parent::boot();

        // Générer automatiquement la référence
        static::creating(function ($purchaseOrder) {
            if (empty($purchaseOrder->reference)) {
                $purchaseOrder->reference = 'BC-' . date('Ymd') . '-' . str_pad(random_int(1, 9999), 4, '0', STR_PAD_LEFT);
            }
        });
    }
}