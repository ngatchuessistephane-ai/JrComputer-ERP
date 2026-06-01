<?php

namespace App\Models\Module2;

use Illuminate\Database\Eloquent\Model;

class SupplierInvoice extends Model
{
    protected $table = 'supplier_invoices';
    protected $fillable = [
        'purchase_order_id', 'invoice_number', 'invoice_date',
        'amount', 'status', 'due_date'
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }
}