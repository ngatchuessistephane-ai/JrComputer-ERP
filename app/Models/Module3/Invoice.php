<?php

namespace App\Models\Module3;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Invoice extends Model
{
    use LogsActivity;

    protected $table = 'invoices';
    protected $fillable = [
        'reference', 'customer_id', 'quote_id', 'date', 'due_date', 'status',
        'subtotal', 'discount', 'tax', 'total', 'paid_amount', 'notes', 'created_by'
    ];

    protected $casts = [
        'date' => 'date',
        'due_date' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }

    public function getRemainingAmount()
{
    return $this->total - $this->paid_amount;
}

}