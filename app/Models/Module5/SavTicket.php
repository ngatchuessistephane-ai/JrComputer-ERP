<?php

namespace App\Models\Module5;

use App\Models\Module3\Customer;
use App\Models\Module1\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class SavTicket extends Model
{
    use LogsActivity;

    protected $table = 'sav_tickets';
    protected $fillable = [
        'ticket_number', 
        'customer_id', 
        'product_id', 
        'serial_number',
        'device_model', 
        'description_failure', 
        'status', 
        'priority',
        'assigned_to', 
        'is_warranty', 
        'warranty_end_date',
        'technical_report',    // ✅ AJOUTÉ
        'duration_minutes',    // ✅ AJOUTÉ
        'closed_at',           // ✅ AJOUTÉ
        'created_by',          // ✅ AJOUTÉ
        'labor_cost',          // ✅ NOUVEAU : montant main d'œuvre saisi par admin
        'diagnostic_fee',      // ✅ NOUVEAU : frais de diagnostic
        'invoice_id',          // ✅ NOUVEAU : lien vers la facture générée
    ];

    protected $casts = [
        'is_warranty' => 'boolean',
        'warranty_end_date' => 'date',
        'closed_at' => 'datetime',
        'duration_minutes' => 'integer',
        'labor_cost' => 'decimal:2',
        'diagnostic_fee' => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function items()
    {
        return $this->hasMany(TicketItem::class, 'ticket_id');
    }

    public function intervention()
    {
        return $this->hasOne(Intervention::class, 'ticket_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function invoice()
    {
        return $this->belongsTo(\App\Models\Module3\Invoice::class, 'invoice_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }
}