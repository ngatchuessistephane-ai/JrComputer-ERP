<?php

namespace App\Models\Module3;

use App\Models\Module1\Product;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $table = 'invoice_items';
    
    protected $fillable = [
    'invoice_id', 'product_id', 'description', 'quantity', 'unit_price', 'total'
];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}