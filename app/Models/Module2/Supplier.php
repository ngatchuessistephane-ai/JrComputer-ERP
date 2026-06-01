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
        'name', 'code', 'contact_person', 'email', 'phone',
        'address', 'tax_number', 'payment_terms',
        'total_purchased', 'total_paid'
    ];

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }
}