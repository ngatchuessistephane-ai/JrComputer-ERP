<?php

namespace App\Models\Module2;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Supplier extends Model
{
    use LogsActivity;

    protected $table = 'suppliers';
    
    protected $fillable = [
        'name', 
        'code', 
        'contact_person', 
        'email', 
        'phone',
        'address', 
        'tax_number', 
        'payment_terms',
        'total_purchased', 
        'total_paid'
    ];

    protected $casts = [
        'payment_terms' => 'integer',
        'total_purchased' => 'decimal:2',
        'total_paid' => 'decimal:2',
    ];

    /**
     * Relation avec les bons de commande
     */
    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    /**
     * Relation avec les produits fournis
     */
    public function products()
    {
        return $this->hasMany(\App\Models\Module1\Product::class, 'supplier_id');
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
     * Calcule et met à jour le total acheté
     */
    public function refreshTotalPurchased(): void
    {
        $total = PurchaseOrder::where('supplier_id', $this->id)
            ->where('status', PurchaseOrder::STATUS_RECEIVED)
            ->sum('total');
        
        $this->update(['total_purchased' => $total]);
    }

    /**
     * Accesseur pour le total acheté formaté
     */
    public function getFormattedTotalPurchasedAttribute(): string
    {
        return number_format($this->total_purchased ?? 0, 0, ',', ' ') . ' FCFA';
    }

    /**
     * Accesseur pour la dette actuelle
     */
    public function getCurrentDebtAttribute(): float
    {
        return max(0, $this->total_purchased - $this->total_paid);
    }

    /**
     * Accesseur pour la dette formatée
     */
    public function getFormattedCurrentDebtAttribute(): string
    {
        return number_format($this->current_debt, 0, ',', ' ') . ' FCFA';
    }

    /**
     * Accesseur pour le délai de paiement formaté
     */
    public function getFormattedPaymentTermsAttribute(): string
    {
        return $this->payment_terms ? $this->payment_terms . ' jours' : '—';
    }

    /**
     * Scope pour les fournisseurs actifs (avec commandes)
     */
    public function scopeActive($query)
    {
        return $query->whereHas('purchaseOrders');
    }

    /**
     * Scope pour les fournisseurs avec dette
     */
    public function scopeWithDebt($query)
    {
        return $query->whereRaw('total_purchased > total_paid');
    }

    /**
     * Boot du modèle
     */
    protected static function boot()
    {
        parent::boot();

        // Générer automatiquement le code si vide
        static::creating(function ($supplier) {
            if (empty($supplier->code)) {
                $prefix = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $supplier->name), 0, 3));
                $count = self::where('code', 'LIKE', $prefix . '-%')->count() + 1;
                $supplier->code = $prefix . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
            }
        });

        // Après la suppression, mettre à jour les totaux des autres fournisseurs (optionnel)
        static::deleting(function ($supplier) {
            // Logique de suppression (optionnelle)
        });
    }
}