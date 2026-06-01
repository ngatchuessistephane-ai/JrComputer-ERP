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

    // Méthode utilitaire pour décrémenter le stock
    public function decreaseStock($quantity, $reason, $userId)
    {
        if ($this->quantity_in_stock < $quantity) {
            throw new \Exception("Stock insuffisant pour la pièce {$this->name}");
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