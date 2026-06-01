<?php

namespace App\Models\Module1;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

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
}