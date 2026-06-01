<?php

namespace App\Models\Module3;

use App\Models\Module1\Product;
use Illuminate\Database\Eloquent\Model;

class QuoteItem extends Model
{
    protected $table = 'quote_items';
    protected $fillable = [
        'quote_id', 'product_id', 'quantity', 'unit_price', 'total'
    ];

    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}