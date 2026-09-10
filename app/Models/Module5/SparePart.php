<?php

namespace App\Models\Module5;

use App\Models\Module1\StockMovement;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class SparePart extends Model
{
    use LogsActivity;

    protected $table = 'spare_parts';
    protected $fillable = [
        'part_number', 'name', 'compatibility', 'purchase_price',
        'selling_price', 'quantity_in_stock', 'min_stock_alert'
    ];

    public function ticketItems()
    {
        return $this->hasMany(TicketItem::class);
    }

    public function stockMovements()
    {
        return $this->morphMany(StockMovement::class, 'stockable');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }

    // ✅ Vérifier si le stock est suffisant
    public function hasSufficientStock(int $quantity): bool
    {
        return $this->quantity_in_stock >= $quantity;
    }

    // ✅ Déduire du stock avec validation
    public function deductStock(int $quantity): bool
    {
        if (!$this->hasSufficientStock($quantity)) {
            return false;
        }
        
        $this->quantity_in_stock -= $quantity;
        $this->save();
        
        return true;
    }

    // ✅ Ajouter au stock
    public function addStock(int $quantity): void
    {
        $this->quantity_in_stock += $quantity;
        $this->save();
    }

    // ✅ Vérifier si le stock est critique (≤ seuil)
    public function isLowStock(): bool
    {
        return $this->quantity_in_stock <= $this->min_stock_alert;
    }

    // ✅ Vérifier si le stock est épuisé
    public function isOutOfStock(): bool
    {
        return $this->quantity_in_stock <= 0;
    }

    // ✅ Méthode utilitaire pour décrémenter le stock (avec exception)
    public function decreaseStock($quantity, $reason, $userId)
    {
        if (!$this->hasSufficientStock($quantity)) {
            throw new \Exception("Stock insuffisant pour la pièce {$this->name}. Disponible: {$this->quantity_in_stock}, Demandé: {$quantity}");
        }
        
        $this->quantity_in_stock -= $quantity;
        $this->save();

        StockMovement::create([
            'spare_part_id' => $this->id,
            'type' => 'out',
            'quantity' => $quantity,
            'reason' => $reason,
            'user_id' => $userId,
        ]);
    }
}