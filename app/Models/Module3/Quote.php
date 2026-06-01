<?php

namespace App\Models\Module3;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Quote extends Model
{
    use LogsActivity;

    protected $table = 'quotes';
    protected $fillable = [
        'reference', 'customer_id', 'date', 'valid_until', 'status',
        'subtotal', 'discount', 'tax', 'total', 'notes', 'created_by'
    ];

    protected $casts = [
        'date' => 'date',
        'valid_until' => 'date',
        'status' => 'string',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(QuoteItem::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }
}