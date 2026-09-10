<?php

namespace App\Models\Module5;

use Illuminate\Database\Eloquent\Model;
use App\Models\Module5\SavTicket; 
use App\Models\Module5\SparePart;
class TicketItem extends Model
{
    protected $table = 'ticket_items';
    protected $fillable = ['ticket_id', 'spare_part_id', 'quantity', 'unit_price'];

    public function ticket()
    {
         return $this->belongsTo(SavTicket::class, 'ticket_id');
    }

    public function sparePart()
    {
        return $this->belongsTo(SparePart::class);
    }
}